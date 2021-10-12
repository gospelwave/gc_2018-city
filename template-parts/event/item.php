<?php

$event = get_query_var('event');

if ( $event != null ) :


	$id = $event->id;
	$title = get_the_title( $event );
	$link  = esc_url( get_permalink( $event ) );
	$date  = complex_date( get_field( 'start', $event ), get_field( 'end', $event ) );
	$time  = complex_time( get_field( 'start', $event ), get_field( 'end', $event ) );


	if ( get_field_or_parent( 'event_picture', $event, 'gc_eventcategory' ) ) {
		$image = get_field_or_parent( 'event_picture', $event, 'gc_eventcategory' );
	} else {
		$image = get_field_or_parent( 'bg_image', $event, 'gc_eventcategory' );
	}


	?>


    <div class="event">
        <a href="<?php echo $link; ?>">
            <div class="content">
                <div class="hover"></div>
                <div id="<?php echo $id; ?>" class="image"
                     style="background-image: url('<?php echo $image['sizes']['square']; ?>')"></div>
                <h2><?php echo $title; ?></h2>
                <div class="bottom">
                    <div class="txt">
                        <h3><?php echo $date; ?></h3>
                        <h4><?php echo $time; ?></h4>
                    </div>
                    <div class="button">
                        <span><?php _e('Learn more', 'gc_2018') ?></span>
                    </div>
                </div>

            </div>

        </a>
    </div>

<?php endif; ?>