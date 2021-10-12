<?php /* Template Name: Weekend */ ?>

<?php get_header(); ?>

<section id="content">

	<?php if ( get_field( 'bg_image' ) ): ?>

        <article class="title">
            <div class="image"
                 style="background-image: url('<?php echo get_field( 'bg_image' )['sizes']['header']; ?>')"></div>
            <div class="title">

                <h1 class="page-title">
                    <span class="txt"><?php echo get_the_title(); ?></span>
                    <span class="underline"></span>
                </h1>

            </div>

        </article>

	<?php else: ?>

        <div class="spacer"></div>

	<?php endif; ?>


	<?php


	$first = date( "Y-m-d H:i:s", strtotime( 'monday this week' ) );

	$last = date( "Y-m-d", strtotime( 'sunday this week' ) ) . ' 23:59:59';


	$dates = get_dates( $first, $last, false, false, true );

	if ( isset( $_GET['category'] ) ) {
		$curr_cat = $_GET['category'];
	} else {
		$curr_cat = 'celebration';
	}

	?>


    <div class="platter" id="weekend">

        <section class="weekend">

			<?php

			$items = [];

			$days = array(
				'friday'   => array(
					'show' => false,
					'date' => strtotime( 'friday this week' ),
				),
				'saturday' => array(
					'show' => false,
					'date' => strtotime( 'saturday this week' ),
				),
				'sunday'   => array(
					'show' => false,
					'date' => strtotime( 'sunday this week' ),
				),
			);


			if ( $dates != null ):

				foreach ( $dates as $date ):

					$start = get_field( 'start', $date );
					$end   = get_field( 'end', $date );

					if ( date( 'Y-m-d', strtotime( $start ) ) == date( 'Y-m-d', $days['friday']['date'] ) ) {
						$days['friday']['show'] = true;
					}

					if ( date( 'Y-m-d', strtotime( $start ) ) == date( 'Y-m-d', $days['saturday']['date'] ) ) {
						$days['saturday']['show'] = true;
					}

					if ( date( 'Y-m-d', strtotime( $start ) ) == date( 'Y-m-d', $days['sunday']['date'] ) ) {
						$days['sunday']['show'] = true;
					}


					if ( $date->post_type == 'gc_event' ) {
						$url          = esc_url( get_permalink( $date ) );
						$location_obj = get_field( 'location', $date );
					} else {
						$url          = '#service-' . $date->ID;
						$location_obj = get_field_or_parent( 'location', $date, 'gc_servicecategory' );

						$cat = get_the_terms( $date, 'gc_servicecategory' )[0];

						if ( $curr_cat == $cat->slug ) {

							$url = '#service-' . $date->ID;

						} else {

							$url = add_query_arg( array(
								'category' => $cat->slug,
								'service'  => $date->ID,
							), $curr_url );

						}

					}


					if ( $location_obj != null ) {
						$location = get_the_title( $location_obj );
					} else {
						$location = "";
					}


					$item = array(
						'title'    => $date->post_title,
						'start'    => get_field( 'start', $date ),
						'end'      => get_field( 'end', $date ),
						'time'     => date( 'G:i', strtotime( get_field( 'start', $date ) ) ),
						'url'      => $url,
						'location' => $location,
						'object'   => $date,
					);

					$items[] = $item;

				endforeach;

			endif;


			?>

			<?php if ( sizeof( $items ) != 0 ): ?>

                <div class="container">

					<?php if ( $days['friday']['show'] ): ?>
                        <article>
                            <header>
                                <div>
                                    <h1><?php echo date_i18n( 'l', $days['friday']['date'] ) ?></h1>
                                    <time><?php echo date_i18n( 'j F', $days['friday']['date'] ) ?></time>
                                </div>
                            </header>
                            <div class="content">

								<?php
								foreach ( $items as $item ):

									if ( date( 'Y-m-d', strtotime( $item['start'] ) ) == date( 'Y-m-d', $days['friday']['date'] ) ):

										?>

                                        <a class="item" href="<?php echo $item['url'] ?>">
                                            <time><?php echo $item['time'] ?></time>
                                            <h2><?php echo $item['title'] ?></h2>
											<?php if ( $item['location'] != "" ): ?>
                                                <p class="location"><?php echo $item['location'] ?></p>
											<?php endif; ?>
                                        </a>


									<?php endif;

								endforeach;
								?>

                            </div>
                        </article>
					<?php endif; ?>

					<?php if ( $days['saturday']['show'] ): ?>
                        <article>
                            <header>
                                <div>
                                    <h1><?php echo date_i18n( 'l', $days['saturday']['date'] ) ?></h1>
                                    <time><?php echo date_i18n( 'j F', $days['saturday']['date'] ) ?></time>
                                </div>
                            </header>
                            <div class="content">

								<?php
								foreach ( $items as $item ):

									if ( date( 'Y-m-d', strtotime( $item['start'] ) ) == date( 'Y-m-d', $days['saturday']['date'] ) ):


										?>

                                        <a class="item" href="<?php echo $item['url'] ?>">
                                            <time><?php echo $item['time'] ?></time>
                                            <h2><?php echo $item['title'] ?></h2>
											<?php if ( $item['location'] != "" ): ?>
                                                <p class="location"><?php echo $item['location'] ?></p>
											<?php endif; ?>
                                        </a>


									<?php endif;

								endforeach;
								?>
                            </div>
                        </article>
					<?php endif; ?>

					<?php if ( $days['sunday']['show'] ): ?>
                        <article>
                            <header>
                                <div>
                                    <h1><?php echo date_i18n( 'l', $days['sunday']['date'] ) ?></h1>
                                    <time><?php echo date_i18n( 'j F', $days['sunday']['date'] ) ?></time>
                                </div>
                            </header>
                            <div class="content">

								<?php
								foreach ( $items as $item ):

									if ( date( 'Y-m-d', strtotime( $item['start'] ) ) == date( 'Y-m-d', $days['sunday']['date'] ) ):


										?>

                                        <a class="item" href="<?php echo $item['url'] ?>">
                                            <time><?php echo $item['time'] ?></time>
                                            <h2><?php echo $item['title'] ?></h2>
											<?php if ( $item['location'] != "" ): ?>
                                                <p class="location"><?php echo $item['location'] ?></p>
											<?php endif; ?>
                                        </a>

									<?php endif;

								endforeach;
								?>
                            </div>
                        </article>
					<?php endif; ?>


                </div>

			<?php else: ?>

                <div class="empty">
                    <h1><?php _e('There is no event this weekend', 'gc_2018') ?></h1>
                </div>


			<?php endif; ?>


        </section>


		<?php
		// TO SHOW THE PAGE CONTENTS
		while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
            <article class="content-page">


				<?php

				if ( get_the_content() != null ) {
					echo '<div class="content">';
					the_content();
					echo '</div>';
				}


				?> <!-- Page Content -->

            </article><!-- .entry-content-page -->


		<?php
		endwhile; //resetting the page loop
		wp_reset_query(); //resetting the page query
		?>


		<?php


		$start = date( "Y-m-d", strtotime( 'monday this week' ) ) . ' 00:00:00';

		$end = date( "Y-m-d", strtotime( "+1 year", strtotime( 'today' ) ) ) . ' 23:59:59';

		$dates = get_dates( $start, $end, false, array( $curr_cat ), true );

		//var_dump($services);

		global $wp;
		$curr_url = home_url( $wp->request );

		?>


        <section class="program" id="services">
            <div class="header">

				<?php

				$categories = get_terms( array(
					'taxonomy'   => 'gc_servicecategory',
					'hide_empty' => 1,
				) );

				?>

                <ul class="category_filter">
					<?php foreach ( $categories as $cat ): ?>

						<?php

						$name = $cat->name;
						$id   = $cat->term_id;

						$link = add_query_arg( array(
							'category' => $cat->slug,
						), $curr_url );

						$acronym = get_field( 'acronym', $cat );
						$class   = '';


						$image = get_field( 'service_picture', $cat );


						$bg_image = $image['sizes']['square'];


						$is_current = false;

						if ( $curr_cat == $cat->slug ) {
							$is_current = true;
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


						?>

					<?php endforeach; ?>

                </ul>
            </div>
            <div class="content ">
				<?php foreach ( $dates as $item ):

					$date = date_i18n( 'j M. Y', strtotime( get_field( 'start', $item ) ) );
					$time = complex_time( get_field( 'start', $item ), get_field( 'end', $item ) );
					$title = get_field( 'title', $item );

					if ( ! $title ) {
						$title = $item->post_title;
					}

					if ( $item->post_type == 'gc_event' ) {
						$class        = 'event';
						$url          = esc_url( get_permalink( $item ) );
						$image        = get_field_or_parent( 'event_picture', $item, 'gc_eventcategory' );
						$txt          = "<a href='$url'>En savoir plus</a>";
						$location_obj = get_field_or_parent( 'location', $item, 'gc_eventcategory' );
						$speakers     = null;


					} else {
						$class        = 'service';
						$url          = '#service-' . $item->ID;
						$location_obj = get_field_or_parent( 'location', $item, 'gc_servicecategory' );
						$image        = get_field( 'service_picture', $item );
						$txt          = get_field_or_parent( 'description', $item, 'gc_servicecategory' );
						$location_obj = get_field_or_parent( 'location', $item, 'gc_servicecategory' );
						$speakers     = get_field( 'service_speaker', $item );

						$cat = get_the_terms( $item, 'gc_servicecategory' )[0];

						if ( $curr_cat == $cat->slug ) {

							$url = '#service-' . $date->ID;

						} else {

							$url = add_query_arg( array(
								'category' => $cat->slug,
								'service'  => $item->ID,
							), $curr_url );

						}

					}


					if ( ! $image ) {

						if ( $speakers != null ) {
							$image = get_people( $speakers[0]['value'] )['picture'];
						}

						if ( ! $image ) {
							$image = get_field_or_parent( 'service_picture', $item, 'gc_servicecategory' );
						}

					}

					if ( $location_obj != null ) {
						$location = get_the_title( $location_obj ) . "<br/>" . get_field( 'address', $location_obj ) . "<br/>" . get_field( 'zip_code', $location_obj ) . " " . get_field( 'city', $location_obj ) . "<br/>" . get_field( 'country', $location_obj );
					} else {
						$location = "";
					}


					?>
                    <article class="<?php echo $class; ?>" id="service-<?php echo $item->ID; ?>">

                        <div class="container">
                            <div class="header">
								<?php if ( $speakers != null ): ?>
                                    <div class="speaker">
										<?php foreach ( $speakers as $speaker ): ?>
                                            <h1><?php echo get_people( $speaker['value'] )['name']; ?></h1>
										<?php endforeach; ?>
                                    </div>
								<?php endif; ?>
                                <div class="image"
                                     style="background-image: url('<?php echo $image['sizes']['speaker']; ?>')"></div>
                            </div>
                            <div class="text">
                                <h1><?php echo $title; ?></h1>
                                <time class="date"><?php echo $date; ?></time>
                                <time class="time"><?php echo $time; ?></time>
                                <p><?php echo $txt; ?></p>
                            </div>
							<?php if ( $location != null ): ?>
                                <div class="location"><?php echo $location ?></div>
							<?php endif; ?>
                        </div>
                    </article>
				<?php endforeach; ?>
            </div>

        </section>

    </div>


</section>


<?php get_footer(); ?>

