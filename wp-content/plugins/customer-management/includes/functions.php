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
			    $customer_orders = get_total_orders($customer->user_id);

				$user_info = get_user_meta($customer->user_id);
				if (!$user_info) continue;
				$content .='
					<tr>
						<td>'.$customer->id.'</td>
						<td>'.$user_info['first_name'][0].' '.$user_info['last_name'][0].'</td>
						<td>'.$customer->company.'</td>
						<td>'.$customer_orders['orders_count'].'</td>
						<td>$'.$customer_orders['orders_total'].'</td>
						<td class="user_actions column-user_actions">
						  <p>
							<a class="button tips edit" href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_info&customer_id='.$customer->id ).'" title="Customer Edit"></a>
						  </p>
						</td>
					</tr>
				';
							// <a class="button tips dashicons dashicons-migrate" title="Payment is overdue"></a>
			}
		}

	$content .='
			</tbody>
		</table>
	';
	return $content;
}

function DisplayGroup() {
	$group_data = get_group_row_data();
	$content = '
		<table class="widefat striped customer-group-list">
			<thead>
				<tr>
					<td style="width: 30%;">Group ID</td>
					<td>Group Name</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>';
	if (sizeof($group_data)>0) {
		foreach ($group_data as $row) {
			$content .= '<tr>
				<td>'.$row->id.'</td>
				<td>'.$row->group_name.'</td>
				<td class="user_actions column-user_actions">
				  <p><a class="button tips edit customer-content-edit" data-row-id="'.$row->id.'" data-type="group"></a></p>
				</td>
			</tr>';
		}
	} else {
		$content .= '<tr style="text-align:center;"><td colspan="3">No Results</td></tr>';
	}
	$content .='</tbody>
		</table>';
	return $content;
}

function DisplayPrice() {
	$price_data = get_price_row_data();
	$content = '
		<table class="widefat striped payment-list">
			<thead>
				<tr>
					<td style="width: 30%;">ID</td>
					<td>Price List Name</td>
					<td>Details</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>';
	if (sizeof($price_data)>0) {
		foreach ($price_data as $row) {
			if ($row->price_rule == 1){
				$detail = $row->price_percentage."% ";
				if ($row->select_rule == 1) {
					$detail .= "Markup Price";
				}else {
					$detail .= "Markdown Price";
				}
			} else {
				$detail = "Manual Price";
			}
			$content .= '<tr>
				<td>'.$row->id.'</td>
				<td>'.$row->price_name.'</td>
				<td>'.$detail.'</td>
				<td class="user_actions column-user_actions">
				  <p><a class="button tips edit customer-content-edit" data-row-id="'.$row->id.'" data-type="price"></a></p>
				</td>
			</tr>';
		}
	} else {
		$content .= '<tr style="text-align:center;"><td colspan="4">No Results</td></tr>';
	}
	$content .='</tbody>
		</table>';
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
	} else {
		$content .= '<tr style="text-align:center;"><td colspan="4">No Results</td></tr>';
	}
	$content .='</tbody>
		</table>';
	return $content;
}

/*
 *
 */
function add_customer() {
	$group_options = get_customer_group_options();
    $country_options = get_country_options("NZ");
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
  		  		<span class="td-text">Call Trading Name</span><br>
  		  		<input name="trading_name" type="text" id="trading_name" value="">
  		  	</td>  		  	
  		  </tr>
  		  <tr>
  		  	<td>
  		  		<span class="td-text">Tax Number</span><br>
  		  		<input name="tax_number" type="text" id="tax_number" value="">
  		  	</td>		  		  	
  		  	<td>
  		  		<span class="td-text">Phone</span><br>
  		  		<input name="phone" type="text" id="phone" value="">
  		  	</td>
  		  </tr>
  		  <tr>
  		  	<td colspan="2">
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

/*
 * Get customer group options
 */
function get_customer_group_options($selected_group=null) {

	$group_data = array();
	$group_data = get_group_row_data();

	if (sizeof($group_data) > 0) {
		$group_options = $selected = '';
		foreach ($group_data as $group) {
			$selected = $group->id ==$selected_group ? 'selected="selected"' : '';
			$group_options .= "<option value='".$group->id."' ".$selected.">".$group->group_name."</option>";
		}
	}else {
		$group_options = '<option value="" selected="selected">Select a group…</option>';
	}

	return $group_options;
}

/*
 * Get customer group options
 */
function get_price_options($selected_price=null) {

	$price_data = array();
	$price_data = get_price_row_data();

	if (sizeof($price_data) > 0) {
		$price_options = $selected = '';
		foreach ($price_data as $price) {
			$selected = $price->id ==$selected_price ? 'selected="selected"' : '';
			$price_options .= "<option value='".$price->id."' ".$selected.">".$price->price_name."</option>";
		}
	}else {
		$price_options = '<option value="" selected="selected">Select a price…</option>';
	}

	return $price_options;
}

/*
 * Get payment terms options
 */
function get_payment_terms_options($selected_terms=null) {

	$terms_data = array();
	$terms_data = get_payment_row_data();

	if (sizeof($terms_data) > 0) {
		$terms_options = $selected = '';
		foreach ($terms_data as $terms) {
			$selected = $terms->id ==$selected_terms ? 'selected="selected"' : '';
			$terms_options .= "<option value='".$terms->id."' ".$selected.">".$terms->terms_name."</option>";
		}
	}else {
		$terms_options = '<option value="" selected="selected">Select a payment terms…</option>';
	}

	return $terms_options;
}

/*
 * Get Week options
 */
function get_week_options($selected_week=1) {

	$week_data = array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
	$week_options = $selected = '';
	foreach ($week_data as $key=>$week_name) {
		$selected = $key ==$selected_week ? 'selected="selected"' : '';
		$week_options .= "<option value='".$key."' ".$selected.">".$week_name."</option>";
	}

	return $week_options;
}

function get_customer_info($customer_id) {
	$customer_data = get_customer_data($customer_id);
	$user_meta_info = get_user_meta($customer_data->user_id);
	$user_info = get_userdata($customer_data->user_id);

	$billing_countries = get_country_options($user_meta_info['billing_country'][0]);
	$shipping_countries = get_country_options($user_meta_info['shipping_country'][0]);
	$ct_type = $customer_data->customer_type == 'Retailer' ? array('checked', '') : array('','checked');

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
	  		<td>
  				<label style='padding-right: 20px; padding-left: 4px;'><input type='radio' name='customer_type' value='Retailer' ".$ct_type[0].">Retailer</label>
  				<label><input type='radio' name='customer_type' value='Business' ".$ct_type[1].">Business</label>
	  		</td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Customer Group:</span></td>
	  		<td><select id='group_id' name='group_id'>".get_customer_group_options($customer_data->group_id)."</select></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Company Name:</span></td>
	  		<td><input id='company' name='company' type='text' value='".$customer_data->company."'s></td>
	  	  </tr>
	  	  <tr>
	  		<td><span class='td-text'>Call Trading Name:</span></td>
	  		<td><input id='trading_name' name='trading_name' type='text' value='".$customer_data->trading_name."'s></td>
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
  		  <tr>
		  	<td colspan='2'>
		  		<span class='td-text'>Payment Terms</span><br>	  		  		
  		  		<select name='payment_terms' id='payment_terms'>".get_payment_terms_options($customer_data->payment_terms)."</select>
  		  	</td>
  		  </tr>
	  	</table>
	  </div>
	</form>
	";

	return $content;
}

function get_customer_transaction($customer_id, $start_date=null, $end_date=null, $order_search_box=null) {
	$transaction = get_transaction_body($customer_id, $start_date, $end_date, $order_search_box);

	$content = '
		<div style="height:65px;">
			<div style="float:left;">
				<h1>Transactions</h1>
			</div>
			<div style="float:right;margin-top: 10px;">
				<input type="text" name="order_start_date" id="order_start_date" class="order-date" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" placeholder="yyyy-mm-dd">
				<span>~</span>
				<input type="text" name="order_end_date" id="order_end_date" class="order-date" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" placeholder="yyyy-mm-dd">
				<input type="text" name="order_search" id="order_search">
				<input type="button" name="order_search_btn" id="order_search_btn" class="document-button" value="Search">
				<input type="hidden" name="order_customer_id" id="order_customer_id" value="'.$customer_id.'">
			</div>
		</div>
		<div>
			<table class="widefat striped transaction-table">
				<thead>
					<tr>
						<td>Order<br>Status</td>
						<td>Date</td>
						<td>Order<br>Number</td>
						<td>Delivery Address</td>
						<td>Note</td>
						<td>Shipping Method</td>
						<td>Order Account</td>
						<td>Balance Due</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody id="transaction_body">'.$transaction.'
				</tbody>
			</table>
		</div>
	';
	return $content;
}

function get_transaction_body($customer_id, $start_date=null, $end_date=null, $order_search_box=null) {

	$customer_data = get_customer_data($customer_id);
    $customer_orders = get_posts( array(
        'numberposts' => - 1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $customer_data->user_id,
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses()),
        'date_query' => array(
	        'relation' => 'AND',
	        array(
	            'after' => $start_date,
	            'inclusive' => true
	        ),
	        array(
	            'before' => $end_date,
	            'inclusive' => true
	        )
        )        
    ) );
	$transaction = '';
    if (sizeof($customer_orders)>0) {
	    foreach ( $customer_orders as $customer_order ) {
	        $order = wc_get_order( $customer_order );
	        $order_data = $order->get_data();
	        $order_status_class = "processing";
	        if ($order_data['status'] == "completed" || $order_data['status'] == "cancelled"){
	        	$order_status_class = $order_data['status'];
	        }
	        $shipping_address = "-";
	        $order_address = $order_data['shipping'];
	        if ($order_address['address_1'] || $order_address['address_2'] || $order_address['city'])
	        	$shipping_address = $order_address['address_1']." ".$order_address['address_2']."<br>".$order_address['city']."-".$order_address['postcode'];

	        if ($order_search_box && (strpos($shipping_address, $order_search_box)===false && strpos($order_data['number'], $order_search_box)===false && strpos($order_data['total'], $order_search_box)===false )) {
	        	continue;
	        }
    		$invoice_url = wp_nonce_url( admin_url( "admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids=" . $order_data['number'] ), 'generate_wpo_wcpdf' );

	        $transaction .= '<tr id="post_'.$order_data['id'].'">
	        	<td class="order_status column-order_status">
	        		<mark class="'.$order_status_class.' tips" title="'.ucfirst($order_status_class).'"></mark>
	        	</td>
	        	<td>'.date('F d, Y',strtotime($order_data['date_created'])).'</td>
	        	<td>'.$order_data['number'].'</td>
	        	<td>'.$shipping_address.'</td>
	        	<td>'.$order_data['customer_note'].'</td>
	        	<td></td>
	        	<td>$'.$order_data['total'].'</td>
	        	<td></td>
				<td class="user_actions column-user_actions">
				  <p>
					<a class="button tips dashicons dashicons-visibility" title="View Order" href="'.admin_url("post.php?post=".$customer_order->ID."&action=edit").'"></a>
					<a class="button tips dashicons pdf-invoice-img" title="PDF Invoice" href="'.$invoice_url.'"></a>
				  </p>
				</td>	        
	        </tr>';
					// <a class="button tips dashicons dashicons-migrate" title="Send an email"></a>

	    }
    }
    if (!$transaction) {
    	$transaction = '<tr style="text-align:center;"><td colspan="9">No Results</td></tr>';
    }

	return $transaction;	
}

function get_customer_price($customer_id) {
	$customer_data = get_customer_data($customer_id);
	$customer_group = get_group_row_data($customer_data->group_id);
	$content = '
	  <form method="post" class="customer-edit-data" action="" enctype="multipart/form-data">
		<div>
			<h1>Price List</h1>
			<table class="add_form_table">
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Customer Type</span>
	  		  	</td>
	  		  	<td>
	  		  		<input type="text" value="'.$customer_data->customer_type.'" disabled>
	  		  		<input type="hidden" name="customer_id" id="customer_id" value="'.$customer_id.'">
	  		  		<input type="hidden" name="main_tab" id="main_tab" value="customer_price">
	  		  	</td>
	  		  </tr>
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Customer Group</span>
	  		  	</td>
	  		  	<td>	  		  		
	  		  		<input type="text" value="'.$customer_group->group_name.'" disabled>
	  		  	</td>
	  		  </tr>
	  		  <tr>
	  		  	<td>
	  		  		<span class="td-text">Price List Name</span>
	  		  	</td>
			  	<td>
			  		<select name="price_id" id="price_id">'.get_price_options($customer_data->price_id).'</select>
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


function get_customer_delivery($customer_id) {
	$customer_info = get_customer_data($customer_id);
	$group_id = $customer_info->group_id;

	$group_data = $cut_time_off = $delivery_charge = array();
	if ($group_id) {
		$group_data = get_group_row_data($group_id);
		$cut_time_off = get_cut_time_row_data($group_id);

		if ($group_data->delivery_charge) {
			$delivery_charge[$group_data->delivery_charge] = 'checked';
		} else {
			$delivery_charge[0] = 'checked';
		}
		$delivery_cut_time = $group_data->cut_off_time == 1 ? array('','checked') : array('checked','');
		
	}

	$week_body = '';

	for ($i=1; $i<=7 ; $i++) {
		$cut_time = $cut_time_off->{'cut_time_'.$i};
		$chk_status = $cut_time ? 'checked' : '';
		$week_select = array('selected','');
		if ($cut_time >= 12) {
			$cut_time -= 12;
			$week_select = array('','selected');
		}
		$delivery_day = $cut_time_off->{'delivery_day_'.$i};

		$week_body .= '<tr>
			<td style="width:25px;"><input type="checkbox" name="week_status_'.$i.'" id="week_status_'.$i.'" '.$chk_status.'></td>
			<td><select disabled style="width:110px">'.get_week_options($i).'</select></td>
			<td><input type="text" name="cut_time_'.$i.'" id="cut_time_'.$i.'" style="width:45px;" value="'.$cut_time.'"></td>
			<td><select name="week_select_'.$i.'" id="week_select_'.$i.'" style="width:50px;">
					<option value="0" '.$week_select[0].'>AM</option>
					<option value="1" '.$week_select[1].'>PM</option>
				</select>
			</td>
			<td><select name="delivery_day_'.$i.'" id="delivery_day_'.$i.'" style="width:110px">'.get_week_options($delivery_day).'</select></td>
		</tr>';
	}

	$content = '
		<div>
			<h1>Delivery Agreement</h1>
		</div>
		<div>
		  <form method="post" id="customer_content_data" action="" enctype="multipart/form-data">
		    <div>
				<table class="group-table" style="padding-top: 25px;">
				  <tr>
				  	<td>
				  		<span class="td-text">Delivery Method</span>
				  	</td>
				  	<td>
				  		<select name="delivery_method" id="delivery_method">
				  			<option value="0" '.$delivery_opt[0].'>Local Delivery</option>
				  			<option value="1" '.$delivery_opt[1].'>Local Courier</option>
				  		</select>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td style="position: relative;">
				  		<span class="td-text" style="position: absolute;top: 0px;">Delivery Days</span>
				  	</td>
				  	<td>
				  		<input type="number" name="delivery_days" id="delivery_days" min="0" max="7" style="width: 60px;" value="'.$group_data->delivery_days.'">
				  		<span style="color: #b7b7b7;">Days in Week</span><br>
				  		<span style="color: #b7b7b7;">Except Weekend and Public Holiday</span>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td style="position: relative;">
				  		<span class="td-text" style="position: absolute;top: 0px;">Delivery Charge</span>
				  	</td>
				  	<td>
				  		<input type="radio" name="delivery_charge" value="0" '.$delivery_charge[0].'>
				  		<select disabled style="width:125px;"><option>Price Included</option></select><br><br>
				  		<input type="radio" name="delivery_charge" value="1" '.$delivery_charge[1].'>
				  		<select disabled style="width:80px;"><option>Flat Fee</option></select>
				  		<input type="text" name="flat_fee" id="flat_fee" value="'.$group_data->flat_fee.'" placeholder="$">
				  		<span style="text-decoration:underline;">Enter Price</span><br><br>			  		
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td style="position: relative;">
				  		<span class="td-text" style="position: absolute;top: 0px;">Delivery Cut Off Time</span>
				  	</td>
				  	<td>
				  		<input type="radio" name="cut_off_time" class="cut-off-time" value="0" '.$delivery_cut_time[0].'>None Cut Off Time<br><br>
				  		<input type="radio" name="cut_off_time" class="cut-off-time" value="1" '.$delivery_cut_time[1].'>Select Days and Cut Off Time
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td></td>
				  	<td>
				  		<table class="group-table">
				  		  <thead>
				  		  	<tr>
				  		  		<td colspan="2">Order Day</td>
				  		  		<td colspan="2">Cut Off Time</td>
				  		  		<td>Delivery Day</td>
				  		  	</tr>
				  		  </thead>
				  		  <tbody>'.$week_body.'</tbody>
				  		</table>
  	  					<input type="hidden" name="customer_row_id" id="customer_row_id" value="'.$row_id.'">
				  	</td>			  	
				  </tr>			  
				</table>
			</div>			
		  </form>
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
	  		  		<a href="/my-account/lost-password/" class="generate-password">Generate Password</a>
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
/*
 * Display Add New Price
 */
function get_price_content($price_id=null) {
	$price_data = array();
	$page_title = "Add New Price";
	if ($price_id){
		$page_title = "Price List";
		$price_data = get_price_row_data($price_id);
	}
	$product_prices = get_product_prices($price_id);

	// Price Rule Checked
	$price_rule_checked = array();
	if ($price_data[0]->price_rule == 1 ) {
		$price_rule_checked[0] = "checked";
	} else {
		$price_rule_checked[1] = "checked";
	}
	// Select Rule Selected
	$select_rule_checked = array();
	if ($price_data[0]->select_rule == 1 ) {
		$select_rule_checked[0] = "selected";
	} else {
		$select_rule_checked[1] = "selected";
	}

	if (!$price_id) {
		$price_rule_checked = array("checked","");
		$select_rule_checked[0] = "selected";
		$select_rule_checked[1] = "";
	}
	
	// Number Round Checked
	$number_round_checked = $price_data[0]->number_round == 1 ? "checked" : "";

	$args = array(
	  'post_type'   => 'product',
	  'posts_per_page' => -1,
	  'orderby'     => array('date'=>'DESC','title'=>'ASC')
	);
	$post_data = get_posts( $args );
	$product_content .= '
		<table class="widefat striped product-table">
		  <thead>
		  	<tr>
		  	  <td>SKU</td>
		  	  <td>Product Name</td>
		  	  <td>Categories</td>
		  	  <td>Stock Status</td>
		  	  <td>Regular Price</td>
		  	  <td>New Price</td>
		  	</tr>
		  </thead>
		  <tbody>
	';
	if (sizeof($post_data)>0) {
		foreach ($post_data as $post) {
			$product = wc_get_product($post->ID);
			$product_row = $product->data;
			$in_stock = $product->is_in_stock() ? 'In Stock' : '';
			$product_content .= '
				<tr>
				  <td>'.$product->get_sku().'</td>
				  <td>'.$post->post_title.'</td>
				  <td>'.$product->get_categories( ', ',  _n( '', 'Category:', sizeof( get_the_terms( $post->ID, 'product_cat' ) ), 'woocommerce' ) . ' ', '' ).'</td>
				  <td>'.$in_stock.'</td>
				  <td>'.$product->get_regular_price().'</td>
				  <td style="padding-bottom: 0px;"><input type="text" name="post_'.$post->ID.'" id="post_'.$post->ID.'" style="width:70px;" value="'.$product_prices[$post->ID].'"></td>
				</tr>
			';
		}
	}else {
		$product_content .= '<tr style="text-align:center;"><td colspan="6">No Results</td></tr>';
	}
	$product_content .= '</tbody></table>';

	$content .= '
		<div style="height:65px;">
			<div style="float:left;">
				<h1>'.$page_title.'</h1>
			</div>
		</div>
		<div>
		  <form method="post" id="customer_content_data" action="" enctype="multipart/form-data">
			<table class="price-table">
			  <tr>
			  	<td>
			  		<span class="td-text">Name</span>
			  	</td>
			  	<td>
			  		<input type="text" name="price_name" id="price_name" value="'.$price_data[0]->price_name.'">
			  	</td>			  	
			  </tr>
			  <tr>
			  	<td style="padding-bottom:50px;">
			  	  <span class="td-text">Price Rule</span>
			  	</td>
			  	<td>
			  	  <input type="radio" name="price_rule" class="price-rule" value="1" '.$price_rule_checked[0].'> Markup OR Markdown the item rates by an percentage.<br><br>
			  	  <input type="radio" name="price_rule" class="price-rule" value="0" '.$price_rule_checked[1].'> Enter a price manually for each item
			  	</td>
			  </tr>
			  <tr>
			  	<td style="padding-bottom:50px;">
			  	  <span class="td-text">Percentage</span>
			  	</td>			  
			  	<td>
			  	  <select name="select_rule" id="select_rule">
			  	  	<option value="1" '.$select_rule_checked[0].'>MarkUp %</option>
			  	  	<option value="0" '.$select_rule_checked[1].'>MarkDown %</option>
			  	  </select>
			  	  <input type="number" name="price_percentage" id="price_percentage" min="0" style="width: 60px;" value="'.$price_data[0]->price_percentage.'"> %<br>
			  	  <input type="checkbox" name="number_round" id="number_round" '.$number_round_checked.'> Round off to nearest whole number
			  	</td>
	  	  	  </tr>
			  <tr id="product_list">
			  	<td>
			  	  <span class="td-text">Enter New Price</span>
			  	</td>
			  	<td>'.$product_content.'</td>
			  </tr>
			  <tr>
			  	<td colspan="2">
		  	  		<input type="hidden" name="customer_row_id" id="customer_row_id" value="'.$price_id.'">
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

/*
 * Display Add New Group
 */
function get_group_content($row_id=null) {
	$group_data = $cut_time_off = array();
	$page_title = "Add New Group";
	if ($row_id){
		$page_title = "Update Group";
		$group_data = get_group_row_data($row_id);
		$cut_time_off = get_cut_time_row_data($row_id);
	}

	$payment_opt = $group_data->payment_method == 1 ? array('','selected') : array('selected','');
	$delivery_opt = $group_data->delivery_method == 1 ? array('','selected') : array('selected','');
	$delivery_charge = array();
	if ($group_data->delivery_charge) {
		$delivery_charge[$group_data->delivery_charge] = 'checked';
	} else {
		$delivery_charge[0] = 'checked';
	}

	$week_body = '';
	$delivery_cut_time = $group_data->cut_off_time == 1 ? array('','checked') : array('checked','');

	for ($i=1; $i<=7 ; $i++) {
		$cut_time = $cut_time_off->{'cut_time_'.$i};
		$chk_status = $cut_time ? 'checked' : '';
		$week_select = array('selected','');
		if ($cut_time >= 12) {
			$cut_time -= 12;
			$week_select = array('','selected');
		}
		$delivery_day = $cut_time_off->{'delivery_day_'.$i};

		$week_body .= '<tr>
			<td style="width:25px;"><input type="checkbox" name="week_status_'.$i.'" id="week_status_'.$i.'" '.$chk_status.'></td>
			<td><select disabled style="width:110px">'.get_week_options($i).'</select></td>
			<td><input type="text" name="cut_time_'.$i.'" id="cut_time_'.$i.'" style="width:45px;" value="'.$cut_time.'"></td>
			<td><select name="week_select_'.$i.'" id="week_select_'.$i.'" style="width:50px;">
					<option value="0" '.$week_select[0].'>AM</option>
					<option value="1" '.$week_select[1].'>PM</option>
				</select>
			</td>
			<td><select name="delivery_day_'.$i.'" id="delivery_day_'.$i.'" style="width:110px">'.get_week_options($delivery_day).'</select></td>
		</tr>';
	}

	$content .= '
		<div style="height:65px;">
			<div style="float:left;">
				<h1>'.$page_title.'</h1>
			</div>
		</div>
		<div>
		  <form method="post" id="customer_content_data" action="" enctype="multipart/form-data">
		    <div style="float:left;">
				<table class="group-table">
				  <tr>
				  	<td style="padding-bottom: 50px;">
				  		<span class="td-text">Group Name</span>
				  	</td>
				  	<td style="padding-bottom: 50px;">
				  		<input type="text" name="group_name" id="group_name" value="'.$group_data->group_name.'">
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td colspan="2">
				  		<span class="td-text">Default Setting for Group</span>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td>
				  		<span class="td-text">Price List</span>
				  	</td>
				  	<td>
				  		<select name="price_id" id="price_id">'.get_price_options($group_data->price_id).'</select>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td>
				  		<span class="td-text">Payment Method</span>
				  	</td>
				  	<td>
				  		<select name="payment_method" id="payment_method">
				  			<option value="0" '.$payment_opt[0].'>On Account</option>
				  			<option value="1" '.$payment_opt[1].'>Credit Card</option>
				  		</select>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td>
				  		<span class="td-text">Payment Terms</span>
				  	</td>
				  	<td>
				  		<select name="payment_terms" id="payment_terms">'.get_payment_terms_options($group_data->payment_terms).'</select>
				  	</td>			  	
				  </tr>
				</table>
			</div>
		    <div>
				<table class="group-table" style="padding-left: 100px;">
				  <tr>
				  	<td>
				  		<span class="td-text">Delivery Method</span>
				  	</td>
				  	<td>
				  		<select name="delivery_method" id="delivery_method">
				  			<option value="0" '.$delivery_opt[0].'>Local Delivery</option>
				  			<option value="1" '.$delivery_opt[1].'>Local Courier</option>
				  		</select>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td style="position: relative;">
				  		<span class="td-text" style="position: absolute;top: 0px;">Delivery Days</span>
				  	</td>
				  	<td>
				  		<input type="number" name="delivery_days" id="delivery_days" min="0" max="7" style="width: 60px;" value="'.$group_data->delivery_days.'">
				  		<span style="color: #b7b7b7;">Days in Week</span><br>
				  		<span style="color: #b7b7b7;">Except Weekend and Public Holiday</span>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td style="position: relative;">
				  		<span class="td-text" style="position: absolute;top: 0px;">Delivery Charge</span>
				  	</td>
				  	<td>
				  		<input type="radio" name="delivery_charge" value="0" '.$delivery_charge[0].'>
				  		<select disabled style="width:125px;"><option>Price Included</option></select><br><br>
				  		<input type="radio" name="delivery_charge" value="1" '.$delivery_charge[1].'>
				  		<select disabled style="width:80px;"><option>Flat Fee</option></select>
				  		<input type="text" name="flat_fee" id="flat_fee" value="'.$group_data->flat_fee.'" placeholder="$">
				  		<span style="text-decoration:underline;">Enter Price</span><br><br>
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td style="position: relative;">
				  		<span class="td-text" style="position: absolute;top: 0px;">Delivery Cut Off Time</span>
				  	</td>
				  	<td>
				  		<input type="radio" name="cut_off_time" class="cut-off-time" value="0" '.$delivery_cut_time[0].'>None Cut Off Time<br><br>
				  		<input type="radio" name="cut_off_time" class="cut-off-time" value="1" '.$delivery_cut_time[1].'>Select Days and Cut Off Time
				  	</td>			  	
				  </tr>
				  <tr>
				  	<td></td>
				  	<td>
				  		<table id="cut_time_table" class="group-table">
				  		  <thead>
				  		  	<tr>
				  		  		<td colspan="2">Order Day</td>
				  		  		<td colspan="2">Cut Off Time</td>
				  		  		<td>Delivery Day</td>
				  		  	</tr>
				  		  </thead>
				  		  <tbody>'.$week_body.'</tbody>
				  		</table>
  	  					<input type="hidden" name="customer_row_id" id="customer_row_id" value="'.$row_id.'">
				  	</td>			  	
				  </tr>			  
				</table>
			</div>			
		  </form>
		</div>
		<div>
  	  		<input type="button" name="customer_cancel_btn" id="customer_cancel_btn" class="document-button" value="Cancel">
  	  		<input type="submit" name="customer_save_btn" id="customer_save_btn" class="document-button customer-save-btn" value="Save">
		</div>		
	';			  		

	return $content;
}
function get_total_orders($user_id) {

	$result = array('orders_count'=>0, 'orders_total'=>0);

    $customer_orders = get_posts( array(
        'numberposts' => - 1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses())      
    ) );

    if (sizeof($customer_orders)>0) {
    	$result['orders_count'] = count($customer_orders);
    	$result['orders_total'] = 0;
	    foreach ( $customer_orders as $customer_order ) {
	        $order = wc_get_order( $customer_order );
	        $order_data = $order->get_data();
	        if ($order_data['status'] != "cancelled") {
	        	$result['orders_total'] += $order_data['total'];
	        }
	    }
    }

	return $result;	
}
?>