<?php

if (!function_exists('hitboox_before_content')) {
    /**
     * Before Content
     * Wraps all WooCommerce content in wrappers which match the theme markup
     *
     * @return  void
     * @since   1.0.0
     */
    function hitboox_before_content()
    {
        echo <<<HTML
<div id="primary" class="content-area">
    <main id="main" class="site-main">
HTML;

    }
}


if (!function_exists('hitboox_after_content')) {
    /**
     * After Content
     * Closes the wrapping divs
     *
     * @return  void
     * @since   1.0.0
     */
    function hitboox_after_content()
    {
        echo <<<HTML
	</main><!-- #main -->
</div><!-- #primary -->
HTML;

        do_action('hitboox_sidebar');
    }
}

if (!function_exists('hitboox_woo_cart_available')) {
    /**
     * Validates whether the Woo Cart instance is available in the request
     *
     * @return bool
     * @since 2.6.0
     */
    function hitboox_woo_cart_available()
    {
        $woo = WC();
        return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
    }
}

if (!function_exists('hitboox_cart_link_fragment')) {
    /**
     * Cart Fragments
     * Ensure cart contents update when products are added to the cart via AJAX
     *
     * @param array $fragments Fragments to refresh via AJAX.
     *
     * @return array            Fragments to refresh via AJAX
     */
    function hitboox_cart_link_fragment($fragments)
    {
        ob_start();
        hitboox_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        ob_start();

        return $fragments;
    }
}

if (!function_exists('hitboox_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function hitboox_cart_link()
    {
        if (!hitboox_woo_cart_available()) {
            return;
        }
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'hitboox'); ?>">
            <?php if (WC()->cart->get_cart_contents_count() > 0) { ?>
                <span class="count"><?php echo wp_kses_data(sprintf('%d', WC()->cart->get_cart_contents_count())); ?></span>
            <?php } ?>
        </a>
        <?php
    }
}

class Hitboox_Custom_Walker_Category extends Walker_Category
{

    public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0)
    {
        /** This filter is documented in wp-includes/category-template.php */
        $cat_name = apply_filters(
            'list_cats',
            esc_attr($category->name),
            $category
        );

        // Don't generate an element if the category name is empty.
        if (!$cat_name) {
            return;
        }

        $link = '<a class="pf-value" href="' . esc_url(get_term_link($category)) . '" data-val="' . esc_attr($category->slug) . '" data-title="' . esc_attr($category->name) . '" ';
        if ($args['use_desc_for_title'] && !empty($category->description)) {
            /**
             * Filters the category description for display.
             *
             * @param string $description Category description.
             * @param object $category Category object.
             *
             * @since 1.2.0
             *
             */
            $link .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
        }

        $link .= '>';
        $link .= $cat_name . '</a>';

        if (!empty($args['feed_image']) || !empty($args['feed'])) {
            $link .= ' ';

            if (empty($args['feed_image'])) {
                $link .= '(';
            }

            $link .= '<a href="' . esc_url(get_term_feed_link($category->term_id, $category->taxonomy, $args['feed_type'])) . '"';

            if (empty($args['feed'])) {
                $alt = ' alt="' . sprintf(esc_html__('Feed for all posts filed under %s', 'hitboox'), $cat_name) . '"';
            } else {
                $alt = ' alt="' . $args['feed'] . '"';
                $name = $args['feed'];
                $link .= empty($args['title']) ? '' : $args['title'];
            }

            $link .= '>';

            if (empty($args['feed_image'])) {
                $link .= $name;
            } else {
                $link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
            }
            $link .= '</a>';

            if (empty($args['feed_image'])) {
                $link .= ')';
            }
        }

        if (!empty($args['show_count'])) {
            $link .= ' (' . number_format_i18n($category->count) . ')';
        }
        if ('list' == $args['style']) {
            $output .= "\t<li";
            $css_classes = array(
                'cat-item',
                'cat-item-' . $category->term_id,
            );

            if (!empty($args['current_category'])) {
                // 'current_category' can be an array, so we use `get_terms()`.
                $_current_terms = get_terms(
                    $category->taxonomy,
                    array(
                        'include' => $args['current_category'],
                        'hide_empty' => false,
                    )
                );

                foreach ($_current_terms as $_current_term) {
                    if ($category->term_id == $_current_term->term_id) {
                        $css_classes[] = 'current-cat pf-active';
                    } elseif ($category->term_id == $_current_term->parent) {
                        $css_classes[] = 'current-cat-parent';
                    }
                    while ($_current_term->parent) {
                        if ($category->term_id == $_current_term->parent) {
                            $css_classes[] = 'current-cat-ancestor';
                            break;
                        }
                        $_current_term = get_term($_current_term->parent, $category->taxonomy);
                    }
                }
            }

            /**
             * Filters the list of CSS classes to include with each category in the list.
             *
             * @param array $css_classes An array of CSS classes to be applied to each list item.
             * @param object $category Category data object.
             * @param int $depth Depth of page, used for padding.
             * @param array $args An array of wp_list_categories() arguments.
             *
             * @since 4.2.0
             *
             * @see wp_list_categories()
             *
             */
            $css_classes = implode(' ', apply_filters('category_css_class', $css_classes, $category, $depth, $args));

            $output .= ' class="' . $css_classes . '"';
            $output .= ">$link\n";
        } elseif (isset($args['separator'])) {
            $output .= "\t$link" . $args['separator'] . "\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    }
}

if (!function_exists('hitboox_show_categories_dropdown')) {
    function hitboox_show_categories_dropdown()
    {
        static $id = 0;
        $args = array(
            'hide_empty' => 1,
            'parent' => 0
        );
        $terms = get_terms('product_cat', $args);
        if (!empty($terms) && !is_wp_error($terms)) {
            ?>
            <div class="search-by-category input-dropdown">
                <div class="input-dropdown-inner hitboox-scroll-content">
                    <a href="#" data-val="0"><span><?php esc_html_e('All category', 'hitboox'); ?></span></a>
                    <?php
                    $args_dropdown = array(
                        'id' => 'product_cat' . $id++,
                        'show_count' => 0,
                        'class' => 'dropdown_product_cat_ajax',
                        'show_option_none' => esc_html__('All category', 'hitboox'),
                    );
                    wc_product_dropdown_categories($args_dropdown);
                    ?>
                    <div class="list-wrapper hitboox-scroll">
                        <ul class="hitboox-scroll-content">
                            <li class="d-none">
                                <a href="#" data-val="0"><?php esc_html_e('All category', 'hitboox'); ?></a></li>
                            <?php
                            if (!apply_filters('hitboox_show_only_parent_categories_dropdown', false)) {
                                $args_list = array(
                                    'title_li' => false,
                                    'taxonomy' => 'product_cat',
                                    'use_desc_for_title' => false,
                                    'walker' => new Hitboox_Custom_Walker_Category(),
                                );
                                wp_list_categories($args_list);
                            } else {
                                foreach ($terms as $term) {
                                    ?>
                                    <li>
                                        <a href="#"
                                           data-val="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('hitboox_product_search')) {
    /**
     * Display Product Search
     *
     * @return void
     * @uses  hitboox_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function hitboox_product_search()
    {
        if (hitboox_is_woocommerce_activated()) {
            static $index = 0;
            $index++;
            ?>
            <div class="site-search ajax-search">
                <div class="widget woocommerce widget_product_search">
                    <div class="ajax-search-result d-none"></div>
                    <form method="get" class="woocommerce-product-search"
                          action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="screen-reader-text"
                               for="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"><?php esc_html_e('Search for:', 'hitboox'); ?></label>
                        <input type="search"
                               id="woocommerce-product-search-field-<?php echo isset($index) ? absint($index) : 0; ?>"
                               class="search-field"
                               placeholder="<?php echo esc_attr__('Search products&hellip;', 'hitboox'); ?>"
                               autocomplete="off" value="<?php echo get_search_query(); ?>" name="s"/>
                        <button type="submit" value="<?php echo esc_attr_x('Search', 'submit button', 'hitboox'); ?>"><i
                                    class="hitboox-icon-search-1"></i><?php echo esc_html_x('Search', 'submit button', 'hitboox'); ?>
                        </button>
                        <input type="hidden" name="post_type" value="product"/>
                        <?php hitboox_show_categories_dropdown(); ?>
                    </form>
                </div>
            </div>
            <?php
        }
    }
}

if (!function_exists('hitboox_header_cart')) {
    /**
     * Display Header Cart
     *
     * @return void
     * @uses  hitboox_is_woocommerce_activated() check if WooCommerce is activated
     * @since  1.0.0
     */
    function hitboox_header_cart()
    {
        if (hitboox_is_woocommerce_activated()) {
            if (!hitboox_get_theme_option('show_header_cart', true)) {
                return;
            }
            ?>
            <div class="site-header-cart menu">
                <?php hitboox_cart_link(); ?>
                <?php

                if (!apply_filters('woocommerce_widget_cart_is_hidden', is_cart() || is_checkout())) {

                    if (hitboox_get_theme_option('header_cart_dropdown', 'side') == 'side') {
                        add_action('wp_footer', 'hitboox_header_cart_side');
                    } else {
                        the_widget('WC_Widget_Cart', 'title=');
                    }
                }
                ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('hitboox_header_cart_side')) {
    function hitboox_header_cart_side()
    {
        if (hitboox_is_woocommerce_activated()) {
            ?>
            <div class="site-header-cart-side">
                <div class="cart-side-heading">
                    <span class="cart-side-title"><?php echo esc_html__('Shopping cart', 'hitboox'); ?></span>
                    <a href="#" class="close-cart-side"><?php echo esc_html__('close', 'hitboox') ?></a></div>
                <?php the_widget('WC_Widget_Cart', 'title='); ?>
            </div>
            <div class="cart-side-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('hitboox_upsell_display')) {
    /**
     * Upsells
     * Replace the default upsell function with our own which displays the correct number product columns
     *
     * @return  void
     * @since   1.0.0
     * @uses    woocommerce_upsell_display()
     */
    function hitboox_upsell_display()
    {
        $columns = apply_filters('hitboox_upsells_columns', 4);
        woocommerce_upsell_display(-1, $columns);
    }
}

if (!function_exists('hitboox_product_columns_wrapper')) {
    /**
     * Product columns wrapper
     *
     * @return  void
     * @since   2.2.0
     */
    function hitboox_product_columns_wrapper()
    {
        $columns = hitboox_loop_columns();
        echo '<div class="columns-' . absint($columns) . '">';
    }
}

if (!function_exists('hitboox_loop_columns')) {
    /**
     * Default loop columns on product archives
     *
     * @return integer products per row
     * @since  1.0.0
     */
    function hitboox_loop_columns()
    {
        $columns = 3; // 3 products per row

        if (function_exists('wc_get_default_products_per_row')) {
            $columns = wc_get_default_products_per_row();
        }

        return apply_filters('hitboox_loop_columns', $columns);
    }
}

if (!function_exists('hitboox_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close
     *
     * @return  void
     * @since   2.2.0
     */
    function hitboox_product_columns_wrapper_close()
    {
        echo '</div>';
    }
}

if (!function_exists('hitboox_shop_messages')) {
    /**
     * ThemeBase shop messages
     *
     * @since   1.4.4
     * @uses    hitboox_do_shortcode
     */
    function hitboox_shop_messages()
    {
        if (!is_checkout()) {
            echo hitboox_do_shortcode('woocommerce_messages');
        }
    }
}

if (!function_exists('hitboox_woocommerce_pagination')) {
    /**
     * ThemeBase WooCommerce Pagination
     * WooCommerce disables the product pagination inside the woocommerce_product_subcategories() function
     * but since ThemeBase adds pagination before that function is excuted we need a separate function to
     * determine whether or not to display the pagination.
     *
     * @since 1.4.4
     */
    function hitboox_woocommerce_pagination()
    {
        if (woocommerce_products_will_display()) {
            woocommerce_pagination();
        }
    }
}

if (!function_exists('hitboox_sticky_single_add_to_cart')) {
    /**
     * Sticky Add to Cart
     *
     * @since 2.3.0
     */
    function hitboox_sticky_single_add_to_cart()
    {
        global $product;

        if (!is_product()) {
            return;
        }

        $show = false;

        if ($product->is_purchasable() && $product->is_in_stock()) {
            $show = true;
        } else if ($product->is_type('external')) {
            $show = true;
        }

        if (!$show) {
            return;
        }

        $params = apply_filters(
            'hitboox_sticky_add_to_cart_params', array(
                'trigger_class' => 'entry-summary',
            )
        );

        wp_localize_script('hitboox-sticky-add-to-cart', 'hitboox_sticky_add_to_cart_params', $params);
        ?>

        <section class="hitboox-sticky-add-to-cart">
            <div class="col-full">
                <div class="hitboox-sticky-add-to-cart__content">
                    <?php echo woocommerce_get_product_thumbnail(); ?>
                    <div class="hitboox-sticky-add-to-cart__content-product-info">
						<span class="hitboox-sticky-add-to-cart__content-title"><?php esc_html_e('You\'re viewing:', 'hitboox'); ?>
							<strong><?php the_title(); ?></strong></span>
                        <span class="hitboox-sticky-add-to-cart__content-price"><?php echo sprintf('%s', $product->get_price_html()); ?></span>
                        <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                    </div>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                       class="hitboox-sticky-add-to-cart__content-button button alt">
                        <?php echo esc_html($product->add_to_cart_text()); ?>
                    </a>
                </div>
            </div>
        </section><!-- .hitboox-sticky-add-to-cart -->
        <?php
    }
}

if (!function_exists('hitboox_woocommerce_product_list_add_to_cart')) {
    function hitboox_woocommerce_product_list_add_to_cart()
    {
        ?>
        <div class="product-caption-bottom">
            <?php
            woocommerce_template_loop_add_to_cart();
            hitboox_wishlist_button();
            hitboox_compare_button();
            hitboox_quickview_button();
            ?>
        </div>
        <?php
    }
}

if (!function_exists('hitboox_woocommerce_product_model')) {
    function hitboox_woocommerce_product_model()
    {

        $unit = get_post_meta(get_the_ID(), '_model', true);
        if (empty($unit)) {
            return;
        }
        ?>
        <div class="product-mode"><?php echo esc_html__('Model: ', 'hitboox'); ?><span
                    class="value"><?php echo esc_html($unit); ?></span></div>
        <?php
    }
}

if (!function_exists('hitboox_product_label')) {
    function hitboox_product_label()
    {
        global $product;

        $output = array();

        if ($product->is_on_sale()) {

            $percentage = '';

            if ($product->get_type() == 'variable') {

                $available_variations = $product->get_variation_prices();
                $max_percentage = 0;

                foreach ($available_variations['regular_price'] as $key => $regular_price) {
                    $sale_price = $available_variations['sale_price'][$key];

                    if ($sale_price < $regular_price) {
                        $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);

                        if ($percentage > $max_percentage) {
                            $max_percentage = $percentage;
                        }
                    }
                }

                $percentage = $max_percentage;
            } elseif (($product->get_type() == 'simple' || $product->get_type() == 'external')) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
            }

            if ($percentage) {
                $output[] = '<span class="onsale">' . -$percentage . '%' . '</span>';
            } else {
                $output[] = '<span class="onsale">' . esc_html__('Sale!', 'hitboox') . '</span>';
            }
        }

        if ($output) {
            echo implode('', $output);
        }
    }
}
add_filter('woocommerce_sale_flash', 'hitboox_product_label', 10);

if (!function_exists('hitboox_template_loop_product_thumbnail')) {
    function hitboox_template_loop_product_thumbnail($size = 'woocommerce_thumbnail', $deprecated1 = 0, $deprecated2 = 0)
    {
        global $product;
        if (!$product) {
            return '';
        }
        $gallery = $product->get_gallery_image_ids();
        $hover_skin = hitboox_get_theme_option('woocommerce_product_hover', 'none');
        if ($hover_skin == 'none' || count($gallery) <= 0) {
            echo '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';

            return '';
        }
        $image_featured = '<div class="product-image">' . $product->get_image('shop_catalog') . '</div>';
        $image_featured .= '<div class="product-image second-image">' . wp_get_attachment_image($gallery[0], 'shop_catalog') . '</div>';

        echo <<<HTML
<div class="product-img-wrap {$hover_skin}">
    <div class="inner">
        {$image_featured}
    </div>
</div>
HTML;
    }
}


if (!function_exists('hitboox_woocommerce_single_product_image_thumbnail_html')) {
    function hitboox_woocommerce_single_product_image_thumbnail_html($image, $attachment_id)
    {
        return wc_get_gallery_image_html($attachment_id, true);
    }
}

if (!function_exists('woocommerce_template_loop_product_title')) {

    /**
     * Show the product title in the product loop.
     */
    function woocommerce_template_loop_product_title()
    {
        echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url_raw(get_the_permalink()) . '">' . get_the_title() . '</a></h3>';
    }
}

if (!function_exists('hitboox_woocommerce_get_product_category')) {
    function hitboox_woocommerce_get_product_category()
    {
        global $product;
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted-in">', '</div>');
    }
}

if (!function_exists('hitboox_woocommerce_get_product_description')) {
    function hitboox_woocommerce_get_product_description()
    {
        global $post;

        $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

        if ($short_description) {
            ?>
            <div class="short-description">
                <?php echo sprintf('%s', $short_description); ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('hitboox_woocommerce_get_product_short_description')) {
    function hitboox_woocommerce_get_product_short_description()
    {
        global $post;
        $short_description = wp_trim_words(apply_filters('woocommerce_short_description', $post->post_excerpt), 20);
        if ($short_description) {
            ?>
            <div class="short-description">
                <?php echo sprintf('%s', $short_description); ?>
            </div>
            <?php
        }
    }
}

if (!function_exists('hitboox_header_wishlist')) {
    function hitboox_header_wishlist()
    {
        if (function_exists('yith_wcwl_count_all_products')) {
            if (!hitboox_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist"
                   href="<?php echo esc_url(get_permalink(get_option('yith_wcwl_wishlist_page_id'))); ?>">
                    <i class="hitboox-icon-wishlist"></i>
                    <span class="count"><?php echo esc_html(yith_wcwl_count_all_products()); ?></span>
                </a>
            </div>
            <?php
        } elseif (function_exists('woosw_init')) {
            if (!hitboox_get_theme_option('show_header_wishlist', true)) {
                return;
            }
            $key = WPCleverWoosw::get_key();

            $class = WPCleverWoosw::get_count($key) > 0 ? 'count' : 'count hide';
            ?>
            <div class="site-header-wishlist">
                <a class="header-wishlist" href="<?php echo esc_url(WPCleverWoosw::get_url($key, true)); ?>">
                    <i class="hitboox-icon-wishlist"></i>
                    <span class="<?php echo esc_attr($class); ?>"><?php echo sprintf('%d', WPCleverWoosw::get_count($key)); ?></span>
                </a>
            </div>
            <?php
        }
    }
}

if (!function_exists('woosw_ajax_update_count') && function_exists('woosw_init')) {
    function woosw_ajax_update_count()
    {
        $key = WPCleverWoosw::get_key();

        wp_send_json(array(
            'text' => esc_html(_nx('Item', 'Items', WPCleverWoosw::get_count($key), 'items wishlist', 'hitboox'))
        ));
    }

    add_action('wp_ajax_woosw_ajax_update_count', 'woosw_ajax_update_count');
    add_action('wp_ajax_nopriv_woosw_ajax_update_count', 'woosw_ajax_update_count');
}

if (!function_exists('hitboox_button_grid_list_layout')) {
    function hitboox_button_grid_list_layout()
    {
        ?>
        <div class="gridlist-toggle desktop-hide-down">
            <a href="<?php echo esc_url(add_query_arg('layout', 'grid')); ?>" id="grid"
               class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? '' : 'active'; ?>"
               title="<?php echo esc_attr__('Grid View', 'hitboox'); ?>"><i class="hitboox-icon-grid"></i></a>
            <a href="<?php echo esc_url(add_query_arg('layout', 'list')); ?>" id="list"
               class="<?php echo isset($_GET['layout']) && $_GET['layout'] == 'list' ? 'active' : ''; ?>"
               title="<?php echo esc_attr__('List View', 'hitboox'); ?>"><i class="hitboox-icon-list"></i></a>
        </div>
        <?php
    }
}


if (!function_exists('hitboox_woocommerce_list_get_rating')) {
    function hitboox_woocommerce_list_show_rating()
    {
        global $product;
        echo wc_get_rating_html($product->get_average_rating());
    }
}

if (!function_exists('hitboox_woocommerce_group_action')) {
    function hitboox_woocommerce_group_action()
    {
        ?>
        <div class="group-action">
            <div class="shop-action">
                <?php
                hitboox_wishlist_button();
                hitboox_compare_button();
                hitboox_quickview_button();
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('hitboox_single_product_extra')) {
    function hitboox_single_product_extra()
    {
        global $product;
        $product_extra = hitboox_get_theme_option('single_product_content_meta', '');
        $product_extra = get_post_meta($product->get_id(), '_extra_info', true) !== '' ? get_post_meta($product->get_id(), '_extra_info', true) : $product_extra;
        if ($product_extra !== '') {
            echo '<div class="hitboox-single-product-extra">' . html_entity_decode($product_extra) . '</div>';
        }
    }
}

if (!function_exists('hitboox_button_shop_canvas')) {
    function hitboox_button_shop_canvas()
    {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle" aria-expanded="false">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 14.875C0.046875 14.3594 0.328125 14.0781 0.84375 14.0312H2.95312C3.14062 13.4453 3.46875 12.9766 3.9375 12.625C4.42969 12.25 4.99219 12.0625 5.625 12.0625C6.25781 12.0625 6.82031 12.25 7.3125 12.625C7.78125 12.9766 8.10938 13.4453 8.29688 14.0312H17.1562C17.6719 14.0781 17.9531 14.3594 18 14.875C17.9531 15.3906 17.6719 15.6719 17.1562 15.7188H8.29688C8.10938 16.3047 7.78125 16.7734 7.3125 17.125C6.82031 17.5 6.25781 17.6875 5.625 17.6875C4.99219 17.6875 4.42969 17.5 3.9375 17.125C3.46875 16.7734 3.14062 16.3047 2.95312 15.7188H0.84375C0.328125 15.6719 0.046875 15.3906 0 14.875ZM4.5 14.875C4.5 15.2031 4.60547 15.4727 4.81641 15.6836C5.02734 15.8945 5.29688 16 5.625 16C5.95312 16 6.22266 15.8945 6.43359 15.6836C6.64453 15.4727 6.75 15.2031 6.75 14.875C6.75 14.5469 6.64453 14.2773 6.43359 14.0664C6.22266 13.8555 5.95312 13.75 5.625 13.75C5.29688 13.75 5.02734 13.8555 4.81641 14.0664C4.60547 14.2773 4.5 14.5469 4.5 14.875ZM11.25 9.25C11.25 9.57812 11.3555 9.84766 11.5664 10.0586C11.7773 10.2695 12.0469 10.375 12.375 10.375C12.7031 10.375 12.9727 10.2695 13.1836 10.0586C13.3945 9.84766 13.5 9.57812 13.5 9.25C13.5 8.92188 13.3945 8.65234 13.1836 8.44141C12.9727 8.23047 12.7031 8.125 12.375 8.125C12.0469 8.125 11.7773 8.23047 11.5664 8.44141C11.3555 8.65234 11.25 8.92188 11.25 9.25ZM12.375 6.4375C13.0078 6.4375 13.5703 6.625 14.0625 7C14.5312 7.35156 14.8594 7.82031 15.0469 8.40625H17.1562C17.6719 8.45312 17.9531 8.73438 18 9.25C17.9531 9.76562 17.6719 10.0469 17.1562 10.0938H15.0469C14.8594 10.6797 14.5312 11.1484 14.0625 11.5C13.5703 11.875 13.0078 12.0625 12.375 12.0625C11.7422 12.0625 11.1797 11.875 10.6875 11.5C10.2188 11.1484 9.89062 10.6797 9.70312 10.0938H0.84375C0.328125 10.0469 0.046875 9.76562 0 9.25C0.046875 8.73438 0.328125 8.45312 0.84375 8.40625H9.70312C9.89062 7.82031 10.2188 7.35156 10.6875 7C11.1797 6.625 11.7422 6.4375 12.375 6.4375ZM6.75 4.75C7.07812 4.75 7.34766 4.64453 7.55859 4.43359C7.76953 4.22266 7.875 3.95312 7.875 3.625C7.875 3.29688 7.76953 3.02734 7.55859 2.81641C7.34766 2.60547 7.07812 2.5 6.75 2.5C6.42188 2.5 6.15234 2.60547 5.94141 2.81641C5.73047 3.02734 5.625 3.29688 5.625 3.625C5.625 3.95312 5.73047 4.22266 5.94141 4.43359C6.15234 4.64453 6.42188 4.75 6.75 4.75ZM9.42188 2.78125H17.1562C17.6719 2.82812 17.9531 3.10937 18 3.625C17.9531 4.14063 17.6719 4.42188 17.1562 4.46875H9.42188C9.23438 5.05469 8.90625 5.52344 8.4375 5.875C7.94531 6.25 7.38281 6.4375 6.75 6.4375C6.11719 6.4375 5.55469 6.25 5.0625 5.875C4.59375 5.52344 4.26562 5.05469 4.07812 4.46875H0.84375C0.328125 4.42188 0.046875 4.14063 0 3.625C0.046875 3.10937 0.328125 2.82812 0.84375 2.78125H4.07812C4.26562 2.19531 4.59375 1.72656 5.0625 1.375C5.55469 1 6.11719 0.8125 6.75 0.8125C7.38281 0.8125 7.94531 1 8.4375 1.375C8.90625 1.72656 9.23438 2.19531 9.42188 2.78125Z"/>
                </svg>
                <span><?php esc_html_e('Filter', 'hitboox'); ?></span></a>
            <?php
        }
    }
}

if (!function_exists('hitboox_render_woocommerce_shop_canvas')) {
    function hitboox_render_woocommerce_shop_canvas()
    {
        if (is_active_sidebar('sidebar-woocommerce-shop') && hitboox_is_product_archive()) {
            ?>
            <div id="hitboox-canvas-filter" class="hitboox-canvas-filter">
                <div class="hitboox-canvas-header">
                    <span class="filter-close"></span>
                </div>

                <div class="hitboox-canvas-filter-wrap">
                    <?php if (hitboox_get_theme_option('woocommerce_archive_layout') == 'canvas' || hitboox_get_theme_option('woocommerce_archive_layout') == 'menu' || hitboox_get_theme_option('woocommerce_archive_layout') == 'fullwidth') {
                        dynamic_sidebar('sidebar-woocommerce-shop');
                    }
                    ?>
                </div>
            </div>
            <div class="hitboox-overlay-filter"></div>
            <?php
        }
    }
}

if (!function_exists('hitboox_button_shop_dropdown')) {
    function hitboox_button_shop_dropdown()
    {
        if (is_active_sidebar('sidebar-woocommerce-shop')) { ?>
            <a href="#" class="filter-toggle-dropdown" aria-expanded="false">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 14.875C0.046875 14.3594 0.328125 14.0781 0.84375 14.0312H2.95312C3.14062 13.4453 3.46875 12.9766 3.9375 12.625C4.42969 12.25 4.99219 12.0625 5.625 12.0625C6.25781 12.0625 6.82031 12.25 7.3125 12.625C7.78125 12.9766 8.10938 13.4453 8.29688 14.0312H17.1562C17.6719 14.0781 17.9531 14.3594 18 14.875C17.9531 15.3906 17.6719 15.6719 17.1562 15.7188H8.29688C8.10938 16.3047 7.78125 16.7734 7.3125 17.125C6.82031 17.5 6.25781 17.6875 5.625 17.6875C4.99219 17.6875 4.42969 17.5 3.9375 17.125C3.46875 16.7734 3.14062 16.3047 2.95312 15.7188H0.84375C0.328125 15.6719 0.046875 15.3906 0 14.875ZM4.5 14.875C4.5 15.2031 4.60547 15.4727 4.81641 15.6836C5.02734 15.8945 5.29688 16 5.625 16C5.95312 16 6.22266 15.8945 6.43359 15.6836C6.64453 15.4727 6.75 15.2031 6.75 14.875C6.75 14.5469 6.64453 14.2773 6.43359 14.0664C6.22266 13.8555 5.95312 13.75 5.625 13.75C5.29688 13.75 5.02734 13.8555 4.81641 14.0664C4.60547 14.2773 4.5 14.5469 4.5 14.875ZM11.25 9.25C11.25 9.57812 11.3555 9.84766 11.5664 10.0586C11.7773 10.2695 12.0469 10.375 12.375 10.375C12.7031 10.375 12.9727 10.2695 13.1836 10.0586C13.3945 9.84766 13.5 9.57812 13.5 9.25C13.5 8.92188 13.3945 8.65234 13.1836 8.44141C12.9727 8.23047 12.7031 8.125 12.375 8.125C12.0469 8.125 11.7773 8.23047 11.5664 8.44141C11.3555 8.65234 11.25 8.92188 11.25 9.25ZM12.375 6.4375C13.0078 6.4375 13.5703 6.625 14.0625 7C14.5312 7.35156 14.8594 7.82031 15.0469 8.40625H17.1562C17.6719 8.45312 17.9531 8.73438 18 9.25C17.9531 9.76562 17.6719 10.0469 17.1562 10.0938H15.0469C14.8594 10.6797 14.5312 11.1484 14.0625 11.5C13.5703 11.875 13.0078 12.0625 12.375 12.0625C11.7422 12.0625 11.1797 11.875 10.6875 11.5C10.2188 11.1484 9.89062 10.6797 9.70312 10.0938H0.84375C0.328125 10.0469 0.046875 9.76562 0 9.25C0.046875 8.73438 0.328125 8.45312 0.84375 8.40625H9.70312C9.89062 7.82031 10.2188 7.35156 10.6875 7C11.1797 6.625 11.7422 6.4375 12.375 6.4375ZM6.75 4.75C7.07812 4.75 7.34766 4.64453 7.55859 4.43359C7.76953 4.22266 7.875 3.95312 7.875 3.625C7.875 3.29688 7.76953 3.02734 7.55859 2.81641C7.34766 2.60547 7.07812 2.5 6.75 2.5C6.42188 2.5 6.15234 2.60547 5.94141 2.81641C5.73047 3.02734 5.625 3.29688 5.625 3.625C5.625 3.95312 5.73047 4.22266 5.94141 4.43359C6.15234 4.64453 6.42188 4.75 6.75 4.75ZM9.42188 2.78125H17.1562C17.6719 2.82812 17.9531 3.10937 18 3.625C17.9531 4.14063 17.6719 4.42188 17.1562 4.46875H9.42188C9.23438 5.05469 8.90625 5.52344 8.4375 5.875C7.94531 6.25 7.38281 6.4375 6.75 6.4375C6.11719 6.4375 5.55469 6.25 5.0625 5.875C4.59375 5.52344 4.26562 5.05469 4.07812 4.46875H0.84375C0.328125 4.42188 0.046875 4.14063 0 3.625C0.046875 3.10937 0.328125 2.82812 0.84375 2.78125H4.07812C4.26562 2.19531 4.59375 1.72656 5.0625 1.375C5.55469 1 6.11719 0.8125 6.75 0.8125C7.38281 0.8125 7.94531 1 8.4375 1.375C8.90625 1.72656 9.23438 2.19531 9.42188 2.78125Z"/>
                </svg><span><?php esc_html_e('Filter', 'hitboox'); ?></span></a>
            <?php
        }
    }
}

if (!function_exists('hitboox_render_woocommerce_shop_dropdown')) {
    function hitboox_render_woocommerce_shop_dropdown()
    {
        ?>
        <div id="hitboox-dropdown-filter" class="hitboox-dropdown-filter">
            <div class="hitboox-dropdown-filter-wrap">
                <?php
                dynamic_sidebar('sidebar-woocommerce-shop');
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('woocommerce_checkout_order_review_start')) {

    function woocommerce_checkout_order_review_start()
    {
        echo '<div class="checkout-review-order-table-wrapper">';
    }
}

if (!function_exists('woocommerce_checkout_order_review_end')) {

    function woocommerce_checkout_order_review_end()
    {
        echo '</div>';
    }
}

if (!function_exists('hitboox_woocommerce_get_product_label_stock')) {
    function hitboox_woocommerce_get_product_label_stock()
    {
        /**
         * @var $product WC_Product
         */
        global $product;
        if ($product->get_stock_status() == 'outofstock') {
            echo '<span class="stock-label">' . esc_html__('Out Of Stock', 'hitboox') . '</span>';
        }
    }
}

if (!function_exists('hitboox_woocommerce_single_content_wrapper_start')) {
    function hitboox_woocommerce_single_content_wrapper_start()
    {
        echo '<div class="content-single-wrapper">';
    }
}

if (!function_exists('hitboox_woocommerce_single_content_wrapper_end')) {
    function hitboox_woocommerce_single_content_wrapper_end()
    {
        echo '</div>';
    }
}

if (!function_exists('hitboox_woocommerce_single_brand')) {
    function hitboox_woocommerce_single_brand()
    {
        $id = get_the_ID();

        $terms = get_the_terms($id, 'product_brand');

        if (is_wp_error($terms)) {
            return $terms;
        }

        if (empty($terms)) {
            return false;
        }

        $links = array();

        foreach ($terms as $term) {
            $link = get_term_link($term, 'product_brand');
            if (is_wp_error($link)) {
                return $link;
            }
            $links[] = '<a href="' . esc_url($link) . '" rel="tag">' . $term->name . '</a>';
        }
        echo '<div class="product-brand">' . esc_html__('Brands: ', 'hitboox') . join('', $links) . '</div>';
    }
}

if (!function_exists('hitboox_output_product_data_accordion')) {
    function hitboox_output_product_data_accordion()
    {
        $product_tabs = apply_filters('woocommerce_product_tabs', array());
        if (!empty($product_tabs)) : ?>
            <div id="hitboox-accordion-container" class="woocommerce-tabs wc-tabs-wrapper product-accordions">
                <?php $_count = 0; ?>
                <?php foreach ($product_tabs as $key => $tab) : ?>
                    <div class="accordion-item">
                        <div class="accordion-head <?php echo esc_attr($key); ?>_tab js-btn-accordion"
                             id="tab-title-<?php echo esc_attr($key); ?>">
                            <div class="accordion-title"><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?></div>
                        </div>
                        <div class="accordion-body js-card-body">
                            <?php call_user_func($tab['callback'], $key, $tab); ?>
                        </div>
                    </div>
                    <?php $_count++; ?>
                <?php endforeach; ?>
            </div>
        <?php endif;
    }
}

if (!function_exists('hitboox_quickview_button')) {
    function hitboox_quickview_button()
    {
        if (function_exists('woosq_init')) {
            echo do_shortcode('[woosq]');
        }
    }
}

if (!function_exists('hitboox_compare_button')) {
    function hitboox_compare_button()
    {
        if (function_exists('woosc_init')) {
            echo do_shortcode('[woosc]');
        }
    }
}

if (!function_exists('hitboox_wishlist_button')) {
    function hitboox_wishlist_button()
    {
        if (function_exists('woosw_init')) {
            echo do_shortcode('[woosw]');
        }
    }
}

function hitboox_ajax_add_to_cart_add_fragments($fragments)
{
    $all_notices = WC()->session->get('wc_notices', array());
    $notice_types = apply_filters('woocommerce_notice_types', array('error', 'success', 'notice'));

    ob_start();
    foreach ($notice_types as $notice_type) {
        if (wc_notice_count($notice_type) > 0) {
            wc_get_template("notices/{$notice_type}.php", array(
                'notices' => array_filter($all_notices[$notice_type]),
            ));
        }
    }
    $fragments['notices_html'] = ob_get_clean();

    wc_clear_notices();

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'hitboox_ajax_add_to_cart_add_fragments');

if (!function_exists('hitboox_shop_page_link')) {
    function hitboox_shop_page_link($keep_query = false, $taxonomy = '')
    {
        // Base Link decided by current page
        if (is_post_type_archive('product') || is_page(wc_get_page_id('shop')) || is_shop()) {
            $link = get_permalink(wc_get_page_id('shop'));
        } elseif (is_product_category()) {
            $link = get_term_link(get_query_var('product_cat'), 'product_cat');
        } elseif (is_product_tag()) {
            $link = get_term_link(get_query_var('product_tag'), 'product_tag');
        } else {
            $queried_object = get_queried_object();
            $link = get_term_link($queried_object->slug, $queried_object->taxonomy);
        }

        if ($keep_query) {

            // Min/Max
            if (isset($_GET['min_price'])) {
                $link = add_query_arg('min_price', wc_clean($_GET['min_price']), $link);
            }

            if (isset($_GET['max_price'])) {
                $link = add_query_arg('max_price', wc_clean($_GET['max_price']), $link);
            }

            // Orderby
            if (isset($_GET['orderby'])) {
                $link = add_query_arg('orderby', wc_clean($_GET['orderby']), $link);
            }

            if (isset($_GET['woocommerce_catalog_columns'])) {
                $link = add_query_arg('woocommerce_catalog_columns', wc_clean($_GET['woocommerce_catalog_columns']), $link);
            }

            if (isset($_GET['layout'])) {
                $link = add_query_arg('layout', wc_clean($_GET['layout']), $link);
            }

            if (isset($_GET['wocommerce_block_style'])) {
                $link = add_query_arg('wocommerce_block_style', wc_clean($_GET['wocommerce_block_style']), $link);
            }

            /**
             * Search Arg.
             * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
             */
            if (get_search_query()) {
                $link = add_query_arg('s', rawurlencode(wp_specialchars_decode(get_search_query())), $link);
            }

            // Post Type Arg
            if (isset($_GET['post_type'])) {
                $link = add_query_arg('post_type', wc_clean($_GET['post_type']), $link);
            }

            // Min Rating Arg
            if (isset($_GET['min_rating'])) {
                $link = add_query_arg('min_rating', wc_clean($_GET['min_rating']), $link);
            }

            // All current filters
            if ($_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes()) {
                foreach ($_chosen_attributes as $name => $data) {
                    if ($name === $taxonomy) {
                        continue;
                    }
                    $filter_name = sanitize_title(str_replace('pa_', '', $name));
                    if (!empty($data['terms'])) {
                        $link = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $link);
                    }
                    if ('or' == $data['query_type']) {
                        $link = add_query_arg('query_type_' . $filter_name, 'or', $link);
                    }
                }
            }
        }

        if (is_string($link)) {
            return $link;
        } else {
            return '';
        }
    }
}

if (!function_exists('hitboox_products_per_page_select')) {

    function hitboox_products_per_page_select()
    {
        if ((wc_get_loop_prop('is_shortcode') || !wc_get_loop_prop('is_paginated') || !woocommerce_products_will_display())) return;

        $row = wc_get_default_products_per_row();
        $max_col = apply_filters('hitboox_products_row_step_max', 6);
        $array_option = [];
        if ($max_col > 2) {
            for ($i = 2; $i <= $max_col; $i++) {
                $array_option[] = $row * $i;
            }
        } else {
            return;
        }

        $col = wc_get_default_product_rows_per_page();

        $products_per_page_options = apply_filters('hitboox_products_per_page_options', $array_option);

        $current_variation = isset($_GET['per_page']) ? $_GET['per_page'] : $col * $row;
        ?>

        <div class="hitboox-products-per-page">

            <label for="per_page" class="per-page-title"><?php esc_html_e('Show', 'hitboox'); ?></label>
            <select name="per_page" id="per_page">
                <?php
                foreach ($products_per_page_options as $key => $value) :

                    ?>
                    <option value="<?php echo add_query_arg('per_page', $value, hitboox_shop_page_link(true)); ?>" <?php echo esc_attr($current_variation == $value ? 'selected' : ''); ?>>
                        <?php echo esc_html($value); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}

if (isset($_GET['per_page'])) {
    add_filter('loop_shop_per_page', 'hitboox_loop_shop_per_page', 20);
}

function hitboox_loop_shop_per_page($cols)
{

    $cols = isset($_GET['per_page']) ? $_GET['per_page'] : $cols;

    return $cols;
}

if (!function_exists('hitboox_active_filters')) {
    function hitboox_active_filters()
    {
        echo '<div class="hitboox-active-filters">';
        the_widget('WC_Widget_Layered_Nav_Filters');
        echo '</div>';
    }
}