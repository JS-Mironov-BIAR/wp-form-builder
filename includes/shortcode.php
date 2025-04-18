<?php
/**
 * Shortcode: [wpfb id="123" name="some-form"]
 */

add_shortcode('wpfb', 'wpfb_render_shortcode_form');

/**
 * Render the form based on shortcode attributes
 *
 * @param array $atts
 * @return string
 */
function wpfb_render_shortcode_form($atts) {
	$atts = shortcode_atts([
	    'id'   => '',
	    'name' => '',
	], $atts, 'wpfb');

	$post_id = (int) $atts['id'];
	if (!$post_id) return '';

	// Получаем шаблон формы из метаполя
	$form_template = get_post_meta($post_id, 'wpfb_form_template', true);
	if (empty($form_template)) return '';

	$post_title = get_the_title($post_id);

	// Рендерим HTML полей формы
	$form_html = wfb_render_form_template($form_template);

	ob_start();
	?>
	<div class="wfb-form-wrapper">
		<?php if (!empty($post_title)) : ?>
			<h2 class="wfb-form-title"><?php echo esc_html($post_title); ?></h2>
		<?php endif; ?>


		<form class="wfb-form" method="post">
			<input type="hidden" name="form_id" value="<?php echo esc_attr($post_id); ?>">
			<input type="hidden" name="action" value="wpfb_send_form">
			<input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr(wp_create_nonce('wpfb_front_nonce')); ?>">
			<input type="text" name="wfb_hp_email" style="display:none !important;" tabindex="-1" autocomplete="off">
			<input type="hidden" name="wfb_form_timestamp" value="<?php echo time(); ?>">
			<?php echo $form_html; ?>

			<?php
			$extra_content = get_post_meta($post_id, 'wpfb_select_extra_html', true);
			if (!empty($extra_content)) : ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const extraContentBlock = document.querySelector('.wfb-form-wrapper .wfb-select-extra-content');
                        if (extraContentBlock) {
                            extraContentBlock.setAttribute('data-content', <?php echo json_encode($extra_content); ?>);
                        }
                    });
                </script>
			<?php endif; ?>
		</form>

		<div class="wfb-loader" style="display: none;">
			<svg class="wfb-spinner" width="48" height="48" viewBox="0 0 50 50">
				<circle class="wfb-spinner-path" cx="25" cy="25" r="20" fill="none" stroke="#0077B5" stroke-width="4" stroke-linecap="round"            />
			</svg>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
