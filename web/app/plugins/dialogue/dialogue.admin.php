<?php
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

include_once ("dialogue.api.php");

/* Defining POST META FIELDS (a.k.a. PMF) */
define ('DIALOGUE_PMF', '_dialogue_enabled');
define ('DIALOGUE_PMF_SHOW_COMMENTS', '_dialogue_show_comments');

function dialogue_manage_comment_columns ($cols)
{
  $cols['comment_content'] = __('Opinião e proposta');
  $cols['justificativa'] = __('Justificativa');
  $cols['dialogue_paragraph'] = __("Paragraph");
  $cols['dialogue_tags'] = __("Tags");
  return $cols;
}
add_filter('manage_edit-comments_columns', 'dialogue_manage_comment_columns');

function dialogue_manage_comment_custom_column ($col, $comment_id = 0)
{
  $comment = get_comment ($comment_id, ARRAY_A);
  $cmid = $comment['comment_ID'];
  if ($col == 'comment_content')
    {
      echo '<strong>Opinião</strong><br />';
      echo '<div>' . get_comment_meta($cmid, 'opiniao', true) . '</div>';

      echo '<strong>Proposta</strong><br />';
      echo '<div>' . get_comment_meta($cmid, 'proposta', true) . '</div>';

      if (trim(get_comment_meta($cmid, 'contribuicao', true) != ''))
        {
          echo '<strong>Contribuição</strong>';
          echo '<div>' . get_comment_meta($cmid, 'contribuicao', true) . '</div>';
        }

    }
  else if ($col == 'justificativa')
    {
      echo '<div>' . get_comment_meta($cmid, 'justificativa', true) . '</div>';
    }
  else if ($col == 'dialogue_paragraph')
    {
      $post = get_post ($comment["comment_post_ID"]);
      $pid = (string) get_comment_meta ($comment['comment_ID'],
                                        DIALOGUE_CF_PARAGRAPH, true);
      $paragraphs = dialogue_api_parse_content (wpautop ($post->post_content));
      $paragraph = $paragraphs[$pid];

      echo "<strong>$pid</strong> &mdash ";
      echo wp_html_excerpt ($paragraph, 128) . '...';
    }
  else if ($col == 'dialogue_tags')
    {
      $cid = $comment["comment_ID"];
      $tags = dialogue_api_get_comment_tags ($cid); 
      $len = sizeof ($tags);
      for ($i = 0; $i < $len; $i++)
        {
          $name = $tags[$i]->name;
          if (trim ($name) == "")
            continue;
          echo "#$name" . ($i != $len - 1 ? ", " : "");
        }
    }
}
add_action ('manage_comments_custom_column', 'dialogue_manage_comment_custom_column');

function dialogue_post_metabox ($post)
{
  if (get_post_meta ($post->ID, DIALOGUE_PMF, true) == "true")
    $plugin_enabled_checked = 'checked="checked"';
  else
    $plugin_enabled_checked = '';

  if (get_post_meta ($post->ID, DIALOGUE_PMF_SHOW_COMMENTS, true) == "true")
    $comments_enabled_checked = 'checked="checked"';
  else
    $comments_enabled_checked = '';

  echo '
  <p class="meta-options">
    <label>
      <input type="checkbox" name="' . DIALOGUE_PMF . '" '
      . $plugin_enabled_checked . '/>'
      . __('Enable the dialogue plugin in this post.') . '
    </label>

    <label>
      <input type="checkbox" name="' . DIALOGUE_PMF_SHOW_COMMENTS . '" '
      . $comments_enabled_checked . '/>'
      . __('Show dialogue comments in this post.') . '
    </label>
  </p>
  ';
}

function dialogue_add_custom_box ()
{
  add_meta_box ('post-dialogue', __('Split comments per paragraph'), 'dialogue_post_metabox',
                'post', 'normal', 'core');
}
add_action ('admin_menu', 'dialogue_add_custom_box');

function dialogue_save_post ($post_id)
{
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    return $post_id;

  $state = isset ($_POST[DIALOGUE_PMF]) ? "true" : "false";
  add_post_meta ($post_id, DIALOGUE_PMF, $state, true) or
    update_post_meta ($post_id, DIALOGUE_PMF, $state);

  $state = isset ($_POST[DIALOGUE_PMF_SHOW_COMMENTS]) ? "true" : "false";
  add_post_meta ($post_id, DIALOGUE_PMF_SHOW_COMMENTS, $state, true) or
    update_post_meta ($post_id, DIALOGUE_PMF_SHOW_COMMENTS, $state);

  return $state;
}
add_action ('save_post', 'dialogue_save_post');
?>
