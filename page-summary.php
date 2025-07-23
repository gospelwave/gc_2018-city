<?php /* Template Name: Summary */ ?>

<?php get_header(); ?>

<section id="content">

    <?php if (get_field('bg_image')): ?>

        <article class="title">
            <div class="image"
                 style="background-image: url('<?php echo get_field('bg_image')['sizes']['header_hd']; ?>')"></div>
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


    <div class="platter" id="summary">
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

                if (get_the_content() != null) {
                    echo '<div class="content">';
                    ?>
                    <?php if (get_field('main_video')): ?>

                        <section class="player" style="max-width: 1200px; margin: 0 auto" data-width="1920">
                            <div class="container" style="padding-bottom: 56.25%">
                                <?php echo get_field('main_video'); ?>
                            </div>
                        </section>

                    <?php endif; ?>
                    <?php
                    the_content();
                    echo '</div>';
                }


                ?> <!-- Page Content -->

            </article><!-- .entry-content-page -->


        <?php
        endwhile; //resetting the page loop
        wp_reset_query(); //resetting the page query
        ?>


        <?php if (have_rows('sections')): ?>
            <section class="sections">
                <?php while (have_rows('sections')): the_row();

                    ?>

                    <article class="item">
                        <?php if (get_sub_field('video')): ?>
                            <div class="video">
                                <section class="player" style="margin: 0 auto" data-width="1920">
                                    <div class="container">
                                        <?php echo get_sub_field('video'); ?>
                                    </div>
                                </section>
                            </div>
                        <?php else: ?>
                            <div class="pic">
                                <div class="image"
                                     style="background-image: url('<?php echo get_sub_field('image')['sizes']['summary']; ?>')"></div>
                            </div>
                        <?php endif; ?>
                        <div class="content">
                            <div class="txt">
                                <h2><?php the_sub_field('title'); ?></h2>
                                <p><?php the_sub_field('text'); ?></p>

                                <?php if (get_sub_field('link_page') != null || get_sub_field('link_url') != null):

                                    $link_url = get_sub_field('link_url');
                                    $link_page = get_sub_field('link_page');
                                    $button_text = get_sub_field('button');

                                    if ($link_page != null) {
                                        $button_url = $link_page;

                                        $target = "_self";
                                    } else {
                                        $button_url = $link_url;

                                        $target = "_blank";
                                    }

                                    ?>
                                    <a target="<?php echo $target; ?>" href="<?php echo $button_url; ?>"
                                       class="dynamic"><?php echo $button_text; ?></a>

                                <?php endif; ?>
                            </div>
                        </div>

                    </article>

                <?php endwhile; ?>
            </section>
        <?php endif; ?>


    </div>


</section>


<?php get_footer(); ?>

