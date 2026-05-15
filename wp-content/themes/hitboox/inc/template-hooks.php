<?php
/**
 * =================================================
 * Hook hitboox_page
 * =================================================
 */
add_action('hitboox_page', 'hitboox_page_header', 10);
add_action('hitboox_page', 'hitboox_page_content', 20);

/**
 * =================================================
 * Hook hitboox_single_post
 * =================================================
 */
add_action('hitboox_single_post', 'hitboox_post_header', 5);
add_action('hitboox_single_post', 'hitboox_post_thumbnail', 10);
add_action('hitboox_single_post', 'hitboox_post_content', 30);

/**
 * =================================================
 * Hook hitboox_single_post_bottom
 * =================================================
 */
add_action('hitboox_single_post_bottom', 'hitboox_post_taxonomy', 5);
add_action('hitboox_single_post_bottom', 'hitboox_single_author', 10);
add_action('hitboox_single_post_bottom', 'hitboox_post_nav', 15);
add_action('hitboox_single_post_bottom', 'hitboox_display_comments', 20);

/**
 * =================================================
 * Hook hitboox_loop_post
 * =================================================
 */
add_action('hitboox_loop_post', 'hitboox_post_header', 15);
add_action('hitboox_loop_post', 'hitboox_post_content', 30);

/**
 * =================================================
 * Hook hitboox_footer
 * =================================================
 */
add_action('hitboox_footer', 'hitboox_footer_default', 20);

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
add_action('wp_footer', 'hitboox_template_account_dropdown', 1);
add_action('wp_footer', 'hitboox_mobile_nav', 1);
add_action('wp_footer', 'render_html_back_to_top', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'hitboox_pingback_header', 1);

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
add_action('hitboox_sidebar', 'hitboox_get_sidebar', 10);

/**
 * =================================================
 * Hook hitboox_loop_after
 * =================================================
 */
add_action('hitboox_loop_after', 'hitboox_paging_nav', 10);

/**
 * =================================================
 * Hook hitboox_page_after
 * =================================================
 */
add_action('hitboox_page_after', 'hitboox_display_comments', 10);

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

/**
 * =================================================
 * Hook hitboox_woocommerce_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_woocommerce_after_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook hitboox_woocommerce_after_shop_loop_item
 * =================================================
 */
