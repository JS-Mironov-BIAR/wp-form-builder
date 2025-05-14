<?php
/**
 * Field buttons used in the form builder tab.
 * Each defines a tag, default attributes, and a description.
 */

return [
	[
		'tag'   => 'text',
		'attrs' => 'name="" placeholder="Ваше имя"',
		'title' => 'Текстовое поле. Атрибуты: required, label, name, placeholder, class',
	], [
		'tag'   => 'tel',
		'attrs' => 'name="" placeholder="Ваш телефон"',
		'title' => 'Телефон. Атрибуты: required, label, name, placeholder, class',
	], [
		'tag'   => 'email',
		'attrs' => 'name="" placeholder="Email"',
		'title' => 'Email. Атрибуты: required, label, name, placeholder, class',
	], [
		'tag'   => 'textarea',
		'attrs' => 'name="" placeholder="Ваше сообщение"',
		'title' => 'Многострочное поле. Атрибуты: required, label, name, placeholder, class',
	], [
		'tag'   => 'select',
		'attrs' => 'name="" items="Сайт, Лендинг, Интернет-магазин"',
		'title' => 'Выпадающий список (select). Атрибуты: required, label, name, class, items, default',
	], [
		'tag'   => 'checkbox',
		'attrs' => 'name="" label="Согласие"',
		'title' => 'Чекбокс. Атрибуты: required, label, name, class, href',
	], [
		'tag'   => 'send',
		'attrs' => 'text="Отправить"',
		'title' => 'Кнопка отправки. Атрибуты: text, class',
	],
];
