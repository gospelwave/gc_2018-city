<?php

/**
 * Create Location
 */
function create_location() {
	register_post_type( 'gc_location',
		array(
			'labels'              => array(
				'name'          => __( 'Locations', 'gc_2018' ),
				'singular_name' => __( 'Location', 'gc_2018' ),
				'add_new'       => __( 'Add a location', 'gc_2018' ),
				'all_items'     => __( 'All locations', 'gc_2018' ),
				'add_new_item'  => __( 'Add New Location', 'gc_2018' ),
				'edit_item'     => __( 'Edit the location', 'gc_2018' ),
			),
			'public'              => true,
			'can_export'          => true,
			'show_ui'             => true,
			'_builtin'            => false,
			'has_archive'         => false,
			'publicly_queryable'  => false,
			'query_var'           => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => false,
			'menu_icon'           => 'dashicons-location-alt',
			'exclude_from_search' => false,
		)
	);
}

add_action( 'init', 'create_location' );


/**
 * Update Location
 *
 * @param $post_id
 */
function update_location( $post_id ) {

	$post_type = get_post_type( $post_id );


	if ( $post_type != "gc_location" ) {
		return;
	}

	$my_post = array(
		'ID'         => $post_id,
		'post_title' => get_field( 'name', $post_id ),
		'post_name'  => $post_id
	);


	// unhook this function so it doesn't loop infinitely
	remove_action( 'save_post', 'update_location' );

	// update the post, which calls save_post again
	wp_update_post( $my_post );

	// re-hook this function
	add_action( 'save_post', 'update_location' );


}

add_action( 'save_post', 'update_location' );


/**
 * Location column
 *
 * @param $columns
 *
 * @return array
 */
function gc_location_column( $columns ) {

	$columns = array(
		'cb'       => '<input type="checkbox" />',
		//'title'           => 'Title',
		'name_col' => __( 'Name', 'gc_2018' ),
		'city'     => __( 'City', 'gc_2018' ),
		'country'  => __( 'Country', 'gc_2018' ),

	);

	return $columns;
}

add_filter( 'manage_edit-gc_location_columns', 'gc_location_column' );


/**
 * Service column content
 *
 * @param $column
 */
function gc_location_custom_column( $column ) {
	global $post;

	if ( $column == 'name_col' ) {

		$id = $post->ID;

		$txt = get_field( 'name', $post );

		echo "<a class='row-title' href='/wp-admin/post.php?post=$id&action=edit'>$txt</a>";


	} elseif ( $column == 'city' ) {
		echo get_field( 'city', $post );

	} elseif ( $column == 'country' ) {
		echo get_field( 'country', $post );

	}
}

add_action( "manage_posts_custom_column", "gc_location_custom_column" );


/**
 * @param $columns
 *
 * @return mixed
 */
function gc_location_sort_column( $columns ) {
	$columns['name_col'] = 'name';

	//To make a column 'un-sortable' remove it from the array
	//unset($columns['date']);

	return $columns;
}

add_filter( 'manage_edit-gc_location_sortable_columns', 'gc_location_sort_column' );


/**
 * @param $query
 */
function gc_location_orderby( $query ) {
	if ( ! is_admin() ) {
		return;
	}

	if ( ! is_single() && isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'gc_location' ) {

		//$orderby = $query->get( 'orderby' );

		$query->set( 'meta_key', 'name' );
		$query->set( 'orderby', 'meta_value' );


	}


}

add_action( 'pre_get_posts', 'gc_location_orderby' );