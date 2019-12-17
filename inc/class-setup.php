<?php //phpcs:ignore -- \r\n notice.

/**
 * This file comes with "wp-live-debug".
 *
 * Author:      Konstantinos Xenos
 * Author URI:  https://xkon.gr
 * Repo URI:    https://github.com/mrxkon/wp-live-debug/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace WP_Live_Debug;

use WP_Live_Debug\Helper;

/**
 * Setup Class.
 */
class Setup {
	/**
	 * Instance.
	 */
	private static $instance = null;

	/**
	 * Return class instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Create the Admin menu.
		add_action( 'init', array( $this, 'create_menus' ) );

		// Enqueue necessary scripts & styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Activation Hook.
	 */
	public static function activate() {
		update_option( 'wp_live_debug_auto_refresh', 'disabled' );

		Helper::create_debug_log();
		Helper::get_first_backup();
	}

	/**
	 * Deactivation Hook.
	 */
	public static function deactivate() {
		delete_option( 'wp_live_debug_risk' );
		delete_option( 'wp_live_debug_log_file' );
		delete_option( 'wp_live_debug_auto_refresh' );

		Helper::clear_manual_backup();
	}

	/**
	 * Create the Admin Menus.
	 */
	public function create_menus() {
		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array( $this, 'populate_admin_menu' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'populate_admin_menu' ) );
		}
	}

	/**
	 * Populate the Admin menu.
	 */
	public function populate_admin_menu() {
		add_menu_page(
			esc_html__( 'WP Live Debug', 'wp-live-debug' ),
			esc_html__( 'WP Live Debug', 'wp-live-debug' ),
			'manage_options',
			'wp-live-debug',
			array( '\\WP_Live_Debug\\Page', 'create' ),
			'dashicons-media-code'
		);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_wp-live-debug' === $hook ) {
			wp_enqueue_style(
				'wp-live-debug',
				WP_LIVE_DEBUG_URL . 'assets/styles.css',
				array(),
				WP_LIVE_DEBUG_VERSION
			);
			wp_enqueue_script(
				'wp-live-debug',
				WP_LIVE_DEBUG_URL . 'assets/scripts.js',
				array( 'jquery' ),
				WP_LIVE_DEBUG_VERSION,
				true
			);
		}
	}
}
