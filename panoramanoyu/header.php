<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <?php if(is_page('contact')): ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <?php else: ?>
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <?php endif; ?>
  <meta name="format-detection" content="telephone=no" />
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <header class="header">
    <div class="header__inner">
    <a href="<?php echo HOME_URL; ?>" class="header__logo-link">
        <?php
          $logo_tag = (is_front_page() || is_home()) ? 'h1' : 'div';
        ?>
        <<?php echo esc_attr($logo_tag); ?> class="header__logo">
          <img src="<?php echo IMAGEPATH; ?>/common/logo.webp" alt="" class="header__logo-img" width="103.477"
            height="30">
        </<?php echo esc_attr($logo_tag); ?>>
      </a>
    </div>
  </header>