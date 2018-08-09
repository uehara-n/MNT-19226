<?php get_header(); ?>
<!--=============================================== ヘッダー　ここまで -->

<!--=============================================== メインビジュアル　ここから -->
<div id="kv">
	<section>
        <div class="inner">
            <div class="sliderArea">
                <ul id="slider" class="slider">
                    <li>
                        <a href="./raiten/" onclick="ga('send', 'event', 'topbanner', 'click', '15プラン');" class="link"><img src="<?php bloginfo('template_url'); ?>/page_image/top/slide/slide1.jpg" alt="" class="banner" />
                        </a>
                    </li>
                    <li>
                        <a href="./company/nagano" onclick="ga('send', 'event', 'topbanner', 'click', '無料相談会');"><img src="<?php bloginfo('template_url'); ?>/page_image/top/slide/slide2.jpg" alt="" class="banner" /></a>
                    </li>
                   <li>
                        <a href="./award/" onclick="ga('send', 'event', 'topbanner', 'click', 'ショールーム');"><img src="<?php bloginfo('template_url'); ?>/page_image/top/slide/slide3.jpg" alt="" class="banner" /></a>
                    </li>

                </ul>
            </div>
        </div>
    </section>
</div>
<div id="apDots"></div>
<!--=============================================== メインビジュアル　ここまで -->
<div class="top_company_area">
  <h2 class="company_tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_tit.png" alt="長野県に3店舗！サンプロのショールーム" width="962" height="58"></h2>
  <ul class="inner">
    <li>
      <h3 class="tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_tit1.png" alt="サンプロスタイルスタジオ塩尻" width="139" height="18"></h3>
      <a href="<?php bloginfo('url'); ?>/company/hon"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_bnr1_rollout.png" width="300" height="159" alt="ショールームを見る" ></a></li>
    <li>
      <h3 class="tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_tit2.png" alt="暮らしと不動産の窓口" width="138" height="18"></h3>
      <a href="<?php bloginfo('url'); ?>/company/matsumoto"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_bnr2_rollout.png" width="300" height="159" alt="ショールームを見る" ></a></li>
    <li>
      <h3 class="tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_tit3.png" alt="サンプロスタイルスタジオ長野" width="138" height="18"></h3>
      <a href="<?php bloginfo('url'); ?>/company/nagano"><img src="<?php bloginfo('template_url'); ?>/page_image/top/showroom_bnr3_rollout.png" alt="ショールームを見る" width="300" height="159"></a></li>
  </ul>
</div>

<div id="content">

	<!--
=============================================== メインコンテンツ　ここから -->
<div id="c_right">

<div id="oshirase">
<strong>◆◇◆◇　お盆休業・臨時休業のお知らせ　◆◇◆◇　</strong><br />
いつもサンプロリフォームをご愛顧頂き、ありがとうございます。<br />
下記期間はお盆休業および臨時休業となります。<br />
お客様にはご不便をおかけいたしますが、何卒ご了承くださいますようお願いいたします。<br />
■臨時休業<br />
８月１０日（金）は正午１２時閉店とし午後は臨時休業となります。<br /><br />
■お盆休業<br />
休業日：８月１１日（土）～１５日（水）<br /><br />
■休業中のお問い合わせについて<br />
８月１６日（木）より順次対応させていただきます。<br />
なお、お急ぎのお客様は０９０－７００７－３２２９（須山）までご連絡下さい。<br /><br />
お客様にはご迷惑をお掛け致しますが、何卒ご了承頂けますようよろしくお願い致します。
</div>

    <!--<div class="mb20 over"><a href="https://www.home-arrange.jp/event/21135.html"><img src="<?php bloginfo('template_url'); ?>/images/top/bnr_soudankai_rollout.png"　width="710" height="170" alt="住まいのリフォーム相談会" /></a></div>-->



<!--<div class="mb20">
<a href="https://www.home-arrange.jp/lp"><img src="<?php bloginfo('template_url'); ?>/page_image/lp_02/lp_bnr_rollout.jpg"　width="710" height="180" alt="住宅博2017" /></a>
</div>
-->



<!--====================================================新着情報　ここから -->
<div class="top_news">
      <h2 class="news_tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/new_tit.gif" width="161" height="25" alt="新着情報" /></h2>
<ul>
  <?php $args = array(
    'numberposts' => -1, //表示する記事の数
    'post_type' => 'whatsnew', //投稿タイプ名
    // 条件を追加する場合はここに追記
  );
  $customPosts = get_posts($args);
  if($customPosts) : foreach($customPosts as $post) : setup_postdata( $post );
  ?>
  <li>
<span class="date">
<?php if(post_custom('whatsnew_link')): ?>
<a href="<?php echo post_custom('whatsnew_link'); ?>">
	<?php endif; ?>
<?php if(post_custom('whatsnew_newicon')){
 	echo '<img src="/wp-content/themes/reform/page_image/top/new_icon.png" class="w_new" />';
} ?>
<?php the_time('Y/m/d'); ?>
<?php if(post_custom('whatsnew_link')): ?>
</a>
<?php endif; ?>
</span>
<span class="text">
<?php if(post_custom('whatsnew_link')): ?>
<a href="<?php echo post_custom('whatsnew_link'); ?>">
<?php endif; ?>
<?php echo get_post_meta($post->ID, 'whatsnew_text', true); ?>
<?php if(post_custom('whatsnew_link')): ?>
</a>
<?php endif; ?>
</span>
</li>
  <?php endforeach; ?>
  <?php else : //記事が無い場合 ?>
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/none.css" />
  <?php endif;
  wp_reset_postdata(); //クエリのリセット ?>
</ul>
</div>
<!--====================================================新着情報　ここから -->




<!--====================================================大型施工事例　ここから -->
		<div id="t_ogata">
			<h2 class="ogata_tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/ogata_tit.gif" width="710" height="40" alt="大型リフォーム・リノベーション施工事例"></h2>
		<!--
====================================================新大型施工事例　ここから　-->
						<?php $args = array(
			'post_type' => 'special', /* 投稿タイプ */
			'paged' => $paged,
			'posts_per_page' => 6 /* 件数表示 */
			); ?>
	<?php query_posts( $args ); ?>
	<?php if (have_posts()) : ?>
	<div class="inner">
<? $i = 0;
$x = 0; ?>

					<?php while( have_posts() ) : the_post(); ?>


<div class="box"><a href="<?php the_permalink(); ?>" class="heightLine-group<?php echo $n; ?>"><span class="img"><?php if(post_custom('special_t_image')){echo gr_get_image( 'special_t_image', array( 'width' => 230 ) );}
	else { echo gr_get_image( 'special_image', array( 'width' => 230 ) );}?>
	<? if(post_custom('special_magaicon')){
 	echo '<img src="/wp-content/themes/reform/page_image/special/icon_maga.png" class="maga" />';
} ?>
	<? if(post_custom('special_newicon')){
 	echo '<img src="/wp-content/themes/reform/images/top/new.png" class="new" />';
} ?>


	</span>
  <span class="txt"><span class="tit"><?php the_title();?></span>
  <span class="name"><?php if($text=post_custom( 'special_add' )){ echo ( $text );} ?>  <?php if($text=post_custom( 'special_name' )){ echo ( $text );} ?></span>
 <?php if($text=post_custom( 'special_naiyo' )){ echo ( $text );} ?></span>
  <span class="more"><img src="<?php bloginfo('template_url'); ?>/page_image/top/oogata/more.png" alt="詳しくはこちら" width="206" height="30" class="over"></span>
  </a> </div>
<?php $i++; ?>
							<?php endwhile; ?>
</div>
<?php endif; ?>
<?php wp_reset_query(); ?>
			<div class="more">
				<a href="/special"><img src="<?php bloginfo('template_url'); ?>/page_image/top/ogata/ogata_more.gif" alt="大規模リフォーム・リノベーション施工事例" width="420" height="40" class="over"></a>
			</div>

</div>
<!--====================================================新大型施工事例　ここまで　-->
 <div id="t_omakase">
    <h2><img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase_tit.png" width="710" height="60" alt="私たち建築・リフォームのスペシャリストにおまかせください" /></h2>
    <div class="inner">
      <div class="box">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_yamamoto.jpg" width="115" height="150" alt="建築士山本" />
      </div>
      <div class="box">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_maruyama.jpg" width="115" height="150" alt="建築士丸山" />
      </div>
      <div class="box">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_abe.jpg" width="115" height="150" alt="建築士安部" />
      </div>
      <div class="box">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_yamamoto2.jpg" width="115" height="150" alt="建築士山本" />
      </div>
      <div class="box">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_kachino.jpg" width="115" height="150" alt="建築士勝野" />
      </div>
      <div class="box">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_takamatsu.jpg" width="115" height="150" alt="建築士高松" />
      </div>
    </div>
    <div class="more">
      <a href="/company/specialist"><img src="<?php bloginfo('template_url'); ?>/page_image/top/omakase/spe_more.png" alt="スペシャリスト集団について詳しく見る" width="420" height="40" class="over" /></a>
    </div>
  </div>
<?php gr_contacts_banner(); ?>
		<!--
========================================================施工事例　ここから -->
		<div class="seko_area">
    <h2 class="seko_tit"><img src="<?php bloginfo('template_url'); ?>/page_image/top/seko/top_h2tit.gif" width="710" height="40" alt="施工事例" class="left" /><a href="/seko"><img src="<?php bloginfo('template_url'); ?>/page_image/top/seko_more.png" width="157" height="14" alt="すべての施工事例を見る" class="right over" /></a></h2>
<?php echo do_shortcode('[seko_archive kensu=18]'); ?>
		</div>
		<!--
========================================================施工事例　ここまで -->

<?php gr_contacts_banner(); ?>

		<!--
========================================================あいさつ ここまで -->
		<!--
========================================================選ばれる理由　ここから -->
		<div id="aisatu">
    <h2><img src="<?php bloginfo('template_url'); ?>/page_image/top/aisatu_tit.png" width="710" height="55" alt="サンプロは、地域に根ざしたリフォーム店を目指し、日々努めて参ります。" /></h2>
    <div class="inner">
      <p class="txt">私たちは、松本市の住宅リフォーム･増改築の専門店として、平成12年1月から株式会社サンプロのリフォームブランドである｢ホームアレンジ｣としてスタートし、平成23年4月から、｢サンプロリフォーム｣と改名致しました。<br />
        現在松本市･塩尻市の中信地域を中心に、暑い日も寒い日も元気よく飛び回っています。<br />
        これからも、お客様の「想い」を「カタチ」にかえられるよう、地域に根ざしたリフォーム店を目指し、誠心誠意、日々取り組んで参ります。<br />
        弊社の近くへおでかけの際には、是非ショールームにもお立ち寄りください。 <span class="name"><img src="<?php bloginfo('template_url'); ?>/page_image/top/aisatu/daihyo_name.gif" width="222" height="22" alt="代表取締役社長　青柳弘昭" /></span></p>
      <div class="img">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/aisatu/daihyo_pic.png" alt="代表写真" width="229" height="257" />
      </div>
      <br clear="all" />
    </div>
  </div>
<div id="t_riyu">
    <h2><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu_tit.png" width="710" height="60" alt="サンプロがお客様にお選べいいただいている理由" /></h2>
    <div class="box">
      <div class="img">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/t_riyu1.jpg" width="190" height="140" alt="自由な提案力" />
      </div>
      <div class="txt">
        <h3><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_stit1.gif" width="243" height="24" alt="理由その1自由な提案力" /></h3>
        <p>8,000件以上のリフォーム実績を誇る建築士集団が<br />
          豊富な経験を活かしてあなたのお住まいにベストなリフォーム提案を行います。</p>
      </div>
      <div class="more">
        <a href="/company/specialist"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go1.gif" alt="スペシャリスト集団" width="143" height="14" class="over" /></a>
      </div>
    </div>
    <div class="box">
      <div class="img">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/t_riyu2.jpg" width="190" height="140" alt="確かな技術力" />
      </div>
      <div class="txt">
        <h3><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_stit2.gif" width="243" height="24" alt="理由その2確かな技術力" /></h3>
        <p>職人さん次第で仕上がりは変わってきます。<br />
          お客さまの想いが叶えられるよう、職人ひとりひとりがこだわりを持って　技術に磨きをかけて、お客さまのお住まいをリフォームさせて頂きます。</p>
      </div>
      <div class="more">
        <a href="/specialcraftman"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go2.gif" alt="地域で一番の職人たち" width="159" height="14" class="over" /></a>
      </div>
    </div>
    <div class="box">
      <div class="img">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/t_riyu3.jpg" width="190" height="140" alt="お値打ち価格" />
      </div>
      <div class="txt">
        <h3><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_stit3.gif" width="245" height="24" alt="理由その３お値打ち価格" /></h3>
        <p>キッチン、バス、トイレ、給湯器などの住宅設備が最大70％もお安くご購入していただく事が出来ます。<br />
          住宅設備をお値打ち価格でのご提供を実現した「直販システム」をご紹介します。</p>
      </div>
      <div class="more">
        <a href="/jyusetsu"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go3.gif" alt="住設が安いわけ" width="119" height="14" class="over" /></a>
      </div>
    </div>
    <div class="box">
      <div class="img">
        <img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/t_riyu4.jpg" width="190" height="140" alt="安心信頼" />
      </div>
      <div class="txt">
        <h3><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_stit4.gif" width="218" height="24" alt="理由その４安心・信頼" /></h3>
        <p>リフォームをお考えならまずはリフォームの流れを知りましょう。<br />
          独自の保証・アフターフォローがしっかりしているサンプロだから安心！</p>
      </div>
      <ul class="riyu4_list">
        <li><a href="/reformnagare"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go4.gif" alt="リフォームの流れ" width="129" height="14" class="over" /></a></li>
        <li><a href="/company/service/"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go5.gif" alt="サンプロ20大サービス" width="160" height="14" class="over" /></a></li>
        <li><a href="/voice/"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go6.gif" alt="お客様の声" width="91" height="14" class="over" /></a></li>
        <li><a href="/seko/"><img src="<?php bloginfo('template_url'); ?>/page_image/top/riyu/riyu_go7.gif" alt="施工事例" width="78" height="14" class="over" /></a> </li>
      </ul>
    </div>
  </div>
  <!--
========================================================選ばれる理由　ここまで -->





<!--========================================= 回遊バナー　ここから -->
<?php gr_kaiyu_banner(); ?>
<?php gr_contact_banner(); ?>
	</div>
	<!--
=================================================== メインコンテンツ　ここまで -->

	<!--
========================================================= サイドバー　ここから -->

<?php get_sidebar(); ?>
	<!--
=============================================== サイドバー　ここまで -->
	<br clear="all" />
</div>
<!--
================================================= フッター　ここから -->
<?php get_footer(); ?>
