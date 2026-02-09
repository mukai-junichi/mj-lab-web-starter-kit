<?php
/**
 * 構造化データ (JSON-LD) - Organization & WebSite
 */

function origin_output_structured_data() {
	$site_url  = home_url( '/' );
	$site_name = get_bloginfo( 'name' );
	$desc      = get_bloginfo( 'description' );

	$organization = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Organization',
		'name'        => $site_name,
		'url'         => $site_url,
		'description' => $desc,
	);

	$website = array(
		'@context'        => 'https://schema.org',
		'@type'            => 'WebSite',
		'url'              => $site_url,
		'name'             => $site_name,
		'potentialAction'  => array(
			'@type'       => 'SearchAction',
			'target'      => $site_url . '?s={search_term_string}',
			'query-input' => 'required name=search_term_string',
		),
	);

	$json_ld = array( $organization, $website );
	echo "\n<script type=\"application/ld+json\">\n";
	echo wp_json_encode( $json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	echo "\n</script>\n";
}
add_action( 'wp_head', 'origin_output_structured_data' );
