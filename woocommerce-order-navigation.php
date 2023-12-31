<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sprucely.net
 * @since             1.0.0
 * @package           Woocommerce_Order_Navigation
 *
 * @wordpress-plugin
 * Plugin Name:       Quick Order Navigation for WooCommerce
 * Plugin URI:        https://www.sprucely.net/plugins/quick-order-navigation-for-woocommerce/
 * Description:       Quick Order Navigation for WooCommerce streamlines order management by adding convenient 'Next Order' and 'Previous Order' buttons to the WooCommerce order edit screen.
 * Version:           1.0.0
 * Author:            Sprucely Designed
 * Author URI:        https://www.sprucely.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-order-navigation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOCOMMERCE_ORDER_NAVIGATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-order-navigation-activator.php
 */
function activate_woocommerce_order_navigation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-order-navigation-activator.php';
	Woocommerce_Order_Navigation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-order-navigation-deactivator.php
 */
function deactivate_woocommerce_order_navigation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-order-navigation-deactivator.php';
	Woocommerce_Order_Navigation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_order_navigation' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_order_navigation' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-order-navigation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_order_navigation() {

	$plugin = new Woocommerce_Order_Navigation();
	$plugin->run();

}
run_woocommerce_order_navigation();
