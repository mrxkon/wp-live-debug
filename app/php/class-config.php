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

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Config Class.
 */
class Config {
	/**
	 * Create the wp-config.WPLD-auto.php.
	 */
	public static function get_auto_backup() {
		if ( file_exists( WP_LIVE_DEBUG_AUTO_BACKUP ) ) {
			unlink( WP_LIVE_DEBUG_AUTO_BACKUP );
		}

		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG ) ) {
			copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_AUTO_BACKUP );
		}
	}

	/**
	 * Check if wp-config.WPLD-auto.php exists.
	 */
	public static function check_auto_backup_json() {
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		if ( ! file_exists( WP_LIVE_DEBUG_AUTO_BACKUP ) ) {
			self::get_auto_backup();
		}

		file_exists( WP_LIVE_DEBUG_AUTO_BACKUP ) ? wp_send_json_success() : wp_send_json_error();
	}

	/**
	 * Check if wp-config.WPLD-manual.php exists.
	 */
	public static function check_manual_backup_json() {
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		file_exists( WP_LIVE_DEBUG_MANUAL_BACKUP ) ? wp_send_json_success() : wp_send_json_error();
	}

	/**
	 * Remove the wp-config.WPLD-manual.php.
	 */
	public static function remove_manual_backup() {
		if ( file_exists( WP_LIVE_DEBUG_MANUAL_BACKUP ) ) {
			unlink( WP_LIVE_DEBUG_MANUAL_BACKUP );
		}
	}

	/**
	 * Creates wp-config.WPLD-manual.php.
	 */
	public static function create_manual_backup() {
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		if ( ! copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_MANUAL_BACKUP ) ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}

	/**
	 * Restores wp-config.php from wp-config.WPLD-manual.php.
	 */
	public static function restore_manual_backup() {
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		if ( ! copy( WP_LIVE_DEBUG_MANUAL_BACKUP, WP_LIVE_DEBUG_WP_CONFIG ) ) {
			wp_send_json_error();
		}

		unlink( WP_LIVE_DEBUG_MANUAL_BACKUP );

		wp_send_json_success();
	}
}
