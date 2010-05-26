<div id="preview">
  <?php

     global $current_post;

     if (isset($_GET['pid'])) {
       $current_post = get_post($_GET['pid']);
     } else if (isset($post)) {
       $current_post = $post;
     } else {
       $all = wp_get_recent_posts (1);
       $current_post = get_post ($all[0]['ID']);
     }

     $current_id = $current_post->ID;
     if (have_posts()) : while (have_posts()) :
       the_post();
       $selected = $current_id == $post->ID ? 'select' : '';
   ?>

  <div class="preview-post <?php echo $selected; ?>">
    <h3>
      <a href="<?php bloginfo('url')?>/?pid=<?php /*<*/ echo $post->ID;?>">
        <?php the_title(); ?>
      </a>
      <?php if ($selected != ''): ?>
      <span class="seta"></span>
      <?php endif; ?>
    </h3>
    <p class="date"><?php the_time('d/m/Y')?></p>
    <p>
      Integer bibendum mi id velit lobortis id venenatis elit
      hendrerit. Pellentesque ut sapien ipsum. Praesent eu
      vulputate nibh.
    </p>
  </div>

  <?php endwhile; endif; ?>
</div><!--fim #preview-->
