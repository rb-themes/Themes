<?php
get_header(); ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
                ?>
                <div class="single-project-header"
                     style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">

                    <div class="project-header-content col-full">
                        <?php
                        the_title('<h1 class="alpha entry-title">', '</h1>');
                        ?>
                        <div class="single-project-platforms-wrap">
                            <h5><?php echo esc_html__('Available at', 'hitboox'); ?></h5>
                            <div class="single-project-platforms">
                                <?php
                                $terms = get_the_terms(get_the_ID(), 'hitboox_project_platform');
                                if (!is_wp_error($terms) && is_array($terms)) {
                                    foreach ($terms as $key => $term) {
                                        $term_link = get_post_meta(get_the_ID(), 'hitboox_platform_' . $term->slug, true) ? get_post_meta(get_the_ID(), 'hitboox_platform_' . $term->slug, true) : '#';
                                        $term_icon = get_term_meta($term->term_id, 'platforms_term_icon', true);
                                        if (is_wp_error($term_link)) {
                                            continue;
                                        }
                                        $icon = !empty($term_icon) ? '<span class="path-icon path-wrap-yes path-border-yes"><i class="' . esc_attr($term_icon) . '"></i></span>' : '';
                                        echo '<a href="' . esc_url($term_link) . '">' . $icon . '<span class="platform-name">' . $term->name . '</span></a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <?php if (hitboox_is_bcn_nav_activated()) {
                            echo '<div class="breadcrumb-listItem">';
                            bcn_display();
                            echo '</div>';
                        } ?>

                    </div>
                    <div class="hitboox-border-shape bottom-right"><svg viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg"><path d="M147.269 54.72L117.876 25.28C114.502 21.9015 109.919 20 105.145 20H0V60H160C155.226 60 150.642 58.0985 147.269 54.72Z"/><path d="M0 0V20H20C8.95435 20 0 11.0457 0 0Z"/></svg></div>

                </div>

                <div class="single-project-content">
                    <?php
                    the_content();
                    ?>
                </div>
            <?php
            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_footer();
