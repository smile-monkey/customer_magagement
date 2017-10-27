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

function get_group_options($selected_group=null) {

	$group_list = array('1'=>'group1','2'=>'group2','3'=>'group3');

	if (sizeof($group_list) > 0) {
		$group_options = $selected = '';
		foreach ($group_list as $key => $group) {
			$selected = $key==$selected_group ? 'selected="selected"' : '';
			$group_options .= "<option value='".$key."' ".$selected.">".$group."</option>";
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
	$customer_data = $wpdb->get_row($wpdb->prepare("select * from `".$customer_tb."` where id=".$customer_id));
	return $customer_data;
}

function get_customer_info($customer_tb,$customer_id) {
	$customer_data = get_customer_data($customer_tb,$customer_id);
	$user_meta_info = get_user_meta($customer_data->user_id);
	$user_info = get_userdata($customer_data->user_id);

	$billing_countries = get_country_options($user_meta_info['shipping_country'][0]);
	$shipping_countries = get_country_options($user_meta_info['shipping_country'][0]);

	$content = "
	  <div style='float:left;'>
	  	<table class='add_form_table'>
	  	  <tr>
	  		<td><span class='td-text'>Customer Number:</span></td>
	  		<td><input type='text' name='company' id='company' value='".$customer_data->id."' disabled></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Customer Type:</span>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->customer_type."' disabled></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Customer Group:</span>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->group_id."' disabled></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Company Name:</span>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->company."'s></td>
	  	  </tr>	  	  	  	  
	  	  <tr>
	  		<td><span class='td-text'>First Name:</span>
	  		<td><input id='company' name='company' type='text' value='".$user_meta_info['first_name'][0]."'></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Last Name:</span>
	  		<td><input id='company' name='company' type='text' value='".$user_meta_info['last_name'][0]."'></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Mobile:</span>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->mobile."'></td>
	  	  </tr>	
	  	  <tr>
	  		<td><span class='td-text'>Email Address:</span>
	  		<td><input id='company' name='company' type='text' value='".$user_info->data->user_email."'></td>
	  	  </tr>	  	  
	  	  <tr>
	  		<td><span class='td-text'>Account Status:</span>
	  		<td><input type='checkbox' class='multi-switch' initial-value='0' unchecked-value='2' checked-value='1' value='".$customer_data->user_status."' name='account_edit_status' id='account_edit_status' /></td>
	  	  </tr>		  	    	  	  	  	  	  
	  	</table>
	  </div>

	  <div>
	  	<table class='add_form_table' style='padding-top: 20px;'>
  		  <tr>
  		  	<td>
  		  		<span class='td-text'>Billing Address</span><br>
  		  		<input type='text' name='billing_address_1' id='billing_address_1' value='".$user_meta_info['billing_address_1'][0]."'><br>
  		  		<input type='text' name='billing_address_2' id='billing_address_2' value='".$user_meta_info['billing_address_2'][0]."'><br>
  		  		<input type='text' name='billing_city' id='billing_city' value='".$user_meta_info['billing_address_1'][0]."'><br>
  		  		<input type='text' name='billing_postcode' id='billing_postcode' value='".$user_meta_info['billing_address_1'][0]."'><br>
  		  		<select name='billing_country' id='billing_country' class='country-select'>".$billing_countries."</select>		  		  		
  		  	</td>
  		  	<td id='shipping_data'>
  		  		<span class='td-text'>Shipping Address</span><br>
  		  		<input type='text' name='shipping_address_1' id='shipping_address_1' value='".$user_meta_info['shipping_address_1'][0]."'><br>
  		  		<input type='text' name='shipping_address_2' id='shipping_address_2' value='".$user_meta_info['shipping_address_2'][0]."'><br>
  		  		<input type='text' name='shipping_city' id='shipping_city' value='".$user_meta_info['shipping_city'][0]."'><br>
  		  		<input type='text' name='shipping_postcode' id='shipping_postcode' value='".$user_meta_info['shipping_postcode'][0]."'><br>
  		  		<select name='shipping_country' id='shipping_country' class='country-select'>".$shipping_countries."</select>
  		  	</td>
  		  </tr>
	  	</table>
	  </div>

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
		<div>
			<h1>Login Credentials</h1>
		</div>

		<div>
			<table class="add_form_table">
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Customer ID:</span>
	  		  	</td>
	  		  	<td>
	  		  		<input type="text" value="'.$customer_data->id.'" disabled>
	  		  	</td>
	  		  </tr>
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Registered Email:</span>
	  		  	</td>
	  		  	<td>	  		  		
	  		  		<input name="user_login" type="text" id="user_login" value="'.$user_info->data->user_email.'">
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
	  		  		<input name="user_pass" type="password" id="user_pass" value="'.$user_info->data->user_pass.'">
	  		  		<input type="button" value="Generate Password">
	  		  	</td>
	  		  </tr>
			</table>
		</div>
	';

	return $content;
}
?>