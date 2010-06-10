<?php get_header(); ?>

      <div id="content">
        <?php include('preview.php'); ?>

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post">
          <span class="title">Blog</span>
          <ul class="navegaPost">
            <li><?php previous_post_link('Anterior %link') ?></li>
            <li><?php next_post_link('%link Próximo') ?></li>
          </ul>
          <h2><?php the_title(); ?></h2>
          <p class="date"><?php the_time('d/m/Y')?></p>
          <?php the_content('<p class="serif">Leia mais &raquo;</p>'); ?>

          <?php comments_template(); ?>

        </div><!--fim .post-->
        <?php endwhile; endif; ?>

		<div id="widgets">
        	<?php get_sidebar(); ?>
		</div>

<?php get_footer(); ?>
