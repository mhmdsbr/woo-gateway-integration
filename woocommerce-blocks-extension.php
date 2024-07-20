<?php
/*
Plugin Name: Extenstion for woocommerce new blocks
Version: 1.0.0
Description:  It will add custom gateway integration and some other features to new woocommerce block mode
Plugin URI: https://mohammadsaber.com
Author: Mohammad Saber
Author URI: https://mohammadsaber.com
*/
if(!defined('ABSPATH')) exit;

define('WOO_GPPDIR', plugin_dir_path( __FILE__ ));
define('WOO_GPPDU', plugin_dir_url( __FILE__ ));

function load_ms_custom_woo_gateway(){

	/* Add ms_custom Gateway Method */
	add_filter('woocommerce_payment_gateways', 'Woocommerce_Add_ms_custom_Gateway');
	function Woocommerce_Add_ms_custom_Gateway($methods){
		$methods[] = 'WC_ms_custom';
		return $methods;
	}

	require_once( WOO_GPPDIR . 'class-wc-gateway-ms-custom.php' );
	//require_once( WOO_GPPDIR . 'block-support.php' );
}
add_action('plugins_loaded', 'load_ms_custom_woo_gateway', 0);


/**
 * Custom function to declare compatibility with cart_checkout_blocks feature 
*/
function declare_ms_custom_cart_checkout_blocks_compatibility() {
    // Check if the required class exists
    if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
        // Declare compatibility for 'cart_checkout_blocks'
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
}
// Hook the custom function to the 'before_woocommerce_init' action
add_action('before_woocommerce_init', 'declare_ms_custom_cart_checkout_blocks_compatibility');


// Hook the custom function to the 'woocommerce_blocks_loaded' action
add_action( 'woocommerce_blocks_loaded', 'ms_custom_register_order_approval_payment_method_type' );

/**
 * Custom function to register a payment method type

 */
function ms_custom_register_order_approval_payment_method_type() {
    // Check if the required class exists
    if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
        return;
    }

    // Include the custom Blocks Checkout class
    require_once plugin_dir_path(__FILE__) . 'class-block.php';

    // Hook the registration function to the 'woocommerce_blocks_payment_method_type_registration' action
    add_action(
        'woocommerce_blocks_payment_method_type_registration',
        function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
            $payment_method_registry->register( new Ms_Custom_Gateway_Blocks );
        }
    );
}