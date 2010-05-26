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
            <?php
               $total = get_comments_number($post->ID);
               $num_pages = max(ceil($total / $rightcolumn_max_comments), 1);
               $current_page = isset($_GET['pagen']) ? $_GET['pagen'] : 0;
               $offset = $current_page * $rightcolumn_max_comments;

               if ($num_pages > 1):
             ?>
            <h3>Lista de páginas com comentários</h3>
            <ol>
              <?php
                 for ($i = 1; $i <= $num_pages; $i++) :
                   $link = get_bloginfo('url') .
                     '/?pid=' . $post->ID .
                     '&pagen=' . ($i - 1);
               ?>
              <li>
                <a href="<?php echo $link ?>"
                   title="Página <?php echo $i ?>">
                  <?php echo $i; ?>
                </a>
              </li>
              <?php endfor; ?>
            </ol>
            <?php endif; ?>

            <form action="get">
              <label for="">Campo busca de comentários</label>
              <input type="text" name="busca nos comentários" />
            </form>
          </div><!--fim #navegaComments-->

          <?php
               $comments = get_comments(array(
                               'post_id' => $post->ID,
                               'number'  => $rightcolumn_max_comments,
                               'offset'  => $offset));

              foreach ($comments as $comment):
           ?>

          <div class="comment">
            <div class="infoUser">
              <?php echo get_avatar($comment, 28) ?>
              <span class="date"><?php comment_date('d/m/Y') ?></span>
              <span class="user" >
                <a href="<?php get_comment_author_link() ?>">
                  <?php comment_author_link() ?>
                </a>
              </span>
            </div>
            <p><?php comment_text(); ?></p>
          </div><!--fim .comment-->

          <?php endforeach; ?>

        </div><!--fim #comments-->
      </div><!--fim #content-->

<?php get_footer(); ?>
