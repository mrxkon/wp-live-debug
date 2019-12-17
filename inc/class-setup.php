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

/**
 * Setup Class.
 */
class Setup {
	/**
	 * Instance.
	 */
	private static $instance = null;

	/**
	 * Helper Class.
	 */
	public static $helper;

	/**
	 * Page Class.
	 */
	public static $page;

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
		error_log( 'construct Setup' );
		spl_autoload_register( array( $this, 'autoload' ) );

		self::$helper = new Helper();
		//self::$page   = new Page();
		//self::$debug  = new Debug();
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
