<?php
/**
 * @package Database for Customer_Management
 * @version 1.0
 * @author Smile
 */
/**
 * Create a table for customer management.
 */

function create_customer_table($customer_main_tb, $customer_doc_tb) {

	global $wpdb;

	try {
		$query = "CREATE TABLE IF NOT EXISTS`".$customer_main_tb."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) DEFAULT NULL,
				  `user_status` int(11) DEFAULT NULL COMMENT 'hold:0,active:1,inactive:2',
				  `customer_type` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Retailer, Business',
				  `group_id` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `company` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `tax_number` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `phone` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `mobile` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `shipping_check` int(11) DEFAULT NULL COMMENT '0 or 1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".$customer_doc_tb."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `customer_id` int(11) DEFAULT NULL,
				  `doc_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'document name',
				  `post_id` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

	} catch (Exception $e) {
		echo $e;
	}			
}

function get_customer_list($customer_tb) {
	global $wpdb;
	$customer_list = $wpdb->get_results("select * from `".$customer_tb."`");
	return $customer_list;
}

/*
 * Customer Data Class Object
 */
function get_customer_data($customer_tb,$customer_id) {
	global $wpdb;
	$customer_data = $wpdb->get_row($wpdb->prepare("select * from `".$customer_tb."` where `id`= %d", $customer_id));
	return $customer_data;
}

function save_customer_info($save_data, $customer_tb) {
	global $wpdb;
	// Update user email address
	$user_id = wp_update_user( array( 'ID' => $save_data['user_id'], 'user_email' => $save_data['user_email'] ));
	// Insert new row in Customers Table
	$customer_data = array();
	$colNames = $wpdb->get_col("DESC {$customer_tb}", 0);
	foreach ($colNames as $colname) {
		if (isset($save_data[$colname]) && $save_data[$colname] !=null) {
			$customer_data[$colname] = $save_data[$colname];
		}
	}
	if (sizeof($customer_data) > 0) {
		$wpdb->update($customer_tb,$customer_data,array('id'=>$save_data['customer_id']));
	}
	// Update User meta data for billing and shipping
	foreach ($save_data as $key => $value) {
		if (strpos($key,"billing_")==0 || strpos($key,"shipping_")==0) {
			update_user_meta($save_data['user_id'],$key,$value);
		}
	}
	return true;
}

function save_customer_login($save_data) {
	global $wpdb;
	$username = $save_data['user_login'];
	// Check if user_login already exists before we force update
	if ( ! username_exists( $username ) ) {

		// Force update user_login and user_email
		$tablename = $wpdb->prefix . "users";
		$wpdb->update( $tablename, 						// Table to Update 	( prefix_users )
					   array( 
					   		'user_login' => $username,	// Data to Update 	( user_login )
					   		'user_email' => $save_data['user_email'] 	// Data to Update 	( user_nicename )
					   ),									
					   array( 'ID' => $save_data['user_id'] ),			// WHERE clause 	( ID = $user->ID )
					   array(
					   		'%s',				// Data format 		( string )
					   		'%s'				// Data format 		( string )
					   	), 							
					   array('%d') 					// Where Format 	( int )
					);
	}
	
	return true;
}

function save_customer_new($customer_db) {

	global $wpdb;
	$save_data = array();

	parse_str($_POST['form_data'],$save_data);

	// create new user
	$user_id = username_exists( $save_data['user_login'] );
	if ( !$user_id and email_exists($save_data['user_email']) == false ) {

		// $save_data['user_pass'] = md5($save_data['user_pass']);
		$save_data['user_id'] = wp_insert_user( $save_data);

		// Insert new row in Customers Table
		$customer_data = array();
		$colNames = $wpdb->get_col("DESC {$customer_db}", 0);
		foreach ($colNames as $colname) {
			if (isset($save_data[$colname]) && $save_data[$colname] !=null) {
				$customer_data[$colname] = $save_data[$colname];
			}
		}
		if (sizeof($customer_data) > 0) {
			$wpdb->insert($customer_db,$customer_data);
		}
		// Add User meta data for billing and shipping
		foreach ($save_data as $key => $value) {
			if (strpos($key,"billing_")==0 || strpos($key,"shipping_")==0) {
				update_user_meta($save_data['user_id'],$key,$value);
			}
		}
	} else {
		echo "User already exists.";
	}

}

?>