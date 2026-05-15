<?php
get_header();

$options        = get_option('hitboox_project_archive');
$col_desktop    = 2;
$col_tablet     = 2;
$col_mobile     = 1;
$gutter         = 30;
$style          = 'style-1';
$enable_filter  = '';
$posts_per_page = 10;

$check_platforms = isset($_GET['platforms']) && !empty($_GET['platforms']);
$check_genres    = isset($_GET['genres']) && !empty($_GET['genres']);
$wrap_class      = 'col-full';

if ($options && is_array($options)) {
    $style          = isset($options['archive_style']) ? $options['archive_style'] : 'style-1';
    $col_desktop    = isset($options['columns_desktop']) ? $options['columns_desktop'] : 2;
    $col_tablet     = isset($options['columns_tablet']) ? $options['columns_tablet'] : 2;
    $col_mobile     = isset($options['columns_mobile']) ? $options['columns_mobile'] : 1;
    $gutter         = isset($options['gutter']) ? $options['gutter'] : 30;
    $enable_filter  = isset($options['filter_style']) ? $options['filter_style'] : '';
    $posts_per_page = isset($options['posts_per_page']) ? $options['posts_per_page'] : $posts_per_page;
    $wrap_class     = isset($options['archive_width']) ? 'col-fluid' : 'col-full';

}
echo '<div class="project-archive-' . esc_attr($style) . ' ' . esc_attr($wrap_class) . '">'
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <header class="page-header">
                <?php
                if (hitboox_is_cmb2_activated()) {

                    if (isset($options['hitboox_project-content']) && !empty($options['hitboox_project-content'])) {
                        ?>
                        <div class="archive-description">
                            <?php echo hitboox_parse_text_editor( $options['hitboox_project-content']); ?>
                        </div>
                        <?php
                    }

                } else {
                    the_archive_title('<h1 class="entry-title">', '</h1>');
                    the_archive_description('<p class="archive-description">', '</p>');
                }
                ?>
            </header>
            <?php


            $archive_class = $enable_filter ? 'has-filter-' . $enable_filter : '';
            ?>
            <div class="project-archive-content <?php echo esc_attr($archive_class); ?>">
                <?php
                if ($enable_filter) {
                    $archive_link = apply_filters('hitboox_project_filters_archive_link', get_post_type_archive_link('hitboox_project'));

                    echo '<form method="get" class="hitboox-project-filter hitboox-project-' . esc_attr($archive_class) . '" action="' . esc_url($archive_link) . '">';
                    $args          = array(
                        'hide_empty' => false,
                        'order'      => 'ASC',
                        'number'     => 0,
                    );
                    $check_style_1 = $enable_filter == 'style-1';

                    $terms_platform    = get_terms('hitboox_project_platform', $args);
                    $platform_selected = $check_platforms ? '' : ($check_style_1 ? 'selected' : 'checked');

                    if (!is_wp_error($terms_platform) && !empty($terms_platform)) {
                        echo '<div class="filter-wrap"><div class="project-filter-title">' . esc_html__('Filter by platform', 'hitboox') . '</div>';
                        if ($check_style_1) {
                            echo '<select name="platforms">';
                            echo '<option ' . esc_attr($platform_selected) . ' value="">' . esc_html__('All Platforms', 'hitboox') . '</option>';
                        } else {
                            echo '<ul>';
                            echo '<li><label><input name="platforms" type="checkbox" ' . esc_attr($platform_selected) . ' value=""><span>' . esc_html__('All Platforms', 'hitboox') . '</span></label></li>';
                        }

                        foreach ($terms_platform as $term) {
                            $platform_selected = '';
                            if ($check_platforms) {
                                $platforms = explode(',', $_GET['platforms']);
                                if (in_array($term->slug, $platforms)) {
                                    $platform_selected = $check_style_1 ? 'selected' : 'checked';
                                }
                            }
                            if ($check_style_1) {
                                echo '<option ' . esc_attr($platform_selected) . ' value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                            } else {
                                echo '<li><label><input name="platforms" type="checkbox" ' . esc_attr($platform_selected) . ' value="' . esc_attr($term->slug) . '"><span>' . esc_html($term->name) . '</span></label></option>';
                            }
                        }
                        if ($check_style_1) {
                            echo '</select></div>';
                        } else {
                            echo '</ul></div>';
                        }
                    }

                    $terms_genre    = get_terms('hitboox_project_genre', $args);
                    $genre_selected = $check_genres ? '' : ($check_style_1 ? 'selected' : 'checked');
                    if (!is_wp_error($terms_genre) && !empty($terms_genre)) {
                        echo '<div class="filter-wrap"><div class="project-filter-title">' . esc_html__('Filter by genre', 'hitboox') . '</div>';
                        if ($check_style_1) {
                            echo '<select name="genres">';
                            echo '<option ' . esc_attr($genre_selected) . ' value="">' . esc_html__('All Genres', 'hitboox') . '</option>';
                        } else {
                            echo '<ul>';
                            echo '<li><label><input name="genres" type="checkbox" ' . esc_attr($genre_selected) . ' value=""><span>' . esc_html__('All Genres', 'hitboox') . '</span></label></li>';
                        }
                        foreach ($terms_genre as $term) {
                            $genre_selected = '';
                            if ($check_genres) {
                                $genres = explode(',', $_GET['genres']);
                                if (in_array($term->slug, $genres)) {
                                    $genre_selected = $check_style_1 ? 'selected' : 'checked';
                                }
                            }
                            if ($check_style_1) {
                                echo '<option ' . esc_attr($genre_selected) . ' value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                            } else {
                                echo '<li><label><input name="genres" type="checkbox" ' . esc_attr($genre_selected) . ' value="' . esc_attr($term->slug) . '"><span>' . esc_html($term->name) . '</span></label></option>';
                            }
                        }
                        if ($check_style_1) {
                            echo '</select></div>';
                        } else {
                            echo '</ul></div>';
                        }
                    }

                    echo '<div class="filter-wrap filter-wrap-button"><button type="submit" class="path-wrap-yes"><span class="hover-text" data-name=" ' . esc_html__('Filter', 'hitboox') . ' "><span>' . esc_html__('Filter', 'hitboox') . '</span></span></button></div>';
                    echo '</form>';

                }
                ?>

                <div class="archive-content-inner  <?php if($style == 'style-3') { echo 'elementor-project-style-3 elementor-style-effect-yes'; }  ?>">
                    <?php
                    $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $args_query = array(
                        'post_type'      => 'hitboox_project',
                        'posts_per_page' => $posts_per_page,
                        'paged'          => $paged,
                    );

                    if ($check_platforms || $check_genres) {
                        $args_query['tax_query'] = [];
                    }
                    if ($check_platforms && $check_genres) {
                        $args_query['tax_query']['relation'] = 'AND';
                    }
                    if ($check_platforms) {
                        $args_query['tax_query'][] = [
                            'taxonomy' => 'hitboox_project_platform',
                            'field'    => 'slug',
                            'terms'    => explode(',', $_GET['platforms']),
                            'operator' => 'IN'
                        ];
                    }

                    if ($check_genres) {
                        $args_query['tax_query'][] = [
                            'taxonomy' => 'hitboox_project_genre',
                            'field'    => 'slug',
                            'terms'    => explode(',', $_GET['genres']),
                            'operator' => 'IN'
                        ];
                    }

                    $the_query = new WP_Query($args_query);

                    if ($the_query->have_posts()) :
                        $current_column = 0;
                        echo '<div style="--gutter-width: ' . esc_attr($gutter) . 'px;" class="d-grid grid-columns-desktop-' . esc_attr($col_desktop) . ' grid-columns-tablet-' . esc_attr($col_tablet) . ' grid-columns-' . esc_attr($col_mobile) . '">';

                        while ($the_query->have_posts()) : $the_query->the_post();
                            global $post;
                            ?>
                            <div class="grid-item">
                                <?php get_template_part('template-parts/project/item-project', $style); ?>
                            </div>
                            <?php
                            $current_column++;

                            if ($style == 'style-3' && $current_column >= $col_desktop) {
                                $current_column = 0;
                                echo '</div>';
                                echo '<div style="--gutter-width: ' . esc_attr($gutter) . 'px;" class="d-grid grid-columns-desktop-' . esc_attr($col_desktop) . ' grid-columns-tablet-' . esc_attr($col_tablet) . ' grid-columns-' . esc_attr($col_mobile) . '">';
                            }
                        endwhile;
                        echo '</div>';
                        echo '<div class="pagination">';
                        echo paginate_links(array(
                            'type'      => 'list',
                            'current'   => max(1, get_query_var('paged')),
                            'total'     => $the_query->max_num_pages,
                            'prev_text' => esc_html__('', 'hitboox'),
                            'next_text' => esc_html__('', 'hitboox'),
                        ));
                        echo '</div>';

                        wp_reset_postdata();
                    else :
                        ?>
                        <div class="no-results not-found">
                            <h2 class="page-title"><?php esc_html_e('Nothing Found', 'hitboox'); ?></h2>
                            <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'hitboox'); ?></p>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->
<?php
echo '</div>';
get_footer();