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

	/*******************
	 * Automated backup.
	 ******************/

	/**
	 * Create the wp-config.WPLD-auto.php.
	 */
	public static function get_auto_backup() {
		// First remove any previous wp-config.WPLD-auto.php.
		if ( file_exists( WP_LIVE_DEBUG_AUTO_BACKUP ) ) {
			unlink( WP_LIVE_DEBUG_AUTO_BACKUP );
		}

		// Create an wp-config.WPLD-auto.php.
		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG ) ) {
			copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_AUTO_BACKUP );
		}
	}

	/**
	 * Check if wp-config.WPLD-auto.php exists.
	 */
	public static function check_auto_backup() {
		return file_exists( WP_LIVE_DEBUG_AUTO_BACKUP ) ? true : false;
	}

	/**
	 * Check if wp-config.WPLD-auto.php exists.
	 *
	 * Returns JSON for React usage.
	 */
	public static function check_auto_backup_json() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// If there is no wp-config.WPLD-auto.php create one.
		if ( ! self::check_auto_backup() ) {
			self::get_auto_backup();
		}

		file_exists( WP_LIVE_DEBUG_AUTO_BACKUP ) ? wp_send_json_success() : wp_send_json_error();
	}

	/*******************
	 * Manual backup.
	 ******************/

	/**
	 * Check if wp-config.WPLD-manual.php exists.
	 */
	public static function check_manual_backup() {
		return file_exists( WP_LIVE_DEBUG_MANUAL_BACKUP ) ? true : false;
	}

	/**
	 * Check if wp-config.WPLD-manual.php exists.
	 *
	 * Returns JSON for React usage.
	 */
	public static function check_manual_backup_json() {
		// Send error if wrong referer.
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
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// If we can't create a wp-config backup then send an error.
		if ( ! copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_MANUAL_BACKUP ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'wp-config.php backup failed.', 'wp-live-debug' ),
				)
			);
		}

		// Send success.
		wp_send_json_success(
			array(
				'message' => esc_html__( 'wp-config.php backup was created.', 'wp-live-debug' ),
			)
		);
	}

	/**
	 * Restores wp-config.php from wp-config.WPLD-manual.php.
	 */
	public static function restore_manual_backup() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// If we can't restore the wp-config then send an error.
		if ( ! copy( WP_LIVE_DEBUG_MANUAL_BACKUP, WP_LIVE_DEBUG_WP_CONFIG ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'wp-config.php restore failed.', 'wp-live-debug' ),
				)
			);
		}

		// Remove the backup file.
		unlink( WP_LIVE_DEBUG_MANUAL_BACKUP );

		// Send success.
		wp_send_json_success(
			array(
				'message' => esc_html__( 'wp-config.php backup was restored.', 'wp-live-debug' ),
			)
		);
	}
}
