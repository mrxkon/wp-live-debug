<?php //phpcs:ignore -- \r\n notice.

/**
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           5.3.1
 * Author:            Konstantinos Xenos
 * Author URI:        https://xkon.gr
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-live-debug
 * Domain Path:       /languages
 *
 */

/**
 * Copyright (C) 2019 Konstantinos Xenos (https://xkon.gr).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/.
 */

/**************************************************/
/****************** Plugin Start ******************/
/**************************************************/

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Various constants.
 */
define( 'WP_LIVE_DEBUG_VERSION', '5.3.1' );
define( 'WP_LIVE_DEBUG_WP_CONFIG', ABSPATH . 'wp-config.php' );
define( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL', ABSPATH . 'wp-config.wpld-original-backup.php' );
define( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP', ABSPATH . 'wp-config.wpld-manual-backup.php' );

/**
 * WP_Live_Debug Class.
 */
if ( ! class_exists( 'WP_Live_Debug' ) ) {
	class WP_Live_Debug {

		/**
		 * WP_Live_Debug constructor.
		 */
		public function __construct() {
			add_action( 'init', array( 'WP_Live_Debug', 'create_menus' ) );
			add_action( 'admin_enqueue_scripts', array( 'WP_Live_Debug', 'enqueue_scripts_styles' ) );
			add_action( 'wp_ajax_wp-live-debug-accept-risk', array( 'WP_Live_Debug', 'accept_risk' ) );
		}

		/**
		 * Accept Risk Popup.
		 */
		public static function accept_risk() {
			update_option( 'wp_live_debug_risk', 'yes' );

			$response = array(
				'message' => esc_html__( 'risk accepted.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Activation Hook.
		 */
		public static function on_activate() {
			update_option( 'wp_live_debug_auto_refresh', 'disabled' );

			WP_Live_Debug_Helper::create_debug_log();
			WP_Live_Debug_Helper::get_first_backup();
		}

		/**
		 * Deactivation Hook.
		 */
		public static function on_deactivate() {
			delete_option( 'wp_live_debug_risk' );
			delete_option( 'wp_live_debug_log_file' );
			delete_option( 'wp_live_debug_auto_refresh' );

			WP_Live_Debug_Helper::clear_manual_backup();
		}

		/**
		 * Create the Admin Menus.
		 */
		public static function create_menus() {
			if ( ! is_multisite() ) {
				add_action( 'admin_menu', array( 'WP_Live_Debug', 'populate_admin_menu' ) );
			} else {
				add_action( 'network_admin_menu', array( 'WP_Live_Debug', 'populate_admin_menu' ) );
			}
		}

		/**
		 * Populate the Admin menu.
		 */
		public static function populate_admin_menu() {
			add_menu_page(
				esc_html__( 'WP Live Debug', 'wp-live-debug' ),
				esc_html__( 'WP Live Debug', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug',
				array( 'WP_Live_Debug', 'create_page' ),
				'dashicons-media-code'
			);
		}

		/**
		 * Enqueue scripts and styles.
		 */
		public static function enqueue_scripts_styles( $hook ) {
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

		/**
		 * Create the WP Live Debug page.
		 */
		public static function create_page() {
			WP_Live_Debug_Live_Debug::create_page();
			$first_time_running = get_option( 'wp_live_debug_risk' );

			if ( empty( $first_time_running ) ) {
				?>
				<div id="safety-popup-holder">
					<div id="safety-popup-inner">
						<div class="safety-popup-header">
							<h3 class="safety-popup-title">Safety First!</h3>
						</div>
						<div class="safety-popup-body">
							<p>
							<?php
								_e( 'WP LIVE DEBUG enables debugging, checks files and runs various tests to gather information about your installation.', 'wp-live-debug' );
							?>
							</p>
							<p>
							<?php
								_e( 'Make sure to have a <strong>full backup</strong> first before proceeding with any of the tools.', 'wp-live-debug' );
							?>
							</p>
						</div>
						<div class="safety-popup-footer">
							<a href="?page=wp-live-debug&wplddlwpconfig=true" class="sui-modal-close sui-button sui-button-green"><?php esc_html_e( 'Download wp-config', 'wp-live-debug' ); ?></a>
							<button id="riskaccept" class="sui-modal-close sui-button sui-button-blue"><?php esc_html_e( 'I understand', 'wp-live-debug' ); ?></button>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}

	// Activation Hook
	register_activation_hook( __FILE__, array( 'WP_Live_Debug', 'on_activate' ) );

	// Deactivation Hook
	register_deactivation_hook( __FILE__, array( 'WP_Live_Debug', 'on_deactivate' ) );

	// Require extra files
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-live-debug.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-helper.php';

	// Initialize Classes.
	new WP_Live_Debug();
	new WP_Live_Debug_Live_Debug();
	new WP_Live_Debug_Helper();
}
