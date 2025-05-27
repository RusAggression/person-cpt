<?php
defined( 'ABSPATH' ) || exit;

/**
 * @psalm-var array{ id: int, description: string, birth_date: string, death_date: string } $params
 */

wp_nonce_field( 'person_meta_box', 'person_meta_box_nonce' );
?>
<p>
	<?php
	wp_editor(
		$params['description'],
		'org_description',
		[
			'media_buttons' => false,
			'textarea_rows' => 10,
			'teeny'         => true,
			'quicktags'     => true,
		]
	);
	?>
</p>
<p>
	<label for="person_birth_date"><?php _e( 'Birth Date:', 'person-cpt' ); ?></label><br/>
	<input type="date" id="person_birth_date" name="person_birth_date" value="<?= esc_attr( $params['birth_date'] ); ?>"/>
</p>
<p>
	<label for="person_death_date"><?php _e( 'Death Date:', 'person-cpt' ); ?></label><br/>
	<input type="date" id="person_death_date" name="person_death_date" value="<?= esc_attr( $params['death_date'] ); ?>"/>
</p>
