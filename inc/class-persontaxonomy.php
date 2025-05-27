<?php

namespace RusAggression\PersonCPT;

final class PersonTaxonomy {
	public static function register(): void {
		if ( taxonomy_exists( 'individual' ) ) {
			return;
		}

		$labels = [
			'name'                       => _x( 'Persons', 'Taxonomy General Name', 'person-cpt' ),
			'singular_name'              => _x( 'Person', 'Taxonomy Singular Name', 'person-cpt' ),
			'menu_name'                  => __( 'Persons', 'person-cpt' ),
			'all_items'                  => __( 'All Persons', 'person-cpt' ),
			'new_item_name'              => __( 'New Person Name', 'person-cpt' ),
			'add_new_item'               => __( 'Add Person', 'person-cpt' ),
			'edit_item'                  => __( 'Edit Person', 'person-cpt' ),
			'update_item'                => __( 'Update Person', 'person-cpt' ),
			'view_item'                  => __( 'View Person', 'person-cpt' ),
			'separate_items_with_commas' => __( 'Separate persons with commas', 'person-cpt' ),
			'add_or_remove_items'        => __( 'Add or remove persons', 'person-cpt' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'person-cpt' ),
			'popular_items'              => __( 'Popular Persons', 'person-cpt' ),
			'search_items'               => __( 'Search Persons', 'person-cpt' ),
			'not_found'                  => __( 'Not Found', 'person-cpt' ),
			'no_terms'                   => __( 'No persons', 'person-cpt' ),
			'items_list'                 => __( 'Persons list', 'person-cpt' ),
			'items_list_navigation'      => __( 'Persons list navigation', 'person-cpt' ),
		];

		$args = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_menu'      => true,
			'show_in_nav_menus' => false,
			'show_in_rest'      => true,
			'show_tagcloud'     => false,
		];

		register_taxonomy( 'individual', [ 'post' ], $args );
	}

	public static function unregister(): void {
		unregister_taxonomy( 'individual' );
	}
}
