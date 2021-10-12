<?php get_header(); ?>

<section id="content">

    <?php if (get_field('bg_image')): ?>

        <article class="title">
            <div class="image"
                 style="background-image: url('<?php echo get_field('bg_image')['sizes']['header']; ?>')"></div>
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

    <div class="platter">
        <nav class="sub-nav">
            <?php

            wp_nav_menu(array(
                'theme_location' => 'principal'
            ));

            ?>
        </nav>

        <?php
        // TO SHOW THE PAGE CONTENTS
        while (have_posts()) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
            <article class="content-page">

                <?php
                echo '<div class="content">';
                the_content();
                echo '</div>';
                ?> <!-- Page Content -->

            </article><!-- .entry-content-page -->


        <?php
        endwhile; //resetting the page loop
        wp_reset_query(); //resetting the page query
        ?>


    </div>


    <?php require_once(__DIR__ . '/template-parts/event/related_events.php'); ?>


</section>


<?php get_footer(); ?>

