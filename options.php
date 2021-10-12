<?php

add_action( 'acf/init', 'my_acf_init' );

function my_acf_init() {

	if ( function_exists( 'acf_add_options_page' ) ) {


		/**
		 * Recurrence
		 */
		acf_add_options_sub_page( array(
			'page_title'  => __( 'Recurrence', 'my_text_domain' ),
			'menu_title'  => __( 'Recurrence', 'my_text_domain' ),
			'parent_slug' => 'edit.php?post_type=gc_service',
			'menu_slug'   => 'recurrence',
			'capability'  => 'edit_posts',
			'autoload'    => true,

		) );


	}

}


function add_service( $fields ) {


	$type = get_term_by( 'id', $fields['type'], 'gc_servicecategory' );

	// Create post object
	$my_post = array(
		'post_status' => 'publish',
		'post_type'   => 'gc_service',
		'post_title'  => $type->name,
		'tax_input'   => array(
			'gc_servicecategory' => array($type->term_id),
		),
	);

	$start_date = date_format( $fields['start'], 'Ymd' );
	$end_date   = date_format( $fields['end'], 'Ymd' );
	$start_time = date_format( $fields['start'], 'H:i:s' );
	$end_time   = date_format( $fields['end'], 'H:i:s' );

	// Insert the post into the database
	$new_post_id = wp_insert_post( $my_post );

	// Update service type
	//$term_id = wp_set_object_terms( $new_post_id, $fields['type'], 'gc_servicecategory' );

	// Update location
	update_field( 'location', $fields['location'], $new_post_id );

	// Update date
	update_field( 'start_date', $start_date, $new_post_id );
	update_field( 'start_time', $start_time, $new_post_id );
	update_field( 'end_date', $end_date, $new_post_id );
	update_field( 'end_time', $end_time, $new_post_id );
	update_field( 'start', date_format( $fields['start'], 'Y-m-d H:i:s' ), $new_post_id );
	update_field( 'end', date_format( $fields['end'], 'Y-m-d H:i:s' ), $new_post_id );

}


function update_options( $post_id ) {
	$screen = get_current_screen();

	if ( $screen->id != "gc_service_page_recurrence" ) {
		return;
	}

	// Get fields
	$type       = get_field( 'type', $post_id );
	$quantity   = get_field( 'quantity', $post_id );
	$location   = get_field( 'location', $post_id );
	$start_date = get_field( 'start_date', $post_id );
	$start_time = get_field( 'start_time', $post_id );
	$end_date   = get_field( 'end_date', $post_id );
	$end_time   = get_field( 'end_time', $post_id );


	// Set end date
	if ( $end_date == null ) {
		$end_date = $start_date;
	}

	// Set end time
	if ( $end_time == null ) {
		$end_time = $start_time;
	}

	// Set start and end date
	$start = new DateTime( $start_date . " " . $start_time );
	$end   = new DateTime( $end_date . " " . $end_time );


	// Add first service
	add_service( array(
		'type'     => $type,
		'location' => $location,
		'start'    => $start,
		'end'      => $end,
	) );


	// Add next services
	for ( $i = 2; $i <= $quantity; $i ++ ) {

		$interval   = new DateInterval( 'P1W' );
		$next_start = $start->add( $interval );
		$next_end   = $end->add( $interval );

		add_service( array(
			'type'     => $type,
			'location' => $location,
			'start'    => $next_start,
			'end'      => $next_end,
		) );

	}


}

add_action( 'acf/save_post', 'update_options', 20 );