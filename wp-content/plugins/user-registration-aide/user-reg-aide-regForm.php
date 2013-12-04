<?php

/*
 * User Registration Aide - Registration Form Options
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.2.8
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

/**
 * Couple of includes for functionality
 *
 * @since 1.2.0
 * @updated 1.2.8
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
include_once ("user-registration-aide.php");
include_once ("user-reg-aide-options.php");
include_once ("user-reg-aide-newFields.php");
include_once ("user-reg-aide-admin.php");

/**
 * Loads and displays the User Registration Aide administration page
 *
 * @since 1.2.0
 * @updated 1.2.8
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

if(!function_exists('csds_userRegAide_regFormOptions')){
function csds_userRegAide_regFormOptions(){

	global $csds_userRegAide_knownFields, $csds_userRegAide_Options, $csds_userRegAide_registrationFields, $csds_userRegAide_NewFields, $csds_userRegAide_fieldOrder, $current_user;

	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	if($csds_userRegAide_Options['csds_userRegAide_db_Version'] != "1.2.8"){
		if(function_exists('csds_userRegAide_updateOptions')){
			csds_userRegAide_updateOptions();
		}
	}

	if(empty($csds_userRegAide_registrationFields)){
		if(function_exists('csds_userRegAide_updateRegistrationFields')){
			csds_userRegAide_updateRegistrationFields();
		}
	}

	if (isset($_POST['reg_form_message_update'])){

		// add code to handle new registration form message
		$update = array();
		$update = get_option('csds_userRegAide_Options');
		$update['select_pass_message'] = $_POST['csds_select_RegFormMessage'];
		$update['registration_form_message'] = $_POST['csds_RegForm_Message'];
		$update['show_custom_agreement_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_agreement_link']));
		$update['agreement_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_newAgreementURL']));
		$update['agreement_title'] = esc_attr(stripslashes($_POST['csds_userRegAide_newAgreementTitle']));
		update_option("csds_userRegAide_Options", $update);
		echo '<div id="message" class="updated fade"><p>'. __('New Registration Form Message Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully

		// Updates registration form agreement message options
	}elseif (isset($_POST['reg_form_agreement_message_update'])){
		$update = array();
		$update = get_option('csds_userRegAide_Options');
		$update['show_custom_agreement_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_agreement_link']));
		$update['agreement_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_newAgreementURL']));
		$update['agreement_title'] = esc_attr(stripslashes($_POST['csds_userRegAide_newAgreementTitle']));
		$update['show_custom_agreement_message'] = esc_attr(stripslashes($_POST['csds_userRegAide_show_agreement_message']));
		$update['show_custom_agreement_checkbox'] = esc_attr(stripslashes($_POST['csds_userRegAide_agreement_checkbox']));
		$update['agreement_message'] = esc_attr(stripslashes($_POST['csds_RegForm_Agreement_Message']));
		update_option("csds_userRegAide_Options", $update);
		echo '<div id="message" class="updated fade"><p>'. __('New Registration Form Agreement Message Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully

		//updates login/registration form custom options

	}elseif (isset($_POST['csds_userRegAide_logo_update'])){
		$update = array();
		$update = get_option('csds_userRegAide_Options');
		$update['show_logo'] = esc_attr(stripslashes($_POST['csds_userRegAide_logo']));
		$update['logo_url'] = esc_attr(stripslashes($_POST['csds_userRegAide_newLogoURL']));
		$update['show_background_image'] = esc_attr(stripslashes($_POST['csds_userRegAide_background_image']));
		$update['background_image_url'] = esc_attr(stripslashes($_POST['csds_userRegAide_newBackgroundImageURL']));
		$update['show_background_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_background_color']));
		$update['reg_background_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newBackgroundColor']));
		$update['change_logo_link'] = esc_attr(stripslashes($_POST['csds_userRegAide_change_logo_link']));
		$update['show_reg_form_page_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_page_background_color']));
		$update['reg_form_page_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newPageBackgroundColor']));
		$update['show_reg_form_page_image'] = esc_attr(stripslashes($_POST['csds_userRegAide_page_background_image']));
		$update['reg_form_page_image'] = esc_attr(stripslashes($_POST['csds_userRegAide_newPageBackgroundImage']));
		$update['show_login_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_text_color']));
		$update['login_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newTextColor']));
		$update['hover_text_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_newHoverTextColor']));
		$update['show_shadow'] = esc_attr(stripslashes($_POST['csds_userRegAide_show_shadow']));
		$update['shadow_size'] = esc_attr(stripslashes($_POST['csds_userRegAide_shadowSize']));
		$update['shadow_color'] = esc_attr(stripslashes($_POST['csds_userRegAide_shadowColor']));
		update_option("csds_userRegAide_Options", $update);
		echo '<div id="message" class="updated fade"><p>'. __('New Registration Form Logo Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully

	}

	// Loading options prior to loading registration form options page

	$csds_userRegAide_getOptions = get_option('csds_userRegAide');
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
	$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
	$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');

	$cnt1 = count($csds_userRegAide_knownFields);
	if($cnt1 < 8 ){
		if(function_exists('csds_userRegAide_fill_known_fields')){
			csds_userRegAide_fill_known_fields();
		}
	}

	// Just checking to make sure options are installed before loading admin page
	if(empty($csds_userRegAide_knownFields)){
		if(function_exists('csds_userRegAide_fill_known_fields')){
			csds_userRegAide_fill_known_fields();
		}
	}
	if(empty($csds_userRegAide_Options)){
		if(function_exists('csds_userRegAide_DefaultOptions')){
			csds_userRegAide_DefaultOptions();
		}
	}

	// Shows Aministration Page
	$current_user = wp_get_current_user();
	if(current_user_can('manage_options')){
	echo '<div id="wpbody">';
		echo '<div class=wrap>';
			echo '<form method="post" name="csds_userRegAide">';
				echo '<h2>'. __('User Registration Aide: Custom Registration Form Options', 'csds_userRegAide') .'</h2>';
				echo '<div id="poststuff">';

				//Form for adding different message to registration form

			echo '<div class="stuffbox">';
					echo '<h3>'.__('Add Your Own Message to Bottom of Registration Form', 'csds_userRegAide');
					echo '</h3>';
					echo '<div class="inside">';
						echo '<table border="5" cellpadding="5" cellspacing="5">';
						echo '<tr>';
							echo '<td>'. __('Choose to add a special message to bottom of registration form if new users can enter own password:', 'csds_userRegAide').'</td>';
							?><td><input type="radio" name="csds_select_RegFormMessage" id="csds_select_RegFormMessage" value="1" <?php
								if ($csds_userRegAide_Options['select_pass_message'] == 1) echo 'checked' ;?>/> Yes
								<input type="radio" name="csds_select_RegFormMessage" id="csds_select_RegFormMessage" value="2" <?php
								if ($csds_userRegAide_Options['select_pass_message'] == 2) echo 'checked' ;?>/> No </td>

							<td><?php _e('Enter new Registration form message below: ', 'csds_userRegAide');?></td></tr>
						<tr>
							<td colspan="3"><textarea name="csds_RegForm_Message" id="csds_RegForm_Message" cols="180" rows="1"
							wrap="hard" title="<?php _e('Enter a custom message here for bottom of registration form if users can create their own password!', 'csds_userRegAide');?>"><?php _e($csds_userRegAide_Options['registration_form_message'],'csds_userRegAide')?></textarea>
							</td>
						</tr>
						</table>
						<div class="submit"><input type="submit" class="button-primary" name="reg_form_message_update" value="<?php _e('Update Registration Form Message Options', 'csds_userRegAide'); ?>"  /></div>
					</div>
				</div>

			<?php 	// Form for adding additional agreement to signup for website
				echo '<div class="stuffbox">';
					?><h3><?php _e('Add Your Own Agreement Message and Policy Link with Confirmation of Agreement to Bottom of Registration Form', 'csds_userRegAide');?></h3>
						<div class="inside">
						<table border="5" cellpadding="5" cellspacing="5">
						<tr>
							<td colspan="3"><?php _e('Choose to add a special message to bottom of registration form for new users requiring them to read and agree to terms and conditions of the website:', 'csds_userRegAide'); ?></td>
						</tr>
						<tr>
							<td width="25%"><?php _e('Show Custom Link for Agreement/Guidelines/Policy Page: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_agreement_link" name="csds_userRegAide_agreement_link" value="1" <?php
								if ($csds_userRegAide_Options['show_custom_agreement_link'] == 1) echo 'checked' ;?>/> Yes
								<input type="radio" id="csds_userRegAide_agreement_link" name="csds_userRegAide_agreement_link"  value="2" <?php
								if ($csds_userRegAide_Options['show_custom_agreement_link'] == 2) echo 'checked' ;?>/> No
							</td><?php
							echo '<td width="25%">'. __('Enter a title to display for link to Agreement/Guidelines/Policies URL: ', 'csds_userRegAide').'<input  style="width: 200px;" type="text" title="Enter the title for the URL where your agreement/guidelines/policy page is located (http://mysite.com/agreement.php)" value="' . $csds_userRegAide_Options['agreement_title'] . '" name="csds_userRegAide_newAgreementTitle" id="csds_userRegAide_newAgreementTitle" /></td>';
							echo '<td width="50%">'. __('Enter Link to Agreement/Guidelines/Policies URL: ', 'csds_userRegAide') .'<input  style="width: 450px;" type="text" title="Enter the URL where your agreement/guidelines/policy page is located (http://mysite.com/agreement.php)" value="' . $csds_userRegAide_Options['agreement_link'] . '" name="csds_userRegAide_newAgreementURL" id="csds_userRegAide_newAgreementURL" /></td>';?></tr>
						<tr>
							<td width="25%"><?php _e('Show Message Confirming Agreement for Agreement/Guidelines/Policy Page: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_show_agreement_message" name="csds_userRegAide_show_agreement_message" value="1" <?php
								if ($csds_userRegAide_Options['show_custom_agreement_message'] == 1) echo 'checked' ;?>/> Yes
								<input type="radio" id="csds_userRegAide_show_agreement_message" name="csds_userRegAide_show_agreement_message"  value="2" <?php
								if ($csds_userRegAide_Options['show_custom_agreement_message'] == 2) echo 'checked' ;?>/> No
							</td>
							<td width="25%"><?php _e('Show Checkbox Confirming Agreement for Agreement/Guidelines/Policy Page: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_agreement_checkbox" name="csds_userRegAide_agreement_checkbox" value="1" <?php
								if ($csds_userRegAide_Options['show_custom_agreement_checkbox'] == 1) echo 'checked' ;?>/> Yes
								<input type="radio" id="csds_userRegAide_agreement_checkbox" name="csds_userRegAide_agreement_checkbox"  value="2" <?php
								if ($csds_userRegAide_Options['show_custom_agreement_checkbox'] == 2) echo 'checked' ;?>/> No
							</td>
							<td><?php _e('Add your special message below to add to bottom of registration form if new users must agree to terms or policies:', 'csds_userRegAide');?></td>
						<tr>
							<td colspan="3"><textarea name="csds_RegForm_Agreement_Message" id="csds_RegForm_Agreement_Message" cols="180" rows="1"
							wrap="hard" title="<?php _e('Enter a custom message here for bottom of registration form if users can create their own password!', 'csds_userRegAide');?>"><?php _e($csds_userRegAide_Options['agreement_message'],'csds_userRegAide')?></textarea>
							</td>
						</tr></table>
							<div class="submit"><input type="submit" class="button-primary" name="reg_form_agreement_message_update" value="<?php _e('Update Registration Form Agreement Options', 'csds_userRegAide');?>" /></div>
						</div>
					</div><?php
					// Form area for adding custom logo

					echo '<div class="stuffbox">';?><h3><?php _e('Add Your Own Logo', 'csds_userRegAide');?></h3><?php
						echo '<div class="inside">';
						echo '<br/>';
						echo '<table border="5" cellpadding="5" cellspacing="5">';
						echo '<tr>';
						// Custom Logo
						?>
						<td width="25%"><?php _e('Show Custom Logo: ', 'csds_userRegAide'); ?><input type="radio" id="csds_userRegAide_logo" name="csds_userRegAide_logo" value="1"
						<?php if ($csds_userRegAide_Options['show_logo'] == 1) echo 'checked' ;?>/> Yes
						<input type="radio" id="csds_userRegAide_logo" name="csds_userRegAide_logo"  value="2" <?php
						if ($csds_userRegAide_Options['show_logo'] == 2) echo 'checked' ;?>/> No
						</td>
						<td width="25%">
						<?php _e('Change Custom Logo Link: ', 'csds_userRegAide');?><input type="radio" id="csds_userRegAide_change_logo_link" name="csds_userRegAide_change_logo_link" value="1" <?php
						if ($csds_userRegAide_Options['change_logo_link'] == 1) echo 'checked' ;?>/> Yes
						<input type="radio" id="csds_userRegAide_change_logo_link" name="csds_userRegAide_change_logo_link"  value="2" <?php
						if ($csds_userRegAide_Options['change_logo_link'] == 2) echo 'checked' ;?><?php echo'/> No';
						echo '</td>';
						echo '<td width="50%">'. __('New Logo URL: ', 'csds_userRegAide') .'<input  style="width: 450px;" type="text" title="Enter the URL where your new logo is for your register/login page -- (http://mysite.com/wp-content/uploads/9/5/thislogo.png)" value="' . $csds_userRegAide_Options['logo_url'] . '" name="csds_userRegAide_newLogoURL" id="csds_userRegAide_newLogoURL" /></td>';
						echo '</tr>';

						// Form Background Image

						echo '<tr>';
						echo '<td>'.__('Show Custom Background Image: ', 'csds_userRegAide');
						echo '<br/>';?>
						<input type="radio" id="csds_userRegAide_background_image" name="csds_userRegAide_background_image" value="1" <?php
						if ($csds_userRegAide_Options['show_background_image'] == 1) echo 'checked' ; ?><?php echo '/> Yes';
						?><input type="radio" id="csds_userRegAide_background_image" name="csds_userRegAide_background_image" value="2"
						<?php if ($csds_userRegAide_Options['show_background_image'] == 2) echo 'checked' ; ?><?php echo '/> No';
						echo '</td>';
						echo '<td colspan="2">'. __('New Background Image URL: ', 'csds_userRegAide') .'<input  style="width: 450px;" type="text" title="Enter the URL where your new background image is for your login/register forms --  (http://mysite.com/wp-content/uploads/9/5/this-background-image.png)" value="' . $csds_userRegAide_Options['background_image_url'] . '" name="csds_userRegAide_newBackgroundImageURL" id="csds_userRegAide_newBackgroundImageURL" /></td>';
						echo '</tr>';

						// Page Background Image

						echo '<tr>';
						echo '<td>'.__('Show Custom Page Background Image: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_page_background_image" name="csds_userRegAide_page_background_image" value="1"
						<?php if ($csds_userRegAide_Options['show_reg_form_page_image'] == 1) echo 'checked';?>/> Yes
						<input type="radio" id="csds_userRegAide_page_background_image" name="csds_userRegAide_page_background_image" value="2"
						<?php if ($csds_userRegAide_Options['show_reg_form_page_image'] == 2) echo 'checked' ;?>/> No
						</td><?php echo '<td colspan="2">'. __('New Page Background Image: ', 'csds_userRegAide') .'<input  style="width: 150px;" type="text" title="Enter the new page background image url for your register/login pages (http://mysite.com/content/uploads/myimage.png)" value="' . $csds_userRegAide_Options['reg_form_page_image'] . '" name="csds_userRegAide_newPageBackgroundImage" id="csds_userRegAide_newPageBackgroundImage" /></td></tr>';

						// Form Background Color

						echo '<tr>';
						echo '<td>'.__('Show Custom Background Color: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_background_color" name="csds_userRegAide_background_color" value="1"
						<?php if ($csds_userRegAide_Options['show_background_color'] == 1) echo 'checked';?>/> Yes
						<input type="radio" id="csds_userRegAide_background_color" name="csds_userRegAide_background_color" value="2"
						<?php if ($csds_userRegAide_Options['show_background_color'] == 2) echo 'checked' ;?>/> No
						</td><?php echo '<td colspan="2">'. __('New Background Color: ', 'csds_userRegAide') .'<input  style="width: 150px;" type="text" title="Enter the new background color for your login/register form (#FFFFFF)" value="' . $csds_userRegAide_Options['reg_background_color'] . '" name="csds_userRegAide_newBackgroundColor" id="csds_userRegAide_newBackgroundColor" />';
						echo '</td>';
						echo '</tr>';

							// Page Background Color

						echo '<tr>';
						echo '<td>'.__('Show Custom Page Background Color: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_page_background_color" name="csds_userRegAide_page_background_color" value="1"
						<?php if ($csds_userRegAide_Options['show_reg_form_page_color'] == 1) echo 'checked';?>/> Yes
						<input type="radio" id="csds_userRegAide_page_background_color" name="csds_userRegAide_page_background_color" value="2"
						<?php if ($csds_userRegAide_Options['show_reg_form_page_color'] == 2) echo 'checked' ;?>/> No
						</td><?php echo '<td colspan="2">'. __('New Page Background Color: ', 'csds_userRegAide') .'<input  style="width: 150px;" type="text" title="Enter the new page background color for your register/login form (#FFFFFF)" value="' . $csds_userRegAide_Options['reg_form_page_color'] . '" name="csds_userRegAide_newPageBackgroundColor" id="csds_userRegAide_newPageBackgroundColor" /></td></tr>';

							// Text label and link colors
						echo '<tr>';
						echo '<td>'.__('Show Custom Text/Links Colors: ', 'csds_userRegAide');?><br/><input type="radio" id="csds_userRegAide_text_color" name="csds_userRegAide_text_color" value="1"
						<?php if ($csds_userRegAide_Options['show_login_text_color'] == 1) echo 'checked';?>/> Yes
						<input type="radio" id="csds_userRegAide_text_color" name="csds_userRegAide_text_color" value="2"
						<?php if ($csds_userRegAide_Options['show_login_text_color'] == 2) echo 'checked' ;?>/> No
						</td><?php echo '<td>'. __('New Text/Links Color: ', 'csds_userRegAide') .'<input  style="width: 100px;" type="text" title="Enter the new text/links color for your site (#FFFFFF)" value="' . $csds_userRegAide_Options['login_text_color'] . '" name="csds_userRegAide_newTextColor" id="csds_userRegAide_newTextColor" /></td>';
						echo '<td>'. __('New Links Hover Color: ', 'csds_userRegAide') .'<input  style="width: 100px;" type="text" title="Enter the new hover color for your login page links (#FFFFFF)" value="' . $csds_userRegAide_Options['hover_text_color'] . '" name="csds_userRegAide_newHoverTextColor" id="csds_userRegAide_newHoverTextColor" />';
						echo '</td>';
						echo '</tr>';

						// Link Shadow Size & colors

						echo '<tr>';
						echo '<td>'.__('Show Link Shadows: ', 'csds_userRegAide');?><input type="radio" id="csds_userRegAide_show_shadow" name="csds_userRegAide_show_shadow" value="1"
						<?php if ($csds_userRegAide_Options['show_shadow'] == 1) echo 'checked';?>/> Yes
						<input type="radio" id="csds_userRegAide_show_shadow" name="csds_userRegAide_show_shadow" value="2"
						<?php if ($csds_userRegAide_Options['show_shadow'] == 2) echo 'checked' ;?>/> No
						</td><?php echo '<td>'. __('Shadow Size in PX: ', 'csds_userRegAide') .'<input  style="width: 100px;" type="text" title="Enter the new size of shadow for login/registration page links in PX for your site (2px)" value="' . $csds_userRegAide_Options['shadow_size'] . '" name="csds_userRegAide_shadowSize" id="csds_userRegAide_newTextColor" /></td>';
						echo '<td>'. __('Shadow Color: ', 'csds_userRegAide') .'<input  style="width: 100px;" type="text" title="Enter the new color for your login page links shadows (#FFFFFF)" value="' . $csds_userRegAide_Options['shadow_color'] . '" name="csds_userRegAide_shadowColor" id="csds_userRegAide_shadowColor" />';
						echo '</td>';
						echo '</tr>';
						echo '</table>';
						echo '<br/>';
						echo '<input type="submit" class="button-primary" name="csds_userRegAide_logo_update" value="'. __('Update Login-Reg Form Style Options', 'csds_userRegAide').'" />';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</form>';
		echo '</div>';
	echo '</div>';
	}else{
		wp_die(__('You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!'));
	}
}
}
?>