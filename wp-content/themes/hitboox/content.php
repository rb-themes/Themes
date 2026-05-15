<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner <?php echo has_post_thumbnail() ? esc_attr('has-thumbnail') : ''; ?>">
        <?php hitboox_post_thumbnail('large', true, true); ?>
        <div class="post-content">
            <?php
            /**
             * Functions hooked in to hitboox_loop_post action.
             *
             * @see hitboox_post_header          - 15
             * @see hitboox_post_content         - 30
             */
            do_action('hitboox_loop_post');
            ?>
        </div>
    </div>
</article><!-- #post-## -->