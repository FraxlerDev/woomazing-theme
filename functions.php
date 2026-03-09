<?php
error_log('WOMAZING FUNCTIONS LOADED');
/**
 * Womazing Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ═══ WALKER (тут, а не в header.php) ═══ */
class Womazing_Mobile_Menu_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = is_array( $item->classes ) && in_array( 'current-menu-item', $item->classes ) ? ' class="is-active"' : '';
        $output .= '<a href="' . esc_url( $item->url ) . '"' . $classes . ' onclick="closeMobileNav()">'
                 . esc_html( $item->title ) . '</a>';
    }
    function start_lvl( &$output, $depth = 0, $args = null ) {}
    function end_lvl( &$output, $depth = 0, $args = null ) {}
    function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/* ═══ THEME SETUP ═══ */
function womazing_setup() {
    load_theme_textdomain( 'womazing', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form','comment-form','comment-list','gallery','caption','style','script' ] );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( [
        'primary' => __( 'Головне меню', 'womazing' ),
        'footer'  => __( 'Футер меню', 'womazing' ),
    ] );
}
add_action( 'after_setup_theme', 'womazing_setup' );

/* ═══ ENQUEUE SCRIPTS & STYLES ═══ */
function womazing_enqueue() {
    wp_enqueue_style( 'google-fonts',
        'https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap',
        [], null );
    wp_enqueue_style( 'womazing-style', get_stylesheet_uri(), ['google-fonts'], '1.0.0' );
    wp_enqueue_script( 'womazing-main', get_template_directory_uri() . '/assets/js/main.js',
        ['jquery'], '1.0.0', true );

    /* Єдина точка конфігурації JS: і callback, і кошик */
    wp_localize_script( 'womazing-main', 'womAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'womazing_callback' ),
    ]);

    wp_localize_script( 'womazing-main', 'womCart', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'womazing_add_to_cart' ),
        'cart_url' => function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/'),
        'count'    => ( function_exists('WC') && WC() && is_a(WC()->cart, 'WC_Cart') ) ? WC()->cart->get_cart_contents_count() : 0,
    ]);

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'womazing_enqueue' );

/* ═══ WOOCOMMERCE ═══ */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_filter( 'loop_shop_columns', function() { return 3; } );
add_filter( 'loop_shop_per_page', function() { return 9; } );



add_filter( 'woocommerce_breadcrumb_defaults', function( $defaults ) {
    $defaults['delimiter']   = ' — ';
    $defaults['wrap_before'] = '<nav class="breadcrumbs">';
    $defaults['wrap_after']  = '</nav>';
    $defaults['before']      = '';
    $defaults['after']       = '';
    return $defaults;
});

function womazing_loop_product_thumbnail() {
    global $product;
    echo '<div class="product-photo">';
    echo '<a href="' . esc_url( get_permalink() ) . '">';
    echo woocommerce_get_product_thumbnail( 'woocommerce_single' );
    echo '<div class="product-overlay">';
    echo '<svg viewBox="0 0 36 26" fill="none"><path d="M1 13h34M24 1l11 12L24 25" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>';
    echo '</div></a></div>';
}
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'womazing_loop_product_thumbnail', 10 );

add_action( 'woocommerce_before_shop_loop_item_title', function() {
    echo '<div class="product-info">';
}, 5 );
add_action( 'woocommerce_after_shop_loop_item', function() {
    echo '</div>';
}, 20 );

add_filter( 'woocommerce_product_loop_title_classes', function() {
    return 'product-name';
});

/* ═══ SIDEBARS ═══ */
function womazing_sidebars() {
    register_sidebar([
        'name'          => __( 'Footer Widget Area', 'womazing' ),
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget__title">',
        'after_title'   => '</h4>',
    ]);
}
add_action( 'widgets_init', 'womazing_sidebars' );

/* ═══ AJAX CALLBACK ═══ */
function womazing_handle_callback() {
    check_ajax_referer( 'womazing_callback', 'nonce' );
    $name  = sanitize_text_field( $_POST['name']  ?? '' );
    $phone = sanitize_text_field( $_POST['phone'] ?? '' );
    $email = sanitize_email( $_POST['email'] ?? '' );

    if ( ! $name || ! $phone ) {
        wp_send_json_error( [ 'message' => "Будь ласка, заповніть Ім'я та Телефон" ] );
    }

    $to      = get_option( 'admin_email' );
    $subject = "Замовлення зворотного дзвінка — $name";
    $body    = "Ім'я: $name\nТелефон: $phone\nEmail: $email";
    wp_mail( $to, $subject, $body );

    wp_send_json_success( [ 'message' => 'Дякуємо! Ми зателефонуємо вам незабаром.' ] );
}
add_action( 'wp_ajax_womazing_callback',        'womazing_handle_callback' );
add_action( 'wp_ajax_nopriv_womazing_callback', 'womazing_handle_callback' );

/* ═══ AJAX ADD TO CART ═══ */
function womazing_ajax_add_to_cart() {
    check_ajax_referer( 'womazing_add_to_cart', 'nonce' );

    if ( ! function_exists('WC') || ! WC() || ! is_a( WC()->cart, 'WC_Cart' ) ) {
        wp_send_json_error( ['message' => 'Кошик недоступний. Спробуйте оновити сторінку.'] );
    }

    $product_id = absint( $_POST['product_id'] ?? 0 );
    $quantity   = absint( $_POST['quantity']   ?? 1 );

    if ( ! $product_id ) {
        wp_send_json_error( ['message' => 'Невірний товар'] );
    }

    $product = wc_get_product( $product_id );
    if ( ! $product || ! $product->is_purchasable() ) {
        wp_send_json_error( ['message' => 'Товар недоступний для покупки'] );
    }

    $added = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( $added ) {
        WC()->cart->calculate_totals();
        wp_send_json_success([
            'message'    => sprintf( '«%s» додано до кошика', $product->get_name() ),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'count'      => WC()->cart->get_cart_contents_count(),
            'cart_url'   => wc_get_cart_url(),
        ]);
    } else {
        wp_send_json_error( ['message' => 'Не вдалося додати товар. Спробуйте ще раз.'] );
    }
}
add_action( 'wp_ajax_womazing_add_to_cart',        'womazing_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_womazing_add_to_cart', 'womazing_ajax_add_to_cart' );

/* ═══ HELPERS ═══ */
function womazing_phone() {
    return get_option( 'womazing_phone', '+38 (044) 555-41-12' );
}
function womazing_email_contact() {
    return get_option( 'womazing_contact_email', 'hello@womazing.com' );
}

/* ═══ THEME OPTIONS ═══ */
function womazing_register_settings() {
    register_setting( 'womazing_options', 'womazing_phone' );
    register_setting( 'womazing_options', 'womazing_contact_email' );
    register_setting( 'womazing_options', 'womazing_hero_title_1' );
    register_setting( 'womazing_options', 'womazing_hero_desc_1' );
    register_setting( 'womazing_options', 'womazing_hero_title_2' );
    register_setting( 'womazing_options', 'womazing_hero_desc_2' );
    register_setting( 'womazing_options', 'womazing_hero_title_3' );
    register_setting( 'womazing_options', 'womazing_hero_desc_3' );
}
add_action( 'admin_init', 'womazing_register_settings' );

function womazing_options_page() {
    add_menu_page(
        'Налаштування Womazing', 'Womazing',
        'manage_options', 'womazing-settings',
        'womazing_options_page_html',
        'dashicons-store', 60
    );
}
add_action( 'admin_menu', 'womazing_options_page' );

function womazing_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    ?>
    <div class="wrap">
        <h1>Налаштування теми Womazing</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'womazing_options' ); ?>
            <table class="form-table">
                <tr><th>Телефон</th><td><input type="text" name="womazing_phone" value="<?php echo esc_attr( womazing_phone() ); ?>" class="regular-text"></td></tr>
                <tr><th>Email для зв'язку</th><td><input type="email" name="womazing_contact_email" value="<?php echo esc_attr( womazing_email_contact() ); ?>" class="regular-text"></td></tr>
                <tr><th colspan="2"><h2>Hero слайд 1</h2></th></tr>
                <tr><th>Заголовок</th><td><input type="text" name="womazing_hero_title_1" value="<?php echo esc_attr( get_option('womazing_hero_title_1','Нові надходження цього сезону') ); ?>" class="large-text"></td></tr>
                <tr><th>Опис</th><td><textarea name="womazing_hero_desc_1" class="large-text" rows="3"><?php echo esc_textarea( get_option('womazing_hero_desc_1','Витончені поєднання та оксамитові відтінки.') ); ?></textarea></td></tr>
                <tr><th colspan="2"><h2>Hero слайд 2</h2></th></tr>
                <tr><th>Заголовок</th><td><input type="text" name="womazing_hero_title_2" value="<?php echo esc_attr( get_option('womazing_hero_title_2','Щось новеньке. Ми чекали тебе.') ); ?>" class="large-text"></td></tr>
                <tr><th>Опис</th><td><textarea name="womazing_hero_desc_2" class="large-text" rows="3"><?php echo esc_textarea( get_option('womazing_hero_desc_2','Настав час нових ідей та свіжих фарб!') ); ?></textarea></td></tr>
                <tr><th colspan="2"><h2>Hero слайд 3</h2></th></tr>
                <tr><th>Заголовок</th><td><input type="text" name="womazing_hero_title_3" value="<?php echo esc_attr( get_option('womazing_hero_title_3','Вмикай новий сезон з WOMAZING') ); ?>" class="large-text"></td></tr>
                <tr><th>Опис</th><td><textarea name="womazing_hero_desc_3" class="large-text" rows="3"><?php echo esc_textarea( get_option('womazing_hero_desc_3','Ми оновили асортимент — легендарні колекції та новинки.') ); ?></textarea></td></tr>
            </table>
            <?php submit_button( 'Зберегти налаштування' ); ?>
        </form>
    </div>
    <?php
}

/* ═══ IMAGE SIZES ═══ */
add_image_size( 'womazing-product', 350, 478, true );
add_image_size( 'womazing-hero',    410, 646, true );
add_image_size( 'womazing-team',    729, 487, true );
add_image_size( 'womazing-about',   442, 547, true );

// Вимкнути стиснення зображень WordPress (100% якість)
add_filter( 'jpeg_quality', function() { return 100; } );
add_filter( 'wp_editor_set_quality', function() { return 100; } );