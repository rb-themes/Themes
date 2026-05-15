<?php
$theme          = wp_get_theme('hitboox');
define( 'HITBOOX_VERSION', $theme['Version'] );
if ( ! isset( $content_width ) ) $content_width = 900;

require_once get_theme_file_path('inc/class-tgm-plugin-activation.php');
require_once get_theme_file_path('inc/class-main.php');
require_once get_theme_file_path('inc/functions.php');
require_once get_theme_file_path('inc/template-hooks.php');
require_once get_theme_file_path('inc/template-functions.php');

require_once get_theme_file_path('inc/merlin/vendor/autoload.php');
require_once get_theme_file_path('inc/merlin/class-merlin.php');
require_once get_theme_file_path('inc/merlin-config.php');
require_once get_theme_file_path('inc/class-customize.php');

if (hitboox_is_woocommerce_activated()) {
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce.php');
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce-adjacent-products.php');
    require_once get_theme_file_path('inc/woocommerce/woocommerce-functions.php');
    require_once get_theme_file_path('inc/woocommerce/woocommerce-template-functions.php');
    require_once get_theme_file_path('inc/woocommerce/woocommerce-template-hooks.php');
    require_once get_theme_file_path('inc/woocommerce/template-hooks.php');
}

if (hitboox_is_elementor_activated()) {
    require_once get_theme_file_path('inc/elementor/functions-elementor.php');
    require_once get_theme_file_path('inc/elementor/class-elementor.php');
    require_once get_theme_file_path('inc/megamenu/megamenu.php');
    require_once get_theme_file_path('inc/elementor/section-parallax.php');
    require_once get_theme_file_path('inc/elementor/path-shape/section-path.php');
    require_once get_theme_file_path('inc/elementor/background-shape/section-decor.php');
    if (defined('ELEMENTOR_PRO_VERSION')) {
        require_once get_theme_file_path('inc/elementor/class-elementor-pro.php');
    }

    require_once get_theme_file_path('inc/elementor/elementor-control/class-elementor-control.php');

    if (function_exists('hfe_init')) {
        require_once get_theme_file_path('inc/header-footer-elementor/class-hfe.php');
        require_once get_theme_file_path('inc/merlin/includes/breadcrumb.php');
    }
    require_once get_theme_file_path('inc/merlin/includes/services.php');
    require_once get_theme_file_path('inc/merlin/includes/projects.php');
}

if (!is_user_logged_in()) {
    require_once get_theme_file_path('inc/modules/class-login.php');
}else {
    require_once get_theme_file_path('inc/modules/media-custom-field.php');
}