<?php get_header(); ?>
<main class="main_contents">
	<ul id="pankuzu" class="clearfix">
		<?php the_pankuzu_keni( ' &gt; ' ); ?>
	</ul>
		<section class="newinfo_single">
			<h2 class="newinfo_tit under_page_tit">新着情報</h2>
			<div class="section">
				<div class="content">
				<?php the_content(); ?>
					</div>
			</div>
<p class="go_archive"><a href="<?php bloginfo('url'); ?>/newinfo" class="more-btn4">一覧に戻る</a></p>
		</section>

</main>
<?php get_footer(); ?>
