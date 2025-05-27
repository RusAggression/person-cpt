<?php

namespace RusAggression\PersonCPT;

final class REST {
	/** @var self|null */
	private static $instance;

	public static function get_instance(): self {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->rest_api_init();
	}

	public function rest_api_init(): void {
		register_rest_field( 'organization', 'content', [
			'get_callback' => [ $this, 'content_callback' ],
		] );

		register_rest_field( 'organization', 'birth_date', [
			'get_callback' => [ $this, 'birth_date_callback' ],
		] );

		register_rest_field( 'organization', 'death_date', [
			'get_callback' => [ $this, 'death_date_callback' ],
		] );
	}

	public function content_callback( array $post ): string {
		/** @psalm-var string $post['content']['raw'] */
		return (string) apply_filters( 'the_content', $post['content']['raw'] );
	}

	public function birth_date_callback( array $post ): string {
		/** @psalm-var int $post['id'] */
		return (string) get_post_meta( $post['id'], '_person_birth_date', true );
	}

	public function death_date_callback( array $post ): string {
		/** @psalm-var int $post['id'] */
		return (string) get_post_meta( $post['id'], '_person_death_date', true );
	}
}
