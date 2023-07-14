<?php
add_action('rest_api_init', function() {
    register_rest_route('membrz-post-endpoint', '/membrz-post-endpoint', array(
        'methods' => 'POST',
        'callback' => 'handle_membrz_post',
        'permission_callback' => function () {
            // Allow requests from any origin
            //TODO implement it so that it only allows $host 
            header("Access-Control-Allow-Origin: *");
            return true;
        },
    )); 
});

add_action('rest_api_init', function() {
    register_rest_route('membrz-post-endpoint', '/membrz-post-endpoint', array(
        'methods' => 'GET',
        'callback' => 'handle_membrz_post',
        'permission_callback' => function () {
            // Allow requests from any origin
            //TODO implement it so that it only allows $host 
            header("Access-Control-Allow-Origin: *");
            return true;
        },
    )); 
});

function handle_membrz_post($request){
    $data = $request->get_body();
    $data = json_decode($data, true);

    $response = array('message' => 'Data arvied succesfully', 'data' => $data);

    $post_data = array(
        'post_title' => $data['name'],
        'post_type' => 'mb_event',
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if($post_id){
        foreach($data as $key => $value){
            if($key) update_post_meta($post_id, $key, $value);
            else return new WP_REST_Response("Invalid post", 405);
        }
    }
    else{
        return new WP_REST_Response('No post id', 405);
    }

    return new WP_REST_Response($response, 200);
}

?>