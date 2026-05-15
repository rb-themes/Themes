<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-content">
        <?php
        /**
         * Functions hooked in to hitboox_single_post_top action
         *
         * */
        do_action('hitboox_single_post_top');

        /**
         * Functions hooked in to hitboox_single_post action
         * @see hitboox_post_header         - 5
         * @see hitboox_post_thumbnail      - 10
         * @see hitboox_post_content        - 30
         */
        do_action('hitboox_single_post');

        /**
         * Functions hooked in to hitboox_single_post_bottom action
         *
         * @see hitboox_post_taxonomy      - 5
         * @see hitboox_single_author      - 10
         * @see hitboox_post_nav            - 15
         * @see hitboox_display_comments    - 20
         */
        do_action('hitboox_single_post_bottom');
        ?>

    </div>

</article><!-- #post-## -->
