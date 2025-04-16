<?php

/**
 * Get Telegram bot token from DB
 * @return string|null
 */
function wtn_get_bot_token(): ?string {
	return get_option('wtn_bot_token') ?: null;
}

/**
 * Get Telegram chat ID from DB
 * @return string|null
 */
function wtn_get_chat_id(): ?string {
	return get_option('wtn_chat_id') ?: null;
}

/**
 * Send message to Telegram
 * @param string $message
 * @return bool
 */
function wtn_send_telegram_message(string $message): bool {
	$bot_token = wtn_get_bot_token();
	$chat_ids_raw = wtn_get_chat_id();

	if (!$bot_token || !$chat_ids_raw) return false;

	// Поддержка разделителей: пробел, запятая, табуляция и перенос
	$chat_ids = preg_split('/[\s,]+/', $chat_ids_raw);

	$at_least_one_sent = false;

	foreach ($chat_ids as $chat_id) {
		$chat_id = trim($chat_id);
		if ($chat_id === '') continue;

		$url = "https://api.telegram.org/bot$bot_token/sendMessage";
		$params = [
			'chat_id'    => $chat_id,
			'text'       => $message,
			'parse_mode' => 'Markdown',
		];

		$response = wp_remote_post($url, ['body' => $params]);

		if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
			$at_least_one_sent = true;
		}
	}

	return $at_least_one_sent;
}

