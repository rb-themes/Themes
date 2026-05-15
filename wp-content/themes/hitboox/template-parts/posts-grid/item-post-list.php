<div class="post-inner blog-list">
    <div class="post-left">
        <?php if (has_post_thumbnail()) { ?>
            <?php hitboox_post_thumbnail('large'); ?>
        <?php } ?>
    </div>
    <div class="post-right">
        <div class="post-content">
            <div class="entry-meta">
                <?php hitboox_post_meta(['show_cat' => true, 'show_date' => true, 'show_author' => false, 'show_comment' => false]); ?>
            </div>
            <?php
                the_title('<h3 class="gamma entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            ?>
            <div class="entry-excerpt"><?php the_excerpt(); ?></div>
        </div>
    </div>
</div>