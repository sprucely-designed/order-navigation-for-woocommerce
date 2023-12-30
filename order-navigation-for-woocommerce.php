<?php
/**
 * Plugin Name: Admin Order Navigation for WooCommerce
 * Plugin URI: https://www.sprucely.net/
 * Description: Adds Next and Previous navigation buttons to WooCommerce order edit screen, compatible with HPOS.
 * Version: 1.1
 * Author: Isaac Russell @ Sprucely Designed
 * Author URI: https://www.sprucely.net
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Declare HPOS compatibility.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * Main plugin class for Admin Order Navigation for WooCommerce.
 *
 * This class adds Next and Previous navigation buttons to the WooCommerce order edit screen,
 * ensuring compatibility with both traditional storage and WooCommerce's HPOS.
 */
class Sprucely_WC_Order_Navigation_HPOS_Compatible {

	/**
	 * Constructor for the Sprucely_WC_Order_Navigation_HPOS_Compatible class.
	 *
	 * Sets up the necessary WordPress hooks for adding the navigation meta box.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'sprucely_add_navigation_meta_box' ) );
	}

	/**
	 * Adds a navigation meta box to the WooCommerce order edit screen.
	 *
	 * This method dynamically determines the appropriate screen ID based on whether
	 * HPOS is enabled to ensure compatibility with both storage systems.
	 */
	public function sprucely_add_navigation_meta_box() {
		$screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
			? wc_get_page_screen_id( 'shop-order' )
			: 'shop_order';

		add_meta_box(
			'sprucely_order_navigation',
			__( 'Order Navigation', 'woocommerce' ),
			array( $this, 'sprucely_order_navigation_meta_box_content' ),
			$screen,
			'side',
			'high'
		);
	}

	/**
	 * Outputs the content for the order navigation meta box.
	 *
	 * Displays Next and Previous buttons for navigation between orders.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function sprucely_order_navigation_meta_box_content( $post ) {
		// Get next and previous order IDs
		$prev_order_id = $this->sprucely_get_adjacent_order_id( $post->ID, 'prev' );
		$next_order_id = $this->sprucely_get_adjacent_order_id( $post->ID, 'next' );

		echo '<div class="sprucely-order-navigation">';
		if ( $prev_order_id ) {
			$prev_order_edit_link = get_edit_post_link( $prev_order_id );
			echo '<a href="' . esc_url( $prev_order_edit_link ) . '" class="button">' . esc_html__( 'Previous Order', 'woocommerce' ) . '</a>';
		}
		if ( $next_order_id ) {
			$next_order_edit_link = get_edit_post_link( $next_order_id );
			echo '<a href="' . esc_url( $next_order_edit_link ) . '" class="button">' . esc_html__( 'Next Order', 'woocommerce' ) . '</a>';
		}
		echo '</div>';
	}

	/**
	 * Retrieves the ID of the adjacent WooCommerce order.
	 *
	 * Determines the next or previous order ID, accounting for HPOS if enabled.
	 *
	 * @param int    $order_id The current order ID.
	 * @param string $direction The direction for navigation ('next' or 'prev').
	 * @return int|null The ID of the adjacent order or null if not found.
	 */
	private function sprucely_get_adjacent_order_id( $order_id, $direction = 'next' ) {
		// Get the current order object.
		$current_order = wc_get_order( $order_id );

		if ( ! $current_order ) {
			return null;
		}

		// Get the order creation date.
		$current_order_date = $current_order->get_date_created()->format( 'Y-m-d H:i:s' );

		// Use WC API to determine if HPOS is enabled.
		if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
			$orders = wc_get_orders(
				array(
					'limit'        => 1,
					'orderby'      => 'date',
					'order'        => $direction === 'prev' ? 'DESC' : 'ASC',
					'return'       => 'ids',
					'status'       => array( 'wc-completed', 'wc-processing', 'wc-on-hold' ),
					'date_created' => $direction === 'prev' ? '<' . $current_order_date : '>' . $current_order_date,
				)
			);

			return ! empty( $orders ) ? $orders[0] : null;

		} else {
			// Fallback for traditional storage.
			global $wpdb;
			$operator = ( 'prev' === $direction ) ? '<' : '>';
			$order    = ( 'prev' === $direction ) ? 'DESC' : 'ASC';
			$query    = $wpdb->prepare(
				"
                SELECT posts.ID
                FROM $wpdb->posts AS posts
                WHERE posts.post_type = 'shop_order'
                AND posts.post_status IN ( 'wc-completed', 'wc-processing', 'wc-on-hold' )
                AND posts.ID $operator %d
                ORDER BY posts.ID $order
                LIMIT 1
            ",
				$order_id
			);
			return $wpdb->get_var( $query );
		}
	}
}

// Initialize the plugin.
new Sprucely_WC_Order_Navigation_HPOS_Compatible();
