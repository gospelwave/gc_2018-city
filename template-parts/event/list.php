<?php get_header(); ?>


<section id="content">

	<?php


	if ( is_tax( 'gc_eventcategory' ) ):

		$bg_image = get_field_or_parent( 'bg_image', get_queried_object(), 'gc_eventcategory' );


		if ( $bg_image == null ) {
			$default_cat = get_term_by( 'slug', 'other', 'gc_eventcategory' );

			$bg_image = get_field( 'bg_image', $default_cat );
		}

		$title = get_queried_object()->name;


    elseif ( get_queried_object()->name == "gc_event" ):

		$default_cat = get_term_by( 'slug', 'other', 'gc_eventcategory' );

		$bg_image = get_field( 'bg_image', $default_cat );

		$title = __('Events', 'gc_2018');


	else:

		$bg_image = get_field_or_parent( 'bg_image', $_POST, 'gc_eventcategory' );

		$title = __('Events', 'gc_2018');

	endif;


	?>




	<?php

	$today = date( 'Y-m-d H:i:s' );

	$query = new WP_Query( array(
		'post_type'  => 'gc_event',
		'showposts'  => 1,
		'meta_key'   => 'main_event',
		'meta_value' => true,
		'orderby'    => 'meta_value',
		'order'      => 'asc',
		'meta_query' => array(
			array(
				'key'     => 'end',
				'compare' => '>=',
				'value'   => $today,
			)
		),
	) );

	$events = $query->get_posts();


	if ( $events[0] instanceof WP_Post && get_queried_object()->name == "gc_event" ) :

		$event = $events[0];

		$id    = $event->ID;
		$title = $event->post_title;
		$link  = esc_url( $event->guid );
		$date  = complex_date( get_field( 'start', $event ), get_field( 'end', $event ) );
		$time  = time_trans( new DateTime( get_field( 'start', $event ) ) );

		if ( get_field_or_parent( 'bg_image', $event, 'gc_eventcategory' ) ) {
			$image = get_field_or_parent( 'bg_image', $event, 'gc_eventcategory' );
		} else {
			$image = get_field_or_parent( 'event_picture', $event, 'gc_eventcategory' );
		}

		?>

        <article class="title main_event">

            <div id="<?php echo $id; ?>" class="image"
                 style="background-image: url('<?php echo $image['sizes']['header']; ?>')"></div>

            <div class="title">

                <h4><?php _e('Special event', 'gc_2018') ?></h4>
                <h1 class="page-title">

                    <span class="txt"><?php echo $title; ?></span>
                    <span class="underline"></span>


                </h1>
                <h3><?php echo $date; ?></h3>
                <a href="<?php echo $link; ?>" class="button"><span><?php _e('Learn more', 'gc_2018') ?></span></a>


            </div>
        </article>

	<?php else: ?>
        <article class="title">

            <div class="image"
                 style="background-image: url('<?php echo $bg_image['sizes']['header']; ?>')"></div>
            <div class="title">

                <h1 class="page-title">
                    <span class="txt"><?php echo $title; ?></span>
                    <span class="underline"></span>
                </h1>

            </div>
        </article>


	<?php endif; ?>


    <div class="platter">


		<?php


		if ( have_posts() ) : ?>

            <section id="events_header">

                <h1><?php _e('Next events', 'gc_2018') ?></h1>

                <div class="content">

                    <ul class="event_categories category_filter">
						<?php

						$queried_object = get_queried_object();

						$curr_cat = null;

						if ( $queried_object instanceof WP_Term ) {
							$curr_cat = $queried_object;
						}


						$exclude    = array();
						$categories = get_terms( array(
							'taxonomy'   => 'gc_eventcategory',
							'hide_empty' => 1,
						) );

						$cat_other = get_term_by( 'slug', 'other', 'gc_eventcategory' );


						foreach ( $categories as $category ) {


							$events = get_posts( array(
								'post_type'   => 'gc_event',
								'numberposts' => - 1,
								'tax_query'   => array(
									array(
										'taxonomy'         => 'gc_eventcategory',
										'field'            => 'id',
										'terms'            => $category->term_id, // Where term_id of Term 1 is "1".
										'include_children' => true
									)
								)
							) );

							foreach ( $events as $key => $event ) {

								$end_date = get_field( "end_date", $event );


								if ( $end_date < date( 'Y-m-d' ) ) {
									unset( $events[ $key ] );
								}
							}

							if ( ! count( $events ) ) {

								$exclude[] = $category->term_id;
							}

						}

						$exclude[] = $cat_other->term_id;


						/*
						wp_list_categories( array(
							'show_option_all' => __( 'Filter events', 'gc_2018' ),
							'value_field'     => 'slug',
							'hide_if_empty'   => false,
							'title_li'        => "",
							'hide_empty'      => 1,
							'hierarchical'    => 1,
							'exclude'         => $exclude,
							'taxonomy'        => 'gc_eventcategory',
							'selected'        => get_queried_object()->slug
						) );
						*/


						$cat_list = get_terms( array(
							'taxonomy' => 'gc_eventcategory',
							'orderby'  => 'name',
							'order'    => 'ASC',
							'exclude'  => $exclude,
							'parent'   => 0
						) );


						foreach ( $cat_list as $cat ) {

							$name    = $cat->name;
							$id      = $cat->term_id;
							$link    = get_term_link( $cat );
							$acronym = get_field( 'acronym', $cat );
							$class   = '';


							if ( get_field( 'event_picture', $cat ) ) {
								$image = get_field( 'event_picture', $cat );
							} else {
								$image = get_field( 'bg_image', $cat );
							}


							$bg_image = $image['sizes']['square'];


							$is_current = false;

							if ( $curr_cat instanceof WP_Term ) {

								if ( $curr_cat->term_id == $cat->term_id ) {
									$is_current = true;
									$link       = get_post_type_archive_link( 'gc_event' );
								}

							}


							if ( $acronym == null ) {
								$s = $name;

								if ( preg_match_all( '/\b(\w)/', strtoupper( $s ), $m ) ) {
									$v = implode( '', $m[1] ); // $v is now SOQTU
								}


								if ( strlen( $v ) <= 1 ) {
									$acronym = substr( $name, 0, 3 );
								} else {
									$acronym = substr( $v, 0, 3 );
								}

							}

							if ( strlen( $name ) >= 15 ) {
								$name = substr( $name, 0, 12 ) . "...";
							}


							if ( $is_current ) {
								$class .= " current";
							} else if ( $curr_cat != null ) {
								$class .= " not_current";
							}

							echo "
					<li id='category-item-$id' class='$class'>
					    <a href='$link'>
					        <div class='round'>
					        <div class='image' style='background-image: url(\" $bg_image \")'></div>
					            <span>$acronym</span>
                            </div>
					        <div class='name'>$name</div>
					        
					    </a>
					</li>";
						}

						?>


                    </ul>
                </div>

            </section>


            <section id="listOfEvents" class="small" data-nb="3">
                <article class="content-page events_table">


					<?php

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						set_query_var( 'event', $post );

						get_template_part( 'template-parts/event/item' );

					endwhile; ?>

                </article>
            </section>


            <nav class="nav">
                <div class="previous"><?php previous_posts_link( __( 'Previous', 'gc_2018' ) ); ?></div>
                <div class="next"><?php next_posts_link( __( 'Next', 'gc_2018' ) ); ?></div>
            </nav>

		<?php

		else :

			get_template_part( 'template-parts/event/none' );

		endif;
		?>

    </div>


</section>


<?php get_footer(); ?>




