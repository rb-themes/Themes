<?php
/**
 * Plugin Name: CYGMA Migration Tools
 * Description: Controlled maintenance, redirect, and news URL tools for the CYGMA redesign migration.
 * Version: 0.3.5
 * Author: CYGMA
 */

if (!defined('ABSPATH')) {
    exit;
}

const CYGMA_MIGRATION_TOOLS_OPTION = 'cygma_migration_tools_options';

function cygma_migration_tools_default_options() {
    return array(
        'maintenance' => false,
        'redirects' => false,
        'news_routing' => false,
        'member_routing' => false,
    );
}

function cygma_migration_tools_options() {
    $options = get_option(CYGMA_MIGRATION_TOOLS_OPTION, array());

    return wp_parse_args(is_array($options) ? $options : array(), cygma_migration_tools_default_options());
}

function cygma_migration_tools_is_enabled($feature) {
    $options = cygma_migration_tools_options();

    return !empty($options[$feature]);
}

function cygma_migration_tools_current_path() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $path = wp_parse_url($request_uri, PHP_URL_PATH);

    if (!$path) {
        return '/';
    }

    return trailingslashit('/' . ltrim($path, '/'));
}

function cygma_migration_tools_noindex_headers() {
    if (headers_sent()) {
        return;
    }

    header('X-Robots-Tag: noindex, nofollow, noarchive', true);
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true);
}

register_activation_hook(__FILE__, function () {
    if (get_option(CYGMA_MIGRATION_TOOLS_OPTION, null) === null) {
        add_option(CYGMA_MIGRATION_TOOLS_OPTION, cygma_migration_tools_default_options(), '', false);
    }
});

add_action('admin_menu', function () {
    add_management_page(
        'CYGMA Migration Tools',
        'CYGMA Migration',
        'manage_options',
        'cygma-migration-tools',
        'cygma_migration_tools_render_settings_page'
    );
});

add_action('admin_post_cygma_migration_tools_save', function () {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to change these settings.', 'cygma-migration-tools'));
    }

    check_admin_referer('cygma_migration_tools_save');

    $previous = cygma_migration_tools_options();
    $next = array(
        'maintenance' => !empty($_POST['maintenance']),
        'redirects' => !empty($_POST['redirects']),
        'news_routing' => !empty($_POST['news_routing']),
        'member_routing' => !empty($_POST['member_routing']),
    );

    update_option(CYGMA_MIGRATION_TOOLS_OPTION, $next, false);

    if ($previous['news_routing'] !== $next['news_routing']) {
        cygma_migration_tools_register_news_rewrite();
        flush_rewrite_rules(false);
    }

    if ($previous['member_routing'] !== $next['member_routing']) {
        cygma_migration_tools_register_member_rewrite();
        flush_rewrite_rules(false);
    }

    wp_safe_redirect(add_query_arg('updated', '1', wp_get_referer() ?: admin_url('tools.php?page=cygma-migration-tools')));
    exit;
});

add_action('rest_api_init', function () {
    register_rest_route('cygma-migration-tools/v1', '/sync-hfe-shells', array(
        'methods' => 'POST',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'callback' => 'cygma_migration_tools_sync_hfe_shells',
    ));

    register_rest_route('cygma-migration-tools/v1', '/sync-design-kit', array(
        'methods' => 'POST',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'callback' => 'cygma_migration_tools_sync_design_kit',
    ));

    register_rest_route('cygma-migration-tools/v1', '/repair-members-loop', array(
        'methods' => 'POST',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'callback' => 'cygma_migration_tools_repair_members_loop',
    ));

    register_rest_route('cygma-migration-tools/v1', '/repair-home-news-loop', array(
        'methods' => 'POST',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
        'callback' => 'cygma_migration_tools_repair_home_news_loop',
    ));
});

add_action('init', 'cygma_migration_tools_register_membership_level_taxonomy', 20);

function cygma_migration_tools_register_membership_level_taxonomy() {
    register_taxonomy('membership-level', array('memberships'), array(
        'label' => 'Membership Levels',
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
        'rewrite' => false,
    ));
}

function cygma_migration_tools_sync_hfe_shells() {
    $pairs = array(
        188 => array('source' => 8778, 'type' => 'header'),
        65 => array('source' => 8776, 'type' => 'footer'),
    );
    $results = array();

    foreach ($pairs as $target_id => $config) {
        $source_id = (int) $config['source'];
        $source_data = get_post_meta($source_id, '_elementor_data', true);

        if (!$source_data) {
            $results[] = array(
                'target' => $target_id,
                'source' => $source_id,
                'updated' => false,
                'reason' => 'missing_source_data',
            );
            continue;
        }

        update_post_meta($target_id, '_elementor_edit_mode', 'builder');
        update_post_meta($target_id, '_elementor_template_type', 'wp-post');
        update_post_meta($target_id, '_elementor_data', wp_slash($source_data));
        update_post_meta($target_id, '_elementor_page_settings', get_post_meta($source_id, '_elementor_page_settings', true));

        clean_post_cache($target_id);

        $results[] = array(
            'target' => $target_id,
            'source' => $source_id,
            'type' => $config['type'],
            'updated' => true,
            'data_length' => strlen($source_data),
        );
    }

    if (did_action('elementor/loaded') && class_exists('Elementor\\Plugin')) {
        Elementor\Plugin::instance()->files_manager->clear_cache();
    }

    return rest_ensure_response(array(
        'updated' => $results,
    ));
}

function cygma_migration_tools_sync_design_asset($source_url, $filename, $overwrite = false) {
    $uploads = wp_upload_dir();
    $target_dir = trailingslashit($uploads['basedir']) . '2026/05';
    $target_url = trailingslashit($uploads['baseurl']) . '2026/05/' . $filename;
    $target_file = trailingslashit($target_dir) . $filename;

    if (!wp_mkdir_p($target_dir)) {
        return new WP_Error('cygma_asset_directory_failed', 'Could not create target uploads directory.');
    }

    if ($overwrite || !file_exists($target_file)) {
        $response = wp_remote_get($source_url, array('timeout' => 30));

        if (is_wp_error($response)) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if ($code < 200 || $code >= 300 || $body === '') {
            return new WP_Error('cygma_asset_download_failed', 'Could not download staging design asset.', array('source' => $source_url, 'status' => $code));
        }

        file_put_contents($target_file, $body);
    }

    return array(
        'file' => $target_file,
        'url' => $target_url,
        'exists' => file_exists($target_file),
        'bytes' => file_exists($target_file) ? filesize($target_file) : 0,
    );
}

function cygma_migration_tools_design_custom_css($assets) {
    $roboto_url = esc_url_raw($assets['roboto']['url']);
    $venture_regular_url = esc_url_raw($assets['venture_regular']['url']);
    $venture_bold_url = esc_url_raw($assets['venture_bold']['url']);
    $venture_thin_url = esc_url_raw($assets['venture_thin']['url']);
    $hover_mask_url = esc_url_raw($assets['hover_mask']['url']);
    $newsletter_bg_url = esc_url_raw($assets['newsletter_bg']['url']);
    $member_associate_url = esc_url_raw($assets['member_associate']['url']);
    $member_ordinary_url = esc_url_raw($assets['member_ordinary']['url']);
    $member_full_url = esc_url_raw($assets['member_full']['url']);
    $member_academic_url = esc_url_raw($assets['member_academic']['url']);

    return <<<CSS
@font-face { font-family: "Roboto Flex variable"; font-display: auto; src: url("{$roboto_url}") format("truetype"); font-weight: 100 1000; font-stretch: 25% 151%; }
@font-face { font-family: "Roboto Flex custom"; font-display: auto; src: url("{$roboto_url}") format("truetype"); font-weight: 100 1000; font-stretch: 25% 151%; }
@font-face { font-family: Venture13; font-style: normal; font-weight: normal; font-display: auto; src: url("{$venture_regular_url}") format("woff2"); }
@font-face { font-family: Venture13; font-style: normal; font-weight: bold; font-display: auto; src: url("{$venture_bold_url}") format("woff2"); }
@font-face { font-family: Venture13; font-style: normal; font-weight: 200; font-display: auto; src: url("{$venture_thin_url}") format("woff2"); }

html, body {
    max-width: 100%;
    overflow-x: hidden;
    position: relative;
    font-family: "Roboto Flex variable", sans-serif !important;
    font-weight: 500 !important;
    font-stretch: 140% !important;
}

.elementor-188,
.elementor-188 a,
.elementor-188 .elementor-item,
.elementor-location-header,
.elementor-location-header a,
.elementor-location-header .elementor-item {
    font-weight: 500 !important;
    font-stretch: 140% !important;
}

.organic-fill-button {
    background: #FFED62;
    border: 3px solid #ffffff !important;
    border-radius: 3px !important;
    transition: 0.5s;
    z-index: 1;
}

.organic-fill-button .elementor-button::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, #FFBF00 0%, #FF8D0B 100%) !important;
    -webkit-mask-image: url("{$hover_mask_url}");
    mask-image: url("{$hover_mask_url}");
    -webkit-mask-size: 2300% 100%;
    mask-size: 2300% 100%;
    -webkit-animation: mask-out 0.5s steps(22) forwards;
    animation: mask-out 0.5s steps(22) forwards;
    z-index: -1;
}

.organic-fill-button:hover .elementor-button::before {
    -webkit-animation: mask-in 0.7s steps(22) forwards;
    animation: mask-in 0.7s steps(22) forwards;
}

.organic-fill-button .elementor-button {
    background: transparent !important;
    z-index: 2;
}

.elementor-188 .elementor-element.elementor-element-62cafaf .elementor-button,
.elementor-188 .elementor-element.elementor-element-62cafaf .elementor-button * {
    font-weight: 750 !important;
    font-stretch: 150% !important;
}

.elementor-188 .elementor-element.elementor-element-62cafaf {
    width: 208px !important;
    height: 43px !important;
}

.elementor-188 .elementor-element.elementor-element-62cafaf .elementor-button {
    width: 202px !important;
    height: 37px !important;
    min-height: 37px !important;
}

h1,
h1.elementor-heading-title,
h1 .elementor-heading-title {
    font-stretch: 40% !important;
}

.elementor-element-1997827 .elementor-heading-title,
.elementor-element-1341d63 .elementor-heading-title,
.elementor-element-71789fe .elementor-heading-title {
    font-stretch: 40% !important;
}

.elementor-element-1c640d06 h3.elementor-heading-title {
    font-stretch: 145% !important;
}

.elementor-element-1c640d06 .elementor-element-66f967e7 .elementor-heading-title {
    font-weight: 700 !important;
    font-stretch: 25% !important;
}

.elementor-element-1c640d06 .elementor-element-26fc4251,
.elementor-element-1c640d06 .elementor-element-26fc4251 .e-n-carousel,
.elementor-element-1c640d06 .elementor-element-26fc4251 .swiper-wrapper,
.elementor-element-1c640d06 .elementor-element-26fc4251 .swiper-slide {
    height: 488px !important;
}

.footer-newsletter-section {
    background-image: url("{$newsletter_bg_url}") !important;
    background-size: cover !important;
    background-position: 50% 0% !important;
    background-repeat: no-repeat !important;
}

.footer-newsletter-section .elementor-form .elementor-button,
.footer-newsletter-section .elementor-form .elementor-button * {
    font-weight: 800 !important;
    font-stretch: 150% !important;
}

.footer-newsletter-section .elementor-button {
    position: relative !important;
    overflow: hidden !important;
    background: #FFED62 !important;
    transition: 0.5s !important;
    isolation: isolate !important;
}

.footer-newsletter-section .elementor-button::before {
    content: "" !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(180deg, #FFBF00 0%, #FF8D0B 100%) !important;
    -webkit-mask-image: url("{$hover_mask_url}") !important;
    mask-image: url("{$hover_mask_url}") !important;
    -webkit-mask-size: 2300% 100% !important;
    mask-size: 2300% 100% !important;
    -webkit-animation: mask-out 0.5s steps(22) forwards !important;
    animation: mask-out 0.5s steps(22) forwards !important;
    pointer-events: none !important;
    z-index: -1 !important;
}

.footer-newsletter-section .elementor-button:hover::before {
    -webkit-animation: mask-in 0.7s steps(22) forwards !important;
    animation: mask-in 0.7s steps(22) forwards !important;
}

.footer-newsletter-section .elementor-button .elementor-button-content-wrapper,
.footer-newsletter-section .elementor-button .elementor-button-text {
    position: relative !important;
    z-index: 1 !important;
}

.footer-newsletter-section .elementor-element-758e089 {
    transform: matrix3d(0.999848, 0.0174524, 0, 0, -0.0174524, 0.999848, 0, 0, 0, 0, 1, -2, 0, 0, 0, 1) !important;
}

.footer-newsletter-section .elementor-element-e9d21ae {
    transform: matrix3d(0.999848, -0.0174524, 0, 0, 0.0174524, 0.999848, 0, 0, 0, 0, 1, -2, 0, 0, 0, 1) !important;
}

.elementor-element-dd75627,
.elementor-element-dd75627 *,
.elementor-element-76d5b6f,
.elementor-element-76d5b6f * {
    font-weight: 700 !important;
}

.elementor-8774 .elementor-heading-title {
    font-weight: 700 !important;
}

.elementor-8774 .elementor-button {
    width: 185px !important;
    min-width: 185px !important;
    height: 34px !important;
    min-height: 34px !important;
    font-weight: 800 !important;
    font-stretch: 150% !important;
}

.elementor-8774 .elementor-button * {
    min-width: 0 !important;
    min-height: 0 !important;
    font-weight: 800 !important;
    font-stretch: 150% !important;
}

.elementor-8773.e-loop-item {
    height: 592px !important;
    overflow: hidden !important;
}

.elementor-element-dc63636 .elementor-8773.e-loop-item {
    height: 716px !important;
}

.elementor-8773 .elementor-element-0d0fd89 {
    height: 126px !important;
    overflow: hidden !important;
}

.elementor-8773 .elementor-element-0d0fd89 .elementor-widget-container,
.elementor-8773 .elementor-element-0d0fd89 .elementor-widget-container > * {
    display: -webkit-box !important;
    -webkit-line-clamp: 6 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
}

.elementor-8773 .elementor-button {
    width: 159px !important;
    min-width: 159px !important;
    height: 29px !important;
    min-height: 29px !important;
    font-weight: 800 !important;
    font-stretch: 150% !important;
}

.elementor-8773 .elementor-button * {
    min-width: 0 !important;
    min-height: 0 !important;
    font-weight: 800 !important;
    font-stretch: 150% !important;
}

.e-loop-item.type-memberships .elementor-element-e148907 {
    background-position: center !important;
    background-repeat: no-repeat !important;
    background-size: 25px 25px !important;
}

.e-loop-item.category-associate .elementor-element-e148907,
.e-loop-item.membership-level-associate .elementor-element-e148907 {
    background-image: url("{$member_associate_url}") !important;
}

.e-loop-item.category-ordinary .elementor-element-e148907,
.e-loop-item.membership-level-ordinary .elementor-element-e148907 {
    background-image: url("{$member_ordinary_url}") !important;
}

.e-loop-item.category-full .elementor-element-e148907,
.e-loop-item.membership-level-full .elementor-element-e148907 {
    background-image: url("{$member_full_url}") !important;
}

.e-loop-item.category-academic .elementor-element-e148907,
.e-loop-item.membership-level-academic .elementor-element-e148907 {
    background-image: url("{$member_academic_url}") !important;
}

@keyframes mask-in {
  from { -webkit-mask-position: 0 center; mask-position: 0 center; }
  to { -webkit-mask-position: 100% center; mask-position: 100% center; }
}

@keyframes mask-out {
  from { -webkit-mask-position: 100% center; mask-position: 100% center; }
  to { -webkit-mask-position: 0 center; mask-position: 0 center; }
}

.post-tags,
.tag-links {
    display: none !important;
}
CSS;
}

function cygma_migration_tools_design_kit_settings($assets) {
    return array(
        'system_colors' => array(
            array('_id' => 'primary', 'title' => 'Primary', 'color' => '#000000'),
            array('_id' => 'secondary', 'title' => 'Secondary', 'color' => '#2E76FF'),
            array('_id' => 'text', 'title' => 'Text', 'color' => '#000000'),
            array('_id' => 'accent', 'title' => 'Accent', 'color' => '#FF9D0B'),
        ),
        'custom_colors' => array(
            array('_id' => '8638455', 'title' => 'light blue', 'color' => '#83E0EF'),
            array('_id' => 'fbe895b', 'title' => 'Background grey', 'color' => '#F2ECE7'),
            array('_id' => '4d9c829', 'title' => 'Background white', 'color' => '#FFFFFF'),
        ),
        'typography_enable_styleguide_preview' => 'yes',
        'system_typography' => array(
            array('_id' => 'primary', 'title' => 'Primary', 'typography_typography' => 'custom', 'typography_font_family' => 'Roboto Flex custom', 'typography_font_weight' => '900'),
            array('_id' => 'secondary', 'title' => 'Secondary', 'typography_font_family' => 'Roboto Flex variable', 'typography_font_weight' => '500', 'typography_font_size' => array('unit' => 'px', 'size' => 34, 'sizes' => array()), 'typography_weight' => array('unit' => 'px', 'size' => 700, 'sizes' => array()), 'typography_width' => array('unit' => 'px', 'size' => 25, 'sizes' => array()), 'typography_line_height' => array('unit' => 'custom', 'size' => '85%', 'sizes' => array())),
            array('_id' => 'text', 'title' => 'Text', 'typography_typography' => 'custom', 'typography_font_family' => 'Roboto Flex variable', 'typography_font_weight' => '500', 'typography_font_size' => array('unit' => 'px', 'size' => 16, 'sizes' => array()), 'typography_weight' => array('unit' => 'px', 'size' => 500, 'sizes' => array()), 'typography_width' => array('unit' => 'px', 'size' => 140, 'sizes' => array()), 'typography_line_height' => array('unit' => 'custom', 'size' => '130%', 'sizes' => array()), 'typography_font_size_tablet' => array('unit' => 'px', 'size' => 15, 'sizes' => array()), 'typography_weight_tablet' => array('unit' => 'px', 'size' => 500, 'sizes' => array()), 'typography_width_tablet' => array('unit' => 'px', 'size' => 140, 'sizes' => array()), 'typography_line_height_tablet' => array('unit' => 'custom', 'size' => '140%', 'sizes' => array())),
            array('_id' => 'accent', 'title' => 'Accent', 'typography_typography' => 'custom', 'typography_font_family' => 'Roboto Flex custom'),
        ),
        'custom_typography' => array(),
        'default_generic_fonts' => 'Sans-serif',
        'site_name' => 'CYGMA',
        'container_width' => array('unit' => 'px', 'size' => 1262, 'sizes' => array()),
        'page_title_selector' => 'h1.entry-title',
        'hello_footer_copyright_text' => 'All rights reserved',
        'activeItemIndex' => 1,
        'viewport_md' => 768,
        'viewport_lg' => 1025,
        'colors_enable_styleguide_preview' => 'yes',
        'container_width_tablet' => array('unit' => 'px', 'size' => 580, 'sizes' => array()),
        'container_width_mobile' => array('unit' => 'px', 'size' => 580, 'sizes' => array()),
        'custom_css' => cygma_migration_tools_design_custom_css($assets),
        'body_typography_typography' => 'custom',
        'body_typography_font_family' => 'Roboto Flex variable',
        'body_typography_font_size' => array('unit' => 'px', 'size' => 16, 'sizes' => array()),
        'body_typography_weight' => array('unit' => 'px', 'size' => 500, 'sizes' => array()),
        'body_typography_width' => array('unit' => 'px', 'size' => 140, 'sizes' => array()),
        'body_typography_line_height' => array('unit' => 'custom', 'size' => '130%', 'sizes' => array()),
        '__globals__' => array('link_normal_color' => ''),
    );
}

function cygma_migration_tools_sync_design_kit() {
    $asset_sources = array(
        'roboto' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/RobotoFlex-VariableFont_GRADXOPQXTRAYOPQYTASYTDEYTFIYTLCYTUCopszslntwdthwght.ttf', 'filename' => 'RobotoFlex-VariableFont_GRADXOPQXTRAYOPQYTASYTDEYTFIYTLCYTUCopszslntwdthwght.ttf'),
        'venture_regular' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/03/Venture13Regular.woff2', 'filename' => 'Venture13Regular.woff2'),
        'venture_bold' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/03/Venture13Bold.woff2', 'filename' => 'Venture13Bold.woff2'),
        'venture_thin' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/03/Venture13Thin.woff2', 'filename' => 'Venture13Thin.woff2'),
        'hover_mask' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/hover-button-1-scaled.png', 'filename' => 'hover-button-1-scaled.png'),
        'newsletter_bg' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/newletter-bg-desktop-1.webp', 'filename' => 'newletter-bg-desktop-1.webp'),
        'logo' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/03/logo.svg', 'filename' => 'logo.svg', 'overwrite' => true),
        'mobile_logo' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/03/CYGMA-Logo-mobile.svg', 'filename' => 'CYGMA-Logo-mobile.svg', 'overwrite' => true),
        'member_associate' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/membership-symbol-01-white.svg', 'filename' => 'membership-symbol-01-white.svg'),
        'member_ordinary' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/membership-symbol-02-white.svg', 'filename' => 'membership-symbol-02-white.svg'),
        'member_full' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/membership-symbol-03-white.svg', 'filename' => 'membership-symbol-03-white.svg'),
        'member_academic' => array('source' => 'https://cygma.bonafideshops.com/wp-content/uploads/2026/04/membership-symbol-04-white.svg', 'filename' => 'membership-symbol-04-white.svg'),
    );
    $assets = array();

    foreach ($asset_sources as $key => $asset) {
        $synced = cygma_migration_tools_sync_design_asset($asset['source'], $asset['filename'], !empty($asset['overwrite']));

        if (is_wp_error($synced)) {
            return $synced;
        }

        $assets[$key] = $synced;
    }

    update_post_meta(4, '_elementor_page_settings', cygma_migration_tools_design_kit_settings($assets));
    clean_post_cache(4);

    if (did_action('elementor/loaded') && class_exists('Elementor\\Plugin')) {
        Elementor\Plugin::instance()->files_manager->clear_cache();
    }

    return rest_ensure_response(array(
        'kit' => 4,
        'assets' => $assets,
        'updated' => true,
    ));
}

function cygma_migration_tools_count_replacements($before, $after, $search) {
    return substr_count($before, $search) - substr_count($after, $search);
}

function cygma_migration_tools_sync_membership_level_terms() {
    cygma_migration_tools_register_membership_level_taxonomy();

    $levels = array(
        'academic' => 'Academic',
        'associate' => 'Associate',
        'full' => 'Full',
        'ordinary' => 'Ordinary',
    );
    $term_results = array();

    foreach ($levels as $slug => $name) {
        $term = term_exists($slug, 'membership-level');

        if (!$term) {
            $term = wp_insert_term($name, 'membership-level', array('slug' => $slug));
        }

        $term_results[$slug] = is_wp_error($term) ? $term->get_error_message() : true;
    }

    $posts = get_posts(array(
        'post_type' => 'memberships',
        'post_status' => 'publish',
        'numberposts' => -1,
        'fields' => 'ids',
    ));
    $assigned = array();

    foreach ($posts as $post_id) {
        $categories = get_the_terms($post_id, 'category');
        $slugs = is_array($categories) ? wp_list_pluck($categories, 'slug') : array();
        $matched = array_values(array_intersect(array_keys($levels), $slugs));

        if ($matched) {
            wp_set_object_terms($post_id, $matched, 'membership-level', false);
            $assigned[$post_id] = $matched;
        }
    }

    return array(
        'terms' => $term_results,
        'assigned' => count($assigned),
        'assigned_posts' => $assigned,
    );
}

function cygma_migration_tools_repair_members_elementor_value($value, &$counts) {
    if (is_array($value)) {
        if (isset($value['widgetType'], $value['settings']) && $value['widgetType'] === 'loop-grid' && is_array($value['settings'])) {
            $value['settings']['post_type'] = 'memberships';
            $value['settings']['posts_post_type'] = 'memberships';
            $value['settings']['query_post_type'] = 'memberships';
            $value['settings']['template_id'] = '8770';
            $counts['loop_grid_settings']++;
        }

        if (isset($value['widgetType'], $value['settings']) && $value['widgetType'] === 'taxonomy-filter' && is_array($value['settings'])) {
            $value['settings']['taxonomy'] = 'membership-level';
            $value['settings']['selected_taxonomy'] = 'membership-level';
            $value['settings']['filter_taxonomy'] = 'membership-level';
            $counts['taxonomy_filter_settings']++;
        }

        foreach ($value as $key => $item) {
            $value[$key] = cygma_migration_tools_repair_members_elementor_value($item, $counts);
        }

        return $value;
    }

    if ($value === 'member') {
        $counts['member_to_memberships']++;
        return 'memberships';
    }

    if ($value === 'category') {
        $counts['category_to_membership_level']++;
        return 'membership-level';
    }

    if ($value === '1818' || $value === 1818) {
        $counts['template_to_8770']++;
        return is_int($value) ? 8770 : '8770';
    }

    if (is_string($value)) {
        $next = str_replace('e-filter-a4eb1e7-category', 'e-filter-a4eb1e7-membership-level', $value);

        if ($next !== $value) {
            $counts['filter_key']++;
        }

        return $next;
    }

    return $value;
}

function cygma_migration_tools_repair_members_loop() {
    $page_id = 14;
    $data = get_post_meta($page_id, '_elementor_data', true);

    if (!$data) {
        return new WP_Error('cygma_missing_members_data', 'Members page Elementor data was not found.', array('status' => 404));
    }

    $before = $data;
    $term_sync = cygma_migration_tools_sync_membership_level_terms();
    $decoded = json_decode($data, true);

    if (!is_array($decoded)) {
        return new WP_Error('cygma_invalid_members_data', 'Members page Elementor data is not valid JSON.', array('status' => 500));
    }

    $counts = array(
        'loop_grid_settings' => 0,
        'taxonomy_filter_settings' => 0,
        'member_to_memberships' => 0,
        'category_to_membership_level' => 0,
        'template_to_8770' => 0,
        'filter_key' => 0,
    );
    $repaired = cygma_migration_tools_repair_members_elementor_value($decoded, $counts);
    $data = wp_json_encode($repaired);

    if ($data === $before) {
        return rest_ensure_response(array(
            'page' => $page_id,
            'updated' => false,
            'counts' => $counts,
            'term_sync' => $term_sync,
        ));
    }

    update_post_meta($page_id, '_elementor_data', wp_slash($data));
    clean_post_cache($page_id);

    if (did_action('elementor/loaded') && class_exists('Elementor\\Plugin')) {
        Elementor\Plugin::instance()->files_manager->clear_cache();
    }

    return rest_ensure_response(array(
        'page' => $page_id,
        'updated' => true,
        'counts' => $counts,
        'term_sync' => $term_sync,
    ));
}

function cygma_migration_tools_repair_home_news_elementor_value($value, &$counts) {
    if (is_array($value)) {
        if (isset($value['widgetType'], $value['settings']) && $value['widgetType'] === 'loop-grid' && is_array($value['settings'])) {
            $is_home_news_loop = isset($value['id']) && $value['id'] === '473ae46';
            $uses_staging_template = isset($value['settings']['template_id']) && (string) $value['settings']['template_id'] === '866';

            if ($is_home_news_loop || $uses_staging_template) {
                $value['settings']['post_type'] = 'post';
                $value['settings']['posts_post_type'] = 'post';
                $value['settings']['query_post_type'] = 'post';
                $value['settings']['template_id'] = '8773';
                $value['settings']['posts_per_page'] = '3';
                $counts['loop_grid_settings']++;
            }
        }

        foreach ($value as $key => $item) {
            $value[$key] = cygma_migration_tools_repair_home_news_elementor_value($item, $counts);
        }

        return $value;
    }

    if ($value === '866' || $value === 866) {
        $counts['template_to_8773']++;
        return is_int($value) ? 8773 : '8773';
    }

    return $value;
}

function cygma_migration_tools_repair_home_news_loop() {
    $page_id = 8765;
    $data = get_post_meta($page_id, '_elementor_data', true);

    if (!$data) {
        return new WP_Error('cygma_missing_home_data', 'Home page Elementor data was not found.', array('status' => 404));
    }

    $before = $data;
    $decoded = json_decode($data, true);

    if (!is_array($decoded)) {
        return new WP_Error('cygma_invalid_home_data', 'Home page Elementor data is not valid JSON.', array('status' => 500));
    }

    $counts = array(
        'loop_grid_settings' => 0,
        'template_to_8773' => 0,
    );
    $repaired = cygma_migration_tools_repair_home_news_elementor_value($decoded, $counts);
    $data = wp_json_encode($repaired);

    if ($data === $before) {
        return rest_ensure_response(array(
            'page' => $page_id,
            'updated' => false,
            'counts' => $counts,
        ));
    }

    update_post_meta($page_id, '_elementor_data', wp_slash($data));
    clean_post_cache($page_id);

    if (did_action('elementor/loaded') && class_exists('Elementor\\Plugin')) {
        Elementor\Plugin::instance()->files_manager->clear_cache();
    }

    return rest_ensure_response(array(
        'page' => $page_id,
        'updated' => true,
        'counts' => $counts,
    ));
}

function cygma_migration_tools_render_settings_page() {
    $options = cygma_migration_tools_options();
    ?>
    <div class="wrap">
        <h1>CYGMA Migration Tools</h1>
        <?php if (!empty($_GET['updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>
        <?php endif; ?>
        <p>Use these switches only during the approved CYGMA migration window. All features are disabled by default.</p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('cygma_migration_tools_save'); ?>
            <input type="hidden" name="action" value="cygma_migration_tools_save">
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Maintenance mode</th>
                    <td>
                        <label>
                            <input type="checkbox" name="maintenance" value="1" <?php checked($options['maintenance']); ?>>
                            Return 503 and noindex responses for visitors. Logged-in administrators can still browse the site.
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Redirect map</th>
                    <td>
                        <label>
                            <input type="checkbox" name="redirects" value="1" <?php checked($options['redirects']); ?>>
                            Enable core 301 redirects and 410 removals from the migration map.
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">News URLs</th>
                    <td>
                        <label>
                            <input type="checkbox" name="news_routing" value="1" <?php checked($options['news_routing']); ?>>
                            Make posts canonical at /news/[slug]/ and redirect old root post URLs.
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Member URLs</th>
                    <td>
                        <label>
                            <input type="checkbox" name="member_routing" value="1" <?php checked($options['member_routing']); ?>>
                            Make membership profiles canonical at /members/[slug]/ and redirect old /memberships/[slug]/ URLs.
                        </label>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save migration settings'); ?>
        </form>
    </div>
    <?php
}

function cygma_migration_tools_maintenance_enabled() {
    if (defined('CYGMA_MAINTENANCE_MODE') && CYGMA_MAINTENANCE_MODE) {
        return true;
    }

    return cygma_migration_tools_is_enabled('maintenance') || file_exists(WP_CONTENT_DIR . '/maintenance.flag');
}

add_action('send_headers', function () {
    if (cygma_migration_tools_maintenance_enabled()) {
        cygma_migration_tools_noindex_headers();
    }
});

add_action('template_redirect', function () {
    if (!cygma_migration_tools_maintenance_enabled()) {
        return;
    }

    if (is_user_logged_in() && current_user_can('manage_options')) {
        return;
    }

    cygma_migration_tools_noindex_headers();
    status_header(503);
    nocache_headers();

    if (!headers_sent()) {
        header('Retry-After: 3600', true);
    }

    $site_name = get_bloginfo('name') ?: 'CYGMA';
    ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title><?php echo esc_html($site_name); ?> - Maintenance</title>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 32px 18px;
            background: #f7faf9;
            color: #152329;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }
        main {
            width: min(100%, 680px);
            border-top: 4px solid #0f766e;
            padding-top: 28px;
        }
        p:first-child {
            margin: 0 0 14px;
            color: #0f766e;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        h1 {
            margin: 0;
            max-width: 12ch;
            font-size: clamp(34px, 8vw, 68px);
            line-height: 0.98;
        }
        p:last-child {
            max-width: 48ch;
            margin: 24px 0 0;
            color: #53636b;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <main>
        <p><?php echo esc_html($site_name); ?></p>
        <h1>Website maintenance</h1>
        <p>We are updating the website and will be back online shortly.</p>
    </main>
</body>
</html>
    <?php
    exit;
}, 0);

function cygma_migration_tools_redirect_map() {
    return array(
        '/about-us/' => array('status' => 301, 'target' => '/about/'),
        '/become-a-member/' => array('status' => 301, 'target' => '/membership/apply/'),
        '/how-to-apply/' => array('status' => 301, 'target' => '/membership/apply/'),
        '/membershippolicy/' => array('status' => 301, 'target' => '/membership-policy/'),
        '/contacts/' => array('status' => 301, 'target' => '/contact/'),
        '/blog/' => array('status' => 301, 'target' => '/news/'),
        '/blog-list/' => array('status' => 301, 'target' => '/news/'),
        '/blog-grid/' => array('status' => 301, 'target' => '/news/'),
        '/cyprus/' => array('status' => 410),
        '/team/' => array('status' => 410),
        '/events/' => array('status' => 410),
        '/faqs/' => array('status' => 410),
        '/elementor-page-6064/' => array('status' => 410),
        '/7330-2/' => array('status' => 410),
    );
}

function cygma_migration_tools_send_gone_response() {
    cygma_migration_tools_noindex_headers();
    status_header(410);
    nocache_headers();

    $site_name = get_bloginfo('name') ?: 'CYGMA';
    ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title><?php echo esc_html($site_name); ?> - Page removed</title>
</head>
<body>
    <main>
        <h1>Page removed</h1>
        <p>This page is no longer available.</p>
    </main>
</body>
</html>
    <?php
    exit;
}

function cygma_migration_tools_handle_redirect_map() {
    if (!cygma_migration_tools_is_enabled('redirects')) {
        return;
    }

    $path = cygma_migration_tools_current_path();
    $redirects = cygma_migration_tools_redirect_map();

    if (!isset($redirects[$path])) {
        return;
    }

    $rule = $redirects[$path];

    if ((int) $rule['status'] === 410) {
        cygma_migration_tools_send_gone_response();
    }

    if ((int) $rule['status'] === 301 && !empty($rule['target'])) {
        wp_safe_redirect(home_url($rule['target']), 301);
        exit;
    }
}

add_action('parse_request', 'cygma_migration_tools_handle_redirect_map', -1000);
add_action('template_redirect', 'cygma_migration_tools_handle_redirect_map', -1000);

function cygma_migration_tools_news_base() {
    return 'news';
}

function cygma_migration_tools_member_base() {
    return 'members';
}

function cygma_migration_tools_member_post_type() {
    return 'memberships';
}

function cygma_migration_tools_news_post_url($post) {
    if (!$post instanceof WP_Post || $post->post_type !== 'post') {
        return '';
    }

    return home_url('/' . cygma_migration_tools_news_base() . '/' . $post->post_name . '/');
}

function cygma_migration_tools_register_news_rewrite() {
    add_rewrite_rule('^' . cygma_migration_tools_news_base() . '/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');
}

function cygma_migration_tools_member_url($post) {
    if (!$post instanceof WP_Post || $post->post_type !== cygma_migration_tools_member_post_type()) {
        return '';
    }

    return home_url('/' . cygma_migration_tools_member_base() . '/' . $post->post_name . '/');
}

function cygma_migration_tools_register_member_rewrite() {
    add_rewrite_rule(
        '^' . cygma_migration_tools_member_base() . '/([^/]+)/?$',
        'index.php?' . cygma_migration_tools_member_post_type() . '=$matches[1]',
        'top'
    );
}

add_action('init', function () {
    if (cygma_migration_tools_is_enabled('news_routing')) {
        cygma_migration_tools_register_news_rewrite();
    }

    if (cygma_migration_tools_is_enabled('member_routing')) {
        cygma_migration_tools_register_member_rewrite();
    }
});

add_filter('post_link', function ($permalink, $post) {
    if (!cygma_migration_tools_is_enabled('news_routing')) {
        return $permalink;
    }

    $news_url = cygma_migration_tools_news_post_url($post);

    return $news_url ?: $permalink;
}, 10, 2);

add_filter('preview_post_link', function ($preview_link, $post) {
    if (!cygma_migration_tools_is_enabled('news_routing')) {
        return $preview_link;
    }

    $news_url = cygma_migration_tools_news_post_url($post);

    return $news_url ? add_query_arg('preview', 'true', $news_url) : $preview_link;
}, 10, 2);

add_filter('post_type_link', function ($permalink, $post) {
    if (!cygma_migration_tools_is_enabled('member_routing')) {
        return $permalink;
    }

    $member_url = cygma_migration_tools_member_url($post);

    return $member_url ?: $permalink;
}, 10, 2);

add_action('template_redirect', function () {
    if (!cygma_migration_tools_is_enabled('news_routing') || !is_singular('post')) {
        return;
    }

    $post = get_queried_object();
    $news_url = cygma_migration_tools_news_post_url($post);

    if (!$news_url) {
        return;
    }

    $path = cygma_migration_tools_current_path();
    $canonical_path = wp_parse_url($news_url, PHP_URL_PATH) ?: '/';

    if (trailingslashit($path) !== trailingslashit($canonical_path)) {
        wp_safe_redirect($news_url, 301);
        exit;
    }
}, 2);

add_action('template_redirect', function () {
    if (!cygma_migration_tools_is_enabled('member_routing') || !is_singular(cygma_migration_tools_member_post_type())) {
        return;
    }

    $member_url = cygma_migration_tools_member_url(get_queried_object());

    if (!$member_url) {
        return;
    }

    $path = cygma_migration_tools_current_path();
    $canonical_path = wp_parse_url($member_url, PHP_URL_PATH) ?: '/';

    if (trailingslashit($path) !== trailingslashit($canonical_path)) {
        wp_safe_redirect($member_url, 301);
        exit;
    }
}, 2);

add_filter('rank_math/frontend/canonical', function ($canonical) {
    if (!cygma_migration_tools_is_enabled('news_routing') || !is_singular('post')) {
        return $canonical;
    }

    $news_url = cygma_migration_tools_news_post_url(get_queried_object());

    return $news_url ?: $canonical;
});

add_filter('rank_math/frontend/canonical', function ($canonical) {
    if (!cygma_migration_tools_is_enabled('member_routing') || !is_singular(cygma_migration_tools_member_post_type())) {
        return $canonical;
    }

    $member_url = cygma_migration_tools_member_url(get_queried_object());

    return $member_url ?: $canonical;
});