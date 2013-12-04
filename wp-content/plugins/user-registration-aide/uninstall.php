<?php

//For Debugging and Testing Purposes ------------



// ----------------------------------------------

	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')){
		exit('Something Bad Happened');
	}else{
		
		delete_option('csds_userRegAideFields');
		delete_option('csds_userRegAide_knownFields');
		delete_option('csds_userRegAide_fieldOrder');
		delete_option('csds_userRegAide_registrationFields');
		delete_option('csds_userRegAide_newField');
		delete_option('csds_userRegAide_dbVersion');
		delete_option('csds_userRegAide');
		delete_option('csds_userRegAide_Options');
		delete_option('csds_userRegAide_dbVersion');
		delete_option('csds_userRegAide_support');
		delete_option('csds_display_link');
		delete_option('csds_display_name');
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
			foreach($csds_userRegAide_NewFields as $field => $value){
				
				$count = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users;");
				$i = 1;
					while($i <= $count)
				{
					$user_id = $i;
					delete_user_meta( $user_id, $field, "");
					$i++;
				}
			}
		delete_option('csds_userRegAide_NewFields');
	
	
	}
?>