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

$globaltags = array(
    "Contratos",
    "Direitos conexos",
    "Direitos morais",
    "Direitos patrimoniais",
    "Domínio público",
    "Gestão coletiva",
    "Licenças não-voluntárias",
    "Limitações",
    "Obra audiovisual",
    "Obra protegida",
    "Obra sob encomenda",
    "Registro",
    "Reprografia",
    "Sanções",
    "Utilização de obra"
);

function dialogue_comment_outline($id, $text) {
  if (strlen($text) > 250) {
    return substr($text, 0, 250) .
      '<a title="Clique aqui para ler todo o comentário" ' .
      '   href="javascript:;"' .
      '   onclick="dialogue_expand_comment(\'' . $id. '\')">(leia mais)</a>';
  } else {
    return $text;
  }
}

function dialogue_expand_children($children, $pid, $post) {
  if (count($children) > 0) {
    ob_start();
    $cmold = $GLOBALS['comment'];
    foreach ($children as $comment) {
      $GLOBALS['comment'] = $comment;
      dialogue_comment_div(get_comment_id(), $pid, $post);
    }
    $GLOBALS['comment'] = $cmold;
    $res = "<ul>" . ob_get_contents() . "</ul>";
    ob_end_clean();
    return $res;

  } else {
    return "";
  }
}

function dialogue_comment_div($cid, $pid, $post) {
  $children = dialogue_api_get_children_paragraph_comments($cid);
?>
  <li>
    <?php
      if (get_post_meta($pid, DIALOGUE_PMF_SHOW_COMMENTS, true) == "true") :
        echo get_avatar( get_comment_author_email(), '48' );
    ?>
    <div class="comdados">
      <strong><?php echo get_comment_author_link(); ?></strong>
      em <?php echo get_comment_date('d/m/Y H:i'); ?>

      <?php
        $tags = dialogue_api_get_comment_tags($cid);
        $len_tags = count ($tags);

        if ($len_tags != 0):
       ?>

      <div class="tags">
        <label>Tags:</label>
        <?php
         for ($i = 0; $i < $len_tags; $i++) {
           echo $tags[$i]->name;
           if ($i < ($len_tags - 1))
             echo ', ';
         }
        ?>
      </div>
      <?php endif; ?>

    </div>
    <?php endif; ?>

    <div class="comdata">

      <?php
         if (get_post_meta($pid, DIALOGUE_PMF_SHOW_COMMENTS, true) == "true") :
      ?>
      <strong>Texto do comentário:</strong>
      <div id="comment-area-<?php echo $cid; ?>">
        <?php echo dialogue_comment_outline($cid, get_comment_text()); ?>
      </div>
      <div style="display:none" id="comment-full-<?php echo $cid; ?>">
        <?php comment_text() ?>
      </div>
      <?php
       $new_text = get_comment_meta ($cid, DIALOGUE_CF_NEWTEXTPROPOSAL, true);
       if (!empty ($new_text)) {
         echo '<div class="newText clear">';
         echo '<strong>Proposta de nova redação:</strong> ';
         echo '<p>' . $new_text . '</p>';
         echo '</div>';
       }
       ?>
      <div class="responderspace">
        <button
           title="Clique aqui para responder ao autor deste comentário"
           onclick="jQuery('#comment-the-comment-<?php echo $cid?>').show();
                    jQuery(this).hide()">
          Responder
        </button>
      </div>
      <?php endif; ?>

      <div style="display:none" id="comment-the-comment-<?php echo $cid?>">

        <form
           action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php"
           method="post" id="commentform-<?php echo $pid . $cid; ?>">

          <input type="hidden" name="dialogue_comment_paragraph"
                 value="<?php echo ($pid); ?>">
          <input type="hidden" name="comment_post_ID"
                 value="<?php echo ($post->ID); ?>">
          <input type="hidden" name="comment_parent"
                 value="<?php echo $cid; ?>" />

          <ol>
            <li>
              <label>Deixe seu comentário</label>
              <textarea name="comment"
                        class="comment required wider"></textarea>
            </li>
            <li class="tag">
              <label>Tags <em>(separadas por vírgula)</em></label>
              <input type="text" name="comment_tags" class="border wider"/>
            </li>
            <li class="newText">
              <input type="checkbox" name="new_text_check"
                     class="checkNewText" id="new_text_check" />
              <label for="new_text_check">
                Quero sugerir uma nova redação
              </label>
              <textarea name="dialogue_comment_new_text"
                        class="newText wider"></textarea>
            </li>
            <li class="last">
              <input type="submit" value="Enviar" />
            </li>
          </ol>

        </form>
      </div>
    </div>

    <?php
      if (get_post_meta($pid, DIALOGUE_PMF_SHOW_COMMENTS, true) == "true")
        echo dialogue_expand_children($children, $pid, $post);
    ?>
  </li>
<?php }

/*
 * TODO: É necessário refatorar esse método utilizando o comment_form()
 * http://codex.wordpress.org/Function_Reference/comment_form
 */
function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
        '/[^\S ]+\</s', //strip whitespaces before tags, except space
        '/(\s)+/s'  // shorten multiple whitespace sequences
        );
    $replace = array(
        '>',
        '<',
        '\\1'
        );
  $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}
function dialogue_comment_html ($pid, $post, $content) {

  $comments = dialogue_api_get_toplevel_paragraph_comments($pid, $post->ID);
  $count = dialogue_api_get_paragraph_comments_count ($pid, $post->ID);

  /* Here we go again, another hammer... */


ob_start();

?><div class="comment-pp"><p class="comment-text"><?php echo ($content);?><a class="comment-button" href="javascript:;"><i class="icon-comment-bg"></i><?php if ($count > 0) { echo "$count "; } ?></a></p><div class="message"></div>
  <div class="comment-form"><div class="loading" style="display:none;">Enviando seu comentário</div><?php global $current_user; get_currentuserinfo();
    if (comments_open ()) :
        if (is_user_logged_in()  ) : ?><form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform-<?php echo $pid; ?>"><input type="hidden" name="dialogue_comment_paragraph" value="<?php echo ($pid); ?>" /><input type="hidden" name="comment_post_ID" value="<?php echo ($post->ID); ?>" /><input type="hidden" name="comment" value="." /><h6><i class="icon-comment-bg"></i>comentar este trecho</h6></form>
      <?php
        else:
      /*
          fields needed to make it possible to list comments when not
          logged in
      */
      ?><input type="hidden" name="dialogue_comment_paragraph" value="<?php echo ($pid); ?>" /><input type="hidden" name="comment_post_ID" value="<?php echo ($post->ID); ?>" /><p>Para habilitar os comentários, por favor, faça seu <a href="<?php echo get_option('siteurl'); ?>/wp-login.php" class="login-button">login</a>.</p></form>
<?php
        endif;
    else :
?>
<input type="hidden" name="dialogue_comment_paragraph" value="<?php echo ($pid); ?>" /><input type="hidden" name="comment_post_ID" value="<?php echo ($post->ID); ?>" /><p>Desculpe, mas o período de participação já foi encerrado.</p></form>
<?php endif; ?></div></div><?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
?>