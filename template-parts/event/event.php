<section id="content">

	<?php


	$location_obj = get_field_or_parent( 'location', $post, 'gc_eventcategory' );

	if ( $location_obj != null ) {
		$location = get_the_title( $location_obj ) . "<br/>" . get_field( 'address', $location_obj ) . "<br/>" . get_field( 'zip_code', $location_obj ) . " " . get_field( 'city', $location_obj ) . "<br/>" . get_field( 'country', $location_obj );
		$location_maps = get_the_title( $location_obj ) . " " . get_field( 'address', $location_obj ) . " " . get_field( 'zip_code', $location_obj ) . " " . get_field( 'city', $location_obj ) . " " . get_field( 'country', $location_obj );
	} else {
		$location = "";
		$location_maps = "";
	}


	$title = get_the_title();
	$dates = complex_date( get_field( 'start' ), get_field( 'end' ) );
	//$description = get_field( 'description', $_POST );

	$description = get_field_or_parent( 'description', $post, 'gc_eventcategory' );

	$subtitle = get_field( 'event_subtitle', $_POST );
	$times    = complex_time( get_field( 'start' ), get_field( 'end' ) );


	$video = get_field( 'event_video', $_POST );

	// use preg_match to find iframe src
	preg_match( '/src="(.+?)"/', $video, $matches );

	if ( sizeof( $matches ) > 1 ) {
		$src = $matches[1];
	} else {
		$src = "";
	}


	$params = array(
		'controls'       => 1,
		'hd'             => 1,
		'autohide'       => 1,
		'rel'            => 0,
		'showinfo'       => 0,
		'color'          => 'e52639',
		'title'          => 0,
		'byline'         => 0,
		'portrait'       => 0,
		'data-show-text' => 0
	);


	$new_src = add_query_arg( $params, $src );

	$video = str_replace( $src, $new_src, $video );

	$attributes = 'frameborder="0"';

	$video = str_replace( '></iframe>', ' ' . $attributes . 'class="video"></iframe>', $video );


	$button_url   = get_field( 'button_url', $post );
	$button_label = get_field( 'button_label', $post );

	$pres_page = get_field_or_parent( 'presentation_page', $post, 'gc_eventcategory' );


	if ( get_field_or_parent( 'bg_image', $post, 'gc_eventcategory' ) ) {
		$bg_image = get_field_or_parent( 'bg_image', $post, 'gc_eventcategory' );
	} else {
		$bg_image = get_field_or_parent( 'event_picture', $post, 'gc_eventcategory' );
	}

	if ( ! $bg_image ) {

		$default_cat = get_term_by( 'slug', 'other', 'gc_eventcategory' );

		$bg_image = get_field( 'bg_image', $default_cat );

	}

	$slideshow = get_field( 'slideshow' );


	?>


    <article class="title">
        <div class="image" style="background-image: url('<?php echo $bg_image['sizes']['header']; ?>')"></div>
        <div class="title">

            <h1 class="page-title">
                <span class="txt"><?php echo $title; ?></span>
                <span class="underline"></span>
            </h1>


        </div>

    </article>


    <div class="platter">

        <div class="nav">
            <div class="back">
                <a href="<?php echo get_post_type_archive_link( 'gc_event' ); ?>"><?php _e( 'Back', 'gc_2018' ) ?></a>
            </div>
        </div>

        <article class="content-page">

            <div class="container">
                <main>
                    <div class="content">
                        <h1><?php echo $subtitle; ?></h1>
                        <div class="mobile">
                            <time class="date"><?php echo $dates; ?></time>
                            <time class="time"><?php echo $times; ?></time>
                        </div>
						<?php echo $description; ?>

	                   <?php print_buttons('link', $post); ?>

                    </div>
                    <div class="video">

						<?php echo $video; ?>
                    </div>

                    <div class="more">


						<?php if ( $pres_page != null ): ?>
                            <a class="dynamic"
                               href="<?php echo get_permalink( $pres_page->ID ); ?>"><?php _e( 'descover', 'gc_2018' ) ?><?php echo " " . $pres_page->post_title; ?></a>
						<?php endif; ?>

                        <div class="social">
                            <a class="social" id="facebook" target="_blank"
                               href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ) ?>">Facebook</a>
                            <a class="social" id="twitter" target="_blank"
                               href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ) ?>&text=<?php echo $title . ' // ' . $dates ?>&original_referer=<?php echo urlencode( get_permalink() ) ?>">Twitter</a>
                        </div>
                    </div>


                </main>

                <aside>
                    <div class="content">
                        <time class="date"><?php echo $dates; ?></time>
                        <time class="time"><?php echo $times; ?></time>

						<?php if ( $location_obj != null ): ?>
                            <div class="location">
                                <h3><?php _e( 'Location', 'gc_2018' ) ?></h3>
                                <p class="address"><?php echo $location; ?></p>

                                <a target="_blank" class="direction"
                                   href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode( $location_maps ) ?>"><?php _e( 'get directions', 'gc_2018' ) ?></a>

                            </div>
						<?php endif; ?>



						<?php if ( $button_url != null ): ?>
                            <a target="_blank" class="button"
                               href="<?php echo $button_url ?>"><?php echo $button_label ?></a>
						<?php endif; ?>

						<?php //if ( $location_obj != null ): ?>
						<?php if ( false ): ?>
                            <a target="_blank" class="small" href=""><?php _e( 'add to calendar', 'gc_2018' ) ?></a>
						<?php endif; ?>


                    </div>
                </aside>

            </div>
			<?php if ( $slideshow ): ?>
                <section class="slideshow">
                    <div id="slides_js" class="slidesjs">
						<?php

						foreach ( $slideshow as $slide ):

							$image = $slide['sizes']['full_hd'];

							echo "<img src='$image'>";

						endforeach; ?>
                    </div>
                    <script>
                        $(function () {
                            $('#slides_js').slidesjs({
                                width: 1280,
                                height: 720,
                                play: {
                                    active: true,
                                    auto: true,
                                    interval: 4000,
                                    swap: true,
                                    pauseOnHover: true,
                                }
                            });
                        });
                    </script>
                </section>
			<?php endif; ?>

        </article>


		<?php


		$categories = [];

		$today = date( 'Y-m-d H:i:s' );

		foreach ( get_the_terms( $post, 'gc_eventcategory' ) as $cat ) {
			$categories[] = $cat->slug;
		}


		$query = new WP_Query( array(
			'post_type'    => 'gc_event',
			'showposts'    => 12,
			'post__not_in' => array( get_the_ID() ),
			'tax_query'    => array(
				array(
					'taxonomy' => 'gc_eventcategory',
					'field'    => 'slug',
					'terms'    => $categories
				)
			)

		) );

		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'start' );
		$query->set( 'meta_key', 'end' );
		$query->set( 'meta_query', array(
			array(
				'key'     => 'end',
				'compare' => '>=',
				'value'   => $today,
			)
		) );
		$query->set( 'order', 'asc' );


		$events = $query->get_posts();


		?>


		<?php if ( $events != null ): ?>
            <section class="content-default" id="related_events">
                <h1><?php _e( 'next dates to come', 'gc_2018' ) ?></h1>

                <ul class="dynamic">
					<?php foreach ( $events as $event ):

						$e_dates = complex_date( get_field( 'start', $event ), get_field( 'end', $event ) );
						$e_times = complex_time( get_field( 'start', $event ), get_field( 'end', $event ) );
						$e_link = $event->guid;

						?>
                        <li>
                            <a href="<?php echo $e_link; ?>">
                                <h1 class="date"><?php echo $e_dates; ?></h1>
                                <h2 class="time"><?php echo $e_times; ?></h2>
                                <span class="top"></span>
                                <span class="right"></span>
                                <span class="bottom"></span>
                                <span class="left"></span>
                            </a>
                        </li>
					<?php endforeach; ?>
                </ul>
            </section>
		<?php endif; ?>


    </div>


</section>

