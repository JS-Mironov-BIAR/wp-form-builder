<?php
/**
 * Метабоксы для конструктора форм: HTML шаблон + шаблон сообщения
 */

add_action('add_meta_boxes', 'wpfb_add_form_meta_boxes');

function wpfb_add_form_meta_boxes(): void {
	add_meta_box(
		'wpfb_form_template_meta',
		'HTML-шаблон формы',
		'wpfb_render_form_template_meta_box',
		'wfb_form',
		'normal',
		'default'
	);

	add_meta_box(
		'wpfb_message_template_meta',
		'Шаблон сообщения Telegram',
		'wpfb_render_message_template_meta_box',
		'wfb_form',
		'normal',
		'default'
	);
}

function wpfb_render_form_template_meta_box(WP_Post $post): void {
	$form_template = get_post_meta($post->ID, 'wpfb_form_template', true) ?: '[text name="name" placeholder="Ваше имя"]' . "\n" . '[tel name="phone" placeholder="Ваш телефон"]' . "\n" . '[textarea name="message" placeholder="Ваше сообщение"]' . "\n" . '[send text="Отправить"]';

	wp_nonce_field('wpfb_save_form_template', 'wpfb_form_template_nonce');
	?>
    <div style="display: flex; gap: 20px; align-items: flex-start;">
        <div style="flex: 1;">
            <div style="margin-bottom: 12px;">
                <strong>Вставить элемент:</strong><br>
                <button type="button" class="wpfb-insert-tag" data-tag='[text name="" placeholder="Ваше имя"]'  title="Текстовое поле. Атрибуты: required, label, name, placeholder, class">[text]</button>
                <button type="button" class="wpfb-insert-tag" data-tag='[tel name="" placeholder="Ваш телефон"]' title="Телефон. Атрибуты: required, label, name, placeholder, class">[tel]</button>
                <button type="button" class="wpfb-insert-tag" data-tag='[email name="" placeholder="Email"]' title="Email. Атрибуты: required, label, name, placeholder, class">[email]</button>
                <button type="button" class="wpfb-insert-tag" data-tag='[textarea name="" placeholder="Ваше сообщение"]' title="Многострочное поле. Атрибуты: required, label, name, placeholder, class">[textarea]</button>
                <button type="button" class="wpfb-insert-tag" data-tag='[select name="" items="Сайт, Лендинг, Интернет-магазин"]' title="Выпадающий список (select). Атрибуты: required, label, name, class, items, default">[select]</button>
                <button type="button" class="wpfb-insert-tag" data-tag='[checkbox name="" label="Согласие"]' title="Чекбокс (consent). Атрибуты: required, label, name, class, href">[checkbox]</button>
                <button type="button" class="wpfb-insert-tag" data-tag='[send text="Отправить"]' title="Кнопка отправки. Атрибуты: text, class">[send]</button>
                <button type="button" id="wpfb-undo-btn" style="margin-left: 16px;">↩️ Отменить</button>
                <button type="button" id="wpfb-redo-btn">↪️ Повторить</button>
            </div>

            <textarea id="wpfb-form-editor" name="wpfb_form_template" rows="10" style="width:100%; font-family: monospace;"><?php echo esc_textarea($form_template); ?></textarea>
        </div>

        <div style="flex: 1;">
            <strong>Предпросмотр:</strong>
            <div id="wpfb-form-preview" style="border: 1px solid #ddd; padding: 15px; background: #fafafa; min-height: 160px;"></div>
            <small style="color:#666; display:block; margin-top:8px;">⚠️ Предпросмотр — структура без стилей сайта</small>
        </div>
    </div>

    <div style="margin-top: 24px;">
        <strong>Контент для пунктов select:</strong>
        <textarea id="wpfb-select-extra-content" name="wpfb_select_extra_html" rows="8" style="width:100%; font-family: monospace;"><?php echo esc_textarea(get_post_meta($post->ID, 'wpfb_select_extra_html', true)); ?></textarea>
        <small style="color:#666; display:block; margin-top:4px;">
            Формат: <br>
            <code>[website] Ваш контент для сайта</code><br>
            <code>[landing] Ваш контент для лендинга</code><br>
            <code>[store] Ваш контент для магазина</code><br>
            (Оборачивайте значение в квадратные скобки, потом текст)
        </small>
    </div>

	<?php
}

function wpfb_render_message_template_meta_box(WP_Post $post): void {
	$message_template = get_post_meta($post->ID, 'wpfb_message_template', true) ?: "📩 *Новое сообщение*\n👤 Имя: [name]\n📱 Телефон: [phone]\n📧 Email: [email]\n💬 Сообщение: [message]";

	wp_nonce_field('wpfb_save_message_template', 'wpfb_message_template_nonce');
	?>
    <div style="margin-bottom: 12px;">
        <strong>Добавить переменную:</strong><br>
        <button type="button" class="wpfb-insert-message-tag" data-tag="[name]" title="Имя отправителя">[name]</button>
        <button type="button" class="wpfb-insert-message-tag" data-tag="[phone]" title="Телефон">[phone]</button>
        <button type="button" class="wpfb-insert-message-tag" data-tag="[email]" title="Email">[email]</button>
        <button type="button" class="wpfb-insert-message-tag" data-tag="[message]" title="Текст сообщения">[message]</button>
        <button type="button" class="wpfb-insert-message-tag" data-tag="[select]" title="Выбранный пункт из выпадающего списка">[select]</button>
        <button type="button" class="wpfb-insert-message-tag" data-tag="[checkbox]" title="Согласие на обработку данных">[checkbox]</button>
        <button type="button" id="wpfb-msg-undo-btn" style="margin-left: 16px;">↩️ Отменить</button>
        <button type="button" id="wpfb-msg-redo-btn">↪️ Повторить</button>
    </div>

    <textarea id="wpfb-message-editor" name="wpfb_message_template" rows="8" style="width:100%; font-family: monospace;"><?php echo esc_textarea($message_template); ?></textarea>

    <div class="postbox" style="margin-top: 20px;">
        <h2 class="hndle" style="padding:12px 16px; font-size:16px;">🧠 Памятка по редакторам</h2>
        <div class="inside" style="padding: 12px 16px;">
            <strong style="display:block; margin-bottom: 6px;">Редактор HTML-формы:</strong>
            <ul style="margin-top: 4px; padding-left: 20px; list-style: disc;">
                <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> — отменить</li>
                <li><kbd>Ctrl</kbd> + <kbd>Y</kbd> или <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>Z</kbd> — повторить</li>
                <li>Кнопки ↩️ и ↪️ = Undo / Redo</li>
                <li>Кликайте по [text], [tel] и т.д. — вставка полей</li>
                <li>Предпросмотр — только структура, не стили сайта</li>
            </ul>

            <hr style="margin: 12px 0;">

            <strong style="display:block; margin-bottom: 6px;">Шаблон сообщения:</strong>
            <ul style="margin-top: 4px; padding-left: 20px; list-style: disc;">
                <li>Переменные: <code>[name]</code>, <code>[phone]</code>, <code>[message]</code>, <code>[consent]</code></li>
                <li>Кликайте по переменным для вставки</li>
                <li><kbd>Ctrl</kbd> + <kbd>Z</kbd> / <kbd>Y</kbd> также работают</li>
                <li>Можно менять порядок и добавлять свои поля</li>
            </ul>
        </div>
    </div>

	<?php
}

add_action('save_post_wfb_form', 'wpfb_save_form_meta_data');

function wpfb_save_form_meta_data(int $post_id): void {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	if (isset($_POST['wpfb_form_template_nonce']) && wp_verify_nonce($_POST['wpfb_form_template_nonce'], 'wpfb_save_form_template')) {
		update_post_meta($post_id, 'wpfb_form_template', wp_kses_post($_POST['wpfb_form_template']));
	}

	if (isset($_POST['wpfb_message_template_nonce']) && wp_verify_nonce($_POST['wpfb_message_template_nonce'], 'wpfb_save_message_template')) {
		update_post_meta($post_id, 'wpfb_message_template', sanitize_textarea_field($_POST['wpfb_message_template']));
	}

	if (isset($_POST['wpfb_select_extra_html'])) {
		update_post_meta($post_id, 'wpfb_select_extra_html', wp_kses_post($_POST['wpfb_select_extra_html']));
	}
}
?>
