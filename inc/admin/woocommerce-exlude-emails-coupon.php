<?php

/**
 * WooCommerce Exclude Emails Coupon BackEnd
 *
 * @author    Andrea Rampin
 * @version   0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
 * Executed during Wc_Coupon populate function.
 * @param $coupon [Wc_Coupon]
 */
function woocommerce_exclude_emails_coupon_api_coupon_response($coupon) {
  if(!(is_a($order, 'Wc_Coupon'))) { return; }

  // Fetch coupon metadata:
  $coupon_data = get_post_meta($coupon->id);

  // Feed coupon with an extra field:
  if(isset($coupon_data['customer_exclude_email']) && is_array($coupon_data['customer_exclude_email'])) {
    $coupon->customer_exclude_email = unserialize(array_shift($coupon_data['customer_exclude_email']));
  }
}
add_filter('woocommerce_coupon_loaded', 'woocommerce_exclude_emails_coupon_api_coupon_response', 10, 1);


/**
 * Add exclude emails text field in the Wordpress backend.
 */
function add_exclude_emails_coupon_admin() {
  global $post;
  $settings = array(
    'id' => 'customer_exclude_email',
    'label' => __('Exclude email restrictions', 'woocommerce-exlude-emails-coupon'),
    'placeholder' => __('No restrictions', 'woocommerce-exlude-emails-coupon'),
    'description' => __('List of excluded emails to check against the customer\'s billing email when an order is placed. Separate email addresses with commas.', 'woocommerce-exlude-emails-coupon'),
    'value' => implode(',', (array) get_post_meta($post->ID, 'customer_exclude_email', true)),
    'desc_tip' => true,
    'type' => 'email',
    'class' => '',
    'custom_attributes' => array('multiple'  => 'multiple')
  ); 
  woocommerce_wp_text_input($settings);
}
add_action('woocommerce_coupon_options_usage_restriction', 'add_exclude_emails_coupon_admin', 10, 3);


/**
 * Display excluded emails in the admin backend.
 */
function add_customer_exclude_email($post_id) {
  $customer_exclude_email = array_filter(array_map('trim', explode(',', wc_clean($_POST['customer_exclude_email']))));
  update_post_meta($post_id, 'customer_exclude_email', $customer_exclude_email);
}
add_action('woocommerce_coupon_options_save', 'add_customer_exclude_email', 10, 1);