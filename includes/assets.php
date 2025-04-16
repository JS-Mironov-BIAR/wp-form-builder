<?php
if (!defined('ABSPATH')) {
	exit;
}

function wtn_enqueue_assets(): void {
	wp_enqueue_style('wtn-style', plugin_dir_url(__FILE__) . '../assets/css/main.css', [], null);
	wp_enqueue_script('wtn-script', plugin_dir_url(__FILE__) . '../assets/dist/main.js', [], null, true);

	wp_localize_script('wtn-script', 'wtn_ajax', [
		'url'   => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('wtn_form_nonce'),
	]);
}
add_action('wp_enqueue_scripts', 'wtn_enqueue_assets');
