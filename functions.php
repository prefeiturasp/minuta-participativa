<?php

/* -- Wordpress default stuff -- */

automatic_feed_links();

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}

/* -- global vars used in our theme -- */

/* Holds the current post being shown at index.php and page.php */
$current_post = null;

?>
