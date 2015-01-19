<?php /* Template Name: Consulta */ ?>
<?php get_header(); ?>
    <div class="pure-g-r">
        <div class="pure-u-1 topbar">
            <a href="http://gestaourbana.prefeitura.sp.gov.br/" class="logo-gestao">Gestão Urbana SP</a>
            <a href="http://gestaourbana.prefeitura.sp.gov.br/" class="voltar-gestao">Voltar</a>
            <p>Minuta Participativa do PDE</p>
            <div class="socialbar pure-g-r">
                <div class="pure-u-1-3">
                    <a href="#"
                    class="uibutton confirm"
                      onclick="
                        window.open(
                          'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),
                          'facebook-share-dialog',
                          'width=626,height=436');
                        return false;">
                        <i class="icon-facebook"></i>
                      Compartilhar
                    </a>
                </div>
                <div class="pure-u-1-3">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="pt" data-size="80">Tweetar</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                </div>
                <div class="pure-u-1-3">
                    <div class="g-plus" data-action="share" data-annotation="bubble"></div>
                    <script type="text/javascript">
                      window.___gcfg = {lang: 'pt-BR'};

                      (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                    </script>
                </div>
            </div>
        </div>
        <div class="pure-u-1 menubar">
            <ul class="primary">
                <!--li><a href="#" class="active"><i class="icon-propose"></i> minuta participativa</a></li-->
                <li><a href="http://gestaourbana.prefeitura.sp.gov.br/arquivos/mapas/mapas.zip" class="active"><i class="icon-map"></i> mapas</a></li>
                <li><a href="http://gestaourbana.prefeitura.sp.gov.br/arquivos/quadros/quadros_preliminar.zip" class="active"><i class="icon-quadro"></i> quadros</a></li>
            </ul>
            <ul class="secondary">
                <li><a class="help-button" href="#">Ajuda</a></li>
                <?php if ( is_user_logged_in() ) :
                    global $current_user;
                    get_currentuserinfo();

                    if (!empty($current_user->user_firstname)) {
                        $logged_in_user = $current_user->user_firstname . ' ' . $current_user->user_lastname;
                    }else {
                        $logged_in_user = $current_user->user_login;
                    }
                ?>
                <li>
                    <a href="#">Olá, <?php echo $logged_in_user; ?> | </a>
                    <a href="<?php echo wp_logout_url(); ?>" title="Sair">Sair</a>
                </li>
                <?php else : ?>
                <li><a class="register-button" href="<?php bloginfo('url'); ?>/wp-login.php?action=register">Cadastre-se</a></li>
                <li><a class="login-button" href="<?php bloginfo('url'); ?>/wp-login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="pure-u-3-5 content">
            <?php query_posts('category_name=Consulta'); while (have_posts()) : the_post(); ?>
            <h1 data-step="1" data-intro="Bem-vindo a Minuta Participativa. Esta é a 4ª e última etapa da Revisão Participativa do PDE. Clique em 'Próximo' para entender como este site funciona ou 'Pular' para sair da ajuda inicial."><?php the_title(); ?></h1>
            <div class="comments-bar">
                <i class="icon-comment-bg"></i>
                <span class="count-comment">Total de comentários <?php $comments = wp_count_comments($post->ID); echo "(" . $comments->approved . ")"; ?></span>
            </div>
            <div class="main-text">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
        </div>
        <div class="pure-u-2-5 sidebar">
            <div class="sidebox sub-featured" data-intro="Aqui está o texto do PDE de 2002 para consulta. Assim fica fácil ver as diferenças entre a lei antiga e a nova." data-step="3" data-position="left">
                <?php $pp = get_posts(array('post_type'=>'page', 'ID'=>2)); $pp = $pp[0]; ?>
                <h2><i class="icon-book"></i><?php echo $pp->post_title; ?></h2>
                <div class="text-content" data-url="<?php bloginfo('url'); ?>/minuta-antiga/">
                <?php //echo ($pp->post_content); ?>
                </div>
                <div class="related-content">
                    <p>
                        conteúdo relacionado:
                        <a target="_blank" href="http://www.prefeitura.sp.gov.br/cidade/secretarias/desenvolvimento_urbano/legislacao/plano_diretor/index.php?p=1391"><i class="icon-map"></i>mapas</a>
                        <a target="_blank" href="http://www.prefeitura.sp.gov.br/cidade/secretarias/desenvolvimento_urbano/legislacao/plano_diretor/index.php?p=1392"><i class="icon-quadro"></i>quadros</a>
                    </p>
                </div>
            </div>

            <!--div class="sidebox featured-comment" data-intro="Estas são as explicações da Secretaria de Desenvolvimento Urbano (SMDU) e as propostas feitas pela população que estão relacionados com o trecho que você selecionar." data-step="4" data-position="left">
                <h2><i class="icon-pencil"></i>observações da smdu</h2>
                <div class="text-content" id="commentFeaturedContainer">
                </div>
                <div class="related-content">
                </div>
            </div-->

            <div class="sidebox comments" data-intro="Após ler o trecho e comparar com a versão anterior do PDE, use este espaço para enviar o seu comentário." data-step="5" data-position="left">
                <h2><i class="icon-comment"></i>comentários</h2>
                <div id="commentContainer">
                </div>
            </div>
        </div>
    </div>

    <div id="modal-content-box" class="pure-g-r" style="display:none"></div>
<?php get_footer(); ?>
