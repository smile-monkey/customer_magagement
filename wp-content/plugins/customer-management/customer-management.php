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
		public $_customer_tb;

		function __construct()
		{
			global $wpdb;
			$this->_customer_tb = $wpdb->prefix."woocommerce_customers";
			/**
			 * Create a table for customer management.
			 */
			register_activation_hook( __FILE__,array( &$this,'register_database'));			
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
			add_action( 'wp_ajax_save_customer_data', array(&$this,'save_customer_data'));
			add_action( 'wp_ajax_show_customer_edit', array(&$this,'show_customer_edit'));
			add_action( 'wp_ajax_show_customer_nav', array(&$this,'show_customer_nav'));

		}
		/**
		 * Create a table for customer management.
		 */
		function register_database() {
			global $wpdb;

			try {
				$query = "CREATE TABLE IF NOT EXISTS`".$this->_customer_tb."` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `user_id` int(11) DEFAULT NULL,
						  `user_status` int(11) DEFAULT NULL COMMENT 'hold:0,active:1,inactive:2',
						  `customer_type` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL COMMENT 'retailer, business',
						  `group_id` int(11) DEFAULT NULL,
						  `company` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
						  `tax_number` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
						  `phone` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
						  `mobile` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
						  `shipping_check` int(11) DEFAULT NULL COMMENT '0 or 1',
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci";
				$wpdb->query($query);
			} catch (Exception $e) {
				echo $e;
			}			
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
			wp_enqueue_style( 'woocommerce-admin-css', PLUGINURL.'assets/css/woocommerce-admin.min.css');
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
					$content = $this->DisplayGroup();
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
			$customer_list = get_customer_list($this->_customer_tb);
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
						$content .='
							<tr>
								<td>'.$customer->user_id.'</td>
								<td>'.$user_info['first_name'][0].' '.$user_info['last_name'][0].'</td>
								<td>'.$customer->company.'</td>
								<td>0</td>
								<td>$0.00</td>
								<td class="user_actions column-user_actions">
								  <p>
									<a class="button tips edit" href="#" data-cusotmer_id="'.$customer->id.'">Edit</a>
									<a class="button tips view" href="#" data-cusotmer_id="'.$customer->id.'">View orders</a>
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
								<a class="button tips view" href="#">View orders</a>
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
								<a class="button tips view" href="#">View orders</a>
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
		  	<img src="<?php echo plugins_url( '/assets/images/customer-icon.png' , __FILE__ );?>">
		  	<h1>Add New Customer</h1>
		  </div>
		  <div class="add-content">
		  	<form id="add_form" method="post" action="" enctype="multipart/form-data" autocomplete="on">
		  		<table class="add_form_table">
		  		  <tr>
	  				<td colspan="2">
		  				<span class="td-text">Customer Type*</span>
		  				<label for="Retailer" style="padding-right: 20px; padding-left: 4px;"><input type="radio" name="customer_type" value="retailer" checked>Retail Customer</label>
		  				<label for="Business"><input type="radio" name="customer_type" value="business">Business Customer</label>
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
		  		  		<input type="text" name="billing_address_2" id="billing_address_2" placeholder="Suburb"><br>
		  		  		<input type="text" name="billing_city" id="billing_city" placeholder="State / Province"><br>
		  		  		<input type="text" name="billing_postcode" id="billing_postcode" placeholder="Postal Code / Zip Code"><br>
		  		  		<select name="billing_country" id="billing_country" class="country-select"><?php echo $country_options;?></select>		  		  		
		  		  	</td>
		  		  	<td id="shipping_data">
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
		  		  		<input type="submit" name="save_new_btn" id="save_new_btn" class="customer-button" value="Save"/>
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

		function save_customer_data() {

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
				$colNames = $wpdb->get_col("DESC {$this->_customer_tb}", 0);
				foreach ($colNames as $colname) {
					if (isset($save_data[$colname]) && $save_data[$colname] !=null) {
						$customer_data[$colname] = $save_data[$colname];
					}
				}
				if (sizeof($customer_data) > 0) {
					$wpdb->insert($this->_customer_tb,$customer_data);
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

		function show_customer_edit() {
			$customer_id = $_POST['customer_id'];
			if ($customer_id) {
				$customer_data = get_customer_data($this->_customer_tb,$customer_id);
				$user_info = get_user_meta($customer_data->user_id);
			?>
			  <div class="add-header">
			  	<img src="<?php echo plugins_url( '/assets/images/customer-icon.png' , __FILE__ );?>">
			  	<h1><?php echo $user_info['first_name'][0]." ".$user_info['last_name'][0]." / ".$customer_data->company;?></h1>
				<select id="customer_edit_select" name="customer_edit_select">
					<option value="edit" selected=true >--Edit--</option>
					<option value="view">--View--</option>
				</select>			  	
			  </div>
			  <nav class="nav-tab-wrapper woo-nav-tab-wrapper customer_edit_nav">
				<a href="#" class="nav-tab nav-tab-active " id="customer_info" name="customer_info">Personal Information</a>
				<a href="#" class="nav-tab" id="customer_transaction" name="customer_transaction">Transactions</a>
				<a href="#" class="nav-tab" id="customer_price" name="customer_price">Price List</a>
				<a href="#" class="nav-tab" id="customer_delivery" name="customer_delivery">Delivery</a>
				<a href="#" class="nav-tab" id="customer_doc" name="customer_doc">Documents</a>
				<a href="#" class="nav-tab" id="customer_login" name="customer_login">Login Credentials</a>
			  </nav>
			  <div class="customer-edit-body">
			  	<?php echo $this->get_customer_nav($customer_id,'customer_info');?>
			  </div>
			<?php

			}else {
				echo "Customer Loading Error!";
			}

			die();
		}

		function show_customer_nav() {
			$customer_id = $_POST['customer_id'];
			$nav_id = $_POST['nav_id'];
			echo $this->get_customer_nav($customer_id,$nav_id);
			exit();
		}

		function get_customer_nav($customer_id,$nav_id) {
			$content = "";
			switch ($nav_id) {
				case 'customer_info':
					$content = get_customer_info($this->_customer_tb, $customer_id);
					break;
				case 'customer_transaction':
					$content = get_customer_transaction($this->_customer_tb, $customer_id);
					break;					
				case 'customer_price':
					$content = get_customer_price($this->_customer_tb, $customer_id);
					break;					
				case 'customer_delivery':
					$content = get_customer_delivery($this->_customer_tb, $customer_id);
					break;					
				case 'customer_doc':
					$content = get_customer_doc($this->_customer_tb, $customer_id);
					break;
				case 'customer_login':
					$content = get_customer_login($this->_customer_tb, $customer_id);
					break;					
				default:
					$content = get_customer_info($this->_customer_tb, $customer_id);
					break;
			}
			return $content;
		}
	}
}

$customerManagement = new Customer_Management();



?>