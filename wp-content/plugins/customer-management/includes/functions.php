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

function get_customer_info($customer_id) {
	$customer_data = get_customer_data($customer_id);
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

function get_customer_transaction($customer_id) {
	$content = '
		<div>
			<h1>Transactions</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}


function get_customer_price($customer_id) {
	$content = '
		<div>
			<h1>Price List</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}


function get_customer_delivery($customer_id) {
	$content = '
		<div>
			<h1>Delivery Agreement</h1>
		</div>
		<div>

		</div>
	';
	return $content;
}

function get_customer_doc($customer_id) {
	$content = '
		<div style="height:65px;">
			<div style="float:left;">
				<h1>Documents</h1>
			</div>
			<div style="float:right;margin-top: 10px;">
				<input type="text" name="search_box" id="search_box">
				<input type="button" name="search_btn" id="search_btn" class="document-button" value="Search">
				<input type="button" name="doc_btn" id="doc_btn" class="document-button" value="Add New Doc">
			</div>
		</div>
		<div>
			<table class="widefat striped doc-table">
				<thead>
					<tr>
						<td>Upload Date</td>
						<td>Document Name</td>
						<td>File</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody id="doc_body">'.get_doc_body($customer_id).'
				</tbody>
			</table>
		</div>
		<div class="popup_background"></div>
		<div id="popup_form">
		  <h1>Upload New Documents</h1>
		  <form id="doc_form" method="post" enctype="multipart/form-data">
			<input type="hidden" name="customer_id" id="customer_id" value="'.$customer_id.'">
		  	<table>
		  	  <tr>
	  			<td><span class="td-text">Document Name:</span></td>
	  			<td><input type="text" id="doc_name" name="doc_name"></td>
		  	  </tr>
		  	  <tr>
		  		<td><span class="td-text">Upload Document:</span></td>
		  		<td>
		  			<input type="text" id="file_path" name="file_path" disabled>
			  		<div class="file-wrapper">
						<input type="file" id="doc_file" name="doc_file" accept=".doc, .docx, .pdf" onchange="select_file(this.files);">
						<span class="button">Upload</span>
					</div>
				</td>
		  	  </tr>
		  	  <tr style="text-align:center;">
		  	  	<td colspan="2">
		  	  		<input type="button" name="doc_cancel_btn" id="doc_cancel_btn" class="document-button" value="Cancel">
		  	  		<input type="submit" name="doc_save_btn" id="doc_save_btn" class="document-button customer-save-btn" value="Save">
		  	  	</td>
		  	  </tr>
		  	</table>
		  </form>
		</div>
	';
	return $content;
}

function get_customer_login($customer_id) {

	$customer_data = get_customer_data($customer_id);
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


?>