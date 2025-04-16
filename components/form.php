<?php

/**
 * Render feedback form via shortcode
 */
function wtn_render_form(): string {
	$custom_form = get_option('wtn_custom_form_markup');

	if (!$custom_form || trim($custom_form) === '') {
		$custom_form = '[text name="name" placeholder="Ваше имя"]' . "\n" .
		               '[tel name="phone" placeholder="Ваш телефон"]' . "\n" .
		               '[textarea name="message" placeholder="Ваше сообщение"]' . "\n" .
		               '[send text="Отправить"]';
	}

	// Подключаем рендерер формы
	if (!function_exists('wtn_render_form_template')) {
		require_once dirname(__DIR__) . '/helpers/render-form.php';
	}

	$form_html = wtn_render_form_template($custom_form);

	ob_start();
	?>
    <div class="wtn-form-wrapper">
        <form class="wtn-form" method="post">
			<?php echo $form_html; ?>
            <input type="hidden" name="action" value="wtn_send_form">
            <input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr(wp_create_nonce('wtn_form_nonce')); ?>">
            <input type="text" name="wtn_hp_email" style="display:none !important;" tabindex="-1" autocomplete="off">
            <input type="hidden" name="wtn_form_timestamp" value="<?php echo time(); ?>">
        </form>

        <div class="wtn-loader" style="display: none;">
            <svg class="wtn-spinner" width="48" height="48" viewBox="0 0 50 50">
                <circle
                    class="wtn-spinner-path"
                    cx="25"
                    cy="25"
                    r="20"
                    fill="none"
                    stroke="#0077B5"
                    stroke-width="4"
                    stroke-linecap="round"
                />
            </svg>

        </div>
    </div>
	<?php
	return ob_get_clean();
}


add_shortcode('wtn_feedback_form', 'wtn_render_form');
