<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner blog-list1">
        <div class="post-content">
            <?php if (has_post_thumbnail()) { ?>
                <div class="post-left">
                    <?php hitboox_post_thumbnail('large'); ?>
                </div>
            <?php } ?>
            <div class="post-right">
                <div class="entry-meta">
                    <?php hitboox_post_meta(['show_cat' => true, 'show_date' => true, 'show_author' => false, 'show_comment' => false]); ?>
                </div>
                <?php
                the_title('<h3 class="gamma entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
                ?>
                <div class="entry-excerpt"><?php the_excerpt(); ?></div>
                <div class="more-link-wrap">
                    <?php echo '<a class="more-link" href="' . get_permalink() . '"><span class="hover-text" data-name=" ' . esc_html__('Continue Reading', 'hitboox') . ' "><span>' . esc_html__('Continue Reading', 'hitboox') . ' </span></span></a>'; ?>
                </div>
            </div>
        </div>
    </div>
</article><!-- #post-## -->