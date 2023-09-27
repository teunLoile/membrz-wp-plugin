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

include_once __DIR__ . "/api.php";

function mb_init_menu()
{
    add_menu_page('Membrz Plugin', 'Membrz Plugin', 'administrator', 'mbr_landing', 'mb_options_page_html');
}

function mb_options_page_html()
{
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="<?= admin_url('admin.php?page=mbr_landing') ?>" method="post">
            <div>
                <label for="mb_dashboard_host">Admininstrator dashboard host url</label>
                <input type="text" name="mb_dashboard_host" value="<?= get_option('mb_url_config') ? get_option('mb_url_config') : '' ?>">
            </div>
            <div>
                <label for="mb_backend_url">Enter the backend url</label>
                <input type="text" name="mb_backend_url" value="<?= get_option('mb_backend_url') ? get_option('mb_backend_url') : '' ?>">
            </div>
            <?php
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'mb_init_menu');

//TODO: remove after testing
function mb_create_post_type()
{
<<<<<<< HEAD
    create_events_postype();
    create_groups_postype();
    add_shortcode('mb_event_list', 'mb_display_event_list');
}

add_action('init', 'mb_create_post_type');

function create_events_postype()
{
=======
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
    register_post_type('events', array(
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => 'mbr_admin',
        'show_in_admin_bar ' => true,
        'fields' => array(
            'event_id' => array(
                'type' => 'NUMERIC',
                'label' => 'Event Id'
            ),
            'image_url' => array(
                'type' => 'text',
                'label' => 'Image url',
            ),
            'name' => array(
                'type' => 'text',
                'label' => 'Name',
            ),
            'start_date' => array(
                'type' => 'date',
                'label' => 'Start date',
            ),
            'end_date' => array(
                'type' => 'date',
                'label' => 'End date',
            ),
            'begin_time' => array(
                'type' => 'time',
                'label' => 'Begin time',
            ),
            'end_time' => array(
                'type' => 'time',
                'label' => 'End time',
            ),
            'location' => array(
                'type' => 'text',
                'label' => 'Location',
            ),
            'description' => array(
                'type' => 'text',
                'label' => 'Description',
            )
        )
    ));
<<<<<<< HEAD
=======

    add_shortcode('mb_event_list', 'mb_display_event_list');
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
}


<<<<<<< HEAD
function create_groups_postype()
{
    register_post_type('mb_groups', array(
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => 'mbr_admin',
        'show_in_admin_bar ' => true,
        'fields' => array(
            'group_id' => array(
                'type' => 'NUMERIC',
                'label' => 'Event Id'
            ),
            'name' => array(
                'type' => 'text',
                'label' => 'Name',
            ),
            'description' => array(
                'type' => 'text',
                'label' => 'Description',
            )
        )
    ));
}

=======
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
function mb_register_custom_post_type_menu()
{
    add_submenu_page('mbr_landing', 'Events', 'Events', 'administrator', 'events-submenu', 'events_html');
    add_submenu_page('mbr_landing', "Commisions", 'Commisions', 'administrator', 'groups-submenu', 'groups_html');
}



add_action('admin_menu', 'mb_register_custom_post_type_menu');

function events_html()
{
    // Query the CPT posts
    $args = array(
        'post_type' => 'events', // Replace 'mb_event' with your CPT slug
        'posts_per_page' => -1 // Retrieve all posts
    );
    $posts = new WP_Query($args);

    // Display the posts in the submenu
    if ($posts->have_posts()) {
    ?> <div class="post-item">
            <?php
<<<<<<< HEAD


=======
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
            echo '<h1>Events</h1>';
            while ($posts->have_posts()) {
                $posts->the_post();

                echo '<a href=' . get_post_permalink() . '><h2>' . get_the_title() . '</h2></a>';
                // Display other post details as needed
            }
            ?> </div>
<?php
<<<<<<< HEAD
        wp_reset_postdata();
    } else {
        echo '<p>No posts found.</p>';
    }
}

function groups_html()
{
    echo "<h1>Groups</h1>";

    $args = array(
        'post_type' => 'mb_groups',
        'posts_per_page' => -1,
    );

    $posts = new WP_Query($args);

    if ($posts->have_posts()) {
        while ($posts->have_posts()) {
            $posts->the_post();
            echo '<a href=' . get_post_permalink() . '><h2>' . get_the_title() . '</h2></a>';
        }

=======
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
        wp_reset_postdata();
    }
}

// Register custom template for single 'mb_event' posts
function mb_event_single_template($template)
{
<<<<<<< HEAD
    if (is_singular('mb_event')) {
        $new_template = plugin_dir_path(__FILE__) . 'single-mb_event.php';
=======
    if (is_singular('events')) {
        $new_template = plugin_dir_path(__FILE__) . 'single-events.php';
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'mb_event_single_template');

<<<<<<< HEAD

// Register custom template for single 'mb_event' posts
function mb_group_single_template($template)
{
    if (is_singular('mb_group')) {
        $new_template = plugin_dir_path(__FILE__) . 'single-mb_group.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'mb_group_single_template');

=======
>>>>>>> c6dedb64296d41c17cd442e44840b7d01b977dd1
function mb_display_event_list($atts)
{
    // Shortcode attributes (if you need any)
    $atts = shortcode_atts(
        array(
            'limit' => -1, // Default value to show all events
        ),
        $atts
    );

    // Query the events
    $args = array(
        'post_type' => 'events',
        'post_status' => array('publish', 'archive'),
        'posts_per_page' => $atts['limit'],
    );
    $events_query = new WP_Query($args);

    // Output the events
    $output = '<div class="mb-event-list">';
    if ($events_query->have_posts()) {
        while ($events_query->have_posts()) {
            $events_query->the_post();
            // Get the custom fields for each event
            $event_id = get_post_meta(get_the_ID(), 'event_id', true);
            $image_url = get_post_meta(get_the_ID(), 'image_url', true);
            $name = get_post_meta(get_the_ID(), 'name', true);
            $start_date = get_post_meta(get_the_ID(), 'start_date', true);
            $end_date = get_post_meta(get_the_ID(), 'end_date', true);
            $begin_time = get_post_meta(get_the_ID(), 'begin_time', true);
            $end_time = get_post_meta(get_the_ID(), 'end_time', true);
            $location = get_post_meta(get_the_ID(), 'location', true);
            $description = get_post_meta(get_the_ID(), 'description', true);

            // Build the event output
            $output .= '<div class="mb-event">';
            $output .= '<h2>' . $name . '</h2>';
            $output .= '<p><strong>Event ID:</strong> ' . $event_id . '</p>';
            $output .= '<img src="' . $image_url . '" alt="' . $name . '">';
            $output .= '<p><strong>Date:</strong> ' . $start_date . ' to ' . $end_date . '</p>';
            $output .= '<p><strong>Time:</strong> ' . $begin_time . ' to ' . $end_time . '</p>';
            $output .= '<p><strong>Location:</strong> ' . $location . '</p>';
            $output .= '<p><strong>Description:</strong> ' . $description . '</p>';
            $output .= '</div>';
        }
    } else {
        $output .= '<p>No events found.</p>';
    }
    $output .= '</div>';

    // Restore original post data
    wp_reset_postdata();

    return $output;
}

function mb_activate()
{
    add_option('mb_url_config');
}

register_activation_hook(__FILE__, 'mb_activate');

register_uninstall_hook(__FILE__, 'mb_deactivate_plugin');

function mb_deactivate_plugin()
{
    delete_option('mb_url_config');
}

if ($_POST && isset($_POST['mb_dashboard_host'])) {
    update_option('mb_url_config', $_POST['mb_dashboard_host']);
}
if ($_POST && isset($_POST['mb_backend_url'])) {
    update_option('mb_backend_url', $_POST['mb_backend_url']);
}

?>