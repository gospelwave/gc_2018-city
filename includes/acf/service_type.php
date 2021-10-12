<?php

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_5a4cb25a67818',
		'title' => 'Service type',
		'fields' => array(
			array(
				'key' => 'field_5a4cb25a6d432',
				'label' => 'Service Picture',
				'name' => 'service_picture',
				'type' => 'image',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '49',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array',
				'preview_size' => 'summary',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => '',
			),
			array(
				'key' => 'field_5a4d0d17f8953',
				'label' => 'Acronym',
				'name' => 'acronym',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '51',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => 3,
			),
			array(
				'key' => 'field_5a4cb25a6d44c',
				'label' => 'Service presentation',
				'name' => 'description',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '61',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => 3,
				'new_lines' => 'wpautop',
			),
			array(
				'key' => 'field_5a4cb25a6d458',
				'label' => 'Service location',
				'name' => 'location',
				'type' => 'post_object',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '51',
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
				'return_format' => 'object',
				'ui' => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'gc_servicecategory',
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