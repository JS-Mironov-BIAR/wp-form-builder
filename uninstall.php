<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Удаляем только опции конкретного модуля
delete_option('wtn_bot_token');
delete_option('wtn_chat_id');

// Можешь добавить очистку полей формы или мета, если нужно в будущем
