<?php
/**
 * Meta box: отображение шорткода (только для просмотра)
 */

add_action('add_meta_boxes', 'wpfb_add_shortcode_meta_box');

function wpfb_add_shortcode_meta_box(): void {
	add_meta_box(
		'wpfb_shortcode_meta',
		'Шорткод формы',
		'wpfb_render_shortcode_meta_box',
		'wfb_form',
		'side'
	);
}

/**
 * Рендерит meta box для отображения шорткода
 *
 * @param WP_Post $post
 */
function wpfb_render_shortcode_meta_box($post): void {
	$title_slug = sanitize_title($post->post_title ?: 'my-form');
	$shortcode = '[wpfb id="' . esc_attr($post->ID) . '" name="' . $title_slug . '"]';

	echo '<input type="text" readonly value="' . esc_attr($shortcode) . '" style="width:100%; background-color: #f7f7f7; font-family: monospace; cursor: text;" onclick="this.select()">';

	echo '<p class="description" style="margin-top:8px;">Вставьте этот шорткод в нужное место шаблона или редактора страницы, чтобы отобразить форму.</p>';
}

/**
 * Мы ничего не сохраняем вручную — поле формируется автоматически из title
 */
add_action('save_post_wfb_form', 'wpfb_dont_save_shortcode_meta');

function wpfb_dont_save_shortcode_meta(): void {
	// заглушка — ничего не сохраняем
}
