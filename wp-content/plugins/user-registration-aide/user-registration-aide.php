<?php
/*
Plugin Name: User Registration Aide
Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
Description: Forces new users to register additional fields with the option to add additional fields other than those supplied with the default Wordpress Installation. We have kept it simple in this version for those of you whom aren't familiar with handling multiple users or websites. We also are currently working on expanding this project with a paid version which will contain alot more features and options for those of you who wish to get more control over users and user access to your site.

Version: 1.2.8
Author: Brian Novotny
Author URI: http://creative-software-design-solutions.com/
Text Domain: user-registration-aide

User Registration Aide Requires & Adds More Fields to User Registration & Profile - Forces new users to register additional fields
on registration form, and gives you the option to add additional fields at your discretion. Gives you more control over who registers,
and allows you to manage users easier!
Copyright (c) 2012 Brian Novotny

Users Registration Helper - Forces new users to register additional fields
Copyright (c) 2012 Brian Novotny
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/


include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
include_once ("user-reg-aide-admin.php");
include_once ("user-reg-aide-newFields.php");
include_once ("user-reg-aide-options.php");
include_once ("user-reg-aide-regForm.php");



//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/**
 * Creates Class CSDS_USER_REG_AIDE & Adds Actions and Hooks to register
 *
 * @since 1.2.0
 * @updated 1.2.8
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class CSDS_USER_REG_AIDE{

	public static $instance;
	protected $retrieve_password_for   = '';
	public    $during_user_creation    = false; // hack

	/**
	* Constructor
	*/

	public function __construct() {
		$this->CSDS_USER_REG_AIDE();
	}

		function CSDS_USER_REG_AIDE() { //constructor

			global $wp_version;
			self::$instance = $this;
			$this->plugin_dir = dirname(__FILE__);
			$this->plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
			$this->ref = explode('?',$_SERVER['REQUEST_URI']);
			$this->ref = $this->ref[0];

			// Filling existing WordPress profile fields into options db
			if( isset($_GET['page']) && $_GET['page'] == 'user-reg-aide-admin' ){
				add_action( 'init',  array(&$this, 'csds_userRegAide_fill_known_fields' ));
			}

			// Filling default User Registration Aide options db
			if(isset($_GET['action']) && $_GET['action'] == 'admin_init'){
				add_action( 'init', array(&$this, 'csds_userRegAide_DefaultOptions' ));
			}

			// Customize Registration & Login Forms
			add_action( 'login_head', array(&$this, 'csds_userRegAide_Password_Header'));
			add_filter('login_headerurl', array(&$this, 'csds_userRegAide_CustomLoginLink'));
			add_filter('login_headertitle', array(&$this, 'csds_userRegAide_Logo_Title_Color'));
			add_action( 'login_head', array(&$this, 'csds_userRegAide_Logo_Head'));

			// Handles new fields for registration form
			if(isset($_GET['action']) && $_GET['action'] == 'register'){
				add_action('register_form', array(&$this, 'csds_userRegAide_addFields'));
				//add_action('register_post','csds_userRegAide_checkFields', 10, 3); Keeps messing up registration form so leave out
				add_action('user_register', array(&$this, 'csds_userRegAide_updateFields'), 10, 1);
				add_filter( 'registration_errors',  array(&$this, 'csds_userRegAide_checkFields'), 10, 3 );
			}

			// Sets new password if user can enter own password on registration
			if(isset($_GET['action']) && $_GET['action'] == 'register'){
				add_filter('random_password',  array(&$this, 'csds_userRegAide_createNewPassword'));
			}
			// Attempts to remove password nag if user enters own password
			add_filter('get_user_option_default_password_nag', array(&$this, 'remove_default_password_nag'));

			// Administration Pages
			add_action('admin_menu', array(&$this, 'csds_userRegAide_optionsPage'));
			add_action('admin_menu', array(&$this, 'csds_userRegAide_editNewFields_optionsPage'));
			add_action('admin_menu', array(&$this, 'csds_userRegAide_regFormOptionsPage'));

			// Handles user profiles and extra fields
			add_action('show_user_profile', array(&$this, 'csds_show_user_profile'));
			add_action('edit_user_profile', array(&$this, 'csds_show_user_profile'));
			add_action('personal_options_update', array(&$this, 'csds_update_user_profile'));
			add_action('edit_user_profile_update', array(&$this, 'csds_update_user_profile'));
			add_action('profile_update', array(&$this, 'csds_update_user_profile'));

			// Translation File - Still in Works
			add_action('init', array(&$this, 'csds_userRegAide_translationFile'));

			// Filters for user input into extra and new fields
			add_filter('pre_user_first_name', 'esc_html');
			add_filter('pre_user_first_name', 'strip_tags');
			add_filter('pre_user_first_name', 'trim');
			add_filter('pre_user_first_name', 'wp_filter_kses');
			add_filter('pre_user_last_name', 'esc_html');
			add_filter('pre_user_last_name', 'strip_tags');
			add_filter('pre_user_last_name', 'trim');
			add_filter('pre_user_last_name', 'wp_filter_kses');
			add_filter('pre_user_nickname', 'esc_html');
			add_filter('pre_user_nickname', 'strip_tags');
			add_filter('pre_user_nickname', 'trim');
			add_filter('pre_user_nickname', 'wp_filter_kses');
			add_filter('pre_user_url', 'esc_url');
			add_filter('pre_user_url', 'strip_tags');
			add_filter('pre_user_url', 'trim');
			add_filter('pre_user_url', 'wp_filter_kses');
			add_filter('pre_user_description', 'esc_html');
			add_filter('pre_user_description', 'strip_tags');
			add_filter('pre_user_description', 'trim');
			add_filter('pre_user_description', 'wp_filter_kses');

			// Deactivation hook for deactivation of User Registration Aide Plugin
			register_deactivation_hook(__FILE__, array(&$this, 'csds_userRegAide_deactivation'));
		}

/**
 * Sets the text color and logo links & shadows in registration/login pages
 *
 * @since 1.2.0
 * @updated 1.2.4
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_Logo_Title_Color(){
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	$show_text_color = $csds_userRegAide_Options['show_login_text_color'];
	$text_color = $csds_userRegAide_Options['login_text_color'];
	$hover_color = $csds_userRegAide_Options['hover_text_color'];
	$show_shadow = $csds_userRegAide_Options['show_shadow'];
	$shadow_size = $csds_userRegAide_Options['shadow_size'];
	$shadow_color = $csds_userRegAide_Options['shadow_color'];

	if($show_text_color == "1" && $show_shadow == "2"){
		echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;}';
		echo '#registerform label{font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;} ';
		echo 'body.login #nav a  {color:'.$text_color.' !important; font-weight:bold; font-size:1.4em; margin-left:-9999; text-shadow: 0px 0px 0px #000000;}';
		echo '.login #backtoblog a { color:'.$text_color.' !important; font-weight:bold; font-size:1.5em; text-shadow: 0px 0px 0px #000000;}';
		echo '.login #nav a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.4em;}';
		echo '.login #backtoblog a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.5em;} </style>';

	}elseif($show_text_color == "1" && $show_shadow == "1"){
		echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;}';
		echo '#registerform label{font-family: verdana,arial; font-size:1.0em; color: '.$text_color.'; font-weight:bold;} ';
		echo 'body.login #nav a  {color:'.$text_color.' !important; font-weight:bold; font-size:1.4em; margin-left:-9999; text-shadow:'.$shadow_size.' '.$shadow_size.' '.$shadow_size.' '.$shadow_color.';}';
		echo '.login #backtoblog a { color:'.$text_color.' !important; font-weight:bold; font-size:1.5em; text-shadow:'.$shadow_size.' '.$shadow_size.' '.$shadow_size.' '.$shadow_color.';}';
		echo '.login #nav a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.4em;}';
		echo '.login #backtoblog a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.5em;} </style>';
	}

	if ($csds_userRegAide_Options['change_logo_link'] == "2"){
		return;
	}elseif($csds_userRegAide_Options['change_logo_link'] == "1"){
		return get_bloginfo('name');
	}
	//return get_bloginfo('name');
}

/**
 * Sets up head for custom login and registration pages
 *
 * @since 1.2.0
 * @updated 1.2.4
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_Logo_Head(){
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	$show_logo = $csds_userRegAide_Options['show_logo'];
	$logo_url = $csds_userRegAide_Options['logo_url'];
	$show_background_image = $csds_userRegAide_Options['show_background_image'];
	$background_image = $csds_userRegAide_Options['background_image_url'];
	$show_background_color = $csds_userRegAide_Options['show_background_color'];
	$background_color = $csds_userRegAide_Options['reg_background_color'];
	$show_page_image = $csds_userRegAide_Options['show_reg_form_page_image'];
	$page_image = $csds_userRegAide_Options['reg_form_page_image'];
	$show_page_color = $csds_userRegAide_Options['show_reg_form_page_color'];
	$page_color = $csds_userRegAide_Options['reg_form_page_color'];

	if ($show_logo == "1"){
		echo '<style type="text/css">h1 a { background-image:url('.$logo_url.') !important; height:150px; margin-bottom:20px; } </style>';
	}

	if($show_background_image =="1"){
		echo '<style type="text/css">#loginform{background:url('.$background_image.') no-repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
		echo '<style type="text/css">#registerform{background:url('.$background_image.') no-repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
	}

	if($show_background_color == "1"){
		 echo '<style type="text/css">#loginform{background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
		 echo '<style type="text/css">#registerform{background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';
	}

	if($show_page_image == "1"){
		echo '<style type="text/css">body.login{height:350%; background:url('.$page_image.') repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
		echo '<style type="text/css">body.register{background:url('.$page_image.') repeat center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
	}

	if($show_page_color == "1"){
		echo '<style type="text/css">body.login{height:350%; background-color:'.$page_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;} </style>';

	}

}

/**
 * Installs stuff for plugin
 *
 * @since 1.0.0
 * @updated 1.2.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_install(){

	if(function_exists('csds_userRegAide_fill_known_fields')){
		csds_userRegAide_fill_known_fields();
	}

	if(function_exists('csds_userRegAide_DefaultOptions')){
		csds_userRegAide_DefaultOptions();
	}

}

/**
 * Inserts password entered by user if option chosen to let users enter own password instead of using default random password emailing
 *
 * @since 1.2.0
 * @updated 1.2.5
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_createNewPassword($password){
	global $wpdb;

	if (!is_multisite()) {
		if (isset($_POST["pass1"]))
			$password = $_POST["pass1"];
	}
	else {
		if (!empty($_GET['key']))
			$key = $_GET['key'];
		else if (!empty($_POST['key']))
			$key = $_POST['key'];

		if (!empty($key)) {
			// seems useless since this code cannot be reached with a bad key anyway you never know
			$key = $wpdb->escape($key);

			$sql = "SELECT active, meta FROM ".$wpdb->signups." WHERE activation_key='".$key."'";
			$data = $wpdb->get_results($sql);

			// Getting Data from new user signup
			if (isset($data[0])) {
				// if not already active
				if (!$data[0]->active) {
					$meta = unserialize($data[0]->meta);

					if (!empty($meta["pass1"])) {
						$password = $meta["pass1"];
					}
				}
			}
		}
	}

	return $password;
}

/**
 * Adds the translation directory to the plugin folder
 *
 * @since 1.1.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_translationFile(){

	$plugin_path = plugin_basename( dirname(__FILE__).'/translations' );
	load_plugin_textdomain('user-registration-aide', '', $plugin_path );
}

/**
 * Add the management page to the user settings bar
 *
 * @since 1.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


function csds_userRegAide_optionsPage(){

	if(function_exists('add_menu_page')){

		add_menu_page('User Registration Aide','User Registration Aide', 'manage_options','user-registration-aide', 'csds_userRegAide_myOptionsSubpanel', '', 71 );

	}

}

/**
 * Add options page for the Registration Form options
 *
 * @since 1.2.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


function csds_userRegAide_regFormOptionsPage(){

	if(function_exists('add_submenu_page')){

		add_submenu_page('user-registration-aide','Registration Form Options', 'Registration Form Options', 'manage_options', 'registration-form-options', 'csds_userRegAide_regFormOptions' );

	}

}


/**
 * Add the Add-Edit New Fields management page to the user settings bar
 *
 * @since 1.1.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


function csds_userRegAide_editNewFields_optionsPage(){

	if(function_exists('add_submenu_page')){

		add_submenu_page('user-registration-aide','Edit New Fields', 'Edit New Fields', 'manage_options', 'edit-new-fields', 'csds_userRegAide_editNewFields' );

	}

}

/**
 * Sets Custom Logo link to site url if option chosen to add custom logo
 *
 * @since 1.2.0
 * @updated 1.2.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_CustomLoginLink(){

	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	if ($csds_userRegAide_Options['change_logo_link'] == "2"){
		return 'http://www.wordpress.org';
	}
	if ($csds_userRegAide_Options['change_logo_link'] == "1"){
		return site_url();
	}
}

/**
 * Add fields to the new user registration page that the user must fill out when they register
 *
 * @since 1.0.0
 * @updated 1.2.7
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_addFields(){

	global $csds_userRegAideFields, $csds_userRegAide_registrationFields, $csds_userRegAide_Options;

	?>

	<style>
	#pass-strength-result{
	padding-top: 3px;
	padding-right: 5px;
	padding-bottom: 3px;
	padding-left: 5px;
	margin-top: 3px;
	text-align: center;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
	display:block;
	}

	</style>

	<?php

	$fieldKey = '';
	$fieldName = '';
	$csds_userRegAide_registrationFields = array();
	$csds_userRegAide_Options = array();
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
	$csds_userRegAideFields = get_option('csds_userRegAideFields');
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');

	if(is_array($csds_userRegAide_registrationFields)){
		if(!empty($csds_userRegAide_registrationFields)){
			foreach($csds_userRegAide_registrationFields as $fieldKey => $fieldName){
				if($fieldKey != 'user_pass'){
					echo '<p>';?>
					<label><?php _e($csds_userRegAide_registrationFields[$fieldKey], 'csds_userRegAide') ?><br />
					<input type="text" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" class="input" value="" size="25" tabindex="20" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;" /></label>
				<?php
					echo '</p>';
				}else{
					//echo '<p>';?>
					<div style="clear:both"><label><?php _e('Password:', 'csds_userRegAide');?> <p>
					<p>
					<input autocomplete="off" name="pass1" id="pass1" size="25" value="" type="password" tabindex="20" /></p></label></div>
					<div> <label><?php _e('Confirm Password:', 'csds_userRegAide');?> <p>
					<input autocomplete="off" name="pass2" id="pass2" size="25" value="" type="password" tabindex="20" /></p></label></div>
					<p>
					<div> <span id="pass-strength-result"><?php echo 'short'; ?></span>
					<small><?php _e('Hint: Use upper and lower case characters, numbers and symbols like !"?$%^&amp;( in your password.', 'csds_userRegAide'); ?> </small></div>
					</p>
					<br/>
					<?php


				}
			}
		}
	}
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	if($csds_userRegAide_Options['show_custom_agreement_message'] == "1"){
		echo $csds_userRegAide_Options['agreement_message'];
		echo '<br/>';
	}
	if($csds_userRegAide_Options['show_custom_agreement_checkbox'] == "1"){
		echo '<br/>';
		 _e('I Agree with the Terms and Conditions: ', 'csds_userRegAide');
		 echo '<br/>';
		 echo '<br/>';
		 ?><input type="radio" id="csds_userRegAide_agree" name="csds_userRegAide_agree" value="1" <?php
			if ($csds_userRegAide_Options['new_user_agree'] == 1) echo 'checked' ; ?> /> I Agree
							<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_agree"  value="2" <?php
			if ($csds_userRegAide_Options['new_user_agree'] == 2) echo 'checked' ; ?> /> I Do Not Agree
			<?php
			echo '<br/>';
	}
	if($csds_userRegAide_Options['show_custom_agreement_link'] == "1"){
		echo '<br/>';
		echo '<a href="'.$csds_userRegAide_Options['agreement_link'].'" target="_blank">'.$csds_userRegAide_Options['agreement_title'].'</a>';
	}

	wp_nonce_field('userRegAideRegForm_Nonce', 'userRegAide_RegFormNonce');

	$csds_userRegAide_registrationFields = array();
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$csds_userRegAide_Options = array();
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');

	if(is_array($csds_userRegAide_registrationFields)){
		if ( in_array("Password", $csds_userRegAide_registrationFields )){
			if($csds_userRegAide_Options['select_pass_message'] == 1){
				echo '<div style="margin:10px 0;border:1px solid #e5e5e5;padding:10px">';
				echo '<p class="message register" style="margin:5px 0;">';
				echo $csds_userRegAide_Options['registration_form_message'];
				echo '</p>';
				echo '</div>';
				?>
			<style>
			#reg_passmail{
				display:none;
			}
			</style>
			<?php
			}
		}
	}
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');

	if($csds_userRegAide_Options['show_support'] == "1"){
			echo '<a target="_blank" href="'.$csds_userRegAide_Options['support_display_link'].'">' . $csds_userRegAide_Options['support_display_name'] . '</a><br/>';
			echo '<br/>';
	}
}



function csds_userRegAide_Password_Header(){

	if(function_exists('csds_userRegAide_Password_Options')){
			csds_userRegAide_Password_Options();
	}

	$csds_userRegAide_registrationFields = array();
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$word = "Password";
	$user_login = '';
	$user_email = '';
	$csds_pwd_options = get_option( 'csds_pwd_options' );

		if( isset( $_GET['user_login'] ) ) $user_login = $_GET['user_login'];
		if( isset( $_GET['user_email'] ) ) $user_email = $_GET['user_email'];

		?>
		<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.js?ver=1.7.1'></script>
		<!--<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-admin/js/common.js?ver=20080318'></script>-->
		<?php
		if(!empty($csds_userRegAide_registrationFields)){
			if(is_array($csds_userRegAide_registrationFields)){
				if (in_array("$word", $csds_userRegAide_registrationFields)){
?>

<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-includes/js/jquery/jquery.color.js?ver=2.0-4561'></script>
<script type='text/javascript'>
/* <![CDATA[ */
	pwsL10n = {
		short: "No Password Entered!",
		bad: "Password Entered is Bad, Too Weak",
		good: "Password Entered is fairly tough and is good to accept",
		strong: "Password Entered is very strong!",
		mismatch: "Password Entered does not match Password Confirm! Try Again Please!"
	}
/* ]]> */
</script>
<script type='text/javascript' src='<?php trailingslashit(get_option('siteurl'));?>wp-admin/js/password-strength-meter.dev.js?ver=20070405'></script>
<script type="text/javascript">
	function check_pass_strength ( ) {

		var pass = jQuery('#pass1').val();
		var pass2 = jQuery('#pass2').val();
		var user = jQuery('#user_login').val();

		// get the result as an object, i'm tired of typing it
		var res = jQuery('#pass-strength-result');

		var strength = passwordStrength(pass, user, pass2);

		jQuery(res).removeClass('empty short bad good strong mismatch');

		 if ( strength == 1 ) {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}
		else if ( strength == 2 ) {
			jQuery(res).addClass('bad');
			jQuery(res).html( pwsL10n.bad );
		}
		else if ( strength == 3 ) {
			jQuery(res).addClass('good');
			jQuery(res).html( pwsL10n.good );
		}
		else if ( strength == 4 ) {
			jQuery(res).addClass('strong');
			jQuery(res).html( pwsL10n.strong );
		}
		else if ( strength == 5 ) {
			jQuery(res).addClass('mismatch');
			jQuery(res).html( pwsL10n.mismatch );
		}
		else {
			// this catches 'Too short' and the off chance anything else comes along
			jQuery(res).addClass('short');
			jQuery(res).html( pwsL10n.short );
		}

	}


	jQuery(function($) {
		$('#pass1').keyup( check_pass_strength );
		$('#pass2').keyup( check_pass_strength )
		$('.color-palette').click(function(){$(this).siblings('input[name=admin_color]').attr('checked', 'checked')});
	} );

	jQuery(document).ready( function() {
		jQuery('#pass1,#pass2').attr('autocomplete','off');
		jQuery('#user_login').val('<?php echo $user_login; ?>');
		jQuery('#user_email').val('<?php echo $user_email; ?>');
    });
</script>
		<?php }
		}
	}
}

/**
 * Add the additional metadata into the database after the new user is created
 *
 * @since 1.0.0
 * @updated 1.2.7
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_updateFields($user_id){

	global $csds_userRegAide_registrationFields, $wpdb, $table_prefix;

	$thisValue = '';
	$fieldName = '';
	$newValue = '';
	$newPass = '';
	$addData = '';
	$newWebsite = '';
	$newCredentials = '';
	$csds_userRegAide_registrationFields = array();
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$csds_userRegAide_Options = array();
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');

	if(!empty($csds_userRegAide_registrationFields)){
		if (wp_verify_nonce($_POST['userRegAide_RegFormNonce'], 'userRegAideRegForm_Nonce')){
			foreach($csds_userRegAide_registrationFields as $thisValue => $fieldName){
				if($_POST[$thisValue] != ''){
					if($thisValue == "first_name"){
						$newValue = apply_filters('pre_user_first_name', $_POST[$thisValue]);
					}elseif($thisValue == "last_name"){
						$newValue = apply_filters('pre_user_last_name', $_POST[$thisValue]);
					}elseif($thisValue == "nickname"){
						$newValue = apply_filters('pre_user_nickname', $_POST[$thisValue]);
					}elseif($thisValue == "description"){
						$newValue = apply_filters('pre_user_description', $_POST[$thisValue]);
					}elseif($thisValue == "user_url"){
						$newWebsite = apply_filters('pre_user_url', $_POST[$thisValue]);
						$addData = $wpdb->prepare("UPDATE $wpdb->users SET user_url =('$newWebsite') WHERE ID = '$user_id'");
						$wpdb->query($addData);
					}elseif($thisValue == "user_pass"){
						$newPass = $_POST['pass1'];
						csds_new_user_notification($user_id, $newPass);
						add_action('phpmailer_init', array(&$this, 'phpmailer_init'), 99999);
						$addData = $wpdb->prepare("UPDATE $wpdb->users SET user_pass = md5('$newPass') WHERE ID = $user_id");
						$wpdb->query($addData);
					}else{
						$newValue = apply_filters('pre_user_description', $_POST[$thisValue]);
					}
					if($thisValue != 'user_url' || $thisValue != "user_pass"){
						update_user_meta( $user_id, $thisValue, $newValue);
					}
					}else{
						//exit('Empty NOOO Fucking shit');
						// Havent figured out what to do here yet as nothing really need to be done
					}
					if($csds_userRegAide_Options['show_custom_agreement_checkbox'] == 1){
						if($_POST['csds_userRegAide_agree'] == 1){
							update_user_meta( $user_id, new_user_agreed, "Yes");
						}
					}
				}
			}else{
			exit(__('Failed Security Verification', 'csds_userRegAide'));
		}
	}
}

// Remove default password message if user entered own password

function remove_default_password_nag() {

		return 0;

}

// Emails registration confirmation to new user

function csds_new_user_notification($user_id, $plaintext_pass = '') {

	$user = new WP_User($user_id);

    $user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option

	    // we want to reverse this for the plain text arena of emails.

	    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	    $message  = sprintf(__('A new user has registered on your site %s:'), $blogname) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	    $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

	    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration Alert'), $blogname), $message);

	    if ( empty($plaintext_pass) ){
	        return;
		}

		$message  = sprintf(__('Thank you for registering with: %s'), $blogname) . "\r\n";
	    $message  .= sprintf(__('Username: %s'), $user_login) . "\r\n";
	    $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
	    $message .= wp_login_url() . "\r\n";

	    wp_mail($user_email, sprintf(__('[%s] Your username and password'), $blogname), $message);

	 return;
}

/**
 * Check the new user registration form for errors
 *
 * @since 1.0.0
 * @updated 1.2.7
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_checkFields($errors, $username, $email){
//function csds_userRegAide_checkFields($login, $email, $errors){

	global $csds_userRegAide_registrationFields;
	$thisValue = '';
	$fieldName1 = '';
	$csds_userRegAide_registrationFields = array();
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$csds_userRegAide_Options = array();
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	//$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
	if(!empty($csds_userRegAide_registrationFields)){
		foreach($csds_userRegAide_registrationFields as $thisValue => $fieldName1){
			if($thisValue != "user_pass"){
				if ($_POST[$thisValue] == '') {
					$errors->add('empty_'.$thisValue , __("<strong>ERROR</strong>: Please type your ".$csds_userRegAide_registrationFields[$thisValue].".",'csds_userRegAide'));
				}
			}elseif($thisValue == "user_pass"){
				// //to check password
				if(empty($_POST['pass1']) || $_POST['pass1'] == '' || empty($_POST['pass2']) || $_POST['pass2'] == ''){
						$errors->add('empty_password', __("<strong>ERROR</strong>: Please type your Password!", 'csds_userRegAide'));

				}elseif($_POST['pass1'] != $_POST['pass2']){
						$errors->add('password_mismatch', ___("<strong>ERROR</strong>: Password too short!", 'csds_userRegAide'));

				}else{
					//exit('Blow Up');//$_POST['user_pw'] = $_POST['pass1'];
				}
			}
		}
	}

	if($csds_userRegAide_Options['show_custom_agreement_checkbox'] == 1){
		if($_POST['csds_userRegAide_agree'] == 2){
			$errors->add('agreement_confirmation', __("<strong>ERROR</strong>: You must agree to the terms and conditions!", 'csds_userRegAide'));
		}elseif(empty($_POST['csds_userRegAide_agree'])){
			$errors->add('agreement_confirmation', __("<strong>ERROR</strong>: You must agree to the terms and conditions!", 'csds_userRegAide'));
		}
	}
	return $errors;
}

/**
 * Add the additional fields added to the user profile page
 *
 * @since 1.0.0
 * @updated 1.2.4
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_show_user_profile($user){

 global $csds_userRegAide_NewFields, $current_user;

	$user_id = $user->ID;
	$fieldKey = '';
	$fieldName = '';
	$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	echo '<h3>User Registration Aide Additional Fields</h3>';
	$current_user = wp_get_current_user();
		if(current_user_can('edit_user', $user_id)){
			if(!empty($csds_userRegAide_NewFields)){
				foreach($csds_userRegAide_NewFields as $fieldKey => $fieldName){

					?>
					<table class="form-table">
					<tr>
					<th><label for="<?php echo $fieldKey ?>"><?php echo $fieldName ?></label></th>
					<td><input type="text" name="<?php echo $fieldKey ?>" id="<?php echo $fieldKey ?>" value="<?php echo esc_attr(get_user_meta($user_id, $fieldKey, TRUE)) ?>" class="regular-text" /></td>
					</tr>
					<?php

				}
					echo '</table>';

			wp_nonce_field('userRegAideProfileForm', 'userRegAideProfileNonce');

			echo '<br/>';
				if($csds_userRegAide_Options['show_support'] == "1"){
					echo '<a target="_blank" href="'.$csds_userRegAide_Options['support_display_link'].'">' . $csds_userRegAide_Options['support_display_name'] . '</a>';
					echo '<br/>';
				}
			}
		}else{
			exit(__('Naughty, Naughty! You do not havve permissions to do this!', 'csds_userRegAide'));
		}
}

 /**
 * Updates the additional fields data added to the user profile page
 * @since 1.0.0
 * @updated  1.2.4
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

 function csds_update_user_profile($user_id){

	global $wpdb, $current_user;
	global $current_user, $csds_userRegAide_NewFields;
	$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
	$userID = $user_id;
	$fieldKey = '';
	$fieldName = '';
	$newValue = '';

	if(!empty($csds_userRegAide_NewFields)){
		$current_user = wp_get_current_user();
		if(current_user_can('edit_user', $userID)){
			if(wp_verify_nonce($_POST["userRegAideProfileNonce"], 'userRegAideProfileForm')){
				foreach($csds_userRegAide_NewFields as $fieldKey => $fieldName){
					$newValue = $_POST[$fieldKey];
					if(!empty($newValue)){
					update_user_meta($userID, $fieldKey, $_POST[$fieldKey]);
					}
					else{
						//exit(__('New Value empty!'));
					}
				}
			}else{
				exit(__('Failed Security Check', 'csds_userRegAide'));
			}
		}else{
			exit(__('You do not have sufficient permissions to edit this user, contact a network administrator if this is an error!', 'csds_userRegAide'));
		}
	}else{
		//exit(__('New Fields Empty'));
	}
}

/**
 * Adds all the additional fields created to existing users meta
 *
 * @since 1.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_add_field_to_users_meta($field){

	global $wpdb;
	//include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$count = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users;");
	$i = 1;
		while($i <= $count)
			{
				$user_id = $i;
				update_user_meta( $user_id, $field, "");
				$i++;
			}
}

/**
 * Deletes the additional fields from existing users meta
 *
 * @since 1.1.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_delete_field_from_users_meta($field){

	global $wpdb;
	//include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$count = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users;");
	$i = 1;
		while($i <= $count)
			{
				$user_id = $i;
				delete_user_meta( $user_id, $field, "");
				$i++;
			}
}

/**
 * Deactivation Function
 *
 * @since 1.0.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_deactivation()
{
	// Do nothing here in case user wants to reactivate at a later time
}

} //end CSDS_USER_REG_AIDE class

# Run The Plugin!
if( class_exists('CSDS_USER_REG_AIDE') ){
	$csds_userRegAide_Instance = new CSDS_USER_REG_AIDE();
	if(isset($csds_userRegAide_Instance)){
		// if(function_exists('csds_userRegAide_install')){
			register_activation_hook( __FILE__, array(  &$csds_userRegAide_Instance, 'csds_userRegAide_install' ) );
		// }
		// if(function_exists('csds_userRegAide_DefaultOptions')){
			// register_activation_hook( __FILE__, array(  &$csds_userRegAide_Instance, 'csds_userRegAide_DefaultOptions' ) );
		// }
		//register_deactivation_hook( __FILE__, array(  &$csds_userRegAide_Instance, 'Uninstall' ) );
	}
}
?>