<?php
/**
 * PirateHash Theme Functions
 *
 * @package PirateHash
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define theme constants
 */
define( 'PIRATEHASH_VERSION', '1.0.0' );
define( 'PIRATEHASH_DIR', get_template_directory() );
define( 'PIRATEHASH_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function piratehash_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Set default thumbnail size
    set_post_thumbnail_size( 1200, 675, true );

    // Add custom image sizes
    add_image_size( 'piratehash-card', 600, 400, true );
    add_image_size( 'piratehash-hero', 1920, 1080, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'piratehash' ),
        'footer'    => esc_html__( 'Footer Menu', 'piratehash' ),
        'mobile'    => esc_html__( 'Mobile Menu', 'piratehash' ),
    ) );

    // Switch default core markup to output valid HTML5
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Set up the WordPress core custom background feature
    add_theme_support( 'custom-background', array(
        'default-color' => '12121a',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for core custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // Add support for responsive embedded content
    add_theme_support( 'responsive-embeds' );

    // Add support for Block Styles
    add_theme_support( 'wp-block-styles' );

    // Add support for full and wide align images
    add_theme_support( 'align-wide' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );

    // Add support for custom color palette in editor
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => esc_html__( 'White', 'piratehash' ),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
        array(
            'name'  => esc_html__( 'Light Gray', 'piratehash' ),
            'slug'  => 'light-gray',
            'color' => '#cccccc',
        ),
        array(
            'name'  => esc_html__( 'Gray', 'piratehash' ),
            'slug'  => 'gray',
            'color' => '#999999',
        ),
        array(
            'name'  => esc_html__( 'Dark Gray', 'piratehash' ),
            'slug'  => 'dark-gray',
            'color' => '#666666',
        ),
        array(
            'name'  => esc_html__( 'Darker Gray', 'piratehash' ),
            'slug'  => 'darker-gray',
            'color' => '#333333',
        ),
        array(
            'name'  => esc_html__( 'Near Black', 'piratehash' ),
            'slug'  => 'near-black',
            'color' => '#111111',
        ),
        array(
            'name'  => esc_html__( 'Black', 'piratehash' ),
            'slug'  => 'black',
            'color' => '#000000',
        ),
    ) );

    // Add WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'piratehash_setup' );

/**
 * Set the content width in pixels
 */
function piratehash_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'piratehash_content_width', 1200 );
}
add_action( 'after_setup_theme', 'piratehash_content_width', 0 );

/**
 * Register widget areas
 */
function piratehash_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'piratehash' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'piratehash' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'piratehash' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in footer column 1.', 'piratehash' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'piratehash' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here to appear in footer column 2.', 'piratehash' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'piratehash' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here to appear in footer column 3.', 'piratehash' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Shop Sidebar', 'piratehash' ),
        'id'            => 'shop-sidebar',
        'description'   => esc_html__( 'Add widgets here to appear in the shop sidebar.', 'piratehash' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'piratehash_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function piratehash_scripts() {
    // Google Fonts - Inter
    wp_enqueue_style(
        'piratehash-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'piratehash-style',
        get_stylesheet_uri(),
        array( 'piratehash-fonts' ),
        PIRATEHASH_VERSION
    );

    // Theme JavaScript
    wp_enqueue_script(
        'piratehash-navigation',
        PIRATEHASH_URI . '/assets/js/navigation.js',
        array(),
        PIRATEHASH_VERSION,
        true
    );

    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Sats conversion for WooCommerce blocks (cart/checkout)
    if ( class_exists( 'WooCommerce' ) && ( is_cart() || is_checkout() ) ) {
        wp_enqueue_script(
            'piratehash-sats-conversion',
            PIRATEHASH_URI . '/assets/js/sats-conversion.js',
            array(),
            PIRATEHASH_VERSION,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'piratehash_scripts' );

/**
 * Custom template tags for this theme
 */
function piratehash_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    echo '<span class="posted-on">' . $time_string . '</span>';
}

function piratehash_posted_by() {
    echo '<span class="byline"><span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span></span>';
}

function piratehash_entry_footer() {
    if ( 'post' === get_post_type() ) {
        $categories_list = get_the_category_list( ', ' );
        if ( $categories_list ) {
            printf( '<span class="cat-links">%s</span>', $categories_list );
        }

        $tags_list = get_the_tag_list( '', ', ' );
        if ( $tags_list ) {
            printf( '<span class="tags-links">%s</span>', $tags_list );
        }
    }
}

/**
 * Get reading time estimate
 */
function piratehash_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 );
    
    return sprintf(
        _n( '%d min read', '%d min read', $reading_time, 'piratehash' ),
        $reading_time
    );
}

/**
 * Custom excerpt length
 */
function piratehash_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'piratehash_excerpt_length' );

/**
 * Custom excerpt more
 */
function piratehash_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'piratehash_excerpt_more' );

/**
 * Add custom classes to body
 */
function piratehash_body_classes( $classes ) {
    // Add class for single posts with no featured image
    if ( is_single() && ! has_post_thumbnail() ) {
        $classes[] = 'no-featured-image';
    }

    // Add class for pages with sidebar
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page() ) {
        $classes[] = 'has-sidebar';
    }

    return $classes;
}
add_filter( 'body_class', 'piratehash_body_classes' );

/**
 * Add custom image sizes to media library
 */
function piratehash_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'piratehash-card' => __( 'Card Image', 'piratehash' ),
        'piratehash-hero' => __( 'Hero Image', 'piratehash' ),
    ) );
}
add_filter( 'image_size_names_choose', 'piratehash_custom_image_sizes' );

/**
 * Customize login page styles
 */
function piratehash_login_styles() {
    ?>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        
        body.login {
            background: #000000;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .login h1 a {
            background-image: none !important;
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            text-indent: 0;
            width: auto;
            height: auto;
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        
        .login h1 a:focus {
            box-shadow: none;
        }
        
        .login form,
        .login form#loginform,
        #loginform {
            background: rgba(0, 0, 0, 1) !important;
            background-color: rgba(0, 0, 0, 1) !important;
            border: 1px solid #333333 !important;
            border-radius: 8px;
            padding: 26px 24px;
        }
        
        .login label {
            color: #999999;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .login input[type="text"],
        .login input[type="password"],
        #user_login,
        #user_pass {
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid #444444 !important;
            border-radius: 0 !important;
            color: #ffffff !important;
            padding: 12px 0;
            font-size: 1rem;
            font-family: inherit;
            -webkit-text-fill-color: #ffffff !important;
            box-shadow: none !important;
        }
        
        #user_login {
            box-shadow: none !important;
        }
        
        .login input#user_pass,
        #user_pass {
            border-right: 1px none rgb(255, 255, 255) !important;
        }
        
        .login input[type="text"]:focus,
        .login input[type="password"]:focus {
            border-bottom-color: #ffffff !important;
            box-shadow: none !important;
            outline: none !important;
            background: transparent !important;
        }
        
        .login input:-webkit-autofill,
        .login input:-webkit-autofill:hover,
        .login input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #000000 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            border: none !important;
            border-bottom: 1px solid #444444 !important;
        }
        
        .login .button-primary,
        #wp-submit {
            background: #ffffff !important;
            border: 2px solid #ffffff !important;
            color: #000000 !important;
            text-shadow: none !important;
            box-shadow: none !important;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 14px 32px;
            height: auto;
            border-radius: 4px;
            transition: all 0.2s ease;
            width: 100%;
            margin-top: 26px !important;
        }
        
        .login .button-primary:hover,
        .login .button-primary:focus {
            background: transparent !important;
            border: 2px solid #ffffff !important;
            color: #ffffff !important;
        }
        
        .login #backtoblog,
        .login #nav {
            padding: 0;
        }
        
        .login #backtoblog a,
        .login #nav a {
            color: #666666;
            font-size: 0.875rem;
        }
        
        .login #backtoblog a:hover,
        .login #nav a:hover {
            color: #ffffff;
        }
        
        .login .message,
        .login .success {
            background: #000000;
            border: 1px solid #333333;
            border-left: 4px solid #ffffff;
            color: #ffffff;
            border-radius: 4px;
        }
        
        .login .notice-error,
        .login #login_error {
            background: #000000;
            border: 1px solid #333333;
            border-left: 4px solid #cc1818;
            color: #ffffff;
            border-radius: 4px;
        }
        
        .login #login_error a {
            color: #ffffff;
        }
        
        .login .privacy-policy-page-link {
            display: none;
        }
        
        .login .forgetmenot label {
            color: #666666;
            font-size: 0.8125rem;
        }
        
        .login input[type="checkbox"] {
            background: #000000;
            border: 1px solid #333333;
        }
        
        .login input[type="checkbox"]:checked::before {
            content: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%23ffffff' d='M14.83 4.89l1.34.94-5.81 8.38H9.02L5.78 9.67l1.34-1.25 2.57 2.4z'/%3E%3C/svg%3E");
        }
        
        .wp-pwd .button.wp-hide-pw {
            color: #666666;
        }
        
        .wp-pwd .button.wp-hide-pw:hover {
            color: #ffffff;
        }
        
        .wp-pwd .button.wp-hide-pw:focus {
            box-shadow: none;
            outline: none;
        }
    </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'piratehash_login_styles' );

function piratehash_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'piratehash_login_logo_url' );

function piratehash_login_logo_text() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'piratehash_login_logo_text' );

/**
 * Add preload for fonts
 */
function piratehash_preload_fonts() {
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php
}
add_action( 'wp_head', 'piratehash_preload_fonts', 1 );

/**
 * Remove WordPress version from head
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Include additional functionality
 */
// Custom WooCommerce functions
if ( class_exists( 'WooCommerce' ) ) {
    require_once PIRATEHASH_DIR . '/includes/woocommerce.php';
}

