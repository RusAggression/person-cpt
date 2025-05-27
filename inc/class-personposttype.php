<?php

namespace RusAggression\PersonCPT;

final class PersonPostType {
	public static function register(): void {
		if ( post_type_exists( 'person' ) ) {
			return;
		}

		$labels = [
			'name'               => _x( 'Persons', 'post type general name', 'person-cpt' ),
			'singular_name'      => _x( 'Person', 'post type singular name', 'person-cpt' ),
			'menu_name'          => _x( 'Persons', 'admin menu', 'person-cpt' ),
			'add_new'            => _x( 'Add New', 'database', 'person-cpt' ),
			'add_new_item'       => __( 'Add New Person', 'person-cpt' ),
			'edit_item'          => __( 'Edit Person', 'person-cpt' ),
			'new_item'           => __( 'New Person', 'person-cpt' ),
			'view_item'          => __( 'View Person', 'person-cpt' ),
			'search_items'       => __( 'Search Persons', 'person-cpt' ),
			'not_found'          => __( 'No persons found', 'person-cpt' ),
			'not_found_in_trash' => __( 'No persons found in trash', 'person-cpt' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'publicly_queryable' => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'person' ],
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'supports'           => [ 'title', 'thumbnail' ],
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-businessperson',
			'show_in_rest'       => true,
			'rest_base'          => 'persons',
		];

		register_post_type( 'person', $args );
	}

	public static function unregister(): void {
		unregister_post_type( 'person' );
	}
}
