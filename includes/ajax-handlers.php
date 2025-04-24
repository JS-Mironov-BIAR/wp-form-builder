<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Отправка формы (фронт)
 */
add_action( 'wp_ajax_wpfb_send_form',        'wpfb_handle_send_form' );
add_action( 'wp_ajax_nopriv_wpfb_send_form', 'wpfb_handle_send_form' );

/**
 * Handles form submission via AJAX
 */
function wpfb_handle_send_form(): void {
	check_ajax_referer('wpfb_front_nonce');

	$post_id = absint( $_POST['form_id'] ?? 0 );
	if ( ! $post_id || 'wfb_form' !== get_post_type( $post_id ) ) {
		wp_send_json_error( 'Неверный ID формы' );
	}

	// 1. Берём шаблоны, сохранённые в мета‑полях формы
	$msg_tpl   = get_post_meta( $post_id, 'wpfb_message_template', true ) ?: "📩 Новое сообщение\n\n[name]\n[phone]\n[message]";
	$form_tpl  = get_post_meta( $post_id, 'wpfb_form_template',    true );

	// 2. Валидация базовых полей (можешь заменить на свою)
	$allowed_fields = [
		'name'    => 'sanitize_text_field',
		'phone'   => 'sanitize_text_field',
		'email'   => 'sanitize_email',
		'message' => 'sanitize_textarea_field',
		'checkout' => 'sanitize_text_field',
		'select'  => 'sanitize_text_field',
	];

	$sanitized_data = [];

	foreach ($_POST as $key => $value) {
		if (isset($allowed_fields[$key])) {
			$sanitized_data[$key] = call_user_func($allowed_fields[$key], $value);
		}
	}

	// Проверка: выбран ли select
	if (isset($sanitized_data['select']) && $sanitized_data['select'] === '') {
		wp_send_json_error('Пожалуйста, выберите значение из списка.');
	}

	// 3. Заполняем шаблон и шлём
	$replace_pairs = [];
	foreach ($sanitized_data as $key => $value) {
		$replace_pairs['[' . $key . ']'] = $value;
	}

	$text = strtr($msg_tpl, $replace_pairs);

	// Обязательно: select не выбран
	if (isset($sanitized_data['select']) && $sanitized_data['select'] === '') {
		wp_send_json_error('Пожалуйста, выберите значение из списка.');
	}

	// Минимальная валидация
	if (
		empty($sanitized_data['name']) &&
		empty($sanitized_data['phone']) &&
		empty($sanitized_data['email']) &&
		empty($sanitized_data['message']) &&
		empty($sanitized_data['select'])
	) {
		wp_send_json_error('Заполните хотя бы одно поле.');
	}

	if (!empty($_POST['wfb_hp_email'])) {
		wp_send_json_error('Спам заблокирован');
	}

	$min_delay = 2;
	$form_time = (int) ($_POST['wfb_form_timestamp'] ?? 0);
	if (time() - $form_time < $min_delay) {
		wp_send_json_error('Форма отправлена слишком быстро');
	}

	// Отправка в Telegram
	$sent = wpfb_send_telegram_message($text);

	$sent ? wp_send_json_success() : wp_send_json_error( 'Ошибка отправки' );
}

add_action('wp_ajax_wpfb_delete_form', function () {
	if (!current_user_can('delete_posts') || !check_ajax_referer('wpfb_nonce', '_wpnonce', false)) {
		wp_send_json_error('Недостаточно прав');
	}

	$post_id = intval($_POST['post_id'] ?? 0);
	if (!$post_id || get_post_type($post_id) !== 'wfb_form') {
		wp_send_json_error('Неверный ID формы');
	}

	wp_delete_post($post_id, true);
	wp_send_json_success();
});
