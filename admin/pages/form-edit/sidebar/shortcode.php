<?php
defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', static function () {
	add_meta_box(
		'wpfb_shortcode_meta',
		__( 'Шорткод формы', 'wpfb' ),
		static function ( WP_Post $post ) {
			$title_slug = sanitize_title( $post->post_title ?: 'my-form' );
			$sc         = '[wpfb id="' . esc_attr( $post->ID ) . '" name="' . $title_slug . '"]';
			?>
			<div style="display:flex; gap:6px;">
				<input type="text" readonly value="<?php echo esc_attr( $sc ); ?>"
				       style="flex:1; background:#f7f7f7; font-family:monospace; cursor:text;"
				       onclick="this.select()">
				<button type="button" class="button"
				        onclick="navigator.clipboard.writeText('<?php echo esc_js( $sc ); ?>');">
					<?php _e( 'Copy', 'wpfb' ); ?>
				</button>
			</div>
			<p class="description" style="margin-top:8px;">
				<?php esc_html_e( 'Insert this shortcode into a template or page to display the form.', 'wpfb' ); ?>
			</p>
			<?php
		},
		'wfb_form',
		'side',
	);
} );
