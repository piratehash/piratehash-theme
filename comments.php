<?php
/**
 * The template for displaying comments
 *
 * @package PirateHash
 * @since 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                esc_html( _n( '%1$s Comment', '%1$s Comments', $comment_count, 'piratehash' ) ),
                number_format_i18n( $comment_count )
            );
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
            ) );
            ?>
        </ol>

        <?php
        the_comments_navigation();

        if ( ! comments_open() ) :
        ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'piratehash' ); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form( array(
        'class_form'         => 'comment-form',
        'title_reply'        => esc_html__( 'Leave a Comment', 'piratehash' ),
        'title_reply_to'     => esc_html__( 'Reply to %s', 'piratehash' ),
        'cancel_reply_link'  => esc_html__( 'Cancel Reply', 'piratehash' ),
        'label_submit'       => esc_html__( 'Post Comment', 'piratehash' ),
        'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'piratehash' ) . '</label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
    ) );
    ?>

</div><!-- #comments -->

