<?php
/**
 * Returns array of available send methods.
 *
 * Each method must define:
 * - label: UI label
 * - plugin: path to plugin main file (used by is_plugin_active)
 * - icon: inline SVG or emoji
 *
 * @return array
 */
return [
	'telegram' => [
		'label'  => 'Telegram',
		'plugin' => 'wp-telefram-notification/wp-telegram-notification.php',
		'icon'   => '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 240 240"><circle cx="120" cy="120" r="120" fill="#1d93d2"/><path d="M81.2,128.7l14.2,39.4s1.8,3.7,3.7,3.7,30.2-29.5,30.2-29.5l31.5-60.9L81.7,118.6Z" fill="#c8daea"/><path d="M100.1,138.8l-2.7,29s-1.1,8.9,7.8,0,17.4-15.8,17.4-15.8" fill="#a9c6d8"/><path d="M81.5,130.2L52.2,120.6s-3.5-1.4-2.4-4.6c.2-.7.7-1.2,2.1-2.2,6.5-4.5,120.1-45.4,120.1-45.4s3.2-1.1,5.1-.4a2.8,2.8,0,0,1,1.9,2.1,9.4,9.4,0,0,1,.3,2.6c0,.8-.1,1.4-.2,2.5-.7,11.2-21.4,94.5-21.4,94.5s-1.2,4.9-5.7,5a8.1,8.1,0,0,1-4.3-1.6c-8.7-7.5-38.8-27.7-45.5-32.2a1.3,1.3,0,0,1-.5-.9c-.1-.5.4-1.1.4-1.1s52.4-46.6,53.8-51.5c.1-.4-.3-.6-.8-.4-3.5,1.3-63.8,39.4-70.5,43.6A3.2,3.2,0,0,1,81.5,130.2Z" fill="#fff"/></svg>',
	],
	'viber' => [
		'label'  => 'Viber',
		'plugin' => 'wp-viber-notification/wp-viber-notification.php',
		'icon'   => '📞',
	],
	'gmail' => [
		'label'  => 'Gmail',
		'plugin' => 'wp-gmail-notification/wp-gmail-notification.php',
		'icon'   => '📧',
	],
	'mail' => [
		'label'  => 'Email',
		'plugin' => 'wp-mail-notification/wp-mail-notification.php',
		'icon'   => '✉️',
	],
	'instagram' => [
		'label'  => 'Instagram DM',
		'plugin' => 'wp-instagram-notification/wp-instagram-notification.php',
		'icon'   => '📸',
	],
];
