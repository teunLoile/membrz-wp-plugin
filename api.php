<?php

//Api Routes events

//POST route
add_action('rest_api_init', function () {
    $access_control_origin = get_option('mb_url_config') ?? '*';
    register_rest_route('membrz-event', '/membrz-post-endpoint', array(
        'methods' => 'POST',
        'callback' => 'handle_membrz_post',
        'permission_callback' => function () use ($access_control_origin) {
            header("Access-Control-Allow-Origin: " . $access_control_origin);
            return true;
        },
    ));
});
//PUT / UPDATE route
add_action('rest_api_init', function () {
    $access_control_origin = get_option('mb_url_config') ??  '*';

    register_rest_route('membrz-event', '/membrz-update-endpoint', array(
        'methods' => 'PUT',
        'callback' => 'handle_membrz_update',
        'permission_callback' => function () use ($access_control_origin) {
            header("Access-Control-Allow-Origin: " . $access_control_origin);
            return true;
        },
    ));
});

//DELETE route
add_action('rest_api_init', function () {
    //TODO: should not be * when we cant find a configured url might be a security vunerability
    $access_control_origin = get_option('mb_url_config') ??  '*';

    register_rest_route('membrz-event', '/membrz-delete-endpoint', array(
        'methods' => 'DELETE',
        'callback' => 'handle_membrz_delete',
        'permission_callback' => function () use ($access_control_origin) {
            header("Access-Control-Allow-Origin: " . $access_control_origin);
            return true;
        }
    ));
});

function handle_membrz_delete(WP_REST_Request $request): WP_REST_Response
{
    $data = $request->get_json_params();

    $event_id = $data['event_id'];

    if (!$event_id) return new WP_REST_Response('no event id set ', 405);

    $args = array(
        'post_type' => 'events',
        'meta_query' => array(
            array(
                'key' => 'event_id',
                'value' => $event_id
            )
        ),
    );

    $posts = new WP_Query($args);

    if (!$posts) return new WP_REST_Response('Failed to find post', 405);

    $result = null;
    if ($posts->have_posts()) {
        while ($posts->have_posts()) {
            $posts->the_post();

            $result = wp_delete_post(get_the_ID(), true);
            if ($result === false || $result === null) return new WP_REST_Response('Failed to remove post', 405);
        }
    }

    return new WP_REST_Response(array("message" => 'Post succesfully removed', 'result' => $result), 202);
}

function handle_membrz_update(WP_REST_Request $request): WP_REST_Response
{
    // Process data
    $data = $request->get_body();
    $data = json_decode($data, true);

    $event_id_to_find = $data['event_id']; // Get the event ID

    // Query the post with meta data matching the specified ID
    $args = array(
        'post_type' => 'events',
        'meta_query' => array(
            array(
                'key' => 'event_id',
                'value' => $event_id_to_find,
                'compare' => '=',
            )
        )
    );
    $posts = new WP_Query($args);

    if (!$posts) {
        return new WP_REST_Response('Failed to find post', 405);
    }

    // Check if the post was found
    if ($posts->have_posts()) {
        while ($posts->have_posts()) {
            $posts->the_post();

            $post_id = get_the_ID();

            // Retrieve the current post data
            $post_data = get_post();

            // Update the post fields
            $post_data->post_title = $data['name'];

            update_post_meta($post_id, 'image_url', $data['image_url']);
            update_post_meta($post_id, 'start_date', $data['start_date']);
            update_post_meta($post_id, 'end_date', $data['end_date']);
            update_post_meta($post_id, 'begin_time', $data['begin_time']);
            update_post_meta($post_id, 'end_time', $data['end_time']);
            update_post_meta($post_id, 'location', $data['location']);
            update_post_meta($post_id, 'description', $data['description']);

            // Save the updated post
            wp_update_post($post_data);
        }
    } else {
        return new WP_REST_Response(['message' => 'Post not found ',  'data' => $data], 404);
    }

    return new WP_REST_Response(['message' => "Updated successfully"], 200);
}

function handle_membrz_post(WP_REST_Request $request): WP_REST_Response
{
    $data = $request->get_body();
    $data = json_decode($data, true);

    if (!is_array($data)) {
        return new WP_REST_Response('Data is not an array');
    }

    $post_data = array(
        'post_title' => $data['name'],
        'post_type' => 'events',
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id === 0) return new WP_REST_Response('Failed to create post', 500);

    foreach ($data as $key => $value) {
        if ($key !== 'name') {
            $result = update_post_meta($post_id, $key, $value);
            if ($result === false) {
                return new WP_REST_Response('Failed to update post meta field: ' . $key . ' value: ' . $value, 500);
            }
        }
    }

    $response = array('message' => 'Data arrived successfully', 'data' => $data, 'post_id' => $post_id);
    return new WP_REST_Response($response, 200);
}

//Api routes comission a.k.a groups 

add_action('rest_api_init', function () {
    $access_control_origin = get_option('mb_url_config') ?? '*';
    register_rest_route('membrz-group', 'membrz-post-endpoint', array(
        'methods' => 'POST',
        'callback' => 'handle_membrz_group_post',
        'permission_callback' => function () use ($access_control_origin) {
            header("Access-Control-Allow-Origin: " . $access_control_origin);
            return true;
        },
    ));
});

add_action('rest_api_init', function () {
    $access_control_origin = get_option('mb_url_config') ?? '*';
    register_rest_route('membrz-group', 'membrz-update-endpoint', array(
        'methods' => 'PUT',
        'callback' => 'handle_membrz_group_update',
        'permission_callback' => function () use ($access_control_origin) {
            header("Access-Control-Allow-Origin: " . $access_control_origin);
            return true;
        },
    ));
});


add_action('rest_api_init', function () {
    $access_control_origin = get_option('mb_url_config') ?? '*';
    register_rest_route('membrz-group', 'membrz-delete-endpoint', array(
        'methods' => 'DELETE',
        'callback' => 'handle_membrz_group_delete',
        'permission_callback' => function () use ($access_control_origin) {
            header("Access-Control-Allow-Origin: " . $access_control_origin);
            return true;
        },
    ));
});

function handle_membrz_group_post(WP_REST_Request $request): WP_REST_Response
{
    $data = $request->get_body();
    $data = json_decode($data, true);

    $response = new WP_REST_Response("Succesfully created group", 200);

    if (!is_array($data)) {
        $response->data = "Data was not set";
        $response->status = 405;
        return $response;
    }

    $post_data = array(
        'post_title' => $data['name'],
        'post_type' => 'mb_groups',
        'post_status' => 'publish'
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        foreach ($data as $key => $value) {
            if ($key !== 'name') {
                update_post_meta($post_id, $key, $value);
            }
        }
    } else {
        $response->data = "There was an error trying to create this post";
        $response->status = 405;
    }

    return $response;
}

function handle_membrz_group_delete(WP_REST_Request $request): WP_REST_Response
{
    $data = $request->get_body();
    $data = json_decode($data, true);

    $response = new WP_REST_Response('Succesfully removed group', 200);

    if (!is_array($data)) {
        $response->data = "ERROR: Data was empty data: " . $data;
        $response->status = 402;
        return $response;
    }

    $group_id = $data['group_id'];

    $args = array(
        'post_type' => 'mb_groups',
        'meta_query' => array(
            array(
                'key' => 'group_id',
                'value' => $group_id
            )
        ),
    );

    $post = new WP_Query($args);

    $result = null;

    if ($post->have_posts()) {
        while ($post->have_posts()) {
            $post->the_post();
            $result = wp_delete_post(get_the_ID(), true);
        }
    }

    $response->data = $result;

    return $response;
}
