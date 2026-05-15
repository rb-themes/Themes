<?php
/**
 * Plugin Name: CYGMA Maintenance Guard
 * Description: Enables a noindex maintenance response during CYGMA migration when wp-content/maintenance.flag exists or CYGMA_MAINTENANCE_MODE is true.
 */

if (!defined('ABSPATH')) {
    exit;
}

function cygma_maintenance_enabled() {
    if (defined('CYGMA_MAINTENANCE_MODE') && CYGMA_MAINTENANCE_MODE) {
        return true;
    }

    return file_exists(WP_CONTENT_DIR . '/maintenance.flag');
}

function cygma_maintenance_send_noindex_headers() {
    if (headers_sent()) {
        return;
    }

    header('X-Robots-Tag: noindex, nofollow, noarchive', true);
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true);
}

add_action('send_headers', function () {
    if (cygma_maintenance_enabled()) {
        cygma_maintenance_send_noindex_headers();
    }
});

add_action('template_redirect', function () {
    if (!cygma_maintenance_enabled()) {
        return;
    }

    if (is_user_logged_in() && current_user_can('manage_options')) {
        return;
    }

    cygma_maintenance_send_noindex_headers();
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
        :root {
            color-scheme: light;
            --cygma-text: #152329;
            --cygma-muted: #53636b;
            --cygma-line: #d9e0e3;
            --cygma-accent: #0f766e;
            --cygma-bg: #f7faf9;
        }
        * {
            box-sizing: border-box;
        }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 32px 18px;
            background: var(--cygma-bg);
            color: var(--cygma-text);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }
        main {
            width: min(100%, 680px);
            border-top: 4px solid var(--cygma-accent);
            padding-top: 28px;
        }
        p:first-child {
            margin: 0 0 14px;
            color: var(--cygma-accent);
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
            font-weight: 700;
        }
        p:last-child {
            max-width: 48ch;
            margin: 24px 0 0;
            color: var(--cygma-muted);
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