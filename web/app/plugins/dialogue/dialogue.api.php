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

/* Defining extra fields for comments. Since wp 2.9 we have the
 * commentsmeta stuff, so we don't need to change the main comments
 * table. */
define ('DIALOGUE_CF_PARAGRAPH',          '_dialogue_cf_paragraph');
define ('DIALOGUE_CF_NEWTEXTPROPOSAL',    '_dialogue_cf_newtextproposal');

function dialogue_api_parse_content ($content)
{
  $newct = array ();
  $pattern = get_shortcode_regex ();
  $len = preg_match_all ('/'.$pattern.'/s', $content, $paragraphs);
  for ($i = 0; $i < $len; $i++)
    {
      if ($paragraphs[2][$i] != 'commentable')
        continue;
      $attrs = shortcode_parse_atts ($paragraphs[3][$i]);
      $newct[$attrs['id']] = $paragraphs[5][$i];
    }
  return $newct;
}

function dialogue_api_get_comments ($post)
{
  return get_comments ($post, ARRAY_A);
}

function dialogue_api_get_paragraph_comments_count($paragraph, $post)
{
  global $wpdb;

  $sql = "SELECT COUNT(*) as count FROM {$wpdb->comments} AS c INNER JOIN
    {$wpdb->prefix}commentmeta as m ON (m.comment_ID = c.comment_ID)
    WHERE c.comment_post_ID = %d AND m.meta_key = '" . DIALOGUE_CF_PARAGRAPH . "'
    AND m.meta_value = %s AND c.comment_approved = 1 ORDER BY c.comment_ID;";
  $query = $wpdb->prepare ($sql, $post, $paragraph);
  $res = $wpdb->get_results ($query);
  return $res[0]->count;
}

function dialogue_api_get_user_meta ($user_email)
{
  global $wpdb;

  $sql = "SELECT um.* FROM {$wpdb->prefix}usermeta um,
    {$wpdb->prefix}users u  WHERE
    u.user_email=%s AND u.id = um.user_id ";

  $query = $wpdb->prepare ($sql, $user_email);
  $user_meta_data = array();
  foreach( $wpdb->get_results ($query) as $user_meta)
    {
      $user_meta_data[$user_meta->meta_key] =  $user_meta->meta_value;
    }

  return $user_meta_data;
}

function dialogue_api_get_comments_without_paragraphs ($post)
{
  global $wpdb;

  $sql = "SELECT
      c.comment_ID, c.comment_post_ID, c.comment_author, c.comment_author_email, c.comment_author_IP,
      c.comment_author_url, c.comment_date, c.comment_date_gmt,
      c.comment_content, c.comment_karma, c.comment_agent,
      c.comment_type, c.comment_parent, um.meta_value AS user_name
    FROM
      {$wpdb->usermeta} AS um,
      {$wpdb->comments} AS c
    INNER JOIN {$wpdb->prefix}commentmeta as m
      ON (m.comment_ID = c.comment_ID)
    WHERE c.comment_post_ID = %d
      AND m.meta_key = '" . DIALOGUE_CF_PARAGRAPH . "'
      AND m.meta_value = ''
      AND c.comment_approved = 1
      AND um.user_id = c.user_id
      AND um.meta_key = 'nomecompleto'
    ORDER BY c.comment_ID;";
  $query = $wpdb->prepare ($sql, $post);
  $comments = $wpdb->get_results ($query);

  /* Getting meta values for the comments */
  $result = array();
  foreach ($comments as $comment)
    {
      $comment->instituicao = esc_attr(get_the_author_meta('instituicao', $comment->user_id));
      $comment->user_meta = dialogue_api_get_user_meta ($comment->comment_author_email);
      $comment->meta = array();
      $sql = "SELECT meta_key, meta_value
        FROM {$wpdb->prefix}commentmeta
        WHERE comment_id = {$comment->comment_ID}";
      foreach ($wpdb->get_results ($sql) as $m)
        $comment->meta[$m->meta_key] = $m->meta_value;
      $comment->comment_author_email = null;
      $comment->tags = dialogue_api_get_comment_tags($comment->comment_ID);
      array_push ($result, $comment);
    }
  return $result;
}


function dialogue_api_get_paragraph_comments ($paragraph, $post)
{
  global $wpdb;

  $sql = "SELECT
      c.comment_ID, c.comment_post_ID, c.comment_author, c.comment_author_email, c.comment_author_IP,
      c.comment_author_url, c.comment_date, c.comment_date_gmt,
      c.comment_content, c.comment_karma, c.comment_agent,
      c.comment_type, c.comment_parent, um.meta_value AS user_name
    FROM
      {$wpdb->usermeta} AS um,
      {$wpdb->comments} AS c
    INNER JOIN {$wpdb->prefix}commentmeta as m
      ON (m.comment_ID = c.comment_ID)
    WHERE c.comment_post_ID = %d
      AND m.meta_key = '" . DIALOGUE_CF_PARAGRAPH . "'
      AND m.meta_value = %s
      AND c.comment_approved = 1
      AND um.user_id = c.user_id
      AND um.meta_key = 'nickname'
      AND um.meta_value != 'smdu'
    ORDER BY c.comment_ID;";
  $query = $wpdb->prepare ($sql, $post, $paragraph);
  $comments = $wpdb->get_results ($query);
//var_dump($query);
  /* Getting meta values for the comments */
  $result = array();
  foreach ($comments as $comment)
    {
      $comment->instituicao = esc_attr(get_the_author_meta('instituicao', $comment->user_id));
      $comment->user_meta = dialogue_api_get_user_meta ($comment->comment_author_email);
      $comment->meta = array();
      $sql = "SELECT meta_key, meta_value
        FROM {$wpdb->prefix}commentmeta
        WHERE comment_id = {$comment->comment_ID}";
      foreach ($wpdb->get_results ($sql) as $m)
        $comment->meta[$m->meta_key] = $m->meta_value;
      $comment->comment_author_email = null;
      //$comment->tags = dialogue_api_get_comment_tags($comment->comment_ID);
      array_push ($result, $comment);
    }
  return $result;
}


function dialogue_api_get_paragraph_comments_featured ($paragraph, $post)
{
  global $wpdb;

  $sql = "SELECT
      c.comment_ID, c.comment_post_ID, c.comment_author, c.comment_author_email, c.comment_author_IP,
      c.comment_author_url, c.comment_date, c.comment_date_gmt,
      c.comment_content, c.comment_karma, c.comment_agent,
      c.comment_type, c.comment_parent, um.meta_value AS user_name
    FROM
      {$wpdb->usermeta} AS um,
      {$wpdb->comments} AS c
    INNER JOIN {$wpdb->prefix}commentmeta as m
      ON (m.comment_ID = c.comment_ID)
    WHERE c.comment_post_ID = %d
      AND m.meta_key = '" . DIALOGUE_CF_PARAGRAPH . "'
      AND m.meta_value = %s
      AND c.comment_approved = 1
      AND um.user_id = c.user_id
      AND um.meta_key = 'nickname'
      AND um.meta_value = 'smdu'
    ORDER BY c.comment_ID;";
  $query = $wpdb->prepare ($sql, $post, $paragraph);
  $comments = $wpdb->get_results ($query);

  /* Getting meta values for the comments */
  $result = array();
  foreach ($comments as $comment)
    {
      $comment->instituicao = esc_attr(get_the_author_meta('instituicao', $comment->user_id));
      $comment->user_meta = dialogue_api_get_user_meta ($comment->comment_author_email);
      $comment->meta = array();
      $sql = "SELECT meta_key, meta_value
        FROM {$wpdb->prefix}commentmeta
        WHERE comment_id = {$comment->comment_ID}";
      foreach ($wpdb->get_results ($sql) as $m)
        $comment->meta[$m->meta_key] = $m->meta_value;
      $comment->comment_author_email = null;
      //$comment->tags = dialogue_api_get_comment_tags($comment->comment_ID);
      array_push ($result, $comment);
    }
  return $result;
}

function dialogue_api_get_toplevel_paragraph_comments ($paragraph, $post)
{
  global $wpdb;

  $sql = "SELECT c.* FROM {$wpdb->comments} AS c INNER JOIN
    {$wpdb->prefix}commentmeta as m ON (m.comment_ID = c.comment_ID)
    WHERE c.comment_post_ID = %d AND m.meta_key = '" . DIALOGUE_CF_PARAGRAPH . "'
    AND m.meta_value = %s
    AND c.comment_parent = 0 AND c.comment_approved = 1 ORDER BY c.comment_ID;";
  $query = $wpdb->prepare ($sql, $post, $paragraph);
  return $wpdb->get_results ($query);
}

function dialogue_api_get_children_paragraph_comments($comment_id)
{
  global $wpdb;

  $sql = "SELECT * FROM {$wpdb->comments}
    WHERE comment_parent= $comment_id
    AND c.comment_approved = 1 ORDER BY comment_ID;";
  $query = $wpdb->prepare ($sql, $post, $paragraph);
  return $wpdb->get_results ($query);
}

function dialogue_api_get_comment_paragraph ($comment)
{
  return get_comment_meta ($comment, DIALOGUE_CF_PARAGRAPH, true);
}

function dialogue_api_get_tags ()
{
  global $wpdb;
  $sql = "SELECT tag_id, name FROM {$wpdb->prefix}dialogue_comment_tags;";
  return $wpdb->get_results ($sql);
}

function dialogue_api_get_comment_tags ($comment)
{
  global $wpdb;
  $sql = "SELECT t.tag_id, t.name, ct.tag_id, ct.comment_id
    FROM {$wpdb->prefix}dialogue_comment_tags AS t
    INNER JOIN {$wpdb->prefix}dialogue_comment_comment_tags AS ct
      ON (ct.tag_id = t.tag_id)
    WHERE ct.comment_id = %d
    ORDER BY t.name;";
  $query = $wpdb->prepare ($sql, $comment);
  return $wpdb->get_results ($query);
}

function dialogue_api_process_request ()
{
  if (!empty ($_REQUEST['dialogue_query']))
    {
      /* Make sure it still work if magic quotes are enabled */
      $json = str_replace("\\", "", $_REQUEST['dialogue_query']);
      $query = json_decode ($json);
      if (function_exists('json_last_error'))
        {
          /* Function json_last_error() is not available before PHP
           * 5.3.0 version. */
          $error = null;
          switch (json_last_error())
            {
            case JSON_ERROR_DEPTH:
              $error = 'Maximum stack depth exceeded';
              break;
            case JSON_ERROR_CTRL_CHAR:
              $error = 'Unexpected control character found';
              break;
            case JSON_ERROR_SYNTAX:
              $error = 'Syntax error, malformed JSON';
              break;
            case JSON_ERROR_NONE:
              break;
            }
          if ($error != null)
            die("{\"error\":$error}");
        }
      if ($query->method == null)
        die("{\"error\":\"Unable to decode json query\"}");

      /* I'm not so stupid to allow the user to call an arbitrary
       * function here... neither to pass \b or any other tricky
       * stuff! */
      $func = 'dialogue_api_' . $query->method;
      $params = $query->params;

      die (json_encode (call_user_func_array ($func,  $params)));
    }
}
?>
