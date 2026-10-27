<?php
/**
 * One-time recovery of three obsolete Twitter/X guides.
 * Exact post ID/slug/status guard; original content, title and SEO description
 * saved as private post metadata. Run once and remove this migration file.
 */
if(!defined('ABSPATH'))exit;
add_action('init',function(){
$jobs=array(
195=>array(
'slug'=>'o-que-e-twitter-pra-que-serve',
'title'=>'O Que É X (Twitter), Como Funciona e Para Que Serve?',
'description'=>'Entenda o que é X (antigo Twitter), como funcionam posts, seguidores, reposts, hashtags, mensagens e os limites atuais de publicação.',
'content'=><<<'POST195'
<p><strong>Atualizado em outubro de 2026.</strong> O Twitter, hoje chamado de <strong>X</strong>, é uma rede de publicações e conversas em que pessoas, empresas e criadores compartilham textos, imagens, vídeos e links. A plataforma continua conhecida pelos debates em tempo real, notícias, comunidades e pela possibilidade de conversar publicamente com outros perfis.</p>
<p>Se você encontrou uma explicação antiga dizendo que o Twitter aceita somente 140 caracteres ou que precisa de sites externos para postar imagens, atenção: essas informações não correspondem ao funcionamento atual. Veja o essencial, com orientações e links oficiais.</p>
<h2>O que é o Twitter, agora chamado de X?</h2>
<p>O X é uma plataforma social baseada em perfis e publicações. Você pode seguir contas, ler posts na linha do tempo, comentar, republicar, citar, compartilhar links e participar de conversas. O nome Twitter continua muito usado em pesquisas e no vocabulário popular, embora a marca atual seja X.</p>
<h2>Como funciona o X na prática?</h2>
<ol><li><strong>Perfil:</strong> representa uma pessoa, organização ou projeto, com nome de usuário, descrição e publicações.</li>
<li><strong>Seguir:</strong> permite acompanhar os conteúdos de uma conta na sua experiência de navegação.</li>
<li><strong>Post:</strong> é a publicação com texto e, quando apropriado, fotos, GIFs, vídeos ou links.</li>
<li><strong>Responder:</strong> participa de uma conversa iniciada por um post.</li>
<li><strong>Repostar ou citar:</strong> distribui uma publicação ou acrescenta seu comentário ao compartilhá-la.</li>
<li><strong>Hashtag:</strong> ajuda a identificar assuntos, sem garantir alcance ou destaque.</li>
<li><strong>Mensagens Diretas:</strong> comunicação privada, sujeita às configurações e regras de cada conta.</li></ol>
<h2>Quantos caracteres o X permite por publicação?</h2>
<p>Segundo a <a href="https://help.x.com/pt/using-x/how-to-post" rel="noopener noreferrer">documentação oficial do X</a>, a publicação de texto padrão aceita até <strong>280 caracteres</strong>. Assinaturas Premium oferecem recursos adicionais, incluindo publicações mais longas, conforme nível e disponibilidade. Não trate recursos pagos como se estivessem liberados para toda conta gratuita.</p>
<h2>Como começar a usar o X?</h2>
<ol><li>Acesse <a href="https://x.com/" rel="noopener noreferrer">x.com</a> ou o aplicativo oficial.</li>
<li>Crie sua conta seguindo a verificação solicitada.</li>
<li>Configure nome de usuário, foto, descrição e opções de privacidade.</li>
<li>Siga fontes e perfis relevantes para seus interesses.</li>
<li>Leia conversas, publique conteúdo original e participe com respeito.</li></ol>
<p>O passo a passo detalhado está em nosso artigo <a href="/o-que-e-twitter-como-funciona-guia-iniciantes/">como criar uma conta no X e configurar o perfil</a>.</p>
<h2>Para que serve o X no marketing digital?</h2>
<p>Empresas podem usar o X para compartilhar informação, acompanhar perguntas públicas, divulgar guias e participar de conversas relevantes. Uma estratégia útil começa ouvindo o que as pessoas precisam e respondendo com algo concreto; postar promoções em toda conversa não é relacionamento.</p>
<p>Para pesquisa e organização editorial, veja <a href="/top10-ferramentas-para-twitter/">10 ferramentas e recursos para X</a>. Para atendimento e reputação, consulte <a href="/top-7-ferramentas-de-analise-monitoramento-twitter/">7 métodos de monitoramento de menções à marca</a>.</p>
<h2>Como publicar fotos, links e vídeos?</h2>
<p>Você pode anexar imagens e vídeos diretamente pelo editor de publicações, conforme o formato e os limites disponíveis para sua conta. Não é necessário utilizar serviços antigos como TwitPic. Acesse nosso <a href="/como-publicar-fotos-no-twitter/">tutorial de publicação de fotos no X pelo celular e computador</a>.</p>
<h2>Como saber se o X está ajudando seu negócio?</h2>
<p>Separe atenção de resultado comercial. Visualizações, conversas e reposts podem ajudar na distribuição, mas não substituem métricas de visitas qualificadas, cadastros e vendas. Se estiver direcionando o público para seu site, organize as campanhas com nosso <a href="/gerador-de-utm/">gerador de UTM gratuito</a>, sempre respeitando limitações de atribuição.</p>
<h2>Perguntas frequentes</h2>
<h3>Twitter e X são a mesma plataforma?</h3><p>Sim. O serviço anteriormente chamado Twitter foi renomeado para X. O nome antigo continua aparecendo em buscas, materiais e conversas.</p>
<h3>É preciso pagar para criar uma conta?</h3><p>O X permite criar contas sem contratar Premium. Alguns recursos e benefícios adicionais, porém, dependem de assinatura.</p>
<h3>O X serve apenas para notícias?</h3><p>Não. Pessoas usam a plataforma para conversas, conteúdo informativo, entretenimento, comunidades, suporte e divulgação de projetos.</p>
<h3>140 caracteres ainda é o limite do Twitter?</h3><p>Não. O limite padrão documentado atualmente é de 280 caracteres, com publicações mais longas disponíveis em determinadas modalidades Premium.</p>
<p><small>Referências: <a href="https://help.x.com/pt/using-x/how-to-post" rel="noopener noreferrer">Como publicar no X</a>, <a href="https://help.x.com/pt/using-x/create-x-account" rel="noopener noreferrer">Como criar conta</a>.</small></p>
POST195
),
786=>array(
'slug'=>'o-que-e-twitter-como-funciona-guia-iniciantes',
'title'=>'Como Criar uma Conta no X (Twitter): Guia para Iniciantes',
'description'=>'Aprenda a criar uma conta no X (Twitter), verificar e-mail, escolher usuário, configurar perfil, privacidade e começar a publicar com segurança.',
'content'=><<<'POST786'
<p><strong>Guia atualizado em outubro de 2026.</strong> Quer criar uma conta no Twitter, agora chamado de X? O processo mudou desde os tutoriais que mostravam antigos menus “Sign Up”, “Design” e “Mobile”. Abaixo estão as etapas atuais, com recomendações para configurar o perfil e evitar erros comuns.</p>
<h2>Como criar uma conta no X pelo celular ou computador</h2>
<ol><li>Acesse o <a href="https://x.com/" rel="noopener noreferrer">site oficial x.com</a> ou instale o aplicativo do X por uma loja confiável.</li>
<li>Escolha a opção de criar conta ou uma alternativa de login disponibilizada na sua plataforma, como Apple ou Google, quando oferecida.</li>
<li>Informe os dados solicitados. O processo pode pedir nome, e-mail ou telefone, data de nascimento e verificação de segurança.</li>
<li>Confirme o endereço de e-mail ou número de telefone conforme a mensagem exibida.</li>
<li>Conclua as etapas de segurança e configure as preferências iniciais de conteúdo.</li></ol>
<p>As telas podem variar entre Android, iOS e navegador. A <a href="https://help.x.com/pt/using-x/create-x-account" rel="noopener noreferrer">Central de Ajuda do X</a> mantém instruções oficiais de inscrição para essas modalidades. Não é necessário fornecer sua senha a sites de terceiros.</p>
<h2>Como escolher o nome de usuário (@)</h2>
<p>O identificador aparece na URL do perfil e permite que as pessoas mencionem sua conta. Escolha algo fácil de reconhecer, coerente com sua identidade e que não tente se passar por outra marca ou pessoa. O X informa regras próprias para tamanho e disponibilidade do nome de usuário; confira os requisitos apresentados na criação.</p>
<h2>Como configurar um perfil de X que transmite confiança</h2>
<ul><li><strong>Foto:</strong> use uma imagem nítida e representativa.</li><li><strong>Descrição:</strong> explique em linguagem simples quem você é e que conteúdo oferece.</li><li><strong>Link:</strong> se tiver um site, leve o visitante à página mais útil para o contexto.</li><li><strong>Imagem de capa:</strong> apresente seu tema, identidade ou projeto sem excesso de texto.</li><li><strong>Segurança:</strong> utilize senha exclusiva e verifique os recursos de autenticação disponíveis.</li><li><strong>Privacidade:</strong> revise a visibilidade das suas publicações, mensagens e notificações.</li></ul>
<h2>Como encontrar pessoas e assuntos relevantes</h2>
<p>Pesquise nomes, temas e perfis que compartilham conteúdo do seu interesse. Siga fontes confiáveis e use listas para agrupar contas relevantes. Evite seguir centenas de pessoas automaticamente apenas para tentar ganhar seguidores: essa prática não constrói relacionamento útil e pode contrariar regras da plataforma.</p>
<h2>Como fazer sua primeira publicação</h2>
<ol><li>Abra o editor de posts.</li><li>Escreva uma mensagem clara, com uma ideia principal.</li><li>Se necessário, adicione foto, vídeo ou um link pelo próprio editor.</li><li>Revise o conteúdo e publique.</li><li>Observe as respostas e continue a conversa quando houver algo útil a acrescentar.</li></ol>
<p>Um post padrão aceita até <strong>280 caracteres</strong>, segundo a documentação atual do X. Alguns tipos de conteúdo têm características e limites diferentes. Para compreender as funções disponíveis, leia também <a href="/o-que-e-twitter-pra-que-serve/">o que é X (Twitter) e para que serve</a>.</p>
<h2>Como começar no X com uma conta profissional</h2>
<p>Se o perfil representa uma empresa, mantenha nome e informações de contato consistentes com o site oficial. Publique explicações sobre produtos, respostas às dúvidas mais comuns e demonstrações reais. Evite prometer resultados que não possa comprovar ou automatizar interações sem controle de qualidade.</p>
<p>Para acompanhar menções públicas à sua marca, use nosso guia de <a href="/top-7-ferramentas-de-analise-monitoramento-twitter/">monitoramento do X</a>. Para planejar conteúdos, consulte <a href="/top10-ferramentas-para-twitter/">ferramentas para X e Twitter</a>.</p>
<h2>Segurança: erros a evitar na criação de conta</h2>
<p>Não reutilize senhas, não compartilhe códigos de verificação e desconfie de páginas que prometem selo de verificação ou seguidores instantâneos. Use apenas caminhos oficiais de recuperação de acesso e revise a autenticidade de mensagens recebidas.</p>
<h2>Perguntas frequentes</h2>
<h3>É possível criar uma conta no X gratuitamente?</h3><p>Sim. A modalidade básica de acesso permite cadastro sem contratação de assinatura, embora benefícios específicos sejam pagos.</p>
<h3>Preciso ter um número de telefone?</h3><p>O X pode oferecer caminhos de inscrição com e-mail ou telefone, e os mecanismos de verificação dependem do fluxo de cadastro.</p>
<h3>Posso mudar meu nome de usuário depois?</h3><p>Em geral, a plataforma permite alterações conforme suas regras e a disponibilidade do identificador escolhido.</p>
<h3>O que fazer depois de criar a conta?</h3><p>Configure o perfil, escolha fontes confiáveis para seguir e comece com conteúdo que represente seus interesses ou sua atividade.</p>
<p><small>Fonte oficial: <a href="https://help.x.com/pt/using-x/create-x-account" rel="noopener noreferrer">Inscrever-se no X e criar uma conta</a>.</small></p>
POST786
),
455=>array(
'slug'=>'como-publicar-fotos-no-twitter',
'title'=>'Como Postar Fotos no X (Twitter) pelo Celular e Computador',
'description'=>'Veja como publicar fotos no X (antigo Twitter) pelo celular e computador, adicionar imagens, GIFs, texto alternativo e corrigir erros comuns.',
'content'=><<<'POST455'
<p><strong>Tutorial atualizado em outubro de 2026.</strong> Para publicar fotos no Twitter, hoje chamado de X, você não precisa mais usar o TwitPic ou outro serviço externo. O X permite anexar fotos diretamente ao editor de publicações no celular e no computador.</p>
<p>Este passo a passo substitui instruções antigas de upload que não funcionam mais. Os nomes de botões podem variar conforme a versão do aplicativo, mas o procedimento principal permanece semelhante.</p>
<h2>Como postar uma foto no X pelo celular</h2>
<ol><li>Abra o aplicativo oficial do X e entre na sua conta.</li><li>Toque no botão de criar um post.</li><li>Escolha o ícone de foto ou mídia para selecionar imagens do seu aparelho. Você também pode usar a câmera, quando disponível.</li><li>Selecione a imagem e revise sua prévia. Faça recorte ou ajuste quando a interface oferecer essa opção.</li><li>Escreva uma legenda que contextualize o conteúdo.</li><li>Revise a visibilidade da publicação e toque em <strong>Postar</strong>.</li></ol>
<h2>Como publicar fotos no X pelo computador</h2>
<ol><li>Acesse <a href="https://x.com/" rel="noopener noreferrer">x.com</a> no navegador e faça login.</li><li>Abra o campo de criação de post.</li><li>Clique no ícone de mídia ou galeria para selecionar uma imagem no computador.</li><li>Confira a prévia, escreva o texto e publique.</li></ol>
<p>De acordo com a <a href="https://help.x.com/pt/using-x/how-to-post" rel="noopener noreferrer">Central de Ajuda do X</a>, um post pode conter diferentes mídias, sujeitas às regras do formato e da conta.</p>
<h2>Quantas imagens posso colocar em uma publicação?</h2>
<p>O X documenta a possibilidade de anexar <strong>até quatro itens de mídia em um único post</strong>, respeitadas combinações e limites apresentados no editor. A página oficial também explica como escolher várias fotos e reordená-las antes de publicar.</p>
<h2>Como adicionar GIFs e vídeos</h2>
<p>No editor, use o recurso de mídia para selecionar um arquivo compatível ou o catálogo de GIFs quando disponível. As regras para GIFs animados e para combinações de mídias podem ser diferentes das de fotos estáticas. Para formatos e limites atuais, consulte a <a href="https://help.x.com/pt/using-x/posting-gifs-and-pictures" rel="noopener noreferrer">documentação sobre fotos e GIFs</a>.</p>
<h2>O que é texto alternativo e por que incluir?</h2>
<p>O texto alternativo descreve a informação importante de uma imagem para pessoas que usam tecnologias assistivas. Quando o editor disponibilizar o recurso, descreva de forma objetiva o que a imagem mostra. Evite encher a descrição de palavras-chave sem relação com a foto.</p>
<p>Exemplo: para um gráfico de campanhas, em vez de “gráfico SEO marketing melhor resultado”, escreva “Gráfico de barras comparando o custo por aquisição de três campanhas, com menor valor na campanha B”.</p>
<h2>Minha foto não aparece no post: o que verificar?</h2>
<ul><li>Confira a conexão de internet e tente novamente.</li><li>Verifique se a imagem está em formato compatível e dentro dos limites de tamanho apresentados.</li><li>Atualize o aplicativo ou tente o navegador oficial.</li><li>Confira permissões para acessar a galeria no celular.</li><li>Veja se o upload foi concluído antes de tocar em publicar.</li><li>Se o problema persistir, consulte a Central de Ajuda do X em vez de enviar sua senha para sites não oficiais.</li></ul>
<h2>Como usar imagens do X em marketing digital?</h2>
<p>Use fotos de produtos reais, demonstrações, bastidores ou gráficos contextualizados. Evite imagens sem relação com a mensagem e respeite direitos de terceiros. Para avaliar se uma publicação trouxe visitantes ao seu site, crie um link rastreável com o nosso <a href="/gerador-de-utm/">gerador de UTM</a>. Lembre-se: o parâmetro mede visitas classificadas no destino, não toda a atividade dentro do X.</p>
<p>Quer conhecer mais recursos? Veja <a href="/o-que-e-twitter-pra-que-serve/">o que é X e para que serve</a> e <a href="/top10-ferramentas-para-twitter/">ferramentas úteis para Twitter/X</a>.</p>
<h2>Perguntas frequentes</h2>
<h3>Preciso do TwitPic para postar fotos?</h3><p>Não. A publicação nativa de imagens pelo X elimina a necessidade de um serviço externo para essa tarefa.</p>
<h3>Posso postar fotos do computador?</h3><p>Sim. O site x.com oferece um editor de publicações que permite anexar arquivos de imagem compatíveis.</p>
<h3>Por que minha imagem aparece cortada?</h3><p>A visualização pode adaptar a proporção da foto ao layout. Confira a prévia do editor e como o post aparece depois de publicado.</p>
<h3>Posso publicar fotos e vídeo juntos?</h3><p>O X documenta combinações de mídias, com limites e requisitos. Consulte o editor e a documentação atual, pois certos formatos têm restrições próprias.</p>
<p><small>Referências oficiais: <a href="https://help.x.com/pt/using-x/posting-gifs-and-pictures" rel="noopener noreferrer">Enviar imagens ou GIFs</a> e <a href="https://help.x.com/pt/using-x/how-to-post" rel="noopener noreferrer">Como publicar no X</a>.</small></p>
POST455
)
);
foreach($jobs as $id=>$job){
  $done='bmd_wave6_completed_20261027_'.$id;
  if(get_option($done,false))continue;
  $post=get_post($id);
  if(!$post||$post->post_type!=='post'||$post->post_status!=='publish'||$post->post_name!==$job['slug'])continue;
  $old=(string)$post->post_content;
  if(strlen($old)<150||strlen($job['content'])<1800)continue;
  $backup='_bmd_original_wave6_20261027';
  if(!metadata_exists('post',$id,$backup)&&!add_post_meta($id,$backup,$old,true))continue;
  if(!metadata_exists('post',$id,'_bmd_original_title_wave6_20261027'))add_post_meta($id,'_bmd_original_title_wave6_20261027',$post->post_title,true);
  if(!metadata_exists('post',$id,'_bmd_original_desc_wave6_20261027'))add_post_meta($id,'_bmd_original_desc_wave6_20261027',(string)get_post_meta($id,'rank_math_description',true),true);
  $r=wp_update_post(array('ID'=>$id,'post_content'=>wp_slash($job['content']),'post_title'=>$job['title']),true);
  if(is_wp_error($r)||(int)$r!==$id)continue;
  update_post_meta($id,'rank_math_description',$job['description']);
  update_option($done,'success',false);
}
},28);
