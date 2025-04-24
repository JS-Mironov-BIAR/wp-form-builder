<?php
/**
 * wp‑form‑builder — admin assets bootstrap
 */

declare( strict_types=1 );

/* -------------------------------------------------------------------------
 *  CONSTANTS & HELPERS
 * ---------------------------------------------------------------------- */
define( 'WPFB_ASSET_BASE', plugin_dir_url( __DIR__ ) . 'assets/' );

/**
 * @return array{0:string,1:string} [url, version]
 */
function wpfb_asset( string $relative ): array {
	$url  = WPFB_ASSET_BASE . ltrim( $relative, '/' );
	$path = plugin_dir_path( __DIR__ ) . 'assets/' . ltrim( $relative, '/' );
	$ver  = file_exists( $path ) ? (string) filemtime( $path ) : '1.0.0';

	return [ $url, $ver ];
}

/** Передаёт ajaxUrl + nonce, если ещё не локализовано */
function wpfb_localize_core( string $handle ): void {
	$wp_scripts = wp_scripts();

	if ( ! wp_script_is( $handle, 'registered' ) || $wp_scripts->get_data( $handle, 'data' ) ) {
		return;
	}
	wp_localize_script(
		$handle,
		'wpfb_admin',
		[
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'wpfb_nonce' ),
		]
	);
}

/* -------------------------------------------------------------------------
 *  LIST PAGE  (?page=wfb-module-form-builder)
 * ---------------------------------------------------------------------- */
add_action( 'admin_enqueue_scripts', 'wpfb_enqueue_list_assets' );

function wpfb_enqueue_list_assets( string $hook ): void {

	if ( ( $_GET['page'] ?? '' ) !== 'wfb-module-form-builder' ) {
		return;
	}

	// ── style
	[ $css, $vCss ] = wpfb_asset( 'css/admin.css' );
	wp_enqueue_style( 'wpfb-admin', $css, [], $vCss );

	// ── list bundle
	[ $listJs, $vList ] = wpfb_asset( 'dist/list.js' );
	wp_enqueue_script( 'wpfb-list', $listJs, [], $vList, true );

	// ── editor bundle (нужен для табов/превью в drawer)
	if ( ! wp_script_is( 'wpfb-editor', 'enqueued' ) ) {
		[ $edJs, $vEd ] = wpfb_asset( 'dist/editor.js' );
		wp_enqueue_script( 'wpfb-editor', $edJs, [], $vEd, true );
	}

	wpfb_localize_core( 'wpfb-list' );
	wpfb_localize_core( 'wpfb-editor' );
}

/* -------------------------------------------------------------------------
 *  EDITOR  (post.php / post‑new.php) — CPT wfb_form
 * ---------------------------------------------------------------------- */
add_action( 'admin_enqueue_scripts', 'wpfb_enqueue_editor_assets' );

function wpfb_enqueue_editor_assets( string $hook ): void {

	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( $screen->post_type !== 'wfb_form' ) {
		return;
	}

	if ( ! wp_script_is( 'wpfb-editor', 'enqueued' ) ) {
		[ $edJs, $vEd ] = wpfb_asset( 'dist/editor.js' );
		wp_enqueue_script( 'wpfb-editor', $edJs, [], $vEd, true );
		wpfb_localize_core( 'wpfb-editor' );
	}
}
