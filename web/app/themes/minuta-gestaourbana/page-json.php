<?php /* Template Name: JSON */
//get_header();
if (have_posts()) : while (have_posts()) : the_post();
echo json_encode(array('content'=>get_the_content()));
endwhile; endif;
//get_footer();
?>