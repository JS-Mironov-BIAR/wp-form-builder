<?php

/**
 * Registers the Telegram Notification settings page in the WordPress admin menu.
 *
 * @return void
 */
function wtn_register_settings_page(): void {
	add_options_page(
		'Telegram Notification Settings',
		'Telegram Notification',
		'manage_options',
		'wtn-settings',
		'wtn_settings_page_html'
	);
}
add_action('admin_menu', 'wtn_register_settings_page');

/**
 * Renders the settings page and adds help tab content.
 *
 * @return void
 */
function wtn_settings_page_html(): void {
	$screen = get_current_screen();

	// Add a help tab with Telegram setup instructions
	$screen->add_help_tab([
		                      'id'      => 'telegram_help',
		                      'title'   => 'Как настроить Telegram',
		                      'content' => '<p>Создайте бота через <a href="https://t.me/BotFather" target="_blank">@BotFather</a>, получите токен и нажмите «Обновить Chat ID».</p>',
	                      ]);

	// Include the HTML template for settings page layout
	include __DIR__ . '/settings-page-template.php';
}

/**
 * Handles the admin post action for syncing chat IDs via Telegram API.
 */
add_action('admin_post_wtn_chat_sync', 'wtn_merge_chat_ids_from_api');

/**
 * Handles the admin post action for sending a test message to Telegram.
 */
add_action('admin_post_wtn_test_send', 'wtn_test_send_to_telegram');

/**
 * Registers plugin settings, sections, and fields for Telegram integration.
 *
 * @return void
 */
function wtn_register_settings(): void {
	// Connection settings
	register_setting('wtn_connection_group', 'wtn_bot_token');
	register_setting('wtn_connection_group', 'wtn_chat_id');

	// Optional: Form markup or message template settings
	register_setting('wtn_form_group', 'wtn_custom_form_markup');
	register_setting('wtn_message_group', 'wtn_custom_message_template');

	// Settings section container
	add_settings_section('wtn_main_section', '', static fn () => null, 'wtn-settings');

	// Telegram Bot Token field
	add_settings_field(
		'wtn_bot_token',
		'Telegram Bot Token',
		'wtn_bot_token_field',
		'wtn-settings',
		'wtn_main_section'
	);

	// Telegram Chat ID field
	add_settings_field(
		'wtn_chat_id',
		'Telegram Chat ID',
		'wtn_chat_id_field',
		'wtn-settings',
		'wtn_main_section'
	);
}
add_action('admin_init', 'wtn_register_settings');

/**
 * Outputs the input field for the Telegram Bot Token.
 *
 * @return void
 */
function wtn_bot_token_field(): void {
	$value = get_option('wtn_bot_token');
	echo '<input type="text" name="wtn_bot_token" value="' . esc_attr($value) . '" class="regular-text">';
}

/**
 * Outputs the input field for the Telegram Chat ID(s).
 *
 * @return void
 */
function wtn_chat_id_field(): void {
	$value = get_option('wtn_chat_id');
	echo '<input type="text" name="wtn_chat_id" value="' . esc_attr($value) . '" class="regular-text">';
	echo '<p class="description">Введите один или несколько chat ID, разделённых запятыми или пробелами.</p>';
}
