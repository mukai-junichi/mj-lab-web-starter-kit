<?php
/**
 * Origin Theme - functions and definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ORIGIN_VITE_DEV' ) ) {
	define( 'ORIGIN_VITE_DEV', false );
}

/**
 * Theme Setup
 */
function origin_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );
	register_nav_menus( array(
		'main-menu'   => 'メインメニュー',
		'footer-menu' => 'フッターメニュー',
	) );
}
add_action( 'after_setup_theme', 'origin_setup' );

/**
 * Scripts & Styles (Vite dev / production)
 */
function origin_scripts() {
	$vite_dev_file = get_template_directory() . '/.vite-dev';
	$vite_dev      = ( defined( 'ORIGIN_VITE_DEV' ) && ORIGIN_VITE_DEV ) || file_exists( $vite_dev_file );
	$is_localhost  = isset( $_SERVER['HTTP_HOST'] ) && ( $_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos( $_SERVER['HTTP_HOST'], '127.0.0.1:' ) === 0 );
	$vite_dev      = $vite_dev && $is_localhost;
	$vite_url      = 'http://localhost:3000';

	wp_enqueue_style( 'origin-style', get_stylesheet_uri(), array(), '1.0.0' );

	if ( $vite_dev ) {
		wp_enqueue_script( 'vite-client', $vite_url . '/@vite/client', array(), null, true );
		wp_enqueue_script( 'origin-main', $vite_url . '/src/js/main.js', array( 'vite-client' ), null, true );
		add_filter( 'script_loader_tag', 'origin_script_module_type', 10, 3 );
	} else {
		wp_enqueue_style( 'origin-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );
		wp_enqueue_script( 'origin-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
	}
}
add_action( 'wp_enqueue_scripts', 'origin_scripts' );

/**
 * Defer scripts (PageSpeed)
 */
function origin_defer_scripts( $tag, $handle, $src ) {
	if ( is_admin() ) {
		return $tag;
	}
	$exclude = array( 'jquery', 'jquery-core', 'jquery-migrate', 'wp-i18n', 'contact-form-7', 'wp-polyfill' );
	if ( in_array( $handle, $exclude, true ) ) {
		return $tag;
	}
	if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) {
		return $tag;
	}
	return str_replace( ' src', ' defer src', $tag );
}
add_filter( 'script_loader_tag', 'origin_defer_scripts', 10, 3 );

/**
 * HTTPS assets
 */
function origin_is_https() {
	return is_ssl() ||
		( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) ||
		( isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on' );
}

function origin_force_https_assets( $src, $handle ) {
	if ( ! $src || ! origin_is_https() ) {
		return $src;
	}
	$theme_handles = array( 'origin-style', 'origin-main' );
	if ( in_array( $handle, $theme_handles, true ) ) {
		return str_replace( 'http://', 'https://', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'origin_force_https_assets', 10, 2 );
add_filter( 'script_loader_src', 'origin_force_https_assets', 10, 2 );

function origin_script_module_type( $tag, $handle, $src ) {
	if ( in_array( $handle, array( 'vite-client', 'origin-main' ), true ) ) {
		return str_replace( ' src', ' type="module" src', $tag );
	}
	return $tag;
}

/**
 * Includes (SEO, Structured Data)
 */
require get_template_directory() . '/inc/seo-fallback.php';
require get_template_directory() . '/inc/structured-data.php';

/**
 * Security
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'wp_generator' );

function origin_no_self_ping( &$links ) {
	$home = get_option( 'home' );
	foreach ( $links as $l => $link ) {
		if ( strpos( $link, $home ) === 0 ) {
			unset( $links[ $l ] );
		}
	}
}
add_action( 'pre_ping', 'origin_no_self_ping' );
