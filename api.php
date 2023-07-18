<?php
//POST route
add_action('rest_api_init', function() {
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
add_action('rest_api_init', function() {
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

function handle_membrz_update(WP_REST_Request $request) : WP_REST_Response {
    // Process data
    $data = $request->get_body();
    $data = json_decode($data, true);

    $event_id_to_find = $data['event_id']; // Get the event ID

    // Query the post with meta data matching the specified ID
    $args = array(
        'post_type' => 'mb_event',  
        'meta_query' => array(
            array(
                'key' => 'event_id', 
                'value' => $event_id_to_find 
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

            // Retrieve the current post data
            $post_data = get_post(get_the_ID());

            // Update the post fields
            $post_data->post_title = $data['name'];
            $post_data->post_content = '';

            // Save the updated post
            wp_update_post($post_data);
        }
    } else {
        return new WP_REST_Response('Post not found', 404);
    }

    return new WP_REST_Response(['message' => "Updated successfully"], 200);
}

function handle_membrz_post(WP_REST_Request $request): WP_REST_Response {
    $data = $request->get_body();
    $data = json_decode($data, true);

    if (!is_array($data)) {
        return new WP_REST_Response('Data is not an array');
    }

    $response = array('message' => 'Data arrived successfully', 'data' => $data);

    $post_data = array(
        'post_title' => $data['name'],
        'post_type' => 'mb_event',
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        foreach ($data as $key => $value) {
            if ($key !== 'name') {
                update_post_meta($post_id, $key, $value);
            }
        }
    } else {
        return new WP_REST_Response('Failed to create post', 405);
    }

    return new WP_REST_Response($response, 200);
}



?>