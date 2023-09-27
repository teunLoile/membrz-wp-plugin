<?php
// Check if this file is being accessed directly and load WordPress if not
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// Get the current event's post object
global $post;

// Retrieve the custom field values for the current event
$name = get_post_meta($post->ID, 'name', true);

?>

<h1><?php echo $name; ?></h1>