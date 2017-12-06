<?php
/**
 * @package Functions for integrating with WooCommerce
 * @version 1.0
 * @author Smile
 */
	function return_custom_price($price, $product) {
    	if (is_user_logged_in()) {
	    	$current_user = wp_get_current_user();
	    	$user_id = $current_user->ID;

	    	$customer_prices = getProductPriceByUser($user_id);
	    	$calc_price = $customer_prices[$product->id];
	    	if ($calc_price > 0) {
	    		return $calc_price;
	    	}
    	}

    	return $price;
	}

	function getProductPriceByUser($user_id) {
		$customer_prices = array();

		$price_info = getUserPriceInfo($user_id);

    	if ($price_info->price_rule == 1) { // when Price Rule is Markup OR Markdown
    		$percentage = 1;
    		if ($price_info->select_rule ==1 ) {
    			$percentage += $price_info->price_percentage/100;
    		} else {
    			$percentage -= $price_info->price_percentage/100;
    		}

		    $args = array(
		        'post_type'      => 'product',
		        'posts_per_page' => -1
		    );
		    $post_data = get_posts( $args );
			if (sizeof($post_data)>0) {
				foreach ($post_data as $post) {
			        $price_data = get_post_meta($post->ID, '_regular_price');
	    			$regular_price = $price_data[0];
	    			if ($price_info->number_round == 1 ) {
	    				$customer_prices[$post->ID] = round($regular_price * $percentage);
	    			} else {
	    				$customer_prices[$post->ID] = $regular_price * $percentage;
	    			}
				}
			}
    	} else { // when Price Rule is Manual Price
			$customer_prices = get_product_prices($price_info->id);
    	}
    	
    	return $customer_prices;
	}

	function add_custom_price( $cart_object ) {
    	if (is_user_logged_in()) {
	    	$current_user = wp_get_current_user();
	    	$user_id = $current_user->ID;

	    	$customer_prices = getProductPriceByUser($user_id);
		    foreach ( $cart_object->cart_contents as $key => $value ) {
		    	$product_id = $value['product_id'];
		    	$custom_price = $customer_prices[$product_id];	// custome price  
		        // $value['data']->price = $custom_price;
		        // for WooCommerce version 3+ use: 
		        $value['data']->set_price($custom_price);
		    }
		}
	}
?>