<?php
get_header();
the_post();
?>
<main class="page_wrap base-inner">
	<ul id="pankuzu" class="clearfix">
		<?php the_pankuzu_keni( ' &gt; ' ); ?>
	</ul>
	<div class="main_contents page_contents">
		<section class="newinfo_single">
			<h2 class="newinfo_tit under_page_tit">新着情報</h2>
			<div class="section">
				<div class="content">
				<?php the_content(); ?>
					</div>
			</div>
<p class="go_archive"><a href="<?php bloginfo('url'); ?>/newinfo" class="more-btn1">一覧に戻る</a></p>
<?php cmn_gotop(); ?>
		</section>
	</div>
	 	<?php get_sidebar('newinfo'); ?>
</main>
<?php get_footer(); ?>
