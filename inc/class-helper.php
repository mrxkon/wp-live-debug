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

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Helper Class.
 */
class Helper {
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
}
