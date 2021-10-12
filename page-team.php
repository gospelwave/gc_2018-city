<?php /* Template Name: Team */ ?>

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


    <div class="platter" id="team">
        <nav class="sub-nav">
			<?php

			wp_nav_menu( array(
				'theme_location' => 'principal'
			) );

			?>
        </nav>

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


		<?php if ( have_rows( 'teams' ) ): ?>
            <section class="teams">
				<?php while ( have_rows( 'teams' ) ): the_row();

					$members  = get_sub_field( 'team_members' );
					$show_bio = get_sub_field( 'show_bio' );

					$class = '';

					if ( $show_bio ) {
						$class .= 'show-bio';
					}

					?>

                    <article class="team <?php echo $class; ?>">
                        <h2><?php the_sub_field( 'title' ) ?></h2>

						<?php foreach ( $members as $member ):

							$image = get_people( $member['value'] )['picture'];
							$name = get_people( $member['value'] )['name'];
							$bio = get_people( $member['value'] )['bio'];

							$class_has_bio = '';

							if ( $bio ) {
								$class_has_bio .= 'has-bio';
							}

							?>

                            <div class="member <?php echo $class_has_bio; ?>">
                                <div class="left">
                                    <div class="image">
                                        <div class="bg"
                                             style="background-image: url('<?php echo $image['sizes']['speaker'] ?>')"></div>
                                    </div>
                                </div>
                                <div class="txt">
                                    <h3 class="name"><?php echo $name; ?></h3>
                                    <div class="bio">
                                        <div class="close">
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <h4><?php echo $name; ?></h4>
										<?php echo $bio; ?>
                                    </div>
                                </div>

                            </div>

						<?php endforeach; ?>
                    </article>

				<?php endwhile; ?>
            </section>
		<?php endif; ?>


    </div>


</section>


<?php get_footer(); ?>

