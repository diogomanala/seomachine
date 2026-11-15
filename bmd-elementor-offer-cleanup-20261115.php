<?php
/**
 * BMD 2026-11-15: guarded one-time cleanup of discontinued SiteCloner Elementor
 * converter claims. Revisions + original post bodies are preserved, both in
 * WordPress private postmeta and in three versioned GitHub JSON files.
 *
 * The legacy URL/slugs stay unchanged. Never silently overwrite newer edits.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    if (get_option('bmd_elementor_converter_cleanup_20261115_done', '') === '1') {
        return;
    }
    $files = array(
        'editorial/patches/bmd-8600-elementor-correction-20261115.json',
        'editorial/patches/bmd-8623-elementor-correction-20261115.json',
        'editorial/patches/bmd-4907-elementor-correction-20261115.json'
    );
    $all_ok = true;
    $results = array();
    foreach ($files as $relative) {
        $absolute = __DIR__ . '/' . $relative;
        if (!is_readable($absolute)) {
            $results[$relative] = 'source_missing';
            $all_ok = false;
            continue;
        }
        $payload = json_decode(file_get_contents($absolute), true);
        if (!is_array($payload) || empty($payload['post_id']) ||
            empty($payload['slug']) || !isset($payload['original_body'], $payload['new_body'])) {
            $results[$relative] = 'bad_manifest';
            $all_ok = false;
            continue;
        }
        $id = (int)$payload['post_id'];
        $marker = '_bmd_elementor_converter_cleanup_20261115';
        $post = get_post($id);
        if (!$post || $post->post_status !== 'publish' || $post->post_name !== $payload['slug']) {
            $results[$id] = 'missing_or_slug_changed';
            $all_ok = false;
            continue;
        }
        if (get_post_meta($id, $marker, true) === $payload['patch_id']) {
            $results[$id] = 'already_applied';
            continue;
        }
        // A concurrent editorial change is never overwritten.
        if (!hash_equals(hash('sha256', $payload['original_body']), hash('sha256', $post->post_content))) {
            $results[$id] = 'content_changed_since_review';
            $all_ok = false;
            continue;
        }
        $backup_key = '_bmd_before_converter_cleanup_20261115';
        if (!metadata_exists('post', $id, $backup_key)) {
            add_post_meta($id, $backup_key, array(
                'post_content' => $post->post_content,
                'post_title' => $post->post_title,
                'post_excerpt' => $post->post_excerpt
            ), true);
        }
        $update = array('ID' => $id, 'post_content' => wp_slash($payload['new_body']));
        if (!empty($payload['new_title'])) {
            $update['post_title'] = $payload['new_title'];
        }
        if (isset($payload['new_excerpt']) && $payload['new_excerpt'] !== null) {
            $update['post_excerpt'] = $payload['new_excerpt'];
        }

        // Restore existing trusted WordPress HTML (styles, HTML blocks, embeds)
        // verbatim without a frontend visitor's lack of unfiltered_html rights
        // removing tags during the one-time server-side editorial revision.
        if (function_exists('kses_remove_filters')) { kses_remove_filters(); }
        $written = wp_update_post($update, true);
        if (function_exists('kses_init_filters')) { kses_init_filters(); }

        if (is_wp_error($written) || !$written) {
            $results[$id] = 'save_failed';
            $all_ok = false;
            continue;
        }
        clean_post_cache($id);
        $saved = get_post($id);
        if (!$saved || !hash_equals(hash('sha256', $payload['new_body']), hash('sha256', $saved->post_content))) {
            $results[$id] = 'saved_body_mismatch_review_required';
            $all_ok = false;
            continue;
        }
        update_post_meta($id, $marker, $payload['patch_id']);
        $results[$id] = 'updated_and_verified';
    }
    update_option('bmd_elementor_converter_cleanup_20261115_status', $results, false);
    if ($all_ok) {
        update_option('bmd_elementor_converter_cleanup_20261115_done', '1', false);
    }
}, 90);
