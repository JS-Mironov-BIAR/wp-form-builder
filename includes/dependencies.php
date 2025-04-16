<?php

if (!function_exists('is_plugin_active')) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

function wtn_check_required_plugins(): void {
	$plugin_variants = [
		'custom-modal-controller/custom-modal-controller.php',
		'custom-popup-controller/custom-modal-controller.php',
	];

	$found = false;

	foreach ($plugin_variants as $plugin) {
		if (is_plugin_active($plugin)) {
			$found = true;
			break;
		}
	}

	if (!$found) {
		deactivate_plugins(plugin_basename(__FILE__));

		add_action('admin_notices', function () {
			echo '<div class="notice notice-error is-dismissible"><p>';
			echo 'Плагин <strong>WP Telegram Notification</strong> был <strong>деактивирован</strong>, потому что требуется активный <strong>Custom Modal Controller</strong>.';
			echo '</p></div>';
		});
	}
}
add_action('admin_init', 'wtn_check_required_plugins');
