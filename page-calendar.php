<?php /* Template Name: Calendar */ ?>

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


    <div class="platter" id="calendar">


		<?php


		if ( isset( $_GET['week'] ) ) {
			$week = $_GET['week'];

			$start = $week . ' 00:00:00';
			$end   = date( "Y-m-d", strtotime( "+6 day", strtotime( $week ) ) ) . ' 23:59:59';

		} else {
			$start = date( "Y-m-d", strtotime( 'monday this week' ) ) . ' 00:00:00';
			$end   = date( "Y-m-d", strtotime( 'sunday this week' ) ) . ' 23:59:59';
		}

		$dates = get_dates( $start, $end, false, false, false );


		$day = date( "Y-m-d", strtotime( $start ) );


		$next_monday = date( "Y-m-d", strtotime( "+7 day", strtotime( $start ) ) );
		$prev_monday = date( "Y-m-d", strtotime( "-7 day", strtotime( $start ) ) );
		$this_monday = date( "Y-m-d", strtotime( 'monday this week' ) );

		global $wp;
		$curr_url = home_url( $wp->request );

		?>


        <article class="calendar week">

            <div class="title">

                <div class="left">
					<?php if ( strtotime( $this_monday ) < strtotime( $start ) ): ?>
                        <a class="go_today" href="<?php echo add_query_arg( array(
							'week' => $this_monday,
						), $curr_url ); ?>">Back to today</a>

					<?php endif; ?>
                </div>

                <div class="center">

                    <div class="select_week">
                        <a href="<?php echo add_query_arg( array(
							'week' => $prev_monday,
						), $curr_url ); ?>" class="prev"></a>
                        <div class="week">
                            <span><?php echo complex_date( $start, $end ); ?></span>
                        </div>
                        <a href="<?php echo add_query_arg( array(
							'week' => $next_monday,
						), $curr_url ); ?>" class="next"></a>
                    </div>
                </div>

                <div class="right">
					<?php if ( strtotime( $this_monday ) > strtotime( $start ) ): ?>
                        <a class="go_today" href="<?php echo add_query_arg( array(
							'week' => $this_monday,
						), $curr_url ); ?>">Go to today</a>

					<?php endif; ?>
                </div>

            </div>

            <div class="flex">

				<?php


				while ( strtotime( $day ) <= strtotime( $end ) ) :

					$day_class = 'day';

					$index = 0;

					if ( strtotime( date( 'Y-m-d' ) ) == strtotime( $day ) ) {
						$day_class .= ' today';
					}


					if ( date( 'N', strtotime( $day ) ) >= 6 ) {
						$day_class .= ' weekend';
					}

					?>

                    <div class="<?php echo $day_class; ?>">
                        <div class="head">
                            <h1><?php echo date_i18n( 'D', strtotime( $day ) ) . '.' ?></h1>
                            <time><span class="day"><?php echo date_i18n( 'j', strtotime( $day ) ) ?></span><span
                                        class="month"><?php echo date_i18n( 'F', strtotime( $day ) ) ?></span><span
                                        class="month_short"><?php echo date_i18n( 'M', strtotime( $day ) ) ?></span>
                            </time>
                        </div>
                        <div class="content">
							<?php

							while ( $index < sizeof( $dates ) ) : ?>

								<?php


								if ( ( strtotime( $day ) >= strtotime( date( 'Y-m-d', strtotime( get_field( 'start', $dates[ $index ] ) ) ) ) ) && ( strtotime( $day ) <= strtotime( date( 'Y-m-d', strtotime( get_field( 'end', $dates[ $index ] ) ) ) ) ) ) :

									$date = $dates[ $index ];
									$date_class = 'date';

									if ( date( 'Y-m-d', strtotime( get_field( 'start', $date ) ) ) != date( 'Y-m-d', strtotime( get_field( 'end', $date ) ) ) ) {
										$date_class .= ' multi';
									}


									$date_start = get_field( 'start', $date );
									$date_title = $date->post_title;

									$date_time = complex_time( get_field( 'start', $date ), get_field( 'end', $date ) );

									if ( $date->post_type == 'gc_event' ) {
										$url = esc_url( get_permalink( $date ) );
									} else {

										$cats = wp_get_post_terms( $date->ID, 'gc_servicecategory');

										$url = add_query_arg( array(
											'service' => $date->ID,
											'category' => $cats[0]->slug,
										), 'weekend' );
									}

									?>

                                    <a href="<?php echo $url; ?>" class="<?php echo $date_class; ?>">
                                        <h1><?php echo $date_title; ?></h1>
                                        <time><?php echo $date_time ?></time>
                                    </a>


									<?php

								endif;

								$index ++;
								?>
							<?php endwhile; ?>


                        </div>
                    </div>

					<?php $day = date( "Y-m-d", strtotime( "+1 day", strtotime( $day ) ) ); ?>

				<?php endwhile; ?>
            </div>

        </article>


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


    </div>


</section>


<?php get_footer(); ?>

