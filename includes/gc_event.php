<?php

/**
 * Create Event
 */
function create_events() {
	register_post_type( 'gc_event',
		array(
			'labels'              => array(
				'name'          => __( 'Events', 'gc_2018' ),
				'singular_name' => __( 'Event', 'gc_2018' ),
				'add_new'       => __( 'Add an event', 'gc_2018' ),
				'all_items'     => __( 'All events', 'gc_2018' ),
				'add_new_item'  => __( 'Add New Event', 'gc_2018' ),
				'edit_item'     => __( 'Edit Event', 'gc_2018' ),
			),
			'public'              => true,
			'can_export'          => true,
			'show_ui'             => true,
			'_builtin'            => false,
			'has_archive'         => true,
			'publicly_queryable'  => true,
			'query_var'           => true,
			'rewrite'             => array(
				"slug"       => "events",
				'with_front' => false
			),
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => false,
			'menu_icon'           => 'dashicons-calendar-alt',
			'taxonomies'          => array( 'gc_eventcategory' ),
			'exclude_from_search' => false,
		)
	);
}

add_action( 'init', 'create_events' );


/**
 * Event taxonomy
 */
function create_eventcategory_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Event categories', 'taxonomy general name', 'gc_2018' ),
		'singular_name'              => _x( 'Event category', 'taxonomy singular name', 'gc_2018' ),
		'search_items'               => __( 'Search Categories', 'gc_2018' ),
		'popular_items'              => __( 'Popular Categories', 'gc_2018' ),
		'all_items'                  => __( 'All event categories', 'gc_2018' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Category', 'gc_2018' ),
		'update_item'                => __( 'Update Category', 'gc_2018' ),
		'add_new_item'               => __( 'Add New Category', 'gc_2018' ),
		'new_item_name'              => __( 'New Category Name', 'gc_2018' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'gc_2018' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'gc_2018' ),
		'choose_from_most_used'      => __( 'Choose from the most used categories', 'gc_2018' ),
	);

	register_taxonomy( 'gc_eventcategory', 'gc_event', array(
		'label'        => __( 'Event Category', 'gc_2018' ),
		'labels'       => $labels,
		'hierarchical' => true,
		'description'  => null,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array( 'slug' => 'event-category' ),
	) );
}

add_action( 'init', 'create_eventcategory_taxonomy', 0 );


/**
 *
 */
function gc_event_remove_custom_taxonomy() {
	remove_meta_box( 'gc_eventcategorydiv', 'gc_event', 'side' );

	// $custom_taxonomy_slug is the slug of your taxonomy, e.g. 'genre' )
	// $custom_post_type is the "slug" of your post type, e.g. 'movies' )
}

add_action( 'admin_menu', 'gc_event_remove_custom_taxonomy' );


/**
 * Update Event
 *
 * @param $post_id
 */
function update_event( $post_id ) {


	$post_type = get_post_type( $post_id );


	if ( ( $post_type != "gc_event" ) || ( empty( $_POST ) ) ) {
		return $post_id;
	}

	$cat = get_the_terms( $post_id, array( 'taxonomy' => 'gc_eventcategory' ) )[0];

	$event_title = get_field( 'event_title', $post_id );


	switch ( get_field( 'weekend_prog', $post_id ) ) {
		case 'category':

			if ( get_field( 'weekend_prog', $cat ) ) {
				$weekend_show = true;
			} else {
				$weekend_show = false;
			}

			break;
		case 'show':
			$weekend_show = true;
			break;
		default:
			$weekend_show = false;
			break;
	}

	switch ( get_field( 'events_page', $post_id ) ) {
		case 'category':

			if ( get_field( 'events_page', $cat ) ) {
				$events_show = true;
			} else {
				$events_show = false;
			}

			break;
		case 'show':
			$events_show = true;
			break;
		default:
			$events_show = false;
			break;
	}


	if ( $event_title == null ) {
		$title = $cat->name;
	} else {
		$title = $event_title;
	}


	$my_post = array(
		'ID'         => $post_id,
		'post_title' => $title,
		'post_name'  => $post_id
	);


	// unhook this function so it doesn't loop infinitely
	remove_action( 'save_post', 'update_event' );

	update_dates( $post_id );

	// update the post, which calls save_post again
	wp_update_post( $my_post );

	update_field( 'weekend_show', $weekend_show, $post_id );

	update_field( 'events_show', $events_show, $post_id );

	// re-hook this function
	add_action( 'save_post', 'update_event' );


}

add_action( 'save_post', 'update_event' );


/**
 * Custom redirect on taxonomy term update, keeps users on the term page for additional updates
 *
 * @param $term_id
 * @param $taxonomy
 */
function post_save_cat_event( $term_id, $taxonomy ) {

	$my_taxonomy_slug = 'gc_eventcategory';


	$args = array(
		'numberposts' => 100,
		'post_type' >= 'any',
		'tax_query'   => array(
			array(
				'taxonomy' => $my_taxonomy_slug,
				'field'    => 'term_id',
				'terms'    => $term_id,
			)
		)
	);


	$query = new WP_Query( $args );

	$events = $query->get_posts();

	/* Restore original Post Data */
	wp_reset_postdata();


	foreach ( $events as $event ) {

		$id = $my_taxonomy_slug . '_' . $term_id;


		if ( get_field( 'weekend_prog', $event ) == 'category' ) {
			update_field( 'weekend_show', get_field( 'weekend_prog', $id ), $event->ID );
		}

		if ( get_field( 'events_page', $event ) == 'category' ) {
			update_field( 'events_show', get_field( 'events_page', $id ), $event->ID );
		}

		update_field( 'event_service_type', get_field( 'event_service_type', $id ), $event->ID );

		//var_dump( $event );
	}

	wp_safe_redirect( admin_url( 'edit-tags.php?action=edit&taxonomy=' . $my_taxonomy_slug . '&&post_type=gc_event&tag_ID=' . $term_id . '&notice=success' ) );

	exit;
}

add_action( 'edited_gc_eventcategory', 'post_save_cat_event', 10, 2 );

/**
 * Event column
 *
 * @param $columns
 *
 * @return array
 */
function gc_event_column( $columns ) {

	$columns = array(
		'cb'          => '<input type="checkbox" />',
		//'title'      => 'Title',
		'event_title' => __('Title', 'gc_2018'),
		'event_date'  => __('Date', 'gc_2018'),
		'event_time'  => __('Time', 'gc_2018'),
		'category'    => __('Category', 'gc_2018'),


	);

	return $columns;
}

add_filter( 'manage_edit-gc_event_columns', 'gc_event_column' );


/**
 * Event column content
 *
 * @param $column
 */
function gc_event_custom_column( $column ) {
	global $post;

	$curr_cat = get_query_var( "gc_eventcategory" );

	$start = get_field( 'start', $post );
	$end   = get_field( 'end', $post );

	$start_o = new DateTime( $start );
	$end_o   = new DateTime( $end );

	$start_t = $start_o->getTimestamp();
	$end_t   = $end_o->getTimestamp();

	if ( $column == "event_title" ) {

		$id = $post->ID;

		$txt = get_the_title();

		echo "<a class='row-title' href='/wp-admin/post.php?post=$id&action=edit'>$txt</a>";

	} elseif ( $column == 'event_date' ) {

		echo complex_date( $start, $end );

		//echo date_i18n( get_option( 'date_format' ), strtotime( get_field( 'start_date', $post ) ) );

	} elseif ( $column == 'event_time' ) {

		if ( date( 'H:i', $start_t ) != '00:00' ) {

			if ( date( 'H:i', $start_t ) != date( 'H:i', $end_t ) ) {
				echo time_trans( $start_o ) . " - " . time_trans( $end_o );
			} else {
				echo time_trans( $start_o );
			}
		}

	} elseif ( $column == 'category' ) {

		foreach ( get_the_terms( $post, array( 'taxonomy' => 'gc_eventcategory' ) ) as $cat ) {

			$name  = $cat->name;
			$slug  = $cat->slug;
			$class = "";

			if ( $curr_cat == $slug ) {
				$class = 'current';
			}

			echo "<a class='$class' href='edit.php?post_type=gc_event&gc_eventcategory=$slug'>$name</a>";


		}

	}
}

add_action( "manage_posts_custom_column", "gc_event_custom_column" );


/**
 * @param $columns
 *
 * @return mixed
 */
function gc_event_sort_column( $columns ) {
	$columns['event_date'] = 'event_date';

	return $columns;
}

add_filter( 'manage_edit-gc_event_sortable_columns', 'gc_event_sort_column' );


/**
 * Order Event
 *
 * @param $query
 *
 * @return mixed
 */
function gc_order_events( $query ) {


	if ( isset( $_GET['post_status'] ) ) {
		$post_status = $_GET['post_status'];
	} else {
		$post_status = 'all';
	}

	$query = order_dates( $query, 'gc_event', 'gc_eventcategory', $post_status );

	return $query;


}

add_action( 'pre_get_posts', 'gc_order_events' );


function default_content_event( $content, $post ) {

	if ( $post->post_type != 'gc_event' ) {
		return $content;
	}

	return $content;


}

add_filter( 'default_content', 'default_content_event', 10, 2 );