<?php
/**
 * WooCommerce Compatibility
 *
 * @package PirateHash
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get Bitcoin price from Mempool API
 * Cached for 5 minutes to avoid too many API calls
 */
function piratehash_get_btc_price() {
    $cached_price = get_transient( 'piratehash_btc_price' );
    
    if ( $cached_price !== false ) {
        return $cached_price;
    }
    
    $response = wp_remote_get( 'https://mempool.space/api/v1/prices', array(
        'timeout' => 5,
    ) );
    
    if ( is_wp_error( $response ) ) {
        // Return a fallback price if API fails
        return 100000;
    }
    
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    
    if ( isset( $data['USD'] ) ) {
        $price = (float) $data['USD'];
        // Cache for 5 minutes
        set_transient( 'piratehash_btc_price', $price, 5 * MINUTE_IN_SECONDS );
        return $price;
    }
    
    return 100000; // Fallback
}

/**
 * Convert USD to Sats
 */
function piratehash_usd_to_sats( $usd_amount ) {
    $btc_price = piratehash_get_btc_price();
    
    if ( $btc_price <= 0 ) {
        return 0;
    }
    
    // 1 BTC = 100,000,000 sats
    $sats = ( $usd_amount / $btc_price ) * 100000000;
    
    return round( $sats );
}

/**
 * Format sats with comma separators
 */
function piratehash_format_sats( $sats ) {
    return number_format( $sats ) . ' sats';
}

/**
 * Display price in sats on shop/archive pages
 */
function piratehash_price_html( $price_html, $product ) {
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    
    // For variable products, get min price
    if ( $product->is_type( 'variable' ) ) {
        $regular_price = $product->get_variation_price( 'min', true );
        $sale_price = $product->get_variation_sale_price( 'min', true );
    }
    
    if ( empty( $regular_price ) ) {
        return $price_html;
    }
    
    $sats_price = piratehash_usd_to_sats( (float) $regular_price );
    $sats_html = '<span class="price-sats">' . piratehash_format_sats( $sats_price ) . '</span>';
    
    // If on sale, show sale sats price
    if ( $product->is_on_sale() && ! empty( $sale_price ) ) {
        $sale_sats = piratehash_usd_to_sats( (float) $sale_price );
        $sats_html = '<span class="price-sats"><del>' . piratehash_format_sats( $sats_price ) . '</del> <ins>' . piratehash_format_sats( $sale_sats ) . '</ins></span>';
    }
    
    // Sats first (big, white), then fiat (small, gray)
    $fiat_html = '<span class="price-fiat">' . $price_html . '</span>';
    
    return $sats_html . '<br>' . $fiat_html;
}
add_filter( 'woocommerce_get_price_html', 'piratehash_price_html', 10, 2 );

/**
 * Add sats to cart item price
 */
function piratehash_cart_item_price( $price, $cart_item, $cart_item_key ) {
    $product_price = $cart_item['data']->get_price();
    $sats = piratehash_usd_to_sats( (float) $product_price );
    $sats_html = '<span class="price-sats">' . piratehash_format_sats( $sats ) . '</span>';
    $fiat_html = '<span class="price-fiat">' . $price . '</span>';
    return $sats_html . '<br>' . $fiat_html;
}
add_filter( 'woocommerce_cart_item_price', 'piratehash_cart_item_price', 10, 3 );

/**
 * Add sats to cart item subtotal
 */
function piratehash_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
    $product_subtotal = $cart_item['data']->get_price() * $cart_item['quantity'];
    $sats = piratehash_usd_to_sats( (float) $product_subtotal );
    $sats_html = '<span class="price-sats">' . piratehash_format_sats( $sats ) . '</span>';
    $fiat_html = '<span class="price-fiat">' . $subtotal . '</span>';
    return $sats_html . '<br>' . $fiat_html;
}
add_filter( 'woocommerce_cart_item_subtotal', 'piratehash_cart_item_subtotal', 10, 3 );

/**
 * Add sats to cart subtotal
 */
function piratehash_cart_subtotal( $subtotal, $compound, $cart ) {
    $cart_subtotal = $cart->get_subtotal();
    $sats = piratehash_usd_to_sats( (float) $cart_subtotal );
    $sats_html = '<span class="price-sats">' . piratehash_format_sats( $sats ) . '</span>';
    $fiat_html = '<span class="price-fiat">' . $subtotal . '</span>';
    return $sats_html . '<br>' . $fiat_html;
}
add_filter( 'woocommerce_cart_subtotal', 'piratehash_cart_subtotal', 10, 3 );

/**
 * Add sats to cart/checkout totals
 */
function piratehash_cart_total( $total ) {
    $cart_total = WC()->cart->get_total( 'edit' );
    $sats = piratehash_usd_to_sats( (float) $cart_total );
    $sats_html = '<span class="price-sats">' . piratehash_format_sats( $sats ) . '</span>';
    $fiat_html = '<span class="price-fiat">' . $total . '</span>';
    return $sats_html . '<br>' . $fiat_html;
}
add_filter( 'woocommerce_cart_totals_order_total_html', 'piratehash_cart_total', 10, 1 );

/**
 * Add sats to order review totals (checkout)
 */
function piratehash_order_formatted_line_subtotal( $subtotal, $item, $order ) {
    $item_total = $item->get_total();
    $sats = piratehash_usd_to_sats( (float) $item_total );
    $sats_html = '<span class="price-sats">' . piratehash_format_sats( $sats ) . '</span>';
    $fiat_html = '<span class="price-fiat">' . $subtotal . '</span>';
    return $sats_html . '<br>' . $fiat_html;
}
add_filter( 'woocommerce_order_formatted_line_subtotal', 'piratehash_order_formatted_line_subtotal', 10, 3 );

/**
 * Add sats to shipping cost
 */
function piratehash_cart_shipping_method_full_label( $label, $method ) {
    if ( $method->cost > 0 ) {
        $sats = piratehash_usd_to_sats( (float) $method->cost );
        $sats_html = ' <span class="price-sats">' . piratehash_format_sats( $sats ) . '</span>';
        $label .= $sats_html;
    }
    return $label;
}
add_filter( 'woocommerce_cart_shipping_method_full_label', 'piratehash_cart_shipping_method_full_label', 10, 2 );

/**
 * Add hero section to shop page
 */
function piratehash_shop_hero() {
    if ( is_shop() && ! is_search() ) :
    ?>
    <section class="hero">
        <div class="video-container">
            <video id="hero-video" class="hero-video" muted loop autoplay playsinline>
                <source src="https://cdn.satellite.earth/a50a79d384c04159af059859540d3a5f28dc1d51a0d90d71989a6d3d1b002c2e.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-overlay" id="video-overlay">
                <div class="container">
                    <h2>Bitcoin curiosities</h2>
                    <p>Premium bitcoin books, posters, and collectibles</p>
                    <button id="play-video-btn" class="play-btn" type="button">
                        <span class="play-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg></span>
                        <span class="play-text">Watch Video</span>
                    </button>
                </div>
            </div>
            <div class="video-click-overlay" id="video-click-overlay"></div>
        </div>
    </section>
    <?php
    endif;
}
add_action( 'woocommerce_before_main_content', 'piratehash_shop_hero', 5 );

/**
 * Add video modal to footer on shop page
 */
function piratehash_shop_video_modal() {
    if ( is_shop() && ! is_search() ) :
    ?>
    <div id="video-modal" class="video-modal">
        <div class="modal-content">
            <button class="modal-close" id="modal-close">&times;</button>
            <video id="modal-video" class="modal-video" controls>
                <source src="https://cdn.satellite.earth/a50a79d384c04159af059859540d3a5f28dc1d51a0d90d71989a6d3d1b002c2e.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
    <?php
    endif;
}
add_action( 'wp_footer', 'piratehash_shop_video_modal' );

/**
 * Remove default WooCommerce wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom WooCommerce wrapper
 */
function piratehash_woocommerce_wrapper_before() {
    ?>
    <main id="primary" class="site-main">
        <div class="container">
    <?php
}
add_action( 'woocommerce_before_main_content', 'piratehash_woocommerce_wrapper_before' );

function piratehash_woocommerce_wrapper_after() {
    ?>
        </div>
    </main>
    <?php
}
add_action( 'woocommerce_after_main_content', 'piratehash_woocommerce_wrapper_after' );

/**
 * Remove default WooCommerce sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Add custom shop sidebar
 */
function piratehash_woocommerce_sidebar() {
    if ( is_active_sidebar( 'shop-sidebar' ) ) {
        ?>
        <aside id="secondary" class="widget-area shop-sidebar">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </aside>
        <?php
    }
}
add_action( 'woocommerce_sidebar', 'piratehash_woocommerce_sidebar' );

/**
 * Products per row
 */
function piratehash_products_per_row() {
    return 3;
}
add_filter( 'loop_shop_columns', 'piratehash_products_per_row' );

/**
 * Products per page
 */
function piratehash_products_per_page() {
    return 12;
}
add_filter( 'loop_shop_per_page', 'piratehash_products_per_page' );

/**
 * Related products count
 */
function piratehash_related_products_args( $args ) {
    $args['posts_per_page'] = 3;
    $args['columns'] = 3;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'piratehash_related_products_args' );

/**
 * Cart fragments for AJAX
 */
function piratehash_cart_link_fragment( $fragments ) {
    ob_start();
    piratehash_cart_link();
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'piratehash_cart_link_fragment' );

/**
 * Cart link
 */
function piratehash_cart_link() {
    ?>
    <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'piratehash' ); ?>">
        <span class="cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </span>
        <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
            <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php endif; ?>
    </a>
    <?php
}

/**
 * Customize breadcrumb
 */
function piratehash_woocommerce_breadcrumb_defaults( $defaults ) {
    $defaults['delimiter']   = ' <span class="breadcrumb-sep">/</span> ';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb">';
    $defaults['wrap_after']  = '</nav>';
    return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'piratehash_woocommerce_breadcrumb_defaults' );

/**
 * Add sale badge custom text
 */
function piratehash_sale_flash( $html, $post, $product ) {
    return '<span class="onsale">' . esc_html__( 'Sale', 'piratehash' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'piratehash_sale_flash', 10, 3 );

/**
 * Remove SKU from product page
 */
add_filter( 'wc_product_sku_enabled', '__return_false' );

/**
 * Remove category from product meta
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/**
 * Remove additional information tab
 */
function piratehash_remove_additional_info_tab( $tabs ) {
    unset( $tabs['additional_information'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'piratehash_remove_additional_info_tab', 98 );

/**
 * Hide quantity field when stock is 1 or less
 */
function piratehash_hide_quantity_input( $return, $product ) {
    if ( $product->get_stock_quantity() <= 1 && $product->managing_stock() ) {
        return false;
    }
    return $return;
}
add_filter( 'woocommerce_quantity_input_args', 'piratehash_quantity_input_args', 10, 2 );

function piratehash_quantity_input_args( $args, $product ) {
    if ( $product->managing_stock() && $product->get_stock_quantity() <= 1 ) {
        $args['min_value'] = 1;
        $args['max_value'] = 1;
    }
    return $args;
}

/**
 * Add class to body when product has low stock
 */
function piratehash_body_class_low_stock( $classes ) {
    if ( is_product() ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product && is_object( $product ) && $product->managing_stock() && $product->get_stock_quantity() <= 1 ) {
            $classes[] = 'single-stock-low';
        }
    }
    return $classes;
}
add_filter( 'body_class', 'piratehash_body_class_low_stock' );

/**
 * Remove shop page title
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/**
 * Remove result count ("Showing all X results")
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Remove catalog ordering (sort select list)
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Add tweets section after products on shop page
 */
function piratehash_shop_tweets_section() {
    if ( ! is_shop() || is_search() ) {
        return;
    }
    
    $tweet_ids = array(
        '1217902429687156736',
        '1592360377740046337',
        '1767641867112341874',
        '1764972232839696458',
        '1592654884809355266',
        '1580896471745908736',
        '1764274116675211443',
        '1525890613224300544',
        '1360268871601709058',
        '1478718874229039117',
        '1407184153733390340',
        '1576479298705584129',
        '1590292708907945985',
        '1393594195693252612',
        '1450836359149768707',
        '1536703057790115843',
        '1233444643888672768',
        '1764250023104070019',
        '1352295159669653508',
    );
    ?>
    <section class="tweets-section">
        <div class="container">
            <h2 class="tweets-section__title">What people are saying</h2>
            <div class="tweets-wrapper">
                <?php foreach ( $tweet_ids as $tweet_id ) : ?>
                    <blockquote class="twitter-tweet" data-theme="dark">
                        <a href="https://twitter.com/x/status/<?php echo esc_attr( $tweet_id ); ?>"></a>
                    </blockquote>
                <?php endforeach; ?>
                <iframe src="https://nostrudel.ninja/n/nevent1qqsxd8tvtl4rncctyz4tq0tun9w4rp6hg4swhz48e9lm5js8xgxm2rspp4mhxue69uhkummn9ekx7mqppamhxue69uhkummnw3ezumt0d5q3camnwvaz7tmwdaehgu3wvf5hgcm0d9hx2u3wwdhkx6tpdspzpv6rt6ljhk3tmhfm84gqycw95qqvmy4xkwwk626q6kjhcmtd7rzrd9z82z" width="100%" height="600" frameborder="0" allowfullscreen></iframe>
                <iframe src="https://nostrudel.ninja/n/nevent1qqsfdh5c24g20xaa8h2rm7qssmck23a8eejxsqj4a9z7uz9mh3wlazgcnvvxq" width="100%" height="600" frameborder="0" allowfullscreen></iframe>
                    <iframe src="https://nostrudel.ninja/n/nevent1qqsd57hqg3yynsqaeyt2zhyf0clx2ulpnv62fdtm96vjdhuavtl4knc0w7p8v" width="100%" height="600" frameborder="0" allowfullscreen></iframe>
                    
            </div>
        </div>
    </section>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <?php
}
add_action( 'woocommerce_after_shop_loop', 'piratehash_shop_tweets_section', 20 );

