<?php
/**
 * @package Database for Customer_Management
 * @version 1.0
 * @author Smile
 */
/**
 * Create a table for customer management.
 */

global $wpdb;
define("customer_tb", $wpdb->prefix."woocommerce_customers");
define("customer_doc_tb", $wpdb->prefix."woocommerce_customers_doc");
define("customers_payment", $wpdb->prefix."woocommerce_customers_payment");
define("customers_price", $wpdb->prefix."woocommerce_customers_price");
define("customers_product", $wpdb->prefix."woocommerce_customers_product");
define("customers_group", $wpdb->prefix."woocommerce_customers_group");
define("customers_cut_off_time", $wpdb->prefix."woocommerce_customers_cut_off_time");


function create_customer_table() {

	global $wpdb;

	try {
		$query = "CREATE TABLE IF NOT EXISTS`".customer_tb."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) DEFAULT NULL,
				  `user_status` int(11) DEFAULT NULL COMMENT 'hold:0,active:1,inactive:2',
				  `customer_type` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'Retailer, Business',
				  `group_id` int(11) DEFAULT NULL,
				  `company` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `trading_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `tax_number` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `phone` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `mobile` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `shipping_check` int(11) DEFAULT NULL COMMENT '0 or 1',
				  `price_id` int(11) DEFAULT NULL,
				  `payment_terms` int(11) DEFAULT NULL,
				  `payment_method` tinyint(1) DEFAULT NULL COMMENT '0:On Account, 1: Credit Card',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".customer_doc_tb."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `customer_id` int(11) DEFAULT NULL,
				  `post_id` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".customers_payment."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `terms_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `due_in_days` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".customers_price."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `price_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `price_rule` tinyint(1) DEFAULT NULL COMMENT '1:markup or markdown, 0:manual',
				  `select_rule` tinyint(1) DEFAULT NULL COMMENT '1:markup, 0:markdown',
				  `price_percentage` int(11) DEFAULT NULL,
				  `number_round` tinyint(1) DEFAULT NULL COMMENT '1:number round',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".customers_product."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `price_id` int(11) DEFAULT NULL,
				  `post_id` int(11) DEFAULT NULL,
				  `product_price` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".customers_group."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
				  `price_id` int(11) DEFAULT NULL,
				  `payment_method` tinyint(1) DEFAULT NULL COMMENT '0:On Account, 1: Credit Card',
				  `payment_terms` int(11) DEFAULT NULL,
				  `delivery_method` tinyint(1) DEFAULT NULL COMMENT '0:Delivery, 1: Courier',
				  `delivery_days` int(11) DEFAULT NULL,
				  `delivery_charge` tinyint(1) DEFAULT NULL COMMENT '0:price,1:flat fee,2:rate table',
				  `flat_fee` int(11) DEFAULT NULL,
				  `cut_off_time` tinyint(1) DEFAULT NULL COMMENT '0:none,1:cut off',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);

		$query = "CREATE TABLE IF NOT EXISTS`".customers_cut_off_time."` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_id` int(11) DEFAULT NULL,
				  `cut_time_1` int(11) DEFAULT NULL,
				  `delivery_day_1` int(11) DEFAULT NULL,
				  `cut_time_2` int(11) DEFAULT NULL,
				  `delivery_day_2` int(11) DEFAULT NULL,
				  `cut_time_3` int(11) DEFAULT NULL,
				  `delivery_day_3` int(11) DEFAULT NULL,
				  `cut_time_4` int(11) DEFAULT NULL,
				  `delivery_day_4` int(11) DEFAULT NULL,
				  `cut_time_5` int(11) DEFAULT NULL,
				  `delivery_day_5` int(11) DEFAULT NULL,
				  `cut_time_6` int(11) DEFAULT NULL,
				  `delivery_day_6` int(11) DEFAULT NULL,
				  `cut_time_7` int(11) DEFAULT NULL,
				  `delivery_day_7` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
		$wpdb->query($query);		

	} catch (Exception $e) {
		echo $e;
	}			
}

function drop_customer_table() {
	global $wpdb;
	try {
		$query = "DROP TABLE IF EXISTS`".customer_tb."`";
		$wpdb->query($query);
		$query = "DROP TABLE IF EXISTS`".customer_doc_tb."`";
		$wpdb->query($query);
		$query = "DROP TABLE IF EXISTS`".customers_payment."`";
		$wpdb->query($query);
		$query = "DROP TABLE IF EXISTS`".customers_price."`";
		$wpdb->query($query);
		$query = "DROP TABLE IF EXISTS`".customers_product."`";
		$wpdb->query($query);
		$query = "DROP TABLE IF EXISTS`".customers_group."`";
		$wpdb->query($query);
		$query = "DROP TABLE IF EXISTS`".customers_cut_off_time."`";
		$wpdb->query($query);
	} catch (Exception $e) {
		echo $e;
	}		
}
/*
 * Get Customers Data(Customer, Group, Price, Payment Terms)
 */
function get_customers_row_data($table_name, $row_id=null) {
	global $wpdb;
	try {
		if ($row_id){
			$rows = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".$table_name." WHERE `id` = %d", $row_id));
		}else {
			$rows = $wpdb->get_results( "SELECT * FROM ".$table_name);
		}
		return $rows;
    } catch (Exception $e) {
    	echo $e;
    }
}

function save_customers_data($save_data, $table_name, $row_id=null) {
	global $wpdb;
	$save_row_id = '';
	try{
		// Insert new row in Customers Table
		$customer_data = array();
		$colNames = $wpdb->get_col("DESC {$table_name}", 0);
		foreach ($colNames as $colname) {
			if (isset($save_data[$colname])) {
				$customer_data[$colname] = $save_data[$colname];
			}
		}
		if (sizeof($customer_data) > 0) {
			if ($row_id){
				$wpdb->update($table_name,$customer_data, array('id'=>$row_id));
				$save_row_id = $row_id;
			}else {
				$wpdb->insert($table_name,$customer_data);
				$save_row_id = $wpdb->insert_id;
			}
		}
	} catch (Exception $e) {
		echo $e;
	}
	return $save_row_id;
}

function get_customer_list() {
	global $wpdb;
	$customer_list = $wpdb->get_results("select * from `".customer_tb."`");
	return $customer_list;
}
/*
 * Check customers registered
 */
function check_customer_list() {
	$user_list = get_users();
	global $wpdb;
	$user_ids = $wpdb->get_col("select user_id from `".customer_tb."`");
	if (sizeof($user_list) > sizeof($user_ids)) {
		$save_data = array('customer_type'=>'Retailer', 'user_status'=>1);
		$group_data = get_group_row_data();
		if (sizeof($group_data)>0) {
			$save_data['group_id'] = $group_data[0]->id;
			$save_data['price_id'] = $group_data[0]->price_id;
			$save_data['payment_terms'] = $group_data[0]->payment_terms;
		}
		foreach ($user_list as $user) {
			if (!in_array($user->data->ID, $user_ids)) { // user registered into customer
				$save_data['user_id'] = $user->data->ID;
				$wpdb->insert(customer_tb,$save_data);
			}
		}
	}
}

/*
 * Customer Data Class Object
 */
function get_customer_data($customer_id) {
	global $wpdb;
	$customer_data = $wpdb->get_row($wpdb->prepare("select * from `".customer_tb."` where `id`= %d", $customer_id));
	return $customer_data;
}

function save_customer_info($save_data) {
	global $wpdb;
	$customer_tb = customer_tb;
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
		if ($save_data['group_id']) {
			$group_info = get_customers_row_data(customers_group, $save_data['group_id']);
			if ($group_info) {
				$customer_data['price_id'] = $group_info->price_id;
			}
		}
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

function save_customer_new() { // add new Customer

	global $wpdb;
	$save_data = array();

	$form_data = $_POST['form_data'];

	parse_str($form_data,$save_data);

	// create new user
	$user_id = username_exists( $save_data['user_login'] );
	if ( !$user_id and email_exists($save_data['user_email']) == false ) {
		$customer_db = customer_tb;
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
			if ($save_data['group_id']) {
				$group_info = get_customers_row_data(customers_group, $save_data['group_id']);
				if ($group_info) {
					$customer_data['price_id'] = $group_info->price_id;
					$customer_data['payment_terms'] = $group_info->payment_terms;
				}
			}			
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
	exit("ok");
}

function get_doc_body($customer_id, $search_key=null) {
	$doc_body = '';
	global $wpdb;
	try {
		$query = 'SELECT d.`id`,p.`post_date`, p.`post_title`, p.`guid`, m.`meta_value` FROM `'.customer_doc_tb.'` AS d
					JOIN `'.$wpdb->prefix.'posts` AS p ON d.`post_id`=p.`ID`
					JOIN `'.$wpdb->prefix.'postmeta` AS m ON p.`ID`=m.`post_id`
					where d.`customer_id`= '.$customer_id.'
				  ';
		if ($search_key){
			$query .= ' AND (p.`post_title` like "%'.$search_key.'%" OR m.`meta_value` like "%'.$search_key.'%" OR p.`post_date` like "%'.$search_key.'%") ';
		}
		$doc_data = $wpdb->get_results($query);
		if (sizeof($doc_data) > 0) {
			foreach ($doc_data as $doc) {
				$doc_name = explode('/', $doc->meta_value);
				$doc_name = $doc_name[count($doc_name)-1];
				$doc_body .= '<tr>
					<td>'.$doc->post_date.'</td>
					<td>'.$doc->post_title.'</td>
					<td><a href="'.$doc->guid.'">'.$doc_name.'</a></td>
					<td class="doc-action-icons">
						<a class="dashicons dashicons-trash" title="Delete" id="delete_'.$doc->id.'"></a>
						<a class="dashicons dashicons-migrate" title="Send an Email" id="send_'.$doc->id.'"></a>
					</td>
				</tr>';
			}
		} else {
			$doc_body .= '<tr style="text-align:center;"><td colspan="4">No Results</td></tr>';
		}
	} catch (Exception $e) {
		echo $e;
	}
	return $doc_body;
}

function delete_customer_document($doc_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'customtable';
    try {
    	$row = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM ".customer_doc_tb." WHERE id = %d", $doc_id));
    	$doc_post_id = $row[0]->post_id;
    	if ($doc_post_id) {
    		wp_delete_post($doc_post_id);
    	}
    	$wpdb->query( $wpdb->prepare( "DELETE FROM ".customer_doc_tb." WHERE id = %d", $doc_id));

    } catch (Exception $e) {
    	echo $e;
    }
}

function send_customer_document($doc_id) {
	global $wpdb;
    try {
    	$row = $wpdb->get_results( $wpdb->prepare( "SELECT c.`user_id`, d.`post_id` FROM `".$wpdb->prefix."woocommerce_customers` AS c JOIN ".customer_doc_tb." AS d ON c.`id` = d.`customer_id` WHERE d.`id` = %d", $doc_id));
    	$user_id = $row[0]->user_id;
    	$post_id = $row[0]->post_id;
    	if ($user_id){

    		$user_data = get_userdata($user_id);
    		$to = $user_data->data->user_email;
			 
			// Email subject and body text.
			$subject = get_the_title($post_id);
			$message = "";
			$headers = array("Content-Type: text/html; charset=UTF-8");
			$attachment_path = get_post_meta($post_id, "_wp_attached_file");
			$attachments = array( WP_CONTENT_DIR . "/uploads/". $attachment_path[0]);
			 
			// send test message using wp_mail function.
			$sent_message = wp_mail( $to, $subject, $message, $headers ,$attachments );
			//display message based on the result.
			if ( $sent_message ) {
			    // The message was sent.
			    echo "ok";
			} else {
			    // The message was not sent.
			    echo "error";
			}
    	}

    } catch (Exception $e) {
    	echo $e;
    }	
}

/*
 * Get Group List or Group Row
 */
function get_group_row_data($row_id=null) {
	return get_customers_row_data(customers_group, $row_id);
}
/*
 * Get Group List or Group Row
 */
function get_cut_time_row_data($group_id=null) {
	global $wpdb;
	$cut_time_row = $wpdb->get_row($wpdb->prepare("SELECT id FROM ".customers_cut_off_time." WHERE group_id = %d", $group_id));
	return get_customers_row_data(customers_cut_off_time, $cut_time_row->id);
}

/*
 * Save Group List
 */
function save_group_content($save_data) {
	global $wpdb;
	$result = false;
	if (!$save_data['group_name']) return $result;

	try {
		$save_data['group_id'] = save_customers_data($save_data, customers_group, $save_data['customer_row_id']);
		$result = true;
		if ($save_data['cut_off_time']==1) {
			$cut_time = array();
			$cut_time['group_id'] = $save_data['group_id'];
			for ($i=1;$i<=7;$i++) {
				$cut_time['cut_time_'.$i] = $save_data['cut_time_'.$i];
				$cut_time['delivery_day_'.$i] = $save_data['delivery_day_'.$i];
				if ($save_data['week_select_'.$i] == 1) {
					$cut_time['cut_time_'.$i] += 12;
				}
			}
			$cut_time_row = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM ".customers_cut_off_time." WHERE group_id = %d", $save_data['group_id']));
			save_customers_data($cut_time, customers_cut_off_time, $cut_time_row->id);
		}
	} catch (Exception $e) {
		echo $e;
	}
	return $result;
}

/*
 * Get Payment Terms or Terms list
 */
function get_payment_row_data($terms_id=null) {
	global $wpdb;
	try {
		if ($terms_id){
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".customers_payment." WHERE `id` = %d", $terms_id));
		}else {
			$rows = $wpdb->get_results( "SELECT * FROM ".customers_payment);
		}
		return $rows;
    } catch (Exception $e) {
    	echo $e;
    }
}
/*
 * Save Payment Terms
 */
function save_payment_content($save_data) {
	global $wpdb;
	
	if (!$save_data['terms_name']) return false;

	try {
		if ($save_data['customer_row_id']) { // Update Payment Terms
			$result = $wpdb->update(customers_payment, array('terms_name'=>$save_data['terms_name'],'due_in_days'=>$save_data['due_in_days'] ), array('id'=>$save_data['customer_row_id']), array('%s','%d'));
		} else { // Insert Payment Terms
			$result = $wpdb->insert(customers_payment, array('terms_name'=>$save_data['terms_name'],'due_in_days'=>$save_data['due_in_days'] ), array('%s','%d'));
		}
	} catch (Exception $e) {
		echo $e;
	}
	return $result;
}

/*
 * Get Price List or Price Row
 */
function get_price_row_data($row_id=null) {
	global $wpdb;
	try {
		if ($row_id){
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".customers_price." WHERE `id` = %d", $row_id));
		}else {
			$rows = $wpdb->get_results( "SELECT * FROM ".customers_price);
		}
		return $rows;
    } catch (Exception $e) {
    	echo $e;
    }
}

/*
 * Save Price List
 */
function save_price_content($save_data) {
	global $wpdb;
	$result = false;
	if (!$save_data['price_name']) return false;

	if (!isset($save_data['number_round'])) $save_data['number_round'] = 0;

	try {
		$save_data['price_id'] = save_customers_data($save_data, customers_price, $save_data['customer_row_id']);
		$result = true;
		if ($save_data['price_rule']==0) {
			foreach ($save_data as $key => $price) {
				$key_array = explode("_", $key);
				if ($key_array[0] == "post"){
					$save_data['post_id'] = $key_array[1];
					$save_data['product_price'] = $price;
					$product_row = $wpdb->get_row( $wpdb->prepare( "SELECT id FROM ".customers_product." WHERE price_id = %d and post_id=%d", $save_data['price_id'],$save_data['post_id']));
					save_customers_data($save_data, customers_product, $product_row->id);
				}
			}
		}
	} catch (Exception $e) {
		echo $e;
	}
	return $result;
}

/*
 * Get Product Price List
 */
function get_product_prices($price_id) {
	global $wpdb;
	$product_prices = array();
	try {
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".customers_product." WHERE `price_id` = %d", $price_id));
		if (sizeof($rows)>0) {
			foreach ($rows as $row) {
				if (isset($row->product_price))
					$product_prices[$row->post_id] = $row->product_price;
			}
		}
	} catch (Exception $e) {
		echo $e;
	}
	return $product_prices;
}

/*
 * Get User Price info by User ID
 */
function getUserPriceInfo($user_id) {
	global $wpdb;
	try {
		$price_row = $wpdb->get_row( $wpdb->prepare("SELECT p.* FROM ".customer_tb." AS c
				JOIN ".customers_price." AS p ON c.`price_id` = p.id
				WHERE c.`user_id` = %d",$user_id));
		return $price_row;
	} catch (Exception $e) {
		return $e;
	}
}

/*
 * Save Customer Price Info on customer price list page
 */
function save_customer_price($save_data) {
	$save_row_id = save_customers_data($save_data, customer_tb, $save_data['customer_id']);
	return $save_row_id;
}
/*
 * Get User Group info by User ID
 */
function getGroupByUserId($user_id) {
	global $wpdb;
	try {
		$group_row = $wpdb->get_row( $wpdb->prepare("SELECT g.* FROM ".customers_group." AS g
				JOIN ".customer_tb." AS c ON g.`id` = c.`group_id`
				WHERE c.`user_id` = %d",$user_id));
		return $group_row;
	} catch (Exception $e) {
		return $e;
	}
}


if(isset($_POST['doc_save_btn'])) {
	$save_data = $_POST;
	$doc_file_name = $_FILES['doc_file']['name'];
	$doc_file_path = $_FILES['doc_file']['tmp_name'];

	include_once(ABSPATH . 'wp-includes/pluggable.php'); 
	$upload_file = wp_upload_bits($doc_file_name, null, $doc_file_path);

	if ( $upload['error'] ) {
		$error = new WP_Error( 'document_upload', __( 'Error uploading document:', 'customer-document' ) . ' ' . $upload['error'] );
		return $error;
	} else {
		$wp_filetype = wp_check_filetype($doc_file_name, null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'guid' => $upload_file['url'],
			'post_title' => preg_replace('/\.[^.]+$/', '', $save_data['doc_name']),
			'post_content' => '',
			'post_status' => 'inherit'
		);
		$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'] );
		if (!is_wp_error($attachment_id)) {
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
			wp_update_attachment_metadata( $attachment_id,  $attachment_data );
		}

		global $wpdb;
		try {
			$result = $wpdb->insert(customer_doc_tb, array('customer_id'=>$save_data['customer_id'],'post_id'=>$attachment_id ), array('%s','%d'));
		} catch (Exception $e) {
			echo $e;
		}		
	}
}
?>