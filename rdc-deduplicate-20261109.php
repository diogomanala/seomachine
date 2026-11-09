<?php
/**
 * BMD: retain article 9137 as canonical, retire duplicate 9138 safely.
 * Do not delete text. A draft is reversible in wp-admin.
 * Redirect duplicate URL even when a stale internal link or old Google result exists.
 */
if (!defined('ABSPATH')) { exit; }
add_action('init', static function () {
    $key = 'bmd_rdc_duplicate_guard_20261109';
    if (get_option($key, false) !== false) { return; }
    $primary = get_post(9137);
    $duplicate = get_post(9138);
    if (!$primary || !$duplicate ||
        $primary->post_type !== 'post' || $duplicate->post_type !== 'post' ||
        $primary->post_status !== 'publish' ||
        $primary->post_name !== 'rdc-1002-2025-documentos-clinica-odontologica' ||
        $duplicate->post_name !== 'rdc-1002-2025-documentos-clinica-odontologica-2' ||
        $primary->post_title !== $duplicate->post_title ||
        strpos($duplicate->post_content, 'artigo 113') === false ||
        strpos($primary->post_content, 'artigo 113') === false) {
        update_option($key, 'guard-mismatch', false);
        return;
    }
    if ($duplicate->post_status === 'draft') {
        update_option($key, 'already-draft', false);
        return;
    }
    if ($duplicate->post_status !== 'publish') {
        update_option($key, 'unexpected-status', false);
        return;
    }
    $result = wp_update_post(array('ID' => 9138, 'post_status' => 'draft'), true);
    if (is_wp_error($result) || (int)$result !== 9138) {
        update_option($key, 'update-error', false);
        return;
    }
    update_option($key, 'duplicate-9138-set-to-draft', false);
    clean_post_cache(9137);
    clean_post_cache(9138);
}, 40);
add_action('template_redirect', static function () {
    $request_path = parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if (rtrim((string)$request_path, '/') === '/rdc-1002-2025-documentos-clinica-odontologica-2') {
        wp_safe_redirect(home_url('/rdc-1002-2025-documentos-clinica-odontologica/'), 301, 'BMD');
        exit;
    }
}, 0);
add_filter('the_content', static function ($html) {
    if (is_admin() || !is_singular('post') || !in_the_loop() || !is_main_query()) {
        return $html;
    }
    return str_replace(
        home_url('/rdc-1002-2025-documentos-clinica-odontologica-2/'),
        home_url('/rdc-1002-2025-documentos-clinica-odontologica/'),
        $html
    );
}, 99);
