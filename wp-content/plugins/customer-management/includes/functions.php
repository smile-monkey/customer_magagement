<?php
/**
 * @package Functions for Customer_Management
 * @version 1.0
 * @author Smile
 */

function DisplayCustomer() {
	$customer_list = get_customer_list();
	$content = '
		<table class="widefat striped customer-table">
			<thead>
				<tr>
					<td>Customer ID</td>
					<td>Name (Last, First)</td>
					<td>Company</td>
					<td>Total Orders</td>
					<td>Amount Due</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>';
		if (sizeof($customer_list)>0) {
			foreach ($customer_list as $customer) {
				$user_info = get_user_meta($customer->user_id);
				if (!$user_info) continue;
				$content .='
					<tr>
						<td>'.$customer->user_id.'</td>
						<td>'.$user_info['first_name'][0].' '.$user_info['last_name'][0].'</td>
						<td>'.$customer->company.'</td>
						<td>0</td>
						<td>$0.00</td>
						<td class="user_actions column-user_actions">
						  <p>
							<a class="button tips edit" href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_info&customer_id='.$customer->id ).'">Edit</a>
							<a class="button tips dashicons dashicons-migrate" title="Payment is overdue"></a>
						  </p>
						</td>
					</tr>
				';
			}
		}

	$content .='
			</tbody>
		</table>
	';
	return $content;
}

function DisplayPayment() {
	$payment_data = get_payment_row_data();
	$content = '
		<table class="widefat striped payment-list">
			<thead>
				<tr>
					<td style="width: 30%;">Payment Terms ID</td>
					<td>Terms Name</td>
					<td>Due in Days</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>';
	if (sizeof($payment_data)>0) {
		foreach ($payment_data as $row) {
			$content .= '<tr>
				<td>'.$row->id.'</td>
				<td>'.$row->terms_name.'</td>
				<td>'.$row->due_in_days.'</td>
				<td class="user_actions column-user_actions">
				  <p><a class="button tips edit customer-content-edit" data-row-id="'.$row->id.'" data-type="payment"></a></p>
				</td>
			</tr>';
		}
	}
	$content .='</tbody>
		</table>';
	return $content;
}

function DisplayGroup() {
	$content = '
		<table class="widefat striped group-table">
			<thead>
				<tr>
					<td>Group ID</td>
					<td>Name (Last, First)</td>
					<td>Company</td>
					<td>Total Orders</td>
					<td>Amount Due</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>123456</td>
					<td>Kinjal Patel Director</td>
					<td>Hi-TECH LIMITED</td>
					<td>0</td>
					<td>$0.00</td>
					<td class="user_actions column-user_actions">
					  <p>
						<a class="button tips edit" href="#">Edit</a>
					  </p>
					</td>
				</tr>
				<tr>
					<td>56789</td>
					<td>Frank Firely Director</td>
					<td>COCA COLA LIMITED</td>
					<td>0</td>
					<td>$0.00</td>
					<td class="user_actions column-user_actions">
					  <p>
						<a class="button tips edit" href="#">Edit</a>
					  </p>
					</td>
				</tr>
			</tbody>
		</table>
	';
	return $content;
}

/*
 *
 */
function add_customer() {
	$group_options = get_group_options();
    $country_options = get_country_options("US");
?>
  <div class="add-header">
  	<img src="<?php echo plugins_url( '../assets/images/customer-icon.png' , __FILE__ );?>">
  	<h1>Add New Customer</h1>
  </div>
  <div class="add-content">
  	<form id="add_form" method="post" action="" enctype="multipart/form-data" autocomplete="on">
  		<table class="add_form_table">
  		  <tr>
				<td colspan="2">
  				<span class="td-text">Customer Type*</span>
  				<label for="Retailer" style="padding-right: 20px; padding-left: 4px;"><input type="radio" name="customer_type" value="Retailer" checked>Retail Customer</label>
  				<label for="Business"><input type="radio" name="customer_type" value="Business">Business Customer</label>
				</td>
  		  </tr>
  		  <tr>
  		  	<td colspan="2">
  		  		<span class="td-text">Customer Group</span>
				<select id="group_id" name="group_id"><?php echo $group_options;?></select> 		
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td colspan="2">
  		  		<span class="td-text" style="float: left;">Account Status</span>
  		  		<span style="float: left;margin-top: -5px;">
	  		  		<input type="checkbox" class="multi-switch" initial-value="0" unchecked-value="2" checked-value="1" value="0" name="account_status" id="account_status" />
  		  		</span>
  		  		<input type="hidden" name="user_status" id="user_status" value="0">
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td>
  		  		<span class="td-text">First Name</span><br>
  		  		<input name="first_name" type="text" id="first_name" value="">
  		  	</td>
  		  	<td>
  		  		<span class="td-text">Last Name</span><br>
  		  		<input name="last_name" type="text" id="last_name" value="">
  		  	</td>		  		  	
  		  </tr>
  		  <tr>
  		  	<td>
  		  		<span class="td-text">Username</span><br>
  		  		<input name="user_login" type="text" id="user_login" value="">
  		  	</td>
  		  	<td>
  		  		<span class="td-text">Password</span><br>
  		  		<input name="user_pass" type="password" id="user_pass" value="">
  		  	</td>		  		  	
  		  </tr>		  		  
  		  <tr>
  		  	<td>
  		  		<span class="td-text">Company Name</span><br>
  		  		<input name="company" type="text" id="company" value="">
  		  	</td>
  		  	<td>
  		  		<span class="td-text">Tax Number</span><br>
  		  		<input name="tax_number" type="text" id="tax_number" value="">
  		  	</td>		  		  	
  		  </tr>
  		  <tr>
  		  	<td>
  		  		<span class="td-text">Phone</span><br>
  		  		<input name="phone" type="text" id="phone" value="">
  		  	</td>
  		  	<td>
  		  		<span class="td-text">Mobile</span><br>
  		  		<input name="mobile" type="text" id="mobile" value="">
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td colspan="2">
  		  		<span class="td-text">Email Address</span><br>
  		  		<input name="user_email" type="email" id="user_email" value="" style="width: calc(100% - 70px);" autocomplete="off">
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td colspan="2">
  		  		<input name="shipping_chk_box" type="checkbox" id="shipping_chk_box" value="">
  		  		<span class="td-text">Is Shipping address as same as Billing Address</span>
  		  		<input type="hidden" name="shipping_check" id="shipping_check" value="0">
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td>
  		  		<span class="td-text" id="billing_title">Billing Address</span><br>
  		  		<input type="text" name="billing_address_1" id="billing_address_1" placeholder="Street Name"><br>
  		  		<input type="text" name="billing_city" id="billing_city" placeholder="Suburb"><br>
  		  		<input type="text" name="billing_state" id="billing_state" placeholder="State / Province"><br>
  		  		<input type="text" name="billing_postcode" id="billing_postcode" placeholder="Postal Code / Zip Code"><br>
  		  		<select name="billing_country" id="billing_country" class="country-select"><?php echo $country_options;?></select>		  		  		
  		  	</td>
  		  	<td id="shipping_data">
  		  		<span class="td-text">Shipping Address</span><br>
  		  		<input type="text" name="shipping_address_1" id="shipping_address_1" placeholder="Street Name"><br>
  		  		<input type="text" name="shipping_city" id="shipping_city" placeholder="Suburb"><br>
  		  		<input type="text" name="shipping_state" id="shipping_state" placeholder="State / Province"><br>
  		  		<input type="text" name="shipping_postcode" id="shipping_postcode" placeholder="Postal Code / Zip Code"><br>
  		  		<select name="shipping_country" id="shipping_country" class="country-select"><?php echo $country_options;?></select>
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td>
  		  		<input type="button" name="cancel_btn" id="cancel_btn" class="customer-button" value="Cancel"/>
  		  	</td>
  		  	<td>
  		  		<input type="submit" name="save_new_btn" id="save_new_btn" class="customer-button" value="Save"/>
  		  	</td>
  		  </tr>		  		  
  		</table>
  	</form>
  </div>
<?php
}

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

function get_payment_content($terms_id=null) {
	$payment_data = array();
	if ($terms_id)
		$payment_data = get_payment_row_data($terms_id);
	$content .= '
		<div style="height:65px;">
			<div style="float:left;">
				<h1>Payment Terms</h1>
			</div>
		</div>
		<div>
		  <form method="post" id="customer_content_data" action="" enctype="multipart/form-data">
			<table class="payment-table">
			  <tr>
			  	<td>
			  		<span class="td-text">Terms Name</span>
			  	</td>
			  	<td>
			  		<span class="td-text">Due in Days</span>
			  	</td>			  	
			  </tr>
			  <tr>
			  	<td>
			  	  <input type="text" name="terms_name" id="terms_name" value="'.$payment_data[0]->terms_name.'">
			  	</td>
			  	<td>
			  	  <input type="number" name="due_in_days" id="due_in_days" min="0" style="width: 60px;" value="'.$payment_data[0]->due_in_days.'">
			  	  <span style="color: #b7b7b7;">Days in due of Invoice Date</span>
			  	</td>
			  </tr>
			  <tr>
			  	<td colspan="2">
		  	  		<input type="hidden" name="customer_row_id" id="customer_row_id" value="'.$terms_id.'">
		  	  		<input type="button" name="customer_cancel_btn" id="customer_cancel_btn" class="document-button" value="Cancel">
		  	  		<input type="submit" name="customer_save_btn" id="customer_save_btn" class="document-button customer-save-btn" value="Save">
		  	  	</td>
	  	  	  </tr>
			</table>
		  </form>
		</div>
	';

	return $content;
}
?>