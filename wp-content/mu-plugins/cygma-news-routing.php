<?php
/**
 * Plugin Name: CYGMA News Routing
 * Description: Makes native WordPress posts canonical under /news/[slug]/ for the CYGMA redesign.
 */

if (!defined('ABSPATH')) {
    exit;
}

function cygma_news_base() {
    return 'news';
}

function cygma_news_routing_version() {
    return '20260515';
}

function cygma_news_post_url($post) {
    if (!$post instanceof WP_Post || $post->post_type !== 'post') {
        return '';
    }

    return home_url('/' . cygma_news_base() . '/' . $post->post_name . '/');
}

add_action('init', function () {
    add_rewrite_rule('^' . cygma_news_base() . '/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');

    if (get_option('cygma_news_routing_version') !== cygma_news_routing_version()) {
        flush_rewrite_rules(false);
        update_option('cygma_news_routing_version', cygma_news_routing_version(), false);
    }
});

add_filter('post_link', function ($permalink, $post) {
    $news_url = cygma_news_post_url($post);

    return $news_url ?: $permalink;
}, 10, 2);

add_filter('preview_post_link', function ($preview_link, $post) {
    $news_url = cygma_news_post_url($post);

    return $news_url ? add_query_arg('preview', 'true', $news_url) : $preview_link;
}, 10, 2);

add_action('template_redirect', function () {
    if (!is_singular('post')) {
        return;
    }

    $post = get_queried_object();
    $news_url = cygma_news_post_url($post);

    if (!$news_url) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $path = wp_parse_url($request_uri, PHP_URL_PATH) ?: '/';
    $canonical_path = wp_parse_url($news_url, PHP_URL_PATH) ?: '/';

    if (trailingslashit($path) !== trailingslashit($canonical_path)) {
        wp_safe_redirect($news_url, 301);
        exit;
    }
}, 2);

add_filter('rank_math/frontend/canonical', function ($canonical) {
    if (!is_singular('post')) {
        return $canonical;
    }

    $news_url = cygma_news_post_url(get_queried_object());

    return $news_url ?: $canonical;
});