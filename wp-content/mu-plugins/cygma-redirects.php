<?php
/**
 * Plugin Name: CYGMA Redirect Map
 * Description: Applies CYGMA migration redirects and 410 responses from the core redirect map.
 */

if (!defined('ABSPATH')) {
    exit;
}

function cygma_redirect_map() {
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

function cygma_current_request_path() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $path = wp_parse_url($request_uri, PHP_URL_PATH);

    if (!$path) {
        return '/';
    }

    return trailingslashit('/' . ltrim($path, '/'));
}

function cygma_send_gone_response() {
    if (!headers_sent()) {
        header('X-Robots-Tag: noindex, nofollow, noarchive', true);
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true);
    }

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

add_action('template_redirect', function () {
    $path = cygma_current_request_path();
    $redirects = cygma_redirect_map();

    if (!isset($redirects[$path])) {
        return;
    }

    $rule = $redirects[$path];

    if ((int) $rule['status'] === 410) {
        cygma_send_gone_response();
    }

    if ((int) $rule['status'] === 301 && !empty($rule['target'])) {
        wp_safe_redirect(home_url($rule['target']), 301);
        exit;
    }
}, 1);