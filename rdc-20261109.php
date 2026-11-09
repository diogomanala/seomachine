<?php
/**
 * Publica UM guia original sobre RDC 1.002/2025 no Blog de Marketing Digital.
 * Upload em wp-content/mu-plugins/ e visita ao site executam uma vez.
 * Idempotente: se slug ja existe, NAO altera o artigo.
 * Desativa o proprio arquivo depois de confirmar.
 */
if (!defined('ABSPATH')) { exit; }
add_action('init', static function () {
    if (get_option('bmd_rdc_guide_20261109_done', false)) { return; }
    $slug = 'rdc-1002-2025-documentos-clinica-odontologica';
    $existing = get_page_by_path($slug, OBJECT, 'post');
    if ($existing) {
        update_option('bmd_rdc_guide_20261109_done', 'already-exists', false);
        if (is_file(__FILE__)) { @rename(__FILE__, __FILE__ . '.disabled'); }
        return;
    }
    $category = get_category_by_slug('marketing-digital');
    $categories = ($category && !is_wp_error($category)) ? array((int)$category->term_id) : array();
    $title = 'RDC 1002/2025 na odontologia: documentos, POPs e checklist';
    $description = 'RDC 1002/2025: veja os documentos, planos e POPs para clínicas odontológicas, o papel do RT, prazo de adequação e um checklist prático.';
    $content = <<<'BMD_RDC_ARTICLE'
<p><strong>A RDC Anvisa nº 1.002/2025 mudou as referências de boas práticas para clínicas e consultórios odontológicos.</strong> Uma das tarefas que mais gera dúvidas é organizar os documentos do serviço: quais planos entram na pasta, quem deve aprovar, como manter os registros acessíveis e o que um modelo pronto realmente resolve.</p>
<p>Este guia explica as exigências documentais com base principalmente nos <strong>artigos 5º, 6º e 110 a 114 da resolução</strong>. Ele inclui um checklist de organização e links para o texto oficial. Atualizado em novembro de 2026.</p>
<div style="padding:18px 20px;border-left:4px solid #11616a;background:#f3f8f8;margin:22px 0"><strong>Resposta rápida:</strong> o responsável técnico (RT) deve elaborar e implementar a Série de Documentos de Boas Práticas de Funcionamento (SDBPF), com rotinas, protocolos, POPs e planos. A documentação precisa refletir os procedimentos reais do serviço, estar acessível e cumprir os requisitos de aprovação e revisão previstos na norma. Uma pasta de modelos preenchidos, por si só, não comprova conformidade.</div>
<h2>O que é a RDC 1.002/2025 e a quem se aplica?</h2>
<p>A <a href="https://anvisalegis.datalegis.net/action/ActionDatalegis.php?acao=abrirTextoAto&amp;cod_menu=8499&amp;cod_modulo=293&amp;link=S&amp;numeroAto=00001002&amp;orgao=RDC%2FDC%2FANVISA%2FMS&amp;seqAto=000&amp;tipo=RDC&amp;valorAno=2025" target="_blank" rel="noopener noreferrer">RDC Anvisa nº 1.002, de 15 de dezembro de 2025</a>, publicada em 16 de dezembro de 2025, estabelece requisitos de boas práticas de funcionamento para os serviços de assistência odontológica. Abrange diferentes modalidades de atendimento, incluindo consultórios, clínicas e, nos dispositivos específicos, laboratórios de prótese dentária.</p>
<p>A regra não se resume a documentos. Também aborda instalações, equipamentos, processamento de dispositivos médicos, segurança do paciente, gestão de riscos e organização do serviço. O checklist a seguir trata da <strong>parte documental</strong> e não substitui a avaliação das condições físicas nem os requisitos locais da Vigilância Sanitária.</p>
<h2>Quais documentos são previstos para clínicas odontológicas?</h2>
<p>O artigo 113 descreve conteúdos mínimos para a SDBPF, de acordo com a realidade e a assistência prestada. A lista abaixo organiza <strong>16 frentes documentais indicadas no dispositivo</strong>; o inciso XVII ainda contempla outros documentos exigidos por normas aplicáveis. Portanto, não significa que toda clínica precise simplesmente imprimir 16 arquivos iguais.</p>
<div style="overflow-x:auto"><table><thead><tr><th>Frente documental</th><th>O que verificar na prática</th></tr></thead><tbody>
<tr><td>1. Projeto Básico de Arquitetura (PBA)</td><td>Conferir o projeto aprovado pela autoridade sanitária competente e sua correspondência com a unidade.</td></tr>
<tr><td>2. Gestão de recursos humanos</td><td>Manter descrição de cargos, registros profissionais, fichas e comprovações pertinentes.</td></tr>
<tr><td>3. Educação permanente</td><td>Planejar capacitações e guardar os registros de treinamento da equipe.</td></tr>
<tr><td>4. Serviços terceirizados</td><td>Organizar contratos e evidências de licenças e qualificações dos fornecedores aplicáveis.</td></tr>
<tr><td>5. Controle da água</td><td>Manter o plano de monitoramento e os registros correspondentes.</td></tr>
<tr><td>6. Controle de pragas</td><td>Registrar ações e contratos de controle de vetores e pragas urbanas.</td></tr>
<tr><td>7. Manutenção das instalações</td><td>Planejar manutenções preventivas e corretivas e arquivar comprovantes.</td></tr>
<tr><td>8. Gestão de tecnologias em saúde</td><td>Organizar equipamentos, manutenções, calibração e o plano aplicável.</td></tr>
<tr><td>9. Resíduos de serviços de saúde</td><td>Verificar o PGRSS e os registros associados ao gerenciamento de resíduos.</td></tr>
<tr><td>10. Processamento de dispositivos médicos</td><td>Documentar procedimentos e rastreabilidade conforme os processos realizados.</td></tr>
<tr><td>11. Limpeza e desinfecção</td><td>Registrar protocolos aplicáveis a superfícies, equipamentos e ambientes.</td></tr>
<tr><td>12. Segurança do paciente</td><td>Elaborar o plano e as medidas pertinentes aos riscos da assistência.</td></tr>
<tr><td>13. Higiene das mãos</td><td>Manter protocolos de higiene, precauções e isolamento pertinentes.</td></tr>
<tr><td>14. Acidentes com perfurocortantes</td><td>Definir e divulgar o fluxo de encaminhamento dos profissionais.</td></tr>
<tr><td>15. Urgências e emergências</td><td>Documentar critérios e encaminhamentos para atendimento ou transferência.</td></tr>
<tr><td>16. Prevenção e controle de infecções</td><td>Manter o plano de prevenção e controle de infecções relacionadas à assistência.</td></tr>
</tbody></table></div>
<p><small>Base: art. 113, incisos I a XVI, da RDC 1.002/2025. A terminologia foi resumida para facilitar a leitura. O inciso XVII prevê outras exigências legais aplicáveis.</small></p>
<h2>O que deve constar em um POP odontológico?</h2>
<p>Um Procedimento Operacional Padrão (POP) deve explicar a rotina que realmente é realizada pela equipe, de maneira que o profissional saiba quando executar cada etapa e onde registrar sua conclusão. O conteúdo preciso depende da atividade e das orientações técnicas vigentes.</p>
<p>Na organização do documento, vale conferir se constam identificação do serviço, objetivo, campo de aplicação, responsáveis, sequência de tarefas, equipamentos e materiais necessários, cuidados de segurança, registros gerados, data de revisão e aprovação. Isso é uma sugestão de estrutura documental, <strong>não um protocolo clínico pronto para qualquer consultório</strong>.</p>
<p>Por exemplo, uma clínica com esterilização própria precisa refletir o fluxo e os controles que realmente usa. Quem terceiriza parte do processamento tem responsabilidades e registros específicos. Copiar o mesmo POP de outra clínica sem adaptação pode deixar procedimentos relevantes de fora.</p>
<h2>Quem deve preparar, aprovar e revisar a documentação?</h2>
<p>O artigo 5º prevê a designação de um cirurgião-dentista habilitado como responsável técnico (RT). O artigo 6º atribui ao RT a elaboração e implementação da SDBPF. Já o artigo 111 estabelece requisitos para acesso, adaptação, aprovação e revisão dos documentos, incluindo aprovação formal e assinatura pelo responsável legal.</p>
<p>Os planos, protocolos e POPs devem ser revisados <strong>no mínimo a cada dois anos, quando aplicável</strong>, ou antes quando houver mudanças relevantes no serviço, nos procedimentos ou no perfil epidemiológico. A equipe também precisa conhecer e seguir as rotinas adotadas. Isso significa que um editor online ajuda na preparação, mas a responsabilidade técnica e a implementação continuam na clínica.</p>
<h2>Qual é o prazo de adequação da RDC 1.002/2025?</h2>
<p>O artigo 182 estabelece <strong>360 dias contados da publicação da resolução</strong> para as adequações necessárias, com regras específicas para estabelecimentos novos, serviços que reiniciam atividades e laboratórios de prótese. Como a publicação ocorreu em 16 de dezembro de 2025, clínicas em funcionamento devem conferir com atenção essa regra de transição e eventuais orientações da autoridade sanitária local.</p>
<p>A norma entrou em vigor na data da publicação, conforme artigo 186. O prazo de adequação não deve ser interpretado como dispensa automática de outras exigências sanitárias que já se aplicavam ao estabelecimento.</p>
<h2>Checklist para começar a organizar a pasta documental</h2>
<ol>
<li><strong>Mapeie o serviço:</strong> número de consultórios, tipo de atendimento, presença de sedação, radiologia, esterilização e processos terceirizados.</li>
<li><strong>Identifique responsáveis:</strong> registre o responsável legal, o RT e, quando cabível, seu substituto.</li>
<li><strong>Compare com o artigo 113:</strong> marque o que existe, o que precisa ser atualizado e o que está pendente, considerando também as outras normas aplicáveis.</li>
<li><strong>Reúna evidências:</strong> contratos, licenças, planos aprovados, registros de manutenção, treinamentos e controles.</li>
<li><strong>Adapte os POPs:</strong> descreva o processo real da clínica e encaminhe as minutas para avaliação técnica.</li>
<li><strong>Organize aprovação e ciência:</strong> confira assinaturas, acesso da equipe, controle de versões e histórico das revisões.</li>
<li><strong>Faça uma revisão periódica:</strong> atribua datas e responsáveis por atualizar a documentação após alterações operacionais ou normativas.</li>
</ol>
<h2>Modelos prontos ajudam? Qual a diferença entre modelo e conformidade?</h2>
<p>Modelos e plataformas de organização podem reduzir o trabalho de começar do zero. São úteis para estruturar minutas, listar pendências, registrar versões e evitar que documentos se percam em pastas diferentes.</p>
<p>Mas há três etapas distintas: <strong>preparar a minuta</strong>, <strong>adaptar e aprovar tecnicamente</strong> e <strong>implantar a rotina</strong>. A última depende das práticas da equipe, dos equipamentos, da estrutura física e de verificações no local. Nenhuma biblioteca de arquivos substitui a atuação do responsável técnico ou o licenciamento da Vigilância Sanitária.</p>
<p>Para quem prefere organizar essa etapa digitalmente, o <a href="https://odontonegocios.com/rdc/">Odonto em Dia, plataforma de organização documental para clínicas odontológicas</a>, oferece 30 materiais de referência com editor online, versões salvas e acompanhamento de pendências. É um produto da Odonto Negócios, com finalidade de apoio à preparação documental. As minutas precisam ser revisadas e aprovadas pelo RT; o sistema não emite certificação sanitária.</p>
<h2>Erros que vale evitar</h2>
<ul>
<li><strong>Trocar personalização por preenchimento automático:</strong> não basta colocar o nome da clínica em um modelo que descreve equipamentos que ela não possui.</li>
<li><strong>Guardar apenas PDFs sem controle:</strong> a equipe precisa consultar versões atualizadas e localizar registros quando necessário.</li>
<li><strong>Confundir plano com execução:</strong> existir um POP não prova que o procedimento está sendo seguido e registrado.</li>
<li><strong>Esquecer terceiros e processos especiais:</strong> terceirização, radiologia, sedação e processamento de materiais exigem atenção à situação concreta.</li>
<li><strong>Ignorar normas locais:</strong> a RDC nacional convive com outras exigências aplicáveis, inclusive licenciamento e orientações estaduais ou municipais.</li>
</ul>
<h2>Perguntas frequentes sobre a RDC 1.002/2025</h2>
<h3>Todo consultório precisa de SDBPF?</h3>
<p>Os artigos 6º e 110 estabelecem a responsabilidade pela série documental para os serviços abrangidos pela norma. Os itens devem ser adequados ao tipo de assistência e à realidade do estabelecimento.</p>
<h3>A clínica pode comprar modelos de POP e usá-los sem revisão?</h3>
<p>Não é recomendável. A resolução exige documentação ajustada à realidade do serviço, com aprovação e controle de revisões. Um modelo serve como ponto de partida para a elaboração sob responsabilidade técnica.</p>
<h3>Um kit de documentos substitui o alvará sanitário?</h3>
<p>Não. O artigo 8º trata do licenciamento sanitário, que é uma exigência distinta da organização documental.</p>
<h3>Qual é a diferença entre PGRSS, PSP e PP-IRAS?</h3>
<p>São frentes diferentes de gestão: resíduos, segurança do paciente e prevenção e controle de infecções relacionadas à assistência, respectivamente. Seus conteúdos devem refletir as atividades e os riscos da unidade.</p>
<h3>A norma exige revisão periódica dos POPs?</h3>
<p>Sim. O artigo 111 prevê revisão no mínimo a cada dois anos, quando aplicável, ou antes se houver mudanças relevantes. A clínica precisa verificar o cronograma adequado ao seu serviço.</p>
<h2>Fontes oficiais e leitura complementar</h2>
<ul>
<li><a href="https://anvisalegis.datalegis.net/action/ActionDatalegis.php?acao=abrirTextoAto&amp;cod_menu=8499&amp;cod_modulo=293&amp;link=S&amp;numeroAto=00001002&amp;orgao=RDC%2FDC%2FANVISA%2FMS&amp;seqAto=000&amp;tipo=RDC&amp;valorAno=2025" target="_blank" rel="noopener noreferrer">Texto da RDC 1.002/2025 no sistema de legislação da Anvisa</a>.</li>
<li><a href="https://www.gov.br/anvisa/pt-br/assuntos/noticias-anvisa/2026/anvisa-avanca-na-fiscalizacao-baseada-em-risco-com-novo-roteiro-para-servicos-odontologicos" target="_blank" rel="noopener noreferrer">Anvisa: roteiro de inspeção baseado em risco para serviços odontológicos</a>.</li>
<li><a href="https://blogdemarketingdigital.com.br/estrategias-marketing-digital-clinicas-odontologicas/">Estratégias de marketing digital para clínicas odontológicas</a>, caso esteja estruturando também a presença online do consultório.</li>
</ul>
<p><small><strong>Nota editorial:</strong> conteúdo informativo baseado nas fontes indicadas, atualizado em 9/11/2026. Não substitui interpretação das autoridades sanitárias, parecer jurídico, inspeção nem revisão técnica por profissional habilitado. A ferramenta Odonto em Dia mencionada neste artigo integra um projeto comercial relacionado ao blog.</small></p>
BMD_RDC_ARTICLE;
    $post_id = wp_insert_post(array(
        'post_author' => 1, // Mesma autoria do artigo odontologico existente (ID 7989).
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
        'post_excerpt' => 'Veja os documentos e POPs previstos na RDC 1002/2025 para clínicas odontológicas, as responsabilidades do RT e um checklist de organização com fontes oficiais.',
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_category' => $categories,
        'comment_status' => 'closed',
    ), true);
    if (is_wp_error($post_id) || !$post_id) {
        update_option('bmd_rdc_guide_20261109_error', is_wp_error($post_id) ? $post_id->get_error_message() : 'no-post-id', false);
        return;
    }
    update_post_meta((int)$post_id, 'rank_math_title', $title . ' | Blog de Marketing Digital');
    update_post_meta((int)$post_id, 'rank_math_description', $description);
    update_post_meta((int)$post_id, 'rank_math_focus_keyword', 'RDC 1002 2025 odontologia');
    update_option('bmd_rdc_guide_20261109_done', (int)$post_id, false);
    clean_post_cache((int)$post_id);
    if (has_action('litespeed_purge_post')) { do_action('litespeed_purge_post', (int)$post_id); }
    if (is_file(__FILE__)) { @rename(__FILE__, __FILE__ . '.disabled'); }
}, 35);