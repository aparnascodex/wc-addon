<?php

if (!defined('ABSPATH')) die('No direct access allowed');
/*
* Class: CustomField
* Description : Extends WooCommerce checkout form. Add new billing field- apartment number
*/
class CustomField {

	public function __construct() {

		add_filter('woocommerce_billing_fields', array($this, 'fun_override_checkout_fields'), 10, 2);
		add_action('woocommerce_checkout_process', array($this, 'validate_aparment_number'));
		add_action( 'woocommerce_checkout_update_order_meta', array($this, 'save_aparment_number'));
		add_filter('woocommerce_order_formatted_billing_address', array($this, 'display_apartment_number'), 10, 2);
		add_filter('woocommerce_formatted_address_replacements', array($this, 'add_field_in_replacement_array'),10,2);
		add_filter('woocommerce_localisation_address_formats', array($this, 'add_aparment_key_in_address_format'));
	}

	/*
	* Filter: woocommerce_billing_fields
	* Description: Add Apartment Number field before address fields
	*/
	public function fun_override_checkout_fields($address_fields, $country)	{
		$address_fields['aparment_number']= array(
				'label'        => __( 'Apartment Number', 'wc' ),
				'required'     => false,
				'type'         => 'number',
				'class'        => array( 'form-row-wide' ),
				'validate'     => array( 'number' ),				
				'priority'     => 40,
			);;
   
     	return $address_fields;
	}

	/*
	* Action : woocommerce_checkout_process
	* Description: Validate field
	*/
	public function validate_aparment_number() {
    
	    $apartment_number = $_POST['aparment_number'];
	    if ($apartment_number != '' && preg_match('/^[0-9]+$/',$apartment_number)== 0)
	        wc_add_notice( __( '<b>Aparment number </b> is invalid ' ), 'error' );
		
	}

	/*
	* Action : woocommerce_checkout_update_order_meta
	* Description: Save aparment number as order meta
	*/
	public function save_aparment_number($order_id)
	{
		if (!empty( $_POST['aparment_number'] ) ) {
        update_post_meta( $order_id, 'apartment_number', sanitize_text_field( $_POST['aparment_number'] ));
    	}
	}
	/*
	* Action : woocommerce_order_formatted_billing_address
	* Description: Display Aparment number on thank you page
	*/
	public function display_apartment_number($address, $order_obj) {
		

		$order_id = $order_obj->get_id();
		$apartment_no =  get_post_meta( $order_id, 'apartment_number', true);
		$address['apartment_number'] = $apartment_no;
		
		return $address;
	}

	/*
	* filter : woocommerce_formatted_address_replacements
	* Description: Display Aparment number on thank you page
	*/
	public function add_field_in_replacement_array($arr, $args) {
		
		$arr['{apartment_number}'] = $args['apartment_number'];
		
		return $arr;
	}

	/*
	* filter : woocommerce_localisation_address_formats
	* Description: Add Aparment number key in format
	*/
	public function add_aparment_key_in_address_format($formats) {
		$element = array('{apartment_number}');
		foreach($formats as $key=>$val) {
						
			$new_arr = str_replace("\n",',',$val);
			$arr = explode(',',$new_arr);
			array_splice( $arr, 3, 0, $element);
			$str = implode("\n",$arr);
			$formats[$key] = $str;
		}
		return $formats;
	}

}
new CustomField();