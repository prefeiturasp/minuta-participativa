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

        <div id="comments">
          <div id="navegaComments">
            <h3>Lista de páginas com comentários</h3>
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
          </div><!--fim #navegaComments-->
          <div class="comment">
            <div class="infoUser">
              <img src="images/avatar.png" alt="Imagem de visualização do usuário" />
              <span class="date">27/10/2009</span>
              <span class="user" >Severino Jose do Santos de Oliveira e Silva</span>
            </div>
            <p>Nunc porttitor tincidunt magna, at rutrum lorem
            molestie eu. Proin vitae magna elit. Etiam enim dui,
            vestibulum eu vestibulum eget, egestas nec justo. Aenean
            at mi arcu, nec egestas velit. Aenean vitae justo
            augue.</p>
          </div><!--fim .comment-->
        </div><!--fim #comments-->
      </div><!--fim #content-->

<?php get_footer(); ?>
