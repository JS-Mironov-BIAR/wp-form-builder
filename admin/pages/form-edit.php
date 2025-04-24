<?php
/**
 * Meta‑box wrapper with tabs: «Форма отправки» | «Способ отправки».
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------
 * 1. Подключаем ВСЕ боковые метабоксы из папки sidebar
 *    (делается один раз при загрузке файла).
 * -------------------------------------------------*/
foreach ( glob( __DIR__ . '/form-edit/sidebar/*.php' ) as $sidebar_file ) {
	require_once $sidebar_file;      // каждый файл сам вешает add_meta_box( …, 'side' … )
}

/* -------------------------------------------------
 * 2. Регистрируем «центральный» метабокс‑вкладки
 * -------------------------------------------------*/
add_action( 'add_meta_boxes', 'wpfb_register_form_tabs_meta_box' );
function wpfb_register_form_tabs_meta_box(): void {
	add_meta_box(
		'wpfb_form_tabs',
		__( 'Конструктор формы', 'wpfb' ),
		'wpfb_render_form_tabs_meta_box',
		'wfb_form',
		'normal',
		'default'
	);
}

/**
 * Output the meta‑box with tab navigation.
 *
 * @param WP_Post $post
 */
function wpfb_render_form_tabs_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'wpfb_save_form_meta', 'wpfb_form_meta_nonce' );
	?>
	<div class="wpfb-tab-wrapper">
		<h2 class="nav-tab-wrapper">
			<a href="#wpfb-tab-form" class="nav-tab nav-tab-active"><?php _e( 'Форма отправки', 'wpfb' ); ?></a>
			<a href="#wpfb-tab-send" class="nav-tab"><?php _e( 'Способ отправки', 'wpfb' ); ?></a>
		</h2>

		<div id="wpfb-tab-form"  class="wpfb-tab-content">
			<?php include __DIR__ . '/form-edit/tabs/form-template.php'; ?>
		</div>

		<div id="wpfb-tab-send" class="wpfb-tab-content" style="display:none;">
			<?php include __DIR__ . '/form-edit/tabs/send-method.php'; ?>
		</div>
	</div>
	<?php
}
