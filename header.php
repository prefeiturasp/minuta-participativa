<!DOCTYPE HTML>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <title><?php bloginfo('name'); ?></title>
    <?php wp_enqueue_script('jquery'); ?>
    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>
    <?php global $BODY_CLASS; ?>
    <link rel="alternate" type="application/rss+xml"
          title="<?php bloginfo('name'); ?> RSS Feed"
          href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="alternate" type="application/atom+xml"
          title="<?php bloginfo('name'); ?> Atom Feed"
          href="<?php bloginfo('atom_url'); ?>" />
    <link rel="alternate" type="application/rss+xml"
          title="<?php bloginfo('name'); ?> Comments RSS Feed"
          href="<?php bloginfo('comments_rss2_url'); ?>" />
    <link href="<?php bloginfo('stylesheet_url'); ?>"
          rel="stylesheet" type="text/css" media="all" />
  </head>
  <body <?php body_class($BODY_CLASS); ?>>
    <div id="acessibilidade" tabindex="1">
      <ul>
        <li><a href="#menu">Ir para o menu</a></li>
        <li><a href="#content">Ir para o conteúdo</a></li>
      </ul>
    </div>
    <div id="container">
      <div id="header">
        <h1>
          <a href="<?php echo get_option('home'); ?>/" accesskey="1">
            <?php bloginfo('name'); ?>
          </a>
        </h1>
        <ul id="menu">
          <li>
            <a href="<?php echo get_option('home'); ?>/" title="INÍCIO">
              INÍCIO
            </a>
          </li>
          <?php
            $proc = get_page_by_title('Processo colaborativo');
            $ajuda = get_page_by_title('Ajuda');
	    wp_list_pages('title_li=&exclude=' . $proc->ID . ',' . $ajuda->ID);
	  ?>
          <li>
            <a href="<?php bloginfo('url') ?>/category/blog/noticias/"
            title='NA MÍDIA'>NA MÍDIA</a>
          </li>
          <li>
            <?php get_search_form(); ?>
          </li>
        </ul>

        <span class="btCadastro">
          <a href="<?php bloginfo('url')?>/termos-de-uso"
             title="Para participar cadastre-se e leia os termos de uso">
             Cadastre-se
          </a>
        </span>

        <?php if (is_user_logged_in()) : ?>
        <span class="btLogout">
          <a href="<?php echo wp_logout_url(); ?>"
             title="Sair do sistema">
            Sair
          </a>
        </span>
        <?php endif; ?>
      </div><!--fim header-->
