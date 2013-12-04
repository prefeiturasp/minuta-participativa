<?php
/*
 * User Registration Aide - Edit New Fields Administration Page
 * Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/
 * Version: 1.2.8
 * Author: Brian Novotny
 * Author URI: http://creative-software-design-solutions.com/
*/

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
include_once ("user-reg-aide-admin.php");
include_once ("user-registration-aide.php");
include_once ("user-reg-aide-newFields.php");
include_once ("user-reg-aide-regForm.php");

/**
 * Adds the new default options for the options fields on admin forms
 *
 * @since 1.2.0
 * @updated 1.2.5
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

if(!function_exists('csds_userRegAide_DefaultOptions')){
	function csds_userRegAide_DefaultOptions(){

		$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
		$csds_userRegAide_Options = array();
		if(empty($csds_userRegAide_Options)){
			if(function_exists('csds_userRegAide_defaultOptionsArray')){
				$csds_userRegAide_Options = csds_userRegAide_defaultOptionsArray();
			}


			update_option("csds_userRegAide_Options", $csds_userRegAide_Options);

			// For updates from older versions

			delete_option('csds_userRegAide_support');
			delete_option('csds_display_link');
			delete_option('csds_display_name');
			delete_option('csds_userRegAide_dbVersion');


		}else{
			$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
		}
		return $csds_userRegAide_Options;
	}
}

/**
 * Array for all the new default options for the options fields on admin forms
 *
 * @since 1.2.5
 * @updated 1.2.8
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

function csds_userRegAide_defaultOptionsArray(){
	$csds_userRegAide_Options = array(
				"csds_userRegAide_db_Version" => "1.2.8",
				"select_pass_message" => "2",
				"registration_form_message" => "You can use the password you entered here to log in right away, and for your reference, your registration details will be emailed after signup",
				"agreement_message" => "I have read and understand and agree to the terms and conditions of the guidelines/agreement policy required for this website provided in the link below",
				"short" => "Password Entered is too Short!",
				"bad" => "Password Entered is Bad, Too Weak",
				"good" => "Password Entered is fairly tough and is good to accept",
				"strong" => "Password Entered is very strong!",
				"mismatch" => "Password Entered does not match Password Confirm! Try Again Please!",
				"show_support" => "2",
				"support_display_link" => "http://creative-software-design-solutions.com/#axzz24C84ExPC",
				"support_display_name" => "Creative Software Design Solutions",
				"show_logo" => "2",
				"logo_url" => "wp-admin/images/wordpress-logo.png",
				"show_background_image" => "2",
				"background_image_url" => "",
				"show_background_color" => "2",
				"reg_background_color" => "#FFFFFF",
				"show_reg_form_page_color" => "2",
				"reg_form_page_color" => "#FFFFFF",
				"show_reg_form_page_image" => "2",
				"reg_form_page_image" => "",
				"show_login_text_color" => "2",
				"login_text_color" => "#BBBBBB",
				"hover_text_color" => "#FF0000",
				"show_shadow" => "2",
				"shadow_size" => "0px",
				"shadow_color" => "#FFFFFF",
				"change_logo_link" => "2",
				"show_custom_agreement_link" => "2",
				"agreement_title" => "Agreement Policy",
				"show_custom_agreement_message" => "2",
				"show_custom_agreement_checkbox" => "2",
				"new_user_agree" => "2",
				"agreement_link" => site_url()

			);
			return $csds_userRegAide_Options;
}

/**
 * Fills array of known fields
 *
 * @since 1.0.0
 * @updated 1.2.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/
if(!function_exists('csds_userRegAide_fill_known_fields')){
	function csds_userRegAide_fill_known_fields(){

		if(!empty($csds_userRegAide_knownFields) && !empty($csds_userRegAide)){
			$csds_userRegAide_knownFields = array();
			$csds_userRegAideFields = array();
		}

		$csds_userRegAide_knownFields = array(
		"first_name"	=> _("Primeiro Nome"),
		"last_name"		=> _("Último Nome"),
		"nickname"		=> _("Nickname"),
		"user_url"		=> _("Website"),
		"aim"			=> _("AIM"),
		"yim"			=> _("Yahoo IM"),
		"jabber"		=> _("Jabber / Google Talk"),
		"description"   => _("Biographical Info"),
		"user_pass"		=> _("Password")
		);

		update_option("csds_userRegAideFields", $csds_userRegAide_knownFields);
		update_option("csds_userRegAide_knownFields", $csds_userRegAide_knownFields);
		if(!empty($csds_userRegAide_NewFields)){
			foreach($csds_userRegAideFields as $key1 => $field1){
				foreach($csds_userRegAide_NewFields as $key => $field){
					if(!$key1 == $key){
						$csds_userRegAideFields[$key] = $field;
					}
				}
			}
		}


		// Updates the field order set to default by order entered into program

		if(empty($csds_userRegAide_fieldOrder) && !empty($csds_userRegAide_NewFields)){
			csds_userRegAide_update_field_order();
		}

		if(empty($csds_userRegAide_registrationFields)){
			csds_userRegAide_updateRegistrationFields();
		}

	}
}

/**
 * Updates the registration fields array and storage method in options db upgrade in 1.1.0
 *
 * @since 1.1.0
 * @updated 1.2.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

if(!function_exists('csds_userRegAide_updateRegistrationFields')){
	function csds_userRegAide_updateRegistrationFields(){

		global $csds_userRegAide_knownFields, $csds_userRegAide_getOptions, $csds_userRegAide_NewFields, $csds_userRegAide_fieldOrder;

		$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
		$csds_userRegAideFields = get_option('csds_userRegAideFields');
		$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
		$csds_userRegAide_getOptions = get_option('csds_userRegAide');

		// Checks to see if older version of additional fields exists and if so transfers them to new option

		if(!empty($csds_userRegAide_getOptions["additionalFields"])){
			foreach($csds_userRegAide_getOptions["additionalFields"] as $key => $value){
				foreach($csds_userRegAide_knownFields as $key1 => $value1){
					if($value == $key1){
						$csds_userRegAide_registrationFields[$key1] = $value1;
						$csds_userRegAide_registrationFields = $csds_userRegAide_registrationFields;
						update_option("csds_userRegAide_registrationFields", $csds_userRegAide_registrationFields);
					}
				}
				foreach($csds_userRegAide_NewFields as $key2 => $value2){
					if($value == $key2){
						$csds_userRegAide_registrationFields[$key2] = $value2;
						$csds_userRegAide_registrationFields = $csds_userRegAide_registrationFields;
						update_option("csds_userRegAide_registrationFields", $csds_userRegAide_registrationFields);
					}
					// Testing echo '<div id="message" class="updated fade"><p>'. __('Test key:'.$key.' value: '.$value.'Key 1: '.$key1.'Value: '.$value1.'Key 2: '.$key2.'Value 2: '.$value2.' end test', 'csds_userRegAide') .'</p></div>';
				}
			}
		$csds_userRegAide = array();
		update_option("csds_userRegAide", $csds_userRegAide);
		delete_option($csds_userRegAide);
		}
	}
}

/**
 * Fills and arranges the order of new fields based on order of creation initially
 *
 * @since 1.1.0
 * @updated 1.2.0
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

if(!function_exists('csds_userRegAide_update_field_order')){
	function csds_userRegAide_update_field_order(){

		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
		$csds_userRegAideFields = get_option('csds_userRegAideFields');
		if(empty($csds_userRegAide_fieldOrder)){
			if(!empty($csds_userRegAide_NewFields)){
				$i = 1;
				foreach($csds_userRegAide_NewFields as $key => $field){
					$csds_userRegAide_fieldOrder[$key] = $i;

					$i++;
				}
			}
			$csds_userRegAideFields = array();
			$csds_userRegAideFields = $csds_userRegAide_knownFields + $csds_userRegAide_NewFields;
			update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
		}else{
			$csds_userRegAide_fieldOrder = array();
			update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
			if(!empty($csds_userRegAide_NewFields)){
				$i = 1;
				foreach($csds_userRegAide_NewFields as $key => $field){
					$csds_userRegAide_fieldOrder[$key] = $i;
					$i++;
				}
			}
			$csds_userRegAideFields = array();
			$csds_userRegAideFields = $csds_userRegAide_knownFields + $csds_userRegAide_NewFields;
			update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
		}
	}
}

/**
 * Updates Database Options
 *
 * @since 1.2.5
 * @updated 1.2.8
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

if(!function_exists('csds_userRegAide_updateOptions')){
	function csds_userRegAide_updateOptions(){
		$csds_userRegAide_oldOptions = array();
		$csds_userRegAide_oldOptions = get_option('csds_userRegAide_Options');
		$csds_userRegAide_defOptions = array();
		$csds_userRegAide_defOptions = csds_userRegAide_defaultOptionsArray();
		$update = array();
		if(empty($csds_userRegAide_oldOptions)){
			if(function_exists('csds_userRegAide_DefaultOptions')){
				csds_userRegAide_DefaultOptions();
			}
		}else{
			foreach($csds_userRegAide_oldOptions as $key1 => $value1){
				foreach($csds_userRegAide_defOptions as $key => $value){
					if($key1 == $key){
						if(!empty($value1)){
							if($key1 == 'csds_userRegAide_db_Version'){
								$update[$key1] = "1.2.8";
							}else{
								if($value1 != $value){
									$update[$key1] = $value1;
								}else{
									$update[$key] = $value;
								}
							}
						}else{
							$update[$key] = $value;
						}
					}

					 if(!array_key_exists($key, $csds_userRegAide_oldOptions)){
						 $update[$key] = $value;
					 }

				}
			}
			update_option("csds_userRegAide_Options", $update);
		}
	}
}
?>