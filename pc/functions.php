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

// 記事の自動整形を無効にする
remove_filter('the_content', 'wpautop');

// 固定ページ以外は自動整形を復活させる
if ( ! function_exists( 're_wpautop' ) ) {
    add_action('wp', 're_wpautop');
    function re_wpautop() {
        if(!is_page()) add_filter('the_content', 'wpautop');
    }
}


// seko ページでは editor 非表示
add_action( 'admin_print_styles-post.php', 'bc_post_page_style' );
add_action( 'admin_print_styles-post-new.php', 'bc_post_page_style' );
function bc_post_page_style() {
	if ( in_array( $GLOBALS['current_screen']->post_type, array( 'seko', 'slide_img', 'leaflet','event' ,'voice','craftsman','staff','whatsnew','price','slide' ) ) ) :
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
         'label' => '施工部位',
         'hierarchical' => true,
    ) );

	register_taxonomy( 'seko_staff', 'seko', array(
		'label' => 'スタッフカテゴリー',
         	'hierarchical' => true,
	) );
	register_taxonomy( 'seko_price_cat', 'seko', array(
			 'label' => '施工金額',
		     'hierarchical' => true,
	) );
	register_taxonomy( 'seko_syubetsu_cat', 'seko', array(
			 'label' => '建物種別',
		     'hierarchical' => true,
	) );
	// 大型施工事例＆お客さまの声
	register_post_type( 'ogata_seko', array(
			'labels' => array(
		'name' => __( '大型施工事例＆お客さまの声' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 11,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'ogata_seko_staff_cat', 'ogata_seko', array(
			 'label' => 'スタッフカテゴリー',
		     'hierarchical' => true,
	) );
	register_taxonomy( 'ogata_seko_price_cat', 'ogata_seko', array(
			 'label' => '施工金額',
		     'hierarchical' => true,
	) );
	register_taxonomy( 'ogata_seko_syubetsu_cat', 'ogata_seko', array(
			 'label' => '建物種別',
		     'hierarchical' => true,
	) );
	// コラム
	register_post_type( 'column', array(
			'labels' => array(
		'name' => __( 'コラム' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 11,
	'supports' => array( 'title', 'editor' ),
	) );

	// お知らせ
	register_post_type( 'whatsnew', array(
			'labels' => array(
		'name' => __( 'お知らせ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 11,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'whatsnew_cat', 'whatsnew', array(
			 'label' => 'お知らせカテゴリー',
		     'hierarchical' => true,
	) );

	// イベント
	register_post_type( 'event', array(
			'labels' => array(
		'name' => __( 'イベント' ),
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
			 'label' => 'スタッフブログ連動',
		     'hierarchical' => true,
	) );

	// スタッフブログ
	register_post_type( 'blog', array(
			'labels' => array(
		'name' => __( 'スタッフブログ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 19,
	'supports' => array( 'title', 'editor','comments' ),
	) );
	register_taxonomy( 'blog_cat', 'blog', array(
			 'label' => 'ブログカテゴリー',
		     'hierarchical' => true,
	) );
	// モデルハウス
	register_post_type( 'modelhouse', array(
			'labels' => array(
		'name' => __( 'モデルハウス' ),
		'enter_title_here' => __( 'ここにモデルハウス名を入れる', 'modelhouse' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 19,
	'supports' => array( 'title', 'editor','comments' ),
	) );

	// メインスライド
	register_post_type( 'slide', array(
			'labels' => array(
		'name' => __( 'メインスライド' ),
		'singular_name' => __( 'メインスライド')
			),
			'public' => true,
			'menu_position' => 9,
        'supports' => array( 'title', 'editor' ),
	) );

	// 新着情報
	register_post_type('whatsnew', array(
			'labels' => array(
				'name' => ('新着情報'),
				'singular_name' => __( '新着情報')
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 5,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'whatsnew_cat', 'whatsnew', array(
			 'label' => '新着情報カテゴリー',
		     'hierarchical' => true,
	) );

}





add_filter( 'enter_title_here', 'custom_enter_title_here', 10, 2 );
function custom_enter_title_here( $enter_title_here, $post ) {
    $post_type = get_post_type_object( $post->post_type );
    if ( isset( $post_type->labels->enter_title_here ) && $post_type->labels->enter_title_here && is_string( $post_type->labels->enter_title_here ) ) {
        $enter_title_here = esc_html( $post_type->labels->enter_title_here );
    }
    return $enter_title_here;
}

//// hooks
add_filter( 'wp_list_categories', 'gr_list_categories', 10, 2 );
function gr_list_categories( $output, $args ) {
	return preg_replace( '@</a>\s*\((\d+)\)@', ' ($1)</a>', $output );
}
add_filter( 'get_archives_link', 'my_archives_link' );
function my_archives_link( $output ) {
  $output = preg_replace('/<\/a>\s*(&nbsp;)\((\d+)\)/',' ($2)</a>',$output);
  return $output;
}
add_action( 'pre_get_posts', 'gr_pre_get_posts' );
function gr_pre_get_posts( $query ) {
	if ( is_admin() ) {
		if ( in_array( $query->get( 'post_type' ), array( 'staff' ) ) ) {
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

function gr_kaiyu_banner() {
?>
<!-- ======================回遊バナーここから======================= -->
<div class="kaiyu">
	<div class="reform">
		<h2><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_ttl_reform.png" alt="本格的なリフォーム・増改築をお考えの方へ" width="710" height="76" /></h2>
		<ul class="clearfix">
			<li class="rfm05"><a href="<?php echo site_url(); ?>/renov"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_reform05.jpg" alt="中古住宅を買ってリフォーム＆レノベーション！！" width="330" height="99" class="img_over" /></a></li>
			<li class="rfm06"><a href="<?php echo site_url(); ?>/designnaiso"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_reform06.jpg" alt="ワンランク上のデザイン内装リノベーション" width="330" height="99" class="img_over" /></a></li>
			<li class="rfm01"><a href="<?php echo site_url(); ?>/sodankai"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_reform01.png" alt="無料リフォーム相談会　予約受付中！" width="330" height="99" class="img_over" /></a></li>
			<li class="rfm02"><a href="<?php echo site_url(); ?>/merit"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_reform02.png" alt="新築 or リフォームでどっちがいいの？　それぞれのメリット・デメリット" width="330" height="99" /></a></li>
			<li class="rfm03"><a href="<?php echo site_url(); ?>/loan"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_reform03.png" alt="リフォームでローン？リフォームローンのメリット" width="330" height="99" /></a></li>
			<li class="rfm04"><a href="<?php echo site_url(); ?>/taishin"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_reform04.png" alt="あなたのお家は大丈夫ですか？耐震リフォーム無料耐震相談受付中" width="330" height="99" /></a></li>
		</ul>
	</div>
	<div class="company">
		<h2><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_ttl_company.png" alt="Reli（レリ）ってどんな会社？" width="710" height="76" /></h2>
		<div class="company_inner">
			<ul class="clearfix">
				<li class="cmpny01"><a href="<?php echo site_url(); ?>/company"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company01.png" alt="会社案内（About us）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny02"><a href="<?php echo site_url(); ?>/staff"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company02.png" alt="スタッフ紹介（Staff）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny03"><a href="<?php echo site_url(); ?>/voice"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company03.png" alt="お客様の声（Message）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny04"><a href="<?php echo site_url(); ?>/reli"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company04.png" alt="Reliの特徴（Advantage）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny05"><a href="<?php echo site_url(); ?>/reformnagare"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company05.png" alt="リフォームの流れ（Flow）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny06"><a href="<?php echo site_url(); ?>/seko"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company06.png" alt="施工事例（Works）" width="210" height="100" class="img_over" /></a></li>

<li class="cmpny08"><a href="<?php echo site_url(); ?>/showroom"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/bnr_showroom.jpg" alt="本社ショールーム" width="320" height="135" border="0" class="img_over" /></a></li>
<li class="cmpny09"><a href="<?php echo site_url(); ?>/shop_osaka"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/bnr_shoposaka.jpg" alt="大阪支店" width="320" height="135" border="0" class="img_over" /></a></li>

			</ul>
		</div>
	</div>
</div>
<!-- ======================回遊バナーここまで======================= -->




<?php
}

function gr_kaiyu_banner2() {
?>
<!-- ======================回遊バナー2ここから======================= -->
<div class="kaiyu">

	<div class="company">
		<h2><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_ttl_company.png" alt="Reli（レリ）ってどんな会社？" width="710" height="76" /></h2>
		<div class="company_inner">
			<ul class="clearfix">
				<li class="cmpny01"><a href="<?php echo site_url(); ?>/company"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company01.png" alt="会社案内（About us）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny02"><a href="<?php echo site_url(); ?>/staff"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company02.png" alt="スタッフ紹介（Staff）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny03"><a href="<?php echo site_url(); ?>/voice"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company03.png" alt="お客様の声（Message）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny04"><a href="<?php echo site_url(); ?>/reli"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company04.png" alt="Reliの特徴（Advantage）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny05"><a href="<?php echo site_url(); ?>/reformnagare"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company05.png" alt="リフォームの流れ（Flow）" width="210" height="100" class="img_over" /></a></li>
				<li class="cmpny06"><a href="<?php echo site_url(); ?>/seko"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/kaiyu_bnr_company06.png" alt="施工事例（Works）" width="210" height="100" class="img_over" /></a></li>

<li class="cmpny08"><a href="<?php echo site_url(); ?>/showroom"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/bnr_showroom.jpg" alt="本社ショールーム" width="320" height="135" border="0" class="img_over" /></a></li>
<li class="cmpny09"><a href="<?php echo site_url(); ?>/shop_osaka"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/bnr_shoposaka.jpg" alt="大阪支店" width="320" height="135" border="0" class="img_over" /></a></li>
<li class="cmpny010"><a href="https://www.facebook.com/reli.taniue/" target="_blank"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/bnr_fb.jpg" alt="FaceBook" width="320" border="0" class="img_over" /></a></li>
<li class="cmpny011"><a href="https://www.instagram.com/reli_taniue/" target="_blank"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/bnr_insta.jpg" alt="インスタグラム" width="320" border="0" class="img_over" /></a></li>
			</ul>
		</div>
	</div>
</div>
<!-- ======================回遊バナー2ここまで======================= -->



<?php
}

function gr_contact_banner() {
?>
	<!-- ======================問合わせテーブルここから======================= -->
<div class="inquiry_box">
	<div class="btn">
	<a href="<?php echo site_url(); ?>/net_yoyaku/"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/btn_yoyaku_rollout.png" alt="来場予約でQUOカードプレゼント 来場予約" width="181" height="73" /></a>
	<a href="<?php echo site_url(); ?>/contact/"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/btn_contact_rollout.png" alt="お問い合わせ" width="175" height="63" /></a>
	</div>
</div>
   	<!-- ======================問合わせテーブルここまで======================= -->

<?php
}
 function gr_contact_banner2() {
?>
	<!-- ======================問合わせテーブルここから======================= -->
<div class="inquiry_box2">
	<div class="btn">
	<a href="<?php echo site_url(); ?>/net_yoyaku/"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/btn_yoyaku_rollout.png" alt="来場予約でQUOカードプレゼント 来場予約" width="181" height="73" /></a>
	<a href="<?php echo site_url(); ?>/contact/"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/btn_contact_rollout.png" alt="お問い合わせ" width="175" height="63" /></a>
	</div>
</div>
   	<!-- ======================問合わせテーブルここまで======================= -->


<?php
}
function gr_scontact_banner() {
?>
<!-- ======================お問い合せ　ここから======================= -->
<div class="inquiry_table3"><a href="/contact"><img src="<?php echo site_url(); ?>/wp-content/themes/reform/images/common/contact_new_bnr_rollout.png" alt="お問い合わせ・無料お見積もり" width="220" height="72" /></a></div>
<!-- ======================お問い合せ　ここまで======================= -->

<?php
}

function cmn_gotop(){?>
	<p class="cmn_go_top"><a href="<?php bloginfo('url'); ?>" class="more-btn3">TOPに戻る</a></p>
<?}

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
//カレンダー
function widget_customCalendar($args) {
	extract($args);
	echo $before_widget;
	echo get_calendar_custom(カスタム投稿名);
	echo $after_widget;
}


function get_calendar_custom($posttype,$initial = true) {
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

	$key = md5( $m . $monthnum . $year );
	if ( $cache = wp_cache_get( 'get_calendar_custom', 'calendar_custom' ) ) {
		if ( isset( $cache[ $key ] ) ) {
			echo $cache[ $key ];
			return;
		}
	}

	ob_start();
	// Quick check. If we have no posts at all, abort!
	if ( !$posts ) {
		$gotsome = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1");
		if ( !$gotsome )
			return;
	}

	if ( isset($_GET['w']) )
		$w = ''.intval($_GET['w']);

	// week_begins = 0 stands for Sunday
	$week_begins = intval(get_option('start_of_week'));

	// Let's figure out when we are
	if ( !empty($monthnum) && !empty($year) ) {
		$thismonth = ''.zeroise(intval($monthnum), 2);
		$thisyear = ''.intval($year);
	} elseif ( !empty($w) ) {
		// We need to get the month from MySQL
		$thisyear = ''.intval(substr($m, 0, 4));
		$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
		$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('${thisyear}0101', INTERVAL $d DAY) ), '%m')");
	} elseif ( !empty($m) ) {
		$thisyear = ''.intval(substr($m, 0, 4));
		if ( strlen($m) < 6 )
				$thismonth = '01';
		else
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
	} else {
		$thisyear = gmdate('Y', current_time('timestamp'));
		$thismonth = gmdate('m', current_time('timestamp'));
	}

	$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);

	// Get the next and previous month and year with at least one post
	$previous = $wpdb->get_row("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) AS year
		FROM $wpdb->posts
		LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)

		WHERE post_date < '$thisyear-$thismonth-01'

		AND post_type = '$posttype' AND post_status = 'publish'
			ORDER BY post_date DESC
			LIMIT 1");

	$next = $wpdb->get_row("SELECT	DISTINCT MONTH(post_date) AS month, YEAR(post_date) AS year
		FROM $wpdb->posts
		LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)

		WHERE post_date >	'$thisyear-$thismonth-01'

		AND MONTH( post_date ) != MONTH( '$thisyear-$thismonth-01' )
		AND post_type = '$posttype' AND post_status = 'publish'
			ORDER	BY post_date ASC
			LIMIT 1");

	echo '<div id="calendar_wrap">
	<table id="wp-calendar" summary="' . __('Calendar') . '">
	<caption>' . date('Y年n月', $unixmonth) . '</caption>
	<thead>
	<tr>';

	$myweek = array();

	for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
	}

	foreach ( $myweek as $wd ) {
		$day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
		echo "\n\t\t<th abbr=\"$wd\" scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	echo '
	</tr>
	</thead>

	<tfoot>
	<tr>';

	echo '
	</tr>
	</tfoot>
	<tbody>
	<tr>';

	// Get days with posts
	$dyp_sql = "SELECT DISTINCT DAYOFMONTH(post_date)
		FROM $wpdb->posts

		LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
		LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)

		WHERE MONTH(post_date) = '$thismonth'

		AND YEAR(post_date) = '$thisyear'
		AND post_type = '$posttype' AND post_status = 'publish'
		AND post_date < '" . current_time('mysql') . "'";

	$dayswithposts = $wpdb->get_results($dyp_sql, ARRAY_N);

	if ( $dayswithposts ) {
		foreach ( (array) $dayswithposts as $daywith ) {
			$daywithpost[] = $daywith[0];
		}
	} else {
		$daywithpost = array();
	}

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'camino') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'safari') !== false)
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$ak_titles_for_day = array();
	$ak_post_titles = $wpdb->get_results("SELECT post_title, DAYOFMONTH(post_date) as dom "
		."FROM $wpdb->posts "

		."LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id) "
		."LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) "

		."WHERE YEAR(post_date) = '$thisyear' "

		."AND MONTH(post_date) = '$thismonth' "
		."AND post_date < '".current_time('mysql')."' "
		."AND post_type = '$posttype' AND post_status = 'publish'"
	);
	if ( $ak_post_titles ) {
		foreach ( (array) $ak_post_titles as $ak_post_title ) {

				$post_title = apply_filters( "the_title", $ak_post_title->post_title );
				$post_title = str_replace('"', '&quot;', wptexturize( $post_title ));

				if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
					$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
		}
	}

	// See how much we should pad in the beginning
	$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
	if ( 0 != $pad )
		echo "\n\t\t".'<td colspan="'.$pad.'" class="pad">&nbsp;</td>';

	$daysinmonth = intval(date('t', $unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( isset($newrow) && $newrow )
			echo "\n\t</tr>\n\t<tr>\n\t\t";
		$newrow = false;

		if ( $day == gmdate('j', (time() + (get_option('gmt_offset') * 3600))) && $thismonth == gmdate('m', time()+(get_option('gmt_offset') * 3600)) && $thisyear == gmdate('Y', time()+(get_option('gmt_offset') * 3600)) )
			echo '<td id="today">';
		else
			echo '<td>';

		if ( in_array($day, $daywithpost) ) // any posts today?
				echo '<a href="' .  $home_url . '/' . $posttype .  '/date/' . $thisyear . '/' . $thismonth . '/' . $day . "\">$day</a>";
		else
			echo $day;
		echo '</td>';

		if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
	if ( $pad != 0 && $pad != 7 )
		echo "\n\t\t".'<td class="pad" colspan="'.$pad.'">&nbsp;</td>';

	echo "\n\t</tr>\n\t</tbody>\n\t</table></div>";

	echo "\n\t<div class=\"calender_navi\"><table cellspacing=\"0\" cellpadding=\"0\"><tr>";

	if ( $previous ) {
		echo "\n\t\t".'<td abbr="' . $wp_locale->get_month($previous->month) . '" colspan="3" id="prev"><a href="' .  $home_url . '/' . $posttype .  '/date/' . $previous->year . '/' . $previous->month . '" title="' . sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($previous->month),			date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
	} else {
		echo "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	echo "\n\t\t".'<td class="pad">&nbsp;</td>';

	if ( $next ) {
		echo "\n\t\t".'<td abbr="' . $wp_locale->get_month($next->month) . '" colspan="3" id="next"><a href="' .  $home_url . '/' . $posttype .  '/date/' . $next->year . '/' . $next->month . '" title="' . sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($next->month),			date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
	} else {
		echo "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}
	echo "\n\t</tr></table></div>";

	$output = ob_get_contents();
	ob_end_clean();
	echo $output;
	$cache[ $key ] = $output;
	wp_cache_set( 'get_calendar_custom', $cache, 'calendar_custom' );
}
//カレンダー


//コメント
function reform_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
echo <<<BUN
   <li
BUN;

comment_class();
echo ' id="li-comment-';
comment_ID();

echo <<<BUN
">
     <div id="comment-
BUN;
comment_ID();
echo <<<BUN
">
      <div class="comment-author vcard">

BUN;

echo get_avatar($comment,$size='48',$default='<path_to_url>' );
echo <<<BUN
<br />

BUN;

printf(__('%s'), get_comment_author_link());
echo <<<BUN
      </div>

BUN;

if ($comment->comment_approved == '0') :
echo <<<BUN
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />

BUN;

endif;
echo <<<BUN
      <div class="comment-meta commentmetadata"><a href="
BUN;

echo htmlspecialchars( get_comment_link( $comment->comment_ID ) );
echo '">';
printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
echo'</a>';
edit_comment_link(__('(Edit)'),'  ','');
echo <<<BUN
<br />

BUN;

comment_text();
echo <<<BUN
      <div class="comment-reply">
BUN;

comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
echo <<<BUN
</div>
     </div>
BUN;

        }
//コメント
//contactform7バリデーション
function theme_name_scripts() {
 wp_enqueue_style( 'validationEngine.jquery.css', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/validationEngine.jquery.min.css', array(), '1.0', 'all');
 wp_enqueue_script( 'jquery.validationEngine-ja.js', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/languages/jquery.validationEngine-ja.min.js', array('jquery'), '2.0.0', true );
 wp_enqueue_script( 'jquery.validationEngine.js', 'https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery.validationEngine.min.js', array('jquery'), '2.6.4', true );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );


//アクセス数の取得
function get_post_views( $postID ) {
	$count_key = 'post_views_count';
	$count = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '0' );
		return "0";
	}
	return $count . '';
}

//アクセス数の保存
function set_post_views( $postID ) {
	$count_key = 'post_views_count';
	$count = get_post_meta( $postID, $count_key, true );
	if ( $count == '' ) {
		$count = 0;
		delete_post_meta( $postID, $count_key );
		add_post_meta( $postID, $count_key, '0' );
	} else {
		$count ++;
		update_post_meta( $postID, $count_key, $count );
	}
}

//ボットのアクセスは拒否
function isBot() {
	$bot_list = array (
		'Googlebot',
		'Yahoo! Slurp',
		'Mediapartners-Google',
		'msnbot',
		'bingbot',
		'MJ12bot',
		'Ezooms',
		'pirst; MSIE 8.0;',
		'Google Web Preview',
		'ia_archiver',
		'Sogou web spider',
		'Googlebot-Mobile',
		'AhrefsBot',
		'YandexBot',
		'Purebot',
		'Baiduspider',
		'UnwindFetchor',
		'TweetmemeBot',
		'MetaURI',
		'PaperLiBot',
		'Showyoubot',
		'JS-Kit',
		'PostRank',
		'Crowsnest',
		'PycURL',
		'bitlybot',
		'Hatena',
		'facebookexternalhit',
		'NINJA bot',
		'YahooCacheSystem',
	);
	$is_bot = false;
	foreach ($bot_list as $bot) {
		if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
			$is_bot = true;
			break;
		}
	}
	return $is_bot;
}

//PCでのみ表示するコンテンツ
function if_is_pc($atts, $content = null )
{
$content = do_shortcode( $content);
    if(!wp_is_mobile())
        {
        return $content;
        }
}
add_shortcode('pc', 'if_is_pc');
//スマートフォン・タブレットでのみ表示するコンテンツ
function if_is_nopc($atts, $content = null )
{
$content = do_shortcode( $content);
    if(wp_is_mobile())
        {
        return $content;
        }
}
add_shortcode('nopc', 'if_is_nopc');



//来店予約
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
    if ($name == 'custom_m1') {
        $tag['options'][0] = 'default:'.$today_m;
    }
    if ($name == 'custom_m2') {
        $tag['options'][0] = 'default:'.$today_m;
    }
    if ($name == 'custom_d1') {
        $tag['options'][0] = 'default:'.$today_d;
    }
    if ($name == 'custom_d2') {
        $tag['options'][0] = 'default:'.$today_d;
    }
     if ($name == 'custom_y1') {
        $tag['options'][0] = 'default:'.$default;
    }
     if ($name == 'custom_y2') {
        $tag['options'][0] = 'default:'.$default;
    }
   return $tag;
}
add_filter('wpcf7_form_tag', 'my_form_tag_filter', 11);



//img srcの記述を拾って画像を取得し表示する
function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];

    if(empty($first_img)){ //Defines a default image
        $first_img = "/images/default.jpg";
    }
return $first_img;
}

/*API*/
function my_acf_init() {
  acf_update_setting('google_api_key', 'AIzaSyD8h2_mZaHWJY_xIrOyju--6NjQbuH34jQ');
}
add_action('acf/init', 'my_acf_init');

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
//                                ショートコード
//------------------------------------------------------------------------
//--------サイトURL
function shortcode_url() {
    return get_bloginfo('url');
}
add_shortcode('url', 'shortcode_url');
/* 投稿内で [url] と記述する */

//--------テンプレートURL
function shortcode_templateurl() {
//    return get_template_directory_uri();
	return get_template_directory_uri();
}
add_shortcode('tmpl_url', 'shortcode_templateurl');
/* 投稿内で [tmpl_url] と記述する */

//--------イベント日付
function kaisairepeat () {
	ob_start();
	$eventid = get_the_ID();
	if(have_rows('kaisairepeat')):
	while(have_rows('kaisairepeat')): the_row();
	$repeat_date = get_sub_field('kaisaidate');
	$week = array("日", "月", "火", "水", "木", "金", "土");
	$date = date_create(''.$repeat_date.'');
	echo date_format($date,'Y年m月d日') . "(" . $week[(int)date_format($date,'w')] . ")" ;
	endwhile;endif;
	$output = ob_get_clean();
	return $output;
}
add_shortcode('kaisairepeat', 'kaisairepeat');

//--------来店予約バナートップ
function raiten_bnr() {
	ob_start();?>
<p class="top_bnr bnr_campaign base-inner"><a href="<?php bloginfo('url'); ?>/net_yoyaku" class="img_hover"><img src="<?php echo get_template_directory_uri(); ?>/images/common/bnr_campaign.png" alt="ご来場予約キャンペーン" width="950" height="165"></a></p>

<?	$output = ob_get_clean();
	return $output;
}
add_shortcode('raiten_bnr', 'raiten_bnr');
/* 投稿内で [raiten_bnr] と記述する */

//--------来店予約バナー下層
function raiten_page_bnr() {
	ob_start();?>
<p class="cmn_bnr"><a href="<?php bloginfo('url'); ?>/net_yoyaku" class="img_hover"><img src="<?php echo get_template_directory_uri(); ?>/images/common/raiten_page_bnr.png" alt="ご来場予約キャンペーン" width="614" height="110"></a></p>

<?	$output = ob_get_clean();
	return $output;
}
add_shortcode('raiten_page_bnr', 'raiten_page_bnr');
/* 投稿内で [raiten_page_bnr] と記述する */

//お問い合わせバナー
function contact_bnr() {
	ob_start();?>
	    <div class="contact_bnr">
    </div>


<?	$output = ob_get_clean();
	return $output;
}
add_shortcode('contact_bnr', 'contact_bnr');
/* 投稿内で [contact_bnr] と記述する */

//回遊バナー
function kaiyu_bnr() {
	ob_start();?>
	<div class="kaiyu_bnr">ここに回遊バナーがはいるよ</div>

<?	$output = ob_get_clean();
	return $output;
}
add_shortcode('kaiyu_bnr', 'kaiyu_bnr');
/* 投稿内で [kaiyu_bnr] と記述する */



//modelhouse_archivesショートコードへ
function modelhouse_archives () {
	$args = array(
		'post_type' => 'modelhouse', 		/* 投稿タイプを指定 */
		'paged' => $paged,			/* ページ番号を指定 */
		'order' => 'ASC',
		'posts_per_page' => 6,			/* 最大表示数 */
	);
	$postslist = new WP_Query( $args );
	ob_start();?>
	<li>
	<p class="pic"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/raitenpic_hon.png" alt="本社" width="210" height="140"></p>
	<p class="tit">本社</p>
	<p class="btn_yoyaku"><a href="#form_raiten" id="set_button1"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/btn_yoyaku.png" alt="来店予約する" width="150" height="35"></a></p>
	<p class="more"><a href="<?php bloginfo('url'); ?>/showroom_hon">詳細を見る &gt;&gt;</a></p></li>
	<li>
	<p class="pic"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/raitenpic_osaka.png" alt="大阪支店（豊中店）" width="210" height="140"></p>
	<p class="tit">大阪支店（豊中店）</p>
	<p class="btn_yoyaku"><a href="#form_raiten" id="set_button2"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/btn_yoyaku.png" alt="来店予約する" width="150" height="35"></a></p>
	<p class="more"><a href="<?php bloginfo('url'); ?>/showroom_osaka">詳細を見る &gt;&gt;</a></p></li>
	<li>
	<p class="pic"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/raitenpic_hanahaku.png" alt="大阪支店（花博住宅展示場）" width="210" height="140"></p>
	<p class="tit">大阪支店（花博住宅展示場）</p>
	<p class="btn_yoyaku"><a href="#form_raiten" id="set_button3"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/btn_yoyaku.png" alt="来店予約する" width="150" height="35"></a></p>
	<p class="more"><a href="<?php bloginfo('url'); ?>/showroom_higashiosaka">詳細を見る &gt;&gt;</a></p></li>
	<?if ( $postslist->have_posts() ) :?>
	<?
		$i = 4;
		while ( $postslist->have_posts() ) : $postslist->the_post(); ?>
			<li>

				<p class="pic"><?php if(get_field( 'commingsoon' )){ //準備中にチェックがあったら
				if(get_field('top_comingsoon_pic')){
	printf(
		'%2$s',
		gr_get_image_src('top_comingsoon_pic'),
		gr_get_image(
			'top_comingsoon_pic',
			array( 'width' => 210, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);}elseif(get_field('top_img')){ //上記がなく、トップページ用の写真があったら
	printf(
		'%2$s',
		gr_get_image_src('top_img'),
		gr_get_image(
			'top_img',
			array( 'width' => 210, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}elseif(get_field('mainpic')){ //上記2点がなく、メイン写真があったら
	printf(
		'%2$s',
		gr_get_image_src('mainpic'),
		gr_get_image(
			'mainpic',
			array( 'width' => 210, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}
}elseif(get_field('top_img')){ //準備中にチェックがなかったら
	printf(
		'%2$s',
		gr_get_image_src('top_img'),
		gr_get_image(
			'top_img',
			array( 'width' => 210, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}elseif(get_field('mainpic')){
	printf(
		'%2$s',
		gr_get_image_src('mainpic'),
		gr_get_image(
			'mainpic',
			array( 'width' => 210, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
		)
	);
}?>
				</p>
				<p class="tit"><? the_title(); ?></p>
				<p class="btn_yoyaku"><a href="#form_raiten" id="set_button<?php echo  sprintf("%d", $i);?>"><img src="<?php echo get_template_directory_uri(); ?>/page_image/net_yoyaku/btn_yoyaku.png" alt="来店予約する" width="150" height="35"></a></p>
<p class="more"><a href="<?php the_permalink(); ?>">詳細を見る &gt;&gt;</a></p>
</li>
<? $i++;
	endwhile; endif; wp_reset_postdata();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('modelhouse_archives', 'modelhouse_archives');





//------------------------------------------------------------------------
//         contactform7プラグインに記事のカスタムフィールドの値を挿入
//------------------------------------------------------------------------

add_filter('wpcf7_special_mail_tags', 'my_special_mail_tags',10,2);
function my_special_mail_tags($output, $name)
{
    if ( ! isset( $_POST['_wpcf7_unit_tag'] ) || empty( $_POST['_wpcf7_unit_tag'] ) )
        return $output;
    if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $_POST['_wpcf7_unit_tag'], $matches ) )
        return $output;

    $post_id = (int) $matches[2];//開催時間
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_time_check' == $name )
        $output = get_post_meta($post->ID,'time',true);


    $post_id = (int) $matches[2];//会場
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_kaijyo_check' == $name )
        $output = get_post_meta($post->ID,'kaijyo',true);

    return $output;

    if ( ! isset( $_POST['_wpcf7_unit_tag'] ) || empty( $_POST['_wpcf7_unit_tag'] ) )
        return $output;
    if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $_POST['_wpcf7_unit_tag'], $matches ) )
        return $output;

}




//--------contactform7に入れる
function kaisairepeat_form () {
	ob_start();
	$eventid = get_the_ID();
	if(have_rows('kaisairepeat')):
	$x = 1;
	while(have_rows('kaisairepeat')): the_row();
	$repeat_date = get_sub_field('kaisaidate');
	$week = array("日", "月", "火", "水", "木", "金", "土");
	$date = date_create(''.$repeat_date.'');
	$date_format = date_format($date,'Y年m月d日');
	$week_format = $week[(int)date_format($date,'w')];
	echo '<label><input type="radio" name="date_check[]" value="'.$date_format.'('.$week_format.')"';
	if($x == 1){
		echo ' checked="checked"';
	}
	echo '>'.$date_format.'('.$week_format.')</label>';
	$x++;
	endwhile;endif;
	$output = ob_get_clean();
	return $output;
}
wpcf7_add_shortcode('kaisairepeat_form', 'kaisairepeat_form');

function kaisairepeat_form_mail () {
	ob_start();
	$eventid = get_the_ID();
	if(have_rows('kaisairepeat')):
	$x = 1;
	while(have_rows('kaisairepeat')): the_row();
	$repeat_date = get_sub_field('kaisaidate');
	$week = array("日", "月", "火", "水", "木", "金", "土");
	$date = date_create(''.$repeat_date.'');
	$date_format = date_format($date,'Y年m月d日');
	$week_format = $week[(int)date_format($date,'w')];
	echo '<input type="hidden" name="date_check_mail[]" value="'.$date_format.'('.$week_format.')">';
	endwhile;endif;
	$output = ob_get_clean();
	return $output;
}
wpcf7_add_shortcode('kaisairepeat_form_mail', 'kaisairepeat_form_mail');


function modelhouse_form_mail () {
    $args = array(
	'post_type' => 'modelhouse', /* 投稿タイプ */
	'paged' => $paged,
	'order' => 'ASC',
	'posts_per_page' => '-1' /* 件数表示 */
);
$postslist = new WP_Query( $args );
$slug_name = basename(get_permalink());
	ob_start();
//-----------------------------表示される部分はここから
?>
<select name="select_tenpo" class="select_tenpo wpcf7-validates-as-required" id="select_tenpo">
	<option value="本社"<?php if(is_page('14295') ){echo ' selected="selected"';}?>>本社</option>
	<option value="大阪支店（豊中店）"<?php if(is_page('15344') ){echo ' selected="selected"';}?>>大阪支店（豊中店）</option>
	<option value="大阪支店（花博住宅展示場）"<?php if(is_page('23313') ){echo ' selected="selected"';}?>>大阪支店（花博住宅展示場）</option>
	<?php
		$now_post = get_the_ID();
		if ( $postslist->have_posts() ) : while ( $postslist->have_posts() ) : $postslist->the_post();
		$post = get_page($page_id);
		$select_page_id = get_the_ID();
		$slug = $post->post_name;
	?>
	<option value="<? the_title()?>"<?php if($now_post == $select_page_id){?> selected="selected"<?}?>><? the_title() ?></option>
				<?php ;endwhile;
					endif;?>
</select>
					<? wp_reset_postdata();?>

<?	$output = ob_get_clean();
	return $output;
}
wpcf7_add_shortcode('modelhouse_form_mail', 'modelhouse_form_mail');



function contact_attention () {
	return '<p class="att_tit"><img src="'.get_template_directory_uri().'/images/form/att_tit.svg" width="110" alt="ご注意"></p><p class="att_text">メールアドレスを登録された方には、<span class="text-red">自動で確認メール</span>を送信しております。メールアドレスの誤入力、携帯電話のドメイン指定受信の設定などにより確認メールを受信できない場合がございます。迷惑メールなどに入っている場合もございますので、再度受信設定をご確認ください。<span class="text-red">確認メールが届かない場合</span>は、当社からの返信を受信できない可能性がございますので、お手数ですが、メール以外の連絡方法を追記の上、再度お問い合わせいただきますようお願い致します。</p>';
}
wpcf7_add_shortcode('contact_attention', 'contact_attention');

//記事番号
function get_post_number( $post_type = 'post', $op = '<=' ) {
    global $wpdb, $post;
    $post_type = is_array($post_type) ? implode("','", $post_type) : $post_type;
    $number = $wpdb->get_var("
        SELECT COUNT( * )
        FROM $wpdb->posts
        WHERE post_date {$op} '{$post->post_date}'
        AND post_status = 'publish'
        AND post_type = ('{$post_type}')
    ");
    return $number;
}

//search
function custom_search($search, $wp_query  ) {
    //query['s']があったら検索ページ表示
    if ( isset($wp_query->query['s']) ) $wp_query->is_search = true;
    return $search;
}
add_filter('posts_search','custom_search', 10, 2);

// テンプレート読み込みフィルターをカスタマイズ
add_filter('template_include','custom_search_template');
function custom_search_template($template){
    // 検索結果の時
    if ( is_search() ) {
        // 表示する投稿タイプを取得
        $post_types = get_query_var('post_type');
        // search-{$post_type}.php の読み込みルールを追加
        foreach ( (array) $post_types as $post_type )
            $templates[] = "search-{$post_type}.php";
        $templates[] = 'search.php';
        $template = get_query_template('search',$templates);
    }
    return $template;
}


//--------------------------------画像サイズ/
add_theme_support('post-thumbnails');
add_image_size('thumbnail', 150, 150, true);//
add_image_size('thumbnail_95', 95, 95, true);//
add_image_size('w120', 120, 9999, false);//
add_image_size('w200', 200, 9999, false);//
add_image_size('w230', 230, 9999, false);//
add_image_size('w250', 250, 9999, false);//
add_image_size('w300', 300, 9999, false);//
add_image_size('w340', 340, 9999, false);//
add_image_size('w350', 350, 9999, false);//
add_image_size('w400', 400, 9999, false);//
add_image_size('w500', 500, 9999, false);//
add_image_size('w600', 600, 9999, false);//
add_image_size('w700', 700, 9999, false);//
add_image_size('w720', 720, 9999, false);//
add_image_size('w800', 800, 9999, false);//
add_image_size('w900', 900, 9999, false);//
add_image_size('w980', 980, 9999, false);//
add_image_size('w1400', 1400, 9999, false);//


//--------------------------------カスタム投稿タイプ表示件数


function pre_get_posts_custom($query) {
  if( is_admin() || ! $query->is_main_query() ){
      return;
  }

  if ( $query->is_post_type_archive('event') ) {
      $query->set( 'posts_per_page', '10' );
      return;
  }
  if ( $query->is_post_type_archive('blog') ) {
      $query->set( 'posts_per_page', '10' );
      return;
  }

}
add_action( 'pre_get_posts', 'pre_get_posts_custom' );

/*ビジュアルエディタにCSSを読ませる*/
/**
 * Registers an editor stylesheet for the theme.
 */
 add_editor_style("css/editor.css");
