<?php
/**
 * BMD: avoid a globally injected Elementor quote popup on two self-service
 * utilities where the reCAPTCHA JS is deliberately excluded.
 *
 * This page-specific incompatibility causes an unusable extra contact form
 * and loads popup assets over a browser-only calculator/UTM generator.
 * Keep Elementor Pro itself, every other popup location, ad slots, the
 * original form template and plugin settings untouched.
 */
if (!defined('ABSPATH')) { exit; }

add_action('wp', function() {
    if (is_admin() || is_preview() || is_customize_preview()) { return; }
    if (!is_page(array('calculadora-roas-cac-lucro','gerador-de-utm'))) { return; }
    if (!class_exists('\ElementorPro\Plugin')) { return; }
    $plugin = \ElementorPro\Plugin::instance();
    if (!isset($plugin->modules_manager)) { return; }
    $popup_module = $plugin->modules_manager->get_modules('popup');
    if (!$popup_module || !is_callable(array($popup_module,'print_popups'))) { return; }
    // Elementor Pro 3.33.2 registers this exact wp_footer callback.
    remove_action('wp_footer', array($popup_module,'print_popups'));
}, 30);
