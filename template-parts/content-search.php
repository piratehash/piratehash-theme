<?php
/**
 * Template part for displaying results in search pages
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
            <span class="post-card__type"><?php echo get_post_type_object( get_post_type() )->labels->singular_name; ?></span>
            <span class="post-card__date"><?php echo get_the_date(); ?></span>
        </div>

        <h2 class="post-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>

        <div class="post-card__excerpt">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="btn btn--secondary">
            <?php esc_html_e( 'View', 'piratehash' ); ?>
        </a>
    </div>
</article>

