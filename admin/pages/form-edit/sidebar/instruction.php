<?php
defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', static function () {
	add_meta_box(
		'wpfb_instruction_meta',
		__( 'Памятка по редактору', 'wpfb' ),
		static function ( WP_Post $post ) {
			$title_slug = sanitize_title( $post->post_title ?: 'my-form' );
			$sc         = '[wpfb id="' . esc_attr( $post->ID ) . '" name="' . $title_slug . '"]';
			?>
			<div class="postbox" style="margin-top: 20px;">
				<h2 class="hndle" style="padding: 12px 16px; font-size: 16px;">🧠 Памятка по редактору</h2>
				<div class="inside" style="padding: 12px 16px;">
					<strong style="display:block; margin-bottom: 6px;">Редактор HTML-формы:</strong>
					<ul style="margin-top: 4px; padding-left: 20px; list-style: disc;">
						<li><kbd>Ctrl</kbd> + <kbd>Z</kbd> — отменить</li>
						<li><kbd>Ctrl</kbd> + <kbd>Y</kbd> или <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Z</kbd> — повторить</li>
						<li>Кнопки ↩️ и ↪️ = Undo / Redo</li>
						<li>Кликайте по тегам для вставки</li>
					</ul>

					<hr style="margin: 12px 0;">

					<strong style="display:block; margin-bottom: 6px;">Шаблон сообщения:</strong>
					<ul style="margin-top: 4px; padding-left: 20px; list-style: disc;">
						<li>Доступные переменные: <code>[name]</code>, <code>[phone]</code>, <code>[message]</code>, <code>[email]</code>, <code>[select]</code>, <code>[checkbox]</code></li>
						<li>Можно менять порядок, добавлять свои поля</li>
						<li>Поддерживаются emoji</li>
					</ul>
				</div>
			</div>
			<?php
		},
		'wfb_form',
		'side',
		'low'
	);
} );
