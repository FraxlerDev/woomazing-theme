<?php
/*
 * Template Name: Контакти
 */
get_header(); ?>

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
    <h1 class="page-hero__title">Контакти</h1>
    <nav class="breadcrumbs">
      <a href="<?php echo esc_url( home_url('/') ); ?>">Головна</a>
      <span>—</span>
      <span>Контакти</span>
    </nav>
  </div>

</div>

<!-- ══ CONTACTS CONTENT ══ -->
<main class="contacts-wrap">

  <!-- ─ Контактна інформація ─ -->
  <div class="contacts-info js-fade">
    <div class="contact-item">
      <span class="contact-item__label">Телефон</span>
      <div class="contact-item__value">
        <a href="tel:<?php echo preg_replace('/[^+\d]/','',womazing_phone()); ?>">
          <?php echo esc_html( womazing_phone() ); ?>
        </a>
      </div>
    </div>
    <div class="contact-item">
      <span class="contact-item__label">Email</span>
      <div class="contact-item__value">
        <a href="mailto:<?php echo esc_attr( womazing_email_contact() ); ?>">
          <?php echo esc_html( womazing_email_contact() ); ?>
        </a>
      </div>
    </div>
    <div class="contact-item">
      <span class="contact-item__label">Адреса</span>
      <div class="contact-item__value">вул. Центральна 20, м. Київ</div>
    </div>
    <div class="contact-item">
      <span class="contact-item__label">Графік роботи</span>
      <div class="contact-item__value contact-item__value--sm">Пн–Пт: 9:00–18:00</div>
    </div>
  </div>

  <!-- ─ Карта ─ -->
  <div class="map-container js-fade">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2540.3!2d30.5238!3d50.4501!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4cf4e65c72093%3A0xd47b254bdb9a7e77!2z0LLRg9C7LiDQptC10L3RgtGA0LDQu9GM0L3QsCwg0JrQuNGX0LI!5e0!3m2!1suk!2sua!4v1234567890"
      width="100%" height="476"
      style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"
      title="Womazing на карті">
    </iframe>
  </div>

  <!-- ─ Форма зворотного зв'язку ─ -->
  <div class="contact-form-wrap js-fade">
    <h2 class="contact-form-wrap__title">Залишити повідомлення</h2>

    <?php if ( function_exists('wpcf7_contact_form') ) :
      echo do_shortcode('[contact-form-7 id="1" title="Контактна форма"]');
    else : ?>

    <div class="wpcf7-form">
      <div class="form-row">
        <div class="form-field">
          <input type="text" id="ctName" placeholder="Ім'я" autocomplete="given-name" />
        </div>
        <div class="form-field">
          <input type="tel" id="ctPhone" placeholder="Телефон" autocomplete="tel" />
        </div>
        <div class="form-field">
          <input type="email" id="ctEmail" placeholder="E-mail" autocomplete="email" />
        </div>
        <div class="form-field">
          <textarea id="ctMsg" placeholder="Повідомлення"></textarea>
        </div>
        <button class="btn-cta wpcf7-submit" onclick="submitContactForm()">Надіслати</button>
      </div>
      <div id="ctSuccess" class="contact-success" hidden>
        Дякуємо! Ми зв'яжемося з вами найближчим часом.
      </div>
    </div>

    <script>
    function submitContactForm() {
      var name  = document.getElementById('ctName').value.trim();
      var phone = document.getElementById('ctPhone').value.trim();
      if ( ! name || ! phone ) {
        alert("Будь ласка, заповніть Ім'я та Телефон");
        return;
      }
      jQuery.post(womAjax.url, {
        action: 'womazing_callback',
        nonce:  womAjax.nonce,
        name:   name,
        phone:  phone,
        email:  document.getElementById('ctEmail').value,
        message: document.getElementById('ctMsg').value,
      }, function(r) {
        if ( r.success ) {
          document.getElementById('ctSuccess').removeAttribute('hidden');
        }
      });
    }
    </script>

    <?php endif; ?>
  </div>

</main>

<?php get_footer(); ?>