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

?>