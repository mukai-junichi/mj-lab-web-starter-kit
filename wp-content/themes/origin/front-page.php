<?php get_header(); ?>
<section class="p-front-hero">
	<div class="l-container">
		<h1 class="p-front-hero__title"><?php bloginfo( 'name' ); ?></h1>
		<p class="p-front-hero__desc"><?php bloginfo( 'description' ); ?></p>
	</div>
</section>
<section class="p-front-content">
	<div class="l-container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="p-front-content__body">
				<?php the_content(); ?>
			</div>
		<?php endwhile; endif; ?>
	</div>
</section>
<?php get_footer(); ?>
