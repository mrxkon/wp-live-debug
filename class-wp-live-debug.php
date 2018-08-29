<?php
/**
 * @package WP Live Debug
 * @version 4.9.8
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           4.9.8
 * Author:            Xenos (xkon) Konstantinos
 * Author URI:        https://xkon.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-live-debug
 * Domain Path:       /languages
 *
 */

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Various Defines
 */
define( 'WP_LIVE_DEBUG_VERSION', '4.9.8' );

/**
 * WP_Live_Debug Class.
 */
if ( ! class_exists( 'WP_Live_Debug' ) ) {
	class WP_Live_Debug {

		/**
		 * WP_Live_Debug constructor.
		 *
		 * @uses WPLiveDebug::init()
		 *
		 * @return void
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Plugin initialization.
		 *
		 * @uses add_action()
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'init', array( 'WP_Live_Debug', 'create_menus' ) );
			add_action( 'admin_enqueue_scripts', array( 'WP_Live_Debug', 'load_scripts_styles' ) );
		}

		/**
		 * Create Menus
		 */
		public static function create_menus() {
			if ( ! is_multisite() ) {
				add_action( 'admin_menu', array( 'WP_Live_Debug', 'create_admin_menu' ) );
			} else {
				add_action( 'network_admin_menu', array( 'WP_Live_Debug', 'create_admin_menu' ) );
			}
		}

		/**
		 * Create Admin menu.
		 *
		 * @uses add_menu_page()
		 *
		 * @return void
		 */
		public static function create_admin_menu() {
			add_menu_page(
				__( 'WP Live Debug', 'wp-live-debug' ),
				__( 'WP Live Debug', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug',
				array( 'WP_Live_Debug_Live_Debug', 'create_page' ),
				'dashicons-media-code'
			);
			add_submenu_page(
				'wp-live-debug',
				__( 'PHP', 'wp-live-debug' ),
				__( 'PHP', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug-php-info',
				array( 'WP_Live_Debug_PHP_Info', 'create_page' )
			);
			add_submenu_page(
				'wp-live-debug',
				__( 'WordPress', 'wp-live-debug' ),
				__( 'WordPress', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug-wp-info',
				array( 'WP_Live_Debug_WP_Info', 'create_page' )
			);
			add_submenu_page(
				'wp-live-debug',
				__( 'Server', 'wp-live-debug' ),
				__( 'Server', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug-server-info',
				array( 'WP_Live_Debug_Server_Info', 'create_page' )
			);
		}

		/**
		 * Load scripts and styles
		 */
		public static function load_scripts_styles( $hook ) {
			if ( 'toplevel_page_wp-live-debug' === $hook ||
				'wp-live-debug_page_wp-live-debug-php-info' === $hook ||
				'wp-live-debug_page_wp-live-debug-wp-info' === $hook ||
				'wp-live-debug_page_wp-live-debug-server-info' === $hook ) {
				wp_enqueue_style(
					'wphb-wpmudev-sui',
					plugin_dir_url( __FILE__ ) . 'assets/sui/css/shared-ui.min.css',
					'2.2.10'
				);
				wp_enqueue_script(
					'wphb-wpmudev-sui',
					plugin_dir_url( __FILE__ ) . 'assets/sui/js/shared-ui.min.js',
					array( 'jquery' ),
					'2.2.10',
					true
				);

				add_filter( 'admin_body_class', array( 'WP_Live_Debug', 'admin_body_classes' ) );
			}

			if ( 'toplevel_page_wp-live-debug' === $hook ) {
				wp_enqueue_style(
					'wp-live-debug',
					plugin_dir_url( __FILE__ ) . 'assets/styles.css',
					WP_LIVE_DEBUG_VERSION
				);
				wp_enqueue_script(
					'wp-live-debug',
					plugin_dir_url( __FILE__ ) . 'assets/scripts.js',
					array( 'jquery' ),
					WP_LIVE_DEBUG_VERSION,
					true
				);
			}
		}

		/**
		 * Add Shared UI 2.2.10
		 */
		public static function admin_body_classes( $classes ) {
			$classes .= 'sui-2-2-10';
			return $classes;
		}

	}
	// Include extra classes
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-live-debug.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-php-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-server-info.php';
	// Initialize WP Live Debug.
	new WP_Live_Debug();
}
