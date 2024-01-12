<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.sprucely.net
 * @since      1.0.0
 *
 * @package    Woocommerce_Order_Navigation
 * @subpackage Woocommerce_Order_Navigation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Order_Navigation
 * @subpackage Woocommerce_Order_Navigation/admin
 * @author     Sprucely Designed <support@sprucely.net>
 */
class Woocommerce_Order_Navigation_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Constructor for the admin class.
	 *
	 * Sets up the WordPress hooks for adding the order navigation meta box.
	 * It also handles the enqueueing of styles and scripts if needed.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The current version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'add_meta_boxes', array( $this, 'add_order_navigation_meta_box' ) );
	}
	/**
	 * Adds the navigation meta box to the WooCommerce order edit screen.
	 *
	 * This method dynamically determines the appropriate screen ID based on whether
	 * HPOS is enabled to ensure compatibility with both storage systems.
	 */
	public function add_order_navigation_meta_box() {
		// Add the meta box
		add_meta_box(
			'woocommerce_order_navigation',
			__( 'Order Navigation', 'woocommerce-order-navigation' ),
			array( $this, 'render_order_navigation_meta_box' ),
			'shop_order',
			'side',
			'default'
		);
	}
	/**
	 * Renders the content inside the order navigation meta box.
	 *
	 * This function outputs the HTML for the 'Next' and 'Previous' order navigation buttons,
	 * allowing quick and easy navigation between WooCommerce orders.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function render_order_navigation_meta_box( $post ) {
		// Get next and previous order IDs
		$prev_order_id = absint( $this->sprucely_get_adjacent_order_id( $post->ID, 'prev' ) );
		$next_order_id = absint( $this->sprucely_get_adjacent_order_id( $post->ID, 'next' ) );

		echo '<div class="sprucely-order-navigation">';

		// Next Order Button.
		if ( $next_order_id ) {
			$next_order_edit_link = get_edit_post_link( $next_order_id );
			echo '<a href="' . esc_url( $next_order_edit_link ) . '" class="button button-secondary next-order" aria-label="' . esc_attr__( 'Go to Next Order', 'woocommerce' ) . '">' . esc_html__( 'Next Order', 'woocommerce' ) . '</a>';
		} else {
			echo '<button class="button button-secondary next-order alignright disabled" disabled>' . esc_html__( 'Next Order', 'woocommerce' ) . '</button>';
		}

		// Previous Order Button.
		if ( $prev_order_id ) {
			$prev_order_edit_link = get_edit_post_link( $prev_order_id );
			echo '<a href="' . esc_url( $prev_order_edit_link ) . '" class="button button-secondary prev-order alignright" aria-label="' . esc_attr__( 'Go to Previous Order', 'woocommerce' ) . '">' . esc_html__( 'Previous Order', 'woocommerce' ) . '</a>';
		} else {
			echo '<button class="button button-secondary prev-order disabled" disabled>' . esc_html__( 'Previous Order', 'woocommerce' ) . '</button>';
		}

		echo '</div>';
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Order_Navigation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Order_Navigation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-order-navigation-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Order_Navigation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Order_Navigation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-order-navigation-admin.js', array( 'jquery' ), $this->version, false );

	}

}
