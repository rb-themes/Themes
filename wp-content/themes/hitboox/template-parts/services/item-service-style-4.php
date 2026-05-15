<div class="service-inner">
    <?php
    $icon_url = get_post_meta(get_the_ID(), 'services_icon_class', true);
    $features = get_post_meta(get_the_ID(), 'services_features_repeat_group', true);
    if ($icon_url && !empty($icon_url)) {
        echo '<div class="icon-wrap"><img class="service-icon" src="' . esc_url($icon_url) . '" alt="service icon"></div>';
    }
    ?>
    <h4 class="service-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

    <?php if (has_post_thumbnail()) : ?>
        <div class="service-post-thumbnail">
            <?php the_post_thumbnail('medium_large'); ?>
        </div>
    <?php endif; ?>
    <div class="description">
        <?php echo get_the_excerpt(); ?>
    </div>
    <?php
    if (!empty($features)) {
        echo '<div class="service-features">';
        foreach ($features as $index => $feature) {
            echo '<div class="service-feature-item"><span class="count">' . esc_html(str_pad($index + 1, 2, "0", STR_PAD_LEFT)) . '.</span><span class="value">' . esc_html($feature['title']) . '</span></div>';
        }
        echo '</div>';
    }
    ?>
    <div class="service-content-bottom">
        <a class="service-button button path-wrap-yes" href="<?php the_permalink() ?>">
                <span class="hover-text" data-name="<?php esc_attr_e('Learn More', 'hitboox') ?>">
                    <span><?php echo esc_html__('Learn More', 'hitboox'); ?></span>
                </span>
        </a>
    </div>
</div>
