<?php
/**
 * The template for displaying archive pages
 *
 * @package PirateHash
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <?php
            the_archive_title( '<h1 class="page-title">', '</h1>' );
            the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', get_post_type() );
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination( array(
                'prev_text' => '&larr; ' . esc_html__( 'Previous', 'piratehash' ),
                'next_text' => esc_html__( 'Next', 'piratehash' ) . ' &rarr;',
            ) );
            ?>

        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();

