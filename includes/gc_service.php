<?php

/**
 * Create Service
 */
function create_services() {
	register_post_type( 'gc_service',
		array(
			'labels'              => array(
				'name'          => __( 'Services', 'gc_2018' ),
				'singular_name' => __( 'Service', 'gc_2018' ),
				'add_new'       => __( 'Add a service', 'gc_2018' ),
				'all_items'     => __( 'All services', 'gc_2018' ),
				'add_new_item'  => __( 'Add New Service', 'gc_2018' ),
				'edit_item'     => __( 'Edit Service', 'gc_2018' ),
			),
			'public'              => true,
			'can_export'          => true,
			'show_ui'             => true,
			'_builtin'            => false,
			'has_archive'         => true,
			'publicly_queryable'  => true,
			'query_var'           => true,
			'rewrite'             => array(
				"slug"       => "services",
				'with_front' => false
			),
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => false,
			'menu_icon'           => 'dashicons-megaphone',
			'taxonomies'          => array( 'gc_servicecategory' ),
			'exclude_from_search' => false,
		)
	);
}

add_action( 'init', 'create_services' );


/**
 * Service taxonomy
 */
function create_servicecategory_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Service types', 'taxonomy general name', 'gc_2018' ),
		'singular_name'              => _x( 'Service type', 'taxonomy singular name', 'gc_2018' ),
		'search_items'               => __( 'Search Service Type', 'gc_2018' ),
		'popular_items'              => __( 'Popular Service Types', 'gc_2018' ),
		'all_items'                  => __( 'All Service types', 'gc_2018' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Type', 'gc_2018' ),
		'update_item'                => __( 'Update Type', 'gc_2018' ),
		'add_new_item'               => __( 'Add New Service Type', 'gc_2018' ),
		'new_item_name'              => __( 'New Service Type', 'gc_2018' ),
		'separate_items_with_commas' => __( 'Separate services with commas', 'gc_2018' ),
		'add_or_remove_items'        => __( 'Add or remove services', 'gc_2018' ),
		'choose_from_most_used'      => __( 'Choose from the most used services', 'gc_2018' ),
	);

	register_taxonomy( 'gc_servicecategory', 'gc_service', array(
		'label'        => __( 'Service Type', 'gc_2018' ),
		'labels'       => $labels,
		'hierarchical' => true,
		'show_ui'      => true,
		'query_var'    => true,
		'rewrite'      => array( 'slug' => 'service-type' ),
	) );
}

add_action( 'init', 'create_servicecategory_taxonomy', 0 );


function gc_service_remove_custom_taxonomy() {
	remove_meta_box( 'gc_servicecategorydiv', 'gc_service', 'side' );

	// $custom_taxonomy_slug is the slug of your taxonomy, e.g. 'genre' )
	// $custom_post_type is the "slug" of your post type, e.g. 'movies' )
}

add_action( 'admin_menu', 'gc_service_remove_custom_taxonomy' );


/**
 * Update Service
 *
 * @param $post_id
 */
function update_service( $post_id ) {

	$post_type = get_post_type( $post_id );


	if ( $post_type != "gc_service" ) {
		return;
	}


	$title = get_field( 'title', $post_id );

	if ( ! $title ) {
		$title = get_the_terms( $post_id, array( 'taxonomy' => 'gc_servicecategory' ) )[0]->name;
	}


	$my_post = array(
		'ID'         => $post_id,
		//'post_title' => date_i18n( get_option( 'date_format' ), strtotime( get_field( 'start', $post_id ) ) ),
		'post_title' => $title,
		'post_name'  => $post_id
	);


	// unhook this function so it doesn't loop infinitely
	remove_action( 'save_post', 'update_service' );

	update_dates( $post_id );

	// update the post, which calls save_post again
	wp_update_post( $my_post );

	// re-hook this function
	add_action( 'save_post', 'update_service' );


}

add_action( 'save_post', 'update_service' );


/**
 * Service column
 *
 * @param $columns
 *
 * @return array
 */
function gc_service_column( $columns ) {

	$columns = array(
		'cb'               => '<input type="checkbox" />',
		//'title'           => 'Date',
		'service_date_col' => __('Date', 'gc_2018'),
		'service_time_col' => __('Time', 'gc_2018'),
		'service_speaker'  => __('Speaker', 'gc_2018'),
		//'service_type'    => 'Type',

	);

	return $columns;
}

add_filter( 'manage_edit-gc_service_columns', 'gc_service_column' );


/**
 * Service column content
 *
 * @param $column
 */
function gc_service_custom_column( $column ) {
	global $post;

	$start = get_field( 'start', $post );
	$end   = get_field( 'end', $post );

	$start_o = new DateTime( $start );
	$end_o   = new DateTime( $end );

	$start_t = $start_o->getTimestamp();
	$end_t   = $end_o->getTimestamp();

	if ( $column == 'service_time_col' ) {

		if ( date( 'H:i', $start_t ) != '00:00' ) {

			if ( date( 'H:i', $start_t ) != date( 'H:i', $end_t ) ) {
				echo time_trans( $start_o ) . " - " . time_trans( $end_o );
			} else {
				echo time_trans( $start_o );
			}
		}

	} elseif ( $column == 'service_type' ) {

		echo get_the_terms( $post, array( 'taxonomy' => 'gc_servicecategory' ) )[0]->name;

	} elseif ( $column == 'service_date_col' ) {

		$id = $post->ID;

		$txt = date_i18n( get_option( 'date_format' ), strtotime( $start ) );

		echo "<a class='row-title' href='/wp-admin/post.php?post=$id&action=edit'>$txt</a>";


	} elseif ( $column == 'service_speaker' ) {

		if ( get_field( 'service_speaker', $post ) ) {
			foreach ( get_field( 'service_speaker', $post ) as $speaker ) {


				if ( is_array( $speaker ) ) {

					echo $speaker['label'] . "<br/>";

				} else {

					echo get_field( 'service_speaker', $post )['label'];

					return;
				}

			}
		} else {
			echo "-";
		}


	}
}

add_action( "manage_posts_custom_column", "gc_service_custom_column" );


/**
 * @param $columns
 *
 * @return mixed
 */
function gc_service_sort_column( $columns ) {
	$columns['service_date_col'] = 'start';

	//To make a column 'un-sortable' remove it from the array
	//unset($columns['date']);

	return $columns;
}

add_filter( 'manage_edit-gc_service_sortable_columns', 'gc_service_sort_column' );


/**
 * @param $views
 *
 * @return mixed
 */
function gc_service_views( $views ) {

	$curr_cat = get_query_var( "gc_servicecategory" );

	unset( $views['publish'] );
	unset( $views['draft'] );
	unset( $views['trash'] );
	unset( $views['pending'] );


	$terms = get_categories( array(
		'taxonomy' => 'gc_servicecategory',
		'orderby'  => 'name',
		'order'    => 'ASC',
	) );

	foreach ( $terms as $term ) {

		$name  = $term->name;
		$slug  = $term->slug;
		$count = $term->count;
		$class = "";

		if ( $curr_cat == $slug ) {
			$class = 'current';
		}

		$views[ $term->name ] = "<a class='$class' href='edit.php?post_type=gc_service&gc_servicecategory=$slug'>$name <span class='count'>($count)</span></a>";


	}


	return $views;

}

add_filter( 'views_edit-gc_service', 'gc_service_views' );


/**
 * @param $query
 *
 * @return mixed
 */
function gc_service_orderby( $query ) {
	$query = order_dates( $query, 'gc_service', 'gc_servicecategory' );

	return $query;


}

add_action( 'pre_get_posts', 'gc_service_orderby' );


/**
 * @param $field
 *
 * @return mixed
 */
function gc_service_load_value( $field ) {


	// Get the current blog id
	$original_blog_id = get_current_blog_id();


	// GC TV
	switch_to_blog( 4 );

	$speakers = get_posts(
		array(
			'post_type'   => 'gc_people',
			'numberposts' => 300,
		)
	);


	$choices = [];


	foreach ( $speakers as $speaker ) {

		$choices[ $speaker->ID ] = $speaker->post_title;
	}

	// Switch back to the current blog
	switch_to_blog( $original_blog_id );


	$field['choices'] = $choices;


	return $field;
}

// acf/load_value - filter for every field load
add_filter( 'acf/load_field/name=service_speaker', 'gc_service_load_value', 10, 3 );

