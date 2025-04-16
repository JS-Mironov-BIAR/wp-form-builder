<?php

/**
 * Sends a test message to the configured Telegram chat using the saved bot token and chat ID.
 *
 * Used to verify that Telegram notifications are working.
 *
 * @return void
 */
function wtn_test_send_to_telegram(): void {
	if (!current_user_can('manage_options')) {
		wp_die('Недостаточно прав');
	}

	$message = "✅ Это тестовое сообщение от WP Telegram Notification!";
	if (function_exists('wtn_send_telegram_message')) {
		wtn_send_telegram_message($message);
	}

	wp_safe_redirect(admin_url('options-general.php?page=wtn-settings&test_send=success'));
	exit;
}
add_action('admin_post_wtn_test_send', 'wtn_test_send_to_telegram');
