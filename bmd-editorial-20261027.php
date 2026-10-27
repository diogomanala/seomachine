<?php
/**
 * BMD: one-time editorial correction of the 2026 automation comparison.
 * Original post HTML is saved in private post metadata before changing it.
 */
if (!defined('ABSPATH')) { exit; }
add_action('init', function () {
    $key = 'bmd_automacao_20261027_editorial_v1';
    if (get_option($key, false) !== false) { return; }
    $slug = 'melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa';
    $post = get_page_by_path($slug, OBJECT, 'post');
    if (!$post || $post->post_status !== 'publish') {
        update_option($key, 'skipped-post-not-found', false);
        return;
    }
    $backup = '_bmd_backup_editorial_20261027';
    if (metadata_exists('post', $post->ID, $backup)) {
        update_option($key, 'skipped-existing-backup', false);
        return;
    }
    $changes = array(
        'Testamos e analisamos as 7 principais plataformas' => 'Comparamos as funcionalidades divulgadas publicamente por 7 plataformas',
        'preços reais e adequação ao mercado brasileiro' => 'faixas de preço informadas publicamente e adequação ao mercado brasileiro',
        '451%' => '7',
        '14,5%' => '6',
        '12,2%' => '3',
        '77%' => '2026',
        'de aumento em leads qualificados reportado por empresas que adotam automação de marketing' => 'plataformas comparadas por recursos, integrações e uso no Brasil',
        'de aumento médio em produtividade de vendas com automação de marketing ativa' => 'critérios de avaliação para comparar as opções antes de contratar',
        'de redução em custos gerais de marketing reportada após implementação de automação' => 'aspectos que mudam o custo: contatos, envios e integrações',
        'dos usuários de automação de marketing relatam aumento nas conversões em 12 meses' => 'comparativo editorial; preços e recursos podem mudar ao longo do ano',
        'Em testes independentes de entregabilidade, a plataforma consistentemente aparece entre as três primeiras posições no mercado.' => 'A entregabilidade depende de fatores como autenticação do domínio, qualidade da lista, reputação de envio e configuração das campanhas.',
        'A conformidade nativa com LGPD é outro ponto relevante para empresas que precisam de tranquilidade jurídica.' => 'Recursos de consentimento e gestão de dados podem apoiar a adequação à LGPD, mas a conformidade depende dos processos adotados por cada empresa.',
        'LGPD nativa em toda a plataforma' => 'Recursos para gerir consentimento e dados',
        'A LGPD exige consentimento explícito para coleta e uso de dados pessoais para marketing.' => 'A LGPD exige uma base legal adequada para tratar dados pessoais em atividades de marketing. O consentimento é uma possibilidade, mas não a única, conforme a finalidade e o contexto.',
        'formulários de captura devem ter checkbox de consentimento claro, você precisa registrar quando e como cada contato consentiu, e deve oferecer mecanismo de cancelamento em cada comunicação.' => 'formulários devem informar a finalidade do tratamento, adotar a base legal pertinente, manter os registros necessários e facilitar o exercício de direitos dos titulares.',
        'mas o RD Station é a única construída com LGPD como prioridade desde o início.' => 'mas nenhuma ferramenta, isoladamente, torna uma operação automaticamente conforme à legislação.',
        'o RD Station é imbatível' => 'o RD Station pode ser uma opção relevante',
        'o Klaviyo não tem concorrente real' => 'o Klaviyo é uma opção relevante, dependendo das integrações e do volume de contatos'
    );
    $original = (string)$post->post_content;
    $updated = $original;
    foreach (array('451%', '14,5%', '12,2%', '77%') as $number) {
        if (substr_count($original, $number) !== 1) {
            update_option($key, 'skipped-metric-mismatch', false);
            return;
        }
    }
    $hits = 0;
    foreach ($changes as $from => $to) {
        if (strpos($updated, $from) !== false) {
            $updated = str_replace($from, $to, $updated);
            $hits++;
        }
    }
    if ($hits < 10) {
        update_option($key, 'skipped-missing-text', false);
        return;
    }
    $note = 'Critérios editoriais (revisado em outubro de 2026)';
    if (strpos($updated, $note) === false) {
        $updated .= '\n<p><small><strong>' . $note . ':</strong> A comparação considera documentação pública, recursos anunciados pelos fornecedores e cenários de uso. Não foi realizado teste prático padronizado das sete plataformas. Preços, planos e integrações podem mudar. Confirme as condições nos sites oficiais antes de contratar.</small></p>';
    }
    if (!add_post_meta($post->ID, $backup, $original, true)) {
        update_option($key, 'skipped-backup-failed', false);
        return;
    }
    $result = wp_update_post(array('ID' => $post->ID, 'post_content' => $updated), true);
    if (is_wp_error($result)) {
        delete_post_meta($post->ID, $backup);
        update_option($key, 'skipped-update-failed', false);
        return;
    }
    clean_post_cache($post->ID);
    if (has_action('litespeed_purge_post')) { do_action('litespeed_purge_post', $post->ID); }
    update_option($key, 'applied', false);
}, 20);
