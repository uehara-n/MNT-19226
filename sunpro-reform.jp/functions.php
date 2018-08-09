<?php
add_action( 'add_admin_bar_menus', 'gr_add_admin_bar_menus' );
function gr_add_admin_bar_menus() {
/*
	remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_menu', 0 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );
*/
	// Site related.
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_my_sites_menu', 20 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_site_menu', 30 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 40 );
	add_action( 'admin_bar_menu', 'gr_admin_bar_wp_menu', 10 );

	// Content related.
	if ( ! is_network_admin() && ! is_user_admin() ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
	}
	remove_action( 'admin_bar_menu', 'wp_admin_bar_edit_menu', 80 );

//	add_action( 'admin_bar_menu', 'wp_admin_bar_add_secondary_groups', 200 );
}
function gr_admin_bar_wp_menu( $wp_admin_bar ) {
	$wp_admin_bar->add_menu( array(
		'id'    => 'gr-logo',
		'title' => '<img class="gr-icon" src="'.get_stylesheet_directory_uri().'/images/gr-logo-t.png"/>',
		'href'  => 'http://www.gotta-ride.com',
		'meta'  => array(
			'title' => 'ゴッタライド',
		),
	) );
}
add_action( 'wp_head', 'gr_head' );
add_action( 'admin_head', 'gr_head' );
function gr_head() {
?>
<style type="text/css" media="screen">
#wpadminbar{background:#f5f5f5;border-bottom:1px solid #333;}
#wpadminbar .quicklinks a, #wpadminbar .quicklinks .ab-empty-item, #wpadminbar .shortlink-input, #wpadminbar { height: 40px; line-height: 40px; }
#wpadminbar #wp-admin-bar-gr-logo { background-color: #f5f5f5;}
#wpadminbar .gr-icon { vertical-align: middle; }
body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 40px;}
#wpadminbar .ab-top-secondary,
#wpadminbar .ab-top-menu > li:hover > .ab-item, #wpadminbar .ab-top-menu > li.hover > .ab-item, #wpadminbar .ab-top-menu > li > .ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus, #wpadminbar #wp-admin-bar-gr-logo a:hover{background-color:transparent;background-image:none;color:#333;}
#screen-meta-links{display:none;}
#wpadminbar .ab-sub-wrapper, #wpadminbar ul, #wpadminbar ul li {background:#F5F5F5;}
#wpadminbar .quicklinks .ab-top-secondary > li > a, #wpadminbar .quicklinks .ab-top-secondary > li > .ab-empty-item,
#wpadminbar .quicklinks .ab-top-secondary > li {border-left: 1px solid #f5f5f5;}
#wpadminbar * {color: #333;text-shadow: 0 1px 0 #fff;}
</style>
<?php
}
add_filter( 'admin_footer_text', '__return_false' );
add_filter( 'update_footer', '__return_false', 9999 );
add_action( 'admin_notices', 'gr_update_nag', 0 );
function gr_update_nag() {
	if ( ! current_user_can( 'administrator' ) ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}

// seko ページでは editor 非表示
add_action( 'admin_print_styles-post.php', 'bc_post_page_style' );
add_action( 'admin_print_styles-post-new.php', 'bc_post_page_style' );
function bc_post_page_style() {
	if ( in_array( $GLOBALS['current_screen']->post_type, array( 'seko', 'slide_img', 'leaflet','event' ,'voice','craftsman','staff','whatsnew','price','bastour' ,'banner','special','tenpo') ) ) :
?>
<style type="text/css">
#postdivrich{display:none;}
#<?php global $current_screen; var_dump( $current_screen) ?>{}
</style>
<?php
	endif;
}
// カスタムフィールド&カスタム投稿タイプの追加
function gr_register_terms( $terms, $taxonomy ) {
	foreach ( $terms as $key => $label ) {
		$keys = explode( '/', $key );
		if ( 1 < count( $keys ) ) {
			$key = $keys[1];
			$parent_id = get_term_by( 'slug', $keys[0], $taxonomy )->term_id;
		} else {
			$parent_id = 0;
		}
		if ( ! term_exists( $key, $taxonomy ) ) {
			wp_insert_term( $label, $taxonomy, array( 'slug' => $key, 'parent' => $parent_id ) );
		}
	}
}

add_action( 'init', 'bc_create_customs', 0 );
function bc_create_customs() {

	// 施工事例
    register_post_type( 'seko', array(
        'labels' => array(
            'name' => __( '施工事例' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 4,
        'supports' => array( 'title', 'editor' ),
    ) );

    register_taxonomy( 'seko_cat', 'seko', array(
         'label' => '施工事例カテゴリー',
         'hierarchical' => true,
    ) );

		$terms = array(
			'kitchen' => 'キッチン',
			'ohuro' => 'お風呂',
			'toilet' => 'トイレ',
			'senmen' => '洗面',
			'gaiheki' => '外壁',
			'yane' => '屋根',
			'exterior' => '外構・エクステリア・庭',
			'living' => 'リビング・内装',
			'zenmen' => '全面改装',
			'sizen' => '自然素材',
			'design' => 'デザインリフォーム',
			'other' => 'その他',
	);
	gr_register_terms( $terms, 'seko_cat' );

	register_taxonomy( 'seko_staff', 'seko', array(
			'label' => 'スタッフカテゴリー',
			'hierarchical' => true,
		 	'update_count_callback' => '_update_post_term_count',
		 	'singular_label' => 'スタッフカテゴリー',
		 	'public' => true,
		 	'show_ui' => true	) );


	// リフォームMenu
	register_post_type( 'reformmenu', array(
			'labels' => array(
		'name' => __( 'リフォームMenu' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 6,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'reformmenu_cat', 'reformmenu', array(
			 'label' => 'リフォームMenuカテゴリー',
	 'hierarchical' => true,
	) );
	$terms = array(
		'reform_kitchen' => 'キッチン',
		'reform_ohuro' => 'お風呂',
		'reform_toilet' => 'トイレ',
		'reform_j2w' => '和室から洋室',
		'reform_kabegami' => '壁紙クロス',
		'reform_gaiheki' => '外壁',
		'reform_yane' => '屋根',
		'reform_kyuto' => '給湯',
		'reform_taishin' => '耐震',
		'reform_yuka' => '床',
	);
	gr_register_terms( $terms, 'reformmenu_cat' );



	// 現場日記
	register_post_type( 'genbanikki', array(
			'labels' => array(
		'name' => __( '現場日記' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 10,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'genba_cat', 'genbanikki', array(
			 'label' => '現場日記カテゴリー',
				     'hierarchical' => true,
	) );



	// 新着情報
	register_post_type( 'whatsnew', array(
			'labels' => array(
		'name' => __( '新着情報' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 11,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'whatsnew_cat', 'whatsnew', array(
			 'label' => '新着情報カテゴリー',
		     'hierarchical' => true,
	) );

	// イベント
	register_post_type( 'event', array(
			'labels' => array(
		'name' => __( 'イベント情報' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 12,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'event_cat', 'event', array(
			 'label' => 'イベントカテゴリー',
		     'hierarchical' => true,
	) );

	// スタッフ
	register_post_type( 'staff', array(
			'labels' => array(
		'name' => __( 'スタッフ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 13,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'staff_cat', 'staff', array(
			 'label' => 'スタッフカテゴリー',
	) );


	// お客様の声
	register_post_type( 'voice', array(
			'labels' => array(
		'name' => __( 'お客様の声' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 15,
	'supports' => array( 'title', 'editor' ),
	) );


	// TOPテロップ(一言メッセージ)
	register_post_type( 'telop', array(
			'labels' => array(
		'name' => __( 'TOPテロップ' ),
		'singular_name' => __( 'TOPテロップ')
			),
			'public' => true,
			'menu_position' => 17,
	'supports' => array( 'title' ),
	) );

	// チラシ
	register_post_type( 'leaflet', array(
			'labels' => array(
		'name' => __( 'チラシ' ),
		'singular_name' => __( 'チラシ')
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 18,
	'supports' => array( 'title', 'editor' ),
	) );

	// よくあるご質問
	register_post_type( 'faq', array(
			'labels' => array(
		'name' => __( 'よくあるご質問' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 13,
	'supports' => array( 'title', 'editor' ),
	) );
    register_taxonomy( 'faq_cat', 'faq', array(
         'label' => 'よくあるご質問カテゴリー',
         'hierarchical' => true,
    ) );

		$terms = array(
			'faq_reform' => 'リフォーム全般',
			'faq_money' => 'お見積・費用・ローン',
			'faq_kitchen' => 'キッチン',
			'faq_bath' => 'お風呂',
			'faq_toilet' => 'トイレ',
			'faq_senmen' => '洗面',
			'faq_gaiheki' => '外壁',
			'faq_yane' => '屋根',
			'faq_exterior' => '外構・エクステリア・庭',
			'faq_genkan' => '玄関',
			'faq_roka' => '廊下',
			'faq_kaidan' => '階段',
			'faq_living' => 'リビング・内装',
			'faq_shizen' => '自然素材',
			'faq_bfree' => 'バリヤフリー',
			'faq_design' => 'デザインリフォーム',
			'faq_kominka' => '古民家再生',
			'faq_shincniku' => '新築／建替え（リフォーム）',
			'faq_renovation' => 'リノベーション',
			'faq_zochiku' => '増築',
			'faq_taishin' => '耐震',
			'faq_shiroari' => 'シロアリ',
	);

	// 現場見学バスツアー
/*
	register_post_type( 'bastour', array(
			'labels' => array(
		'name' => __( '現場見学バスツアー' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 10,
	'supports' => array( 'title', 'editor' ),
	) );
*/

	// スマホ用お知らせ
	register_post_type( 'spnews', array(
			'labels' => array(
		'name' => __( 'スマホ用お知らせ' ),
		'singular_name' => __( 'スマホ用お知らせ')
			),
			'public' => true,
			'menu_position' => 17,
	'supports' => array( 'title' ),
	) );

	// バナー
	register_post_type( 'banner', array(
			'labels' => array(
		'name' => __( 'バナー' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 18,
	'supports' => array( 'title', 'editor' ),
	) );

	// 大型事例
	register_post_type( 'special', array(
			'labels' => array(
		'name' => __( '大規模リフォーム' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 19,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'special_cat', 'special', array(
			'label' => 'スタッフカテゴリー',
			'hierarchical' => true,
		 	'update_count_callback' => '_update_post_term_count',
		 	'singular_label' => 'スタッフカテゴリー',
		 	'public' => true,
		 	'show_ui' => true	) );

	// 来店予約店舗
	register_post_type( 'tenpo', array(
			'labels' => array(
		'name' => __( '来店予約店舗' ),
		'singular_name' => __( '来店予約店舗')
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 21,
	'supports' => array( 'title', 'editor' ),
	) );

}

//// hooks
add_filter( 'wp_list_categories', 'gr_list_categories', 10, 2 );
function gr_list_categories( $output, $args ) {
	return preg_replace( '@</a>\s*\((\d+)\)@', ' ($1)</a>', $output );
}

add_action( 'pre_get_posts', 'gr_pre_get_posts' );
function gr_pre_get_posts( $query ) {
	if ( is_admin() ) {
		if ( in_array( $query->get( 'post_type' ), array( 'seko', 'staff' ) ) ) {
			$query->set( 'posts_per_page', -1 );
		}
		return;
	}
/*
	if ( is_post_type_archive() ) {
		if ( 'seko' == get_query_var( 'post_type' ) ) {
			$query->tax_query[] = array(
				'taxonomy' =>	'seko_cat',
				'term'     => 'kitchen',
				'field'    => 'slug',
			);
		}
	}
*/
}

function gr_adjacent_post_join( $join, $in_same_cat, $excluded_categories ) {
	if ( false && $in_same_cat ) {
		global $post, $wpdb;

		$taxonomy  = $post->post_type . '_cat';
		$terms     = implode( ',', wp_get_object_terms( $post->ID, $taxonomy, array('fields' => 'ids') ) );
		$join      = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
		$join     .= $wpdb->prepare( " AND tt.taxonomy = %s AND tt.term_id IN ($terms)", $taxonomy );
	}

	return $join;
}

//// functions
function gr_title() {
	global $page, $paged;

	wp_title( '|', true, 'right' );
	bloginfo( 'name' );

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && is_front_page() )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf(  '%sページ', max( $paged, $page ) );
}

function gr_description() {
	$desc = get_option( 'gr_description' );

	if ( is_front_page() || ! $desc ) {
		bloginfo( 'description' );
	} else {
		$title = str_replace( '|', '', wp_title( '|', false ) );
		echo str_replace( '%title%', $title, get_option( 'gr_description' ) );
	}
}

function gr_get_posts_count() {
	global $wp_query;
	return get_query_var( 'posts_per_page' ) ? $wp_query->found_posts : $wp_query->post_count;
}

function gr_get_pagename() {
	$pagename = '';

	if ( is_page() ) {
		/*
		$obj = get_queried_object();
		if ( 14 == $obj->post_parent )
			$pagename = 'business';
		else
		*/
			$pagename = get_query_var( 'pagename' );
	} elseif( ! $pagename = get_query_var( 'post_type' ) ) {
		//
	}

	return $pagename;
}

define( 'GR_IMAGES', get_stylesheet_directory_uri() . '/images/' );
function gr_img( $file, $echo = true ) {
	$img = esc_attr( GR_IMAGES . $file );

	if ( $echo )
		echo $img;
	else
		return $img;
}

function gr_get_post( $post_name ) {
	global $wpdb;
	$null = $_post = null;

	if ( ! $_post = wp_cache_get( $post_name, 'posts' ) ) {
		$_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_name = %s LIMIT 1", $post_name ) );
		if ( ! $_post )
			return $null;
		_get_post_ancestors($_post);
		$_post = sanitize_post( $_post, 'raw' );
		wp_cache_add( $post_name, $_post, 'posts' );
	}

	return $_post;
}

function gr_get_permalink( $name, $taxonomy = '' ) {
	$link = false;

	if ( false && term_exists( $name, $taxnomy ) ) {
		$link = get_term_link( $name );
	} else if ( post_type_exists( $name ) ) {
		$link = get_post_type_archive_link( $name );
	} else {
		$_post = gr_get_post( $name );
		if ( $_post )
			$link = get_permalink( $_post );
	}

	return $link;
}

function gr_image_id( $key ) {
    $imagefield = post_custom( $key );
    return  preg_replace('/(\[)([0-9]+)(\])(http.+)?/', '$2', $imagefield );
}

function gr_get_image( $key, $att = '' ) {
	$id = gr_image_id( $key );

	if ( is_numeric( $id ) ) {
		if ( isset( $att['size'] ) ) {
			$size = $att['size'];
			unset( $att['size'] );
		}
		if ( isset( $att['width'] ) ) {
			$size = array( $att['width'], 99999 );
			unset( $att['width'] );
		}
		return wp_get_attachment_image( $id, $size, false, $att );
	}

	if ( $id ) {
		/* ファイル存在チェック
		 * $id = /images/seko/289-2-t.jpg のようなパスでここに渡ってくるので
		 * get_stylesheet_directory_uri()のようなhttpで絶対パスを指定せず
		 * dirname(__FILE__)でチェック
		 */
		if( file_exists( dirname(__FILE__) . "$id" ) ) {
			return sprintf(
				'<img src="%1$s%2$s"%3$s%4$s%5$s />',
				get_stylesheet_directory_uri(),
				$id,
				( $att['width' ] ? ' width="' .$att['width' ].'"' : '' ),
				( $att['height'] ? ' height="'.$att['height'].'"' : '' ),
				( $att['alt'   ] ? ' alt="'   .$att['alt'   ].'"' : '' )
			);
		}
	}

	return '';
}
function gr_get_image_src( $key ) {
	$id = gr_image_id( $key );
	$src = '';

	if ( is_numeric( $id ) ) {
		@list( $src, $width, $height ) = wp_get_attachment_image_src( $id, $size, false );
	} else if ( $id ) {
		$src = get_stylesheet_directory_uri() . $id;
	}
	return $src;
}
function sunpro_url() {
?>https://www.sunpro-reform.jp<?php }
function gr_contacts_banner() {
?>
	<!-- ======================問合わせテーブルここから======================= -->
		<div class="s_contact">
			<div class="box">
				<div class="btn">
					<p><a href="<?php bloginfo('url'); ?>/contact"><img src="<?php bloginfo('template_url'); ?>/images/kaiyu/toi_btn3.png" alt="お問い合わせ見積り依頼" width="300" height="72" class="over" /></a></p>
                    <p><a href="<?php bloginfo('url'); ?>/book"><img src="<?php bloginfo('template_url'); ?>/images/kaiyu/shiryou_btn3.png" alt="資料請求" width="300" height="72" class="over" /></a></p>
				</div>
			</div>
		</div>
	<!-- ======================問合わせテーブルここまで======================= -->

<?php
}
function gr_contact_banner() {
?>
	<!-- ======================問合わせテーブルここから======================= -->
	<br clear="all">
		<div class="contact_bnr">
        	<div class="bnr_nagare">
           		<a href="<?php bloginfo('url'); ?>/reformnagare"><img src="<?php bloginfo('template_url'); ?>/images/kaiyu/kaiyu_nagare_btn2.png" alt="リフォームの流れを写真付きで解説" width="628" height=133 class="over" /></a>
            </div>
			<div class="btn contact_toi">
				<a href="<?php bloginfo('url'); ?>/contact"><img src="<?php bloginfo('template_url'); ?>/images/kaiyu/toi_btn3.png" alt="お問い合わせ　見積り依頼" width="300" height="72" class="over" /></a><a href="<?php bloginfo('url'); ?>/book"><img src="<?php bloginfo('template_url'); ?>/images/kaiyu/shiryou_btn3.png" alt="資料請求" width="300" height="72" class="over" /></a>
			</div>
		</div>
	<!-- ======================問合わせテーブルここまで======================= -->

<?php
}
function gr_showroom() {
?>
	<!-- ======================ショールームここから======================= -->
			<div class="box2">
				<h3><img src="/wp-content/themes/reform/page_image/top/show/showroom1_t.gif" width="228" alt="サンプロスタイルスタジオ" /></h3>
				<img src="/wp-content/themes/reform/page_image/top/show/showroom1.jpg" width="228" alt="松本市　空間提案ショールーム雑貨ショップ" />
				<div class="more">
					<a href="https://www.home-arrange.jp/company/hon"><img src="/wp-content/themes/reform/page_image/top/show/showroom_more.jpg" alt="もっと見る" class="over" /></a>				</div>
			</div>
			<div class="box2">
				<h3><img src="/wp-content/themes/reform/page_image/top/show/showroom2_t.gif" width="228" alt="暮らしと不動産の窓口" /></h3>

					<img src="/wp-content/themes/reform/page_image/top/show/showroom2.jpg" alt="塩尻市　水廻りショールーム不動産情報" />

				<div class="more">
					<a href="https://www.home-arrange.jp/company/matsumoto"><img src="/wp-content/themes/reform/page_image/top/show/showroom_more.jpg" alt="もっと見る" class="over" /></a>				</div>
			</div>


			<div class="box2 r_0">
				<h3><img src="/wp-content/themes/reform/page_image/top/show/showroom3_t.gif" width="228" alt="サンプロスタイルスタジオ長野" /></h3>

					<img src="/wp-content/themes/reform/page_image/top/show/showroom3.jpg" alt="長野市　新築リフォームの空間提案スタジオ" />

				<div class="more">
					<a href="https://www.home-arrange.jp/company/nagano"><img src="/wp-content/themes/reform/page_image/top/show/showroom_more.jpg" alt="もっと見る" class="over" /></a>				</div>
			</div>


			<br clear="all" />

			<!-- ======================ショールームここまで======================= -->

<?php
}
function gr_kaiyu_banner() {
?>
    <!-- ======================回遊バナーここから======================= -->
    <ul class="kaiyu_area">
      <li> <a href="<?php bloginfo('url'); ?>/specialcraftman"><img src="/wp-content/themes/reform/images/kaiyu/kaiyu_01.png" alt="腕自慢の職人" width="329" height="234" class="over" /></a></li>
      <li><a href="<?php bloginfo('url'); ?>/company/specialist"><img src="/wp-content/themes/reform/images/kaiyu/kaiyu_02.png" alt="技術の高い建築士集団" width="329" height="234" class="over" /></a></li>
      <li><a href="<?php bloginfo('url'); ?>/jyusetsu"><img src="/wp-content/themes/reform/images/kaiyu/kaiyu_03.png" alt="住宅設備が安く買える" width="329" height="224" class="over" /></a></li>
      <li><a href="<?php bloginfo('url'); ?>/award"><img src="/wp-content/themes/reform/images/kaiyu/kaiyu_04.png" alt="表彰実績" width="330" height="224" class="over" /></a></li>
    </ul>
    <!-- ======================回遊バナーここまで======================= --> 

<?php
}


//スマホ用に付き、空タグ
function company_link() {
echo ' ';
}






if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
				'name' => 'sidebar',
				'before_widget' => '',
				'after_widget' => '</ul>',
				'before_title' => '<p class="pic">',
				'after_title' => '</p><ul class="page_left_menu">',
	) );
}

//// enqueue
add_action( 'wp_print_styles', 'gr_print_styles' );
function gr_print_styles() {
	if( ! is_admin() ) {
		if ( is_front_page() ) {
			wp_enqueue_style( 'gr_orbit'  , get_stylesheet_directory_uri() . '/common/css/orbit.css' );
		}
		wp_enqueue_style( 'gr_shadowbox', get_stylesheet_directory_uri() . '/common/css/shadowbox.css' );
		wp_enqueue_style( 'gr_common'   , get_stylesheet_directory_uri() . '/css/common.css' );
	}
}


//// admin

//add_action( 'admin_print_scripts-options-general.php', 'gr_options_general' );
add_action( 'admin_footer-options-general.php', 'gr_options_general' );
function gr_options_general() {
?>
<script type="text/javascript">
//<![CDATA[
(function($) {
	if($('body.options-general-php').length) {
		$('#blogdescription').parent().parent().before( $('#gr_companyname' ).parent().parent() );
		$('#blogdescription').parent().parent()
			.after( $('#gr_author' ).parent().parent() )
			.after( $('#gr_keywords' ).parent().parent() )
			.after( $('#gr_description' ).parent().parent() );
	}
})(jQuery);
//]]>
</script>
<?php
}

class GR_Admin {
	static private $options = NULL;

	public function GR_Admin() {
		$this->__construct;
	}

	public function __construct() {
		$this->options = array(
			array( 'id' => 'companyname', 'label' => '会社名'		     , 'desc' => '著作権表示用などに使用する会社名です。' ),
			array( 'id' => 'author'		, 'label' => '作成者'		     , 'desc' => 'サイトの作成者情報です。' ),
			array( 'id' => 'description', 'label' => 'ディスクリプション', 'desc' => '下層ページ用description' ),
			array( 'id' => 'keywords'	, 'label' => 'キーワード'	     , 'desc' => '半角コンマ（,）で区切って複数指定できます。' ),
		);
		add_action( 'admin_init'			, array( &$this, 'add_settings_fields' 		) );
		add_filter( 'whitelist_options'		, array( &$this, 'whitelist_options' 		) );
	}
	public function whitelist_options( $whitelist_options ) {
		foreach ( (array) $this->options as $option ) {
			$whitelist_options['general'][] = 'gr_' . $option['id'];
		}

		return $whitelist_options;
	}
	public function add_settings_fields() {
		foreach ( (array) $this->options as $key => $option ) {
			add_settings_field(
				$key+1, $option['label'], array( &$this, 'print_settings_field' ), 'general', 'default',
				array(
					'label_for' 	=> 'gr_' . $option['id'],
					'description' 	=> $option['desc'],
				)
			);
		}
	}
	public function print_settings_field( $args ) {
		printf(
			'<input name="%1$s" type="text" id="%1$s" value="%2$s" class="regular-text" />',
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) )
		);
		if ( ! empty( $args['description'] ) )
			printf(
				'<span class="description">%1$s</span>',
				esc_html( $args['description'] )
			);
	}
}

new GR_Admin;

/***************************************/

/**
 * 管理画面でのフォーカスハイライト
 */
function focus_highlight() {
	?>
		<style type="text/css">
		input:focus,textarea:focus{
			background-color: #dee;
		}
	</style>
		<?php
}

add_action( 'admin_head', 'focus_highlight' );

/**
 * 投稿での改行
 * [br] または [br num="x"] x は数字を入れる
 */
function sc_brs_func( $atts, $content = null ) {
	extract( shortcode_atts( array(
					'num' => '5',
					), $atts ));
	$out = "";
	for ($i=0;$i<$num;$i++) {
		$out .= "<br />";
	}
	return $out;
}

add_shortcode( 'br', 'sc_brs_func' );

//---------------------------------------------------------------------------
//\r\nの文字列の無効化
//---------------------------------------------------------------------------

add_filter('post_custom', 'fix_gallery_output');

function fix_gallery_output( $output ){
  $output = str_replace('rn', '', $output );
  return $output;
}


// echo fix_gallery_output(file_get_contents(__FILE__));

//---------------------------------------------------------------------------
//パンくず
//---------------------------------------------------------------------------

function the_pankuzu_keni( $separator = '　→　', $multiple_separator = '　|　' )
{
	global $wp_query;

	echo("<li><a href=\""); bloginfo('url'); echo("\">HOME</a>$separator</li>" );

	$queried_object = $wp_query->get_queried_object();

	if( is_page() )
	{
		//ページ
		if( $queried_object->post_parent )
		{
			echo( get_page_parents_keni( $queried_object->post_parent, $separator ) );
		}
		echo '<li>'; the_title(); echo '</li>';
	}
	else if( is_archive() )
	{
		if( is_post_type_archive() )
		{
			echo '<li>'; post_type_archive_title(); echo '</li>';
		}
		else if( is_category() )
		{
			//カテゴリアーカイブ
			if( $queried_object->category_parent )
			{
				echo get_category_parents( $queried_object->category_parent, 1, $separator );
			}
			echo '<li>'; single_cat_title(); echo '</li>';
		}
		else if( is_day() )
		{
			echo '<li>'; printf( __('Archive List for %s','keni'), get_the_time(__('F j, Y','keni'))); echo '</li>';
		}
		else if( is_month() )
		{
			echo '<li>'; printf( __('Archive List for %s','keni'), get_the_time(__('F Y','keni'))); echo '</li>';
		}
		else if( is_year() )
		{
			echo '<li>'; printf( __('Archive List for %s','keni'), get_the_time(__('Y','keni'))); echo '</li>';
		}
		else if( is_author() )
		{
			echo '<li>'; _e('Archive List for authors','keni'); echo '</li>';
		}
		else if(isset($_GET['paged']) && !empty($_GET['paged']))
		{
			echo '<li>'; _e('Archive List for blog','keni'); echo '</li>';
		}
		else if( is_tag() )
		{
			//タグ
			echo '<li>'; printf( __('Tag List for %s','keni'), single_tag_title('',0)); echo '</li>';
		}
	}
	else if( is_single() )
	{
		$obj = get_post_type_object( $queried_object->post_type );
		if ( $obj->has_archive ) {
			printf(
				'<li><a href="%1$s">%2$s</a>%3$s</li>',
				get_post_type_archive_link( $obj->name ),
				apply_filters( 'post_type_archive_title', $obj->labels->name ),
				$separator
			);
		} else {
			//シングル
			echo '<li>'; the_category_keni( $separator, 'multiple', false, $multiple_separator ); echo '</li>';
			echo( $separator );
		}
		echo '<li>'; the_title(); echo '</li>';
	}
	else if( is_search() )
	{
		//検索
		echo '<li>'; printf( __('Search Result for %s','keni'), strip_tags(get_query_var('s'))); echo '</li>';
	}
	else
	{
		$request_value = "";
		foreach( $_REQUEST as $request_key => $request_value ){
			if( $request_key == 'sitemap' ){ $request_value = $request_key; break; }
		}

		if( $request_value == 'sitemap' )
		{
			echo '<li>'; _e('Sitemap','keni'); echo '</li>';
		}
		else
		{
			echo '<li>'; the_title(); echo '</li>';
		}
	}
}

function get_page_parents_keni( $page, $separator )
{
	$pankuzu = "";

	$post = get_post( $page );

	$pankuzu = '<li><a href="'. get_permalink( $post ) .'">' . $post->post_title . '</a>' . $separator . '</li>';

	if( $post->post_parent )
	{
		$pankuzu = get_page_parents_keni( $post->post_parent, $separator ) . $pankuzu;
	}

	return $pankuzu;
}

function the_category_keni($separator = '', $parents='', $post_id = false, $multiple_separator = '/') {
	echo get_the_category_list_keni($separator, $parents, $post_id, $multiple_separator);
}

function get_the_category_list_keni($separator = '', $parents='', $post_id = false, $multiple_separator = '/')
{
	global $wp_rewrite;
	$categories = get_the_category($post_id);
	if (empty($categories))
		return apply_filters('the_category', __('Uncategorized', 'keni'), $separator, $parents);

	$rel = ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

	$thelist = '';
	if ( '' == $separator ) {
		$thelist .= '<ul class="post-categories">';
		foreach ( $categories as $category ) {
			$thelist .= "\n\t<li>";
			switch ( strtolower($parents) ) {
				case 'multiple':
					if ($category->parent)
						$thelist .= get_category_parents($category->parent, TRUE, $separator);
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->name.'</a></li>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>';
					if ($category->parent)
						$thelist .= get_category_parents($category->parent, FALSE);
					$thelist .= $category->name.'</a></li>';
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->cat_name.'</a></li>';
			}
		}
		$thelist .= '</ul>';
	} else {
		$i = 0;
		foreach ( $categories as $category ) {
			if ( 0 < $i )
				$thelist .= $multiple_separator . ' ';
			switch ( strtolower($parents) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents($category->parent, TRUE, $separator);
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->cat_name.'</a>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents($category->parent, FALSE);
					$thelist .= "$category->cat_name</a>";
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->name.'</a>';
			}
			++$i;
		}
	}
	return apply_filters('the_category', $thelist, $separator, $parents);
}
function get_specials(){
	include "specials.php";
}
function get_menutoi(){
	include "menutoi.php";
}
function get_menuohuro1(){
	include "menuohuro1.php";
}
function get_menuohuro2(){
	include "menuohuro2.php";
}

function get_menukitchen1(){
	include "menukitchen1.php";
}
function get_menukitchen2(){
	include "menukitchen2.php";
}

function get_menutoilet1(){
	include "menutoilet1.php";
}
function get_menutoilet2(){
	include "menutoilet2.php";
}

function get_menuyane1(){
	include "menuyane1.php";
}
function get_menuyane2(){
	include "menuyane2.php";
}

function get_menugaiheki1(){
	include "menugaiheki1.php";
}
function get_menugaiheki2(){
	include "menugaiheki2.php";
}

function get_menuyuka1(){
	include "menuyuka1.php";
}
function get_menuyuka2(){
	include "menuyuka2.php";
}

function get_menukabegami1(){
	include "menukabegami1.php";
}
function get_menukabegami2(){
	include "menukabegami2.php";
}

function get_menuj2w1(){
	include "menuj2w1.php";
}
function get_menuj2w2(){
	include "menuj2w2.php";
}

function get_menutaishin1(){
	include "menutaishin1.php";
}
function get_menutaishin2(){
	include "menutaishin2.php";
}

function get_menukyuto1(){
	include "menukyuto1.php";
}
function get_menukyuto2(){
	include "menukyuto2.php";
}

//ダッシュボードの記述▼

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'ゴッタライドからのお知らせ', 'dashboard_text');
}
function dashboard_text() {
echo '<iframe src="http://www.gotta-ride.com/cloud/news.html" height=200 width=100% scrolling=no>
この部分は iframe 対応のブラウザで見てください。
</iframe>';
}

function example_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
}
add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets');

//ダッシュボードの記述▲

//投稿画面から消す▼

function remove_post_metaboxes() {
    remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); // タグ
}
add_action('admin_menu', 'remove_post_metaboxes');

//投稿画面から消す▲ /ログイン時メニューバー消す▼

add_filter('show_admin_bar', '__return_false');

//ログイン時メニューバー消す▲　/アップデートのお知らせを管理者のみに　▼
if (!current_user_can('edit_users')) {
  function wphidenag() {
    remove_action( 'admin_notices', 'update_nag');
  }
  add_action('admin_menu','wphidenag');
}

//アップデートのお知らせ▲

/**
 *
 * 最新記事のIDを取得
 * @return  Int ID
 *
 */
function get_the_latest_ID() {
    global $wpdb;
    $row = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC");
    return !empty( $row ) ? $row->ID : '0';
}
function the_latest_ID() {
    echo get_the_latest_ID();
}

/*ＩＤ取得*/

function get_gaiyobar(){

echo <<<BNR

<div class="content_gaiyobt">
<h3><img src="/wp-content/themes/reform/page_image/gaiyo/tit_gaiyobt.gif" width="202" height="17" alt="ありがとうの家　会社概要" title="ありがとうの家　会社概要" /></h3>
<ul>
	<li><a href="/company/" title="会社案内"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo1_rollout.gif" width="222" height="52" alt="会社案内" /></a></li>
	<li><a href="/company/history/" title="創業物語"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo2_rollout.gif" width="222" height="52" alt="創業物語" /></a></li>
	<li><a href="/company/promise/" title="代表あいさつ"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo4_rollout.gif" width="222" height="52" alt="代表あいさつ" /></a></li>
	<li><a href="/staff/" title="スタッフ紹介"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo5_rollout.gif" width="222" height="52" alt="スタッフ紹介" /></a></li>
	<li><a href="/soudan/" title="よくあるご質問"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo6_rollout.gif" width="222" height="52" alt="よくあるご質問" /></a></li>
	<li><a href="/voice/" title="お客様の声"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo7_rollout.gif" width="222" height="52" alt="お客様の声" /></a></li>
	<li><a href="/event/" title="イベント情報"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo3_rollout.gif" width="222" height="52" alt="イベント情報" /></a></li>
<br clear="all"/>
</ul>
<br clear="all"/>
</div>
BNR;

}

//リフォームメニュー　一覧のURL取得処理
function getReformListUrl($cat,$post_id){
	$terms = get_the_terms($post_id,'soudan_cat');
	foreach($terms as $term){
		if($term->slug === $cat){
		  $link = get_term_link((int)$term->term_id,'soudan_cat'). '#' . $post_id;
		  break;
		}
	}
	return $link;
}

//メール投稿　誰でも投稿可能に
function ke_another_author($user_id, $address) {
    return koushin; // 投稿はすべてこのユーザーに固定（作っておくこと）
}
add_filter('ktai_validate_address', 'ke_another_author', 10, 2);

//現場日記
function get_the_post_image_src($postid,$size,$order=0,$max=null) {
    $attachments = get_children(array('post_parent' => $postid, 'post_type' => 'attachment', 'post_mime_type' => 'image'));
    if ( is_array($attachments) ){
        foreach ($attachments as $key => $row) {
            $mo[$key]  = $row->menu_order;
            $aid[$key] = $row->ID;
        }
        array_multisort($mo, SORT_ASC,$aid,SORT_DESC,$attachments);
        $max = empty($max)? $order+1 :$max;
        for($i=$order;$i<$max;$i++){
            return wp_get_attachment_image_src( $attachments[$i]->ID, $size );
        }
    }
}

function get_the_post_image_id($post_id,$size){
	$attachments = get_children(array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image','posts_per_page' => 1 ));
	if(is_array($attachments)){
	        foreach ($attachments as $attachments) {
	            $imgL = wp_get_attachment_image_src( $attachments->ID, 'large' );
	            echo '<p><a href="' . $imgL[0] . '" rel="lightbox[genba]" title="' . get_the_title() . '">' . wp_get_attachment_image( $attachments->ID, $size ) . '</a></p>';
	        }
	}
}

// Contact Form 7 にショートコードを追加
// function the_tenpo_info ($args) {
//   $template = dirname(__FILE__) . '/archive-tenpo.php';
//   if (!file_exists($template)) {
//     return;
//   }
//   $args = shortcode_atts($def, $args);
//   $posts = get_posts($args);
//   ob_start();
//   foreach ($posts as $post) {
//     $post_custom = get_post_custom($post->ID);
//     include($template);
//   }
//   $output = ob_get_clean();
//   return $output;
// }
// wpcf7_add_shortcode('tenpo_info', 'the_tenpo_info');

//archive-tenpo.php自体をショートコードへ
function the_tenpo_info () {
	$args = array(
		'post_type' => 'tenpo', 		/* 投稿タイプを指定 */
		'paged' => $paged,				/* ページ番号を指定 */
		'posts_per_page' => 6,			/* 最大表示数 */
	);
	$postslist = new WP_Query( $args );
	ob_start();
	if ( $postslist->have_posts() ) : while ( $postslist->have_posts() ) : $postslist->the_post(); ?>
	<div class="tenpo_box">
		<p class="tenpo_check">
			<input type="checkbox" name="place[]" value="<?php the_title(); ?>" id="<?php the_title(); ?>">
			<label for="<?php the_title(); ?>">
<?php if( post_custom( 'tenpo_checkbox' ) <>'' ){	//項目が空白でなかったら表示
	echo post_custom( 'tenpo_checkbox' ); }?>
			</label>
		</p>
		<p class="tenpo_photo"><?php printf( '%s', gr_get_image('tenpo_img')); ?></p>
<div class="tenpo_info">
<h4><?php the_title(); ?></h4>
		<p>
<?php if( post_custom( 'tenpo_address' ) <>'' ){	//項目が空白でなかったら表示
	echo '<span class="tenpo_add">住所</span>'.post_custom( 'tenpo_address' ) ;
}?>
		</p>
		<p>
<?php if( post_custom( 'tenpo_time' ) <>'' ){	//項目が空白でなかったら表示
	echo '<span class="tenpo_add">営業時間</span>'.post_custom( 'tenpo_time' ) ;
}?>
		</p>
		<p>
<?php if( post_custom( 'tenpo_teikyubi' ) <>'' ){	//項目が空白でなかったら表示
	echo '<span class="tenpo_add">定休日</span>'.post_custom( 'tenpo_teikyubi' ) ;
}?>
		</p>
</div>
	</div>
<? endwhile; endif; wp_reset_postdata();
	$output = ob_get_clean();
	return $output;
}
wpcf7_add_shortcode('tenpo_info', 'the_tenpo_info');

//contactform7プラグインに記事のカスタムフィールドの値を挿入
add_filter('wpcf7_special_mail_tags', 'my_special_mail_tags',10,2);
function my_special_mail_tags($output, $name)
{
    if ( ! isset( $_POST['_wpcf7_unit_tag'] ) || empty( $_POST['_wpcf7_unit_tag'] ) )
        return $output;
    if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $_POST['_wpcf7_unit_tag'], $matches ) )
        return $output;

    $post_id = (int) $matches[2];//開催日
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_date_check' == $name )
        $output = get_post_meta($post->ID,'event_date',true);

    $post_id = (int) $matches[2];//開催時間
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_time_check' == $name )
        $output = get_post_meta($post->ID,'event_time',true);

    $post_id = (int) $matches[2];//開催場所
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_place_check' == $name )
        $output = get_post_meta($post->ID,'event_place',true);

    $post_id = (int) $matches[2];//開催場所郵便番号
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_zip_check' == $name )
        $output = get_post_meta($post->ID,'event_zip',true);

    $post_id = (int) $matches[2];//開催場所住所
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_add_check' == $name )
        $output = get_post_meta($post->ID,'event_add',true);

    return $output;

    if ( ! isset( $_POST['_wpcf7_unit_tag'] ) || empty( $_POST['_wpcf7_unit_tag'] ) )
        return $output;
    if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $_POST['_wpcf7_unit_tag'], $matches ) )
        return $output;

}

//------------------------------------------------------------------------
//                   ショートコード
//------------------------------------------------------------------------


//URLショートコード

function shortcode_url() {
    return get_bloginfo('url');
}
add_shortcode('url', 'shortcode_url');

//テンプレートURL
function shortcode_templateurl() {
    return get_bloginfo('template_url');
}
add_shortcode('tmpl_url', 'shortcode_templateurl');


//sunpro-reform.jp URL

function shortcode_sunprourl() {
    return "http://www.sunpro-reform.jp/";
}
add_shortcode('sunpro_url', 'shortcode_sunprourl');

//------------------------------------------------------------------------
//                   施工事例関数化
//------------------------------------------------------------------------
function topSeko($id,$kensu){ ?>
<div class="seko_archive">
<div class="inner">
<? $i = 0;
$x = 0; ?>
<?php query_posts( array( 'seko_cat' => $id, 'posts_per_page' => $kensu )); ?>
<?php if(have_posts()) : while( have_posts() ) : the_post(); ?>
<div class="box">
<a href="<?php the_permalink(); ?>"> 
        <!--インポート用画像1 ここから--> 
        <span class="img"> <?php
global $post;
$Tokuchou = get_post_meta($post->ID,'seko_sekomachi');

if(in_array("施工待ち",$Tokuchou)):?><span class="stamp"><img src="<?php echo site_url(); ?>/wp-content/themes/gaiheki/images/common/icon_machi.png" width="78" height="78" alt="施工待ち" /></span><?php
						elseif(in_array("施工中",$Tokuchou)):?><span class="stamp"><img src="<?php echo site_url(); ?>/wp-content/themes/gaiheki/images/common/icon_chu.png" width="78" height="78" alt="施工中" /></span><?php
						endif;

        if(post_custom('seko_img')){
	printf(
		'%s',
		gr_get_image(
			'seko_img',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
        else if(post_custom('seko_img2')){
	printf(
		'%s',
		gr_get_image(
			'seko_img2',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
        else if(post_custom('seko_img3')){
	printf(
		'%s',
		gr_get_image(
			'seko_img3',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}

        else if(post_custom('seko_after_image')){
	printf(
		'%s',
		gr_get_image(
			'seko_after_image',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
        else if(post_custom('seko_point_image01')){
	printf(
		'%s',
		gr_get_image(
			'seko_point_image01',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
else if($img = post_custom('seko_csv01')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv2')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv3')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv4')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv5')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
	?> </span> <span class="txt"><? if(post_custom('seko_newicon')){
if( post_custom('seko_newicon') ){
 	echo '<span class="new"><img src="/wp-content/themes/reform/page_image/top/seko/icon_new.gif" width="33" height="13" alt="new" /></span>';
}} ?>
<?php echo post_custom( 'seko_city' ); ?> <?php echo post_custom( 'seko_name' ); ?><br />
        <span class="tit"><? the_title(); ?></span> 工期：<span class="koki"><?php echo post_custom( 'seko_duration' ); ?></span>　費用：<span class="hiyo"><?php echo post_custom( 'seko_price' ); ?></span><? if($text = post_custom('seko_content')){ ?><br/ >工事内容:<span class="naiyo"><? echo $text;?></span> <? } ?> </span> <span class="more"> <img src="<?php bloginfo('template_url'); ?>/page_image/top/seko/seko_more.gif" alt="詳しく見る" width="206" height="30" class="over" /> </span> </a>
      </div><?php
$i++;
endwhile; ?>
<?php else : ?>
<p>現在登録されておりません。</p>
<?php endif; ?>
<?php wp_reset_query(); ?>
</div></div>
<? } 



//------------------------------------------------------------------------
//                   階層が一番上のページスラッグ名を取得
//------------------------------------------------------------------------
function ps_get_root_page( $cur_post, $cnt = 0 ) {
    if ( $cnt > 100 ) { return false; }
    $cnt++;
    if ( $cur_post->post_parent == 0 ) {
        $root_page = $cur_post;
    } else {
        $root_page = ps_get_root_page( get_post( $cur_post->post_parent ), $cnt );
    }
    return $root_page;
}
//------------------------------------------------------------------------
//　　　　　　　　　　　施工事例ショートコード
//------------------------------------------------------------------------
function seko_archive($atts){
	$paged = get_query_var('paged') ? get_query_var('paged') : 1;
	extract(shortcode_atts(array(
        'seko_cat' => $seko_cat,
        'staff_id' => $staff_id,
        'kensu' => $kensu,
        'hukusu' => $hukusu,
    ), $atts));
    if($hukusu == 'yes'){
$args = array(
	'post_type' => 'seko', /* 投稿タイプ */
	'seko_cat' => array($seko_cat),
	'seko_staff' => $staff_id,
	'paged' => $paged,
	'posts_per_page' => $kensu /* 件数表示 */
);
    }else{
if(is_singular( 'staff' ) ){ 
$staff_id = get_the_id();
}
if(is_tax('seko_cat') ){
$term = get_term_by( 'slug', get_query_var( 'term' ), 'seko_cat' );
$term_slug = $term->slug;
$seko_cat = $term_slug;
}
$args = array(
	'post_type' => 'seko', /* 投稿タイプ */
	'seko_cat' => $seko_cat,
	'seko_staff' => $staff_id,
	'paged' => $paged,
	'posts_per_page' => $kensu /* 件数表示 */
);
}
$postslist = new WP_Query( $args );
	ob_start();
//-----------------------------表示される部分はここから
?>
<div class="seko_archive">
	<div class="inner">
		<?php if ( $postslist->have_posts() ) : while ( $postslist->have_posts() ) : 	$postslist->the_post(); 
		?>

		<div class="box">
        <a href="<?php the_permalink(); ?>"> 
        <!--インポート用画像1 ここから--> 
        <span class="img"> <?php
global $post;
$Tokuchou = get_post_meta($post->ID,'seko_sekomachi');

if(in_array("施工待ち",$Tokuchou)):?><span class="stamp"><img src="<?php echo site_url(); ?>/wp-content/themes/gaiheki/images/common/icon_machi.png" width="78" height="78" alt="施工待ち" /></span><?php
						elseif(in_array("施工中",$Tokuchou)):?><span class="stamp"><img src="<?php echo site_url(); ?>/wp-content/themes/gaiheki/images/common/icon_chu.png" width="78" height="78" alt="施工中" /></span><?php
						endif;

        if(post_custom('seko_img')){
	printf(
		'%s',
		gr_get_image(
			'seko_img',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
        else if(post_custom('seko_img2')){
	printf(
		'%s',
		gr_get_image(
			'seko_img2',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
        else if(post_custom('seko_img3')){
	printf(
		'%s',
		gr_get_image(
			'seko_img3',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}

        else if(post_custom('seko_after_image')){
	printf(
		'%s',
		gr_get_image(
			'seko_after_image',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
        else if(post_custom('seko_point_image01')){
	printf(
		'%s',
		gr_get_image(
			'seko_point_image01',
			array( 'width' => 230, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
else if($img = post_custom('seko_csv01')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv2')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv3')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv4')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
else if($img = post_custom('seko_csv5')){
echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="" />';
}
	?> </span> <span class="txt"><? if(post_custom('seko_newicon')){
if( post_custom('seko_newicon') ){
 	echo '<span class="new"><img src="/wp-content/themes/reform/page_image/top/seko/icon_new.gif" width="33" height="13" alt="new" /></span>';
}} ?>
<?php echo post_custom( 'seko_city' ); ?> <?php echo post_custom( 'seko_name' ); ?><br />
        <span class="tit"><? the_title(); ?></span> 工期：<span class="koki"><?php echo post_custom( 'seko_duration' ); ?></span>　費用：<span class="hiyo"><?php echo post_custom( 'seko_price' ); ?></span><? if($text = post_custom('seko_content')){ ?><br/ >工事内容:<span class="naiyo"><? echo $text;?></span> <? } ?> </span> <span class="more"> <img src="<?php bloginfo('template_url'); ?>/page_image/top/seko/seko_more.gif" alt="詳しく見る" width="206" height="30" class="over" /> </span> </a>
      </div>

				<?php $i++;endwhile;
					else: ?>
					
<p class="no_data">現在登録されておりません。</p>
<?php endif;
	wp_reset_postdata(); ?>
		</div>
</div>
<?
//-----------------------------表示される部分はここまで
	$output = ob_get_clean();
	return $output;
} 
add_shortcode('seko_archive', 'seko_archive');


//---------------------------------来店予約
function my_form_tag_filter($tag) {
    if (!is_array($tag))
    return $tag;

    //今日の日付を取得
    $today_y = date('Y');
    $today_m = date('n');
    $today_d = date('j');
     //今年をdefaultの数字に置き換え
     $default = $today_y - 2016;

    //取得した今日の日付をデフォルト値としてセット
    $name = $tag['name'];
    if ($name == 'm1kibou') {
        $tag['options'][0] = 'default:'.$today_m;
    }
    if ($name == 'm2kibou') {
        $tag['options'][0] = 'default:'.$today_m;
    }
    if ($name == 'm3kibou') {
        $tag['options'][0] = 'default:'.$today_m;
    }
    if ($name == 'd1kibou') {
        $tag['options'][0] = 'default:'.$today_d;
    }
    if ($name == 'd2kibou') {
        $tag['options'][0] = 'default:'.$today_d;
    }
    if ($name == 'd3kibou') {
        $tag['options'][0] = 'default:'.$today_d;
    }
     if ($name == 'kibou_day1_year') {
        $tag['options'][0] = 'default:'.$default;
    }
     if ($name == 'kibou_day2_year') {
        $tag['options'][0] = 'default:'.$default;
    }
     if ($name == 'kibou_day3_year') {
        $tag['options'][0] = 'default:'.$default;
    }
   return $tag;
}
add_filter('wpcf7_form_tag', 'my_form_tag_filter', 11);

//2018/06/26
//show newicon status in list and Edable in quickEdit panel
require get_template_directory() . '/admin/page-quick-edit.php';