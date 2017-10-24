<?php 
/**
 * @package Customer_Management
 * @version 1.0
 * @author Smile
 */
/* Plugin Name:Customer Management

* Plugin URI: https://github.com/smile-monkey/

* Description:A Customer Management plugin is a customer management plugin for Customers where we can differentiate our customers, standard customer as a client and wholesale customers as re-seller with price control base on their customer type.

* Version: 1.0

* Author: Smile

* Author URI: https://github.com/smile-monkey/

* License: 

* Text Domain:Customer_Management

* License URI: https://github.com/smile-monkey/

*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'PLUGINURL', plugin_dir_url( __FILE__ ) );

include_once (__DIR__ . '/functions.php');

if (!class_exists(Customer_Management)){
	/**
	* 
	*/
	class Customer_Management
	{
		
		function __construct()
		{
			/**
			 * Add sub menu page to menu
			 */
			add_action('admin_menu', array(&$this, 'Customer_Management_Menu'));

			/**
			 * Load CSS and JS files
			 */
			add_action('admin_init', array(&$this, 'Customer_Management_Init'));
			// add_action( 'admin_enqueue_scripts', array(&$this, 'customer_enqueue' ));
			
			/**
			 * Ajax define
			 */
			add_action( 'wp_ajax_show_list', array(&$this,'show_list'));

		}

		/**
		 * Display Customer Management Plugin Menu
		 */
		function Customer_Management_Menu() {
		    add_menu_page(
		        __( 'Customer_Management', 'textdomain' ),
		        __( 'Customer Management', 'CustomerManagement' ),
		        'manage_options',
		        'customer_management',
		        array( &$this, 'Customer_management_Main' ),
		        PLUGINURL.'assets/images/menu-icon.png',
		        56
		    );			
		}

		function customer_enqueue() {
			wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
		}
		
		function Customer_Management_Init() {
			// wp_enqueue_script( 'jquery-1-12-1-js', PLUGINURL.'assets/js/jquery-1.12.1.min.js');
			wp_enqueue_style( 'multiSwith-css', PLUGINURL.'assets/css/multi-switch.min.css');
			wp_enqueue_script( 'multiSwith-js', PLUGINURL.'assets/js/multi-switch.js');

			wp_enqueue_script( 'customerManagement-js', PLUGINURL.'assets/js/customer.min.js');
			wp_enqueue_style( 'customerManagement-css', PLUGINURL.'assets/css/customer.min.css');

		}


		function Customer_management_Main() {
		?>
		  <div class="customer-body">
			<div class="customer-main">
				<h1 class="main-title"><?php _e( 'Customer Management', 'customer_management_title' ); ?></h1>
				<select id="customer_select" name="customer_select">
					<option value="0" selected=true >--Add New--</option>
					<option value="Customer">Customer</option>
					<option value="Group">Group</option>
				</select>
			</div>
			<div class="customer-list">
				<ul>
					<li id="customer_list" name="customer_list">Customer List</li>
					<li id="group_list" name="group_list">Group List</li>
					<li id="price_list" name="price_list">Price List</li>
					<li id="payment_list" name="payment_list">Payment Terms</li>
				</ul>
			</div>
			<div id="main_content" name="main_content">				
			</div>
		  </div>
		  <div class="customer-add">
		  	<?php $this->add_customer();?>
		  </div>
		  <div class="group-add">
		  	<?php $this->add_group();?>
		  </div>		  
		  <div class="customer-edit">
		  </div>		  
		<?php
		}

		function show_list() {
			$content = '';
			$list_id = $_POST['list_id'];
			switch ($list_id) {
				case 'group_list':
					$content = $this->DisplayGroup();
					break;
				case 'price_list':
					$content = $this->DisplayCustomer();
					break;
				case 'payment_list':
					$content = $this->DisplayGroup();
					break;					
				default:
					$content = $this->DisplayCustomer();
					break;
			}
			echo $content;
			die();
		}

		function DisplayCustomer() {
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
					<tbody>
						<tr>
							<td>123456</td>
							<td>Kinjal Patel Director</td>
							<td>Hi-TECH LIMITED</td>
							<td>115</td>
							<td>$2340.00</td>
							<td>Action</td>
						</tr>
						<tr>
							<td>56789</td>
							<td>Frank Firely Director</td>
							<td>COCA COLA LIMITED</td>
							<td>115</td>
							<td>$2340.00</td>
							<td>Action</td>
						</tr>
					</tbody>
				</table>
			';
			return $content;
		}

		function DisplayGroup() {
			$content = '
				<table class="widefat striped customer-table">
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
							<td>3453</td>
							<td>Kinjal Patel Director</td>
							<td>Hi-TECH LIMITED</td>
							<td>35</td>
							<td>$723.00</td>
							<td>Action</td>
						</tr>
						<tr>
							<td>63634</td>
							<td>Frank Firely Director</td>
							<td>COCA COLA LIMITED</td>
							<td>45</td>
							<td>$240.00</td>
							<td>Action</td>
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
		  	<img src="<?php echo plugins_url( '/assets/images/customer-icon.png' , __FILE__ );?>">
		  	<h1>Add New Customer</h1>
		  </div>
		  <div class="add-content">
		  	<form id="add_form">
		  		<table class="add_form_table">
		  		  <tr>
	  				<td colspan="2">
		  				<span class="td-text">Customer Type*</span>
		  				<label for="Retailer" style="padding-right: 20px; padding-left: 4px;"><input type="radio" name="customer_type" id="retailer" value="retailer">Retail Customer</label>
		  				<label for="Business"><input type="radio" name="customer_type" id="business" value="business">Business Customer</label>
	  				</td>
		  		  </tr>
		  		  <tr>
		  		  	<td colspan="2">
		  		  		<span class="td-text">Customer Group</span>
						<select id="group_select" name="group_select"><?php echo $group_options;?></select> 		
		  		  	</td>
		  		  </tr>
		  		  <tr>
		  		  	<td colspan="2">
		  		  		<span class="td-text" style="float: left;">Account Status</span>
		  		  		<span style="float: left;">
			  		  		<input type="checkbox" class="multi-switch" initial-value="0" unchecked-value="2" checked-value="1" value="0" />
		  		  		</span>
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
		  		  		<input name="email" type="text" id="email" value="" style="width: calc(100% - 70px);">
		  		  	</td>
		  		  </tr>
		  		  <tr>
		  		  	<td colspan="2">
		  		  		<input name="shipping_check" type="checkbox" id="shipping_check" value="">
		  		  			<span class="td-text">Is Shipping address as same as Billing Address</span>
		  		  		</input>
		  		  	</td>
		  		  </tr>
		  		  <tr>
		  		  	<td>
		  		  		<span class="td-text">Billing Address</span><br>
		  		  		<input type="text" name="billing_address_1" id="billing_address_1" placeholder="Street Name"><br>
		  		  		<input type="text" name="billing_address_2" id="billing_address_2" placeholder="Suburb"><br>
		  		  		<input type="text" name="billing_city" id="billing_city" placeholder="State / Province"><br>
		  		  		<input type="text" name="billing_postcode" id="billing_postcode" placeholder="Postal Code / Zip Code"><br>
		  		  		<select name="billing_country" id="billing_country" class="country-select"><?php echo $country_options;?></select>		  		  		
		  		  	</td>
		  		  	<td>
		  		  		<span class="td-text">Shipping Address</span><br>
		  		  		<input type="text" name="shipping_address_1" id="shipping_address_1" placeholder="Street Name"><br>
		  		  		<input type="text" name="shipping_address_2" id="shipping_address_2" placeholder="Suburb"><br>
		  		  		<input type="text" name="shipping_city" id="shipping_city" placeholder="State / Province"><br>
		  		  		<input type="text" name="shipping_postcode" id="shipping_postcode" placeholder="Postal Code / Zip Code"><br>
		  		  		<select name="shipping_country" id="shipping_country" class="country-select"><?php echo $country_options;?></select>
		  		  	</td>
		  		  </tr>
		  		  <tr>
		  		  	<td>
		  		  		<input type="button" name="cancel_btn" id="cancel_btn" class="customer-button" value="Cancel"/>
		  		  	</td>
		  		  	<td>
		  		  		<input type="submit" name="save_btn" id="save_btn" class="customer-button" value="Save"/>
		  		  	</td>
		  		  </tr>		  		  
		  		</table>
		  	</form>
		  </div>
	<?php
		}		

		function add_group() {
			$this->add_customer();
		}
	}
}

$customerManagement = new Customer_Management();

?>