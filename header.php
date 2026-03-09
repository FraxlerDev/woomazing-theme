<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ══ MOBILE NAV ══ -->
<div class="mobile-nav" id="mobileNav">
  <button class="mobile-nav__close" onclick="closeMobileNav()">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/mobile-menu-close.png" alt="Закрити" width="28" height="28" />
  </button>
  <?php
  wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => '',
    'items_wrap'     => '%3$s',
    'walker'         => new Womazing_Mobile_Menu_Walker(),
  ]);
  ?>
  <span class="mobile-nav__phone"><?php echo esc_html( womazing_phone() ); ?></span>
</div>

<!-- ══ STICKY HEADER ══ -->
<div class="site-header site-header--sticky" id="stickyHeader">
  <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">
    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/logo.svg" alt="Womazing" class="logo__img" />
  </a>
  <nav class="main-nav">
    <?php wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => '',
      'items_wrap'     => '%3$s',
    ]); ?>
  </nav>
  <div class="header-right">
    <a href="tel:<?php echo preg_replace('/[^+\d]/', '', womazing_phone()); ?>" class="header-phone">
      <?php echo esc_html( womazing_phone() ); ?>
    </a>
    <button class="icon-btn call-btn" onclick="openPopup()" title="Замовити дзвінок">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/telephone-menu.png" alt="Дзвінок" width="24" height="24" class="icon-hover-swap" data-hover="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/telephone-menu-hover.png" />
    </button>
    <?php if ( function_exists('wc_get_cart_url') ) : ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-btn cart-icon-btn" title="Кошик">
      <span class="cart-icon-wrap">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/shopping-bags.svg" alt="Кошик" width="24" height="24" />
        <?php $count = ( function_exists('WC') && WC() && is_a(WC()->cart, 'WC_Cart') ) ? WC()->cart->get_cart_contents_count() : 0; ?>
        <span class="cart-count<?php echo $count > 0 ? ' is-visible' : ''; ?>"><?php echo esc_html($count); ?></span>
      </span>
    </a>
    <?php endif; ?>
    <button class="burger" id="burgerSticky" onclick="openMobileNav()">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/mobile-menu.png" alt="Меню" width="28" height="28" />
    </button>
  </div>
</div>