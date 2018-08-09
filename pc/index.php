<?php get_header(); ?>
<main class="main_contents">
	<div class="main_v">
		<div class="slide">
			<?php
			$args = array(
				'post_type' => 'slide', /* 投稿タイプ */
				'posts_per_page' => -1 /* 件数表示 */
			);
			query_posts( $args );
			if ( have_posts() ): while ( have_posts() ): the_post();
			?>
			<div class="box">
				<? if ($linkurl = post_custom('slide_link')):?>
				<a href="<? echo $linkurl;?>" <? if(post_custom( 'new_window')){echo ' target=_blank';}?>><?php	printf('%s',gr_get_image('pc_slide',array( 'width' => 950, 'alt' => get_the_title() )));?></a>
				<? else:?>
				<?php printf('%s',gr_get_image('pc_slide',array( 'width' => 950, 'alt' => get_the_title() )));?>
				<? endif;?>
			</div>
			<?php endwhile;
	endif;
	wp_reset_query();?>
		</div>
	</div>
	<div id="oshirase">
<strong>◆◇お盆期間中の営業・休業に関するお知らせ◇◆</strong>
【本社・豊中店】<br />
誠に勝手ながら、2018年8月13日（月）～ 2018年8月16日（木）は休業させていただきます。<br />
通常営業は、8月17日（金）からとなります。<br />
休業中にいただいたお問い合わせについては、通常営業日より順次対応させていただきますので、ご了承ください。<br />
今後も変わらぬご愛顧をどうぞよろしくお願いいたします。<br /><br />
【花博住宅展示場】<br />
花博住宅展示場は、通常通り営業しております！<br />
カフェスペースやオシャレなキッチン、ルーフバルコニーなど、見所が盛りだくさん。どうぞお越しくださいませ。
</div>

	<?php echo do_shortcode('[raiten_bnr]'); ?>
	<?php
	$args = array(
		'post_type' => 'event', /* 投稿タイプ */
		'posts_per_page' => 2 /* 件数表示 */
	);
	query_posts( $args );
	?>
	<div class="t_event-news base-inner">
		<section class="t_event">
			<h2 class="sec_tit t_eve_tit"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_event_tit.png" alt="イベント情報" width="178" height="47"></h2>
			<? if (have_posts()) : while (have_posts()) : the_post();?>
			<article class="box">
				<a href="<?php the_permalink(); ?>" class="inner">
				<div class="detail">
					<?php
						if(get_field( 'icon' )){
$field = get_field_object('icon'); //フィールドの設定情報を取得
$value = $field['value']; //選択された値を取得
$label = $field['choices'][ $value ];//選択された表示名（ラベル）を取得
?>
					<span class="<?php echo $value; ?>"><?php echo $label; }?></span>
					<h3 class="event_tit">
						<? the_title(); ?>
					</h3>
					<?php if(get_field( 'subtit' )){?>
					<p class="text"><?php echo mb_substr((post_custom('subtit')),0,20); ?>...</p><? }?>
					<dl class="info">
						<dt class="kaisai_tit">開催期間</dt>
						<dd class="kaisai_date">
								<? if($text = post_custom( 'event_date' )){
							echo $text;
						}?>
								<?php
									if(get_field('kaisaiday')):
										echo get_field('kaisaiday');
									elseif ( have_rows( 'kaisairepeat' ) ):
									echo '<ul class="list">';
								$rowCount = count( get_field( 'kaisairepeat' ) );
								while ( have_rows( 'kaisairepeat' ) ): the_row();
								$repeat_date = get_sub_field( 'kaisaidate' );
								$week = array( "日", "月", "火", "水", "木", "金", "土" );
								$date = date_create( '' . $repeat_date . '' );
								$date_format = date_format( $date, 'Y年m月d日' );
								$week_format = $week[ ( int )date_format( $date, 'w' ) ];
								echo '<li>' . $date_format . '(' . $week_format . ')</li>';
								endwhile;
								echo '</ul>';
								endif;
								?>
						</dd>
					</dl>
				</div>
				<p class="pic">
					<? if(post_custom('event_img00')){
								printf(
									'%s',
									gr_get_image(
										'event_img00',
										array( 'width' => 100, 'alt' => esc_attr( post_custom( '施行事例' ) ) )
									)
								);
						}elseif(get_field('main_pic')){
	printf(
		'%2$s',
		gr_get_image_src('main_pic'),
		gr_get_image(
			'main_pic',
			array( 'width' => '100', 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}else{
	echo '<img src="'.get_template_directory_uri().'/images/common/noimage.png" width="100" height="100" alt="画像準備中">';
	}
?>
				</p>
				</a>
			</article>
			<?php endwhile;
	endif;
	wp_reset_query();?>
			<p class="more"><a href="<?php bloginfo('url'); ?>/event" class="more-btn1">一覧を見る</a>
			</p>
		</section>

		<section class="t_news">
			<h2 class="sec_tit t_news_tit"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_news_tit.png" alt="新着情報" width="205" height="47"></h2>

			<?php $argswn = array(
				'post_type' => 'whatsnew',
				'posts_per_page' => 5,
			); ?>
			<?php $my_query = new WP_Query( $argswn ); ?>

			<ul class="list">

				<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>

				<li>
					<a href="<?php echo post_custom('whatsnew_link'); ?>" class="icon-more">
						<?php if(post_custom('whatsnew_newicon')){
						 	echo '<img src="https://www.taniue-reform.jp/wp/wp-content/themes/reform2/page_image/top/new_icon.png" class="w_new" />';
						} ?>
						<span class="date"><?php the_time('Y/m/d'); ?></span>
						<?php the_title(); ?>
					</a>
				</li>

			<?php endwhile; ?>

			</ul>
			<?php wp_reset_postdata(); ?>
		</section>
	</div>

	<section class="t_modelhouse">
		<h2 class="sec_tit t_model_tit"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_model_tit.png" alt="モデルハウス" width="241" height="47"></h2>
		<p class="comment font-gen-md">ご予約ひとつでいつでも見れるリノベーションモデル。<br> リノベーションの経験豊富なスタッフのプランしたお家を
			<br> ぜひご体感下さいませ。
		</p>
		<ol class="list2 base-inner">
			<?php
			$args = array(
				'post_type' => 'modelhouse', /* 投稿タイプ */
				'order' => 'ASC',
				'posts_per_page' => -1 /* 件数表示 */
			);
			query_posts( $args );
			$i = 1;
			if ( have_posts() ): while ( have_posts() ): the_post();
			?>

			<li>
			<a href="<?php the_permalink(); ?>">
				<span class="pic"><?php if(get_field( 'commingsoon' )){ //準備中にチェックがあったら
				if(get_field('top_comingsoon_pic')){
	printf(
		'%2$s',
		gr_get_image_src('top_comingsoon_pic'),
		gr_get_image(
			'top_comingsoon_pic',
			array( 'width' => 270, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);}elseif(get_field('top_img')){ //上記がなく、トップページ用の写真があったら
	printf(
		'%2$s',
		gr_get_image_src('top_img'),
		gr_get_image(
			'top_img',
			array( 'width' => 270, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}elseif(get_field('mainpic')){ //上記2点がなく、メイン写真があったら
	printf(
		'%2$s',
		gr_get_image_src('mainpic'),
		gr_get_image(
			'mainpic',
			array( 'width' => 270, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
}elseif(get_field('top_img')){ //準備中にチェックがなかったら
	printf(
		'%2$s',
		gr_get_image_src('top_img'),
		gr_get_image(
			'top_img',
			array( 'width' => 270, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}elseif(get_field('mainpic')){
	printf(
		'%2$s',
		gr_get_image_src('mainpic'),
		gr_get_image(
			'mainpic',
			array( 'width' => 270, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}?>
					<?php
					$field = get_field_object('icon'); //フィールドの設定情報を取得
					$value = $field['value']; //選択された値を取得
					$label = $field['choices'][ $value ];//選択された表示名（ラベル）を取得
?>
					<span class="icon font-gen-h <?php echo $value; ?>"><?php if($value == 'mansion'){echo 'MANSION MODEL';}elseif($value == 'kodate'){echo 'MODEL HOUSE';} ?></span>


				</span>
				<span class="tit"><span class="en font-gen-h"><?php echo get_field('tit_en');?></span> <span class="ja font-gen-n"><? the_title(); ?></span>
				</span>				<span class="add font-gen-bold"><? echo get_field('detail_add');?></span></a></li>
<?php
			$i ++; endwhile;endif;wp_reset_query();?>
		</ol>
	</section>
			<?php
			$args = array(
				'post_type' => 'ogata_seko', /* 投稿タイプ */
				'posts_per_page' => 3 /* 件数表示 */
			);
			query_posts( $args );
			if ( have_posts() ): ?>
	<section class="t_worksvoice">
		<h2 class="sec_tit t_model_tit"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_ogata_tit.png" alt="大型施工事例" width="256" height="47"></h2>
		<div class="inner base-inner">
<? while ( have_posts() ): the_post();?>
			<div class="box">
				<a href="<?php the_permalink(); ?>"><span class="pic"><?
	$rows = get_field ( 'after_pic' );
	$caption =
	$first_row = $rows[0];
	$first_row_image = $first_row['pic'];
	$image = wp_get_attachment_image_src( $first_row_image, 'w300' );
	if($image){
	echo '<img src="' . $image[0] . '" width="'.$image[1].'" height="'.$image[2].'" alt="'.get_the_title().'">';}?></span> <span class="detail_tit font-gen-md">
	<?php echo mb_strimwidth(get_the_title(), 0, 50, "...", "UTF-8"); ?></span>
		<?php
	$rows = get_field ( 'enquete' );
	$caption = get_field ( 'answer' );
	$first_row = $rows[0];
	$first_row_caption = $first_row['answer'];?>
	<span class="text"><?php echo mb_strimwidth($first_row_caption, 0, 220, "...", "UTF-8"); ?></span> </a>
			</div>
<?php endwhile;?>
		</div>
		<p class="more"><a href="<?php bloginfo('url'); ?>/ogata_seko" class="more-btn1">VIEW MORE</a></p>
	</section>
<? endif; wp_reset_query();?>
			<?php
			$args = array(
				'post_type' => 'seko', /* 投稿タイプ */
				'posts_per_page' => 7 /* 件数表示 */
			);
			query_posts( $args );
			if ( have_posts() ): ?>
	<section class="t_parts">
		<h2 class="sec_tit t_parts_tit text-white"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_seko_tit.png" alt="部分施工事例" width="235" height="49"></h2>
		<div class="inner base-inner">
			<ul class="list">
<? while ( have_posts() ): the_post();?>
				<li><a href="<?php the_permalink(); ?>">
					<p class="pic"><?php
						if(get_field('mainpic')){
								printf(
									'%s',
									gr_get_image(
										'mainpic',
										array( 'width' => 500, 'alt' => esc_attr( post_custom( '施行事例' ) ) )
									)
								);

						}elseif(post_custom('seko_after_image')){
								printf(
									'%s',
									gr_get_image(
										'seko_after_image',
										array( 'width' => 500, 'alt' => esc_attr( post_custom( '施行事例' ) ) )
									)
								);
						}?></p></a>
					<div class="detail font-gen-bold">
						<p class="name"><? if(get_field('name')){ echo get_field('name');}elseif(post_custom('seko_name')){ echo post_custom('seko_city').post_custom('seko_name');}?></p>
						<p class="text"><?
							$content = get_field('detail_content');
							$seko_content = post_custom('seko_content');
							if($content){ echo mb_strimwidth($content, 0, 40, "...", "UTF-8");}elseif($seko_content){ echo mb_strimwidth($seko_content, 0, 40, "...", "UTF-8");}?><br> （工期：<? if(get_field('detail_duration')){ echo get_field('detail_duration');}elseif(post_custom('seko_duration')){ echo post_custom('seko_duration');}?>）
						</p>
					</div>
				</li>
<?php endwhile;?>
			</ul>
			<p class="more"><a href="<?php bloginfo('url'); ?>/seko" class="more-btn2">VIEW MORE</a>
			</p>
		</div>
	</section>
<? endif; wp_reset_query();?>
	<p class="top_bnr bnr_campaign base-inner"><a href="https://www.reli-fudosannavi.jp/" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_bukken.png" alt="物件数最大級約4,000件公開中！" width="950" height="256"></a>
	</p>
			<?php
			$args = array(
				'post_type' => 'column', /* 投稿タイプ */
				'posts_per_page' => 4 /* 件数表示 */
			);
			query_posts( $args );
			if ( have_posts() ): ?>
	<section class="t_column t_sec">
		<h2 class="sec_tit t_column_tit"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_column_tit.png" alt="お役立ちコラム" width="174" height="48"></h2>
		<ul class="base-inner inner">
<? while ( have_posts() ): the_post();?>
			<li><a href="<?php the_permalink(); ?>"><?php
						if(get_field('top_img_out')){
								printf(
									'%s',
									gr_get_image(
										'top_img_out',
										array( 'width' => 211, 'alt' => esc_attr( get_the_title() ))
									)
								);

						}?></a>
</li>
<?php endwhile;?>
		</ul>
		<p class="more"><a href="<?php bloginfo('url'); ?>/column" class="more-btn1">VIEW MORE</a>
		</p>
<? endif; wp_reset_query();?>
	</section>
	<section class="t_link t_sec">
		<h2 class="sec_tit t_link_tit"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/t_link_tit.png" alt="リンク集" width="124" height="48"></h2>
		<ul class="list base-inner">
			<li><a href="http://www.facebook.com/taniue" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_link_face.png" alt="facebook" width="298" height="98"></a>
			</li>
			<li><a href="http://www.instagram.com/reli_taniue" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_link_reriinsta.png" alt="ReliInstagram" width="298" height="98"></a>
			</li>
			<li><a href="http://www.instagram.com/insideallagi/?hl=ja" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_link_insta.png" alt="Instagram" width="298" height="98"></a>
			</li>
			<li><a href="http://osaka-fudosannavi.jp/" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_link_allagi.png" alt="ALLGI" width="298" height="98"></a>
			</li>
			<li><a href="http://www.taniue.jp" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_link_style.png" alt="STYLE HOUSE" width="298" height="98"></a>
			</li>
			<li><a href="http://www.next-taniue.jp" class="img_hover" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_link_next.png" alt="next" width="298" height="98"></a>
			</li>
		</ul>
	</section>
	<p class="top_bnr bnr_campaign base-inner"><a href="<?php bloginfo('url'); ?>/staff" class="img_hover"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/bnr_staff.png" alt="スタッフ紹介" width="950" height="264"></a>
	</p>
</main>
<?php get_footer(); ?>
