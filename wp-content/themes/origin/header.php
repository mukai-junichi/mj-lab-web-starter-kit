<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a href="#main" class="c-skip-link">メインコンテンツへスキップ</a>
<header class="l-header">
	<div class="l-header__inner">
		<div class="l-header__logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</div>
		<nav class="l-header__nav" aria-label="メインメニュー">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'main-menu',
				'container'      => false,
				'menu_class'     => 'l-header__menu',
				'fallback_cb'    => false,
			) );
			?>
		</nav>
	</div>
</header>
<main id="main" class="l-main" id="js-scroll-content">
