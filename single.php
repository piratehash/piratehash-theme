<?php
/**
 * The template for displaying single posts
 *
 * @package PirateHash
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container container--narrow">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
                <header class="entry-header">
                    <?php
                    $categories = get_the_category();
                    if ( $categories ) :
                    ?>
                        <div class="entry-categories">
                            <?php foreach ( $categories as $category ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-card__category">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <div class="entry-meta">
                        <?php piratehash_posted_by(); ?>
                        <span class="sep">&bull;</span>
                        <?php piratehash_posted_on(); ?>
                        <span class="sep">&bull;</span>
                        <span class="reading-time"><?php echo piratehash_reading_time(); ?></span>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail( 'piratehash-hero' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'piratehash' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) :
                    ?>
                        <div class="entry-tags">
                            <span class="tags-label"><?php esc_html_e( 'Tags:', 'piratehash' ); ?></span>
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                                    #<?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="post-navigation">
                        <?php
                        the_post_navigation( array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'piratehash' ) . '</span><span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'piratehash' ) . '</span><span class="nav-title">%title</span>',
                        ) );
                        ?>
                    </div>
                </footer>
            </article>

            <?php
            // Author bio
            if ( get_the_author_meta( 'description' ) ) :
            ?>
                <div class="author-bio">
                    <div class="author-avatar">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
                    </div>
                    <div class="author-info">
                        <h4 class="author-name"><?php the_author(); ?></h4>
                        <p class="author-description"><?php the_author_meta( 'description' ); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();

