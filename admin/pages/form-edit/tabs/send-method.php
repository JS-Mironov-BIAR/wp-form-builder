<?php
/**
 * Message template tab for the form editor.
 * Includes:
 * — Method selector (Telegram, Viber, etc)
 * — Insertable template variables
 * — Message textarea editor
 */

/**
 * Load available send methods config.
 *
 * @var array $send_methods
 */
$send_methods = require WPFB_DIR . 'admin/config/send-methods.php';

$tags = require WPFB_DIR . 'admin/config/template-tags.php';

// Get current message template or fallback to default
$message_template = get_post_meta( $post->ID, 'wpfb_message_template', true );
if ( ! $message_template ) {
	$message_template = "📩 *Новое сообщение*\n👤 Имя: [name]\n📱 Телефон: [phone]\n📧 Email: [email]\n💬 Сообщение: [message]";
}

?>

<div class="wpfb-message-builder wp-core-ui">

	<?php
	/**
	 * Load plugin detection functions if needed.
	 */
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	/**
	 * Filter active methods based on installed plugins.
	 */
	$available_methods = array_filter( $send_methods, function ( $method ) {
		return is_plugin_active( $method['plugin'] );
	} );

	$current_method = get_post_meta( $post->ID, 'wpfb_send_method', true ) ?: 'telegram';
	?>

    <div class="wpfb-send-methods">
        <p>
            <strong>
                Выбор метода отправки сообщения:
            </strong>
        </p>

        <div class="wpfb-methods-tiles">
			<?php foreach ( $available_methods as $slug => $method ) : ?>
                <label class="wpfb-method-tile <?= $slug === $current_method ? 'active' : ''; ?>">
                    <input
                        type="radio"
                        name="wpfb_send_method"
                        value="<?= esc_attr( $slug ); ?>"
                        <?php checked( $slug, $current_method ); ?>
                        hidden
                        style="opacity: 0"
                    >

                    <span class="icon">
                        <?= $method['icon']; ?>
                    </span>

                    <span class="label">
                        <?= esc_html( $method['label'] ); ?>
                    </span>
                </label>
			<?php endforeach; ?>
        </div>
    </div>

    <table class="form-table">
        <tr>
            <th scope="row">
                <label>
                    Insert variable
                </label>
            </th>

            <td>
                <div class="wpfb-buttons-row">
					<?php foreach ( $tags as $btn ) : ?>
                        <button
                            type="button"
                            class="button button-secondary wpfb-insert-message-tag"
                            data-tag="<?= esc_attr( $btn['tag'] ); ?>"
                            title="<?= esc_attr( $btn['title'] ); ?>"
                        ><?= esc_html( $btn['tag'] ); ?></button>
					<?php endforeach; ?>

                    <button
                        type="button"
                        id="wpfb-msg-undo-btn"
                        class="button"
                    >
                        ↩️
                    </button>

                    <button
                        type="button"
                        id="wpfb-msg-redo-btn"
                        class="button"
                    >
                        ↪️
                    </button>
                </div>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="wpfb-message-editor">
                    Шаблон сообщения
                </label>
            </th>
            <td>
                <textarea
                    id="wpfb-message-editor"
                    name="wpfb_message_template"
                    rows="8"
                    class="large-text code"
                    style="font-family: monospace;"><?= esc_textarea( $message_template ); ?></textarea>
            </td>
        </tr>
    </table>
</div>
