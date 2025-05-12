<?php
/**
 * Message template variables used in the send-method tab.
 * Each tag represents a shortcode placeholder and its description.
 */

return [
	[
		'tag'   => '[name]',
		'title' => 'Имя отправителя. Используется для вставки значений из поля [name]',
	],
	[
		'tag'   => '[phone]',
		'title' => 'Телефон. Используется для вставки значений из поля [phone]',
	],
	[
		'tag'   => '[email]',
		'title' => 'Email. Используется для вставки значений из поля [email]',
	],
	[
		'tag'   => '[message]',
		'title' => 'Текст сообщения. Используется для вставки значений из [textarea]',
	],
	[
		'tag'   => '[select]',
		'title' => 'Выбранный пункт из поля выпадающего списка. Используется для вставки значений из [Select]',
	],
	[
		'tag'   => '[checkbox]',
		'title' => 'Значение чекбокса (например, согласие). Используется для вставки значений из [Checkbox]',
	],
];
