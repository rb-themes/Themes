<div class="service-inner service-style-inner">
    <div class="service-content">
        <div class="service-content-inner">
            <h5 class="service-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <div class="service-content-bottom">
                <div class="description">
                    <?php echo get_the_excerpt(); ?>
                </div>
                <a class="service-button button path-wrap-yes path-border-yes" href="<?php the_permalink() ?>">
                    <span class="hover-text" data-name="<?php esc_attr_e('Learn More', 'hitboox') ?>">
                        <span><?php echo esc_html__('Learn More', 'hitboox'); ?></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <?php if (has_post_thumbnail()) : ?>
        <div class="service-post-thumbnail">
            <?php the_post_thumbnail('medium_large'); ?>
        </div>
    <?php endif; ?>
</div>
