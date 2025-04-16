<?php
if (!defined('ABSPATH')) {
	exit;
}

require_once dirname(__DIR__) . '/components/form.php';
require_once dirname(__DIR__) . '/config/telegram.php';
require_once dirname(__DIR__) . '/helpers/render-form.php';

// Регистрируем AJAX для отправки формы
add_action('wp_ajax_wtn_send_form', 'wtn_handle_ajax');
add_action('wp_ajax_nopriv_wtn_send_form', 'wtn_handle_ajax');

/**
 * AJAX-обработка отправки формы
 */
function wtn_handle_ajax(): void {
	check_ajax_referer('wtn_form_nonce');

	$name    = sanitize_text_field($_POST['name'] ?? '');
	$phone   = sanitize_text_field($_POST['phone'] ?? '');
	$message = sanitize_textarea_field($_POST['message'] ?? '');

	$template = get_option('wtn_custom_message_template');
	if (!$template) {
		$template = "📩 *Новое обращение с сайта*\n\n👤 Имя: [name]\n📱 Телефон: [phone]\n💬 Сообщение: [message]";
	}

	$text = strtr($template, [
		'[name]'    => $name,
		'[phone]'   => $phone,
		'[message]' => $message,
	]);

	if (empty($_POST['consent'])) {
		wp_send_json_error('Вы должны согласиться с политикой обработки данных.');
	}

	if (!empty($_POST['wtn_hp_email'])) {
		wp_send_json_error('Спам заблокирован');
	}

	$min_delay = 2;
	$form_time = (int) ($_POST['wtn_form_timestamp'] ?? 0);
	if (time() - $form_time < $min_delay) {
		wp_send_json_error('Форма отправлена слишком быстро');
	}


	if (wtn_send_telegram_message($text)) {
		wp_send_json_success();
	} else {
		wp_send_json_error('Ошибка отправки');
	}
}
