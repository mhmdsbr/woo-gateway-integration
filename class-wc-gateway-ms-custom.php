<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (class_exists('WC_Payment_Gateway') && !class_exists('WC_ms_custom')) {
    class WC_ms_custom extends WC_Payment_Gateway {

        private $baseurl = 'https://api.domain.com/v1';
        private $ms_customToken;
        private $success_massage;
        private $failed_massage;

        // Constructor for the gateway
        public function __construct() {

            // Basic gateway settings
            $this->id = 'WC_ms_custom';
            $this->method_title = __('Gateway Title', 'woocommerce');
            $this->method_description = __('Description', 'woocommerce');
            $this->icon = apply_filters('woo_ms_custom_logo', WOO_GPPDU . '/assets/images/logo.png');
            $this->has_fields = false;

            // Initialize form fields and settings
            $this->init_form_fields();
            $this->init_settings();

            // Check if external server is enabled
            $checkserver = $this->settings['ioserver'];
            if ($checkserver == 'yes')
                $this->baseurl = 'https://api.domain.io/v1';

            // Set instance variables based on settings
            $this->title = $this->settings['title'];
            $this->description = $this->settings['description'];
            $this->ms_customToken = $this->settings['ms_customToken'];
            $this->success_massage = $this->settings['success_massage'];
            $this->failed_massage = $this->settings['failed_massage'];

            // Hook into WooCommerce actions to save settings
            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>='))
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            else
                add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options'));

            // Hook into WooCommerce receipt page and API return
            add_action('woocommerce_receipt_' . $this->id, array($this, 'Send_to_ms_custom_Gateway'));
            add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'Return_from_ms_custom_Gateway'));
        }

        // Admin panel options
        public function admin_options() {
            parent::admin_options();
        }

        // Initialize form fields for the gateway settings
        public function init_form_fields() {
            $this->form_fields = apply_filters('WC_ms_custom_Config', array(
                'base_confing' => array(
                    'title' => __('Basic Settings', 'woocommerce'),
                    'type' => 'title',
                    'description' => '',
                ),
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable Custom Gateway', 'woocommerce'),
                    'description' => __('Check this box to enable the custom payment gateway.', 'woocommerce'),
                    'default' => 'yes',
                    'desc_tip' => true,
                ),
                'ioserver' => array(
                    'title' => __('External Server', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Connect to External Server', 'woocommerce'),
                    'description' => __('Check this box to connect to an external server.', 'woocommerce'),
                    'default' => 'no',
                    'desc_tip' => true,
                ),
                'title' => array(
                    'title' => __('Gateway Title', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Title of the payment gateway displayed during checkout.', 'woocommerce'),
                    'default' => __('Pay via Custom Gateway', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Gateway Description', 'woocommerce'),
                    'type' => 'text',
                    'desc_tip' => true,
                    'description' => __('Description of the payment gateway displayed during checkout.', 'woocommerce'),
                    'default' => __('Pay using any credit card via Custom Gateway.', 'woocommerce')
                ),
                'account_confing' => array(
                    'title' => __('Custom Gateway Account Settings', 'woocommerce'),
                    'type' => 'title',
                    'description' => '',
                ),
                'ms_customToken' => array(
                    'title' => __('Token', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Custom gateway token.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'payment_confing' => array(
                    'title' => __('Payment Operation Settings', 'woocommerce'),
                    'type' => 'title',
                    'description' => '',
                ),
                'success_massage' => array(
                    'title' => __('Successful Payment Message', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Enter the message to be displayed to the user after a successful payment. You can use the shortcode {transaction_id} to display the custom gateway transaction ID.', 'woocommerce'),
                    'default' => __('Thank you. Your order has been successfully paid.', 'woocommerce'),
                ),
                'failed_massage' => array(
                    'title' => __('Failed Payment Message', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Enter the message to be displayed to the user after a failed payment. You can use the shortcode {fault} to display the error reason.', 'woocommerce'),
                    'default' => __('Your payment has failed. Please try again or contact the site administrator.', 'woocommerce'),
                )
            ));
        }

        // Process the payment and return the result
        public function process_payment($order_id) {
            $order = new WC_Order($order_id);
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url(true)
            );
        }

        // Send the customer to the custom gateway for payment
        public function Send_to_ms_custom_Gateway($order_id) {
            // Add code to send customer to the payment gateway
        }

        // Handle the return from the custom gateway
        public function Return_from_ms_custom_Gateway() {
            // Add code to handle the response from the payment gateway
        }

    }
}
