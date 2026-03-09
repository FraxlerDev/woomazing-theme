/* ═══ WOMAZING MAIN JS ═══ */
(function($) {
  'use strict';

  $(document).ready(function() {

  /* ── Hero Slider + touch swipe ── */
  var curSlide   = 0;
  var heroSlides = $('.hero__slide');
  var heroDots   = $('.slider-dots .dot');
  var heroImgs   = $('.hero__main-img');

  function goSlide(n) {
    heroSlides.eq(curSlide).removeClass('is-active');
    heroDots.eq(curSlide).removeClass('is-active');
    heroImgs.eq(curSlide).removeClass('is-active');
    curSlide = ((n % heroSlides.length) + heroSlides.length) % heroSlides.length;
    heroSlides.eq(curSlide).addClass('is-active');
    heroDots.eq(curSlide).addClass('is-active');
    heroImgs.eq(curSlide).addClass('is-active');
  }
  window.goSlide = goSlide;

  if ( heroSlides.length ) {
    setInterval(function() { goSlide(curSlide + 1); }, 5000);

    /* Swipe на мобільних */
    var heroEl  = document.querySelector('.hero');
    var touchX  = 0;
    if (heroEl) {
      heroEl.addEventListener('touchstart', function(e) {
        touchX = e.changedTouches[0].clientX;
      }, { passive: true });
      heroEl.addEventListener('touchend', function(e) {
        var dx = e.changedTouches[0].clientX - touchX;
        if (Math.abs(dx) > 50) goSlide(curSlide + (dx < 0 ? 1 : -1));
      }, { passive: true });
    }
  }

  /* ── Team Gallery ── */
  var curGal  = 0;
  var gSlides = $('.gallery-slide');
  var gDots   = $('.gallery-dot');

  function goGallery(n) {
    gSlides.eq(curGal).removeClass('is-active');
    gDots.eq(curGal).removeClass('is-active');
    curGal = ((n % gSlides.length) + gSlides.length) % gSlides.length;
    gSlides.eq(curGal).addClass('is-active');
    gDots.eq(curGal).addClass('is-active');
  }
  window.goGallery  = goGallery;
  window.prevGallery = function() { goGallery(curGal - 1); };
  window.nextGallery = function() { goGallery(curGal + 1); };

  /* ── Scroll to main ── */
  window.scrollToMain = function() {
    var $main = $('#mainContent');
    if ($main.length) {
      $('html, body').animate({ scrollTop: $main.offset().top }, 700);
    }
  };

  /* ── Sticky header ── */
  var $sticky = $('#stickyHeader');
  $(window).on('scroll.sticky', function() {
    $sticky.toggleClass('is-visible', $(window).scrollTop() > 500);
  });

  /* ── Scroll reveal ── */
  if ( 'IntersectionObserver' in window ) {
    var revObs = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting) {
          e.target.classList.add('is-visible');
          revObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });
    document.querySelectorAll('.js-fade').forEach(function(el) { revObs.observe(el); });
  } else {
    $('.js-fade').addClass('is-visible');
  }

  /* ── Mobile nav ── */
  var $mobileNav = $('#mobileNav');
  var $burgers   = $('.burger');

  /* ── Scrollbar-width compensation (prevents layout jump on lock) ── */
  function lockScroll() {
    /* scrollbar-gutter: stable в CSS вже резервує місце — padding не потрібен */
    document.body.style.overflow = 'hidden';
  }
  function unlockScroll() {
    document.body.style.overflow = '';
  }

  window.openMobileNav = function() {
    $mobileNav.addClass('is-open');
    $burgers.addClass('is-open');
    lockScroll();
  };
  window.closeMobileNav = function() {
    $mobileNav.removeClass('is-open');
    $burgers.removeClass('is-open');
    unlockScroll();
  };

  /* ── Popup ── */
  var $overlay = $('#popupOverlay');

  window.openPopup = function() {
    $overlay.addClass('is-open');
    lockScroll();
  };
  window.closePopup = function() {
    $overlay.removeClass('is-open');
    unlockScroll();
    setTimeout(function() {
      $('#popupForm').show();
      $('#popupSuccess').removeClass('is-show');
      $('#cbName, #cbEmail, #cbPhone').val('');
    }, 350);
  };

  $overlay.on('click', function(e) {
    if ($(e.target).is($overlay)) window.closePopup();
  });

  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') { window.closePopup(); window.closeMobileNav(); }
  });

  /* ── Popup AJAX submit ── */
  $('#cbSubmit').on('click', function() {
    var name  = $('#cbName').val().trim();
    var phone = $('#cbPhone').val().trim();
    var email = $('#cbEmail').val().trim();

    if (!name || !phone) {
      alert("Будь ласка, заповніть Ім'я та Телефон");
      return;
    }

    $(this).prop('disabled', true).text('Надсилаємо...');

    $.post(womAjax.url, {
      action: 'womazing_callback',
      nonce:  womAjax.nonce,
      name:   name,
      phone:  phone,
      email:  email,
    })
    .done(function(r) {
      if (r.success) {
        $('#popupForm').hide();
        $('#popupSuccess').addClass('is-show');
      } else {
        alert(r.data.message || 'Помилка. Спробуйте ще раз.');
      }
    })
    .fail(function() {
      alert('Помилка з\'єднання. Спробуйте ще раз.');
    })
    .always(function() {
      $('#cbSubmit').prop('disabled', false).text('Надіслати');
    });
  });

  /* ── WooCommerce: price class fix ── */
  $('.woocommerce-Price-amount').closest('.price').find('del .woocommerce-Price-amount')
    .addClass('price-old');
  $('.woocommerce-Price-amount').closest('.price').find('ins .woocommerce-Price-amount')
    .addClass('price-new');

  /* ── Ініціалізація badge при завантаженні ── */
  if (typeof womCart !== 'undefined') {
    window.updateCartBadge(womCart.count);
  }

  }); // end document.ready

})(jQuery);

/* ── Глобальна функція оновлення badge кошика ── */
window.updateCartBadge = function(count) {
  var badges = document.querySelectorAll('.cart-count');
  badges.forEach(function(b) {
    b.textContent = count;
    if (count > 0) {
      b.classList.add('is-visible');
    } else {
      b.classList.remove('is-visible');
    }
  });
};

/* ── Вибір розміру ── */
window.selectSize = function(btn) {
  document.querySelectorAll('.size-btn').forEach(function(b) { b.classList.remove('is-active'); });
  btn.classList.add('is-active');
};

/* ── Вибір кольору ── */
window.selectColor = function(btn) {
  document.querySelectorAll('.color-btn').forEach(function(b) { b.classList.remove('is-active'); });
  btn.classList.add('is-active');
};

/* ── Додати в кошик (AJAX) ── */
window.addToCart = function(btn) {
  var productId = btn.getAttribute('data-product-id');
  var nonce     = btn.getAttribute('data-nonce');
  var qty       = parseInt(document.getElementById('product-qty')?.value || 1, 10);

  btn.disabled = true;
  var originalText = btn.textContent;
  btn.textContent = 'Додаємо...';

  jQuery.post(
    (typeof womCart !== 'undefined' ? womCart.ajax_url : womAjax.url),
    {
      action:     'womazing_add_to_cart',
      nonce:      nonce,
      product_id: productId,
      quantity:   qty,
    },
    function(r) {
      if (r.success) {
        var count = r.data.count || r.data.cart_count || 0;
        window.updateCartBadge(count);
        btn.textContent = 'Додано ✓';
        setTimeout(function() {
          btn.textContent = originalText;
          btn.disabled    = false;
        }, 2000);
      } else {
        btn.textContent = originalText;
        btn.disabled    = false;
        alert(r.data.message || 'Помилка. Спробуйте ще раз.');
      }
    }
  ).fail(function() {
    btn.textContent = originalText;
    btn.disabled    = false;
    alert('Помилка з\'єднання.');
  });
};
/* ═══ ICON HOVER SWAP ═══ */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.icon-hover-swap').forEach(function(img) {
    var original = img.src;
    var hover = img.getAttribute('data-hover');
    if (hover) {
      img.addEventListener('mouseenter', function() { img.src = hover; });
      img.addEventListener('mouseleave', function() { img.src = original; });
    }
  });
});