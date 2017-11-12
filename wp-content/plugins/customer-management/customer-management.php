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

include_once (__DIR__ . '/includes/database.php');
include_once (__DIR__ . '/includes/functions.php');

if (!class_exists(Customer_Management)){
	/**
	* 
	*/
	class Customer_Management
	{

		function __construct()
		{
			/**
			 * Create a table for customer management.
			 */
			register_activation_hook( __FILE__, 'create_customer_table');			
			/**
			 * Add sub menu page to menu
			 */
			add_action('admin_menu', array(&$this, 'Customer_Management_Menu'));

			/**
			 * Load CSS and JS files
			 */
			add_action('admin_init', array(&$this, 'Customer_Management_Init'));
			
			/**
			 * Ajax define
			 */
			add_action( 'wp_ajax_show_list', array(&$this,'show_list'));
			add_action( 'wp_ajax_get_customer_content', array(&$this, 'get_customer_content'));
			add_action( 'wp_ajax_save_customer_data', 'save_customer_new');
			add_action( 'wp_ajax_save_customer_edit_data', array(&$this,'save_customer_edit_data'));
			add_action( 'wp_ajax_upload_doc_data', array(&$this,'upload_doc_data'));
			add_action( 'wp_ajax_get_document_body', array(&$this, 'get_document_body'));
			add_action ('wp_ajax_process_document_action', array(&$this, 'process_document_action'));
			add_action ('wp_ajax_save_customer_content_data', array(&$this, 'save_customer_content_data'));
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
		        'dashicons-index-card',
		        56
		    );
		}
		
		function Customer_Management_Init() {
			// wp_enqueue_script( 'jquery-1-12-1-js', PLUGINURL.'assets/js/jquery-1.12.1.min.js');
			wp_enqueue_style( 'woocommerce-admin-css', PLUGINURL.'assets/css/woocommerce-admin.min.css');
			wp_enqueue_style( 'multiSwith-css', PLUGINURL.'assets/css/multi-switch.min.css');
			wp_enqueue_script( 'multiSwith-js', PLUGINURL.'assets/js/multi-switch.js');

			wp_enqueue_script( 'customerManagement-js', PLUGINURL.'assets/js/customer.min.js');
			wp_enqueue_style( 'customerManagement-css', PLUGINURL.'assets/css/customer.min.css');
			wp_localize_script( 'customerManagement-js', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'adminurl' => admin_url('admin.php?page=customer_management') ) );

		}

		function Customer_management_Main() {
			$main_tab = $_GET['main_tab'];
			$customer_id = $_GET['customer_id'];
			if (isset($main_tab) && isset($customer_id)) {
				$this->show_customer_edit ($main_tab, $customer_id);
			}else {
		?>
			  <div class="customer-content customer-body">
				<div class="customer-main">
					<h1 class="main-title"><?php _e( 'Customer Management', 'customer_management_title' ); ?></h1>
					<select id="customer_select" name="customer_select">
						<option value="0" selected=true >--Add New--</option>
						<option value="customer">Customer</option>
						<option value="group">Group</option>
						<option value="price">Price List</option>
						<option value="payment">Payment Terms</option>
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
			  <div class="customer-content customer-add"><?php echo add_customer();?></div>
			  <div class="customer-content group-add"></div>
		<?php
			}
		}

		function show_list() {
			$content = '';
			$list_id = $_POST['list_id'];
			switch ($list_id) {
				case 'group_list':
					$content = DisplayGroup();
					break;
				case 'price_list':
					$content = DisplayGroup();
					break;
				case 'payment_list':
					$content = DisplayPayment();
					break;					
				default:
					$content = DisplayCustomer();
					break;
			}
			echo $content;
			die();
		}

		function get_customer_content() {
			$add_type = $_POST['add_type'];
			$row_id = $_POST['row_id'];
			switch ($add_type) {
				case 'payment':
					echo get_payment_content($row_id);
					break;
			}
			die();
		}

		function show_customer_edit($main_tab, $customer_id) {

			$customer_data = get_customer_data($customer_id);
			$user_info = get_user_meta($customer_data->user_id);
			$content = "";
			$tab_active = array();
			switch ($main_tab) {
				case 'customer_info':
					$content = get_customer_info($customer_id);
					$tab_active[0] = 'nav-tab-active';
					break;
				case 'customer_transaction':
					$content = get_customer_transaction($customer_id);
					$tab_active[1] = 'nav-tab-active';
					break;					
				case 'customer_price':
					$content = get_customer_price($customer_id);
					$tab_active[2] = 'nav-tab-active';
					break;					
				case 'customer_delivery':
					$content = get_customer_delivery($customer_id);
					$tab_active[3] = 'nav-tab-active';
					break;					
				case 'customer_doc':
					$content = get_customer_doc($customer_id);
					$tab_active[4] = 'nav-tab-active';
					break;
				case 'customer_login':
					$content = get_customer_login($customer_id);
					$tab_active[5] = 'nav-tab-active';
					break;
			}
		?>
			<div class="customer-edit">
			  <div class="add-header">
			  	<img src="<?php echo plugins_url( '/assets/images/customer-icon.png' , __FILE__ );?>">
			  	<h1><?php echo $user_info['first_name'][0]." ".$user_info['last_name'][0]." / ".$customer_data->company;?></h1>
				<select id="customer_edit_select" name="customer_edit_select">
					<option value="edit" selected=true >--Edit--</option>
					<option value="view">--View--</option>
				</select>			  	
			  </div>
			  <nav class="nav-tab-wrapper woo-nav-tab-wrapper customer_edit_nav">
		<?php echo '
				<a href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_info&customer_id='.$customer_id ).'"  class="nav-tab '.$tab_active[0].'">Personal Information</a>
				<a href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_transaction&customer_id='.$customer_id ).'"  class="nav-tab '.$tab_active[1].'">Transactions</a>
				<a href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_price&customer_id='.$customer_id ).'"  class="nav-tab '.$tab_active[2].'">Price List</a>
				<a href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_delivery&customer_id='.$customer_id ).'"  class="nav-tab '.$tab_active[3].'">Delivery</a>
				<a href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_doc&customer_id='.$customer_id ).'"  class="nav-tab '.$tab_active[4].'">Documents</a>
				<a href="'.admin_url( 'admin.php?page=customer_management&main_tab=customer_login&customer_id='.$customer_id ).'"  class="nav-tab '.$tab_active[5].'">Login Credentials</a>
			  </nav>
			  <div class="customer-edit-body">'.$content.'</div>
			</div>';
			die();
		}

		function save_customer_edit_data() {
			global $wpdb;
			$save_data = array();

			parse_str($_POST['form_data'], $save_data);
			switch ($save_data['main_tab']) {
				case 'customer_info':
					save_customer_info($save_data);
					break;
				case 'customer_login':
					save_customer_login($save_data);
					break;
			}			
			exit("ok");
		}

		function get_document_body() {
			$customer_id = $_POST['customer_id'];
			$search_key = $_POST['search_key'];
			echo get_doc_body($customer_id, $search_key);
			exit;
		}

		function process_document_action() {
			$selected_id = explode('_', $_POST['selected_id']);
			switch ($selected_id[0]) {
				case 'delete':
					$result = delete_customer_document($selected_id[1]);
					break;
				case 'send':
					$result = send_customer_document($selected_id[1]);
					break;
			}
			exit($result);
		}

		// Save Payment Terms
		function save_customer_content_data() {
			$save_data = array();
			$customer_content_type = $_POST['customer_content_type'];
			parse_str($_POST['form_data'],$save_data);
			switch ($customer_content_type) {
				case 'payment':
					$result = save_payment_content($save_data);
					break;
			}
			exit($result);
		}
	}
}

$customerManagement = new Customer_Management();

?>