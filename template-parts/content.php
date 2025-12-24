<?php
/**
 * Template part for displaying posts in a grid
 *
 * @package PirateHash
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-card__thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'piratehash-card' ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-card__content">
        <div class="post-card__meta">
            <?php
            $categories = get_the_category();
            if ( $categories ) :
                $category = $categories[0];
            ?>
                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-card__category">
                    <?php echo esc_html( $category->name ); ?>
                </a>
            <?php endif; ?>
            
            <span class="post-card__date"><?php echo get_the_date(); ?></span>
        </div>

        <h2 class="post-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>

        <div class="post-card__excerpt">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="btn btn--secondary">
            <?php esc_html_e( 'Read More', 'piratehash' ); ?>
        </a>
    </div>
</article>

