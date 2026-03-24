<?php
// ==========================================================================
// WordPress テーマの基本設定
// ==========================================================================
function my_theme_setup() {
    // アイキャッチ画像を有効化
    add_theme_support('post-thumbnails');

    // タイトルタグ自動生成を有効化
    add_theme_support('title-tag');

    // RSSフィードリンクを自動生成
    add_theme_support('automatic-feed-links');

    // HTML5 マークアップのサポート（フォーム、コメント、ギャラリーなど）
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    // ウィジェットの部分的なリフレッシュを有効化
    add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'my_theme_setup');

// ==========================================================================
// スクリプトとスタイルのエンキュー（共通 + 特定ページ）
// ==========================================================================
function enqueue_custom_scripts() {
    $theme_path = get_theme_file_path();
    $asset_uri  = get_theme_file_uri('/assets');
    $get_ver    = function ($file_path) {
        return file_exists($file_path) ? filemtime($file_path) : wp_get_theme()->get('Version');
    };
    $in_footer = false; // JS はすべて head で defer

    // トップ
    if (is_front_page()) {
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', [], '12.1.0', $in_footer);
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', [], '12.1.0');
        wp_enqueue_script('top-script', "{$asset_uri}/js/top.js", ['swiper-js'], $get_ver("{$theme_path}/assets/js/top.js"), $in_footer);
    }

    // お問い合わせ
    if (is_page('contact') || is_page_template('page-contact.php')) {
        wp_enqueue_script('form-script', "{$asset_uri}/js/form.js", [], $get_ver("{$theme_path}/assets/js/form.js"), $in_footer);
    }

    // 共通
    wp_enqueue_style('common-style', "{$asset_uri}/css/style.css", [], $get_ver("{$theme_path}/assets/css/style.css"));
    wp_enqueue_script('common-script', "{$asset_uri}/js/script.js", [], $get_ver("{$theme_path}/assets/js/script.js"), $in_footer);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// すべての JS に defer を付与（管理画面では行わない）
add_filter('script_loader_tag', function ($tag) {
    if (is_admin()) {
        return $tag;
    }
    return strpos($tag, ' defer') === false ? str_replace(' src', ' defer src', $tag) : $tag;
}, 10, 1);


// ==========================================================================
// ヘッダーへのプリロード
// ==========================================================================
function enqueue_preload_headers() {
  ?>
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="preload"
    as="style" fetchpriority="high">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet"
    media="print" onload="this.media='all'">
<?php
}
add_action('wp_head', 'enqueue_preload_headers');

// ==========================================================================
// 不要な head内のタグやスクリプトを削除する関数
// ==========================================================================
function codeups_clean_up_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
}

add_action('after_setup_theme', 'codeups_clean_up_head');

// ==========================================================================
// ブロックエディタのスタイルを追加
// ==========================================================================

function add_block_editor_styles() {
    wp_enqueue_style( 'editor-styles', get_stylesheet_directory_uri() . '/assets/css/editor.css' );
    
    // エディターにフォントファミリーを追加
    add_editor_style( array(
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap',
    ) );
    
    // インラインスタイルでフォントファミリーを設定
    wp_add_inline_style( 'editor-styles', '
        .editor-styles-wrapper {
            font-family: "Noto Sans JP", "Noto Sans", sans-serif !important;
        }
    ' );
}
add_action( 'enqueue_block_editor_assets', 'add_block_editor_styles' );

// ==========================================================================
// 投稿数の変更
// ==========================================================================
function modify_posts_per_page($query) {
    if ($query->is_main_query() && !is_admin()) {
        if (wp_is_mobile()) {
            $query->set('posts_per_page', 5); // スマホの場合
        } else {
            $query->set('posts_per_page', 6); // PCの場合
        }
    }
}
add_action('pre_get_posts', 'modify_posts_per_page');