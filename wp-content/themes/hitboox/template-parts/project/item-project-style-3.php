<div class="project-item project-style-3">
    <div class="project-post-thumbnail">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large'); ?>
        <?php endif; ?>
    </div><!-- .post-thumbnail -->
    <div class="project-content">
        <?php
        $logo_url = get_post_meta(get_the_ID(), 'project_image_class', true);
        if ($logo_url && !empty($logo_url)) {
            echo '<div class="project-logo-wrap"><img class="project-logo" src="' . esc_url($logo_url) . '" alt="project logo"></div>';
        }
        ?>
        <h4 class="project-title delta"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        <?php echo Hitboox_Project::getInstance()->get_term_genre(get_the_ID()); ?>
        <div class="project-content-meta">
            <div class="project-excerpt"><?php the_excerpt(); ?></div>
            <div class="project-bottom-wrap">
                <a class="project-button button path-wrap-yes path-border-yes" href="<?php the_permalink() ?>">
                    <span class="hover-text" data-name="<?php esc_attr_e('Details', 'hitboox') ?>">
                    <span><?php echo esc_html__('Details', 'hitboox'); ?></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
