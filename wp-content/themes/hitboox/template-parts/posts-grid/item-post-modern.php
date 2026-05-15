<div class="post-inner blog-modern">
    <?php hitboox_post_thumbnail('large'); ?>
    <div class="post-content">
        <div class="entry-header">
            <?php
            $categories_list = get_the_category_list(' ');
            if ($categories_list) {
                echo '<div class="categories-link">' . $categories_list . '</div>';
            }
            the_title('<h3 class="omega entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            ?>
            <div class="entry-excerpt"><?php the_excerpt(); ?></div>
        </div>
    </div>
</div>