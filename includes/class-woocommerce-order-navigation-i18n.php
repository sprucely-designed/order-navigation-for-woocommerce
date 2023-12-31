<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.sprucely.net
 * @since      1.0.0
 *
 * @package    Woocommerce_Order_Navigation
 * @subpackage Woocommerce_Order_Navigation/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Order_Navigation
 * @subpackage Woocommerce_Order_Navigation/includes
 * @author     Sprucely Designed <support@sprucely.net>
 */
class Woocommerce_Order_Navigation_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-order-navigation',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
