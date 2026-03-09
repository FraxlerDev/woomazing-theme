<?php
/**
 * Template Name: Кошик
 *
 * Використовується автоматично для сторінки з slug "cart"
 * завдяки ієрархії шаблонів WordPress: page-{slug}.php
 *
 * @package Womazing
 */

defined( 'ABSPATH' ) || exit;

$icons = get_template_directory_uri() . '/assets/icons/';

get_header();
?>

<!-- ══ PAGE HERO ══ -->
<div class="page-hero">

  <div class="site-header site-header--page">
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">
      <img src="<?php echo esc_url( $icons . 'logo.svg' ); ?>" alt="Womazing" class="logo__img" />
    </a>
    <nav class="main-nav">
      <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'','items_wrap'=>'%3$s']); ?>
    </nav>
    <div class="header-right">
      <a href="tel:<?php echo preg_replace('/[^+\d]/','',womazing_phone()); ?>" class="header-phone">
        <?php echo esc_html( womazing_phone() ); ?>
      </a>
      <button class="icon-btn call-btn" onclick="openPopup()" title="Замовити дзвінок">
        <img src="<?php echo esc_url( $icons . 'telephone-menu.png' ); ?>" alt="Дзвінок" width="24" height="24"
             class="icon-hover-swap" data-hover="<?php echo esc_url( $icons . 'telephone-menu-hover.png' ); ?>" />
      </button>
      <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-btn cart-icon-btn" title="Кошик">
        <span class="cart-icon-wrap">
          <img src="<?php echo esc_url( $icons . 'shopping-bags.svg' ); ?>" alt="Кошик" width="24" height="24" />
          <?php $count = ( function_exists('WC') && WC() && is_a(WC()->cart, 'WC_Cart') ) ? WC()->cart->get_cart_contents_count() : 0; ?>
          <span class="cart-count<?php echo $count > 0 ? ' is-visible' : ''; ?>"><?php echo esc_html($count); ?></span>
        </span>
      </a>
      <button class="burger" onclick="openMobileNav()" title="Меню">
        <img src="<?php echo esc_url( $icons . 'mobile-menu.png' ); ?>" alt="Меню" width="28" height="28" />
      </button>
    </div>
  </div>

  <div class="page-hero__inner">
    <h1 class="page-hero__title"><?php esc_html_e( 'Корзина', 'womazing' ); ?></h1>
    <nav class="breadcrumbs">
      <a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Головна', 'womazing' ); ?></a>
      <span>—</span>
      <span><?php esc_html_e( 'Корзина', 'womazing' ); ?></span>
    </nav>
  </div>

</div>

<!-- ══ CART CONTENT ══ -->
<main class="cart-wrap">

  <?php
  // Виводимо повідомлення WooCommerce (успіх купону, помилки тощо)
  if ( function_exists('wc_print_notices') ) {
    wc_print_notices();
  }

  // Рендеримо вміст кошика через шорткод — WooCommerce сам будує форму
  if ( function_exists('WC') ) {
    echo do_shortcode('[woocommerce_cart]');
  }
  ?>

</main>

<?php get_footer(); ?>