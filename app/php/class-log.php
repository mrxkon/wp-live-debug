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
 * Debug Class.
 */
class Log {
	/**
	 * Finds out where debug.log is located.
	 */
	public static function find_debug_log_json() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// If debug log is defined.
		if ( defined( 'WP_DEBUG_LOG' ) && is_string( WP_DEBUG_LOG ) ) {
			$log_file = wp_normalize_path( WP_DEBUG_LOG );
			update_option( 'wp_live_debug_debug_log_location', $log_file );
		} elseif ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );
			update_option( 'wp_live_debug_debug_log_location', $log_file );
		} else {
			$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );
			update_option( 'wp_live_debug_debug_log_location', $log_file );
		}

		// Return the path.
		wp_send_json_success(
			array(
				'debuglog_path' => $log_file,
			)
		);
	}

	/**
	 * Check if auto refresh is enabled.
	 */
	public static function auto_refresh_is() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( get_option( 'wp_live_debug_auto_refresh' ) );
	}

	/**
	 * Refresh debug.log toggle
	 */
	public static function alter_auto_refresh() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		$allowed = array(
			'enabled',
			'disabled',
		);

		$value = $_POST['value'];

		// Send error if value isn't allowed.
		if ( ! in_array( $value, $allowed, true ) ) {
			wp_send_json_error();
		}

		update_option( 'wp_live_debug_auto_refresh', $value );

		wp_send_json_success();
	}

	/**
	 * Read debug.log.
	 */
	public static function read_debug_log() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		$log_file = get_option( 'wp_live_debug_debug_log_location' );

		if ( file_exists( $log_file ) ) {
			if ( 2000000 > filesize( $log_file ) ) {
				$debug_contents = file_get_contents( $log_file );
				if ( empty( $debug_contents ) ) {
					$debug_contents = esc_html__( 'Awesome! The log seems to be empty.', 'wp-live-deubg' );
				}
			} else {
				$debug_contents = esc_html__( 'The log is over 2 MB. Please open it via FTP.', 'wp-live-debug' );
			}
		} else {
			$debug_contents = esc_html__( 'Could not find the log file.', 'wp-live-deubg' );

		}

		echo $debug_contents;

		wp_die();
	}

	/**
	 * Clear log.
	 */
	public static function clear_debug_log() {
		$nonce    = sanitize_text_field( $_POST['nonce'] );
		$log_file = sanitize_text_field( $_POST['log'] );

		if ( ! wp_verify_nonce( $nonce, $log_file ) ) {
			$response = array(
				'message' => esc_html__( 'Could not validate nonce', 'wp-live-debug' ),
			);
			wp_send_json_error( $response );
		}

		if ( 'log' != substr( strrchr( $log_file, '.' ), 1 ) ) {
			$response = array(
				'message' => esc_html__( 'This is not a log file.', 'wp-live-debug' ),
			);

			wp_send_json_error( $response );
		}

		file_put_contents( $log_file, '' );

		$response = array(
			'message' => esc_html__( '.log was cleared', 'wp-live-debug' ),
		);

		wp_send_json_success( $response );
	}

	/**
	 * Delete log.
	 */
	public static function delete_debug_log() {
		$nonce    = sanitize_text_field( $_POST['nonce'] );
		$log_file = sanitize_text_field( $_POST['log'] );

		if ( ! wp_verify_nonce( $nonce, $log_file ) ) {
			wp_send_json_error();
		}

		if ( 'log' != substr( strrchr( $log_file, '.' ), 1 ) ) {
			wp_send_json_error();
		}

		unlink( $log_file );

		wp_send_json_success();
	}
}
