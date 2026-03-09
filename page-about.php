<?php
/*
 * Template Name: Про бренд
 */
get_header(); ?>

<?php
$icons = get_template_directory_uri() . '/assets/icons/';
$img   = get_template_directory_uri() . '/assets/img/';
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
    <h1 class="page-hero__title">Про бренд</h1>
    <nav class="breadcrumbs">
      <a href="<?php echo esc_url( home_url('/') ); ?>">Головна</a>
      <span>—</span>
      <span>Про бренд</span>
    </nav>
  </div>

</div>

<!-- ══ ABOUT CONTENT ══ -->
<main class="about-wrap">

  <!-- ─ Блок 1: фото зліва, текст справа ─ -->
  <div class="about-block js-fade">

    <div class="about-img">
      <img
        src="<?php echo esc_url( get_theme_mod('womazing_about_1', $img . 'about-1.png') ); ?>"
        alt="Womazing — ідея і жінка"
        loading="lazy"
      />
    </div>

    <div class="about-text">
      <h2 class="about-text__title">Ідея і жінка</h2>
      <p class="about-text__body">Womazing було засновано у 2010-ні та стало однією з найбільш успішних компаній у сфері жіночої моди. Бренд виріс із кількох незалежних ініціатив і залишається сімейною справою, хоча жоден із членів родини не є профільним модельєром.</p>
      <p class="about-text__body">Ми обрали свій особливий шлях: залучили відомих незалежних дизайнерів до створення власних колекцій. Цей підхід набув широкого визнання у світі моди як унікальна форма співтворчості — характерна для низки італійських будинків pret-à-porter.</p>
    </div>

  </div>

  <!-- ─ Блок 2: текст зліва, фото справа ─ -->
  <div class="about-block about-block--reverse js-fade">

    <div class="about-text">
      <h2 class="about-text__title">Магія в деталях</h2>
      <p class="about-text__body">Перший магазин Womazing відкрився в невеликому приміщенні на північ від центру міста на початку 2010-х. Перша колекція складалася лише з двох пальт і кількох костюмів, пошитих руками майстрів із великим досвідом.</p>
      <p class="about-text__body">Незважаючи на скромний початок, основна ідея була чіткою: створювати одяг для впевнених жінок, яким важливі як естетика, так і якість. Прагнення робити речі, що надихають — з правильним кроєм, правильними тканинами й увагою до кожної деталі — залишається серцем Womazing і сьогодні.</p>
    </div>

    <div class="about-img">
      <img
        src="<?php echo esc_url( get_theme_mod('womazing_about_2', $img . 'about-2.png') ); ?>"
        alt="Womazing — магія в деталях"
        loading="lazy"
      />
    </div>

  </div>

  <!-- ─ CTA ─ -->
  <div class="about-cta js-fade">
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id('shop') ) ); ?>" class="btn-outline">
      Перейти в магазин
    </a>
  </div>

</main>

<?php get_footer(); ?>