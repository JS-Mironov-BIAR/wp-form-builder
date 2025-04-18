<?php

/**
 * Bootstraps the plugin functionality and routes.
 */

// Загружаем «ядро» (панель + админ‑страницы)
require_once __DIR__ . '/includes/admin/modular-panel.php';
require_once __DIR__ . '/admin/pages/forms.php';

// Мета‑боксы и сохранение полей формы
require_once __DIR__ . '/admin/form-meta.php';
require_once __DIR__ . '/admin/meta.php';
require_once __DIR__ . '/admin/save-form-meta.php';

// Общие вспомогалки
require_once __DIR__ . '/includes/render-form.php';
require_once __DIR__ . '/includes/send-message.php';
require_once __DIR__ . '/includes/shortcode.php';


// Все AJAX‑действия (и фронт, и админ)
require_once __DIR__ . '/includes/ajax-handlers.php';   // сюда же можно перенести delete‑form

// Подключаем стили/скрипты
require_once __DIR__ . '/admin/assets.php';             // admin‑side
require_once __DIR__ . '/includes/assets.php';          // front‑side (шорткод)

