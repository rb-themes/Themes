<?php
get_header(); ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php
            while (have_posts()) :
                the_post();

                $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '';
                ?>

                <div class="single-services-header" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                    <div class="services-header-content col-full">
                        <?php
                        the_title('<h1 class="alpha entry-title">', '</h1>');
                        ?>

                        <?php if (hitboox_is_bcn_nav_activated()) {
                            echo '<div class="breadcrumb-listItem">';
                            bcn_display();
                            echo '</div>';
                        } ?>

                    </div>
                    <div class="hitboox-border-shape bottom-right"><svg viewBox="0 0 160 60" xmlns="http://www.w3.org/2000/svg"><path d="M147.269 54.72L117.876 25.28C114.502 21.9015 109.919 20 105.145 20H0V60H160C155.226 60 150.642 58.0985 147.269 54.72Z"/><path d="M0 0V20H20C8.95435 20 0 11.0457 0 0Z"/></svg></div>
                </div>

                <div class="single-services-content">
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
