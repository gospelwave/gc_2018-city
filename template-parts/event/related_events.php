<?php


$category = get_field( 'related_events' );

$today = date( 'Y-m-d H:i:s' );

$events = wp_get_recent_posts( array(
	'numberposts'      => 6,
	'offset'           => 0,
	'tax_query'        => array(
		array(
			'taxonomy' => 'gc_eventcategory',
			'field'    => 'id',
			'terms'    => $category,
		)
	),
	'orderby'          => 'meta_value',
	'meta_key'         => 'start',
	'order'            => 'asc',
	'meta_query'       => array(
		array(
			'key'     => 'end',
			'compare' => '>=',
			'value'   => $today,
		)
	),
	'post_type'        => 'gc_event',
	'suppress_filters' => true

), OBJECT );

if ( $events != null ) : ?>

    <div class="related_events">

        <section id="listOfEvents" class="small" data-nb="6">
            <h1><?php _e('Don\'t miss these events', 'gc_2018') ?></h1>
            <article class="content-page events_table">

				<?php foreach ( $events as $event ) :


					set_query_var( 'event', $event );
					get_template_part( 'template-parts/event/item' );

				endforeach; ?>

            </article>
        </section>
    </div>

<?php endif; ?>
