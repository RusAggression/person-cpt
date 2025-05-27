<?php

namespace RusAggression\PersonCPT;

use WP_Post;
use WP_Term;

final class Admin {
	/** @var self|null */
	private static $instance;

	public static function get_instance(): self {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->init();
	}

	public function init(): void {
		add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	public function admin_init(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_filter( 'wp_insert_post_data', [ $this, 'wp_insert_post_data' ] );
		add_action( 'post_updated', [ $this, 'post_updated' ], 10, 3 );
		add_action( 'save_post_person', [ $this, 'save_post_person' ], 10, 2 );
		add_action( 'delete_post_person', [ $this, 'delete_post_person' ], 10, 2 );
	}

	public function add_meta_boxes(): void {
		add_meta_box(
			'person_details',
			__( 'Person Details', 'person-cpt' ),
			[ $this, 'person_meta_box_callback' ],
			'person',
			'normal',
			'high'
		);
	}

	public function person_meta_box_callback( WP_Post $post ): void {
		$params = [
			'id'          => $post->ID,
			'description' => get_post_field( 'post_content', $post->ID, 'edit' ),
			'birth_date'  => (string) get_post_meta( $post->ID, '_person_birth_date', true ),
			'death_date'  => (string) get_post_meta( $post->ID, '_person_death_date', true ),
		];

		self::render( 'person-metabox', $params );
	}

	public function wp_insert_post_data( array $data ): array {
		if ( ! isset( $_POST['person_meta_box_nonce'] ) ||
			! is_string( $_POST['person_meta_box_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_POST['person_meta_box_nonce'] ), 'person_meta_box' )
		) {
			return $data;
		}

		if ( isset( $_POST['person_description'] ) && is_string( $_POST['person_description'] ) ) {
			$data['post_content'] = wp_kses_post( $_POST['person_description'] );
		}

		return $data;
	}

	public function post_updated( int $post_id, WP_Post $post_after, WP_Post $post_before ): void {
		$post_type = get_post_type( $post_id );
		if ( 'person' === $post_type ) {
			$old_title = $post_before->post_title;
			$new_title = $post_after->post_title;

			if ( $old_title !== $new_title && term_exists( $old_title, 'individual' ) ) {
				$term = get_term_by( 'name', $old_title, 'individual' );
				if ( $term instanceof WP_Term ) {
					/** @psalm-suppress InvalidArgument -- name *is* allowed */
					wp_update_term( $term->term_id, 'individual', [ 'name' => $new_title ] );
				}
			}
		}
	}

	public function save_post_person( int $post_id, WP_Post $post ): void {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
			! current_user_can( 'edit_post', $post_id ) ||
			! isset( $_POST['person_meta_box_nonce'] ) ||
			! is_string( $_POST['person_meta_box_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_POST['person_meta_box_nonce'] ), 'person_meta_box' )
		) {
			return;
		}

		$post_title = $post->post_title;
		if ( ! term_exists( $post_title, 'individual' ) ) {
			wp_insert_term( $post_title, 'individual' );
		}

		$fields = [
			'person_birth_date' => '_person_birth_date',
			'person_death_date' => '_person_death_date',
		];

		foreach ( $fields as $field_name => $meta_key ) {
			if ( isset( $_POST[ $field_name ] ) && is_string( $_POST[ $field_name ] ) ) {
				$value = sanitize_text_field( $_POST[ $field_name ] );
				$dt    = strtotime( $value );
				$value = false === $dt ? '' : $value;
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
	}

	/**
	 * @psalm-suppress UnusedParam
	 */
	public function delete_post_person( int $post_id, WP_Post $post ): void /* NOSONAR */ {
		$term = get_term_by( 'name', $post->post_title, 'individual' );
		if ( $term instanceof WP_Term ) {
			wp_delete_term( $term->term_id, 'individual' );
		}
	}

	/**
	 * @psalm-suppress UnusedParam
	 */
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	private static function render( string $template, array $params = [] ): void /* NOSONAR */ {
		/** @psalm-suppress UnresolvableInclude */
		require __DIR__ . '/../views/' . basename( $template ) . '.php'; // NOSONAR
	}
}
