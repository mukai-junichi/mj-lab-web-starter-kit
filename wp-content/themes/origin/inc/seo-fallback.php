<?php
/**
 * SEO 補完（SEO SIMPLE PACK 等のプラグイン未入力時用）
 * プラグインを使用しない場合は、title と description のメタはこのテーマでは出力しません（WP 標準の title-tag のみ）。
 */

function origin_get_default_seo_data() {
	$site_name = get_bloginfo( 'name' );
	$desc      = get_bloginfo( 'description' );
	return array(
		'front' => array(
			'title' => $site_name . ' | ' . $desc,
			'desc'  => $desc,
		),
	);
}

// SEO SIMPLE PACK 等が ssp_output_title フィルタを使う場合に補完
add_filter( 'ssp_output_title', function( $title ) {
	$defaults = origin_get_default_seo_data();
	$slug     = is_front_page() ? 'front' : ( is_page() ? get_post()->post_name : '' );
	if ( ( $title === get_bloginfo( 'name' ) || empty( $title ) ) && isset( $defaults[ $slug ] ) ) {
		return $defaults[ $slug ]['title'];
	}
	return $title;
}, 10, 1 );

add_filter( 'ssp_output_description', function( $description ) {
	if ( ! empty( $description ) ) {
		return $description;
	}
	$defaults = origin_get_default_seo_data();
	$slug     = is_front_page() ? 'front' : ( is_page() ? get_post()->post_name : '' );
	if ( isset( $defaults[ $slug ]['desc'] ) ) {
		return $defaults[ $slug ]['desc'];
	}
	return get_bloginfo( 'description' );
}, 10, 1 );

