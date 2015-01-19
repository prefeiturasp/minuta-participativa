<?php
/*
Plugin Name: Dialogue
Plugin URI: http://xemele.cultura.gov.br
Description: A hack to separate comments per paragraph
Version: 0.1
Author: Lincoln de Sousa
Author URI: http://lincoln.comum.org
License: AGPLv3
*/

/* Copyright 2010  Ministério da Cultura Brasileiro
 *
 *     Author: Lincoln de Sousa <lincoln@comum.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once ('dialogue.install.php');
include_once ('dialogue.api.php');
include_once ('dialogue.admin.php');
include_once ("dialogue.comment.php");

define ('DIALOGUE_PLUGIN_URL', WP_PLUGIN_URL . '/dialogue/');

/* Enqueuing all necessary js scripts and css styles that this plugin
 * needs to work. */
/*
wp_enqueue_script('jquery');
wp_enqueue_script('jquery.forms', DIALOGUE_PLUGIN_URL . 'js/jquery.form.js');
wp_enqueue_script('comments-pp', DIALOGUE_PLUGIN_URL . 'js/comments-pp.js');
wp_enqueue_style('comments-pp', DIALOGUE_PLUGIN_URL . 'css/comments-pp.css',
                 array(), false, 'all'); */

/**
 * Shortcode handler that makes a paragraph commentable.  It calls the
 * `dialogue_comment_html' function that builds an html form to receive
 * comments for each paragraph.
 */
function comment_func ($attrs, $content='')
{
  global $post;
  if (get_post_meta ($post->ID, DIALOGUE_PMF, true) == "false")
    return $content;
  extract (shortcode_atts (array ('id' => ''), $attrs));
  if (trim ($content) != '')
    return dialogue_comment_html (esc_attr ($id), $post, $content);
  else
    return $content;
}
add_shortcode('commentable', 'comment_func');

/**
 * This function will be hooked to the `the_content' wp action and
 * will break the post content in using the <p> tags. Each found
 * paragraph will be processed by `process_paragraph()' function.
 */
function dialogue_process_content ($content)
{
  global $post;
  if (get_post_meta ($post->ID, DIALOGUE_PMF, true) == "false")
    return $content;
  return do_shortcode ($content);
}
add_filter ('the_content', 'dialogue_process_content');

/**
 * Hook connected to the `insert_comment' action responsible for
 * associating the number of the paragraph to the comment. The best
 * place I found to pass this info was the request in the form action
 * field =/.
 */
function dialogue_on_insert_comment ($comment)
{
  global $wpdb;

  /* This updates the just inserted comment with the selected
   * paragraph id. */
  $val = $_POST['dialogue_comment_paragraph'];
  add_comment_meta ($comment, DIALOGUE_CF_PARAGRAPH, $val, true) or
    update_comment_meta ($comment, DIALOGUE_CF_PARAGRAPH, $val);

  /* Storing extra fields */
  $fields = array('opiniao', 'proposta', 'contribuicao', 'justificativa');
  foreach ($fields as $field)
    add_comment_meta ($comment, $field, trim($_POST[$field]), true) or
      update_comment_meta ($comment, $field, trim($_POST[$field]));

  /* Handling tags */
  $tags = (array) $_POST['comment_tags'];
  foreach ($tags as $tag)
    {
      $table_name = $wpdb->prefix . 'dialogue_comment_tags';
      $sql = "SELECT tag_id FROM $table_name WHERE name = %s";
      $name = trim ($tag);
      $query = $wpdb->prepare ($sql, $name);
      $tid = $wpdb->get_var ($query);
      if ($tid == null)
        {
          /* Tag doesn't exist, lets create it */
          $wpdb->insert ($wpdb->prefix . 'dialogue_comment_tags',
                         array ('name' => $name));
          $tid = $wpdb->insert_id;
        }

      /* Time to associate a tag to a comment */
      $wpdb->insert ($wpdb->prefix . 'dialogue_comment_comment_tags',
                     array ('tag_id' => $tid, 'comment_id' => $comment));
    }
}
add_action ('wp_insert_comment', 'dialogue_on_insert_comment');

/* Registering plugin {de,}activation hooks */
register_activation_hook (__FILE__, 'dialogue_install');
register_deactivation_hook (__FILE__, 'dialogue_uninstall');

/* It is a wp-mu hack. http://mu.wordpress.org/forums/topic/13594 */
dialogue_install ();

dialogue_api_process_request ();
?>
