<?php
/**
 * Hitboox WooCommerce hooks
 *
 * @package hitboox
 */

/**
 * Layout
 *
 * @see  hitboox_before_content()
 * @see  hitboox_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  hitboox_shop_messages()
 */

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action('woocommerce_before_main_content', 'hitboox_before_content', 10);
add_action('woocommerce_after_main_content', 'hitboox_after_content', 10);


//Position label onsale
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);


//Wrapper content single
add_action('woocommerce_before_single_product_summary', 'hitboox_woocommerce_single_content_wrapper_start', 0);
add_action('woocommerce_single_product_summary', 'hitboox_woocommerce_single_content_wrapper_end', 99);

/**
 * Products
 *
 * @see hitboox_upsell_display()
 * @see hitboox_single_product_pagination()
 */

add_action('woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 1);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
add_action('woocommerce_single_product_summary', 'hitboox_single_product_extra', 35);

add_filter('woosc_button_position_single', '__return_false');
add_filter('woosw_button_position_single', '__return_false');

add_action('woocommerce_share', 'hitboox_social_share', 10);

add_action('woocommerce_after_add_to_cart_button', 'hitboox_wishlist_button', 31);
add_action('woocommerce_after_add_to_cart_button', 'hitboox_compare_button', 32);

add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-slider');


/**
 * Cart fragment
 *
 * @see hitboox_cart_link_fragment()
 */
if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
    add_filter('woocommerce_add_to_cart_fragments', 'hitboox_cart_link_fragment');
} else {
    add_filter('add_to_cart_fragments', 'hitboox_cart_link_fragment');
}

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_start', 5);
add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_end', 15);

add_filter('woocommerce_get_script_data', function ($params, $handle) {
    if ($handle == "wc-add-to-cart") {
        $params['i18n_view_cart'] = '';
    }
    return $params;
}, 10, 2);

add_filter('woocommerce_gallery_thumbnail_size',function (){
    return array(150,150);
});


add_filter( 'woocommerce_loop_add_to_cart_link', 'custom_add_to_cart_class', 10, 2 );

function custom_add_to_cart_class( $button, $product ) {
    // Add a custom class to the button
    $custom_class = 'path-wrap-yes'; // Replace with your desired class name

    // Modify the button by injecting the custom class
    $button = str_replace( 'class="button', 'class="button ' . $custom_class, $button );

    return $button;
}
/*
 *
 * Layout Product
 *
 * */

add_filter('woosc_button_position_archive', '__return_false');
add_filter('woosq_button_position', '__return_false');
add_filter('woosw_button_position_archive', '__return_false');

function hitboox_include_hooks_product_blocks() {

    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    // Remove product content link
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

}

if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
    return;
}

hitboox_include_hooks_product_blocks();

