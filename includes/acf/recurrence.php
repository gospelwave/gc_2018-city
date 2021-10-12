<?php

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_5a09bbcd43255',
		'title' => 'Recurrence',
		'fields' => array(
			array(
				'key' => 'field_5a09cc519d6fb',
				'label' => 'Type',
				'name' => 'type',
				'type' => 'taxonomy',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '61',
					'class' => '',
					'id' => '',
				),
				'taxonomy' => 'gc_servicecategory',
				'field_type' => 'select',
				'allow_null' => 0,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'multiple' => 0,
			),
			array(
				'key' => 'field_5a09d88b091db',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '61',
					'class' => '',
					'id' => '',
				),
				'post_type' => array(
					0 => 'gc_location',
				),
				'taxonomy' => array(
				),
				'allow_null' => 1,
				'multiple' => 0,
				'return_format' => 'id',
				'ui' => 1,
			),
			array(
				'key' => 'field_5a09d1c7cdc24',
				'label' => 'Dates',
				'name' => 'dates',
				'type' => 'clone',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'clone' => array(
					0 => 'group_5a04aa94d7fc4',
				),
				'display' => 'seamless',
				'layout' => 'block',
				'prefix_label' => 0,
				'prefix_name' => 0,
			),
			array(
				'key' => 'field_5a09bbfafda4f',
				'label' => 'Quantity',
				'name' => 'quantity',
				'type' => 'number',
				'instructions' => 'How many recurrences?',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '41',
					'class' => '',
					'id' => '',
				),
				'default_value' => 1,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => 52,
				'step' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'recurrence',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;