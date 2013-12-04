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
include_once ("user-reg-aide-options.php");
include_once ("user-reg-aide-regForm.php");

/**
 * Loads and displays the User Registration Aide Edit New Fields Administration Page
 *
 * @since 1.1.0
 * @updated 1.2.8
 * @access private
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

if(!function_exists('csds_userRegAide_editNewFields')){
	function csds_userRegAide_editNewFields(){

	global $csds_userRegAide_knownFields, $csds_userRegAide_registrationFields, $csds_userRegAide_NewFields, $csds_userRegAide_Options, $csds_userRegAide_fieldOrder, $current_user;
	
	
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
			
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	if($csds_userRegAide_Options['csds_userRegAide_db_Version'] != "1.2.8"){
		if(function_exists('csds_userRegAide_updateOptions')){
			csds_userRegAide_updateOptions();
		}
	}
		
		
	$seperator = '';
	$results1 = '';
	$csds_userRegAideFields = get_option('csds_userRegAideFields');
	$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
	$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
	$csds_userRegAide_support = get_option('csds_userRegAide_support');
	$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
	$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
	$fieldKey = '';
	$fieldOrder = '';
	$csds_userRegAide_Options = get_option('csds_userRegAide_Options');
	// Double checks to see that the new field order is updated
	
	if(!empty($csds_userRegAide_NewFields)){
		if(function_exists('csds_userRegAide_update_field_order')){
			//include("user-reg-aide-admin.php");
			csds_userRegAide_update_field_order();
		}
	}
	
	// Handles the delete field form
	
	if (isset($_POST['delete_field'])){
		
		$results1 = '';
		$csds_current_user = wp_get_current_user();
		
		if(current_user_can('activate_plugins')){
		
			$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
			$csds_userRegAideFields = get_option('csds_userRegAideFields');
			$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
			$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
			
			// Checking for field to delete if empty gives warning and exits
			
			if(!empty($_POST['deleteNewFields'])){
				$results1 =  $_POST['deleteNewFields'];
			}else{
				echo '<div id="message" class="updated fade"><p>'. __('No field was selected for deletion, you must select a field to delete first!' , 'csds_userRegAide') .'</p></div>';
				exit();
			}
			unset($csds_userRegAide_NewFields[$results1]);
			unset($csds_userRegAideFields[$results1]);
			unset($csds_userRegAide_fieldOrder[$results1]);
			unset($csds_userRegAide_registrationFields[$results1]);
			$csds_userRegAide_NewFields = $csds_userRegAide_NewFields;
			$csds_userRegAideFields = $csds_userRegAideFields;
			$csds_userRegAide_fieldOrder = $csds_userRegAide_fieldOrder;
			$csds_userRegAide_registrationFields = $csds_userRegAide_registrationFields;
			update_option("csds_userRegAide_NewFields", $csds_userRegAide_NewFields);
			update_option("csds_userRegAideFields", $csds_userRegAideFields);
			update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
			update_option("csds_userRegAide_registrationFields", $csds_userRegAide_registrationFields);
			
			if(function_exists('csds_userRegAide_update_field_order')){
				csds_userRegAide_update_field_order();
			}
			
			if(function_exists('csds_delete_field_from_users_meta')){
				csds_delete_field_from_users_meta($results1);  // Deletes field from user meta
			}
			//Report to the user that the data has been updated successfully or that an error has occurred
			
			echo '<div id="message" class="updated fade"><p>'. __( $results1.' Successfully Deleted!' , 'csds_userRegAide') .'</p></div>';			
		}else{
			echo '<div id="message" class="updated fade"><p>'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
			exit();
		}
	
	// Handles the new fields order form
	
	}elseif (isset($_POST['field_order'])){
		
		$key = '';
		$key1 = '';
		$key2 = '';
		$value = '';
		$value1 = '';
		$value2 = '';
		$results = '';
		$aa = '';
		
		$csds_current_user = wp_get_current_user();
		
		if(current_user_can('activate_plugins')){
			$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
							
			// Getting values from new field order select options
				
			$results = $_POST['csds_editFieldOrder'];
			$aa = 0;
				
			foreach($csds_userRegAide_fieldOrder as $key => $value){
				foreach($results as $key1 => $value1){
					foreach($results as $key2 => $value2){
						if($key1 != $key2 && ($value1 == $value2)){
							echo '<div id="message" class="updated fade"><p>'. __('Error Updating New Field Order Options, two or more fields have the same order!', 'csds_userRegAide') .'</p></div>';
						exit();
						}
					}
					if($aa == $key1){
						$csds_userRegAide_fieldOrder_temp[$key] = $value1;
					}	
				}
						
			$aa++;
			}
					
			// Updating New Field Order
				
			$csds_userRegAide_fieldOrder = $csds_userRegAide_fieldOrder_temp;
			asort($csds_userRegAide_fieldOrder);
			update_option("csds_userRegAide_fieldOrder", $csds_userRegAide_fieldOrder);
				
				// Updating New Fields to new Order
				
			foreach($csds_userRegAide_fieldOrder as $key => $order){
				foreach($csds_userRegAide_NewFields as $key1 => $name1){
					if($key == $key1){
						$csds_userRegAide_NewFields_temp[$key1] = $name1;
					}
				}
			}
			
			// Updating field order in database
			
			$csds_userRegAide_NewFields = $csds_userRegAide_NewFields_temp;
			update_option("csds_userRegAide_NewFields", $csds_userRegAide_NewFields);
			$csds_userRegAide_knownFields;	
			$csds_userRegAideFields = array();
			$csds_userRegAideFields = $csds_userRegAide_knownFields + $csds_userRegAide_NewFields;
			update_option("csds_userRegAideFields", $csds_userRegAideFields);
			echo '<div id="message" class="updated fade"><p>'. __('New Field Order Options updated successfully.', 'csds_userRegAide') .'</p></div>';
		}else{
			echo '<div id="message" class="updated fade"><p>'. __('You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'csds_userRegAide') .'</p></div>';
		}
	}elseif (isset($_POST['csds_userRegAide_support_submit'])){
		$update = array();
		$update = get_option('csds_userRegAide_Options');
		$update['show_support'] = esc_attr(stripslashes($_POST['csds_userRegAide_support']));
		update_option("csds_userRegAide_Options", $update);
		echo '<div id="message" class="updated fade"><p>'. __('Support Options updated successfully.', 'csds_userRegAide') .'</p></div>'; //Report to the user that the data has been updated successfully
	}else{
		$csds_userRegAide_getOptions = get_option('csds_userRegAide');
		
	}
		
	// Checks to make sure that the known fields were loaded on installation 

	if(!empty($csds_userRegAide_knownFields)){
		if(function_exists('csds_userRegAide_fill_known_fields')){
			csds_userRegAide_fill_known_fields();
		}
	}
		// Loads additional options needed for page just in case
		
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		$csds_userRegAideFields = get_option('csds_userRegAideFields');
		$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
		$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
		
		
		// Checks to see if any new fields exist and if not displays message telling user to create some new fields before editing them
		
		if(empty($csds_userRegAide_NewFields)){
			echo '<div id="message" class="updated fade"><p>'. __('No new fields have been created, please go to the main page to add some new fields first before you try and edit them! ', 'csds_userRegAide') .'</p></div>';
			exit();
		}

// Displays the Edit New Additional Fields Administration Page
$current_user = wp_get_current_user();
if(current_user_can('manage_options')){		
echo '<div id="wpbody">';
	echo '<div class=wrap>';
		echo '<form method="post" name="csds_userRegAide">';
            echo '<h2>'. __('User Registration Aide: Edit New Fields', 'csds_userRegAide') .'</h2>';
			
			// Delete new fields form
			
            echo '<div id="poststuff">';       
                echo '<div class="stuffbox">';
                    echo '<h3>'.__('Edit Additional Fields for Profile &  Registration Form: Delete Field', 'csds_userRegAide').'</h3>';
						echo '<div class="inside">';
                        echo '<p>'.__('Here you can select the new additional fields you added that you want to delete.', 'csds_userRegAide').'</p>';
						echo '<select name="deleteNewFields" id="csds_userRegMod_delete_Select" size="8"  style="height:100px">';
                       
                            foreach($csds_userRegAide_NewFields as $fieldKey => $fieldName){
								echo '<option value="'.$fieldKey.'">'.$fieldName.'</option>';
                            }
							                        
						echo '</select>';
						
                    	echo '<br/>';
						
						echo '<div class="submit"><input type="submit" class="button-primary" name="delete_field" value="'.__('Delete New Field', 'csds_userRegAide').'"/></div>';
						echo '</div>';
					    echo '</div>';
						
				// Edit new field order form 
		
				echo '<div class="stuffbox">';
				echo '<h3>'.__('Edit New Field Order', 'csds_userRegAide').'</h3>';
				echo '<div class="inside">';
						$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
						$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
						echo '<p>'.__('Here you can select or change the order for the new additional fields on the registration form and profile. You must not have the same number twice, so make sure you change all fields accordingly so there are no duplicates or you will generate an error!', 'csds_userRegAide') .'</p>';
						
						$i = '';
						$cnt = '';
						$fieldKey = '';
						$fieldOrder = '';
						$fieldKeyUpper = '';
						
						$i = count($csds_userRegAide_NewFields);
						$cnt = 1;
						
						// Table for field order
						echo '<br/>';
						echo '<table border="5" cellpadding="5" cellspacing="5">';
						echo '<tr>';
						echo '<td >'. __('Additional New Field Name: ', 'csds_userRegAide').'</td>';
						echo '<td>'. __('Current Field Order: ', 'csds_userRegAide').'</td>';
						echo '</tr>';
                            foreach($csds_userRegAide_fieldOrder as $fieldKey => $fieldOrder){
								echo '<tr>';
								echo '<td align="center">';
								$fieldKeyUpper = strtoupper($fieldKey);
								echo '<label for="'.$fieldKey.'">'.$fieldKeyUpper.'</label>';
								//Changed from check box to label here
								
								echo '</td>';
								echo '<td align="center">';
								echo '<select name="csds_editFieldOrder[]">';
                       
								for($ii = 1; $ii <= $i; $ii++){
									if($ii == $fieldOrder){
										echo '<option selected="'.$fieldKey.'" >'.$fieldOrder.'</option>';
									}else{
										echo '<option value="'.$ii.'">'.$ii.'</option>';
									}									
								}
								echo '</td>';
								$cnt ++;
							    echo '</select>';
								echo '</tr>';
                            }
						echo '</table>';
						echo '<div class="submit"><input type="submit" class="button-primary" name="field_order" value="'. __('Update Field Order', 'csds_userRegAide').'"  /></div>';
					echo '</div>';
				echo '</div>';
				
						// Plugin Help & Support Form
				
						echo '<div class="stuffbox">';
						echo '<h3><a href="http://creative-software-design-solutions.com" target="_blank">Creative Software Design Solutions</a></h3>';
						echo '<div class="inside">';
						echo '<p>'.__('Show Plugin Support: ', 'csds_userRegAide').'<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="1"';
						if ($csds_userRegAide_Options['show_support'] == 1) echo 'checked' ;?>/> Yes
						<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="2" <?php
						if ($csds_userRegAide_Options['show_support'] == 2) echo 'checked' ;?>/> No<?php
						echo '<br/>';
						echo '<br/>';
					
								echo '<input name="csds_userRegAide_support_submit" id="csds_userRegAide_support_submit" lang="publish" class="button-primary" value="Update" type="Submit" />';?><h2><?php _e('Plugin Configuration Help', 'csds_userRegAide');?></h2>
								<ul>
									<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Plugin Page & Screenshots</a></li>
								</ul>
								<h2><?php _e('Check Official Website', 'csds_userRegAide');?></h2>
								<ul>
									<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Check official website for live demo</a></li>
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="6BCZESUXLS9NN">
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
									</form><?php

							    echo '</ul>';
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