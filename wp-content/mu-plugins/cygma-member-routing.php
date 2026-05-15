<?php
/**
 * Plugin Name: CYGMA Member Routing
 * Description: Makes membership profiles canonical under /members/[slug]/ for the CYGMA redesign.
 */

if (!defined('ABSPATH')) {
    exit;
}

function cygma_member_base() {
    return 'members';
}

function cygma_member_post_type() {
    return 'memberships';
}

function cygma_member_routing_version() {
    return '20260515';
}

function cygma_member_url($post) {
    if (!$post instanceof WP_Post || $post->post_type !== cygma_member_post_type()) {
        return '';
    }

    return home_url('/' . cygma_member_base() . '/' . $post->post_name . '/');
}

add_action('init', function () {
    add_rewrite_rule(
        '^' . cygma_member_base() . '/([^/]+)/?$',
        'index.php?' . cygma_member_post_type() . '=$matches[1]',
        'top'
    );

    if (get_option('cygma_member_routing_version') !== cygma_member_routing_version()) {
        flush_rewrite_rules(false);
        update_option('cygma_member_routing_version', cygma_member_routing_version(), false);
    }
});

add_filter('post_type_link', function ($permalink, $post) {
    $member_url = cygma_member_url($post);

    return $member_url ?: $permalink;
}, 10, 2);

add_action('template_redirect', function () {
    if (!is_singular(cygma_member_post_type())) {
        return;
    }

    $member_url = cygma_member_url(get_queried_object());

    if (!$member_url) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $path = wp_parse_url($request_uri, PHP_URL_PATH) ?: '/';
    $canonical_path = wp_parse_url($member_url, PHP_URL_PATH) ?: '/';

    if (trailingslashit($path) !== trailingslashit($canonical_path)) {
        wp_safe_redirect($member_url, 301);
        exit;
    }
}, 2);

add_filter('rank_math/frontend/canonical', function ($canonical) {
    if (!is_singular(cygma_member_post_type())) {
        return $canonical;
    }

    $member_url = cygma_member_url(get_queried_object());

    return $member_url ?: $canonical;
});