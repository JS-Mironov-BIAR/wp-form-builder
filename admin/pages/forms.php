<?php
/**
 * Form Builder admin interface and custom post type registration.
 * @package WP Form Builder
 */

// loader
add_action('init', 'wpfb_register_admin_panel');
add_action('after_setup_theme', 'wpfb_register_post_type');
add_filter('parent_file', 'wpfb_fix_parent_menu');
add_filter('submenu_file', 'wpfb_fix_submenu_highlight');


/**
 * Register custom post type "Форма"
 */
function wpfb_register_post_type(): void {
	register_post_type('wfb_form', [
		'labels' => [
			'name' => __('Формы', 'wfb'),
			'singular_name' => __('Форма', 'wfb'),
			'add_new' => __('Добавить форму', 'wfb'),
			'edit_item' => __('Редактировать форму', 'wfb'),
			'new_item' => __('Новая форма', 'wfb'),
			'view_item' => __('Посмотреть форму', 'wfb'),
			'search_items' => __('Найти форму', 'wfb'),
			'not_found' => __('Форм не найдено', 'wfb'),
			'not_found_in_trash' => __('В корзине форм не найдено', 'wfb'),
		],
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'supports' => ['title'],
		'menu_icon' => 'dashicons-feedback',
		'has_archive' => false,
		'capability_type' => 'post',
		'rewrite' => false,
	]);
}

/**
 * Register module inside the modular admin panel
 */
function wpfb_register_admin_panel(): void {
	wfb_register_modular_admin_page(
		'form-builder',
		__('Конструктор форм', 'wfb'),
		'1.0.1',
		'wpfb_render_forms_admin_page'
	);
}


/**
 * Render the admin page content
 */
function wpfb_render_forms_admin_page(): void {
	$forms = get_posts([
	    'post_type'      => 'wfb_form',
	    'post_status'    => ['publish', 'draft'],
	    'posts_per_page' => -1,
	    'orderby'        => 'date',
	    'order'          => 'DESC',
	]);
	?>
    <div class="wrap">
        <h1><?php _e('Конструктор форм', 'wfb'); ?></h1>
        <p><?php _e('Создавайте и управляйте формами обратной связи.', 'wfb'); ?></p>

        <div style="margin: 1rem 0;">
            <button class="button button-primary" data-wpfb-form-edit="<?php echo esc_url(admin_url('post-new.php?post_type=wfb_form')); ?>">
				<?php _e('Добавить новую форму', 'wfb'); ?>
            </button>
        </div>

        <hr>

        <div id="wpfb-form-list">
		<?php if (!empty($forms)) : ?>
            <table class="widefat striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php _e('Название', 'wfb'); ?></th>
                    <th><?php _e('Статус', 'wfb'); ?></th>
                    <th><?php _e('Шорткод', 'wfb'); ?></th>
                    <th>ID</th>
                    <th><?php _e('Удалить', 'wfb'); ?></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($forms as $index => $form): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <button class="wpfb-form-link" data-wpfb-form-edit="<?php echo esc_url(get_edit_post_link($form->ID)); ?>">
								<?php echo esc_html($form->post_title ?: 'Без названия'); ?>
                            </button>
                        </td>
                        <td>
							<?php echo esc_html($form->post_status === 'publish' ? 'Опубликовано' : 'Черновик'); ?>
                        </td>
                        <td>
							<?php
							$form_name = sanitize_title($form->post_title ?: 'my-form');
							echo '<code>[wpfb id="' . $form->ID . '" name="' . esc_attr($form_name) . '"]</code>';
							?>
                        </td>
                        <td><?php echo $form->ID; ?></td>
                        <td>
                            <button class="wpfb-delete-form" data-id="<?php echo $form->ID; ?>" title="Удалить форму">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
		<?php else: ?>
            <p style="color:#666;"><?php _e('Формы ещё не созданы.', 'wfb'); ?></p>
		<?php endif; ?>
        </div>

        <!-- Overlay and Drawer -->
        <div id="wpfb-overlay"></div>

        <div id="wpfb-drawer">
            <div id="wpfb-toast-container" class="wpfb-toast-container"></div>

            <div id="wpfb-drawer-header">
                <h2><?php _e('Редактирование формы', 'wfb'); ?></h2>
                <button id="wpfb-drawer-close" aria-label="Закрыть панель">&times;</button>
            </div>

            <div id="wpfb-drawer-content">
                <div class="drawer-loading-message"><?php _e('Загрузка формы...', 'wfb'); ?></div>
            </div>
        </div>
    </div>
	<?php
}

/**
 * Highlight main and submenu for CPT
 */
function wpfb_fix_parent_menu($parent_file) {
	global $typenow;
	return ($typenow === 'wfb_form') ? 'wfb-modular-panel' : $parent_file;
}

function wpfb_fix_submenu_highlight($submenu_file) {
	global $typenow;
	return ($typenow === 'wfb_form') ? 'wfb-module-form-builder' : $submenu_file;
}
