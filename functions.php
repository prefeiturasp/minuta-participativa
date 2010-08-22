<?php

function my_admin_init() {
  add_action( 'manage_comments_custom_column', 'edit_comment_author_column' );
  add_filter('manage_edit-comments_columns', 'custom_manage_comment_columns');
}

function custom_manage_comment_columns ($cols) {
  $cols['dados_autor'] = 'Nome Completo e Tipo de Colaboração';
  return $cols;
}

function edit_comment_author_column($col_title, $id = 0){

  if($col_title == 'dados_autor') {
    $comment = get_comment ($id, ARRAY_A);
    $nomecompleto = get_nome_completo($comment['user_id']);
    $descricao_tipo_contribuicao =
        get_descricao_tipo_contribuicao($comment['user_id']);

    echo("<strong>{$nomecompleto}</strong><br/>{$descricao_tipo_contribuicao}");
  }
}

add_action( 'admin_init', 'my_admin_init' );

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

  $end = $length - 1;
  $s = substr($the_string, $length, 1);

  while( $s != " ")
  {
    $length++;
    $s = substr($the_string, $length , 1);
  }

  return substr($the_string, 0, $length);
}
/* -- Wordpress default stuff -- */

automatic_feed_links();

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

function da_show_user_profile($user) {
  include('userprofile/admin.php');
}
add_action('show_user_profile', 'da_show_user_profile');
add_action('edit_user_profile', 'da_show_user_profile');

function da_show_user_profile_regform() {
  include('userprofile/site.php');
}
add_action('register_form','da_show_user_profile_regform');

/* Specific validation functions */

function da_validate_br_cpf($cpf) {
  $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

  if (strlen($cpf) != 11)
    return false;
  for ($i = 0; $i <= 9; $i++) {
    $obvious_value = '';
    for ($j = 0; $j < 11; $j++)
      $obvious_value .= $i;
    if ($cpf == $obvious_value)
      return false;
  }

  for ($v = 9; $v < 11; $v++) {
    for ($d = 0, $c = 0; $c < $v; $c++)
      $d += $cpf{$c} * (($v + 1) - $c);
    if ($cpf{$c} != ((10 * $d) % 11) % 10)
      return false;
  }
  return true;
}

function da_validate_register_fields($login, $email, $errors) {
  /* User did not agree with terms or user, kick him!!! :D */
  if (!isset($_POST['agreeWithTermsOfUse']))
    $errors->add('form_erros',
                 '<strong>ERRO:</strong> Você precisa ler e aceitar ' .
                 'os termos de uso do site para prosseguir');

  /* List of required fields */
  $fields = array('cpf', 'estado', 'cidade', 'segmento', 'manifestacao',
                  'nomecompleto');
  foreach ($fields as $field) {
    if (trim($_POST[$field]) == '')
      $errors->add('form_erros',
          "<strong>ERRO:</strong> O campo $field está vazio");
  }

  /* Dependency validation */
  if (trim($_POST['manifestacao']) == 'institucional' &&
      trim($_POST['instituicao']) == '') {
    $errors->add('form_errors',
                 '<strong>ERRO:</strong> O campo instituição está vazio');
  }

  /* Specific validation */
  if (trim($_POST['cpf']) != '' && !da_validate_br_cpf($_POST['cpf']))
    $errors->add('form_errors',
                 '<strong>ERRO:</strong> O CPF informado é inválido');
}
add_action('register_post', 'da_validate_register_fields', 10, 3);

function da_register_extra_fields($user_id, $password="", $meta=array()) {
  /* list of all extra fields being inserted in the user entity */
  $fields = array('cpf', 'estado', 'cidade', 'segmento', 'manifestacao',
                  'nomecompleto', 'instituicao');

  /* Thanks to da_validade_register_fields we don't need to validate
   * fields here. */
  foreach ($fields as $field)
    update_usermeta($user_id, $field, $_POST[$field]);
}
add_action('user_register', 'da_register_extra_fields');

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

?>

