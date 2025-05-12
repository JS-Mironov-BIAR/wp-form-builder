<?php

/**
 * Bootstraps the plugin functionality and routes.
 */
define( 'WPFB_DIR', plugin_dir_path( __FILE__ ) );

// Загружаем «ядро» (панель + админ‑страницы)
require_once WPFB_DIR . '/includes/admin/modular-panel.php';
require_once WPFB_DIR . '/admin/pages/forms.php';
require_once WPFB_DIR . '/admin/pages/form-edit.php';
require_once WPFB_DIR . '/admin/pages/form-edit/save-meta.php';

// Общие вспомогалки
require_once WPFB_DIR . '/includes/render-form.php';
require_once WPFB_DIR . '/includes/send-message.php';
require_once WPFB_DIR . '/includes/shortcode.php';

// Все AJAX‑действия (и фронт, и админ)
require_once WPFB_DIR . '/includes/ajax-handlers.php';

// Подключаем стили/скрипты
require_once WPFB_DIR . '/admin/assets.php';
require_once WPFB_DIR . '/includes/assets.php';

