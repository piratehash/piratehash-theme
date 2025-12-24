<?php
/**
 * The template for displaying 404 pages
 *
 * @package PirateHash
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title">404</h1>
                <p class="page-subtitle"><?php esc_html_e( 'Arr! This treasure be lost to Davy Jones\' locker!', 'piratehash' ); ?></p>
            </header>

            <div class="page-content">
                <p><?php esc_html_e( 'The page ye be seekin\' has sailed off into the sunset. Perhaps it was plundered by rival pirates, or maybe ye just took a wrong turn at the reef.', 'piratehash' ); ?></p>
                
                <div class="error-actions">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
                        <?php esc_html_e( 'Return to Port', 'piratehash' ); ?>
                    </a>
                </div>

                <div class="error-search">
                    <h3><?php esc_html_e( 'Search for Treasure', 'piratehash' ); ?></h3>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();

