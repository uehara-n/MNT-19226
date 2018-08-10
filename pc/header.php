<!doctype html>
<html>
<!--アナリティクスタグ-->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-51810895-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-51810895-1');
</script>
<!--/アナリティクスタグ-->

<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TBDG3JX');</script>
<!-- End Google Tag Manager -->	<meta charset="UTF-8">
	<meta name="format-detection" content="telephone=no">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
	<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
	<link href="<?php echo get_template_directory_uri(); ?>/css/common/allpage.css" rel="stylesheet">
	<link href="<?php echo get_template_directory_uri(); ?>/css/common/lightbox.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" id="u2" href="https://www.taniue-reform.jp/wp/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.9.8&amp;wp-mce-4800-20180716-tadv-4.6.7">
<?php //TOPページのみの分岐
if ( is_home() || is_front_page() ) : ?>
<link href="<?php echo get_template_directory_uri(); ?>/css/top.css" rel="stylesheet">
<?php else: ?>
	<link href="<?php echo get_template_directory_uri(); ?>/css/common/page.css" rel="stylesheet">
<?php if(is_page()&&!is_404()): ?>
	<?php
	$root_slug = ps_get_root_page( $post );
	$root_slug = $root_slug->post_name;
	?>
<link href="<?php bloginfo('template_directory'); ?>/css/<?php echo $root_slug; ?>.css" rel="stylesheet" type="text/css"/>
<!-- /固定ページcss -->
	<?php else: ?>
<link href="<?php bloginfo('template_directory'); ?>/css/<?php echo esc_html(get_post_type_object(get_post_type())->name); ?>.css" rel="stylesheet" type="text/css"/>
	<!-- 各ページcss -->
	<?php endif; ?>
<?php //TOPページのみの分岐終わり
endif;?>
<?php //スライダー使用するページの設定
if ( is_home() || is_front_page() || is_page_template( 'page-showroom.php' ) || is_page( '24859' ) ) : ?>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/slick.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/slick-theme.css"/>
<?php endif;?>
<?php //テンプレートがshowroomの時
if ( is_page_template( 'page-showroom.php' )  ) : ?>
<link rel="stylesheet" type="text/css" href="<?php  echo get_stylesheet_directory_uri(); ?>/css/show_common.css"/>
<?php endif; ?>
	<?php if( is_404( )):?>
	<!-- 404の時-->
	<link href="<?php echo get_template_directory_uri(); ?>/css/common/notfound.css" rel="stylesheet">
	<?php endif; ?>
	<!--検索結果画面 -->
	<?php if ( is_search() ) : ?>
	<link href="<?php echo get_template_directory_uri(); ?>/css/search.css" rel="stylesheet">
	<?php endif; ?>
	<script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
	<?php if(is_post_type_archive(array('ogata_seko','ogata_seko_price_cat','ogata_seko_syubetsu_cat','seko','seko_price_cat','seko_syubetsu_cat'))||is_singular(array('ogata_seko','seko'))||is_tax(array('ogata_seko','ogata_seko_price_cat','ogata_seko_syubetsu_cat','seko','seko_price_cat','seko_syubetsu_cat'))||is_search()  ): ?>
<!--==================== 大型施工事例、施工事例、サーチ画面 -->
<script>
function offradio() {
   var ElementsCount = document.sample.elements.length; // ラジオボタンの数
   for( i=0 ; i<ElementsCount ; i++ ) {
      document.sample.elements[i].checked = false;
   }
}
</script>
		<script>
		$(function () {
		$(".search_price input[type='radio']").change(function(){
			if($(this).is(":checked")){
          $('.search_price .radio_bg2').removeClass("radio_on2");
          $(this).parent().addClass("radio_on2");
      }
  });
});
		$(function () {
		$(".search_syubetsu input[type='radio']").change(function(){
			if($(this).is(":checked")){
          $('.search_syubetsu .radio_bg1').removeClass("radio_on1");
          $(this).parent().addClass("radio_on1");
      }
  });
});
		$(function () {
		$(".search_bui input[type='radio']").change(function(){
			if($(this).is(":checked")){
          $('.search_bui .radio_bg3').removeClass("radio_on3");
          $(this).parent().addClass("radio_on3");
      }
  });
});
$(function(){
    $('.reset').click(function(){
	$('.search_bui .radio_bg3').removeClass("radio_on3");
	$('.search_price .radio_bg2').removeClass("radio_on2");
	$('.search_syubetsu .radio_bg1').removeClass("radio_on1");
	});
});
</script>
<!--==================== /大型施工事例、施工事例、サーチ画面 -->
<?php endif; ?>
	<script src="<?php echo get_template_directory_uri(); ?>/js/navi.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/rollover2.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/smartRollover.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/lightbox.js" type="text/javascript"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/smoothScroll.js" type="text/javascript"></script>
	<?php if(is_page(array('15684','19095','360') )||is_singular( array('event','modelhouse' )) || is_page_template( 'page-showroom.php' )  ): ?>
	<!-- ============フォーム関連 -->
	<!-- 郵便番号 -->
	<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
	<script type="text/JavaScript">
		jQuery(function(){ jQuery('#zip-btn').click(function(event){ AjaxZip3.zip2addr('zip','','ken','add'); return false; }) })
	</script>
	<!-- バリデーション -->
<script>
jQuery(document).ready(function(){
    jQuery("#formID").validationEngine();
});
</script>
	<style type="text/css">
	.wpcf7-not-valid-tip {
	    display: none !important;
	}
	span.wpcf7-form-control-wrap {
	        position: static !important;
	}
	.formError .formErrorContent {
	    border: none !important;
	    box-shadow: none !important;
	}
	.formError .formErrorArrow div {
	    border-left: none !important;
	    border-right: none !important;
	    box-shadow: none !important;
	}
	#request_area table tr:hover {
	background-color: #FEFBF3;
	}
	</style>
<!-- /バリデーション -->
<!-- ============/フォーム関連 -->
	<?php endif; ?>

<!-- ============各フォームバリデーション -->
<? if(is_page(array('15684','19095') )||is_singular( array('modelhouse','event' )) || is_page_template( 'page-showroom.php' ) ): ?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
	 jQuery("#name").addClass("validate[required]");
	 jQuery("#furi").addClass("validate[required]");
	 jQuery("#email").addClass("validate[required,custom[email]]");
	 jQuery("#url").addClass("validate[required,custom[url]]");
	 jQuery("#tel").addClass("validate[required,custom[phone]]");
	});
	</script>


<?php endif; ?>
<? if(is_page(array('360') )): //資料請求バリデーション ?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
	 jQuery("#name").addClass("validate[required]");
	 jQuery("#furi").addClass("validate[required]");
	 jQuery("#email").addClass("validate[required,custom[email]]");
	 jQuery("#url").addClass("validate[required,custom[url]]");
	 jQuery("#tel").addClass("validate[required,custom[phone]]");
	 jQuery("#zip").addClass("validate[required]");
	 jQuery("#add").addClass("validate[required]");
	});
	</script>


<?php endif; //資料請求バリデーション ?>
<!-- ============/各フォームバリデーション -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8h2_mZaHWJY_xIrOyju--6NjQbuH34jQ"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/map.js" type="text/javascript"></script>
	<?php if ( is_home() || is_front_page() || is_page_template( 'page-showroom.php' ) || is_page( '24859' ) ) : ?>
	<script src="<?php echo get_template_directory_uri(); ?>/js/slick.js" type="text/javascript"></script>
	<script>
		$( function () {
			$( '.main_v .slide' ).slick( {
				dots: true,
				infinite: true,
				arrows: false,
				speed: 500,
				slidesToShow: 1,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 6000,
				speed: 1500,
			} );
			$( '.t_parts .list' ).slick( {
				dots: false,
				infinite: true,
				arrows: true,
				speed: 500,
				slidesToShow: 5,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 2000,
			} );
			$( '.gal-slide' ).slick( {
				infinite: true,
				arrows: false,
				speed: 500,
				slidesToShow: 3,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 2000,
			} );
			$( '.t_modelhouse .list2' ).slick( {
				dots: false,
				infinite: true,
				arrows: true,
				speed: 500,
				slidesToShow: 3,
				slidesToScroll: 1,
				autoplay: true,
				autoplaySpeed: 2000,
			} );
		} );
	</script>
	<?php endif; ?>
	<?php if(is_page('19095')): ?>
<!-- 	ネット予約 -->
	<script>
		jQuery(function($) {
		$("#set_button1").click( function(){
	  // value値が2のデータを選択
	  $("#select_tenpo").val("本社");
	});
		$("#set_button2").click( function(){
	  // value値が2のデータを選択
	  $("#select_tenpo").val("大阪支店（豊中店）");
	});
		$("#set_button3").click( function(){
	  // value値が2のデータを選択
	  $("#select_tenpo").val("大阪支店（花博住宅展示場）");
	});
<?php
	$args = array(
		'post_type' => 'modelhouse', /* 投稿タイプ */
		'order' => 'ASC',
		'posts_per_page' => -1 /* 件数表示 */
	);
	query_posts( $args );
	$i = 4;
	if ( have_posts() ): while ( have_posts() ): the_post();
	?>
		$("#set_button<? echo $i;?>").click( function(){
	  // value値が2のデータを選択
	  $("#select_tenpo").val("<? the_title(); ?>");
	});

<?php
	$i ++;
	endwhile;?>
	  });
	</script>
<? endif;wp_reset_query();endif;?>


	<?php wp_head(); ?>
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TBDG3JX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<header id="head">
		<div class="inner base-inner">
			<h1 class="head_logo"><span class="inner"><a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_template_directory_uri(); ?>/images/head/logo.svg" alt="Reli" width="104" height="66"></a><span class="produce"><img src="<?php echo get_template_directory_uri(); ?>/images/head/logo_produce.svg" alt="PRODUCE byALLAGI株式会社" width="94" height="23"></span></span></h1>
			<div class="head_contact">
				<p class="tel_area"><span class="tel font-gen-md">050-7542-4893</span><span class="cominfo font-gen-r">受付時間　9:00〜19:00　水曜定休</span>
				</p>
				<ul class="form_btn_area">
					<li><a href="<?php bloginfo('url'); ?>/contact" class="btn_contact"><img src="<?php echo get_template_directory_uri(); ?>/images/common/cmn_btn_contact.svg" alt="問い合わせ" width="130"></a>
					</li>
					<li><a href="<?php bloginfo('url'); ?>/net_yoyaku" class="btn_raiten"><img src="<?php echo get_template_directory_uri(); ?>/images/common/cmn_btn_raiten.svg" alt="来場予約" width="130"></a>
					</li>
				</ul>
			</div>
		</div>
		<nav class="head_nav">
			<ul class="globalnavi base-inner clearfix">
				<li><a href="<?php bloginfo('url'); ?>/reli"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_about_off.png" width="134" height="50"></a>
				</li>
				<li><a href="<?php bloginfo('url'); ?>/net_yoyaku#raijyo_kibo"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_model_off.png" alt="モデルハウス" width="134" height="50"></a>
				<ul>
					<li><a href="<?php bloginfo('url'); ?>/showroom_hon">本社</a></li>
					<li><a href="<?php bloginfo('url'); ?>/showroom_osaka">大阪支店（豊中店）</a></li>
					<li><a href="<?php bloginfo('url'); ?>/showroom_higashiosaka">大阪支店（花博住宅展示場）</a></li>
			<?php
			$args = array(
				'post_type' => 'modelhouse', /* 投稿タイプ */
				'order' => 'ASC',
				'posts_per_page' => -1 /* 件数表示 */
			);
			query_posts( $args );
			if ( have_posts() ): while ( have_posts() ): the_post();
			?>
			<li><a href="<?php the_permalink(); ?>"><?php echo get_the_title();?></a></li><?php endwhile;endif;wp_reset_query();?>
				</ul>
			  </li>
				<li><a href="<?php bloginfo('url'); ?>/event"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_event_off.png" alt="イベント" width="134" height="50"></a>
				</li>
				<li><a href="<?php bloginfo('url'); ?>/seko"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_works_off.png" alt="施工事例" width="134" height="50"></a>
					<ul>
						<li><a href="<?php bloginfo('url'); ?>/ogata_seko">大型施工事例</a></li>
						<li><a href="<?php bloginfo('url'); ?>/seko">部分施工事例</a></li>
				  </ul>
				</li>
				<li><a href="<?php bloginfo('url'); ?>/column"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_column_off.png" alt="お役立ちコラム" width="134" height="50"></a>
				</li>
				<li><a href="<?php bloginfo('url'); ?>/blog"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_blog_off.png" alt="ブログ" width="134" height="50"></a>
					<ul>
						<li><a href="<?php bloginfo('url'); ?>/blog">スタッフブログ</a></li>
						<li><a href="https://www.taniue.jp/blog/taniue/" target="_blank">社長ブログ</a></li>
				  </ul>
				</li>
				<li><a href="<?php bloginfo('url'); ?>/company"><img src="<?php echo get_template_directory_uri(); ?>/images/head/gnav/gnav_company_off.png" alt="会社概要ト" width="134" height="50"></a>
				</li>
			</ul>
		</nav>
	</header>
