<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php
  global $product;
  $product = wc_get_product( get_the_ID() );
  if ( ! $product ) { get_footer(); exit; }
  $icons   = get_template_directory_uri() . '/assets/icons/';
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
      <?php if ( function_exists('wc_get_cart_url') ) : ?>
      <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="icon-btn cart-icon-btn" title="Кошик">
        <span class="cart-icon-wrap">
          <img src="<?php echo esc_url( $icons . 'shopping-bags.svg' ); ?>" alt="Кошик" width="24" height="24" />
          <?php $count = ( function_exists('WC') && WC() && is_a(WC()->cart, 'WC_Cart') ) ? WC()->cart->get_cart_contents_count() : 0; ?>
          <span class="cart-count<?php echo $count > 0 ? ' is-visible' : ''; ?>"><?php echo esc_html($count); ?></span>
        </span>
      </a>
      <?php endif; ?>
      <button class="burger" onclick="openMobileNav()" title="Меню">
        <img src="<?php echo esc_url( $icons . 'mobile-menu.png' ); ?>" alt="Меню" width="28" height="28" />
      </button>
    </div>
  </div>

  <div class="page-hero__inner">
    <h1 class="page-hero__title"><?php the_title(); ?></h1>
    <?php woocommerce_breadcrumb(); ?>
  </div>

</div>

<!-- ══ SINGLE PRODUCT ══ -->
<main class="single-product-wrap">
  <div class="product-layout">

    <!-- ─ Галерея ─ -->
    <div class="product-gallery">
      <?php
      $img_id      = $product->get_image_id();
      $gallery_ids = $product->get_gallery_image_ids();
      $all_imgs    = $img_id ? array_merge([$img_id], $gallery_ids) : $gallery_ids;
      ?>
      <div class="product-gallery__main">
        <?php if ( $img_id ) : ?>
          <img id="productMainImg"
               src="<?php echo esc_url( wp_get_attachment_image_url($img_id, 'woocommerce_single') ); ?>"
               alt="<?php echo esc_attr( $product->get_name() ); ?>" />
        <?php else : ?>
          <?php echo wc_placeholder_img('woocommerce_single'); ?>
        <?php endif; ?>
      </div>

      <?php if ( count($all_imgs) > 1 ) : ?>
      <div class="product-gallery__thumbs">
        <?php foreach ( $all_imgs as $i => $gid ) :
          $thumb = wp_get_attachment_image_url($gid, 'thumbnail');
          $full  = wp_get_attachment_image_url($gid, 'woocommerce_single');
        ?>
        <button type="button"
                class="product-gallery__thumb<?php echo $i === 0 ? ' is-active' : ''; ?>"
                onclick="switchProductImg('<?php echo esc_js($full); ?>', this)">
          <img src="<?php echo esc_url($thumb); ?>" alt="" loading="lazy" />
        </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- ─ Деталі ─ -->
    <div class="product-details">

      <h2 class="product-details__name"><?php the_title(); ?></h2>

      <!-- Ціна -->
      <div class="product-price-block">
        <?php if ( $product->is_on_sale() ) : ?>
          <span class="product-price"><?php echo wc_price( $product->get_sale_price() ); ?></span>
          <span class="product-price-old"><?php echo wc_price( $product->get_regular_price() ); ?></span>
        <?php else : ?>
          <span class="product-price"><?php echo wc_price( $product->get_price() ); ?></span>
        <?php endif; ?>
      </div>

      <!-- Короткий опис -->
      <?php if ( $product->get_short_description() ) : ?>
      <div class="product-details__desc">
        <?php echo wp_kses_post( $product->get_short_description() ); ?>
      </div>
      <?php endif; ?>

      <!-- Розміри -->
      <?php
      $sizes = [];
      if ( $product->is_type('variable') ) {
        foreach ( $product->get_variation_attributes() as $attr => $vals ) {
          if ( preg_match('/(size|розм)/i', $attr) ) { $sizes = $vals; break; }
        }
        if ( empty($sizes) ) $sizes = reset( $product->get_variation_attributes() );
      }
      $sizes = $sizes ?: ['S','M','L','XL','XXL'];
      ?>
      <div class="product-variants">
        <label class="product-variants__label">Виберіть розмір</label>
        <div class="size-options">
          <?php foreach ( $sizes as $i => $s ) : ?>
          <button type="button"
                  class="size-btn<?php echo $i === 1 ? ' is-active' : ''; ?>"
                  data-size="<?php echo esc_attr($s); ?>"
                  onclick="selectSize(this)">
            <?php echo esc_html( strtoupper($s) ); ?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Кольори -->
      <?php
      $colors = [];
      if ( $product->is_type('variable') ) {
        foreach ( $product->get_variation_attributes() as $attr => $vals ) {
          if ( preg_match('/(color|colour|колір)/i', $attr) ) { $colors = $vals; break; }
        }
      }
      $demo_colors = [
        ['hex' => '#7a6560', 'label' => 'Шоколад'],
        ['hex' => '#c8c8c8', 'label' => 'Сірий'],
        ['hex' => '#e8837a', 'label' => 'Коралевий'],
        ['hex' => '#f0b96e', 'label' => 'Абрикос'],
      ];
      ?>
      <div class="product-variants">
        <label class="product-variants__label">Виберіть колір</label>
        <div class="color-options">
          <?php if ( ! empty($colors) ) :
            foreach ( $colors as $i => $c ) : ?>
              <button type="button"
                      class="color-btn<?php echo $i === 0 ? ' is-active' : ''; ?>"
                      style="background:<?php echo esc_attr($c); ?>;"
                      onclick="selectColor(this)"
                      title="<?php echo esc_attr($c); ?>"></button>
          <?php endforeach; else :
            foreach ( $demo_colors as $i => $c ) : ?>
              <button type="button"
                      class="color-btn<?php echo $i === 0 ? ' is-active' : ''; ?>"
                      style="background:<?php echo esc_attr($c['hex']); ?>;"
                      onclick="selectColor(this)"
                      title="<?php echo esc_attr($c['label']); ?>"></button>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <!-- Кількість + Додати в кошик -->
      <div class="qty-cart">
        <input type="number"
               id="productQty"
               class="qty-input"
               value="1" min="1"
               max="<?php echo esc_attr( $product->get_stock_quantity() ?: 99 ); ?>"
               aria-label="Кількість" />
        <button type="button"
                class="btn-cta btn-add-to-cart"
                id="addToCartBtn"
                data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                data-nonce="<?php echo esc_attr( wp_create_nonce('womazing_add_to_cart') ); ?>">
          Додати в кошик
        </button>
      </div>

      <!-- Feedback -->
      <div class="cart-feedback" id="cartFeedback" hidden></div>

      <!-- Мета -->
      <?php
      $cats = get_the_terms( $product->get_id(), 'product_cat' );
      if ( $product->get_sku() || ($cats && !is_wp_error($cats)) ) : ?>
      <div class="product-meta">
        <?php if ( $product->get_sku() ) : ?>
          <span class="product-meta__item">Артикул: <strong><?php echo esc_html($product->get_sku()); ?></strong></span>
        <?php endif; ?>
        <?php if ( $cats && ! is_wp_error($cats) ) :
          $cat_links = array_map(fn($c) => '<a href="'.esc_url(get_term_link($c)).'">'.esc_html($c->name).'</a>', $cats);
          ?>
          <span class="product-meta__item">Категорія: <?php echo implode(', ', $cat_links); ?></span>
        <?php endif; ?>
      </div>
      <?php endif; ?>

    </div><!-- /.product-details -->
  </div><!-- /.product-layout -->

  <!-- ─ Повний опис ─ -->
  <?php if ( $product->get_description() ) : ?>
  <div class="product-description js-fade">
    <h2 class="product-description__title">Про товар</h2>
    <div class="product-description__body">
      <?php echo wp_kses_post( $product->get_description() ); ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- ─ Схожі товари ─ -->
  <?php
  $related_ids = wc_get_related_products( $product->get_id(), 3 );
  if ( $related_ids ) :
    $related = array_filter( array_map('wc_get_product', $related_ids) );
  ?>
  <section class="related-products js-fade">
    <h2 class="related-title">Схожі товари</h2>
    <div class="products-grid">
      <?php foreach ( $related as $rel ) :
        $rimg = $rel->get_image_id()
          ? wp_get_attachment_image_url($rel->get_image_id(), 'womazing-product')
          : wc_placeholder_img_src();
      ?>
      <article class="product-card" onclick="location.href='<?php echo esc_url(get_permalink($rel->get_id())); ?>'">
        <div class="product-photo">
          <img src="<?php echo esc_url($rimg); ?>" alt="<?php echo esc_attr($rel->get_name()); ?>" loading="lazy" />
          <div class="product-overlay">
            <img src="<?php echo esc_url($icons . 'overlay-button.png'); ?>" alt="" width="36" />
          </div>
        </div>
        <div class="product-info">
          <span class="product-name"><?php echo esc_html($rel->get_name()); ?></span>
          <div class="price-row"><?php echo $rel->get_price_html(); ?></div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

</main>

<script>
function switchProductImg(src, thumb) {
  var img = document.getElementById('productMainImg');
  if (img) img.src = src;
  document.querySelectorAll('.product-gallery__thumb').forEach(function(t){ t.classList.remove('is-active'); });
  thumb.classList.add('is-active');
}
function selectSize(btn) {
  btn.closest('.size-options').querySelectorAll('.size-btn').forEach(function(b){ b.classList.remove('is-active'); });
  btn.classList.add('is-active');
}
function selectColor(btn) {
  btn.closest('.color-options').querySelectorAll('.color-btn').forEach(function(b){ b.classList.remove('is-active'); });
  btn.classList.add('is-active');
}
document.addEventListener('DOMContentLoaded', function() {
  var btn      = document.getElementById('addToCartBtn');
  var feedback = document.getElementById('cartFeedback');
  if (!btn) return;

  btn.addEventListener('click', function() {
    /* Використовуємо nonce з womCart (wp_localize_script), а не з data-атрибуту */
    if (typeof womCart === 'undefined') {
      alert('Помилка конфігурації. Оновіть сторінку.');
      return;
    }

    var pid = btn.dataset.productId;
    var qty = parseInt(document.getElementById('productQty').value) || 1;

    btn.disabled    = true;
    btn.textContent = 'Додаємо…';

    jQuery.ajax({
      url:      womCart.ajax_url,
      type:     'POST',
      dataType: 'json',
      data: {
        action:     'womazing_add_to_cart',
        nonce:      womCart.nonce,
        product_id: pid,
        quantity:   qty,
      },
      success: function(r) {
        if (r && r.success) {
          var count = r.data.cart_count || r.data.count || 0;
          /* Глобальна функція з main.js — оновлює всі .cart-count на сторінці */
          if (typeof window.updateCartBadge === 'function') {
            window.updateCartBadge(count);
          }
          feedback.textContent = '✓ ' + (r.data.message || 'Товар додано до кошика!');
          feedback.removeAttribute('hidden');
          feedback.className = 'cart-feedback is-success';
          btn.textContent = '✓ Додано';
          setTimeout(function(){
            btn.textContent = 'Додати в кошик';
            feedback.setAttribute('hidden','');
          }, 3000);
        } else {
          var msg = (r && r.data && r.data.message) ? r.data.message : 'Помилка. Спробуйте ще раз.';
          feedback.textContent = msg;
          feedback.removeAttribute('hidden');
          feedback.className = 'cart-feedback is-error';
          btn.textContent = 'Додати в кошик';
        }
      },
      error: function(xhr) {
        feedback.textContent = 'Помилка сервера (HTTP ' + xhr.status + '). Спробуйте ще раз.';
        feedback.removeAttribute('hidden');
        feedback.className = 'cart-feedback is-error';
        btn.textContent = 'Додати в кошик';
      },
      complete: function() {
        btn.disabled = false;
      },
    });
  });
});
</script>

<?php endwhile; ?>
<?php get_footer(); ?>