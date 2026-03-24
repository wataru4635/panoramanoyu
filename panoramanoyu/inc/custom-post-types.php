<?php
// ==========================================================================
// カスタム投稿：お知らせ（タクソノミーあり）
// ==========================================================================
function create_post_type_news() {
	register_post_type(
		'news',
		array(
			'labels' => array(
				'name' => 'お知らせ',
				'singular_name' => 'お知らせ',
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array( 'slug' => 'news' ),
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-edit',
			'menu_position' => 5,
			'taxonomies' => array( 'news_category' ),
		)
	);

	register_taxonomy(
		'news_category',
		'news',
		array(
			'label' => 'カテゴリー',
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'rewrite' => array( 'slug' => 'news-category' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'create_post_type_news' );

// ==========================================================================
// カスタム投稿タイプ "news"・タクソノミー "news_category" での表示件数
// ==========================================================================
function custom_posts_per_page_news( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( $query->is_post_type_archive( 'news' ) || $query->is_tax( 'news_category' ) ) {
			if ( wp_is_mobile() ) {
				$query->set( 'posts_per_page', 6 );
			} else {
				$query->set( 'posts_per_page', 12 );
			}
		}
	}
}
add_action( 'pre_get_posts', 'custom_posts_per_page_news' );
