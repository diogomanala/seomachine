<?php
/**
 * BMD 2026-10-29 — scoped frontend asset reduction for two standalone tools.
 *
 * Following removal of the incompatible global Elementor popup, the public
 * ROAS calculator and UTM generator contain zero Elementor component nodes
 * and no Elementor shortcodes. Both tools are authored in BMD's own HTML/JS.
 *
 * Only dequeue the exact Elementor frontend handles that are not used by
 * these two non-Elementor pages. Preserve Elementor in admin/edit/preview,
 * other posts, Elementor-authored pages, advertising, consent banners,
 * and the OceanWP mobile navigation.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_tool_frontend_without_elementor_oct29() {
    if (is_admin() || is_user_logged_in() || is_preview() || is_customize_preview()) { return false; }
    if (!is_page(array('calculadora-roas-cac-lucro', 'gerador-de-utm'))) { return false; }

    $post_id = get_queried_object_id();
    if (!$post_id || get_post_type($post_id) !== 'page') { return false; }

    // Fail closed if the page later migrates into Elementor or embeds it.
    if (get_post_meta($post_id,'_elementor_edit_mode',true) === 'builder') { return false; }
    $content = (string)get_post_field('post_content',$post_id);
    if (preg_match('~\[elementor-template\b|data-elementor-id|class=["\x27][^"\x27]*elementor-(?:element|widget|section)|elementor_library~i',$content)) {
        return false;
    }

    return true;
}

function bmd_dequeue_unused_elementor_frontend_oct29() {
    if (!bmd_tool_frontend_without_elementor_oct29()) { return; }
    foreach (array(
        'elementor-frontend',
        'elementor-frontend-modules',
        'elementor-webpack-runtime'
    ) as $handle) {
        wp_dequeue_script($handle);
    }
}

// Most scripts enqueue via wp_enqueue_scripts; the second hook runs before
// the standard footer script printer, catching late Elementor registrations.
add_action('wp_enqueue_scripts','bmd_dequeue_unused_elementor_frontend_oct29',10000);
add_action('wp_print_footer_scripts','bmd_dequeue_unused_elementor_frontend_oct29',1);
