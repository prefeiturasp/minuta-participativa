<?php get_header(); ?>

      <div id="content">
        <?php include('preview.php'); ?>

        <div class="post">
          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <span class="title">Blog</span>
          <ul class="navegaPost">
            <li><a href="#" title="post anterior">Anterior</a></li>
            <li><a href="#" title="proximo post">Próximo</a></li>
          </ul>
          <h2><?php the_title(); ?></h2>
          <p class="date"><?php the_time('d/m/Y')?></p>
          <?php the_content('<p class="serif">Leia mais &raquo;</p>'); ?>

          <?php comments_template(); ?>

          <?php endwhile; endif; ?>

        </div><!--fim .post-->

        <?php get_sidebar(); ?>

<?php get_footer(); ?>
