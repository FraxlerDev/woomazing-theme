<footer>
  <div class="footer-grid">

    <!-- Лого + юридичні посилання -->
    <div>
      <div class="footer-logo">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/logo.svg" alt="Womazing" class="logo__img" />
      </div>
      <div class="footer-legal">
        <a href="#">© Всі права захищені</a>
        <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Політика конфіденційності</a>
        <a href="#">Публічна оферта</a>
      </div>
    </div>

    <!-- Навігація + категорії -->
    <div>
      <nav class="footer-nav-row">
        <?php wp_nav_menu([
          'theme_location' => 'footer',
          'container'      => false,
          'menu_class'     => '',
          'items_wrap'     => '%3$s',
          'fallback_cb'    => function() {
            echo '<a href="' . esc_url(home_url('/')) . '">Головна</a>';
            echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">Магазин</a>';
            echo '<a href="' . esc_url(home_url('/pro-brend')) . '">Про бренд</a>';
            echo '<a href="' . esc_url(home_url('/kontakty')) . '">Контакти</a>';
          },
        ]); ?>
      </nav>
      <div class="footer-cats">
        <?php
        $cat_terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 4, 'exclude' => get_option('default_product_cat')]);
        if ( ! is_wp_error($cat_terms) ) :
          foreach ( $cat_terms as $term ) : ?>
            <a href="<?php echo esc_url( get_term_link($term) ); ?>"><?php echo esc_html( $term->name ); ?></a>
          <?php endforeach;
        else: ?>
          <a href="#">Пальта</a>
          <a href="#">Світшоти</a>
          <a href="#">Кардигани</a>
          <a href="#">Толстовки</a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Контакти + соцмережі -->
    <div class="footer-right">
      <div class="footer-contacts">
        <a href="tel:<?php echo preg_replace('/[^+\d]/', '', womazing_phone()); ?>">
          <?php echo esc_html( womazing_phone() ); ?>
        </a>
        <a href="mailto:<?php echo esc_attr( womazing_email_contact() ); ?>">
          <?php echo esc_html( womazing_email_contact() ); ?>
        </a>
      </div>
      <div class="footer-socials">
        <a href="#" title="Instagram">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/instagram-icon.png" alt="Instagram" width="24" height="24" />
        </a>
        <a href="#" title="Facebook">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/facebook-icon.png" alt="Facebook" width="24" height="24" />
        </a>
        <a href="#" title="Twitter">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/twitter-icon.png" alt="Twitter" width="24" height="24" />
        </a>
      </div>
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/visa-mc.png' ); ?>"
           alt="Visa Mastercard" style="height:22px" loading="lazy" />
    </div>

  </div>
</footer>

<!-- ══ POPUP ══ -->
<div class="popup-overlay" id="popupOverlay">
  <div class="popup">
    <button class="popup__close" onclick="closePopup()">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/popup-close.png" alt="Закрити" width="20" height="20" />
    </button>
    <div id="popupForm">
      <h3 class="popup__title">Замовити зворотний дзвінок</h3>
      <div class="form-fields">
        <div class="form-field">
          <input type="text" id="cbName" placeholder="Ім'я" autocomplete="given-name" />
        </div>
        <div class="form-field">
          <input type="email" id="cbEmail" placeholder="E-mail" autocomplete="email" />
        </div>
        <div class="form-field">
          <input type="tel" id="cbPhone" placeholder="Телефон" autocomplete="tel" />
        </div>
        <button class="btn-cta" id="cbSubmit" style="width:100%;justify-content:center;">Надіслати</button>
      </div>
    </div>
    <div class="popup-success" id="popupSuccess">
      <p class="popup-success__title">Чудово! Ми незабаром зателефонуємо вам.</p>
      <button class="btn-outline" onclick="closePopup()">Закрити</button>
    </div>
  </div>
</div>


<?php wp_footer(); ?>
</body>
</html>