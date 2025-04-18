<?php

/**
 * Admin‑assets for the Form‑Builder page.
 */
add_action( 'admin_enqueue_scripts', 'wpfb_enqueue_admin_assets' );

function wpfb_enqueue_admin_assets( string $hook ): void {

	// Поключаем ТОЛЬКО на нашей странице панели
	if ( empty( $_GET['page'] ) || $_GET['page'] !== 'wfb-module-form-builder' ) {
		return;
	}

	$base = plugin_dir_url( __DIR__ ) . 'assets/';    //  …/wp-form-builder/assets/
	$ver  = '1.0.0';

	// Стили и JS самой страницы
	wp_enqueue_style( 'wpfb-admin-style', $base . 'css/admin.css', [], $ver );
	wp_enqueue_script( 'wpfb-admin-script', $base . 'dist/admin.js', [], $ver, true );
	wp_enqueue_script( 'wpfb-form-panel', $base . 'dist/form.js', [], $ver, true );

	// 👇 Локализуем переменные — по одному разу для КАЖДОГО хэндла
	$data = [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'wpfb_nonce' ),
	];
	wp_localize_script( 'wpfb-admin-script', 'wpfb_admin', $data );
	wp_localize_script( 'wpfb-form-panel', 'wpfb_admin', $data );   // тем же именем
}
