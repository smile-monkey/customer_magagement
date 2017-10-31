<?php
/**
 * @package Functions for Customer_Management
 * @version 1.0
 * @author Smile
 */

function get_country_options($selected_country=null) {

	global $woocommerce;

	$countries_obj = new WC_Countries();
    $countries   = $countries_obj->__get('countries');
    if (sizeof($countries) > 0) {
    	$country_options = '';
    	$selected = '';
    	foreach ($countries as $key => $country) {
    		$selected = $key==$selected_country ? 'selected="selected"' : '';
    		$country_options .= "<option value='".$key."' ".$selected.">".$country."</option>";
    	}
    }else {
		$country_options = '<option value="" selected="selected">Select a country…</option>';
    }

    return $country_options;
}
/*
 * integrate with WooCommerce Dynamic Pricing
 */
function get_group_options($selected_group=null) {

	$pricing_group_info = get_option( '_a_totals_pricing_rules', array() );

	if (sizeof($pricing_group_info) > 0) {
		$group_options = $selected = '';
		foreach ($pricing_group_info as $key => $group) {
			$selected = $key==$selected_group ? 'selected="selected"' : '';
			$group_options .= "<option value='".$key."' ".$selected.">".$group['admin_title']."</option>";
		}
	}else {
		$group_options = '<option value="" selected="selected">Select a group…</option>';
	}

	return $group_options;
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

function get_customer_info($customer_tb,$customer_id) {
	$customer_data = get_customer_data($customer_tb,$customer_id);
	$user_meta_info = get_user_meta($customer_data->user_id);
	$user_info = get_userdata($customer_data->user_id);

	$billing_countries = get_country_options($user_meta_info['billing_country'][0]);
	$shipping_countries = get_country_options($user_meta_info['shipping_country'][0]);

	$content = "
	<form id='customer_edit_data' method='post' class='customer-edit-data' action='' enctype='multipart/form-data'>
	  <div style='float:left;'>
	  	<table class='add_form_table'>
	  	  <tr>
	  		<td><span class='td-text'>Customer Number:</span></td>
	  		<td>
	  			<input type='text' value='".$customer_data->id."' disabled>
	  			<input type='hidden' name='customer_id' id='customer_id' value='".$customer_data->id."'>
	  			<input type='hidden' name='user_id' id='user_id' value='".$customer_data->user_id."'>
	  			<input type='hidden' name='main_tab' id='main_tab' value='customer_info'>
			</td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Customer Type:</span></td>
	  		<td><input name='customer_type' type='text' value='".$customer_data->customer_type."' disabled></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Customer Group:</span></td>
	  		<td><select id='group_id' name='group_id'>".get_group_options($customer_data->group_id)."</select></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Company Name:</span></td>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->company."'s></td>
	  	  </tr>	  	  	  	  
	  	  <tr>
	  		<td><span class='td-text'>First Name:</span></td>
	  		<td><input id='first_name' name='first_name' type='text' value='".$user_meta_info['first_name'][0]."'></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Last Name:</span></td>
	  		<td><input id='last_name' name='last_name' type='text' value='".$user_meta_info['last_name'][0]."'></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Mobile:</span></td>
	  		<td><input id='mobile' name='mobile' type='text' value='".$customer_data->mobile."'></td>
	  	  </tr>	
	  	  <tr>
	  		<td><span class='td-text'>Email Address:</span></td>
	  		<td><input id='user_email' name='user_email' type='text' value='".$user_info->data->user_email."'></td>
	  	  </tr>	  	  
	  	  <tr>
	  		<td><span class='td-text'>Account Status:</span></td>
	  		<td class='account-status-box'><input type='checkbox' class='multi-switch' initial-value='0' unchecked-value='2' checked-value='1' value='".$customer_data->user_status."' name='account_edit_status' id='account_edit_status' />
	  			<input type='hidden' name='user_status' id='user_status' value='".$customer_data->user_status."'>
	  		</td>
	  	  </tr>
	  	  <tr>
			  <td colspan='2' class='edit-footer'>
			  	<input type='submit' class='customer-button customer-edit-button' value='Save'>
			  </td>
	  	  </tr>
	  	</table>
	  </div>

	  <div>
	  	<table class='add_form_table' style='padding-top: 20px;'>
  		  <tr>
  		  	<td>
  		  		<span class='td-text'>Billing Address</span><br>
  		  		<input type='text' name='billing_address_1' id='billing_address_1' value='".$user_meta_info['billing_address_1'][0]."' placeholder='Street Name'><br>
  		  		<input type='text' name='billing_city' id='billing_city' value='".$user_meta_info['billing_city'][0]."' placeholder='Suburb'><br>
  		  		<input type='text' name='billing_state' id='billing_state' value='".$user_meta_info['billing_state'][0]."' placeholder='State / Province'><br>
  		  		<input type='text' name='billing_postcode' id='billing_postcode' value='".$user_meta_info['billing_postcode'][0]."' placeholder='Postal Code / Zip Code'><br>
  		  		<select name='billing_country' id='billing_country' class='country-select'>".$billing_countries."</select>		  		  		
  		  	</td>
  		  	<td id='shipping_data'>
  		  		<span class='td-text'>Shipping Address</span><br>
  		  		<input type='text' name='shipping_address_1' id='shipping_address_1' value='".$user_meta_info['shipping_address_1'][0]."' placeholder='Street Name'><br>
  		  		<input type='text' name='shipping_city' id='shipping_city' value='".$user_meta_info['shipping_city'][0]."' placeholder='Suburb'><br>
  		  		<input type='text' name='shipping_state' id='shipping_state' value='".$user_meta_info['shipping_state'][0]."' placeholder='State / Province'><br>
  		  		<input type='text' name='shipping_postcode' id='shipping_postcode' value='".$user_meta_info['shipping_postcode'][0]."' placeholder='Postal Code / Zip Code'><br>
  		  		<select name='shipping_country' id='shipping_country' class='country-select'>".$shipping_countries."</select>
  		  	</td>
  		  </tr>
	  	</table>
	  </div>
	</form>
	";

	return $content;
}

function get_customer_transaction($customer_tb, $customer_id) {
	$content = '
		<div>
			<h1>Transactions</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}


function get_customer_price($customer_tb, $customer_id) {
	$content = '
		<div>
			<h1>Price List</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}


function get_customer_delivery($customer_tb, $customer_id) {
	$content = '
		<div>
			<h1>Delivery Agreement</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}

function get_customer_doc($customer_tb, $customer_id) {
	$content = '
		<div>
			<h1>Documents</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}

function get_customer_login($customer_tb,$customer_id) {

	$customer_data = get_customer_data($customer_tb,$customer_id);
	$user_info = get_userdata($customer_data->user_id);
	$content = '
	  <form method="post" class="customer-edit-data" action="" enctype="multipart/form-data">
		<div>
			<h1>Login Credentials</h1>
			<table class="add_form_table">
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Customer ID:</span>
	  		  	</td>
	  		  	<td>
	  		  		<input type="text" value="'.$customer_data->id.'" disabled>
	  		  		<input type="hidden" name="user_id" id="user_id" value="'.$customer_data->user_id.'">
	  		  		<input type="hidden" name="main_tab" id="main_tab" value="customer_login">
	  		  	</td>
	  		  </tr>
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Registered Email:</span>
	  		  	</td>
	  		  	<td>	  		  		
	  		  		<input name="user_email" type="text" id="user_email" value="'.$user_info->data->user_email.'">
	  		  	</td>
	  		  </tr>
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Username:</span>
	  		  	</td>
	  		  	<td>	  		  		
	  		  		<input name="user_login" type="text" id="user_login" value="'.$user_info->data->user_login.'">
	  		  	</td>
	  		  </tr>
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Password:</span>
	  		  	</td>
	  		  	<td>	  		  		
	  		  		<input name="user_pass" type="password" id="user_pass" value="'.$user_info->data->user_pass.'" disabled>
	  		  	</td>
	  		  </tr>
		  	  <tr>
				  <td colspan="2" class="edit-footer">
				  	<input type="submit" class="customer-button customer-edit-button" value="Save">
				  </td>
		  	  </tr>	  		  
			</table>
		</div>
	  </form>
	';

	return $content;
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


?>