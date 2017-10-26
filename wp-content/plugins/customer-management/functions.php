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
	$content = "
	  <div>
	  	<table>
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
	  		<td><input id='company' name='company' type='text' value='".$customer_data->company."' disabled></td>
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
	  		<td><input id='company' name='company' type='text' value='".$customer_data->mobile."' disabled></td>
	  	  </tr>	
	  	  <tr>
	  		<td><span class='td-text'>Email Address:</span>
	  		<td><input id='company' name='company' type='text' value='".$user_info->data->user_email."' disabled></td>
	  	  </tr>	  	  
	  	  <tr>
	  		<td><span class='td-text'>Account Status:</span>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->user_status."' disabled></td>
	  	  </tr>		  	    	  	  	  	  	  
	  	</ul>
	  </div>

	  <div>

	  </div>

	";

	return $content;
}
?>