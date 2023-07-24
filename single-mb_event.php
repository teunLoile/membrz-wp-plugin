
<?php
// Check if this file is being accessed directly and load WordPress if not
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// Get the current event's post object
global $post;

// Retrieve the custom field values for the current event
$event_id = get_post_meta($post->ID, 'event_id', true);
$image_url = get_post_meta($post->ID, 'image_url', true);
$name = get_post_meta($post->ID, 'name', true);
$start_date = get_post_meta($post->ID, 'start_date', true);
$end_date = get_post_meta($post->ID, 'end_date', true);
$begin_time = get_post_meta($post->ID, 'begin_time', true);
$end_time = get_post_meta($post->ID, 'end_time', true);
$location = get_post_meta($post->ID, 'location', true);
$description = get_post_meta($post->ID, 'description', true);
?>

<!-- Display the event details -->
<div class="event-details">
    <h1><?php echo $name; ?></h1>
    <p><strong>Event ID:</strong> <?php echo $event_id; ?></p>
    <img src="<?php echo $image_url; ?>" alt="<?php echo $name; ?>">
    <p><strong>Date:</strong> <?php echo $start_date . ' to ' . $end_date; ?></p>
    <p><strong>Time:</strong> <?php echo $begin_time . ' to ' . $end_time; ?></p>
    <p><strong>Location:</strong> <?php echo $location; ?></p>
    <p><strong>Description:</strong> <?php echo $description; ?></p>
</div>


<div>
    <form action="<?= 'http://localhost:5000/api/event/register' ?>" method="POST">
        <div class="form-input">
            <label for="email">Email</label>
            <input type="text" name="email">
        </div>
        <div class="form-input">
            <label for="password">Password</label>
            <input type="password" name="password">
        </div>
        <div>
            <input type="hidden" value=<?=$event_id?> name="event_id">
            <input type="hidden" value=<?= get_permalink()?> name="redirect_url">
        </div>
        <div>
            <input type="submit">
        </div>
    </form>
</div>