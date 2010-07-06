<?php /* Template Name: Consulta */ ?>

<?php get_header(); ?>

<script type="text/javascript">
/* I hate to type jQuery all the time */
$ = jQuery;

/* Useful values when building urls, using PHP to generate them. */
blogUrl = "<?php bloginfo('url'); ?>";
templateUrl = "<?php bloginfo('template_url'); ?>";

/* Paragraph that is currently loaded in the comments list. It starts
 * empty and is filled by the `loadComments()' function. */
loadedParagraph = null;


function formatDate(date) {
    return date;
}

function formatOpinion(opinion) {
    switch (opinion) {
    case 'concordo':
        return 'Concordo com o Dispositivo';
    case 'concordo-com-ressalvas':
        return 'Concordo com o Dispositivo com ressalvas';
    case 'nao-concordo':
        return 'Discordo do Dispositivo';
    default:
        return opinion;
    }
}

function formatProposal(proposal) {
    switch (proposal) {
    case 'alteracao':
        return 'Alteração no texto';

    case 'exclusao':
        return 'Exclusão do dispositivo';

    case 'retorno':
        return 'Retorno à redação original';

    case 'acrescimo':
        return 'Acréscimo de um novo dispositivo';

    default:
        return proposal;
    }
}

function filterContent(content) {
    while (content.indexOf('\n') != -1)
        content = content.replace('\n', '<br />');
    return content;
}

function loadComments(paragraphId, postId) {
    var query = '{"method":"get_paragraph_comments","params":["' +
        paragraphId + '",' + postId + ']}'
    var container = $('#commentContainer');

    /* Testing if the paragraph asked to be loaded is already the
     * current one, if it's not we set the loaded paragraph. */
    if (loadedParagraph == paragraphId)
        return;
    else
        loadedParagraph = paragraphId;

    /* Clearing up comment list */
    container.html(
        '<li class="comment">' +
        '<img src="' + templateUrl + '/images/loading.gif" />' +
        '</li>');

    /* Getting comment list */
    $.getJSON(blogUrl, {dialogue_query:query}, function (comments) {
        var i;
        container.html('');

        /* No comments yet */
        if (comments.length == 0) {
            container.html(
                '<li class="comment">' +
                'Não há propostas para o dispositivo selecionado.' +
                '</li>');
            return;
        }

        /* Building the html of the comment list */
        for (i = 0; i < comments.length; i++) {
            var obj = comments[i];
            var infoUser = $('<div>')
                .addClass('infoUser')
                .append($('<span>')
                        .addClass('date')
                        .html(formatDate(obj.comment_date)))
                .append($('<span>')
                        .addClass('user')
                        .html(obj.user_name));
            var li = $('<li>')
                .addClass('comment')
                .append(infoUser)
                .append('<strong>Opinião</strong>')
                .append($('<p>').html(formatOpinion(obj.meta.opiniao)));

            if (obj.meta.proposta) {
                li.append('<strong>Proposta</strong>');
                li.append($('<p>').html(formatProposal(obj.meta.proposta)));
            }

            if (obj.meta.contribuicao) {
                li.append('<strong>Contribuição</strong>');
                li.append($('<p>').html(filterContent(obj.meta.contribuicao)));
            }

            if (obj.meta.justificativa) {
                li.append('<strong>Justificativa</strong>');
                li.append($('<p>').html(filterContent(obj.meta.justificativa)));
            }

            if (obj.tags) {
                var ul = $('<ul>');
                li.append('<strong>Tags</strong>');
                for (var t = 0; t < obj.tags.length; t++)
                    ul.append($('<li>').append(obj.tags[t].name));
                li.append(ul);
            }

            li.appendTo(container);
        }
    });
}

$(document).ready(function () {
    $('.comment-pp').click(function () {
        var postId = $('input[name=comment_post_ID]', $(this)).val();
        var paragraphId =
            $('input[name=dialogue_comment_paragraph]', $(this)).val();
        loadComments(paragraphId, postId);

        /* marking selected paragraph as the active, comments of this
         * `selected' paragraph are going to be shown in the right
         * column. */
        $('.comment-pp').removeClass('active');
        $(this).addClass('active');

        /* Moving the comments column to near the clicked post */
        var margin = $(this).offset().top - 370;
        $('#comments').css('margin-top', margin);
    });

    /* Loading comments from the first paragraph */
    var postIdExpr = $('input[name=comment_post_ID]');
    var paragraphIdExpr = $('input[name=dialogue_comment_paragraph]');
    if (postIdExpr.length > 0 && paragraphIdExpr.length > 0) {
        var paragraphId = $(paragraphIdExpr[0]);

        /* Marking comment paragraph as the active one */
        paragraphId.parents('div.comment-pp').addClass('active');

        /* Actually loading comments from the found paragraph */
        loadComments(paragraphId.val(), $(postIdExpr[0]).val());
    }
});
</script>

<div id="content">
  <?php
    query_posts('category_name=Consulta');
    while (have_posts()) : the_post();
  ?>
  <div class="post">
    <span class="title"><?php the_title(); ?></span>
    <span class="counter">
      <?php
        $comments = get_comment_count($post->ID);
        echo "(" . $comments['approved'] . ")";
      ?>
    </span>
    <?php the_content(); ?>
  </div>
  <?php endwhile; ?>

  <div id="comments">
    <div id="proposta">
        <h2>Consute aqui a Lei 9620/98 Consolidada</h2>
        <div class="listaProposta">
            <h3>Lei 9610/98</h3>
            <p>Atualizada com as mudanças da Minuta de Anteprojeto de Lei que está em Consulta Pública.</p>
            <p>Altera, atualiza e consolida a legislação sobre direitos autorais e dá outras providências.</p>
            <h3>Título I</h3>
            <h3>Disposições Preliminares</h3>
            <p></p>
            <p>Art. 1 Esta Lei regula os direitos autorais, entendendo-se sob esta denominação os direitos de autor e os que lhes são conexos, e orienta-se pelo equilíbrio entre os ditames constitucionais de proteção aos direitos autorais e de garantia ao pleno exercício dos direitos culturais e dos demais direitos fundamentais e pela promoção do desenvolvimento nacional.</p>
            <p>Parágrafo único. A proteção dos direitos autorais deve ser aplicada em harmonia com os princípios e normas relativos à livre iniciativa, à defesa da concorrência e à defesa do consumidor.</p>
            <p>Art. 2 Os estrangeiros domiciliados no exterior gozarão da proteção assegurada nos acordos, convenções e tratados em vigor no Brasil.</p>
            <p>Parágrafo único. Aplica-se o disposto nesta Lei aos nacionais ou pessoas domiciliadas em país que assegure aos brasileiros ou pessoas domiciliadas no Brasil a reciprocidade na proteção aos direitos autorais ou equivalentes.</p>
            <p>Art. 3 Os direitos autorais reputam-se, para os efeitos legais, bens móveis.</p>
            <p>Art. 3-A &#8211; Na interpretação e aplicação desta Lei atender-se-á às finalidades de estimular a criação artística e a diversidade cultural e garantir a liberdade de expressão e o acesso à cultura, à educação, à informação e ao conhecimento, harmonizando-se os interesses dos titulares de direitos autorais e os da sociedade. </p>
            <p>Art. 4 Interpretam-se restritivamente os negócios jurídicos sobre os direitos autorais, visando ao atendimento de seu objeto.</p>
            <p>Art. 5 Para os efeitos desta Lei, considera-se:</p>
            <p>I – publicação – o oferecimento de obra literária, artística ou científica ao conhecimento do público, com o consentimento do autor, ou de qualquer outro titular de direito de autor, por qualquer forma ou processo;</p>
            <p>II – emissão &#8211; a difusão de sons, de sons e imagens ou das representações desses, sem fio, por meio de sinais ou ondas radioelétricas ou qualquer outro processo eletromagnético, inclusive com o uso de satélites.</p>
            <p>II – transmissão ou emissão – a difusão de sons ou de sons e imagens, por meio de ondas radioelétricas; sinais de satélite; fio, cabo ou outro condutor; meios óticos ou qualquer outro processo eletromagnético;</p>
            <p> </p>
            <p> de uma empresa por outra; </p>
            <p>IV – distribuição – a colocação à disposição do público do original ou cópia de obras literárias, artísticas ou científicas, interpretações ou execuções fixadas e fonogramas, mediante a venda, locação ou qualquer outra forma de transferência de propriedade ou posse;</p>
            <p>V – distribuição – a oferta ao público de original ou cópia de obras literárias, artísticas ou científicas, interpretações ou execuções fixadas e fonogramas, mediante a venda, locação ou qualquer outra forma de transferência de propriedade ou posse;</p>
            <p>VI – comunicação ao público – ato mediante o qual a obra é colocada ao alcance do público, por qualquer meio ou procedimento e que não consista na distribuição de exemplares;</p>
            <p>VII – reprodução – a cópia de um ou vários exemplares de uma obra literária, artística ou científica ou de um fonograma, de qualquer forma tangível, incluindo qualquer armazenamento permanente ou temporário por meios eletrônicos ou qualquer outro meio de fixação que venha a ser desenvolvido;</p>
            <p>ressalvados os casos em que a Lei dispensa a autorização;</p>
            <p>IX – obra:</p>
            <p>a) em co-autoria – quando é criada em comum, por dois ou mais autores;</p>
            <p>b) anônima – quando não se indica o nome do autor, por sua vontade ou por ser desconhecido;</p>
            <p>c) pseudônima – quando o autor se oculta sob nome suposto;</p>
            <p>d) inédita – a que não haja sido objeto de publicação;</p>
            <p>e) póstuma – a que se publique após a morte do autor;</p>
            <p>f) originária – a criação primígena;</p>
            <p>g) derivada – a que, constituindo criação intelectual nova, resulta da transformação de obra originária;</p>
            <p>h) coletiva – a criada por iniciativa, organização e responsabilidade de uma pessoa física ou jurídica, que a publica sob seu nome ou marca e que é constituída pela participação de diferentes autores, cujas contribuições se fundem numa criação autônoma;</p>
            <p>i) audiovisual – a obra criada por um autor ou a obra em coautoria que resulta da fixação de imagens com ou sem som, que tenha a finalidade de criar, por meio de sua reprodução, a impressão de movimento, independentemente dos processos de sua captação, do suporte usado inicial ou posteriormente para fixá-lo, bem como dos meios utilizados para sua veiculação;</p>
            <p>que não seja uma fixação incluída em uma obra audiovisual;</p>
            <p>XI – editor – a pessoa física ou jurídica à qual se atribui o direito exclusivo de reprodução da obra e o dever de divulgá-la, nos limites previstos no contrato de edição;</p>
            <p>XII – produtor – a pessoa física ou jurídica que toma a iniciativa e tem a responsabilidade econômica da primeira fixação do fonograma ou da obra audiovisual, qualquer que seja a natureza do suporte utilizado;</p>
            <p>XII – radiodifusão – a transmissão sem fio, inclusive por satélites, de sons ou imagens e sons ou das representações desses, para recepção ao público e a transmissão de sinais codificados, quando os meios de decodificação sejam oferecidos ao público pelo organismo de radiodifusão ou com seu consentimento;</p>
            <p>a emissão cuja recepção do sinal ou onda radioelétrica pelo público ocorra de forma livre e gratuita, ressalvados os casos em que a Lei exige a autorização;</p>
            <p>expressões do folclore.</p>
            <p>.</p>
            <p>Art. 6 Não serão de domínio da União, dos Estados, do Distrito Federal ou dos Municípios as obras por eles simplesmente subvencionadas.</p>
            <p>Art. 6º-A Nos contratos realizados com base nesta Lei, as partes contratantes são obrigadas a observar, durante a sua execução, bem como em sua conclusão, os princípios da probidade e da boa-fé, cooperando mutuamente para o cumprimento da função social do contrato e para a satisfação de sua finalidade e das expectativas comuns e de cada uma das partes. </p>
            <p>§ 1. Nos contratos de execução continuada ou diferida, qualquer uma das partes poderá pleitear sua revisão ou resolução, por onerosidade excessiva, quando para a outra parte decorrer extrema vantagem em virtude de acontecimentos extraordinários e imprevisíveis. </p>
            <p>§ 2. É anulável o contrato quando o titular de direitos autorais, sob premente necessidade, ou por inexperiência, tenha se obrigado a prestação manifestamente desproporcional ao valor da prestação oposta, podendo não ser decretada a anulação do negócio se for oferecido suplemento suficiente, ou se a parte favorecida concordar com a redução do proveito.</p>
            <h3>Título II</h3>
            <h3>Das Obras Intelectuais</h3>
            <h4>Capítulo I</h4>
            <h4>Das Obras Protegidas</h4>
            <p>Art. 7 São obras intelectuais protegidas as criações do espírito, expressas por qualquer meio ou fixadas em qualquer suporte, tangível ou intangível, conhecido ou que se invente no futuro, tais como:</p>
            <p>I – os textos de obras literárias, artísticas ou científicas;</p>
            <p>II – as conferências, alocuções, sermões e outras obras da mesma natureza;</p>
            <p>III – as obras dramáticas e dramático-musicais;</p>
            <p>IV – as obras coreográficas e pantomímicas, cuja execução cênica se fixe por escrito ou por outra qualquer forma;</p>
            <p>V – as composições musicais, tenham ou não letra;</p>
            <p>VI – as obras audiovisuais, sonorizadas ou não, inclusive as cinematográficas;</p>
            <p>VII – as obras fotográficas e as produzidas por qualquer processo análogo ao da fotografia;</p>
            <p>VIII – as obras de desenho, pintura, gravura, escultura, litografia e arte cinética;</p>
            <p>IX – as ilustrações, cartas geográficas e outras obras da mesma natureza;</p>
            <p>X – os projetos, esboços e obras plásticas concernentes à geografia, engenharia, topografia, arquitetura, paisagismo, cenografia e ciência;</p>
            <p>XI – as adaptações, os arranjos, as orquestrações, as traduções e outras transformações de obras originais, apresentadas como criação intelectual nova; </p>
            <p>XII – os programas de computador;</p>
            <p>XIII – as coletâneas ou compilações, antologias, enciclopédias, dicionários, bases de dados e outras obras, que, por sua seleção, organização ou disposição de seu conteúdo, constituam uma criação intelectual.</p>
            <p>§ 1 Os programas de computador são objeto de legislação específica, observadas as disposições desta Lei que lhes sejam aplicáveis.</p>
            <p>§ 2 A proteção concedida no inciso XIII não abarca os dados ou materiais em si mesmos e se entende sem prejuízo de quaisquer direitos autorais que subsistam a respeito dos dados ou materiais contidos nas obras.</p>
            <p>§ 3 No domínio das ciências, a proteção recairá sobre a forma literária ou artística, não abrangendo o seu conteúdo científico ou técnico, sem prejuízo dos direitos que protegem os demais campos da propriedade imaterial.</p>
            <p>Art. 8 Não são objeto de proteção como direitos autorais de que trata esta Lei:</p>
            <p>I – as idéias, procedimentos normativos, sistemas, métodos, projetos ou conceitos matemáticos como tais;</p>
            <p>II – os esquemas, planos ou regras para realizar atos mentais, jogos ou  negócios;</p>
            <p>III – os formulários em branco para serem preenchidos por qualquer tipo de informação, científica ou não, e suas instruções;</p>
            <p>IV – os textos de tratados ou convenções, leis, decretos, regulamentos, decisões judiciais e demais atos oficiais;</p>
            <p>informativas ou explicativas;</p>
            <p>VI – os nomes e títulos isolados;</p>
            <p>VII – o aproveitamento industrial ou comercial das idéias contidas nas obras;</p>
            <p>VIII – as normas técnicas em si mesmas, ressalvada a sua proteção em legislação específica; e</p>
            <p>IX – as notícias diárias que têm o caráter de simples informações de imprensa.</p>
            <p>Art. 9 À cópia de obra de arte plástica feita pelo próprio autor é assegurada a mesma proteção de que goza o original,</p>
            <p>Art. 10. A proteção à obra intelectual abrange o seu título, se original e inconfundível com o de obra do mesmo gênero, divulgada anteriormente por outro autor.</p>
            <p>Parágrafo único. O título de publicações periódicas, inclusive jornais, é protegido até um ano após a saída do seu último número, salvo se forem anuais, caso em que esse prazo se elevará a dois anos.</p>
            <h4>Capítulo II</h4>
            <h4>Da Autoria das Obras Intelectuais</h4>
            <p>Art. 11. Autor é a pessoa física criadora de obra literária, artística ou científica.</p>
            <p>Parágrafo único. A proteção concedida ao autor poderá aplicar-se às pessoas jurídicas nos casos previstos nesta Lei.</p>
            <p>Art. 12. Para se identificar como autor, poderá o criador da obra literária, artística ou científica usar de seu nome civil, completo ou abreviado até por suas iniciais, de pseudônimo ou qualquer outro sinal convencional.</p>
            <p>Art. 13. Considera-se autor da obra intelectual, não havendo prova em contrário, aquele que, por uma das modalidades de identificação referidas no artigo anterior, tiver, em conformidade com o uso, indicada ou anunciada essa qualidade na sua utilização.</p>
            <p>Art. 14. É titular de direitos de autor quem adapta, traduz, arranja ou orquestra obra caída no domínio público, não podendo opor-se a outra adaptação, arranjo, orquestração ou tradução, salvo se for cópia da sua.</p>
            <p>Art. 15. A co-autoria da obra é atribuída àqueles em cujo nome, pseudônimo ou sinal convencional for utilizada.</p>
            <p>§ 1 Não se considera co-autor quem simplesmente auxiliou o autor na produção da obra literária, artística ou científica, revendo-a, atualizando-a, bem como fiscalizando ou dirigindo sua edição ou apresentação por qualquer meio.</p>
            <p>§ 2 Ao co-autor, cuja contribuição possa ser utilizada separadamente, são asseguradas todas as faculdades inerentes à sua criação como obra individual, vedada, porém, a utilização que possa acarretar prejuízo à exploração da obra comum.</p>
            <p>Art. 16. São co-autores da obra audiovisual o autor do assunto ou argumento literário, musical ou lítero-musical e o diretor.</p>
            <p>Art. 16. São coautores da obra audiovisual o diretor realizador, o roteirista e os autores do argumento literário e da composição musical ou literomusical criados especialmente para a obra. </p>
            <p>Parágrafo único. Consideram-se co-autores de desenhos animados os que criam os desenhos utilizados na obra audiovisual.</p>
            <p>Art. 17. É assegurada a proteção às participações individuais em obras coletivas.</p>
            <p>§ 1 Qualquer dos participantes, no exercício de seus direitos morais, poderá proibir que se indique ou anuncie seu nome na obra coletiva, sem prejuízo do direito de haver a remuneração contratada.</p>
            <p>§ 2 Cabe ao organizador a titularidade dos direitos patrimoniais sobre o conjunto da obra coletiva.</p>
            <p>§ 3 O contrato com o organizador especificará a contribuição do participante, o prazo para entrega ou realização, a remuneração e demais condições para sua execução.</p>
            <p>§ 4º Ao autor, cuja contribuição possa ser utilizada separadamente, são asseguradas todas as faculdades inerentes à sua criação como obra individual, vedada, porém, a utilização que possa acarretar prejuízo à exploração da obra coletiva.</p>
            <h4>Capítulo III</h4>
            <h4>Do Registro das Obras Intelectuais</h4>
            <p>Art. 18. A proteção aos direitos de que trata esta Lei independe de registro.</p>
            <p>Art. 19. É facultado ao autor registrar a sua obra no órgão público definido no <em>caput</em> e no § 1 do art. 17 da Lei n 5.988, de 14 de dezembro de 1973.</p>
            <p>Art. 19. É facultado ao autor registrar a sua obra na forma desta Lei. </p>
            <p>Parágrafo único. Compete ao Poder Executivo federal dispor sobre a forma e as condições para o registro da obra, especificando os órgãos ou entidades responsáveis por esse registro.</p>
            <p>Art. 20. Para os serviços de registro previstos nesta Lei será cobrada retribuição, cujo valor e processo de recolhimento serão estabelecidos por ato do titular do órgão da administração pública federal a que estiver vinculado o registro das obras intelectuais.</p>
            <p>Art. 20. Para os serviços de registro previstos nesta Lei será cobrada retribuição, cujo valor e processo de recolhimento serão estabelecidos por ato do Ministro de Estado da Cultura.</p>
            <p>Art 21. Os serviços de registro de que trata esta Lei serão organizados conforme preceitua o § 2 do art. 17 da Lei n<sup>o</sup> 5.988, de 14 de dezembro de 1973.</p>
            <p>Art 21. (Revogado).</p>
            <h3>Título III</h3>
            <h3>Dos Direitos do Autor</h3>
            <h4>Capítulo I</h4>
            <h4>Disposições Preliminares</h4>
            <p>Art. 22. Pertencem ao autor os direitos morais e patrimoniais sobre a obra que criou.</p>
            <p>Art. 23. Os co-autores da obra intelectual exercerão, de comum acordo, os seus direitos, salvo convenção em contrário.</p>
            <h4>Capítulo II</h4>
            <h4>Dos Direitos Morais do Autor</h4>
            <p>Art. 24. São direitos morais do autor:</p>
            <p>I – o de reivindicar, a qualquer tempo, a autoria da obra;</p>
            <p>II – o de ter seu nome, pseudônimo ou sinal convencional indicado ou anunciado, como sendo o do autor, na utilização de sua obra;</p>
            <p>III – o de conservar a obra inédita;</p>
            <p>IV – o de assegurar a integridade da obra, opondo-se a quaisquer modificações ou à prática de atos que, de qualquer forma, possam prejudicá-la ou atingi-lo, como autor, em sua reputação ou honra;</p>
            <p>V – o de modificar a obra, antes ou depois de utilizada;</p>
            <p>VI – o de retirar de circulação a obra ou de suspender qualquer forma de utilização já autorizada, quando a circulação ou utilização implicarem afronta à sua reputação e imagem;</p>
            <p>VII – o de ter acesso a exemplar único e raro da obra, quando se encontre legitimamente em poder de outrem, para o fim de, por meio de processo fotográfico ou assemelhado, ou audiovisual, preservar sua memória, de forma que cause o menor inconveniente possível a seu detentor, que, em todo caso, será indenizado de qualquer dano ou prejuízo que lhe seja causado.</p>
            <p>§ 1 Por morte do autor, transmitem-se a seus sucessores os direitos a que se referem os incisos I a IV.</p>
            <p> Por morte do autor, transmitem-se a seus sucessores os direitos a que se referem os incisos I, II, III, IV e VII.</p>
            <p>§ 2 Compete ao Estado a defesa da integridade e autoria da obra caída em domínio público.</p>
            <p> do art. 5 da Lei n 7347, de 24 de julho de 1985, a defesa da integridade e autoria da obra caída em domínio público.</p>
            <p>§ 3 Nos casos dos incisos V e VI, ressalvam-se as prévias indenizações a terceiros, quando couberem.</p>
            <p> Nos casos dos incisos V e VI, ressalvam-se as indenizações a terceiros, quando couberem.</p>
            <p>Art. 25. Cabe exclusivamente ao diretor o exercício dos direitos morais sobre a obra audiovisual.</p>
            <p>Art. 25. Os direitos morais da obra audiovisual serão exercidos sobre a versão acabada da obra, pelo diretor realizador, em comum acordo com seus coautores. </p>
            <p>Parágrafo único. Os direitos previstos nos incisos I, II e VII do art. 24 poderão ser exercidos de forma individual pelos coautores, sobre suas respectivas participações.</p>
            <p>Art. 26. O autor poderá repudiar a autoria de projeto arquitetônico alterado sem o seu consentimento durante a execução ou após a conclusão da construção.</p>
            <p>Parágrafo único. O proprietário da construção responde pelos danos que causar ao autor sempre que, após o repúdio, der como sendo daquele a autoria do projeto repudiado.</p>
            <p>Art. 27. Os direitos morais do autor são inalienáveis e irrenunciáveis.</p>
            <h4>Capítulo III</h4>
            <h4>Dos Direitos Patrimoniais do Autor e de sua Duração</h4>
            <p>Art. 28. Cabe ao autor o direito exclusivo de utilizar, fruir e dispor da obra literária, artística ou científica.</p>
            <p>Art. 29. Depende de autorização prévia e expressa do autor a utilização da obra, por quaisquer modalidades, tais como:</p>
            <p>I – a reprodução parcial ou integral;</p>
            <p>II – a edição;</p>
            <p>III – a adaptação, o arranjo musical e quaisquer outras transformações;</p>
            <p>IV – a tradução para qualquer idioma;</p>
            <p>V – a inclusão em fonograma ou produção audiovisual;</p>
            <p>V – a inclusão em obra audiovisual; </p>
            <p>VI – a distribuição, quando não intrínseca ao contrato firmado pelo autor com terceiros para uso ou exploração da obra;</p>
            <p>VII – a distribuição para oferta de obras ou produções mediante cabo, fibra ótica, satélite, ondas ou qualquer outro sistema que permita ao usuário realizar a seleção da obra ou produção para percebê-la em um tempo e lugar previamente determinados por quem formula a demanda, e nos casos em que o acesso às obras ou produções se faça por qualquer sistema que importe em pagamento pelo usuário;</p>
            <p> </p>
            <p>VIII – a utilização, direta ou indireta, da obra literária, artística ou científica, mediante:</p>
            <p> </p>
            <p>a) representação, recitação ou declamação;</p>
            <p>b) execução musical;</p>
            <p>c) emprego de alto-falante ou de sistemas análogos;</p>
            <p>d) radiodifusão sonora ou televisiva;</p>
            <p>d) emissão, transmissão ou radiodifusão sonora ou televisiva;</p>
            <p>e) captação de transmissão de radiodifusão em locais de freqüência coletiva;</p>
            <p>e) recepção de emissão ou transmissão em locais de freqüência coletiva;</p>
            <p>f) sonorização ambiental;</p>
            <p>g) a exibição audiovisual, cinematográfica ou por processo assemelhado;</p>
            <p>h) emprego de satélites artificiais; (Revogada);</p>
            <p>i) emprego de sistemas óticos, fios telefônicos ou não, cabos de qualquer tipo e meios de comunicação similares que venham a ser adotados; (Revogada);</p>
            <p>j) exposição de obras de artes plásticas e figurativas;</p>
            <p>IX – a inclusão em base de dados, o armazenamento em computador, a microfilmagem e as demais formas de arquivamento do gênero;</p>
            <p>X – quaisquer outras modalidades de utilização existentes ou que venham a ser inventadas.</p>
            <p>X – a inserção em fonograma ou conteúdo audiovisual que não se caracterize como obra audiovisual; e</p>
            <p>XI – quaisquer outras modalidades de utilização existentes ou que venham a ser inventadas.</p>
            <p>Parágrafo único. No exercício do direito previsto no inciso VII, o titular dos direitos autorais poderá colocar à disposição do público a obra, na forma, local e pelo tempo que desejar, a título oneroso ou gratuito. </p>
            <p>Art. 30. No exercício do direito de reprodução, o titular dos direitos autorais poderá colocar à disposição do público a obra, na forma, local e pelo tempo que desejar, a título oneroso ou gratuito.</p>
            <p>Art. 30. Em qualquer modalidade de reprodução, a quantidade de cópias, realizadas por qualquer meio ou processo, será informada e controlada, cabendo a quem reproduzir a obra a responsabilidade de manter os registros que permitam, ao autor, a fiscalização do aproveitamento econômico da exploração.</p>
            <p>§ 1 O direito de exclusividade de reprodução não será aplicável quando ela for temporária e apenas tiver o propósito de tornar a obra, fonograma ou interpretação perceptível em meio eletrônico ou quando for de natureza transitória e incidental, desde que ocorra no curso do uso devidamente autorizado da obra, pelo titular.</p>
            <p>§ 2 Em qualquer modalidade de reprodução, a quantidade de exemplares será informada e controlada, cabendo a quem reproduzir a obra a responsabilidade de manter os registros que permitam, ao autor, a fiscalização do aproveitamento econômico da exploração.</p>
            <p>§ 2 No caso da inserção tratar-se de uma fixação efêmera de obra, fonograma ou interpretação, realizada por um organismo de radiodifusão, pelos seus próprios meios e para suas próprias emissões ao vivo ou suas retransmissões, não se aplica o direito de exclusividade de reprodução.</p>
            <p>Art. 30-A. Quando a distribuição for realizada pelo titular dos direitos da obra ou fonograma, ou com o seu consentimento, mediante venda, em qualquer Estado membro da Organização Mundial do Comércio, exaure-se o direito patrimonial de distribuição no território nacional do objeto da venda.</p>
            <p>Parágrafo único. Não se aplica o disposto no <strong>caput</strong>aos direitos de locação de programas de computador e de obras audiovisuais e ao direito de sequência de que trata o artigo 38.</p>
            <p>Art. 31. As diversas modalidades de utilização de obras literárias, artísticas ou científicas ou de fonogramas são independentes entre si, e a autorização concedida pelo autor, ou pelo produtor, respectivamente, não se estende a quaisquer das demais.</p>
            <p>Art. 32. Quando uma obra feita em regime de co-autoria não for divisível, nenhum dos co-autores, sob pena de responder por perdas e danos, poderá, sem consentimento dos demais, publicá-la ou autorizar-lhe a publicação, salvo na coleção de suas obras completas.</p>
            <p>§ 1 Havendo divergência, os co-autores decidirão por maioria.</p>
            <p>§ 2 Ao co-autor dissidente é assegurado o direito de não contribuir para as despesas de publicação, renunciando a sua parte nos lucros, e o de vedar que se inscreva seu nome na obra.</p>
            <p>§ 3 Cada co-autor pode, individualmente, sem aquiescência dos outros, registrar a obra e defender os próprios direitos contra terceiros.</p>
            <p>Art. 33. Ninguém pode reproduzir obra que não pertença ao domínio público, a pretexto de anotá-la, comentá-la ou melhorá-la, sem permissão do autor.</p>
            <p>Parágrafo único. Os comentários ou anotações poderão ser publicados separadamente.</p>
            <p>Art. 34. As cartas missivas, cuja publicação está condicionada à permissão do autor, poderão ser juntadas como documento de prova em processos administrativos e judiciais.</p>
            <p>Art. 35. Quando o autor, em virtude de revisão, tiver dado à obra versão definitiva, não poderão seus sucessores reproduzir versões anteriores.</p>
            <p>Art. 36. O direito de utilização econômica dos escritos publicados pela imprensa, diária ou periódica, com exceção dos assinados ou que apresentem sinal de reserva, pertence ao editor, salvo convenção em contrário, assegurada a proteção às participações individuais em obras coletivas de que trata o artigo 17.</p>
            <p>Parágrafo único. A autorização para utilização econômica de artigos assinados, para publicação em diários e periódicos, não produz efeito além do prazo da periodicidade acrescido de vinte dias, a contar de sua publicação, findo o qual recobra o autor o seu direito.</p>
            <p>Art. 37. A aquisição do original de uma obra, ou de sua cópia obtida licitamente por qualquer meio ou processo, não confere ao adquirente qualquer dos direitos patrimoniais do autor, salvo convenção em contrário entre as partes e os casos previstos nesta Lei.</p>
            <p>Art. 38. O autor tem o direito, irrenunciável e inalienável, de perceber, no mínimo, cinco por cento sobre o aumento do preço eventualmente verificável em cada revenda de obra de arte ou manuscrito, sendo originais, que houver alienado.</p>
            <p>Art. 38. O autor tem o direito, irrenunciável e inalienável, de perceber, no mínimo, três por cento sobre o preço de venda verificado em estabelecimentos comerciais, em leilões ou em quaisquer outras transações em que haja intervenção de um intermediário ou agente comercial em cada revenda de obra de arte ou manuscrito, sendo originais, que houver alienado.</p>
            <p>Parágrafo único. Caso o autor não perceba o seu direito de seqüência no ato da revenda, o vendedor é considerado depositário da quantia a ele devida, salvo se a operação for realizada por leiloeiro, quando será este o depositário.</p>
            <p>Art. 39. Os direitos patrimoniais do autor, excetuados os rendimentos resultantes de sua exploração, não se comunicam, salvo pacto antenupcial em contrário.</p>
            <p>Art. 39. Os direitos patrimoniais do autor não se comunicam, salvo disposição em contrário firmada em pacto antenupcial ou contrato escrito entre os companheiros.</p>
            <p>Parágrafo único &#8211; Tampouco se comunicam, no regime da comunhão parcial aplicável ao casamento ou à união estável, os rendimentos resultantes da exploração dos direitos patrimoniais, salvo disposição em contrário firmada em pacto antenupcial ou contrato escrito entre os companheiros.</p>
            <p>Art. 40. Tratando-se de obra anônima ou pseudônima, caberá a quem publicá-la o exercício dos direitos patrimoniais do autor.</p>
            <p>Parágrafo único. O autor que se der a conhecer assumirá o exercício dos direitos patrimoniais, ressalvados os direitos adquiridos por terceiros.</p>
            <p>Art. 41. Os direitos patrimoniais do autor perduram por setenta anos contados de 1 de janeiro do ano subseqüente ao de seu falecimento, obedecida a ordem sucessória da lei civil.</p>
            <p>Art. 41. Os direitos patrimoniais do autor duram por toda a sua vida e por mais setenta anos contados de 1 de janeiro do ano subseqüente ao de seu falecimento, obedecida a ordem sucessória da lei civil.</p>
            <p>Parágrafo único. Aplica-se às obras póstumas o prazo de proteção a que alude o <strong>caput</strong> deste artigo.</p>
            <p>Art. 42. Quando a obra literária, artística ou científica realizada em co-autoria for indivisível, o prazo previsto no artigo anterior será contado da morte do último dos co-autores sobreviventes.</p>
            <p>Parágrafo único. Acrescer-se-ão aos dos sobreviventes os direitos do co-autor que falecer sem sucessores.</p>
            <p>Art. 43. Será de setenta anos o prazo de proteção aos direitos patrimoniais sobre as obras anônimas ou pseudônimas, contado de 1 de janeiro do ano imediatamente posterior ao da primeira publicação</p>
            <p>Parágrafo único. Aplicar-se-á o disposto no art. 41 e seu parágrafo único, sempre que o autor se der a conhecer antes do termo do prazo previsto no <strong>caput</strong> deste artigo.</p>
            <p>Art. 44. O prazo de proteção aos direitos patrimoniais sobre obras audiovisuais e fotográficas será de setenta anos, a contar de 1 de janeiro do ano subseqüente ao de sua divulgação.</p>
            <p>Art. 44. O prazo de proteção aos direitos patrimoniais sobre obras audiovisuais, fotográficas e coletivas será de setenta anos, a contar de 1 de janeiro do ano subseqüente ao de sua publicação. </p>
            <p>Parágrafo único. Decorrido o prazo de proteção previsto neste artigo, a utilização ou exploração por terceiros da obra audiovisual ou da obra coletiva, não poderá ser impedida pela eventual proteção de direitos autorais de partes que sejam divisíveis e que são também objeto de exploração comercial em separado.</p>
            <p>Art. 45. Além das obras em relação às quais decorreu o prazo de proteção aos direitos patrimoniais, pertencem ao domínio público:</p>
            <p>I – as de autores falecidos que não tenham deixado sucessores;</p>
            <p>II – as de autor desconhecido, ressalvada a proteção legal aos conhecimentos étnicos e tradicionais.</p>
            <p>II – as de autor desconhecido, ressalvada a proteção legal aplicável às expressões culturais tradicionais. </p>
            <p>Parágrafo único – O exercício dos direitos reais sobre os suportes materiais em que se fixam as obras intelectuais pertencentes ao domínio público não compreende direito exclusivo à sua imagem ou reprodução, garantindo-se o acesso ao original, mediante as garantias adequadas e sem prejuízo ao detentor da coisa, para que o Estado possa assegurar à sociedade a fruição das criações intelectuais.</p>
            <h4>Capítulo IV</h4>
            <h4>Das Limitações aos Direitos Autorais</h4>
            <p> </p>
            <p>Art. 46. Não constitui ofensa aos direitos autorais:</p>
            <p>Art. 46. Não constitui ofensa aos direitos autorais a utilização de obras protegidas, dispensando-se, inclusive, a prévia e expressa autorização do titular e a necessidade de remuneração por parte de quem as utiliza, nos seguintes casos:</p>
            <p>I – a reprodução:</p>
            <p>a) na imprensa diária ou periódica, de notícia ou de artigo informativo, publicado em diários ou periódicos, com a menção do nome do autor, se assinados, e da publicação de onde foram transcritos;</p>
            <p>b) em diários ou periódicos, de discursos pronunciados em reuniões públicas de qualquer natureza;</p>
            <p>c) de retratos, ou de outra forma de representação da imagem, feitos sob encomenda, quando realizada pelo proprietário do objeto encomendado, não havendo a oposição da pessoa neles representada ou de seus herdeiros;</p>
            <p>d) de obras literárias, artísticas ou científicas, para uso exclusivo de deficientes visuais, sempre que a reprodução, sem fins comerciais, seja feita mediante o sistema Braille ou outro procedimento em qualquer suporte para esses destinatários;</p>
            <p>I – a reprodução, por qualquer meio ou processo, de qualquer obra legitimamente adquirida, desde que feita em um só exemplar e pelo próprio copista, para seu uso privado e não comercial;</p>
            <p>II – a reprodução, em um só exemplar de pequenos trechos, para uso privado do copista, desde que feita por este, sem intuito de lucro;</p>
            <p>II – a reprodução, por qualquer meio ou processo, de qualquer obra legitimamente adquirida, quando destinada a garantir a sua portabilidade ou interoperabilidade, para uso privado e não comercial;</p>
            <p>III – a citação em livros, jornais, revistas ou qualquer outro meio de comunicação, de passagens de qualquer obra, para fins de estudo, crítica ou polêmica, na medida justificada para o fim a atingir, indicando-se o nome do autor e a origem da obra;</p>
            <p>III – a reprodução na imprensa, de notícia ou de artigo informativo, publicado em diários ou periódicos, com a menção do nome do autor, se assinados, e da publicação de onde foram transcritos; </p>
            <p>IV – o apanhado de lições em estabelecimentos de ensino por aqueles a quem elas se dirigem, vedada sua publicação, integral ou parcial, sem autorização prévia e expressa de quem as ministrou;</p>
            <p>IV – a utilização na imprensa, de discursos pronunciados em reuniões públicas de qualquer natureza ou de qualquer obra, quando for justificada e na extensão necessária para cumprir o dever de informar sobre fatos noticiosos;</p>
            <p>V – a utilização de obras literárias, artísticas ou científicas, fonogramas e transmissão de rádio e televisão em estabelecimentos comerciais, exclusivamente para demonstração à clientela, desde que esses estabelecimentos comercializem os suportes ou equipamentos que permitam a sua utilização;</p>
            <p>VI – a representação teatral e a execução musical, quando realizadas no recesso familiar ou, para fins exclusivamente didáticos, nos estabelecimentos de ensino, não havendo em qualquer caso intuito de lucro;</p>
            <p>VI – a representação teatral, a recitação ou declamação, a exibição audiovisual e a execução musical, desde que não tenham intuito de lucro e que o público possa assistir de forma gratuita, realizadas no recesso familiar ou nos estabelecimentos de ensino, quando destinadas exclusivamente aos corpos discente e docente, pais de alunos e outras pessoas pertencentes à comunidade escolar;</p>
            <p>VII – a utilização de obras literárias, artísticas ou científicas para produzir prova judiciária ou administrativa;</p>
            <p>VIII – a reprodução, em quaisquer obras, de pequenos trechos de obras preexistentes, de qualquer natureza, ou de obra integral, quando de artes plásticas, sempre que a reprodução em si não seja o objetivo principal da obra nova e que não prejudique a exploração normal da obra reproduzida nem cause um prejuízo injustificado aos legítimos interesses dos autores.</p>
            <p>VIII – a utilização, em quaisquer obras, de pequenos trechos de obras preexistentes, de qualquer natureza, ou de obra integral, quando de artes visuais, sempre que a utilização em si não seja o objetivo principal da obra nova e que não prejudique a exploração normal da obra reproduzida nem cause um prejuízo injustificado aos legítimos interesses dos autores;</p>
            <p>IX – a reprodução, a distribuição, a comunicação e a colocação à disposição do público de obras para uso exclusivo de pessoas portadoras de deficiência, sempre que a deficiência implicar, para o gozo da obra por aquelas pessoas, necessidade de utilização mediante qualquer processo específico ou ainda de alguma adaptação da obra protegida, e desde que não haja fim comercial na reprodução ou adaptação;</p>
            <p>X – reprodução e colocação à disposição do público para inclusão em portfólio ou currículo profissional, na medida justificada para este fim, desde que aquele que pretenda divulgar as obras por tal meio seja um dos autores ou pessoa retratada;</p>
            <p>XI &#8211; a utilização de retratos, ou de outra forma de representação da imagem, feitos sob encomenda, quando realizada pelo proprietário do objeto encomendado, não havendo a oposição da pessoa neles representada ou, se morta ou ausente, de seu cônjuge, seus ascendentes ou descendentes;</p>
            <p>XII – a reprodução de palestras, conferências e aulas por aqueles a quem elas se dirigem, vedada a publicação, independentemente do intuito de lucro, sem autorização prévia e expressa de quem as ministrou;</p>
            <p>XIII – a reprodução necessária à conservação, preservação e arquivamento de qualquer obra, sem finalidade comercial, desde que realizada por bibliotecas, arquivos, centros de documentação, museus, cinematecas e demais instituições museológicas, na medida justificada para atender aos seus fins;</p>
            <p>XIV – a citação em livros, jornais, revistas ou qualquer outro meio de comunicação, de passagens de qualquer obra, para fins de estudo, crítica ou polêmica, na medida justificada para o fim a atingir, indicando-se o nome do autor e a origem da obra;</p>
            <p>XV – a representação teatral, a recitação ou declamação, a exibição audiovisual e a execução musical, desde que não tenham intuito de lucro, que o público possa assistir de forma gratuita e que ocorram na medida justificada para o fim a se atingir e nas seguintes hipóteses: </p>
            <p>a) para fins exclusivamente didáticos;</p>
            <p><span>b) com finalidade de difusão cultural e multiplicação de público, formação de opinião ou debate, por associações cineclubistas, assim reconhecidas; </p>
            <p>c) estritamente no interior dos templos religiosos e exclusivamente no decorrer de atividades litúrgicas; ou</p>
            <p>d) para fins de reabilitação ou terapia, em unidades de internação médica que prestem este serviço de forma gratuita, ou em unidades prisionais, inclusive de caráter socioeducativas.</p>
            <p>XVI – a comunicação e a colocação à disposição do público de obras intelectuais protegidas que integrem as coleções ou acervos de bibliotecas, arquivos, centros de documentação, museus, cinematecas e demais instituições museológicas, para fins de pesquisa, investigação ou estudo, por qualquer meio ou processo, no interior de suas instalações ou por meio de suas redes fechadas de informática;</p>
            <p>XVII – a reprodução, sem finalidade comercial, de obra literária, fonograma ou obra audiovisual, cuja última publicação não estiver mais disponível para venda, pelo responsável por sua exploração econômica, em quantidade suficiente para atender à demanda de mercado, bem como não tenha uma publicação mais recente disponível e, tampouco, não exista estoque disponível da obra ou fonograma para venda; e</p>
            <p>XVIII – a reprodução e qualquer outra utilização de obras de artes visuais para fins de publicidade relacionada à exposição pública ou venda dessas obras, na medida em que seja necessária para promover o acontecimento, desde que feita com autorização do proprietário do suporte em que a obra se materializa, excluída qualquer outra utilização comercial.</p>
            <p>Parágrafo único. Além dos casos previstos expressamente neste artigo, também não constitui ofensa aos direitos autorais a reprodução, distribuição e comunicação ao público de obras protegidas, dispensando-se, inclusive, a prévia e expressa autorização do titular e a necessidade de remuneração por parte de quem as utiliza, quando essa utilização for:</p>
            <p>I &#8211; para fins educacionais, didáticos, informativos, de pesquisa ou para uso como recurso criativo; e</p>
            <p>II &#8211; feita na medida justificada para o fim a se atingir, sem prejudicar a exploração normal da obra utilizada e nem causar prejuízo injustificado aos legítimos interesses dos autores. </p>
            <p>Art. 47. São livres as paráfrases e paródias que não forem verdadeiras reproduções da obra originária nem lhe implicarem descrédito.</p>
            <p>Art. 48. As obras situadas permanentemente em logradouros públicos podem ser representadas livremente, por meio de pinturas, desenhos, fotografias e procedimentos audiovisuais.</p>
            <p>Art. 48. As obras de artes visuais e arquitetônicas permanentemente perceptíveis em logradouros públicos podem ser livremente representadas, por qualquer meio ou processo, inclusive fotográfico.</p>
            <h4>Capítulo V</h4>
            <h4>Da Transferência dos Direitos de Autor</h4>
            <p>Art. 49. Os direitos de autor poderão ser total ou parcialmente transferidos a terceiros, por ele ou por seus sucessores, a título universal ou singular, pessoalmente ou por meio de representantes com poderes especiais, por meio de licenciamento, concessão, cessão ou por outros meios admitidos em Direito, obedecidas as seguintes limitações:</p>
            <p>Art. 49. Os direitos de autor poderão ser total ou parcialmente transferidos a terceiros, por ele ou por seus sucessores, por prazo determinado ou em definitivo, a título universal ou singular, pessoalmente ou por meio de representantes com poderes especiais, pelos meios admitidos em Direito, obedecidas as seguintes regras e especificações:</p>
            <p>I – a transmissão total compreende todos os direitos de autor, salvo os de natureza moral e os expressamente excluídos por lei;</p>
            <p>I – a cessão total compreende todos os direitos de autor, salvo os de natureza moral e os expressamente excluídos por lei;</p>
            <p>II – somente se admitirá transmissão total e definitiva dos direitos mediante estipulação contratual escrita; (Revogado);</p>
            <p>III – na hipótese de não haver estipulação contratual escrita, o prazo máximo será de cinco anos;</p>
            <p>IV – a cessão será válida unicamente para o país em que se firmou o contrato, salvo estipulação em contrário;</p>
            <p>V – a cessão só se operará para modalidades de utilização já existentes à data do contrato;</p>
            <p>VI – não havendo especificações quanto à modalidade de utilização, o contrato será interpretado restritivamente, entendendo-se como limitada apenas a uma que seja aquela indispensável ao cumprimento da finalidade do contrato.</p>
            <p>Art. 49-A. O autor ou titular de direitos patrimoniais poderá conceder a terceiros, sem que se caracterize transferência de titularidade dos direitos, licença que se regerá pelas estipulações do respectivo contrato e pelas disposições previstas neste capítulo, quando aplicáveis.</p>
            <p>Parágrafo único. Salvo estipulação contratual expressa em contrário, a licença se presume não exclusiva.</p>
            <p>Art. 50. A cessão total ou parcial dos direitos de autor, que se fará sempre por escrito, presume-se onerosa.</p>
            <p>Art. 50. A cessão total ou parcial dos direitos de autor, que se fará sempre por estipulação contratual escrita, presume-se onerosa.</p>
            <p>§ 1 Poderá a cessão ser averbada à margem do registro a que se refere o art. 19 desta Lei, ou, não estando a obra registrada, poderá o instrumento ser registrado em Cartório de Títulos e Documentos.</p>
            <p>ou, não estando, o instrumento de cessão deverá ser registrado em Cartório de Títulos e Documentos.</p>
            <p>§ 2 Constarão do instrumento de cessão como elementos essenciais seu objeto e as condições de exercício do direito quanto a tempo, lugar e preço.</p>
            <p>Decorrido o prazo previsto no instrumento, os direitos autorais retornam obrigatoriamente ao controle econômico do titular originário ou de seus sucessores, independentemente de possíveis dívidas ou outras obrigações pendentes entre as partes contratantes.</p>
            <p>Art. 51. A cessão dos direitos de autor sobre obras futuras abrangerá, no máximo, o período de cinco anos, contado a partir da data de assinatura do contrato.</p>
            <p>Parágrafo único. O prazo será reduzido a cinco anos sempre que indeterminado ou superior, diminuindo-se, na devida proporção, o preço estipulado.</p>
            <p>Art. 52. A omissão do nome do autor, ou de co-autor, na divulgação da obra não presume o anonimato ou a cessão de seus direitos.</p>
            <p></p>
            <h4>Capítulo VI</h4>
            <h4>Da obra sob encomenda ou decorrente de vínculo</h4>
            <p>Art. 52-A. Salvo convenção em contrário, caberá ao empregador, ente público, ou comitente, exclusivamente para as finalidades que constituam o objeto do contrato ou das suas atividades, o exercício da titularidade dos direitos patrimoniais das obras:</p>
            <p>I – criadas em cumprimento a dever funcional ou a contrato de trabalho;</p>
            <p>II – criadas em cumprimento de contrato de encomenda, inclusive para os efeitos dos art. 54 e 55 desta Lei;</p>
            <p>§ 1º &#8211; O autor conservará seus direitos patrimoniais com relação às demais modalidades de utilização da obra, podendo assim explorá-la livremente.</p>
            <p>§ 2º &#8211; A liberdade conferida ao autor de explorar sua obra, na forma deste artigo, não poderá importar em prejuízo injustificado para o empregador, ente público ou comitente na exploração da obra.</p>
            <p>§ 3º &#8211; A retribuição pelo trabalho ou encomenda esgota-se com a remuneração ou com o salário convencionado, salvo disposição em contrário.</p>
            <p>§ 4º &#8211; Será restituída ao autor a totalidade de seus direitos patrimoniais sempre que a obra objeto de contrato de encomenda não se iniciar dentro do termo inicial contratualmente estipulado, nas seguintes condições:</p>
            <p>I &#8211; quando houver retribuição condicionada à participação na exploração econômica da obra, não sendo neste caso o autor obrigado a restituir as quantias recebidas a título de adiantamento de tal modalidade de retribuição;</p>
            <p>II &#8211; quando houver retribuição não condicionada à participação na exploração econômica da obra, desde que o autor restitua as quantias recebidas a título de tal modalidade de retribuição.</p>
            <p>§ 5º &#8211; Para efeitos do § 4º, no caso de não haver termo contratualmente estipulado para a exploração econômica da obra, o autor recobrará a totalidade de seus direitos patrimoniais, no prazo de um ano da entrega da obra, obedecidos os critérios de restituição previstos nos incisos I e II do § 4º.</p>
            <p>§ 6º &#8211; Os contratos de obra sob encomenda far-se-ão sempre por escrito.</p>
            <p>§ 7º &#8211; O autor terá direito de publicar, em suas obras completas, a obra encomendada, após um ano do início de sua comercialização pelo encomendante, salvo convenção em contrário.</p>
            <p>§ 8º &#8211; Não havendo termo fixado para a entrega da obra, entende-se que o autor pode entregá-la quando lhe convier. </p>
            <p>§ 9º -<sup> </sup>Serão <em>nulas</em> de pleno <em>direito</em> as <em>cláusulas</em><strong> </strong>contratuais que limitem o exercício dos direitos morais pelo autor da obra encomendada, observado o disposto no art. 24 § 3º.</p>
            <p>§ 10º As disposições deste artigo não se aplicam:</p>
            <p>I &#8211; aos radialistas, aos autores e aos artistas intérpretes ou executantes cujo exercício profissional é regido pelas Leis n 6.533, de 24 de maio de 1978, e n 6.615, de 16 de dezembro de 1978, sendo-lhes devidos os direitos autorais e conexos em decorrência de cada publicação, execução ou exibição da obra e vedada a cessão ou a promessa de cessão de direitos autorais e conexos decorrentes da prestação de serviços ou da relação de emprego; </p>
            <p>II – às relações que digam respeito à utilização econômica dos artigos publicados pela imprensa, regidas pelo art. 36 desta Lei; </p>
            <p>III – às relações decorrentes de contrato ou vínculo de professores ou pesquisadores com instituição que tenha por finalidade o ensino ou a pesquisa; </p>
            <p>IV – quando a criação exceder claramente o desempenho da função, ou tarefa ajustada, ou quando forem feitos usos futuros da obra que não haviam sido previstos no contrato; </p>
            <p>V – aos profissionais regidos pela Lei n 5.194, de 24 de dezembro de 1966; </p>
            <p>VI – às produções de obra audiovisual de natureza não publicitária.</p>
            <p></p>
            <h4>Capítulo VII </h4>
            <h4>Das licenças não voluntárias</h4>
            <p>Art. 52-B. O Presidente da República poderá, mediante requerimento de interessado legitimado nos termos do § 3º, conceder licença não voluntária e não exclusiva para tradução, reprodução, distribuição, edição e exposição de obras literárias, artísticas ou científicas, desde que a licença atenda necessariamente aos interesses da ciência, da cultura, da educação ou do direito fundamental de acesso à informação, nos seguintes casos:</p>
            <p>I – Quando, já dada a obra ao conhecimento do público há mais de cinco anos, não estiver mais disponível para comercialização em quantidade suficiente para satisfazer as necessidades do público; </p>
            <p>II – Quando os titulares, ou algum deles, de forma não razoável, recusarem ou criarem obstáculos à exploração da obra, ou ainda exercerem de forma abusiva os direitos sobre ela;</p>
            <p>III – Quando não for possível obter a autorização para a exploração de obra que presumivelmente não tenha ingressado em domínio público, pela impossibilidade de se identificar ou localizar o seu autor ou titular; ou</p>
            <p>IV &#8211; Quando o autor ou titular do direito de reprodução, de forma não razoável, recusar ou criar obstáculos ao licenciamento previsto no art. 88-A.</p>
            <p>§ 1º No caso das artes visuais, aplicam-se unicamente as hipóteses previstas nos incisos II e III<em>.</em></p>
            <p>§ 2º Todas as hipóteses de licenças não voluntárias previstas neste artigo estarão sujeitas ao pagamento de remuneração ao autor ou titular da obra, arbitrada pelo Poder Público em procedimento regular que atenda os imperativos do devido processo legal, na forma do regulamento, e segundo termos e condições que assegurem adequadamente os interesses morais e patrimoniais que esta Lei tutela, ponderando-se o interesse público em questão.</p>
            <p>§ 3º A licença de que trata este artigo só poderá ser requerida por pessoa com legítimo interesse e que tenha capacidade técnica e econômica para realizar a exploração eficiente da obra, que deverá destinar-se ao mercado interno. </p>
            <p>§ 4º Sempre que o titular dos direitos possa ser determinado, o requerente deverá comprovar que solicitou previamente ao titular a licença voluntária para exploração da obra, mas que esta lhe foi recusada ou lhe foram criados obstáculos para sua obtenção, de forma não razoável, especialmente quando o preço da retribuição não tenha observado os usos e costumes do mercado. </p>
            <p>§ 5º Salvo por razões legítimas, assim reconhecidas por ato do Ministério da Cultura, o licenciado deverá obedecer ao prazo para início da exploração da obra, a ser definido na concessão da licença, sob pena de caducidade da licença obtida.</p>
            <p>§ 6º O licenciado ficará investido de todos os poderes para agir em defesa da obra. </p>
            <p>§ 7º Fica vedada a concessão da licença nos casos em que houver conflito com o exercício dos direitos morais do autor. </p>
            <p>§ 8º As disposições deste capítulo não se aplicam a programas de computador.</p>
            <p lang="pt-PT">Art. 52-C.  O Poder Executivo, observado o disposto nesta Lei, disporá, em regulamento, sobre o procedimento e as condições para apreciação e concessão da licença não voluntária de que trata o art. 52-B, com obediência aos preceitos do devido processo legal. </p>
            <p lang="pt-PT">§ 1º O requerimento de licença não voluntária será dirigido ao Ministério da Cultura, acompanhado da documentação necessária, nos termos do regulamento.</p>
            <p lang="pt-PT">§ 2º Caberá ao Ministério da Cultura, na forma do regulamento, oportunizar ao autor ou titular da obra o direito à ampla defesa e ao contraditório.</p>
            <p>§ 3 Se não houver necessidade de diligências complementares ou após a realização destas, o Ministério da Cultura elaborará parecer técnico, não vinculativo, e o encaminhará, juntamente com o processo administrativo referente ao requerimento, para apreciação do Presidente da República.</p>
            <p lang="pt-PT">§ 4º  Da decisão que conceder a licença não voluntária caberá pedido de reconsideração, recebido apenas no efeito devolutivo, para que, no prazo de até quinze dias contado do recebimento desse pedido, seja proferida decisão definitiva. </p>
            <p lang="pt-PT">§ 5º  O ato de concessão da licença não voluntária deverá estabelecer, no mínimo, as seguintes condições, além de outras previstas em regulamento:</p>
            <p lang="pt-PT">I &#8211; o prazo de vigência da licença;</p>
            <p lang="pt-PT">II &#8211; a possibilidade de prorrogação; e</p>
            <p lang="pt-PT">III &#8211; a remuneração ao autor ou titular da obra pelo licenciado.</p>
            <p>§ 6º O regulamento deverá estabelecer a forma de recolhimento e destinação dos recursos pagos pelo licenciado a título de remuneração, na hipótese de licença não voluntária decorrente do inciso III do art. 52-B.</p>
            <p lang="pt-PT">§ 7º É vedada a cessão, a transferência ou o substabelecimento da licença não voluntária.</p>
            <p lang="pt-PT">§ 8º  As obrigações remuneratórias do licenciado para com o autor ou titular cessam quando a obra cair em domínio público.</p>
            <p lang="pt-PT">Art. 52-D.  Durante o período de sua vigência, a licença não voluntária poderá ser revogada quando:</p>
            <p lang="pt-PT">I &#8211; o licenciado deixar de cumprir com as condições que o qualificaram; ou</p>
            <p lang="pt-PT">II &#8211; houver descontinuidade do pagamento da remuneração ao autor ou titular da obra.</p>
            <p>Parágrafo único. A revogação da licença poderá ser de ofício ou mediante requerimento do autor ou titular da obra ou do Ministério Público, na forma definida em regulamento.</p>
            <h3>Título IV</h3>
            <h3>Da Utilização de Obras Intelectuais e dos Fonogramas</h3>
            <h4>Capítulo I</h4>
            <h4>Da Edição</h4>
            <p>Art. 53. Mediante contrato de edição, o editor, obrigando-se a reproduzir e a divulgar a obra literária, artística ou científica, fica autorizado, em caráter de exclusividade e em atendimento aos legítimos interesses do autor, a publicá-la e a explorá-la pelo prazo e nas condições pactuadas com o autor.</p>
            <p>§ 1º O contrato de edição não poderá conter cláusula de cessão dos direitos patrimoniais do autor.</p>
            <p>Parágrafo único. Em cada exemplar da obra o editor mencionará:</p>
            <p>§ 2º Em cada exemplar da obra o editor mencionará:</p>
            <p>I – o título da obra e seu autor;</p>
            <p>II – no caso de tradução, o título original e o nome do tradutor;</p>
            <p>III – o ano de publicação;</p>
            <p>IV – o seu nome ou marca que o identifique.</p>
            <p>O autor poderá requerer a resolução do contrato quando o editor, após notificado pelo autor, obstar a circulação da obra em detrimento dos legítimos interesses do autor.</p>
            <p>Art. 54. Pelo mesmo contrato pode o autor obrigar-se à feitura de obra literária, artística ou científica em cuja publicação e divulgação se empenha o editor.</p>
            <p>Art. 55. Em caso de falecimento ou de impedimento do autor para concluir a obra, o editor poderá:</p>
            <p>I – considerar resolvido o contrato, mesmo que tenha sido entregue parte considerável da obra;</p>
            <p>II – editar a obra, sendo autônoma, mediante pagamento proporcional do preço;</p>
            <p>III – mandar que outro a termine, desde que consintam os sucessores e seja o fato indicado na edição.</p>
            <p>Parágrafo único. É vedada a publicação parcial, se o autor manifestou a vontade de só publicá-la por inteiro ou se assim o decidirem seus sucessores.</p>
            <p>Art. 56. Entende-se que o contrato versa apenas sobre uma edição, se não houver cláusula expressa em contrário.</p>
            <p>Parágrafo único. No silêncio do contrato, considera-se que cada edição se constitui de três mil exemplares.</p>
            <p>Art. 57. O preço da retribuição será arbitrado, com base nos usos e costumes, sempre que no contrato não a tiver estipulado expressamente o autor.</p>
            <p>Art. 58. Se os originais forem entregues em desacordo com o ajustado e o editor não os recusar nos trinta dias seguintes ao do recebimento, ter-se-ão por aceitas as alterações introduzidas pelo autor.</p>
            <p>Art. 59. Quaisquer que sejam as condições do contrato, o editor é obrigado a facultar ao autor o exame da escrituração na parte que lhe corresponde, bem como a informá-lo sobre o estado da edição.</p>
            <p>Art. 60. Ao editor compete fixar o preço da venda, sem, todavia, poder elevá-lo a ponto de embaraçar a circulação da obra.</p>
            <p>Art. 61. O editor será obrigado a prestar contas mensais ao autor sempre que a retribuição deste estiver condicionada à venda da obra, salvo se prazo diferente houver sido convencionado.</p>
            <p>Art. 62. A obra deverá ser editada em dois anos da celebração do contrato, salvo prazo diverso estipulado em convenção.</p>
            <p>Parágrafo único. Não havendo edição da obra no prazo legal ou contratual, poderá ser rescindido o contrato, respondendo o editor por danos causados.</p>
            <p>Art. 63. Enquanto não se esgotarem as edições a que tiver direito o editor, não poderá o autor dispor de sua obra, cabendo ao editor o ônus da prova.</p>
            <p>§ 1 Na vigência do contrato de edição, assiste ao editor o direito de exigir que se retire de circulação edição da mesma obra feita por outrem.</p>
            <p>§ 2 Considera-se esgotada a edição quando restarem em estoque, em poder do editor, exemplares em número inferior a dez por cento do total da edição.</p>
            <p>Art. 64. Somente decorrido um ano de lançamento da edição, o editor poderá vender, como saldo, os exemplares restantes, desde que o autor seja notificado de que, no prazo de trinta dias, terá prioridade na aquisição dos referidos exemplares pelo preço de saldo.</p>
            <p>Art. 65. Esgotada a edição, e o editor, com direito a outra, não a publicar, poderá o autor notificá-lo a que o faça em certo prazo, sob pena de perder aquele direito, além de responder por danos.</p>
            <p>Art. 66. O autor tem o direito de fazer, nas edições sucessivas de suas obras, as emendas e alterações que bem lhe aprouver.</p>
            <p>Parágrafo único. O editor poderá opor-se às alterações que lhe prejudiquem os interesses, ofendam sua reputação ou aumentem sua responsabilidade.</p>
            <p>Art. 67. Se, em virtude de sua natureza, for imprescindível a atualização da obra em novas edições, o editor, negando-se o autor a fazê-la, dela poderá encarregar outrem, mencionando o fato na edição.</p>
            <p>Art. 67-A. As regras relativas à edição de que trata este capítulo aplicam-se a todas as obras protegidas e suscetíveis de serem publicadas em livros, jornais, revistas ou outros periódicos, tais como as traduções, as fotografias, os desenhos, as charges e as caricaturas.</p>
            <p>Art. 67-B. São aplicáveis aos contratos de edição de obra musical as disposições contidas no art. 53 desta Lei e nos demais artigos deste capítulo, no que couber.</p>
            <h4>Capítulo II</h4>
            <h4>Da Comunicação ao Público</h4>
            <p>Art. 68. Sem prévia e expressa autorização do autor ou titular, não poderão ser utilizadas obras teatrais, composições musicais ou lítero-musicais e fonogramas, em representações e execuções públicas.</p>
            <p>Art. 68. Sem prévia e expressa autorização do autor ou titular, não poderão ser utilizadas obras teatrais, composições musicais ou literomusicais, fonogramas e obras audiovisuais em representações, exibições e execuções públicas.</p>
            <p>§ 1 Considera-se representação pública a utilização de obras teatrais no gênero drama, tragédia, comédia, ópera, opereta, balé, pantomimas e assemelhadas, musicadas ou não, mediante a participação de artistas, remunerados ou não, em locais de freqüência coletiva ou pela radiodifusão, transmissão e exibição cinematográfica.</p>
            <p> </p>
            <p>§ 2 Considera-se execução pública a utilização de composições musicais ou lítero-musicais, mediante a participação de artistas, remunerados ou não, ou a utilização de fonogramas e obras audiovisuais, em locais de freqüência coletiva, por quaisquer processos, inclusive a radiodifusão ou transmissão por qualquer modalidade, e a exibição cinematográfica.</p>
            <p>§ 2 Considera-se execução pública a utilização de composições musicais ou líteromusicais ou não, ou a utilização de fonogramas, em locais de freqüência coletiva, por quaisquer processos, inclusive a radiodifusão, a transmissão ou a emissão por qualquer modalidade, e a exibição cinematográfica.</p>
            <p>§ 3 Considera-se exibição pública a utilização de obras audiovisuais em locais de freqüência coletiva, por quaisquer processos, inclusive a radiodifusão, transmissão ou emissão por qualquer modalidade, e a exibição cinematográfica. </p>
            <p>§ 3 Consideram-se locais de freqüência coletiva os teatros, cinemas, salões de baile ou concertos, boates, bares, clubes ou associações de qualquer natureza, lojas, estabelecimentos comerciais e industriais, estádios, circos, feiras, restaurantes, hotéis, motéis, clínicas, hospitais, órgãos públicos da administração direta ou indireta, fundacionais e estatais, meios de transporte de passageiros terrestre, marítimo, fluvial ou aéreo, ou onde quer que se representem, executem ou transmitam obras literárias, artísticas ou científicas.</p>
            <p>§ 4 Consideram-se locais de freqüência coletiva os teatros, cinemas, salões de baile ou concertos, boates, bares, clubes ou associações de qualquer natureza, lojas, estabelecimentos comerciais e industriais, estádios, circos, feiras, restaurantes, hotéis, motéis, clínicas, hospitais, órgãos públicos da administração direta ou indireta, fundacionais e estatais, meios de transporte de passageiros terrestre, marítimo, fluvial ou aéreo, ou onde quer que se representem, executem, exibam ou haja recepção de transmissões ou emissões de obras literárias, artísticas ou científicas.</p>
            <p>§ 4 Previamente à realização da execução pública, o empresário deverá apresentar ao escritório central, previsto no art. 99, a comprovação dos recolhimentos relativos aos direitos autorais.</p>
            <p>§ 5 Previamente à realização da execução ou exibição pública, o usuário deverá apresentar à entidade responsável pela arrecadação dos direitos relativos à execução ou exibição pública a comprovação dos recolhimentos relativos aos direitos autorais.</p>
            <p>§ 5 Quando a remuneração depender da freqüência do público, poderá o empresário, por convênio com o escritório central, pagar o preço após a realização da execução pública.</p>
            <p>§ 6 Quando a remuneração depender da freqüência do público, poderá o usuário, por convênio com a entidade responsável pela arrecadação dos direitos relativos à execução ou exibição pública, pagar o preço após a realização da execução ou exibição pública.</p>
            <p>§ 6 O empresário entregará ao escritório central, imediatamente após a execução pública ou transmissão, relação completa das obras e fonogramas utilizados, indicando os nomes dos respectivos autores, artistas e produtores.</p>
            <p>§ 7 O usuário entregará à entidade responsável pela arrecadação dos direitos relativos à execução ou exibição pública, imediatamente após a representação, exibição ou execução pública, relação completa das obras e fonogramas utilizados, indicando os nomes dos respectivos autores, artistas e produtores.</p>
            <p>§ 7 As empresas cinematográficas e de radiodifusão manterão à imediata disposição dos interessados, cópia autêntica dos contratos, ajustes ou acordos, individuais ou coletivos, autorizando e disciplinando a remuneração por execução pública das obras musicais e fonogramas contidas em seus programas ou obras audiovisuais.</p>
            <p>§ 8 As empresas responsáveis pela representação, exibição, radiodifusão, emissão ou transmissão de obras e fonogramas manterão à imediata disposição dos interessados, cópia autêntica dos contratos, ajustes ou acordos, individuais ou coletivos, autorizando e disciplinando a remuneração por representação, execução ou exibição públicas das obras e fonogramas utilizados em seus programas ou obras audiovisuais.</p>
            <p>Art. 69. O autor, observados os usos locais, notificará o empresário do prazo para a representação ou execução, salvo prévia estipulação convencional.</p>
            <p>Art. 70. Ao autor assiste o direito de opor-se à representação ou execução que não seja suficientemente ensaiada, bem como fiscalizá-la, tendo, para isso, livre acesso durante as representações ou execuções, no local onde se realizam.</p>
            <p>Art. 71. O autor da obra não pode alterar-lhe a substância, sem acordo com o empresário que a faz representar.</p>
            <p>Art. 72. O empresário, sem licença do autor, não pode entregar a obra a pessoa estranha à representação ou à execução.</p>
            <p>Art. 73. Os principais intérpretes e os diretores de orquestras ou coro, escolhidos de comum acordo pelo autor e pelo produtor, não podem ser substituídos por ordem deste, sem que aquele consinta.</p>
            <p>Art. 74. O autor de obra teatral, ao autorizar a sua tradução ou adaptação, poderá fixar prazo para utilização dela em representações públicas.</p>
            <p>Parágrafo único. Após o decurso do prazo a que se refere este artigo, não poderá opor-se o tradutor ou adaptador à utilização de outra tradução ou adaptação autorizada, salvo se for cópia da sua.</p>
            <p>Art. 75. Autorizada a representação de obra teatral feita em co-autoria, não poderá qualquer dos co-autores revogar a autorização dada, provocando a suspensão da temporada contratualmente ajustada.</p>
            <p>Art. 76. É impenhorável a parte do produto dos espetáculos reservada ao autor e aos artistas.</p>
            <h4>Capítulo III</h4>
            <h4>Da Utilização da Obra de Arte Plástica</h4>
            <p>Art. 77. Salvo convenção em contrário, o autor de obra de arte plástica, ao alienar o objeto em que ela se materializa, transmite o direito de expô-la, mas não transmite ao adquirente o direito de reproduzi-la.</p>
            <p>Art. 78. A autorização para reproduzir a obra de arte plástica, por qualquer processo, deve se fazer por escrito e se presume onerosa.</p>
            <h4>Capítulo IV</h4>
            <h4>Da Utilização da Obra Fotográfica</h4>
            <p>Art. 79. O autor de obra fotográfica tem direito a reproduzi-la e colocá-la à venda, observadas as restrições à exposição, reprodução e venda de retratos, e sem prejuízo dos direitos de autor sobre a obra fotografada, se de artes plásticas protegidas.</p>
            <p>§ 1 A fotografia, quando utilizada por terceiros, indicará de forma legível o nome do seu autor.</p>
            <p>§ 2 É vedada a reprodução de obra fotográfica que não esteja em absoluta consonância com o original, salvo prévia autorização do autor.</p>
            <h4>Capítulo V</h4>
            <h4>Da Utilização de Fonograma</h4>
            <p>Art. 80. Ao publicar o fonograma, o produtor mencionará em cada exemplar:</p>
            <p>I – o título da obra incluída e seu autor;</p>
            <p>II – o nome ou pseudônimo do intérprete;</p>
            <p>III – o ano de publicação;</p>
            <p>IV – o seu nome ou marca que o identifique.</p>
            <h4>Capítulo VI</h4>
            <h4>Da Utilização da Obra Audiovisual</h4>
            <p>Art. 81. A autorização do autor e do intérprete de obra literária, artística ou científica para produção audiovisual implica, salvo disposição em contrário, consentimento para sua utilização econômica pelo produtor.</p>
            <p>§ 1 A exclusividade da autorização depende de cláusula expressa e cessa dez anos após a celebração do contrato.</p>
            <p> 29 Em cada cópia da obra audiovisual, mencionará o produtor:</p>
            <p>I – o título da obra audiovisual;</p>
            <p>II – os nomes ou pseudônimos do diretor e dos demais co-autores;</p>
            <p>>III – o título da obra adaptada e seu autor, se for o caso;</p>
            <p>IV – os artistas intérpretes;</p>
            <p>V – o ano de publicação;</p>
            <p>VI – o seu nome ou marca que o identifique; e </p>
            <p>VII – o nome dos dubladores, se for o caso.</p>
            <p>Art. 82. O contrato de produção audiovisual deve estabelecer:</p>
            <p>I – a remuneração devida pelo produtor aos co-autores da obra e aos artistas intérpretes e executantes, bem como o tempo, lugar e forma de pagamento;</p>
            <p>II – o prazo de conclusão da obra;</p>
            <p>III – a responsabilidade do produtor para com os co-autores, artistas intérpretes ou executantes, no caso de co-produção.</p>
            <p>Art. 83. O participante da produção da obra audiovisual que interromper, temporária ou definitivamente, sua atuação, não poderá opor-se a que esta seja utilizada na obra nem a que terceiro o substitua, resguardados os direitos que adquiriu quanto à parte já executada.</p>
            <p>Art. 84. Caso a remuneração dos co-autores da obra audiovisual dependa dos rendimentos de sua utilização econômica, o produtor lhes prestará contas semestralmente, se outro prazo não houver sido pactuado.</p>
            <p>Art. 85. Não havendo disposição em contrário, poderão os co-autores da obra audiovisual utilizar-se, em gênero diverso, da parte que constitua sua contribuição pessoal.</p>
            <p>Parágrafo único. Se o produtor não concluir a obra audiovisual no prazo ajustado ou não iniciar sua exploração dentro de dois anos, a contar de sua conclusão, a utilização a que se refere este artigo será livre.</p>
            <p>Art. 86. Os direitos autorais de execução musical relativos a obras musicais, lítero-musicais e fonogramas incluídos em obras audiovisuais serão devidos aos seus titulares pelos responsáveis dos locais ou estabelecimentos a que alude o § 3 do art. 68 desta Lei, que as exibirem, ou pelas emissoras de televisão que as transmitirem.</p>
            <p>Art. 86. Os direitos autorais, decorrentes da exibição pública de obras audiovisuais e da execução pública de obras musicais, líteromusicais e fonogramas pré-existentes incluídos em obras audiovisuais, serão devidos aos seus titulares pelos responsáveis dos locais ou estabelecimentos a que alude o § 4º do art. 68 desta Lei, que as exibirem, ou pelas empresas de comunicação que as transmitirem ou emitirem.</p>
            <p>Parágrafo único. Sem prejuízo do disposto no art. 81, os proventos pecuniários resultantes de cada exibição pública de obras audiovisuais serão repartidos entre seus autores, artistas intérpretes e produtores, na forma convencionada entre eles ou suas associações.</p>
            <p>Art. 86-A. Os responsáveis pelas salas de exibição cinematográfica deverão deduzir cinqüenta por cento do montante total dos direitos autorais, devidos em razão do <strong>caput</strong> do art. 86, do valor a ser pago às empresas distribuidoras das obras audiovisuais. </p>
            <h4>Capítulo VII</h4>
            <h4>Da Utilização de Bases de Dados</h4>
            <p>Art. 87. O titular do direito patrimonial sobre uma base de dados terá o direito exclusivo, a respeito da forma de expressão da estrutura da referida base, de autorizar ou proibir:</p>
            <p>I – sua reprodução total ou parcial, por qualquer meio ou processo;</p>
            <p>II – sua tradução, adaptação, reordenação ou qualquer outra modificação;</p>
            <p>III – a distribuição do original ou cópias da base de dados ou a sua comunicação ao público;</p>
            <p>IV – a reprodução, distribuição ou comunicação ao público dos resultados das operações mencionadas no inciso II deste artigo.</p>
            <h4>Capítulo VIII</h4>
            <h4>Da Utilização da Obra Coletiva</h4>
            <p>Art. 88. Ao publicar a obra coletiva, o organizador mencionará em cada exemplar:</p>
            <p>I – o título da obra;</p>
            <p>II – a relação de todos os participantes, em ordem alfabética, se outra não houver sido convencionada;</p>
            <p>III – o ano de publicação;</p>
            <p>IV – o seu nome ou marca que o identifique.</p>
            <p>Parágrafo único. Para valer-se do disposto no § 1 do art. 17, deverá o participante notificar o organizador, por escrito, até a entrega de sua participação.</p>
            <p lang="pt-PT"></p>
            <h4>Capítulo IX</h4>
            <h4>Da Reprografia</h4>
            <p>Art. 88-A. A reprodução total ou parcial, de obras literárias, artísticas e científicas, realizada por meio de fotocopiadora ou processos assemelhados com finalidade comercial ou intuito de lucro, deve observar as seguintes disposições:</p>
            <p>I &#8211; A reprodução prevista no <strong>caput</strong>estará sujeita ao pagamento de uma retribuição aos titulares dos direitos autorais sobre as obras reproduzidas, salvo quando estes colocarem à disposição do público a obra, a título gratuito, na forma do parágrafo único do art. 29;</p>
            <p>II &#8211; Os estabelecimentos que ofereçam serviços de reprodução reprográfica mediante pagamento pelo serviço oferecido deverão obter autorização prévia dos autores ou titulares das obras protegidas ou da associação de gestão coletiva que os representem; </p>
            <p>§ 1º Caberá aos responsáveis pelos estabelecimentos citados no inciso II do <strong>caput</strong> manter o registro das reproduções, em que conste a identificação e a quantidade de páginas reproduzidas de cada obra, com a finalidade de prestar tais informações regularmente aos autores, de forma a permitir-lhes a fiscalização e o controle do aproveitamento econômico das reproduções;</p>
            <p>§ 2º A arrecadação e distribuição da remuneração a que se refere este capítulo serão feitas por meio das entidades de gestão coletiva constituídas para este fim, as quais deverão unificar a arrecadação, seja delegando a cobrança a uma delas, seja constituindo um ente arrecadador com personalidade jurídica própria, observado o disposto no Título VI desta Lei;</p>
            <p>§ 3º Cabe ao editor receber dos estabelecimentos previstos no inciso II do <strong>caput</strong> os proventos pecuniários resultantes da reprografia de obras literárias, artísticas e científicas e reparti-los com os autores na forma convencionada entre eles ou suas associações, sendo que a parcela destinada aos autores não poderá ser inferior a cinqüenta por cento dos valores arrecadados; </p>
            <p>§ 4º Os titulares dos direitos autorais poderão praticar, pessoalmente, os atos referidos neste artigo, mediante comunicação prévia à entidade a que estiverem filiados.</p>
            <h3>Título V</h3>
            <h3>Dos Direitos Conexos</h3>
            <h4>Capítulo I</h4>
            <h4>Disposições Preliminares</h4>
            <p>Art. 89. As normas relativas aos direitos de autor aplicam-se, no que couber, aos direitos dos artistas intérpretes ou executantes, dos produtores fonográficos e das empresas de radiodifusão.</p>
            <p>Art. 89. As normas relativas aos direitos de autor, inclusive as que se referem às limitações, aplicam-se, no que couber, aos direitos dos artistas intérpretes ou executantes, dos produtores e das empresas de radiodifusão.</p>
            <p>Parágrafo único. A proteção desta Lei aos direitos previstos neste artigo deixa intactas e não afeta as garantias asseguradas aos autores das obras literárias, artísticas ou científicas.</p>
            <h4>Capítulo II</h4>
            <h4>Dos Direitos dos Artistas Intérpretes ou Executantes</h4>
            <p>Art. 90. Tem o artista intérprete ou executante o direito exclusivo de, a título oneroso ou gratuito, autorizar ou proibir:</p>
            <p>I – a fixação de suas interpretações ou execuções;</p>
            <p>II – a reprodução, a execução pública e a locação das suas interpretações ou execuções fixadas;</p>
            <p>II – a reprodução, a execução ou exibição públicas e a locação das suas interpretações ou execuções fixadas;</p>
            <p>III – a radiodifusão das suas interpretações ou execuções, fixadas ou não;</p>
            <p>IV – a colocação à disposição do público de suas interpretações ou execuções, de maneira que qualquer pessoa a elas possa ter acesso, no tempo e no lugar que individualmente escolherem;</p>
            <p>V – qualquer outra modalidade de utilização de suas interpretações ou execuções.</p>
            <p>§ 1 Quando na interpretação ou na execução participarem vários artistas, seus direitos serão exercidos pelo diretor do conjunto.</p>
            <p>§ 2 A proteção aos artistas intérpretes ou executantes estende-se à reprodução da voz e imagem, quando associadas às suas atuações.</p>
            <p>Art. 91. As empresas de radiodifusão poderão realizar fixações de interpretação ou execução de artistas que as tenham permitido para utilização em determinado número de emissões, facultada sua conservação em arquivo público.</p>
            <p>Parágrafo único. A reutilização subseqüente da fixação, no País ou no exterior, somente será lícita mediante autorização escrita dos titulares de bens intelectuais incluídos no programa, devida uma remuneração adicional aos titulares para cada nova utilização.</p>
            <p>Art. 92. Aos intérpretes cabem os direitos morais de integridade e paternidade de suas interpretações, inclusive depois da cessão dos direitos patrimoniais, sem prejuízo da redução, compactação, edição ou dublagem da obra de que tenham participado, sob a responsabilidade do produtor, que não poderá desfigurar a interpretação do artista.</p>
            <p>Parágrafo único. O falecimento de qualquer participante de obra audiovisual, concluída ou não, não obsta sua exibição e aproveitamento econômico, nem exige autorização adicional, sendo a remuneração prevista para o falecido, nos termos do contrato e da lei, efetuada a favor do espólio ou dos sucessores.</p>
            <p></p>
            <h4>Capítulo III</h4>
            <h4>Dos Direitos dos Produtores Fonográficos</h4>
            <p>Art. 93. O produtor de fonogramas tem o direito exclusivo de, a título oneroso ou gratuito, autorizar-lhes ou proibir-lhes:</p>
            <p>I – a reprodução direta ou indireta, total ou parcial;</p>
            <p>II – a distribuição por meio da venda ou locação de exemplares da reprodução;</p>
            <p>III – a comunicação ao público por meio da execução pública, inclusive pela radiodifusão;</p>
            <p>IV – (VETADO)</p>
            <p>V – quaisquer outras modalidades de utilização, existentes ou que venham a ser inventadas.</p>
            <p>Art. 94. Cabe ao produtor fonográfico perceber dos usuários a que se refere o art. 68, e parágrafos, desta Lei os proventos pecuniários resultantes da execução pública dos fonogramas e reparti-los com os artistas, na forma convencionada entre eles ou suas associações.</p>
            <p>Art. 94-A. Cabe ao produtor responsável pela primeira fixação de obra audiovisual perceber uma remuneração referente à exibição pública a que se refere o art. 68, na forma convencionada com os autores e artistas intérpretes da obra audiovisual, ou suas associações.</p>
            <h4>Capítulo IV</h4>
            <h4>Dos Direitos das Empresas de Radiodifusão</h4>
            <p>Art. 95. Cabe às empresas de radiodifusão o direito exclusivo de autorizar ou proibir a retransmissão, fixação e reprodução de suas emissões, bem como a comunicação ao público, pela televisão, em locais de freqüência coletiva, sem prejuízo dos direitos dos titulares de bens intelectuais incluídos na programação.</p>
            <h4>Capítulo V</h4>
            <h4>Da Duração dos Direitos Conexos</h4>
            <p>Art. 96. É de setenta anos o prazo de proteção aos direitos conexos, contados a partir de 1 de janeiro do ano subseqüente à fixação, para os fonogramas; à transmissão, para as emissões das empresas de radiodifusão; e à execução e representação pública, para os demais casos.</p>
            <p>Art. 96. É de setenta anos o prazo de proteção aos direitos conexos, contados a partir de 1 de janeiro do ano subseqüente à fixação, para os fonogramas; à emissão, para as empresas de radiodifusão; e à execução, exibição ou representação públicas, para os demais casos.</p>
            <h3>Título VI</h3>
            <h3>Das Associações de Titulares de Direitos de Autor e dos que lhes são Conexos</h3>
            <p>Art. 97. Para o exercício e defesa de seus direitos, podem os autores e os titulares de direitos conexos associar-se sem intuito de lucro.</p>
            <p>§ 1 É vedado pertencer a mais de uma associação para a gestão coletiva de direitos da mesma natureza.</p>
            <p>§ 2 Pode o titular transferir-se, a qualquer momento, para outra associação, devendo comunicar o fato, por escrito, à associação de origem.</p>
            <p>§ 3 As associações com sede no exterior far-se-ão representar, no País, por associações nacionais constituídas na forma prevista nesta Lei.</p>
            <p>§ 4 As associações poderão destinar até vinte por cento de sua arrecadação em benefício de seus associados, de forma direta ou por meio de outras entidades, para a promoção e o fomento à produção de obras, capacitação e formação, bem como outras atividades de finalidade cultural, social e assistencial.</p>
            <p>Art. 98. Com o ato de filiação, as associações tornam-se mandatárias de seus associados para a prática de todos os atos necessários à defesa judicial ou extrajudicial de seus direitos autorais, bem como para sua cobrança.</p>
            <p>Parágrafo único. Os titulares de direitos autorais poderão praticar, pessoalmente, os atos referidos neste artigo, mediante comunicação prévia à associação a que estiverem filiados.</p>
            <p>Art. 98. Com o ato de filiação, as associações de gestão coletiva de direitos autorais de que trata o art. 97 tornam-se mandatárias de seus associados para a prática de todos os atos necessários à defesa judicial ou extrajudicial de seus direitos autorais, bem como para o exercício da atividade de cobrança desses direitos.</p>
            <p>§ 1 Os titulares de direitos autorais poderão praticar, pessoalmente, os atos referidos neste artigo, mediante comunicação prévia à associação a que estiverem filiados.</p>
            <p>§ 2 O exercício da atividade de cobrança citada no <strong>caput</strong>somente será licito para as associações que obtiverem registro no Ministério da Cultura, nos termos do art. 98-A. </p>
            <p>Art. 98-A. O exercício da atividade de cobrança de que trata o art. 98 dependerá de registro prévio no Ministério da Cultura, conforme disposto em regulamento, cujo processo administrativo observará: </p>
            <p>I – o cumprimento, pelos estatutos da entidade solicitante, dos requisitos estabelecidos na legislação para sua constituição;</p>
            <p>II – a demonstração documental de que a entidade solicitante reúne as condições necessárias de representatividade para assegurar uma administração eficaz e transparente dos direitos a ela confiados em parte significativa do território nacional, mediante comprovação dos seguintes documentos e informações: </p>
            <p>a) os cadastros das obras e titulares que representam;</p>
            <p>b) contratos e convênios mantidos com usuários de obras de seus repertórios;</p>
            <p>c) estatutos e respectivas alterações;</p>
            <p>d) atas das assembléias ordinárias ou extraordinárias; </p>
            <p>e) acordos de representação recíproca com entidades congêneres estrangeiras, quando existentes; </p>
            <p>f) relatório anual de suas atividades, quando aplicável;</p>
            <p>g) demonstrações contábeis anuais, quando aplicável; e</p>
            <p>h) relatório anual de auditoria externa de suas contas, desde que sua elaboração seja demandada pela maioria de seus associados ou por sindicato ou associação profissional, nos termos do art. 100. </p>
            <p>III – outras informações consideradas relevantes pelo Ministério da Cultura, na forma do regulamento, como as que demonstrem o cumprimento de suas obrigações internacionais contratuais que possam ensejar questionamento ao Estado Brasileiro no âmbito dos acordos internacionais dos quais é parte.</p>
            <p>§1º Os documentos e informações a que se referem os Incisos II e III deste artigo deverão ser apresentados anualmente ao Ministério da Cultura. </p>
            <p>§2º O registro de que trata o § 2º do art. 98 deverá ser anulado quando for constatado vício de legalidade, ou poderá ser cancelado administrativamente pelo Ministério da Cultura quando verificado que a associação não atende corretamente ao disposto neste artigo, assegurado sempre o contraditório e a ampla defesa.</p>
            <p>§3º A ausência de uma associação que seja mandatária de determinada categoria de titulares em função da aplicação do § 2º deste artigo não isenta os usuários das obrigações previstas no art. 68, que deverão ser quitadas em relação ao período compreendido entre o indeferimento do pedido de registro, a anulação ou o cancelamento do registro e a obtenção de novo registro ou constituição de entidade sucessora nos termos do art. 98.</p>
            <p>§4º As associações de gestão coletiva de direitos autorais que estejam, desde 01 de janeiro de 2010, legalmente constituídas e arrecadando e distribuindo os direitos autorais de obras e fonogramas considerar-se-ão, para todos os efeitos, registradas para exercerem a atividade econômica de cobrança, devendo obedecer às disposições constantes deste artigo.</p>
            <p>Art. 98-B. As associações de gestão coletiva de direitos autorais, no desempenho de suas funções, deverão:</p>
            <p>I &#8211; Dar publicidade e transparência, por meio de sítios eletrônicos próprios, às formas de cálculo e critérios de cobrança e distribuição dos valores dos direitos autorais arrecadados;</p>
            <p>II &#8211; Dar publicidade e transparência, por meio de sítios eletrônicos próprios, aos estatutos, regulamentos de arrecadação e distribuição e às atas de suas reuniões deliberativas; </p>
            <p>III – Buscar eficiência operacional, por meio da redução de seus custos administrativos e dos prazos de distribuição dos valores aos titulares de direitos.</p>
            <p>Art. 98-C. As associações de gestão coletiva de direitos autorais deverão manter atualizados e disponíveis aos associados os documentos e as informações previstas nos incisos II e III do art. 98-A.</p>
            <p>Art. 98-D. As associações de gestão coletiva de direitos autorais deverão prestar contas dos valores devidos, em caráter regular e de modo direto, aos seus associados.</p>
            <p>Art. 99. As associações manterão um único escritório central para a arrecadação e distribuição, em comum, dos direitos relativos à execução pública das obras musicais e lítero-musicais e de fonogramas, inclusive por meio da radiodifusão e transmissão por qualquer modalidade, e da exibição de obras audiovisuais.</p>
            <p>Art. 99. As associações que reúnam titulares de direitos sobre as obras musicais, literomusicais e fonogramas manterão um único escritório central para a arrecadação e distribuição, em comum, dos direitos relativos à sua execução pública, observado o disposto no art. 99-A.</p>
            <p>§ 1 O escritório central organizado na forma prevista neste artigo não terá finalidade de lucro e será dirigido e administrado pelas associações que o integrem.</p>
            <p>§ 2 O escritório central e as associações a que se refere este Título atuarão em juízo e fora dele em seus próprios nomes como substitutos processuais dos titulares a eles vinculados.</p>
            <p>§ 3 O recolhimento de quaisquer valores pelo escritório central somente se fará por depósito bancário.</p>
            <p> 4 O escritório central poderá manter fiscais, aos quais é vedado receber do empresário numerário a qualquer título.</p>
            <p>§ 4 O escritório central poderá manter fiscais, aos quais é vedado receber do usuário numerário a qualquer título.</p>
            <p>§ 5 A inobservância da norma do parágrafo anterior tornará o faltoso inabilitado à função de fiscal, sem prejuízo das sanções civis e penais cabíveis.</p>
            <p>§6º O escritório central deverá observar as disposições do art. 98-B e apresentar ao Ministério da Cultura, no que couber, a documentação prevista no art. 98-A.</p>
            <p>Art. 99-A. As associações que reúnam titulares de direitos sobre as obras audiovisuais e o escritório central a que se refere o art. 99 deverão unificar a arrecadação dos direitos relativos à exibição e execução pública, inclusive por meio de radiodifusão, transmissão ou emissão por qualquer modalidade, quando essa arrecadação recair sobre um mesmo usuário, seja delegando a cobrança a uma delas, seja constituindo um ente arrecadador com personalidade jurídica própria. </p>
            <p>§ 1 Até a implantação da arrecadação unificada prevista neste artigo, a arrecadação e distribuição dos direitos sobre as obras musicais, literomusicais e fonogramas, referentes à exibição audiovisual, será feita pelo escritório central previsto no art. 99, quer se trate de obras criadas especialmente para as obras audiovisuais ou obras pré-existentes às mesmas.</p>
            <p>§ 2 A organização da arrecadação unificada de que trata o <strong>caput</strong>deste artigo deverá ser feita de comum acordo entre as associações de gestão coletiva de direitos autorais correspondentes e o escritório central, inclusive no que concerne à definição dos critérios de divisão dos valores arrecadados entre as associações e o escritório central.</p>
            <p>§ 3 Os autores e titulares de direitos conexos das obras musicais criadas especialmente para as obras audiovisuais, considerados coautores da obra audiovisual nos termos do <strong>caput</strong> do art. 16, poderão confiar o exercício de seus direitos a associação de gestão coletiva de direitos musicais ou a associação de gestão coletiva de direitos sobre obras audiovisuais.</p>
            <p>§ 4 O prazo para a organização e implantação da arrecadação unificada de que trata este artigo, nos termos do § 2º, será de seis meses contado da data do inicio da vigência desta Lei.</p>
            <p>§ 5 Ultrapassado o prazo de que trata o § 4º sem que tenha sido organizada a arrecadação unificada ou havido acordo entre as partes, o Ministério da Cultura poderá, na forma do regulamento, atuar administrativamente na resolução do conflito, objetivando a aplicação do disposto neste artigo, sem prejuízo da apreciação pelo Sistema Brasileiro de Defesa da Concorrência.</p>
            <p>Art. 100. O sindicato ou associação profissional que congregue não menos de um terço dos filiados de uma associação autoral poderá, uma vez por ano, após notificação, com oito dias de antecedência, fiscalizar, por intermédio de auditor, a exatidão das contas prestadas a seus representados.</p>
            <p>Art. 100. O sindicato ou associação profissional que congregue não menos do que cinco por cento dos filiados de uma associação de gestão coletiva de direitos autorais poderá, uma vez por ano, após notificação, com oito dias de antecedência, fiscalizar, por intermédio de auditor, a exatidão das contas prestadas por essa associação autoral a seus representados.</p>
            <p>Art. 100-A. Os dirigentes, diretores, superintendentes ou gerentes das associações de gestão coletiva de direitos autorais e do escritório central respondem solidariamente, com seus bens pessoais, quanto ao inadimplemento das obrigações para com os associados, por dolo ou culpa.</p>
            <p>Art. 100-B. Eventuais denúncias de usuários ou titulares de direitos autorais acerca de abusos cometidos pelas associações de gestão coletiva de direitos autorais ou pelo escritório central, em especial as relativas às fórmulas de cálculo e aos critérios de cobrança e distribuição que norteiam as atividades de arrecadação, poderão ser encaminhadas aos órgãos do Sistema Brasileiro de Defesa do Consumidor e do Sistema Brasileiro de Defesa da Concorrência, conforme o caso, sem prejuízo da atuação administrativa do Ministério da Cultura na resolução de conflitos no que tange aos direitos autorais, na forma do regulamento.</p>
            <h3>Título VII</h3>
            <h3>Das Sanções às Violações dos Direitos Autorais</h3>
            <h4>Capítulo I</h4>
            <h4>Disposição Preliminar</h4>
            <p>Art. 101. As sanções civis de que trata este Capítulo aplicam-se sem prejuízo das penas cabíveis.</p>
            <p>Art. 101. As sanções civis de que trata este Capítulo aplicam-se sem prejuízo das sanções penais.</p>
            <h4>Capítulo II</h4>
            <h4>Das Sanções Civis</h4>
            <p>Art. 102. O titular cuja obra seja fraudulentamente reproduzida, divulgada ou de qualquer forma utilizada, poderá requerer a apreensão dos exemplares reproduzidos ou a suspensão da divulgação, sem prejuízo da indenização cabível.</p>
            <p>Art. 102. O titular cuja obra seja fraudulentamente reproduzida, divulgada ou de qualquer forma utilizada, poderá requerer a busca e apreensão dos exemplares reproduzidos ou a suspensão da divulgação, sem prejuízo da indenização cabível. </p>
            <p>Art. 103. Quem editar obra literária, artística ou científica, sem autorização do titular, perderá para este os exemplares que se apreenderem e pagar-lhe-á o preço dos que tiver vendido.</p>
            <p>Parágrafo único. Não se conhecendo o número de exemplares que constituem a edição fraudulenta, pagará o transgressor o valor de até três mil exemplares, além dos apreendidos.</p>
            <p>Art. 104. Quem vender, expuser a venda, ocultar, adquirir, distribuir, tiver em depósito ou utilizar obra ou fonograma reproduzidos com fraude, com a finalidade de vender, obter ganho, vantagem, proveito, lucro direto ou indireto, para si ou para outrem, será solidariamente responsável com o contrafator, nos termos dos artigos precedentes, respondendo como contrafatores o importador e o distribuidor em caso de reprodução no exterior.</p>
            <p>Art. 105. A transmissão e a retransmissão, por qualquer meio ou processo, e a comunicação ao público de obras artísticas, literárias e científicas, de interpretações e de fonogramas, realizadas mediante violação aos direitos de seus titulares, deverão ser imediatamente suspensas ou interrompidas pela autoridade judicial competente, sem prejuízo da multa diária pelo descumprimento e das demais indenizações cabíveis, independentemente das sanções penais aplicáveis; caso se comprove que o infrator é reincidente na violação aos direitos dos titulares de direitos de autor e conexos, o valor da multa poderá ser aumentado até o dobro.</p>
            <p>Art. 105. A emissão, a transmissão e a retransmissão, por qualquer meio ou processo, e a comunicação ao público de obras artísticas, literárias e científicas, de interpretações e de fonogramas, realizadas mediante violação aos direitos de seus titulares, poderão ser imediatamente suspensas ou interrompidas pela autoridade judicial competente, sem prejuízo da multa diária pelo descumprimento e das demais indenizações cabíveis, independentemente das sanções penais aplicáveis; caso se comprove que o infrator é reincidente na violação aos direitos dos titulares de direitos de autor e conexos, o valor da multa poderá ser aumentado até o dobro.</p>
            <p>Art. 106. A sentença condenatória poderá determinar a destruição de todos os exemplares ilícitos, bem como as matrizes, moldes, negativos e demais elementos utilizados para praticar o ilícito civil, assim como a perda de máquinas, equipamentos e insumos destinados a tal fim ou, servindo eles unicamente para o fim ilícito, sua destruição.</p>
            <p>Art. 107. Independentemente da perda dos equipamentos utilizados, responderá por perdas e danos, nunca inferiores ao valor que resultaria da aplicação do disposto no art. 103 e seu parágrafo único, quem:</p>
            <p>I – alterar, suprimir, modificar ou inutilizar, de qualquer maneira, dispositivos técnicos introduzidos nos exemplares das obras e produções protegidas para evitar ou restringir sua cópia;</p>
            <p>II – alterar, suprimir ou inutilizar, de qualquer maneira, os sinais codificados destinados a restringir a comunicação ao público de obras, produções ou emissões protegidas ou a evitar a sua cópia;</p>
            <p>III – suprimir ou alterar, sem autorização, qualquer informação sobre a gestão de direitos; </p>
            <p>IV – distribuir, importar para distribuição, emitir, comunicar ou puser à disposição do público, sem autorização, obras, interpretações ou execuções, exemplares de interpretações fixadas em fonogramas e emissões, sabendo que a informação sobre a gestão de direitos, sinais codificados e dispositivos técnicos foram suprimidos ou alterados sem autorização.</p>
            <p>§1º Incorre na mesma sanção, sem prejuízo de outras penalidades previstas em lei, quem por qualquer meio:</p>
            <p>a) dificultar ou impedir os usos permitidos pelos arts. 46, 47 e 48 desta Lei; ou</p>
            <p>b) dificultar ou impedir a livre utilização de obras, emissões de radiodifusão e fonogramas caídos em domínio público.</p>
            <p>§2º O disposto no <strong>caput</strong>não se aplica quando as condutas previstas nos incisos I, II e IV relativas aos sinais codificados e dispositivos técnicos forem realizadas para permitir as utilizações previstas nos arts. 46, 47 e 48 desta Lei ou quando findo o prazo dos direitos patrimoniais sobre a obra, interpretação, execução, fonograma ou emissão.</p>
            <p>§3º Os sinais codificados e dispositivos técnicos mencionados nos incisos I, II e IV devem ter efeito limitado no tempo, correspondente ao prazo dos direitos patrimoniais sobre a obra, interpretação, execução, fonograma ou emissão. </p>
            <p>Art. 108. Quem, na utilização, por qualquer modalidade, de obra intelectual, deixar de indicar ou de anunciar, como tal, o nome, pseudônimo ou sinal convencional do autor e do intérprete, além de responder por danos morais, está obrigado a divulgar-lhes a identidade da seguinte forma:</p>
            <p>I – tratando-se de empresa de radiodifusão, no mesmo horário em que tiver ocorrido a infração, por três dias consecutivos;</p>
            <p>II – tratando-se de publicação gráfica ou fonográfica, mediante inclusão de errata nos exemplares ainda não distribuídos, sem prejuízo de comunicação, com destaque, por três vezes consecutivas em jornal de grande circulação, dos domicílios do autor, do intérprete e do editor ou produtor;</p>
            <p>III – tratando-se de outra forma de utilização, por intermédio da imprensa, na forma a que se refere o inciso anterior.</p>
            <p>Art. 109. A execução pública feita em desacordo com os arts. 68, 97, 98 e 99 desta Lei sujeitará os responsáveis a multa de vinte vezes o valor que deveria ser originariamente pago.</p>
            <p>Art. 109. A representação, a execução ou a exibição públicas feitas em desacordo com os arts. 68, 97, 98, 99 e 99-A desta Lei sujeitarão os responsáveis à multa de até vinte vezes o valor que deveria ser originariamente pago. </p>
            <p>Art. 110. Pela violação de direitos autorais nos espetáculos e audições públicas, realizados nos locais ou estabelecimentos a que alude o art. 68, seus proprietários, diretores, gerentes, empresários e arrendatários respondem solidariamente com os organizadores dos espetáculos.</p>
            <p>Art. 110-A. O titular de direito autoral, ou seu mandatário, que, ao exercer seu direito de forma abusiva, praticar infração da ordem econômica sujeitar-se-á, no que couber, às disposições da Lei nº 8.884, de 11 de junho de 1994, sem prejuízo das demais sanções cabíveis.</p>
            <p>Art. 110-B. O oferecimento, por parte de titular de direitos autorais ou pessoa a seu serviço, de ganho, vantagem, proveito ou benefício material direto ou indireto, para os proprietários, diretores, funcionários ou terceiros a serviço de empresas de radiodifusão ou serviços de televisão por assinatura, com o intuito de aumentar ou diminuir artificiosamente a frequência da execução ou exibição pública de obras ou fonogramas específicos, caracterizará infração da ordem econômica, na forma da Lei nº 8.884, de 1994.</p>
            <p>Art. 110-C. A inobservância do disposto no § 6º do art. 99 sujeitará os dirigentes, diretores, superintendentes ou gerentes das associações de gestão coletiva de direitos autorais ou do escritório central à multa de até 50 mil reais, aplicada pelo Ministério da Cultura mediante regular processo administrativo, assegurado o contraditório e a ampla defesa, conforme disposto em regulamento.</p>
            <p>Parágrafo único. A multa de que trata o <strong>caput </strong>será revertida ao Fundo Nacional de Cultura.</p>
            <h4>Capítulo III</h4>
            <h4>Da Prescrição da Ação</h4>
            <p lang="en-US">Art. 111. (VETADO)</p>
            <p>Art. 111-A. A ação civil por violação a direitos autorais prescreve em cinco anos, contados da data da violação do direito.</p>
            <p>Parágrafo único. Em caso de prática continuada de violação a direitos de determinado autor pelo mesmo contrafator ou grupo de contrafatores, conta-se a prescrição do último ato de violação.</p>
            <h3>Título VIII</h3>
            <h3>Disposições Finais e Transitórias</h3>
            <p>Art. 112. Se uma obra, em conseqüência de ter expirado o prazo de proteção que lhe era anteriormente reconhecido pelo § 2 do art. 42 da Lei n 5.988, de 14 de dezembro de 1973, caiu no domínio público, não terá o prazo de proteção dos direitos patrimoniais ampliado por força do art. 41 desta Lei.</p>
            <p>Art. 113. Os fonogramas, os livros e as obras audiovisuais sujeitar-se-ão a selos ou sinais de identificação sob a responsabilidade do produtor, distribuidor ou importador, sem ônus para o consumidor, com o fim de atestar o cumprimento das normas legais vigentes, conforme dispuser o regulamento.</p>
            <p>Art. 113-A. Caberá ao Poder Executivo dispor, em regulamento, sobre a manifestação do Ministério da Cultura, no processo de renovação de concessões públicas outorgadas a organismos de radiodifusão, acerca da adimplência desses organismos no que tange aos direitos autorais.</p>
            <p>Art. 113-B. Enquanto os serviços de registro de que trata o art. 19 desta Lei não forem organizados pelo Poder Executivo federal, o autor da obra intelectual poderá registrá-la, conforme sua natureza:</p>
            <p>I &#8211; na Fundação Biblioteca Nacional;</p>
            <p>II &#8211; na Escola de Música da Universidade Federal do Rio de Janeiro;</p>
            <p>III &#8211; na Escola de Belas Artes da Universidade Federal do Rio de Janeiro; ou</p>
            <p>IV &#8211; no Conselho Federal de Engenharia, Arquitetura e Agronomia.</p>
            <p>§ 1º Se a obra for de natureza que comporte registro em mais de um desses órgãos, deverá ser registrada naquele com que tiver maior afinidade.</p>
            <p>§ 2º Não se aplica o disposto neste artigo para o registro de programas de computador.</p>
            <p>Art. 114. Esta Lei entra em vigor no prazo de cento e vinte dias após sua publicação, ressalvados os demais prazos especificados nesta Lei.</p>
            <p>Art. 115. Ficam revogados os arts. 649 a 673 e 1.346 a 1.362 do Código Civil e as Leis ns 4.944, de 6 de abril de 1966; 5.988, de 14 de dezembro de 1973, excetuando-se o art. 17 e seus §§ 1 e 2; 6.800, de 25 de junho de 1980; 7.123, de 12 de setembro de 1983; 9.045, de 18 de maio de 1995, e demais disposições em contrário, mantidos em vigor as Leis ns 6.533, de 24 de maio de 1978 e 6.615, de 16 de dezembro de 1978. </p>
        </div>
    </div><!--fim #proposta-->
    <div id="navegaComments">
      <h2 class="widgettitle">Propostas</h2>
      <!--
      <ol>
        <li><a href="#" title="Página 1">1</a></li>
        <li><a href="#" title="Página 2">2</a></li>
        <li><a href="#" title="Página 3">3</a></li>
        <li><a href="#" title="Página 4">4</a></li>	
        <li><a href="#" title="Página 5">5</a></li>
      </ol>
      <form action="get">
        <label for="">Campo busca de comentários</label>
        <input type="text" name="busca nos comentários" />
      </form>
      -->
    </div><!--fim #navegaComments-->
    <ul id="commentContainer"></ul>
  </div><!--fim #comments-->

</div>

<?php get_footer(); ?>
