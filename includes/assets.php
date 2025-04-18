<?php
/**
 * Front‑end assets – подключаются на всех страницах сайта,
 * где может встретиться шорткод [wpfb …].
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_enqueue_scripts', 'wpfb_enqueue_front_assets' );

function wpfb_enqueue_front_assets() : void {

	$base = plugin_dir_url( __DIR__ ) . 'assets/';   //  …/wp-form-builder/assets/
	$ver  = '1.0.0';

	wp_enqueue_style ( 'wpfb-front-style', $base . 'css/main.css',  [], $ver );
	wp_enqueue_script( 'wpfb-front-script', $base . 'dist/main.js', [], $ver, true );

	// ➜ переменные только для фронтового скрипта
	wp_localize_script( 'wpfb-front-script', 'wpfb_front', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'wpfb_front_nonce' ),
	] );
}
