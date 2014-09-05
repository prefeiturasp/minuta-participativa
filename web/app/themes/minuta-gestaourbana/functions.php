<?php

function remove_password_email_text ( $text ) {
     if ($text == 'Uma senha será enviada para seu email.'){$text = '
        Uma senha será enviada para seu email. Verifique também a caixa de SPAM.';}
        return $text;
     }
//add_filter( 'gettext', 'remove_password_email_text' );

/* changes the "Register For This Site" text on the Wordpress login screen (wp-login.php) */
    function ik_change_login_message($message)
    {
        // change messages that contain 'Register'
        if (strpos($message, 'Registrar') !== FALSE) {
            $newMessage = "Olá! Faça seu cadastro prenchendo o formulário abaixo. Após o cadastro, um email contendo sua senha será enviado para você. <br /><br /><strong>ATENÇÃO</strong>: Verifique na caixa de <strong>SPAM</strong>, ele pode ter ido para lá acidentalmente.";
            return '<p class="message register">' . $newMessage . '</p>';
        }
        else {
            return $message;
        }
    }

    // add our new function to the login_message hook
    add_action('login_message', 'ik_change_login_message');

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_bloginfo( 'template_directory' ) ?>/images/logo-gestao_urbana.png);
            padding-bottom: 30px;
            background-size: 90%;
            background-position: 50% 50%;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function admin_default_page() {
  return '/';
}

add_filter('login_redirect', 'admin_default_page');

show_admin_bar(false);

/* --  foobar functions -- */

function get_descricao_tipo_contribuicao( $user_id )
{
    $manifestacao = get_the_author_meta('manifestacao', $user_id);

    if(empty($manifestacao))
        return "Não informado";

    $instituicao = get_the_author_meta('instituicao', $user_id);
    $descricao = ($manifestacao  == 'institucional') ?
        "{$instituicao} (contribuição institucional)" : "Particular";

    return $descricao;
}

function get_nome_completo( $user_id )
{
    $nome_completo = esc_attr(get_the_author_meta('nomecompleto', $user_id));

    if(empty($nome_completo))
        $nome_completo = esc_attr(get_the_author_meta('first_name', $user_id) .
        " " . get_the_author_meta('last_name', $user_id));

    $nome_completo = trim($nome_completo);

    return (empty($nome_completo)) ? "Não informado" : $nome_completo;
}

function substr_to_next_white_space( $the_string, $length ) {
  if( $the_string == "" )
    return;

  $end = $length - 1;
  $s = substr($the_string, $length, 1);

  while( $s != " ")
  {
    $length++;
    $s = substr($the_string, $length , 1);
  }

  return substr($the_string, 0, $length);
}

function substr_to_next_white_space_or_message( $the_string, $length, $message )
{
  $retVal = substr_to_next_white_space( $the_string, $length );
  return ( $retVal == null ) ? $message : $retVal;
}

/* -- Wordpress default stuff -- */

add_theme_support( 'automatic-feed-links' );

if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name' => 'Sidebar Esquerda',
    'id' => 'sidebar2',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>',
  ));

  register_sidebar(array(
    'name' => 'Sidebar Direita',
    'id' => 'sidebar1',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>',
  ));
}

/* -- global vars used in our theme -- */

/* Holds the current post being shown at index.php and page.php */
$current_post = null;

/* Specific validation functions */

function da_validate_br_cep($cep) {
    // retira espacos em branco
    $cep = trim($cep);
    // expressao regular para avaliar o cep
    $avaliaCep = preg_match("/^[0-9]{5}-[0-9]{3}$/", $cep);

    // verifica o resultado
    if(!$avaliaCep) {
        return false;
    } else {
        return true;
    }
}

function da_validate_register_fields($login, $email, $errors) {
  /* Specific validation */
  if (trim($_POST['cep']) != '' && !da_validate_br_cep($_POST['cep']))
    $errors->add('form_errors',
                 '<strong>ERRO:</strong> O CEP informado é inválido');
}
add_action('register_post', 'da_validate_register_fields', 10, 3);

/* Customizing comment data. This should only affect comments of posts
 * with dialogue enabled. */

function dialogue_preprocess_comment ($commentdata) {
  /* Doing nothing in common posts */
  $plugin_enabled =
    get_post_meta ($commentdata['comment_post_ID'], DIALOGUE_PMF, true);
  if ($plugin_enabled == "false")
    return $commentdata;

  /* I hate when I have to make something ugly like this,
   * but... there's a time in our lives that we have to forget some
   * principles and just do the job.
   *
   * This heavy hammer is here to bypass wordpress comment content
   * checks. No empty comments neither duplicated ones with this
   * tricky hack. */
  $commentdata['comment_content'] = date('Y m d H:m:s');
  return $commentdata;
}
add_filter ('preprocess_comment', 'dialogue_preprocess_comment');

/* add theme options to direitoautoral */
require_once ( get_template_directory() . '/theme-options.php' );

/* custom callback function to display the comments */
function direitoautoral_comment( $comment, $args, $depth )
{
  $GLOBALS['comment'] = $comment;
  $user_segmento = get_usermeta($comment->user_id, "segmento");
  if( empty( $user_segmento ) )
    $user_segmento = "Não informado";

  $direitoautoral_options = get_option('direitoautoral_config_theme_options');
  ?>
      <li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		  <?php if ( 'div' != $args['style'] ) : ?>
		    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		  <?php endif; ?>
		  <div class="comment-author vcard">
		  <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		    <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
        <?php if( is_array($direitoautoral_options) && array_key_exists('show_comment_data', $direitoautoral_options ) ) : ?>
          <span class="user_comment_direitoautoral_data_container">
            <span class="user_comment_direitoautoral_data">IP: <?php echo $comment->comment_author_IP; ?></span>
            <span class="user_comment_direitoautoral_data">Área de Atuação: <?php echo $user_segmento; ?></span>
          <span>
		      </div>
        <?php endif; ?>
        <?php if ($comment->comment_approved == '0') : ?>
		      <em><?php _e('Your comment is awaiting moderation.') ?></em>
		      <br />
        <?php endif; ?>

		    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'&nbsp;&nbsp;','') ?></div>

		    <?php comment_text() ?>

		    <div class="reply">
        <?php comment_reply_link(array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		    </div>
      <?php if ( 'div' != $args['style'] ) : ?>
		    </div>
		  <?php endif; ?>
  <?php
}

/* */
function search_contribuicoes( $search_query, $the_page )
{
  global $wpdb;
  $total_per_page = 6;
  $limit = $the_page * $total_per_page;
  $sql_count = "select count(*) as total
        from  wp_direitoautoral_commentmeta as cm where
        (cm.meta_key = 'Proposta' or cm.meta_key = 'Justificativa')
        and cm.meta_value like '%" . $search_query . "%'";

  $sql = "select
          date_format(c.comment_date,'%d/%m/%Y') as comment_date_formated,
          cm.*, c.*
        from
          wp_direitoautoral_commentmeta as cm, wp_direitoautoral_comments c
        where
          (cm.meta_key = 'Proposta' or cm.meta_key = 'Justificativa')
          and cm.meta_value like '%" . $search_query . "%'
          and c.comment_id = cm.comment_id and c.comment_approved = 1
        limit " . $limit . ", " . ($limit + $total_per_page);

  $comments_search = $wpdb->get_results($sql);
  $comments_count = $wpdb->get_results($sql_count);
  $comments_total = $comments_count[0]->total;


  $total_pages = $comments_total / $total_per_page;

  $previous_link = ($the_page >= 1)? get_bloginfo('url') . "/?s=" .
                    $search_query . "&comments_page=" . ($the_page - 1) : "";

  $next_link = ($the_page+1 <= $total_pages ) ? get_bloginfo('url') . "/?s=" .
                $search_query . "&comments_page=" . ($the_page + 1) : "";

  $first_link = get_bloginfo('url') . "/?s=" . $search_query . "&comments_page=0";

  $next_link_tag = ($the_page+1 <= $total_pages) ?
                      "<a href=\"{$next_link}\">Próximos</a>" : "";

  $previous_link_tag = ($the_page >= 1) ?
                      "<a href=\"{$previous_link}\">Anteriores</a>" : "";


  $retVal = array();
  $retVal['results'] = $comments_search;
  $retVal['total'] = $comments_total;
  $retVal['previous_link'] = $previous_link;
  $retVal['next_link'] = $next_link;
  $retVal['first_link'] = $first_link;
  $retVal['next_link_tag'] = $next_link_tag;
  $retVal['previous_link_tag'] = $previous_link_tag;

  return $retVal;
}

remove_filter( 'the_content', 'wpautop' );
//add_filter( 'the_content', 'wpautop' , 12);
?>