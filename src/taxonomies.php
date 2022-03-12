<?php

if ( ! function_exists( 'property_custom_taxonomy' ) ) {

// Register categorias de propiedad
function property_custom_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Tipos de propiedad', 'Taxonomy General Name', 'ibars-easybroker' ),
		'singular_name'              => _x( 'Tipo de propiedad', 'Taxonomy Singular Name', 'ibars-easybroker' ),
		'menu_name'                  => __( 'Tipo de Propiedad', 'ibars-easybroker' ),
		'all_items'                  => __( 'All Items', 'ibars-easybroker' ),
		'parent_item'                => __( 'Parent Item', 'ibars-easybroker' ),
		'parent_item_colon'          => __( 'Parent Item:', 'ibars-easybroker' ),
		'new_item_name'              => __( 'New Item Name', 'ibars-easybroker' ),
		'add_new_item'               => __( 'Add New Item', 'ibars-easybroker' ),
		'edit_item'                  => __( 'Edit Item', 'ibars-easybroker' ),
		'update_item'                => __( 'Update Item', 'ibars-easybroker' ),
		'view_item'                  => __( 'View Item', 'ibars-easybroker' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ibars-easybroker' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ibars-easybroker' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ibars-easybroker' ),
		'popular_items'              => __( 'Popular Items', 'ibars-easybroker' ),
		'search_items'               => __( 'Search Items', 'ibars-easybroker' ),
		'not_found'                  => __( 'Not Found', 'ibars-easybroker' ),
		'no_terms'                   => __( 'No items', 'ibars-easybroker' ),
		'items_list'                 => __( 'Items list', 'ibars-easybroker' ),
		'items_list_navigation'      => __( 'Items list navigation', 'ibars-easybroker' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
		'rest_base'                  => 'property_type',
	);
	register_taxonomy( 'property_type', array( 'property' ), $args );

}
add_action( 'init', 'property_custom_taxonomy', 0 );

}


if ( ! function_exists( 'property_custom_tags' ) ) {

	// Register etiquetas de propiedad
	function property_custom_tags() {
	
		$labels = array(
			'name'                       => _x( 'Etiquetas de propiedad', 'Taxonomy General Name', 'ibars-easybroker' ),
			'singular_name'              => _x( 'Etiqueta de propiedad', 'Taxonomy Singular Name', 'ibars-easybroker' ),
			'menu_name'                  => __( 'Etiquetas de Propiedad', 'ibars-easybroker' ),
			'all_items'                  => __( 'All Items', 'ibars-easybroker' ),
			'parent_item'                => __( 'Parent Item', 'ibars-easybroker' ),
			'parent_item_colon'          => __( 'Parent Item:', 'ibars-easybroker' ),
			'new_item_name'              => __( 'New Item Name', 'ibars-easybroker' ),
			'add_new_item'               => __( 'Add New Item', 'ibars-easybroker' ),
			'edit_item'                  => __( 'Edit Item', 'ibars-easybroker' ),
			'update_item'                => __( 'Update Item', 'ibars-easybroker' ),
			'view_item'                  => __( 'View Item', 'ibars-easybroker' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'ibars-easybroker' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'ibars-easybroker' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'ibars-easybroker' ),
			'popular_items'              => __( 'Popular Items', 'ibars-easybroker' ),
			'search_items'               => __( 'Search Items', 'ibars-easybroker' ),
			'not_found'                  => __( 'Not Found', 'ibars-easybroker' ),
			'no_terms'                   => __( 'No items', 'ibars-easybroker' ),
			'items_list'                 => __( 'Items list', 'ibars-easybroker' ),
			'items_list_navigation'      => __( 'Items list navigation', 'ibars-easybroker' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
			'show_in_rest'               => true,
			'rest_base'                  => 'property_tag',
		);
		register_taxonomy( 'property_tag', array( 'property' ), $args );
	
	}
	add_action( 'init', 'property_custom_tags', 0 );
	
	}