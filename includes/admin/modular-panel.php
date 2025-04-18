<?php

/**
 * Регистрирует модуль и всегда добавляет панель и страницу модуля
 *
 * @param string $module_slug
 * @param string $module_name
 * @param string $module_version
 * @param callable $render_callback
 */
function wfb_register_modular_admin_page(string $module_slug, string $module_name, string $module_version, callable $render_callback): void {
	add_action('admin_menu', function () use ($module_slug, $module_name, $render_callback) {
		// Всегда создаём основную панель (дублирование не страшно, WordPress сам фильтрует)
		add_menu_page(
			__('Управление модулями', 'wfb'),
			__('Модульная система', 'wfb'),
			'manage_options',
			'wfb-modular-panel',
			'wfb_modular_panel_main_page',
			'dashicons-admin-generic',
			65
		);

		add_submenu_page('wfb-modular-panel', __('Другие модули', 'wfb'), __('Другие модули', 'wfb'), 'manage_options', 'wfb-modules', 'wfb_other_modules_page');
		add_submenu_page('wfb-modular-panel', __('Поддержите проект', 'wfb'), __('Поддержите проект', 'wfb'), 'manage_options', 'wfb-donate', 'wfb_donation_page');
		add_submenu_page('wfb-modular-panel', __('Техподдержка', 'wfb'), __('Техподдержка', 'wfb'), 'manage_options', 'wfb-support', 'wfb_support_page');

		// И всегда добавляем страницу модуля
		add_submenu_page(
			'wfb-modular-panel',
			$module_name,
			$module_name,
			'manage_options',
			'wfb-module-' . $module_slug,
			$render_callback
		);
	});
}
