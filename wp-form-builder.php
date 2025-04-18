<?php
/**
 * Plugin Name:       WordPress Form Builder
 * Plugin URI:        https://olaksen.by
 * Description:       Lightweight and fast form builder for WordPress. Easily connect forms to Gmail and Telegram.
 * Version:           1.0.0
 * Author:            Egor Mironov
 * Author URI:        https://yourwebsite.com
 * License:           GPL2
 * Text Domain:       wp-form-builder
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles full uninstallation logic
 */
function wpfb_handle_uninstall(): void {
	require_once plugin_dir_path(__FILE__) . 'uninstall.php';
}
register_uninstall_hook(__FILE__, 'wpfb_handle_uninstall');

// Load plugin logic
require_once plugin_dir_path(__FILE__) . 'loader.php';
