<?php
/**
 * Save meta‑box data from both tabs.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'save_post_wfb_form', 'wpfb_save_form_meta_data', 10, 2 );

/**
 * Validate & save.
 *
 * @param int     $post_id
 * @param WP_Post $post
 */
function wpfb_save_form_meta_data( int $post_id, WP_Post $post ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['wpfb_form_meta_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['wpfb_form_meta_nonce'], 'wpfb_save_form_meta' ) ) {
		return;
	}

	// Форма отправки
	if ( isset( $_POST['wpfb_form_template'] ) ) {
		update_post_meta(
			$post_id,
			'wpfb_form_template',
			wp_kses_post( $_POST['wpfb_form_template'] )
		);
	}

	// Способ отправки
	if ( isset( $_POST['wpfb_message_template'] ) ) {
		update_post_meta(
			$post_id,
			'wpfb_message_template',
			sanitize_textarea_field( $_POST['wpfb_message_template'] )
		);
	}

	// Доп. контент для select
	if ( isset( $_POST['wpfb_select_extra_html'] ) ) {
		update_post_meta(
			$post_id,
			'wpfb_select_extra_html',
			wp_kses_post( $_POST['wpfb_select_extra_html'] )
		);
	}

	// Фрагмент, который добавляем в save-meta.php
	if ( isset( $_POST['_wfb_fields'] ) ) {
		update_post_meta( $post_id, '_wfb_fields', wp_kses_post( wp_unslash( $_POST['_wfb_fields'] ) ) );
	}

	if ( isset( $_POST['_wfb_settings'] ) ) {
		update_post_meta( $post_id, '_wfb_settings', wp_kses_post( wp_unslash( $_POST['_wfb_settings'] ) ) );
	}

	if ( isset( $_POST['_wfb_actions'] ) ) {
		update_post_meta( $post_id, '_wfb_actions', wp_kses_post( wp_unslash( $_POST['_wfb_actions'] ) ) );
	}

}
