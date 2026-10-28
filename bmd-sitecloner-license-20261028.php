<?php
/**
 * One-time, guarded editorial correction of SiteCloner Pro licensing.
 * Uses visible paragraph text for matching so WordPress inline markup does not
 * prevent safe updates. Backs up the original HTML before any write.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $flag = 'bmd_sitecloner_license_20261028_v2';
    if (get_option($flag, false) !== false) { return; }

    $post = get_page_by_path('como-clonar-um-site-completo', OBJECT, 'post');
    if (!$post || $post->post_status !== 'publish') { return; }

    $backup_key = '_bmd_backup_sitecloner_license_20261028_v2';
    if (metadata_exists('post', $post->ID, $backup_key)) {
        update_option($flag, 'skipped-backup-exists', false);
        return;
    }

    $replacement = array(
        'intro' => 'O SiteCloner Pro oferece licença de 1 ano, com pagamento único e sem mensalidade. Durante a vigência, estão incluídas capturas ilimitadas, Editor Pro e atualizações. Consulte o preço atual e as condições da garantia de 7 dias na página oficial.',
        'faq' => 'Não. A licença do SiteCloner Pro tem pagamento único e validade de 1 ano, sem mensalidade nem cobrança de créditos por captura durante sua vigência. Confira o preço atualizado na página oficial.',
        'cta' => 'Licença de 1 ano com pagamento único, capturas ilimitadas durante a vigência, atualizações incluídas e garantia de 7 dias. Consulte as condições atuais na página oficial.'
    );
    $counts = array('intro' => 0, 'faq' => 0, 'cta' => 0);
    $original = (string) $post->post_content;

    $updated = preg_replace_callback('~(<p\b[^>]*>)(.*?)(</p>)~is', function ($matches) use (&$counts, $replacement) {
        $text = html_entity_decode(wp_strip_all_tags($matches[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('~\s+~u', ' ', str_replace("\xC2\xA0", ' ', $text)));
        $type = '';
        if (strpos($text, 'O acesso custa R$ 57') !== false && strpos($text, 'vitalício') !== false) {
            $type = 'intro';
        } elseif (strpos($text, 'SiteCloner Pro custa R$ 57') !== false && strpos($text, 'créditos por captura') !== false) {
            $type = 'faq';
        } elseif (strpos($text, 'Pagamento único de R$ 57') !== false && strpos($text, 'vitalício') !== false) {
            $type = 'cta';
        }
        if ($type === '') { return $matches[0]; }
        // Fail closed on linked or interactive markup; only simple paragraphs may be rewritten.
        if (preg_match('~<(?:a|img|iframe|button|script|style)\b~i', $matches[2])) {
            $counts[$type] += 100;
            return $matches[0];
        }
        $counts[$type]++;
        return $matches[1] . esc_html($replacement[$type]) . $matches[3];
    }, $original);

    if (!is_string($updated) || $counts !== array('intro' => 1, 'faq' => 1, 'cta' => 1)) {
        update_option($flag, 'skipped-match-count-' . implode('-', array_values($counts)), false);
        return;
    }
    if ($updated === $original || strpos($updated, 'R$ 57') !== false || strpos($updated, 'acesso vitalício') !== false) {
        update_option($flag, 'skipped-post-validation', false);
        return;
    }
    if (!add_post_meta($post->ID, $backup_key, $original, true)) {
        update_option($flag, 'skipped-backup-failed', false);
        return;
    }
    $result = wp_update_post(array('ID' => $post->ID, 'post_content' => $updated), true);
    if (is_wp_error($result)) {
        update_option($flag, 'failed-write', false);
        return;
    }
    clean_post_cache($post->ID);
    if (has_action('litespeed_purge_post')) { do_action('litespeed_purge_post', $post->ID); }
    update_option($flag, 'applied', false);
}, 25);

/**
 * Safety-net for the public article until a successful one-time DB migration.
 * Scoped to the known slug and the_content; no price is stored in the output.
 * Fails closed for paragraphs that contain interactive elements.
 */
add_filter('the_content', function ($html) {
    if (is_admin() || !is_singular('post') || get_post_field('post_name', get_queried_object_id()) !== 'como-clonar-um-site-completo') {
        return $html;
    }
    $replacement = array(
        'intro' => 'O SiteCloner Pro oferece licença de 1 ano com pagamento único, sem mensalidade. Durante a vigência, estão incluídas capturas ilimitadas e acesso ao Editor Pro. Consulte o valor atualizado e as condições de garantia na página oficial.',
        'faq' => 'Não. A licença do SiteCloner Pro tem pagamento único e validade de 1 ano, sem mensalidade nem cobrança de créditos por captura durante a vigência. Confira o valor atualizado na página oficial.',
        'cta' => 'Licença de 1 ano com pagamento único, capturas ilimitadas durante a vigência, atualizações incluídas e garantia de 7 dias. Consulte as condições atuais na página oficial.'
    );
    return preg_replace_callback('~(<p\\b[^>]*>)(.*?)(</p>)~is', function ($parts) use ($replacement) {
        $text = html_entity_decode(wp_strip_all_tags($parts[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim(preg_replace('~\\s+~u', ' ', str_replace("\\xC2\\xA0", ' ', $text)));
        if (preg_match('~<(?:a|button|iframe|form|script|style)\\b~i', $parts[2])) return $parts[0];
        if (strpos($text, 'O acesso custa R$ 57') !== false && strpos($text, 'vitalício') !== false) {
            return $parts[1] . esc_html($replacement['intro']) . $parts[3];
        }
        if (strpos($text, 'SiteCloner Pro custa R$ 57') !== false && strpos($text, 'créditos por captura') !== false) {
            return $parts[1] . esc_html($replacement['faq']) . $parts[3];
        }
        if (strpos($text, 'Pagamento único de R$ 57') !== false && strpos($text, 'vitalício') !== false) {
            return $parts[1] . esc_html($replacement['cta']) . $parts[3];
        }
        return $parts[0];
    }, $html);
}, 80);
