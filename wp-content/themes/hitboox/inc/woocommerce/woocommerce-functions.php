<?php
/**
 * Checks if the current page is a product archive
 *
 * @return boolean
 */
function hitboox_is_product_archive() {
    if (is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag()) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $product WC_Product
 */
function hitboox_product_get_image($product) {
    return $product->get_image();
}

/**
 * @param $product WC_Product
 */
function hitboox_product_get_price_html($product) {
    return $product->get_price_html();
}

/**
 * Retrieves the previous product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function hitboox_get_previous_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Hitboox_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy, true);
    return $product->get_product();
}

/**
 * Retrieves the next product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function hitboox_get_next_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Hitboox_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy);
    return $product->get_product();
}


function hitboox_is_woocommerce_extension_activated($extension = 'WC_Bookings') {
    if ($extension == 'YITH_WCQV') {
        return class_exists($extension) && class_exists('YITH_WCQV_Frontend') ? true : false;
    }

    return class_exists($extension) ? true : false;
}

function hitboox_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<i class="hitboox-icon hitboox-icon-arrow-left"></i><span class="screen-reader-text">' . esc_html__('Previons', 'hitboox') . '</span>';
    $args['next_text'] = '<span class="screen-reader-text">' . esc_html__('Next', 'hitboox') . '</span><i class="hitboox-icon hitboox-icon-arrow-right"></i>';
    return $args;
}

add_filter('woocommerce_pagination_args', 'hitboox_woocommerce_pagination_args', 10, 1);


function hitboox_unsupported_theme_remove_review_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}

function woocommerce_template_loop_rating() {

    global $product;
    if (!wc_review_ratings_enabled()) {
        return;
    }
    $count = $product->get_review_count();
    ?>
    <div class="count-review">
        <?php
        if ($rating_html = wc_get_rating_html($product->get_average_rating())) {
            echo apply_filters('hitboox_woocommerce_rating_html', $rating_html);
        } else {
            echo '<div class="star-rating"></div>';
        }
        echo '<span class="count">' . sprintf(esc_html(_n('%1$s Review', '%1$s Reviews', $count, 'hitboox')), esc_html($count)) . '</span>';
        ?>
    </div>
    <?php
}

