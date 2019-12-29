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
		if ( defined( 'WP_DEBUG_LOG' ) ) {
			if ( is_string( WP_DEBUG_LOG ) ) {
				$log_file = wp_normalize_path( WP_DEBUG_LOG );
				update_option( 'wp_live_debug_debug_log_location', $log_file );
			} elseif ( WP_DEBUG_LOG ) {
				$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );
				update_option( 'wp_live_debug_debug_log_location', $log_file );
			}
		}

		// Return the path.
		wp_send_json_success(
			array(
				'debuglog_path' => $log_file,
			)
		);
	}

	/**
	 * Check if debug.log exists.
	 */
	public static function check_debug_log() {
		// Get debug.log location.
		$log_file = get_option( 'wp_live_debug_debug_log_location' );

		// Return true/false if debug.log exists.
		return file_exists( $log_file ) ? true : false;
	}

	/**
	 * Create the debug.log if it doesn't exist.
	 */
	public static function create_debug_log() {
		// Get debug.log location.
		$log_file = get_option( 'wp_live_debug_debug_log_location' );

		// If debug.log doesn't exist create it.
		if ( ! self::check_debug_log() ) {
			$file = fopen( $log_file, 'w' ) or die( 'Cannot create debug.log!' );
			fwrite( $file, '' );
			fclose( $file );
		}
	}

	/**
	 * Refresh debug log toggle
	 */
	public static function refresh_debug_log() {
		if ( ! empty( $_POST['checked'] ) && 'true' === $_POST['checked'] ) {
			update_option( 'wp_live_debug_auto_refresh', 'enabled' );

			$response = array(
				'message' => esc_html__( 'enabled', 'wp-live-debug' ),
			);
		} else {
			update_option( 'wp_live_debug_auto_refresh', 'disabled' );

			$response = array(
				'message' => esc_html__( 'disabled', 'wp-live-debug' ),
			);
		}

		wp_send_json_success( $response );
	}

	/**
	 * Read log.
	 */
	public static function read_debug_log() {
		$log_file = get_option( 'wp_live_debug_log_file' );

		if ( file_exists( $log_file ) ) {
			if ( 2000000 > filesize( $log_file ) ) {
				$debug_contents = file_get_contents( $log_file );
				if ( empty( $debug_contents ) ) {
					// translators: %1$s log filename.
					$debug_contents = sprintf( esc_html__( 'Awesome! %1$s seems to be empty.', 'wp-live-deubg' ), basename( $log_file ) );
				}
			} else {
				// translators: %1$s log filename.
				$debug_contents = sprintf( esc_html__( '%1$s is over 2 MB. Please open it via FTP.', 'wp-live-debug' ), basename( $log_file ) );
			}
		} else {
			// translators: %1$s log filename.
			$debug_contents = sprintf( esc_html__( 'Could not find %1$s file.', 'wp-live-deubg' ), basename( $log_file ) );

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
