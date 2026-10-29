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

add_filter('rank_math/frontend/title',function($value) {
    if (!bmd_gsc_tech_detection_oct29()) { return $value; }
    return 'Como Saber se um Site É WordPress? Descubra a Plataforma';
},85);

add_filter('rank_math/frontend/description',function($value) {
    if (!bmd_gsc_tech_detection_oct29()) { return $value; }
    return 'Aprenda a identificar WordPress, Elementor, Shopify ou outras tecnologias pelo código-fonte, Wappalyzer e WhatCMS. Veja os sinais e suas limitações.';
},85);
