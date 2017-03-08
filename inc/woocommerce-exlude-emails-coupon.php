<?php

/**
 * WooCommerce Exclude Emails Coupon FrontEnd
 *
 * @author    Andrea Rampin
 * @version   0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
 * Verify if the billing email is valid if the excluded emails are set.
 */
function coupon_is_valid_for_email($posted) {

  // Load cart:
  global $woocommerce;
  $cart = $woocommerce->cart;

  // Fetch coupons:
  $coupons = $cart->get_coupons();

  // Process every single coupon in search of exceptions if defined:
  foreach ($coupons as $code => $coupon) {
    if(isset($coupon->customer_exclude_email)) {

      // Standardise the customer_exclude_email property:
      if(!is_array($coupon->customer_exclude_email)) {
        $coupon->customer_exclude_email = array($coupon->customer_exclude_email);
      }

      // If an exception is found then remove the coupon from the cart and notify:
      if (in_array($posted['billing_email'], $coupon->customer_exclude_email)) {
        $coupon->add_coupon_message( WC_Coupon::E_WC_COUPON_NOT_YOURS_REMOVED );

        // Remove the coupon
        $cart->remove_coupon($coupon);

        // Flag totals for refresh
        WC()->session->set('refresh_totals', true);
      }
    }
  }
}
add_action( 'woocommerce_after_checkout_validation', 'coupon_is_valid_for_email', 10, 1);