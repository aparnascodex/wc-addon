<?php
/*
 * Plugin Name: WC addon
 * Author: Aparna
 * Description: This is sample plugin created for adding custom field on WooCommerce checkout page
 * Author URI: http://aparnascodex.com
 * Plugin URI: https://github.com/aparnascodex/wc-addon 
 * Version: 1.0
 * Text Domain: wc
 */

define('WC_DIR', dirname(__FILE__));
if (is_file(WC_DIR.'/billing_field.php')) require_once(WC_DIR.'/billing_field.php');