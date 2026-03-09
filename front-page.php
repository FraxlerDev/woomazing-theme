<?php get_header(); ?>

<!-- ══ HERO ══ -->
<section class="hero">
  <div class="hero__bg"></div>

  <!-- Hero-шапка (поверх hero) -->
  <div class="site-header" style="position:absolute;">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
      <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/logo.svg" alt="Womazing" class="logo__img" />
    </a>
    <nav class="main-nav">
      <?php wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'','items_wrap'=>'%3$s']); ?>
    </nav>
    <div class="header-right">
      <a href="tel:<?php echo preg_replace('/[^+\d]/','',womazing_phone()); ?>" class="header-phone"><?php echo esc_html(womazing_phone()); ?></a>
      <button class="icon-btn call-btn" onclick="openPopup()">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/telephone-menu.png" alt="Дзвінок" width="24" height="24" class="icon-hover-swap" data-hover="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/telephone-menu-hover.png" />
      </button>
      <?php if ( function_exists('wc_get_cart_url') ) : ?>
      <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="icon-btn cart-icon-btn" title="Кошик">
        <span class="cart-icon-wrap">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/shopping-bags.svg" alt="Кошик" width="24" height="24" />
          <?php $count = ( function_exists('WC') && WC() && is_a(WC()->cart, 'WC_Cart') ) ? WC()->cart->get_cart_contents_count() : 0; ?>
          <span class="cart-count<?php echo $count > 0 ? ' is-visible' : ''; ?>"><?php echo esc_html($count); ?></span>
        </span>
      </a>
      <?php endif; ?>
      <button class="burger" onclick="openMobileNav()">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/mobile-menu.png" alt="Меню" width="28" height="28" />
      </button>
    </div>
  </div>

  <!-- Слайди: тільки заголовок + опис змінюються -->
  <div class="hero__inner">
    <?php
    $slides = [
      [
        'title' => get_option('womazing_hero_title_1', 'Нові надходження цього сезону'),
        'desc'  => get_option('womazing_hero_desc_1', 'Витончені поєднання та оксамитові відтінки — саме те, що ви шукали цього сезону. Час досліджувати.'),
      ],
      [
        'title' => get_option('womazing_hero_title_2', 'Щось новеньке. Ми чекали тебе.'),
        'desc'  => get_option('womazing_hero_desc_2', 'Набридло шукати себе у сірому місті? Настав час нових ідей, свіжих фарб та натхнення з Womazing!'),
      ],
      [
        'title' => get_option('womazing_hero_title_3', 'Вмикай новий сезон з WOMAZING'),
        'desc'  => get_option('womazing_hero_desc_3', 'Ми оновили асортимент — легендарні колекції та новинки від вітчизняних дизайнерів.'),
      ],
    ];
    foreach ( $slides as $i => $slide ) :
      $active = $i === 0 ? ' is-active' : '';
    ?>
    <div class="hero__slide<?php echo $active; ?>">
      <h1 class="hero__title"><?php echo esc_html($slide['title']); ?></h1>
      <p class="hero__desc"><?php echo esc_html($slide['desc']); ?></p>
    </div>
    <?php endforeach; ?>

    <!-- Кнопки статичні — не дублюються на кожен слайд -->
    <div class="hero__btns">
      <button class="btn-scroll" onclick="scrollToMain()">
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/arrow-down.png" alt="Прокрутити вниз" width="40" height="40" />
      </button>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-cta">Відкрити магазин</a>
    </div>
  </div>

  <!-- Три фото як у Figma -->
  <?php
  $base = get_template_directory_uri() . '/assets/img/';
  $hero_mains = [
    get_theme_mod('womazing_hero_photo',  $base . 'hero-main.png'),
    get_theme_mod('womazing_hero_photo_2', $base . 'hero-main2.png'),
    get_theme_mod('womazing_hero_photo_3', $base . 'hero-main3.png'),
  ];
  $hero_side = get_theme_mod('womazing_hero_photo2', $base . 'hero-side.png');
  $hero_low  = get_theme_mod('womazing_hero_photo3', $base . 'hero-low.png');
  ?>
  <div class="hero__photos" aria-hidden="true">
    <!-- Головне фото — слайдер синхронний з текстом -->
    <div class="hero__photo-main">
      <?php foreach ( $hero_mains as $i => $src ) :
        $act = $i === 0 ? ' is-active' : '';
      ?>
      <img class="hero__main-img<?php echo $act; ?>" src="<?php echo esc_url($src); ?>" alt="" />
      <?php endforeach; ?>
    </div>
    <!-- Статичне фото вгорі праворуч -->
    <div class="hero__photo-side">
      <img src="<?php echo esc_url($hero_side); ?>" alt="" />
    </div>
    <!-- Статичне фото внизу -->
    <div class="hero__photo-low">
      <img src="<?php echo esc_url($hero_low); ?>" alt="" />
    </div>
  </div>

  <!-- Dots -->
  <div class="slider-dots">
    <button class="dot is-active" onclick="goSlide(0)"></button>
    <button class="dot" onclick="goSlide(1)"></button>
    <button class="dot" onclick="goSlide(2)"></button>
  </div>
</section>

<!-- ══ MAIN CONTENT ══ -->
<main class="main-wrap" id="mainContent">

  <!-- ─ Нова колекція ─ -->
  <section class="section js-fade">
    <h2 class="section-title">Нова колекція</h2>
    <div class="products-grid">
      <?php
      $featured = wc_get_products([
        'limit'    => 3,
        'status'   => 'publish',
        'orderby'  => 'date',
        'order'    => 'DESC',
        'featured' => true,
      ]);
      if ( empty($featured) ) {
        $featured = wc_get_products(['limit' => 3, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC']);
      }
      foreach ( $featured as $product ) :
        $img_id = $product->get_image_id();
        $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'womazing-product') : wc_placeholder_img_src();
        $price_html = $product->get_price_html();
      ?>
      <article class="product-card" onclick="location.href='<?php echo esc_url(get_permalink($product->get_id())); ?>'">
        <div class="product-photo">
          <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" loading="lazy" />
          <div class="product-overlay">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/overlay-button.png" alt="" width="36" height="26" />
          </div>
        </div>
        <div class="product-info">
          <span class="product-name"><?php echo esc_html($product->get_name()); ?></span>
          <div class="price-row"><?php echo $price_html; ?></div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="section-cta">
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-outline">Відкрити магазин</a>
    </div>
  </section>

  <!-- ─ Що для нас важливо ─ -->
  <section class="section js-fade">
    <h2 class="section-title">Що для нас важливо</h2>
    <div class="triggers-grid">
      <div class="trigger">
        <div class="trigger__icon">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/quality.png" alt="Якість" width="56" height="56" />
        </div>
        <div>
          <h3 class="trigger__title">Якість</h3>
          <p class="trigger__text">Наші професіонали працюють на найкращому обладнанні для пошиву одягу безпрецедентної якості</p>
        </div>
      </div>
      <div class="trigger">
        <div class="trigger__icon">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/settings.png" alt="Швидкість" width="56" height="56" />
        </div>
        <div>
          <h3 class="trigger__title">Швидкість</h3>
          <p class="trigger__text">Завдяки налагодженій системі у Womazing ми можемо відшивати до 20 одиниць продукції у власних цехах</p>
        </div>
      </div>
      <div class="trigger">
        <div class="trigger__icon">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/responsible.png" alt="Відповідальність" width="56" height="56" />
        </div>
        <div>
          <h3 class="trigger__title">Відповідальність</h3>
          <p class="trigger__text">Ми дбаємо про людей і планету. Безвідходне виробництво та комфортні умови праці — все це Womazing</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ─ Команда мрії ─ -->
  <section class="section js-fade">
    <h2 class="section-title">Команда мрії Womazing</h2>
    <div class="team-inner">
      <div class="team-gallery">
        <?php
        $team_imgs = [
          get_theme_mod('womazing_team_1', get_template_directory_uri() . '/assets/img/team-1.png'),
          get_theme_mod('womazing_team_2', get_template_directory_uri() . '/assets/img/team-2.png'),
          get_theme_mod('womazing_team_3', get_template_directory_uri() . '/assets/img/team-3.png'),
        ];
        foreach ( $team_imgs as $idx => $timg ) :
          $act = $idx === 0 ? ' is-active' : '';
        ?>
        <div class="gallery-slide<?php echo $act; ?>">
          <img src="<?php echo esc_url($timg); ?>" alt="Команда Womazing <?php echo $idx+1; ?>" loading="lazy" />
        </div>
        <?php endforeach; ?>
        <div class="gallery-dots">
          <button class="gallery-dot is-active" onclick="goGallery(0)"></button>
          <button class="gallery-dot" onclick="goGallery(1)"></button>
          <button class="gallery-dot" onclick="goGallery(2)"></button>
        </div>
        <button class="gallery-arr gallery-arr--prev" onclick="prevGallery()">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/arrow-slider-left.png" alt="Попередній" width="14" height="24" />
        </button>
        <button class="gallery-arr gallery-arr--next" onclick="nextGallery()">
          <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/icons/arrow-slider-right.png" alt="Наступний" width="14" height="24" />
        </button>
      </div>
      <div class="team-text">
        <h3 class="team-subtitle">Для кожної</h3>
        <p class="team-desc">Кожна дівчина унікальна. Але ми схожі в мільйоні дрібниць.<br><br>Womazing шукає ці дрібниці та створює прекрасні речі, які вигідно підкреслюють переваги кожної дівчини.</p>
        <a href="<?php echo esc_url(home_url('/pro-brend')); ?>" class="link-teal">Детальніше про бренд</a>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>