<?php
/**
 * BMD 2026-10-29 — source-grounded search snippet improvement.
 * GSC (Contabo SerpBear, 2026-10-27):
 * "como saber se um site foi feito com wordpress" position 18.1
 * on the existing technology-identification post (desktop Brazil; 8 impressions).
 *
 * Keep canonical, post slug, original visible title and all body content.
 * This improves search intent alignment only; no ranking guarantees.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_gsc_tech_detection_oct29() {
    return !is_admin() && is_singular('post') &&
        (string)get_post_field('post_name',get_queried_object_id()) === 'como-descobrir-plataforma-do-site';
}

// SEO metadata now stored directly on WordPress post 4907 (Rank Math).
// Keep this historical file for audit only; no frontend title/description overrides.
