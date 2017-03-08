<?php
/*
Plugin Name: Woocommerce Exclude Emails Coupon
Plugin URI:  
Description: Simple Woocomerce extension for emails exclusion
Version:     0.1.0
Author:      Andrea Rampin
Author URI:  https://github.com/andrearampin
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: woocomerce, coupon, exclude
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// Define the plugin path.
defined('WC_EEC') or define('WC_EEC', sprintf('%s/inc/', dirname(__FILE__)));

// Load libraries.
// Backend
require sprintf('%sadmin/woocommerce-exlude-emails-coupon.php', WC_EEC);
// Frontend
require sprintf('%swoocommerce-exlude-emails-coupon.php', WC_EEC);