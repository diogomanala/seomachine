# Padrão editorial BMD para comparativos de afiliados (v1.0)

**Escopo:** novos guias de compra do blogdemarketingdigital.com.br. Primeiro exemplo: `/melhores-microfones-lapela-sem-fio-para-gravar-reels/`.

## 1. Pesquisar antes de escrever

- Definir **uma busca principal** de intenção de compra e até oito **perguntas secundárias**, verificando repetição com artigos existentes no WordPress.
- Identificar o problema real que faz o comprador pesquisar: compatibilidade, capacidade, acabamento, medidas, falhas, manutenção, limitações.
- Conferir ao menos duas fontes primárias (fabricantes, manuais, órgãos oficiais), com data da revisão. Comparar os anúncios reais da Amazon pelo ASIN.
- Separar três dimensões distintas: (a) evidência das especificações, (b) julgamento editorial, (c) dados próprios de busca. Sem GSC/SERPBear atualizado, **não afirmar volume, concorrência ou ranking**.
- Para cada modelo escrever **quem deve considerar**, **quando não deve comprar** e **qual detalhe técnico checar**. Não usar avaliações, testes práticos, fotos, notas ou números inventados.

## 2. Estrutura recomendada do artigo

1. H1 específico com produto + situação + resultado esperado. Manter URL estável ao atualizar.
2. Primeiras duas frases respondem à busca, sem introdução genérica.
3. Aviso de afiliado próximo do começo.
4. `[bmd_comparativo]`: seleção por perfil, três a seis ASINs conferidos, recomendação condicional, cards e tabela por critérios que realmente alteram a compra.
5. Sessões explicativas sobre **compatibilidade, erros comuns, uso real e limites**; blocos ilustrativos simples que não se passam por testes de produto.
6. Uma seção de diagnóstico ou checklist com 3 a 6 passos.
7. `[bmd_aff_perguntas]`: até oito perguntas secundárias, preenchidas no metabox “BMD SEO” (não repetir a mesma resposta em três lugares).
8. Metodologia: fontes, critérios, limitações e indicação clara de que **não houve teste independente**, quando for o caso.
9. Links internos para artigos relacionados, sem redirecionamentos artificiais, e fontes oficiais clicáveis.
10. Data da última revisão, sem mudar artificialmente a data se nada foi verificado.

## 3. Reutilização no WordPress

- Criar um post com conteúdo editorial próprio e inserir os shortcodes acima nos pontos indicados.
- Na caixa **BMD Comparativo | Produtos Amazon**, informar os ASINs de 10 caracteres, nomes, perfil de uso, vantagem e ressalva de cada produto. O link usa `?tag=supercompra0d-20`.
- Na caixa **BMD SEO | Buscas relacionadas, fontes e perguntas**, informar busca principal, buscas secundárias, fontes verificadas e perguntas com respostas claras.
- Selecionar a categoria: Áudio, Home office ou Ferramentas. Os critérios de comparação mudam por categoria.
- Escolher imagem destacada editorial WebP (ideal: 1200 × 675, arquivo leve), usar alt descritivo real, e imagem complementar apenas quando acrescentar informação.
- O painel do WordPress sinaliza comparativos que ultrapassaram **45 dias sem revisão**.

## 4. SEO / assistentes de IA (GEO)

- Responder diretamente à pergunta antes de detalhes extensos. Usar H2 e H3 sem repetição artificial.
- Manter título SEO, meta description e canonical coerentes com a intenção. Links afiliados com `rel="nofollow sponsored noopener"`.
- Para listas comparativas, usar estrutura `ItemList` com itens identificáveis. **Não inventar Product/Review/AggregateRating/Offer/FAQPage** nem prometer rich results: o Google limita alguns deles a situações e páginas específicas.
- Citar materiais técnicos de fabricante, distinguir alegações de fábrica de ensaios próprios e evitar copiar textos de concorrentes.
- Se não houver dados medidos do Search Console, chamar o tema de **hipótese editorial**, não “keyword fácil”.

## 5. Checklist de publicação (obrigatório)

- [ ] Slug não duplica artigo existente e a canonical aponta para ele.
- [ ] H1/H2 legíveis, contraste alto, CSS em arquivo próprio (nunca CSS visível no conteúdo).
- [ ] Estruturas do artigo e do FAQ funcionam em mobile sem overflow horizontal na página inteira.
- [ ] Não há shortcode cru nem PHP exibido ao público.
- [ ] Capa e imagens carregam, têm alt específico e não fingem ser foto do produto realmente vendido.
- [ ] ASINs abrem o item/variante correta, tag de afiliado é `supercompra0d-20`, e links têm `rel="sponsored"`.
- [ ] Sem preço fixo ou avaliação capturada sem autorização da API apropriada.
- [ ] Formato da data de revisão e fontes verificáveis.
- [ ] CSS/shortcodes validados no HTML público; teste visual em celular e desktop quando navegador disponível.
- [ ] Backups antes de editar produção; publicar com instalação pontual e remover cron temporário.

**Observação:** este padrão busca aumentar a utilidade do guia. Não garante posicionamento no Google nem resposta destacada de ferramentas de IA.
