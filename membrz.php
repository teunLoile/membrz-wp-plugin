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

if($url = get_option('mb_url_config')){
    $data = ['collection' => 'Membrz'];

    $curl = curl_init($url);

    $host = get_site_url();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'X-RapidAPI-Host: ' . $host,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    echo $response;
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