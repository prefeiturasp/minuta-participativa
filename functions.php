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

/* -- extending user profile -- */

function da_show_user_profile($user) {
  include('userprofile/admin.php');
}
add_action('show_user_profile', 'da_show_user_profile');
add_action('edit_user_profile', 'da_show_user_profile');

function da_show_user_profile_regform() {
  include('userprofile/site.php');
}
add_action('register_form','da_show_user_profile_regform');

function da_validade_register_fields($login, $email, $errors) {
  /* List of required fields */
  $fields = array('cpf', 'estado', 'cidade', 'segmento', 'manifestacao');
  foreach ($fields as $field) {
    if (trim($_POST[$field]) == '')
      $errors->add('form_erros',
          "<strong>ERROR:</strong> O campo $field está vazio");
  }

  /* Dependency validation */
  if (trim($_POST['manifestacao']) == 'institucional' &&
      trim($_POST['instituicao']) == '') {
    $errors->add('form_errors',
                 '<strong>ERROR:</strong> O campo instituição está vazio');
  }
}
add_action('register_post', 'da_validade_register_fields', 10, 3);

function da_register_extra_fields($user_id, $password="", $meta=array()) {
  /* list of all extra fields being inserted in the user entity */
  $fields = array('cpf', 'estado', 'cidade', 'segmento', 'manifestacao',
                  'instituicao');

  /* Thanks to da_validade_register_fields we don't need to validate
   * fields here. */
  foreach ($fields as $field)
    update_usermeta($user_id, $field, $_POST[$field]);
}
add_action('user_register', 'da_register_extra_fields');

?>
