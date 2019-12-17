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

namespace WP_Live_Debug\Helper;

use WP_Live_Debug\Page;

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Helper Class.
 */
class Helper {

	/**
	 * Constructor.
	 */
	public function __construct() {
		error_log( 'construct Helper' );

		// Create the Admin menu.
		add_action( 'init', array( $this, 'create_menus' ) );

		// Enqueue necessary scripts & styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
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
			array( 'Page', 'create' ),
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
				plugin_dir_url( __FILE__ ) . 'assets/styles.css',
				array(),
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
}
