<?php get_header(); ?>
<section class="p-archive">
	<div class="l-container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article <?php post_class( 'c-card' ); ?>>
				<h2 class="c-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; else : ?>
			<p>投稿がありません。</p>
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>
