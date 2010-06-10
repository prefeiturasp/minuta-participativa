<div id="preview">
  <?php

     global $current_post;

     /* Number of posts to be listed */
     $maxposts = 5;

     /* From which post the listing should start (helper for the
      * pagination) */
     $offset = 0;

     /* Query that will return posts */
     $category = get_cat_ID("Blog");
     $allposts = get_posts("numberposts=$maxposts&" .
                           "category=$category&" .
                           "offset=$offset");

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
        $excerpt = strip_tags($content);
        if (strlen($excerpt) > 152) {
          $excerpt = substr($excerpt, 0, 152) . '...';
        }
        echo $excerpt;
      ?>
    </p>
  </div>

  <?php endforeach; ?>
</div><!--fim #preview-->
