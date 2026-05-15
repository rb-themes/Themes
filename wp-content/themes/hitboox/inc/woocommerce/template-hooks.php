<?php
/**
 * =================================================
 * Hook hitboox_page
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_single_post
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_single_post_bottom
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_loop_post
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_footer
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'hitboox_render_woocommerce_shop_canvas', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_content_top
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_sidebar
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_loop_after
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_page_after
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_woocommerce_before_shop_loop_item_title
 * =================================================
 */
add_action('hitboox_woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
add_action('hitboox_woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 15);

/**
 * =================================================
 * Hook hitboox_woocommerce_shop_loop_item_title
 * =================================================
 */
add_action('hitboox_woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 5);

/**
 * =================================================
 * Hook hitboox_woocommerce_after_shop_loop_item_title
 * =================================================
 */
add_action('hitboox_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 15);
add_action('hitboox_woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20);
add_action('hitboox_woocommerce_after_shop_loop_item_title', 'hitboox_woocommerce_get_product_description', 25);
add_action('hitboox_woocommerce_after_shop_loop_item_title', 'hitboox_woocommerce_product_list_add_to_cart', 30);

/**
 * =================================================
 * Hook hitboox_woocommerce_after_shop_loop_item
 * =================================================
 */
