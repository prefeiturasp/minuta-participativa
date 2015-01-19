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

/**
 * Test if the required tables are installed. Using a simple `SHOW
 * TABLES'.
 */
function dialogue_is_installed ()
{
  global $wpdb;
  $sql = "SHOW TABLES";	
  $columns = $wpdb->get_results($sql, ARRAY_N);
  $reqtables = array ('dialogue_comment_tags', 'dialogue_comment_comment_tags');
  $found = 0;
  foreach ($columns as $column)
    if (in_array (str_replace ($wpdb->prefix, "", $column[0]), $reqtables))
      $found++;
  return $found == count ($reqtables);
}

function dialogue_install ()
{
  global $wpdb;
  if (!dialogue_is_installed ()) {
      $table_name = $wpdb->prefix . 'dialogue_comment_tags';
      $sql = "CREATE TABLE $table_name (
          tag_id bigint(20) unsigned NOT NULL auto_increment,
          name varchar (200) NOT NULL,
          PRIMARY KEY (tag_id),
          UNIQUE KEY (name)
      );";
      $wpdb->query ($sql);

      $table_name = $wpdb->prefix . 'dialogue_comment_comment_tags';
      $sql = "CREATE TABLE $table_name (
          id bigint(20) unsigned NOT NULL auto_increment,
          tag_id bigint(20) unsigned NOT NULL,
          comment_id bigint(20) unsigned NOT NULL,
          PRIMARY KEY (id),
          KEY (tag_id),
          KEY (comment_id)
      );";
      $wpdb->query ($sql);
    }
}

function dialogue_uninstall ()
{
  global $wpdb;
  if (dialogue_is_installed ())
    {
      $table_name = $wpdb->prefix . 'dialogue_comment_tags';
      $sql = "DROP TABLE $table_name;";
      $wpdb->query ($sql);

      $table_name = $wpdb->prefix . 'dialogue_comment_comment_tags';
      $sql = "DROP TABLE $table_name;";
      $wpdb->query ($sql);
    }
}

?>
