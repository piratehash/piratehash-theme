<?php
/**
 * The footer template
 *
 * @package PirateHash
 * @since 1.0.0
 */
?>
    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-inner">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <div class="footer-column">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="footer-bottom">
                <div class="copyright">
                    <p>
                        &copy; <?php echo date( 'Y' ); ?> 
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                        <?php esc_html_e( '. All rights reserved.', 'piratehash' ); ?>
                    </p>
                </div>

                <div class="footer-links">
                    <?php if ( has_nav_menu( 'footer' ) ) : ?>
                        <nav class="footer-nav">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ) );
                            ?>
                        </nav>
                    <?php endif; ?>
                    <a href="https://primal.net/p/nprofile1qqsqdm608ftudey54u3l5ehjpun8naqlmsylle7mhjw6na8xxm63gqqzzfjsa" target="_blank" class="nostr-link">Follow on nostr</a>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

