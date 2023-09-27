<?php get_header();
$drawing_lots = get_field('drawing_lots');

global $post;

$event_id = get_post_meta($post->ID, 'event_id', true);
$event_date = $start_date = get_post_meta($post->ID, 'start_date', true) ? get_post_meta($post->ID, 'start_date', true) :  get_field('event_date');
$event_start_time = get_post_meta($post->ID, 'begin_time', true) ? get_post_meta($post->ID, 'begin_time', true) : get_field('event_start_time');
$event_end_time = get_post_meta($post->ID, 'end_time', true) ? get_post_meta($post->ID, 'end_time', true)  : get_field('event_end_time');
$event_end_date = get_post_meta($post->ID, 'end_date', true) ? get_post_meta($post->ID, 'end_date', true) :  get_field('event_end_date');
$image_url = get_post_meta($post->ID, 'image_url', true);
$name = get_post_meta($post->ID, 'name', true);
$location = get_post_meta($post->ID, 'location', true);
$description = get_post_meta($post->ID, 'description', true);

?>

<main>

    <div class="page-intro normal-page-intro">
        <div class="card">
            <div class="card-img hero-slider">
                <?php if (has_post_thumbnail()) { ?>
                    <?php the_post_thumbnail('large', ['class' => 'card-img']); ?>
                <?php } else { ?>
                    <img src="<?= get_template_directory_uri() ?>/images/intro-fallback.jpg" class="card-img">
                <?php } ?>
            </div>

            <div class="card-img-overlay d-flex align-items-end justify-content-center p-0">
                <div class="container">
                    <div class="row">
                        <div class="col-11">
                            <div class="inner d-flex align-items-center justify-content-between py-3">
                                <?php the_title('<h1 class="text-white mb-0">', '</h1>'); ?>

                                <?php
                                $main_sponsor = get_field('main_sponsor', 'options');
                                $alt_main_sponsor = get_field('alt_main_sponsor', 'options');
                                $alt_main_sponsor_2 = get_field('alt_main_sponsor_2', 'options');
                                ?>
                                <div class="ml-auto d-none d-lg-flex">
                                    <?php if ($main_sponsor) { ?>
                                        <div class="main-sponsor">
                                            <?= get_the_post_thumbnail($main_sponsor); ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($alt_main_sponsor) { ?>
                                        <div class="main-sponsor ml-3">
                                            <?= get_the_post_thumbnail($alt_main_sponsor); ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ($alt_main_sponsor_2) { ?>
                                        <div class="main-sponsor ml-3">
                                            <?= get_the_post_thumbnail($alt_main_sponsor_2); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php if (have_posts()) { ?>
        <?php while (have_posts()) { ?>
            <?php the_post(); ?>
            <section class="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 order-2 order-lg-1">
                            <?php the_content(); ?>

                            <a href="<?= get_post_type_archive_link('events') ?>" class="btn btn-link pl-0 text-muted">&larr; Back to archive</a>
                        </div>


                        <div class="col-lg-4 order-1 order-lg-2">
                            <h2>Event details</h2>

                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td scope="row">(Start)date</td>
                                        <td><?= date('d-m-Y', strtotime($event_date)) ?></td>
                                    </tr>
                                    <?php if ($event_start_time) { ?>
                                        <tr>
                                            <td scope="row">(Start)time</td>
                                            <td><?= $event_start_time ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($event_end_date != $event_date && $event_end_date > $event_date) { ?>
                                        <tr>
                                            <td scope="row">Enddate</td>
                                            <td><?= date('d-m-Y', strtotime($event_end_date)) ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($event_end_time != '00:00:00') { ?>
                                        <tr>
                                            <td scope="row">Enddate</td>
                                            <td><?= $event_end_time ?></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>


                            <?php $event_id = get_field('event_id'); ?>

                            <?php if ($event_id) { ?>
                                <?php $event = lassie_get_event_by_id($event_id); ?>
                                <h2>Register</h2>

                                <?php if (lassie_is_logged_in()) { ?>
                                    <?= lassie_get_alerts() ?>


                                    <?php if ($drawing_lots) { ?>
                                        <p>There are only a certain number of places for this event. There will be a draw.</p>
                                    <?php } ?>


                                    <?php
                                    $subscriptions = lassie_get_subscriptions();
                                    $subscription_id = array_search($event_id, array_column((array) $subscriptions, 'event_id', 'id'));
                                    ?>

                                    <?php if ($subscription_id) { ?>

                                        <?php
                                        $subscription = $subscriptions->$subscription_id;
                                        ?>

                                        <?php if (($subscription->transaction_id && lassie_verify_transaction($subscription->transaction_id)) || (($event->fee === 0 || $event->fee === '0.00' || $event->fee === '0,00') && (lassie_is_subscribed_to(false, $event_id)))) { ?>

                                            <p>You have registered and paid for this event!</p>

                                            <?php $subscriptions = lassie_get_subscription_by_person_id();

                                            $subscriptions = array_filter(json_decode(json_encode($subscriptions), true), function ($item) use ($event_id) {
                                                return $item['event_id'] === $event_id;
                                            });
                                            if (count($subscriptions) !== 1) { ?>
                                                <div class="alert alert-warning">De aanmelding kon niet gevonden worden.</div>
                                            <?php }
                                            $subscriptions = array_values($subscriptions);
                                            $subscription_id = (int) $subscriptions[0]['id'];
                                            ?>


                                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="mb-4">
                                                <input type="hidden" name="action" value="lassie_unsubscribe_event">
                                                <input type="hidden" name="subscription_id" value="<?= $subscription_id ?>">

                                                <button type="submit" class="btn btn-primary">Sign out</button>
                                            </form>

                                        <?php } else { ?>

                                            <p>You have not yet paid for this event.</p>

                                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="mb-4">
                                                <input type="hidden" name="action" value="lassie_save_transaction">
                                                <input type="hidden" name="event_id" value="<?= $event_id ?>">

                                                <button type="submit" class="btn btn-primary">Pay &euro; <?= number_format($event->fee, 2, ',', '.') ?></button>
                                            </form>

                                        <?php } ?>

                                    <?php } else { ?>

                                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="mb-4">
                                            <input type="hidden" name="action" value="lassie_insert_subscription">
                                            <input type="hidden" name="event_id" value="<?= $event_id ?>">

                                            <button type="submit" class="btn btn-primary">Sign up</button>
                                        </form>

                                    <?php } ?>


                                    <?php $subscriptions = lassie_get_event_subscriptions($event_id); ?>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Registrations</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (json_decode(json_encode($subscriptions), true) as $key => $subscription) { ?>
                                                <?php $user = lassie_get_personal_information($subscription['person_id']); ?>
                                                <tr>
                                                    <?php if ($user->person_id) { ?>
                                                        <td scope="row">
                                                            <a href="<?= get_page_link(268); ?>?id=<?= $user->person_id ?>">
                                                                <?= $user->first_name . ' ' . $user->last_name ?>
                                                            </a>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td scope="row"><?= $user->first_name . ' ' . $user->last_name ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                <?php } else { ?>

                                    <p>You need to sign in to register for this event.</p>

                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
        <?php wp_reset_query(); ?>
    <?php  } ?>
</main>

<?php get_footer(); ?>