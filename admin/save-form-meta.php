<?php

add_action('save_post_wfb_form', 'wfb_save_form_meta', 10, 3);

/**
 * Сохраняет мета-данные формы (поля, настройки, действия)
 */
function wfb_save_form_meta(int $post_id, WP_Post $post, bool $update): void {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	$fields   = $_POST['_wfb_fields'] ?? null;
	$settings = $_POST['_wfb_settings'] ?? null;
	$actions  = $_POST['_wfb_actions'] ?? null;

	if ($fields !== null) {
		update_post_meta($post_id, '_wfb_fields', wp_unslash($fields));
	}
	if ($settings !== null) {
		update_post_meta($post_id, '_wfb_settings', wp_unslash($settings));
	}
	if ($actions !== null) {
		update_post_meta($post_id, '_wfb_actions', wp_unslash($actions));
	}
}
