<?php
$buttons = [
	[
		'tag'   => 'text',
		'attrs' => 'name="" placeholder="Ваше имя"',
		'title' => 'Текстовое поле. Атрибуты: required, label, name, placeholder, class',
	],
	[
		'tag'   => 'tel',
		'attrs' => 'name="" placeholder="Ваш телефон"',
		'title' => 'Телефон. Атрибуты: required, label, name, placeholder, class',
	],
	[
		'tag'   => 'email',
		'attrs' => 'name="" placeholder="Email"',
		'title' => 'Email. Атрибуты: required, label, name, placeholder, class',
	],
	[
		'tag'   => 'textarea',
		'attrs' => 'name="" placeholder="Ваше сообщение"',
		'title' => 'Многострочное поле. Атрибуты: required, label, name, placeholder, class',
	],
	[
		'tag'   => 'select',
		'attrs' => 'name="" items="Сайт, Лендинг, Интернет-магазин"',
		'title' => 'Выпадающий список (select). Атрибуты: required, label, name, class, items, default',
	],
	[
		'tag'   => 'checkbox',
		'attrs' => 'name="" label="Согласие"',
		'title' => 'Чекбокс. Атрибуты: required, label, name, class, href',
	],
	[
		'tag'   => 'send',
		'attrs' => 'text="Отправить"',
		'title' => 'Кнопка отправки. Атрибуты: text, class',
	],
];

$form_template = get_post_meta( $post->ID, 'wpfb_form_template', true );

if ( ! $form_template ) {
	$form_template = '[text name="name" placeholder="Ваше имя"]' . "\n"
	                 . '[tel name="phone" placeholder="Ваш телефон"]' . "\n"
	                 . '[textarea name="message" placeholder="Ваше сообщение"]' . "\n"
	                 . '[send text="Отправить"]';
}

?>

<div class="wpfb-form-builder wp-core-ui">
    <table class="form-table">
        <tr>
            <th scope="row"><label>Вставить элемент</label></th>
            <td>
                <div class="wpfb-buttons-row">
				    <?php
				    foreach ( $buttons as $btn ) {
					    $tag   = $btn['tag'];
					    $attrs = $btn['attrs'];
					    $title = $btn['title'];
					    printf(
						    '<button type="button" class="button button-secondary wpfb-insert-tag" data-tag=\'[%s %s]\' title="%s">[%s]</button> ',
						    esc_attr( $tag ),
						    esc_attr( $attrs ),
						    esc_attr( $title ),
						    esc_html( $tag )
					    );
				    }
				    ?>

                    <button type="button" id="wpfb-undo-btn" class="button">↩️ Отменить</button>
                    <button type="button" id="wpfb-redo-btn" class="button">↪️ Повторить</button>
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="wpfb-form-editor">HTML-шаблон</label></th>
            <td>
                <textarea id="wpfb-form-editor" name="wpfb_form_template" rows="10" class="large-text code" style="font-family: monospace;"><?php echo esc_textarea( $form_template ); ?></textarea>
            </td>
        </tr>

        <tr>
            <th scope="row">Предпросмотр</th>
            <td>
                <div id="wpfb-form-preview" class="wpfb-preview-box"></div>
                <p class="description">⚠️ Предпросмотр — структура без стилей сайта</p>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="wpfb-select-extra-content">Контент для select</label></th>
            <td>
                <textarea id="wpfb-select-extra-content" name="wpfb_select_extra_html" rows="6" class="large-text code" style="font-family: monospace;"><?php echo esc_textarea( get_post_meta( $post->ID, 'wpfb_select_extra_html', true ) ); ?></textarea>
                <p class="description">
                    Формат:<br>
                    <code>[website] Контент для сайта</code><br>
                    <code>[landing] Контент для лендинга</code><br>
                    <code>[store] Контент для магазина</code>
                </p>
            </td>
        </tr>
    </table>
</div>
