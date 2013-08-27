<?php get_header(); ?>

      <div id="content">
        <?php include('preview.php'); ?>
        <?php
           if ($current_post != null):
             global $post;
             $post = $current_post;
             setup_postdata ($post);
         ?>
        <div class="post">
          <span class="title">Blog</span>
          <ul class="navegaPost">
            <li><?php previous_post_link('Anterior %link') ?></li>
            <li><?php next_post_link('%link Próximo') ?></li>
          </ul>
          <h2>
            <?php the_title(); ?>
          </h2>
          <p class="date"><?php the_time('d/m/Y')?></p>

          <?php the_content('<p class="serif">Leia mais &raquo;</p>'); ?>

          <a href="<?php the_permalink() ?>#comments"
             title="Exibir comentários" class="lerComentarios">
            Ler comentários
          </a>

          <p class="postmetadata">
            <?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in
            <?php the_category(', ') ?> |
            <?php edit_post_link('Editar', '', ' | '); ?>
            <?php comments_popup_link('Nenhum comentário &#187;',
                                      '1 Comentário &#187;',
                                      '% Comentários &#187;'); ?>
          </p>

        </div><!--fim .post-->

        <?php else : ?>
        <div class="post">
          <h2 class="center">Não encontrado.</h2>
          <p class="center">
            Ops. O conteúdo que você procura não foi encontrado. Que tal tentar de novo?
          </p>
          <?php get_search_form(); ?>
        </div>

        <?php endif; ?>
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
      </div><!--fim #content-->

<?php get_footer(); ?>
