<?php
/**
 * Hitboox functions.
 *
 * @package hitboox
 */

if (!function_exists('hitboox_is_bcn_nav_activated')) {
    function hitboox_is_bcn_nav_activated()
    {
        return function_exists('bcn_display') ? true : false;
    }
}

if (!function_exists('hitboox_is_cmb2_activated')) {
    function hitboox_is_cmb2_activated()
    {
        return defined('CMB2_LOADED') ? true : false;
    }
}

if (!function_exists('hitboox_is_revslider_activated')) {
    function hitboox_is_revslider_activated()
    {
        return class_exists('RevSliderBase');
    }
}

if (!function_exists('hitboox_is_wpml_activated')) {
    function hitboox_is_wpml_activated()
    {
        return class_exists('SitePress') ? true : false;
    }
}

if (!function_exists('hitboox_is_woocommerce_activated')) {
    /**
     * Query WooCommerce activation
     */
    function hitboox_is_woocommerce_activated()
    {
        return class_exists('WooCommerce') ? true : false;
    }
}

if (!function_exists('hitboox_is_elementor_activated')) {
    function hitboox_is_elementor_activated()
    {
        return defined('ELEMENTOR_VERSION') ? true : false;
    }
}

if (!function_exists('hitboox_is_elementor_pro_activated')) {
    function hitboox_is_elementor_pro_activated()
    {
        return defined('ELEMENTOR_PRO_VERSION') ? true : false;
    }
}

if (!function_exists('hitboox_is_redux_activated')) {
    function hitboox_is_redux_activated()
    {
        return class_exists('Redux') ? true : false;
    }
}

if (!function_exists('hitboox_is_contactform_activated')) {
    function hitboox_is_contactform_activated()
    {
        return class_exists('WPCF7');
    }
}

if (!function_exists('hitboox_is_mailchimp_activated')) {
    function hitboox_is_mailchimp_activated()
    {
        return defined('MC4WP_VERSION') ? true : false;
    }
}

if (!function_exists('hitboox_elementor_check_type')) {
    function hitboox_elementor_check_type($type = '')
    {
        if ($type) {
            $data = get_post_meta(get_the_ID(), '_elementor_data', true);
            if ($data) {
                return preg_match('/' . $type . '/', $data);
            }
        }

        return false;
    }
}


/**
 * Call a shortcode function by tag name.
 *
 * @param string $tag The shortcode whose function to call.
 * @param array $atts The attributes to pass to the shortcode function. Optional.
 * @param array $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 * @since  1.4.6
 *
 */
function hitboox_do_shortcode($tag, array $atts = array(), $content = null)
{
    global $shortcode_tags;

    if (!isset($shortcode_tags[$tag])) {
        return false;
    }

    return call_user_func($shortcode_tags[$tag], $atts, $content, $tag);
}

function hitboox_function_to_call($type, $args)
{
    $function_name = "register_" . $type;
    if (function_exists($function_name)) {
        call_user_func($function_name, ...$args);
    }
}

/*
 * get theme option redux
 * @param string $option_name
 * @param mix $default
 * @return mix
 *
 * */

if (!function_exists('hitboox_get_theme_option')) {
    function hitboox_get_theme_option($option_name, $default = false)
    {

        if ($option = get_option('hitboox_options_' . $option_name)) {
            $default = $option;
        }

        return $default;
    }
}


if (!function_exists('hitboox_get_post_meta')) {
    function hitboox_get_post_meta($post_id, $meta_name, $default = false)
    {
        $value = get_post_meta($post_id, $meta_name, true);
        if (!$value) {
            return $default;
        }

        return $value;
    }
}
function hitboox_add_meta($type, $callback, $priority = 10, $accepted_args = 1)
{
    $action_name = "add_meta_" . $type;
    if (has_action($action_name)) {
        add_action($action_name, $callback, $priority, $accepted_args);
    }
}

function hitboox_add_meta_fn($type, $args)
{
    $function_name = "add_meta_" . $type;
    if (function_exists($function_name)) {
        call_user_func($function_name, ...$args);
    }
}

function hitboox_parse_text_editor($content)
{

    $content = shortcode_unautop($content);
    $content = do_shortcode($content);
    $content = wptexturize($content);

    if ($GLOBALS['wp_embed'] instanceof \WP_Embed) {
        $content = $GLOBALS['wp_embed']->autoembed($content);
    }

    return $content;
}

if (!function_exists('hitboox_get_page_by_title')) {
    function hitboox_get_page_by_title($title, $output = OBJECT, $post_type = 'page')
    {
        $query = new WP_Query(
            [
                'post_type' => $post_type,
                'title' => $title,
                'post_status' => 'publish',
                'posts_per_page' => 1,
            ]
        );

        if ($query->have_posts()) {
            $query->the_post();
            $page = get_post(get_the_ID(), $output);
        } else {
            $page = null;
        }

        wp_reset_postdata();

        return $page;

    }
}

if (!function_exists('hitboox_write_to_file')) {
    function hitboox_write_to_file($file_path, $content)
    {
        if (!function_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        global $wp_filesystem;
        if (!WP_Filesystem()) {
            return false;
        }
        return $wp_filesystem->put_contents($file_path, $content, FS_CHMOD_FILE);
    }
}

if (!function_exists('hitboox_read_from_file')) {
    function hitboox_read_from_file($file)
    {
        if (!function_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        global $wp_filesystem;
        if (!WP_Filesystem()) {
            return false;
        }
        return $wp_filesystem->get_contents($file);
    }
}