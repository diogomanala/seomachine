<?php
/**
 * BMD 29/10/2026 — Use the local WordPress copy of the affiliate banner.
 *
 * On tool and article pages this specific block previously loaded an
 * unmeasured image from a different domain, with no width/height attributes.
 * Preserve affiliate URL and widget settings. Filter only the identified
 * block HTML; nothing is changed in the WordPress widgets database.
 *
 * Local WordPress media attachment #8913 has actual 600×750 dimensions.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('widget_block_content', function($content, $instance, $widget) {
    if (is_admin() || !is_string($content)) { return $content; }
    $old = 'https://thirdsectorsocialmedia.com/wp-content/webp-express/webp-images/uploads/2025/07/cupom-hostinger.jpg.webp';
    if (strpos($content, $old) === false) { return $content; }

    $attachment_id = 8913;
    if (get_post_type($attachment_id) !== 'attachment') { return $content; }
    $url = wp_get_attachment_url($attachment_id);
    $meta = wp_get_attachment_metadata($attachment_id);
    if (!$url || !is_array($meta)) { return $content; }

    $width = isset($meta['width']) ? (int)$meta['width'] : 0;
    $height = isset($meta['height']) ? (int)$meta['height'] : 0;
    if ($width < 200 || $height < 200) { return $content; }

    // Original widget markup has the exact double-quoted HTML src below.
    // Modify no other image or widget, and leave the affiliate <a> untouched.
    $find = 'src="' . $old . '"';
    if (strpos($content, $find) === false) { return $content; }
    $replace = 'src="' . esc_url($url) . '" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"';
    return str_replace($find, $replace, $content);
}, 20, 3);
