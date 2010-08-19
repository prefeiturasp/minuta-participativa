<div id="preview">
  <?php

     global $current_post;

     /* Number of posts to be listed */
     $maxposts = get_option('posts_per_page');

     /* From which post the listing should start (helper for the
      * pagination) */
     $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

     /* Query that will return posts */
     $allposts = get_posts("numberposts=$maxposts&" .
                           "category_name=blog&" .
                           "paged=$paged");

     /* Using this `pid' flag to avoid clashing with `p' var that
      * leads the user to the `single.php' page. */
     if (isset($_GET['pid'])) {
       $current_post = get_post($_GET['pid']);
     } else {
       $current_post = get_post ($allposts[0]->ID);
     }

     $current_id = $current_post->ID;
     foreach ($allposts as $post) :
       /* Setting up post data to make the_*() functions work */
       setup_postdata($post);

       /* Defining if this post is the selected one */
       $selected = $current_id == $post->ID ? 'select' : '';

       if( is_home() and $selected == 'select') continue;
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
      <?php
        if ($post->post_excerpt)
          $content = $post->post_excerpt;
        else
          $content = $post->post_content;
        
        echo substr_to_next_white_space( strip_tags($content), 152 ) . " ...";
      ?>
    </p>
  </div>

  <?php endforeach; ?>
  <?php posts_nav_link(); ?>

</div><!--fim #preview-->
