<?php
/**
 * Единая функция отправки сообщения в Telegram
 * используется и в админ‑AJAX, и на фронте
 */
function wpfb_send_telegram_message( string $text ): bool {

	$bot_token = get_option( 'wtn_bot_token' );
	$chat_ids  = preg_split( '/[\s,]+/', (string) get_option( 'wtn_chat_id' ) );

	if ( ! $bot_token || empty( $chat_ids ) ) {
		return false;
	}

	foreach ( $chat_ids as $id ) {
		$id = trim( $id );
		if ( ! $id ) {
			continue;
		}

		$response = wp_remote_post(
			"https://api.telegram.org/bot{$bot_token}/sendMessage",
			[
				'body' => [
					'chat_id'    => $id,
					'text'       => $text,
					'parse_mode' => 'Markdown',
				],
			]
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false; // хотя бы один не ушёл → ошибка
		}
	}

	return true;
}
