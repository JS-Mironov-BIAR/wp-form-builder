<?php

/**
 * Fetches unique Telegram chat IDs by calling the Telegram Bot API `getUpdates` method.
 *
 * @return array<int, string> List of chat IDs as strings.
 */
function wtn_fetch_telegram_chat_ids(): array {
	$bot_token = get_option('wtn_bot_token');
	if (!$bot_token) return [];

	$url = "https://api.telegram.org/bot$bot_token/getUpdates";
	$response = wp_remote_get($url);

	if (is_wp_error($response)) return [];

	$data = json_decode(wp_remote_retrieve_body($response), true);
	if (!isset($data['result'])) return [];

	$chat_ids = [];

	foreach ($data['result'] as $update) {
		$chat = $update['message']['chat'] ?? null;
		if ($chat && isset($chat['id'])) {
			$chat_ids[] = (string) $chat['id'];
		}
	}

	return array_unique($chat_ids);
}

/**
 * Merges fetched Telegram chat IDs from API with the saved ones in settings,
 * and updates the plugin option `wtn_chat_id`.
 *
 * Redirects back to the settings page with a success indicator.
 *
 * @return void
 */
function wtn_merge_chat_ids_from_api(): void {
	if (!current_user_can('manage_options')) {
		wp_die('Недостаточно прав');
	}

	$existing = get_option('wtn_chat_id') ?: '';
	$existing_ids = preg_split('/[\s,]+/', $existing, -1, PREG_SPLIT_NO_EMPTY);

	$new_ids = wtn_fetch_telegram_chat_ids();
	$merged = array_unique([...$existing_ids, ...$new_ids]);

	update_option('wtn_chat_id', implode(',', $merged));

	wp_safe_redirect(admin_url('options-general.php?page=wtn-settings&chat_sync=success'));
	exit;
}
