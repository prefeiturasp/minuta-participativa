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

          <a href="#" title="Exibir comentários" class="lerComentarios">
            ler comentários
          </a>
        </div><!--fim .post-->

        <?php else : ?>
        <div class="post">
          <h2 class="center">Not Found</h2>
          <p class="center">
            Sorry, but you are looking for something that isn't here.
          </p>
          <?php get_search_form(); ?>
        </div>

        <?php endif; ?>
        <?php include('commentlist.php') ?>
      </div><!--fim #content-->

<?php get_footer(); ?>
