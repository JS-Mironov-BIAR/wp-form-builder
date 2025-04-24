<?php
$tags = [
	[
		'tag'   => '[name]',
		'title' => 'Имя отправителя. Используется для вставки значений из поля name',
	],
	[
		'tag'   => '[phone]',
		'title' => 'Телефон. Используется для вставки значений из поля phone',
	],
	[
		'tag'   => '[email]',
		'title' => 'Email. Используется для вставки значений из поля email',
	],
	[
		'tag'   => '[message]',
		'title' => 'Текст сообщения. Используется для вставки значений из textarea',
	],
	[
		'tag'   => '[select]',
		'title' => 'Выбранный пункт из поля выпадающего списка. Используется для вставки значений из Select',
	],
	[
		'tag'   => '[checkbox]',
		'title' => 'Значение чекбокса (например, согласие).Используется для вставки значений из Checkbox',
	],
];

$message_template = get_post_meta( $post->ID, 'wpfb_message_template', true );

if ( ! $message_template ) {
	$message_template = "📩 *Новое сообщение*\n👤 Имя: [name]\n📱 Телефон: [phone]\n📧 Email: [email]\n💬 Сообщение: [message]";
}

?>

<div class="wpfb-message-builder wp-core-ui">

    <table class="form-table">
        <tr>
            <th scope="row"><label>Добавить переменную</label></th>
            <td>
                <div class="wpfb-buttons-row">
					<?php
                    foreach ( $tags as $btn ) {
						printf(
							'<button type="button" class="button button-secondary wpfb-insert-message-tag" data-tag="%s" title="%s">%s</button> ',
							esc_attr( $btn['tag'] ),
							esc_attr( $btn['title'] ),
							esc_html( $btn['tag'] )
						);
					}
                    ?>

                    <button type="button" id="wpfb-msg-undo-btn" class="button">↩️ Отменить</button>
                    <button type="button" id="wpfb-msg-redo-btn" class="button">↪️ Повторить</button>
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="wpfb-message-editor">Шаблон сообщения</label></th>
            <td>
                <textarea id="wpfb-message-editor" name="wpfb_message_template" rows="8" class="large-text code" style="font-family: monospace;"><?php echo esc_textarea( $message_template ); ?></textarea>
            </td>
        </tr>
    </table>
</div>
