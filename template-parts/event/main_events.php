<?php
/**
 * Created by PhpStorm.
 * User: yoanngern
 * Date: 11.12.17
 * Time: 12:07
 */


$query = new WP_Query( array(
	'post_type'  => 'gc_event',
	'showposts'  => 3,
	'meta_key'   => 'main_event',
	'meta_value' => true
) );


$events = $query->get_posts();


?>

<section class="main-event">
    <div class="events_table">

	<?php foreach ( $events as $event ):

		if ( $event instanceof WP_Post ):
			//var_dump( $event );

			$id    = $event->ID;
			$title = $event->post_title;
			$link  = esc_url( $event->guid );
			$image = get_field_or_parent( 'bg_image', $event, 'gc_eventcategory' )['sizes']['square'];
			$date  = complex_date( get_field( 'start', $event ), get_field( 'end', $event ) );
			$time  = time_trans( new DateTime( get_field( 'start', $event ) ) );

			?>


            <article class="event">
                <a href="<?php echo $link; ?>">
                    <div class="content">
                        <div class="hover">
                            <span><?php _e('Learn more', 'gc_2018') ?></span>
                        </div>
                        <div id="<?php echo $id; ?>" class="image"
                             style="background-image: url('<?php echo $image; ?>')"></div>
                        <h2><?php echo $title; ?></h2>
                        <div class="txt">
                            <h3><?php echo $date; ?></h3>
                            <h4><?php echo $time; ?></h4>
                        </div>
                    </div>

                </a>
            </article>


		<?php endif; ?>

	<?php endforeach; ?>
    </div>
</section>

