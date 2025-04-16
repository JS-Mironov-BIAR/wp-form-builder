<?php
/**
 * Plugin Name: WordPress form builder
 * Plugin URI:  https://olaksen.by
 * Description: Легкий и быстрый способ создавать формы обратной связи и подключать их для GMail и Telegram.
 * Version:     1.0.0
 * Author:      Egor Mironov
 * Author URI:  https://yourwebsite.com
 * License:     GPL2
 * Text Domain: wp-form-builder

 */

if (!defined('ABSPATH')) {
	exit;
}

// Connecting Files
require_once plugin_dir_path(__FILE__) . 'includes/dependencies.php';

require_once plugin_dir_path(__FILE__) . 'admin/settings.php';
require_once plugin_dir_path(__FILE__) . 'admin/chat-sync.php';
require_once plugin_dir_path(__FILE__) . 'admin/test-send.php';
require_once plugin_dir_path(__FILE__) . 'includes/init.php';
require_once plugin_dir_path(__FILE__) . 'includes/assets.php';
