<?php
// Check if this file is being accessed directly and load WordPress if not
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// Get the current event's post object
global $post;

// Retrieve the custom field values for the current event
$name = get_post_meta($post->ID, 'name', true);
$group_id = get_post_meta($post->ID, 'group_id', true);

?>

<h1><?= $post->post_title ?></h1>


<a href="<?= get_option('mb_backend_url') ?>/api/groups?id=<?= $group_id ?>">Register for group <?= $name ?> </a>