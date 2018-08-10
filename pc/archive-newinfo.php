<?php
get_header();

$args = array(
	'post_type' => 'newinfo', /* 投稿タイプを指定 */
	'paged' => $paged, /* ページ番号を指定 */
	'posts_per_page' => 10, /* 最大表示数 */
);
query_posts( $args );
?>
<main class="page_wrap base-inner">
	<ul id="pankuzu" class="clearfix">
		<?php the_pankuzu_keni( ' &gt; ' ); ?>
	</ul>
	<div class="main_contents">
		<section class="newinfo_archive">
			<h2 class="newinfo_tit under_page_tit">新着情報</h2>
			<ul class="list">
				<? while ( have_posts() ): the_post();?>
				<li><a href="<?php the_permalink(); ?>">
					<span class="date"><?php the_time('Y/m/d'); ?></span>
					<?php the_title(); ?>
				</a>
				</li>
				<?php endwhile;?>
			</ul>
			<!--customer_navi-->
			<div class="customer_navi clearfix">
				<div class="customer_navi_right">
					<?php if ( function_exists( 'wp_pagenavi' ) ) wp_pagenavi(); ?>
				</div>
			</div>
			<!--customer_navi-->

		</section>
<?php cmn_gotop(); ?>
	</div>
	 	<?php get_sidebar('newinfo'); ?>
</main>
<?php get_footer(); ?>
