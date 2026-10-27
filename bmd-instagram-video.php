<?php
/**
 * BMD Instagram public video utility.
 * No login, cookies, bypass, proxy rotation, or third-party downloader APIs.
 * Supports media only when Instagram exposes a public file to logged-out users.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_ig_parse_link($value) {
    if (!is_string($value) || strlen($value) > 1400) { return false; }
    $p = wp_parse_url(trim($value));
    if (!$p || !in_array(strtolower($p['scheme'] ?? ''), array('https', 'http'), true)) { return false; }
    $host = strtolower($p['host'] ?? '');
    if (!in_array($host, array('instagram.com', 'www.instagram.com', 'm.instagram.com'), true)) { return false; }
    if (!empty($p['port']) || !empty($p['user']) || !empty($p['pass'])) { return false; }
    if (!preg_match('~^/(reel|reels|p|tv)/([A-Za-z0-9_-]{5,45})(?:/|$)~', $p['path'] ?? '', $m)) { return false; }
    return array('url'=>'https://www.instagram.com/'.($m[1] === 'reels' ? 'reel' : $m[1]).'/'.$m[2].'/', 'code'=>$m[2]);
}
function bmd_ig_allowed_media($value) {
    if (!is_string($value) || strlen($value) > 2800) { return false; }
    $u = wp_parse_url(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    if (!$u || strtolower($u['scheme'] ?? '') !== 'https' || !empty($u['port']) || !empty($u['user']) || !empty($u['pass'])) { return false; }
    $host = strtolower($u['host'] ?? '');
    if (!preg_match('/^[a-z0-9.-]+$/', $host)) { return false; }
    foreach (array('cdninstagram.com','fbcdn.net') as $suffix) {
        if ($host === $suffix || substr($host, -strlen('.'.$suffix)) === '.'.$suffix) { return $value; }
    }
    return false;
}
function bmd_ig_extract_public_video($html) {
    if (!is_string($html) || $html === '') { return ''; }
    if (preg_match_all('~<meta\b[^>]*>~i', $html, $tags)) {
        foreach ($tags[0] as $tag) {
            if (!preg_match('~\b(?:property|name)\s*=\s*["\x27]og:video(?::secure_url|:url)?["\x27]~i', $tag)) { continue; }
            if (preg_match('~\bcontent\s*=\s*(["\x27])(.*?)\1~is', $tag, $m)) {
                $candidate = html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if (bmd_ig_allowed_media($candidate)) { return $candidate; }
            }
        }
    }
    if (preg_match_all('~"(?:video_url|video_url_hd|video_url_sd)"\s*:\s*"((?:\\\\.|[^"\\\\])*)"~i', $html, $hits)) {
        foreach ($hits[1] as $encoded) {
            $candidate = json_decode('"'.$encoded.'"', true);
            if (is_string($candidate) && bmd_ig_allowed_media($candidate)) { return $candidate; }
        }
    }
    return '';
}
function bmd_ig_rate_limit($bucket, $limit, $period) {
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'anonymous');
    $key = 'bmdig_'.md5($bucket.'|'.$ip.'|'.wp_salt('nonce'));
    $count = (int)get_transient($key);
    if ($count >= $limit) { return false; }
    set_transient($key, $count + 1, $period);
    return true;
}
add_action('rest_api_init', function () {
    register_rest_route('bmd/v1', '/instagram', array(
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => function (WP_REST_Request $request) {
            if (!bmd_ig_rate_limit('lookup', 10, HOUR_IN_SECONDS)) {
                return new WP_Error('limit', 'Muitas tentativas. Aguarde e tente novamente mais tarde.', array('status'=>429));
            }
            $url = bmd_ig_parse_link($request->get_param('url'));
            if (!$url) { return new WP_Error('url', 'Cole um link válido de Reel ou publicação do Instagram.', array('status'=>400)); }
            if ($request->get_param('authorized') !== true) {
                return new WP_Error('rights', 'Confirme que o conteúdo é seu ou que você tem autorização para baixá-lo.', array('status'=>400));
            }
            $cache = 'bmd_ig_public_'.md5($url['code']);
            $media = get_transient($cache);
            if (!is_string($media) || !bmd_ig_allowed_media($media)) {
                $media = '';
                $paths = array($url['url'].'embed/captioned/', $url['url']);
                foreach ($paths as $target) {
                    $res = wp_safe_remote_get($target, array(
                        'timeout'=>9, 'redirection'=>2, 'limit_response_size'=>1250000,
                        'headers'=>array('Accept'=>'text/html', 'Accept-Language'=>'pt-BR,pt;q=0.9',
                            'User-Agent'=>'Mozilla/5.0 (compatible; BMD-PublicMedia/1.0)')
                    ));
                    if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200) { continue; }
                    $media = bmd_ig_extract_public_video(wp_remote_retrieve_body($res));
                    if ($media) { set_transient($cache, $media, 4 * MINUTE_IN_SECONDS); break; }
                }
            }
            if (!$media) {
                return rest_ensure_response(array(
                    'available'=>false, 'original'=>$url['url'],
                    'message'=>'O Instagram não disponibilizou um arquivo de vídeo acessível sem login. Se o criador habilitou o download, abra o Reel no aplicativo e escolha Compartilhar → Baixar.'
                ));
            }
            $exp = time() + 240;
            $encoded = rtrim(strtr(base64_encode($media), '+/', '-_'), '=');
            $payload = $url['code'].'.'.$exp.'.'.$encoded;
            $sig = hash_hmac('sha256', $payload, wp_salt('auth'));
            return rest_ensure_response(array('available'=>true,'original'=>$url['url'],
                'download'=>add_query_arg(array('action'=>'bmd_ig_download','token'=>$payload.'.'.$sig), admin_url('admin-ajax.php')),
                'message'=>'Vídeo público localizado. O link de download expira em 4 minutos.'));
        }
    ));
});
function bmd_ig_download_handler() {
    nocache_headers();
    if (!bmd_ig_rate_limit('file', 5, HOUR_IN_SECONDS)) { status_header(429); exit('Limite temporário de downloads.'); }
    $token = isset($_GET['token']) && is_string($_GET['token']) ? $_GET['token'] : '';
    if (strlen($token) > 4000 || !preg_match('~^([A-Za-z0-9_-]{5,45})\.([0-9]{10})\.([A-Za-z0-9_-]+)\.([a-f0-9]{64})$~D', $token, $m)) {
        status_header(400); exit('Link inválido.');
    }
    $payload = $m[1].'.'.$m[2].'.'.$m[3];
    if ((int)$m[2] < time() || (int)$m[2] > time() + 300 ||
        !hash_equals(hash_hmac('sha256', $payload, wp_salt('auth')), $m[4])) {
        status_header(403); exit('Link expirado ou inválido.');
    }
    $media = base64_decode(strtr($m[3], '-_', '+/'), true);
    if (!bmd_ig_allowed_media($media)) { status_header(400); exit('Endereço de vídeo inválido.'); }
    $tmp = wp_tempnam('bmd-ig-download.mp4');
    if (!$tmp) { status_header(503); exit('Download indisponível.'); }
    $max = 32 * 1024 * 1024;
    $response = wp_safe_remote_get($media, array(
        'timeout'=>35, 'redirection'=>0, 'stream'=>true, 'filename'=>$tmp,
        'limit_response_size'=>$max + 1,
        'headers'=>array('Accept'=>'video/mp4,application/octet-stream')
    ));
    $size = is_file($tmp) ? filesize($tmp) : 0;
    $expected = is_wp_error($response) ? 0 : (int)wp_remote_retrieve_header($response, 'content-length');
    $ctype = is_wp_error($response) ? '' : strtolower((string)wp_remote_retrieve_header($response,'content-type'));
    $valid = !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200 &&
             $size > 1024 && $size <= $max && (!$expected || $expected === $size) &&
             (strpos($ctype,'video/') === 0 || strpos($ctype,'application/octet-stream') === 0);
    if (!$valid) { @unlink($tmp); status_header(502); exit('Não foi possível transferir este arquivo público. Tente o download pelo Instagram.'); }
    header('Content-Type: video/mp4');
    header('Content-Length: '.$size);
    header('Content-Disposition: attachment; filename="reel-instagram-'.$m[1].'.mp4"');
    header('X-Content-Type-Options: nosniff');
    readfile($tmp);
    @unlink($tmp);
    exit;
}
add_action('wp_ajax_nopriv_bmd_ig_download', 'bmd_ig_download_handler');
add_action('wp_ajax_bmd_ig_download', 'bmd_ig_download_handler');

add_shortcode('bmd_instagram_downloader', function () {
    $endpoint = esc_url_raw(rest_url('bmd/v1/instagram'));
    ob_start();
    ?>
    <section class="bmd-ig" aria-label="Ferramenta para baixar vídeos públicos do Instagram">
      <div class="bmd-ig-head"><span class="bmd-ig-kicker">FERRAMENTA GRATUITA · SEM CADASTRO</span>
        <h2>Seu Reel, salvo em poucos passos.</h2>
        <p>Cole o link de um vídeo público que você criou ou tem permissão para baixar. Quando o arquivo estiver disponível sem login, você poderá salvá-lo em MP4.</p></div>
      <form class="bmd-ig-form" id="bmd-ig-form">
        <label for="bmd-ig-url">Link do Reel ou vídeo</label>
        <div class="bmd-ig-input"><input id="bmd-ig-url" type="url" inputmode="url" autocomplete="off" required placeholder="https://www.instagram.com/reel/..." maxlength="1400"><button id="bmd-ig-paste" type="button" class="bmd-ig-paste">Colar</button></div>
        <label class="bmd-ig-check"><input type="checkbox" id="bmd-ig-rights" required> <span>Sou o autor ou tenho autorização para baixar este conteúdo.</span></label>
        <button type="submit" class="bmd-ig-submit" id="bmd-ig-submit">Verificar e baixar vídeo <span aria-hidden="true">↗</span></button>
      </form>
      <div class="bmd-ig-result" id="bmd-ig-result" role="status" aria-live="polite" hidden></div>
      <p class="bmd-ig-privacy">Não pedimos sua senha e não armazenamos cópias dos vídeos. Alguns links públicos exigem login ou têm downloads desativados pelo autor.</p>
    </section>
    <div class="bmd-ig-content">
      <h2>Como baixar um vídeo do Instagram?</h2>
      <ol><li>Abra um Reel que você criou ou tem autorização para salvar.</li><li>Toque em <strong>Compartilhar → Copiar link</strong> e cole o endereço acima.</li><li>Se o vídeo estiver disponível publicamente, use o botão para salvar em MP4. Caso contrário, tente a opção de download nativa do aplicativo.</li></ol>
      <h2>Baixar Reels pelo próprio Instagram</h2>
      <p>No aplicativo, toque no botão de compartilhar do Reel e procure <strong>Baixar</strong>. A opção aparece apenas quando o autor permite, e pode incluir marca d'água e restrições de áudio. Contas privadas e publicações restritas não são atendidas por esta ferramenta.</p>
      <h2>Perguntas frequentes</h2>
      <details><summary>Precisa fazer login?</summary><p>Não. Por segurança, não solicitamos usuário, senha ou cookies. Isso também significa que publicações bloqueadas para visitantes sem login não podem ser extraídas aqui.</p></details>
      <details><summary>Funciona com conta privada?</summary><p>Não. Respeitamos as configurações de privacidade e a opção de download estabelecida pelos criadores.</p></details>
      <details><summary>O download mantém o áudio?</summary><p>Depende do arquivo disponibilizado pelo Instagram. Faixas musicais licenciadas podem estar ausentes até mesmo dos downloads realizados pelo aplicativo.</p></details>
      <details><summary>Posso republicar um vídeo baixado?</summary><p>Baixar um arquivo não transfere direitos autorais. Solicite autorização antes de reproduzir ou reutilizar material de terceiros.</p></details>
      <p class="bmd-ig-disclaimer">Ferramenta independente; não afiliada ao Instagram ou à Meta. Apenas arquivos públicos acessíveis sem autenticação e utilizados com autorização.</p>
    </div>
    <style>
    .bmd-ig{margin:8px 0 34px;background:#fff;border:1px solid #e6e9ee;border-radius:22px;box-shadow:0 14px 45px rgba(9,24,49,.065);overflow:hidden}
    .bmd-ig-head{padding:clamp(24px,5vw,44px);padding-bottom:20px;background:linear-gradient(135deg,#fff 20%,#fff7f3 100%)}
    .bmd-ig-kicker{display:inline-flex;font-size:11px;font-weight:800;letter-spacing:.1em;color:#c45736;margin-bottom:16px}
    .bmd-ig-head h2{font-size:clamp(27px,4vw,43px)!important;letter-spacing:-.045em;line-height:1.08;margin:0 0 14px!important;color:#152033}
    .bmd-ig-head p{font-size:16px;line-height:1.65;color:#526174;margin:0;max-width:650px}
    .bmd-ig-form{padding:0 clamp(24px,5vw,44px) 24px!important;margin:0!important}
    .bmd-ig-form>label:first-child{font-weight:750;display:block;margin-bottom:9px;font-size:14px;color:#273448}
    .bmd-ig-input{display:flex;gap:8px;padding:6px;border:1px solid #dce2e9;border-radius:13px;background:white;align-items:center}
    .bmd-ig-input:focus-within{border-color:#d56b45;box-shadow:0 0 0 3px rgba(213,107,69,.12)}
    .bmd-ig-input input{min-width:0;flex:1;border:0!important;outline:0!important;box-shadow:none!important;background:transparent!important;margin:0!important;padding:12px!important;font-size:15px!important;color:#162337!important}
    .bmd-ig-paste{border:0!important;background:#f0f3f6!important;color:#29384c!important;border-radius:9px!important;min-height:43px;font-weight:650;padding:0 16px!important}
    .bmd-ig-check{display:flex!important;align-items:flex-start;gap:10px;margin:19px 0!important;font-size:13px!important;line-height:1.5!important;color:#59677a!important;cursor:pointer}
    .bmd-ig-check input{flex:none;margin:3px 0!important;width:16px;height:16px;accent-color:#d25f37}
    .bmd-ig-submit{border:0!important;display:flex!important;justify-content:space-between;align-items:center;width:100%;background:#172338!important;color:#fff!important;font-weight:750;font-size:15px!important;min-height:56px;padding:0 20px!important;border-radius:12px!important}
    .bmd-ig-submit:hover{background:#293951!important}.bmd-ig-submit:disabled{opacity:.6;cursor:wait}
    .bmd-ig-result{margin:0 clamp(24px,5vw,44px) 20px;padding:17px;border-radius:12px;background:#f4f7fb;color:#283a4b;font-size:14px;line-height:1.6}
    .bmd-ig-result a{display:inline-flex;margin-top:13px;margin-right:9px;padding:10px 14px;border-radius:8px;background:#172338;color:white;text-decoration:none;font-weight:700}
    .bmd-ig-result a.bmd-ig-original{background:#e7ebef;color:#283a4b}
    .bmd-ig-privacy{margin:0;padding:17px clamp(24px,5vw,44px);background:#f8f9fb;border-top:1px solid #edf0f3;color:#627184;font-size:12px;line-height:1.6}
    .bmd-ig-content{max-width:760px;margin-bottom:45px}.bmd-ig-content h2{font-size:clamp(21px,3vw,27px);margin:35px 0 14px;line-height:1.3}
    .bmd-ig-content li{margin-bottom:9px}.bmd-ig-content details{border-bottom:1px solid #e7eaf0;padding:15px 0}
    .bmd-ig-content summary{cursor:pointer;font-weight:700;color:#26344b}.bmd-ig-content details p{margin:12px 0 0}
    .bmd-ig-disclaimer{margin-top:22px;font-size:12px;color:#68788a}
    @media(max-width:520px){.bmd-ig{border-radius:17px}.bmd-ig-head p{font-size:14px}.bmd-ig-input input{font-size:13px!important}.bmd-ig-input button{font-size:13px;padding:0 11px!important}}
    </style>
    <script>
    (function(){
      var f=document.getElementById('bmd-ig-form'),url=document.getElementById('bmd-ig-url'),
          rights=document.getElementById('bmd-ig-rights'),result=document.getElementById('bmd-ig-result'),
          submit=document.getElementById('bmd-ig-submit'),paste=document.getElementById('bmd-ig-paste');
      if(!f)return;
      paste.addEventListener('click',function(){
        if(!navigator.clipboard||!navigator.clipboard.readText){url.focus();return;}
        navigator.clipboard.readText().then(function(t){url.value=t;url.focus();}).catch(function(){url.focus();});
      });
      function anchor(href,title,cls){
        var a=document.createElement('a');a.href=href;a.textContent=title;a.className=cls||'';
        if(cls==='bmd-ig-original'){a.target='_blank';a.rel='noopener noreferrer';}
        return a;
      }
      f.addEventListener('submit',function(e){
        e.preventDefault();
        if(!rights.checked){rights.focus();return;}
        result.hidden=false;result.textContent='Verificando se o vídeo está disponível publicamente…';
        submit.disabled=true;submit.textContent='Verificando link…';
        fetch(<?php echo wp_json_encode($endpoint); ?>,{method:'POST',headers:{'Content-Type':'application/json'},
          body:JSON.stringify({url:url.value.trim(),authorized:true}),credentials:'omit'
        }).then(function(r){return r.json().then(function(d){if(!r.ok)throw Error(d.message||'Não foi possível verificar o link.');return d;});})
        .then(function(d){
          result.textContent=d.message||'Consulta concluída.';
          if(d.available&&d.download){result.appendChild(document.createElement('br'));result.appendChild(anchor(d.download,'Baixar MP4'));}
          if(d.original){result.appendChild(document.createElement('br'));result.appendChild(anchor(d.original,'Abrir Reel no Instagram','bmd-ig-original'));}
        }).catch(function(e){result.textContent=e.message||'Erro temporário. Tente novamente mais tarde.';})
        .finally(function(){submit.disabled=false;submit.textContent='Verificar e baixar vídeo ↗';});
      });
    })();
    </script>
    <?php
    return ob_get_clean();
});

add_action('init', function () {
    if (get_option('bmd_ig_page_created_20261027', false)) { return; }
    $p = get_page_by_path('baixar-video-instagram', OBJECT, 'page');
    if (!$p) {
        $id = wp_insert_post(array(
            'post_type'=>'page','post_status'=>'publish','post_name'=>'baixar-video-instagram',
            'post_title'=>'Baixar Vídeo do Instagram Online — Reels em MP4',
            'post_content'=>'[bmd_instagram_downloader]',
            'meta_input'=>array(
                'rank_math_title'=>'Baixar Vídeo do Instagram Online Grátis | Reels MP4',
                'rank_math_description'=>'Baixe seus Reels e vídeos públicos do Instagram quando disponíveis sem login. Cole o link, verifique e salve em MP4. Grátis e sem pedir senha.',
                'rank_math_focus_keyword'=>'baixar vídeo do instagram'
            ),
        ), true);
        if (is_wp_error($id) || !$id) { return; }
        $p = get_post($id);
    }
    update_option('bmd_ig_page_created_20261027', (int)$p->ID, false);
}, 32);

/** Retire the obsolete legacy tool reversibly and preserve a relevant 301. */
add_action('init', function() {
    if (!get_option('bmd_ig_page_created_20261027', false) || get_option('bmd_email_retired_20261027', false)) { return; }
    $old = get_page_by_path('extrator-de-emails-e-leads', OBJECT, 'page');
    if (!$old) { update_option('bmd_email_retired_20261027', 'not-found', false); return; }
    if ($old->post_status === 'trash' || wp_trash_post($old->ID)) {
        update_option('bmd_email_retired_20261027', (int)$old->ID, false);
    }
}, 34);
add_action('template_redirect', function(){
    $path = trim((string)(wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: ''), '/');
    if ($path === 'extrator-de-emails-e-leads') {
        wp_safe_redirect(home_url('/ferramentas-gratis/'), 301, 'BMD');
        exit;
    }
}, 0);


/** Repair an initial duplicate page using narrowly scoped matching. */
add_action('init', function () {
    $primary = get_page_by_path('baixar-video-instagram', OBJECT, 'page');
    $duplicate = get_page_by_path('baixar-video-instagram-2', OBJECT, 'page');
    if (!$primary || !$duplicate || $primary->ID === $duplicate->ID) { return; }
    if ($primary->post_status !== 'publish' || $duplicate->post_status !== 'publish') { return; }
    if ($primary->post_title !== $duplicate->post_title ||
        trim($primary->post_content) !== '[bmd_instagram_downloader]' ||
        trim($duplicate->post_content) !== '[bmd_instagram_downloader]') { return; }
    wp_trash_post($duplicate->ID);
}, 38);
add_action('template_redirect', function () {
    $path = trim((string)(wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: ''), '/');
    if ($path !== 'baixar-video-instagram-2') { return; }
    wp_safe_redirect(home_url('/baixar-video-instagram/'), 301, 'BMD');
    exit;
}, 0);

/** Keep the tools directory SERP snippet aligned with its visible inventory. */
add_action('init', function () {
    if (get_option('bmd_instagram_tools_seo_v1', false)) { return; }
    $hub = get_page_by_path('ferramentas-gratis', OBJECT, 'page');
    if (!$hub || $hub->post_status !== 'publish') { return; }
    update_post_meta($hub->ID, 'rank_math_description',
        'Ferramentas grátis para marketing digital: baixe seus Reels públicos do Instagram, calcule ROAS e CAC, gere UTMs e remova metadados. Sem cadastro.');
    update_option('bmd_instagram_tools_seo_v1', $hub->ID, false);
}, 42);
