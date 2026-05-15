<?php
get_header(); ?>
    <div id="primary" class="content">
        <main id="main" class="site-main">
            <div class="error-404 not-found">
                <div class="page-content">
                    <div class="error-content">
                        <img src="<?php echo get_theme_file_uri('assets/images/404/404-img1.png') ?>" class="error-img1" alt="<?php echo esc_attr__('404 Page', 'hitboox') ?>">
                        <img src="<?php echo get_theme_file_uri('assets/images/404/404-img2.png') ?>" class="error-img2" alt="<?php echo esc_attr__('404 Page', 'hitboox') ?>">
                        <img src="<?php echo get_theme_file_uri('assets/images/404/404-img3.png') ?>" class="error-img3" alt="<?php echo esc_attr__('404 Page', 'hitboox') ?>">
                        <div class="text-404"><?php esc_html_e('404', 'hitboox'); ?></div>
                        <div class="error-title"><?php esc_html_e('Page Not Found', 'hitboox'); ?></div>
                        <p class="error-text"><?php esc_html_e('We\'re not being able to find the page you\'re looking for', 'hitboox') ?></p>
                        <div class="button-error">
                            <a href="javascript: history.go(-1)" class="button go-back path-wrap-yes">
                                <span class="hover-text" data-name="<?php esc_attr_e('back To Homepage', 'hitboox'); ?>"><span> <?php esc_html_e('back To Homepage', 'hitboox'); ?></span></span>
                            </a>
                        </div>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php

get_footer();
