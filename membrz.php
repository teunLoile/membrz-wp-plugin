<?php

/*
 * Plugin Name:       Membrz Plugin
 * Plugin URI:        https://github.com/memrz-plugin
 * Description:       The membrz plugin
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://github.com/teunrompa
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

 function mb_init_menu(){
    add_menu_page('Membrz Plugin', 'Membrz Plugin', 'administrator', 'mbr_admin', 'mb_options_page_html');
 }

 //TODO: remove after testing
 function mb_create_post_type(){
    register_post_type('mb_event', array(
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => 'mbr_admin',
        'show_in_admin_bar ' => true,
    ));
 }

 function mb_add_meta_box(){
    add_meta_box('mb_box_id', 'Events metabox', 'mb_event_meta_html', 'mb_event');
 }

function mb_event_meta_html($post){
    $value = get_post_meta($post->ID, 'name');
    ?> 
        <div><h3><?=$value?></h3></div>
    <?php
}

 add_action('add_meta_boxes', 'mb_add_meta_box');

function mb_register_custom_post_type_menu(){
    add_submenu_page('edit.php?=mb_event', 'Events', 'Submenu', 'manage_options', 'events-submenu', 'events_html');
}

add_action('admin_menu', 'mb_register_custom_post_type_menu');

function events_html(){
    echo "<h1> Test </h1>";
}

 function mb_activate(){
    add_option('mb_url_congig');
 }

register_activation_hook(__FILE__, 'mb_activate');

register_uninstall_hook(__FILE__, 'mb_deactivate_plugin');

function mb_deactivate_plugin(){
    delete_option('mb_url_config');
}

if($_POST && $url_location = $_POST['mb_dashboard_host']){
    update_option('mb_url_config', $url_location);  
}

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



    return new WP_REST_Response($response, 200);
}


function mb_options_page_html() {
    $post_types = get_post_types();
    ?>
    <div class="wrap">
      <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
      <form action="<?= admin_url('admin.php?page=mbr_admin') ?>" method="post">
        <div>
        <label for="mb_dashboard_host" >Admininstrator dashboard host url</label> 
        <input type="text" name="mb_dashboard_host" value="<?=get_option('mb_url_config') ? get_option('mb_url_config') : ''?>">
        </div>
        <div>
            <label for="mb_post_location">Target posts to add</label>
            <select name="mb_post_location" id="select-post-field">
                <?php foreach($post_types as $post_type) : ?>
                <option value="<?=$post_type?>"><?=$post_type?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
            submit_button('Save Settings');
        ?>
      </form>
    </div>
    <?php
}



 add_action('admin_menu', 'mb_init_menu');
?>