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
			wp_enqueue_script( 'customerManagement-js', PLUGINURL.'assets/js/customer.min.js');
			wp_enqueue_style( 'customerManagement-css', PLUGINURL.'assets/css/customer.min.css');
		}


		function Customer_management_Main() {
		?>
			<div class="customer-main">
				<h1 class="main-title"><?php _e( 'Customer Management', 'customer_management_title' ); ?></h1>
				<select id="customer_select" name="customer_select">
					<option value="add" selected=true >Add Item</option>
					<option value="view">View List</option>
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
				<table>
					<tr>
						<td>Customer ID</td>
						<td>Name<br>(job title)</td>
						<td>Company</td>
						<td>Total Orders</td>
						<td>Amount Due</td>
						<td>Action</td>
					</tr>
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
				</table>
			';
			return $content;
		}

		function DisplayGroup() {
			$content = '
				<table>
					<tr>
						<td>Group ID</td>
						<td>Name<br>(job title)</td>
						<td>Company</td>
						<td>Total Orders</td>
						<td>Amount Due</td>
						<td>Action</td>
					</tr>
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
				</table>
			';
			return $content;
		}		
	}
}

$customerManagement = new Customer_Management();

?>