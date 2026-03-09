<?php get_header(); ?>

<?php $icons = get_template_directory_uri() . '/assets/icons/'; ?>

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
          <?php $count = (function_exists('WC') && WC() && is_a(WC()->cart, 'WC_Cart')) ? WC()->cart->get_cart_contents_count() : 0; ?>
          <span class="cart-count<?php echo $count > 0 ? ' is-visible' : ''; ?>"><?php echo esc_html($count); ?></span>
        </span>
      </a>
      <button class="burger" onclick="openMobileNav()" title="Меню">
        <img src="<?php echo esc_url( $icons . 'mobile-menu.png' ); ?>" alt="Меню" width="28" height="28" />
      </button>
    </div>
  </div>

  <div class="page-hero__inner">
    <h1 class="page-hero__title">Магазин</h1>
    <nav class="breadcrumbs">
      <a href="<?php echo esc_url( home_url('/') ); ?>">Головна</a>
      <span>—</span>
      <span>Магазин</span>
    </nav>
  </div>

</div>

<!-- ══ SHOP CONTENT ══ -->
<main class="shop-wrap">

  <?php if ( woocommerce_product_loop() ) : ?>

  <div class="cat-filters">
    <?php
    $current_cat = is_product_category() ? get_queried_object() : null;
    $active_id   = $current_cat ? $current_cat->term_id : 0;
    $shop_url    = get_permalink( wc_get_page_id('shop') );
    ?>
    <a href="<?php echo esc_url( $shop_url ); ?>"
       class="cat-btn<?php echo ! $active_id ? ' is-active' : ''; ?>">Всі</a>
    <?php
    $cats = get_terms(['taxonomy'=>'product_cat','hide_empty'=>true,'exclude'=>get_option('default_product_cat')]);
    foreach ( $cats as $cat ) : ?>
    <a href="<?php echo esc_url( get_term_link($cat) ); ?>"
       class="cat-btn<?php echo ($active_id === $cat->term_id) ? ' is-active' : ''; ?>">
      <?php echo esc_html( $cat->name ); ?>
    </a>
    <?php endforeach; ?>
  </div>

  <p class="shop-count">
    <?php
    global $wp_query;
    $total = $wp_query->found_posts;
    $shown = min( $total, wc_get_loop_prop('current_page') * (int) get_option('posts_per_page', 9) );
    printf( 'Показано: %d з %d товарів', $shown, $total );
    ?>
  </p>

  <div class="products-grid">
    <?php while ( have_posts() ) :
      the_post();
      global $product;
      $product = wc_get_product( get_the_ID() );
      if ( ! $product ) continue;
      $img_id  = $product->get_image_id();
      $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'womazing-product' ) : wc_placeholder_img_src();
    ?>
    <article class="product-card" onclick="location.href='<?php echo esc_url( get_permalink() ); ?>'">
      <div class="product-photo">
        <img src="<?php echo esc_url( $img_url ); ?>"
             alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy" />
        <div class="product-overlay">
          <img src="<?php echo esc_url( $icons . 'overlay-button.png' ); ?>" alt="" width="36" />
        </div>
      </div>
      <div class="product-info">
        <span class="product-name"><?php echo esc_html( $product->get_name() ); ?></span>
        <div class="price-row"><?php echo $product->get_price_html(); ?></div>
      </div>
    </article>
    <?php endwhile; ?>
  </div>

  <?php if ( wc_get_loop_prop('is_paginated') && woocommerce_products_will_display() ) : ?>
  <div class="woocommerce-pagination">
    <?php woocommerce_pagination(); ?>
  </div>
  <?php endif; ?>

  <?php else : ?>
    <p class="woocommerce-info">Товарів не знайдено.</p>
  <?php endif; ?>

</main>

<?php get_footer(); ?>