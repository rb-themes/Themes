<div class="post-inner blog-grid">
    <?php hitboox_post_thumbnail('large', true, true); ?>
    <div class="post-content">
        <div class="entry-content">
            <?php
                the_title('<h3 class="omega entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            ?>
        </div>
    </div>
</div>