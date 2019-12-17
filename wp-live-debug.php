<?php //phpcs:ignore -- \r\n notice & class- filename.

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

namespace WP_Live_Debug;

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Setup various constants.
 */
if ( ! defined( 'WP_LIVE_DEBUG_VERSION' ) ) {
	define( 'WP_LIVE_DEBUG_VERSION', '5.3.1' );
}

if ( ! defined( 'WP_LIVE_DEBUG_WP_CONFIG' ) ) {
	define( 'WP_LIVE_DEBUG_WP_CONFIG', ABSPATH . 'wp-config.php' );
}

if ( ! defined( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL' ) ) {
	define( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL', ABSPATH . 'wp-config.wpld-original-backup.php' );
}

if ( ! defined( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP' ) ) {
	define( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP', ABSPATH . 'wp-config.wpld-manual-backup.php' );
}

if ( ! defined( 'WP_LIVE_DEBUG_DIR' ) ) {
	define( 'WP_LIVE_DEBUG_DIR', wp_normalize_path( dirname( __FILE__ ) ) );
}

/**
 * Setup Class.
 */

class Setup {
	/**
	 * Instance.
	 */
	private static $instance = null;

	/**
	 * Page Class.
	 */
	public static $page;

	/**
	 * Helper Class.
	 */
	public static $helper;

	/**
	 * Debug Class;
	 */
	public static $debug;

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
		spl_autoload_register( array( $this, 'autoload' ) );

		self::$page   = new Page\Page();
		self::$helper = new Helper\Helper();
		self::$helper = new Debug\Debug();
	}

	/**
	 * Autoload additional classes.
	 */
	public function autoload( $class ) {
		$prefix = 'WP_Live_Debug\\';
		$len    = strlen( $prefix );

		if ( 0 !== strncmp( $prefix, $class, $len ) ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$path           = explode( '\\', strtolower( str_replace( '_', '-', $relative_class ) ) );
		$file           = array_pop( $path );
		$file           = WP_LIVE_DEBUG_DIR . '/inc/class-' . $file . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}

	/**
	 * Activation Hook.
	 */
	public static function activate() {
		update_option( 'wp_live_debug_auto_refresh', 'disabled' );

		self::create_debug_log();
		self::get_first_backup();
	}

	/**
	 * Deactivation Hook.
	 */
	public static function deactivate() {
		delete_option( 'wp_live_debug_risk' );
		delete_option( 'wp_live_debug_log_file' );
		delete_option( 'wp_live_debug_auto_refresh' );

		self::clear_manual_backup();
	}

	/**
	 * Create the debug.log if it doesn't exist.
	 */
	public static function create_debug_log() {
		$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );

		if ( ! file_exists( $log_file ) ) {
			$fo = fopen( $log_file, 'w' ) or die( 'Cannot create debug.log!' );

			fwrite( $fo, '' );

			fclose( $fo );
		}

		update_option( 'wp_live_debug_log_file', $log_file );
	}

	/**
	 * Create the wp-config.wpld-original-backup.php
	 */
	public static function get_first_backup() {
		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG ) ) {
			copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL );
		}
	}

	/**
	 * Delete the wp-config.wpld-manual-backup.php on deactivation
	 */
	public static function clear_manual_backup() {
		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG_BACKUP ) ) {
			unlink( WP_LIVE_DEBUG_WP_CONFIG_BACKUP );
		}
	}
}

// Activation Hook
register_activation_hook( __FILE__, array( 'WP_Live_Debug\\Setup', 'activate' ) );

// Deactivation Hook
register_deactivation_hook( __FILE__, array( 'WP_Live_Debug\\Setup', 'deactivate' ) );

// Load WP_Live_Debug.
add_action( 'plugins_loaded', array( 'WP_Live_Debug\\Setup', 'get_instance' ) );

