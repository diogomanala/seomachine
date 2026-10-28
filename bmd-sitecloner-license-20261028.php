<?php
/**
 * One-time reconciliation of SiteCloner Pro editorial licensing terms.
 * Intentionally limited to one published post and three exact excerpts.
 * Saves the original HTML in private postmeta before writing.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    $flag = 'bmd_sitecloner_license_20261028_v1';
    if (get_option($flag, false) !== false) { return; }

    $post = get_page_by_path('como-clonar-um-site-completo', OBJECT, 'post');
    if (!$post || $post->post_status !== 'publish') {
        return;
    }

    $backup_key = '_bmd_backup_sitecloner_license_20261028';
    if (metadata_exists('post', $post->ID, $backup_key)) {
        update_option($flag, 'skipped-existing-backup', false);
        return;
    }

    $replacements = array(
        'O acesso custa R$ 57 em pagamento único, sem mensalidade e sem limite de capturas. A compra inclui acesso vitalício, atualizações e garantia de 7 dias.'
            => 'O SiteCloner Pro oferece licença de 1 ano, em pagamento único, sem mensalidade. Durante a vigência, você pode realizar capturas ilimitadas e utilizar o Editor Pro incluso. Consulte o valor atualizado e a garantia de 7 dias na página oficial.',
        'Não. O SiteCloner Pro custa R$ 57 em pagamento único, sem mensalidade e sem cobrança de créditos por captura.'
            => 'Não. A licença do SiteCloner Pro é paga uma única vez por 1 ano, sem mensalidade e sem cobrança de créditos por captura durante sua vigência. Consulte o valor atual na página oficial.',
        'Pagamento único de R$ 57, acesso vitalício, atualizações incluídas e garantia de 7 dias.'
            => 'Licença de 1 ano com pagamento único, capturas ilimitadas durante a vigência, atualizações incluídas e garantia de 7 dias. Veja as condições atuais na página oficial.'
    );

    $original = (string) $post->post_content;
    foreach ($replacements as $old => $new) {
        if (substr_count($original, $old) !== 1) {
            update_option($flag, 'skipped-exact-text-mismatch', false);
            return;
        }
    }

    $updated = str_replace(array_keys($replacements), array_values($replacements), $original);
    if ($updated === $original || substr_count($updated, 'R$ 57') !== 0 || stripos($updated, 'acesso vitalício') !== false) {
        update_option($flag, 'skipped-validation', false);
        return;
    }
    if (!add_post_meta($post->ID, $backup_key, $original, true)) {
        update_option($flag, 'skipped-backup-failure', false);
        return;
    }

    $result = wp_update_post(array('ID' => $post->ID, 'post_content' => $updated), true);
    if (is_wp_error($result)) {
        update_option($flag, 'failed-write', false);
        return;
    }
    clean_post_cache($post->ID);
    if (has_action('litespeed_purge_post')) {
        do_action('litespeed_purge_post', $post->ID);
    }
    update_option($flag, 'applied', false);
}, 25);
