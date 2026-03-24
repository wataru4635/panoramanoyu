<?php
// ==========================================================================
// 定義
// ==========================================================================
/* ---------- パスの短縮 ---------- */
define('IMAGEPATH',            get_template_directory_uri() . '/assets/images');

/* ---------- 各ページのリンク ---------- */
define('HOME_URL',             esc_url(home_url('/')));                          // トップページ
define('IZUMISOU_URL',         esc_url(home_url('/izumisou/')));                 // いずみ荘
define('PANORAMANOYU_URL',     esc_url(home_url('/panoramanoyu/')));             // パノラマの湯について
define('GUIDE_URL',            esc_url(home_url('/guide/')));                    // ご利用案内
define('FACILITIES_URL',       esc_url(home_url('/facilities/')));               // 館内紹介
define('RESTAURANT_URL',       esc_url(home_url('/restaurant/')));               // 食事処いずみ
define('NEWS_URL',             esc_url(home_url('/news/')));                     // お知らせ
define('SIGHTSEEING_URL',      esc_url(home_url('/sightseeing/')));              // 周辺観光情報
define('CAMP_URL',             esc_url(home_url('/camp/')));                     // キャンプ場
