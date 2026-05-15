<?php
/**
 * Plugin Name: CYGMA Migration Tools
 * Description: Controlled maintenance, redirect, and news URL tools for the CYGMA redesign migration.
 * Version: 0.1.0
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
    );

    update_option(CYGMA_MIGRATION_TOOLS_OPTION, $next, false);

    if ($previous['news_routing'] !== $next['news_routing']) {
        cygma_migration_tools_register_news_rewrite();
        flush_rewrite_rules(false);
    }

    wp_safe_redirect(add_query_arg('updated', '1', wp_get_referer() ?: admin_url('tools.php?page=cygma-migration-tools')));
    exit;
});

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

add_action('template_redirect', function () {
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
}, 1);

function cygma_migration_tools_news_base() {
    return 'news';
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

add_action('init', function () {
    if (cygma_migration_tools_is_enabled('news_routing')) {
        cygma_migration_tools_register_news_rewrite();
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

add_filter('rank_math/frontend/canonical', function ($canonical) {
    if (!cygma_migration_tools_is_enabled('news_routing') || !is_singular('post')) {
        return $canonical;
    }

    $news_url = cygma_migration_tools_news_post_url(get_queried_object());

    return $news_url ?: $canonical;
});