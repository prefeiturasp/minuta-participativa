<?php get_header(); ?>
<div id="content">
  <?php 
  
  global $query_string, $wpdb, $wp_query;

  $query_args = explode("&", $query_string);
  $search_query = array();

  foreach($query_args as $key => $string) {
	  $query_split = explode("=", $string);
	  $search_query[$query_split[0]] = $query_split[1];
  } 

  $count_search = new WP_Query('s=' . wp_specialchars($_GET['s']) . '&show_posts=-1&posts_per_page=-1');
  $published_posts = $count_search->post_count;
  $the_contribuicoes_page = ( isset( $_GET['comments_page'] ) ) ? (int)$_GET['comments_page'] : 1;
  $published_contribuicoes = search_contribuicoes( wp_specialchars($_GET['s']), $the_contribuicoes_page );
  $total_contribuicoes = $published_contribuicoes['total'];
  
  /*
  NOTA: essa busca serve para os contribuicoes e para os posts,
  existe um belo if/else verificando se a query string comments_page existe...
  se existir, automaticamente a busca eh por contribuicoes.

  OUTRA NOTA: esse template foi copiado de index.php ...
  */
  ?>

  <!-- INICIO PESQUISA -->
  <h2>
    Resultado da busca no Blog
    (<a href="<?php echo get_bloginfo('url') ?>/?s=<?php echo $_GET['s']?>">
      <?php echo $published_posts; ?>
    </a>) 
    e nas Contribuições
    (<a href="<?php echo $published_contribuicoes['first_link']; ?>">
      <?php echo $published_contribuicoes['total']; ?>
    </a>)
  </h2>
  <?php if (have_posts() && !isset( $_GET['comments_page'] ) ) : ?>
    <div class="content">   
      <div class="navegaBusca">
      <span><?php previous_posts_link('Anterior') ?></span>
      <span><?php next_posts_link('Próximo') ?></span>
      </div>
      <hr size="1"></hr><br/>
      <?php while (have_posts()) : ?>
        <?the_post(); ?>
        <div class="post">
        <h2 id="post-<?php the_ID(); ?>">
          <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
            <?php the_title(); ?>
          </a>
        </h2>
        <p class="date">Publicado em: <?php the_time('d/m/Y')?></p>
        <?php echo substr_to_next_white_space_or_message(
              strip_tags($post->post_content), 152, 
              "Clique no título para conferir o conteúdo") . " ..."; ?>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>

  <?php if( isset( $_GET['comments_page'] ) ) : ?>
    <?php if ( count( $published_contribuicoes['total'] ) > 0 ) : ?>

      <div class="content">
      <div class="navegaBusca">
      <span><?php echo $published_contribuicoes['previous_link_tag']; ?></span>
      <span><?php echo $published_contribuicoes['next_link_tag']; ?></span>
      </div>
      <hr size="1"></hr><br/>
      <?php while ( count( $published_contribuicoes['results'] ) > 0 ) : ?>
        <? $the_comment = array_shift( $published_contribuicoes['results'] ); ?>

        <div class="post">
        <h2 id="contribuicao-<?php echo $the_comment->comment_id; ?>">
          <a href="<?php echo get_permalink($the_comment->comment_post_ID) ?>" 
            rel="bookmark" title="">Ver Post do Comentário</a>
        </h2>
        <p class="date">
          Publicado por <?php echo $the_comment->comment_author ?> em 
          <?php echo $the_comment->comment_date_formated ?>.
        </p>
        <strong>Contribuição:</strong>
        <?php echo $the_comment->meta_value; ?>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  <?php endif; ?>

  <!-- Resultado das pesquisas de opniao -->
  <!-- FIM PESQUISA -->
  <div id="widgets">
    <div id="sidebar2">
      <ul>
        <?php dynamic_sidebar('sidebar2'); ?>
      </ul>
    </div>
    <div id="sidebar">
      <ul>
        <?php dynamic_sidebar('sidebar1'); ?>
      </ul>
    </div>
  </div>

</div><!-- fim #content -->

<?php get_footer(); ?>
