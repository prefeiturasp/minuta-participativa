<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
  //register_setting( 'shabadoo_options', 'shabadoo_theme_options', 'theme_options_valid
	register_setting( 'direitoautoral_config_options', 'direitoautoral_config_theme_options');
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Configurações do Direito Autoral' ), __( 'Configurações do Direito Autoral' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}


/**
 * Create the options page
 */
function theme_options_do_page() {

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Configurações do Direito Autoral' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Salvar configurações' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'direitoautoral_config_options' ); ?>
			<?php $options = get_option( 'direitoautoral_config_theme_options' ); ?>

			<table class="form-table">
      <tr valign="top"><th scope="row"><?php _e( 'Exibir captchas? (Lembrete ativar o plugin "si-captcha-for-wordpress")' ); ?></th>
					<td>
						<input id="direitoautoral_config_theme_options[show_captcha]" name="direitoautoral_config_theme_options[show_captcha]" type="checkbox" value="1" <?php checked( '1', $options['show_captcha'] ); ?> />
						<label class="description" for="direitoautoral_config_theme_options[show_captcha]"><?php _e( 'Sim' ); ?></label>
					</td>
				</tr>

        <tr valign="top"><th scope="row"><?php _e( 'Exibir IP e atividade fim nos comentários?' ); ?></th>
					<td>
						<input id="direitoautoral_config_theme_options[show_comment_data]" name="direitoautoral_config_theme_options[show_comment_data]" type="checkbox" value="1" <?php checked( '1', $options['show_comment_data'] ); ?> />
						<label class="description" for="direitoautoral_config_theme_options[show_comment_data]"><?php _e( 'Sim' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

?>
