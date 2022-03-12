<?php
if ( ! function_exists('property_cpt') ) {

// Register Custom Post Type
function property_cpt() {

	$labels = array(
		'name'                  => _x( 'Propiedades', 'Post Type General Name', 'ibars_easybroker_sync' ),
		'singular_name'         => _x( 'Propiedad', 'Post Type Singular Name', 'ibars_easybroker_sync' ),
		'menu_name'             => __( 'Propiedades', 'ibars_easybroker_sync' ),
		'name_admin_bar'        => __( 'Propiedad', 'ibars_easybroker_sync' ),
		'archives'              => __( 'Item Archives', 'ibars_easybroker_sync' ),
		'attributes'            => __( 'Item Attributes', 'ibars_easybroker_sync' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ibars_easybroker_sync' ),
		'all_items'             => __( 'Todas las propiedades', 'ibars_easybroker_sync' ),
		'add_new_item'          => __( 'Agregar propiedad', 'ibars_easybroker_sync' ),
		'add_new'               => __( 'Agregar nuevo', 'ibars_easybroker_sync' ),
		'new_item'              => __( 'Nueva propiedad', 'ibars_easybroker_sync' ),
		'edit_item'             => __( 'Editar propiedad', 'ibars_easybroker_sync' ),
		'update_item'           => __( 'Update Item', 'ibars_easybroker_sync' ),
		'view_item'             => __( 'View Item', 'ibars_easybroker_sync' ),
		'view_items'            => __( 'View Items', 'ibars_easybroker_sync' ),
		'search_items'          => __( 'Search Item', 'ibars_easybroker_sync' ),
		'not_found'             => __( 'Not found', 'ibars_easybroker_sync' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ibars_easybroker_sync' ),
		'insert_into_item'      => __( 'Insert into item', 'ibars_easybroker_sync' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'ibars_easybroker_sync' ),
		'items_list'            => __( 'Items list', 'ibars_easybroker_sync' ),
		'items_list_navigation' => __( 'Items list navigation', 'ibars_easybroker_sync' ),
		'filter_items_list'     => __( 'Filter items list', 'ibars_easybroker_sync' ),
	);
	$args = array(
		'label'                 => __( 'Propiedad', 'ibars_easybroker_sync' ),
		'description'           => __( 'Propiedades de EasyBroker', 'ibars_easybroker_sync' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'custom-fields','thumbnail' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-home',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
		'rest_base'             => 'properties',
	);
	register_post_type( 'property', $args );

}
add_action( 'init', 'property_cpt', 0 );

}