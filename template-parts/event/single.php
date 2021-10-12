<?php get_header(); ?>

<?php

$post = get_post();


$categories = get_the_terms( $post->ID, 'gc_eventcategory' );

foreach ( $categories as $category ) :

	$parent_cat = $category;

	while ( $parent_cat != null ) {

		$current_cat = get_term( $parent_cat, 'gc_eventcategory' );

		$parent_cat = $current_cat->parent;

	}

endforeach; ?>

<?php

if ( have_posts() ) :

	/* Start the Loop */
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/event/event', 'single-gc_event' );

	endwhile; ?>

	<?php

else :

	get_template_part( 'template-parts/event/none' );

endif;
?>

<?php get_footer(); ?>