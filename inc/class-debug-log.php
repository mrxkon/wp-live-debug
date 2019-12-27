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
	die( 'Nope :)' );
}

/**
 * Debug Class.
 */
class Debug_Log {
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
	 * Select log.
	 */
	public static function select_log_file() {
		$nonce    = sanitize_text_field( $_POST['nonce'] );
		$log_file = sanitize_text_field( $_POST['log'] );

		if ( ! wp_verify_nonce( $nonce, $log_file ) ) {
			wp_send_json_error();
		}

		if ( 'log' != substr( strrchr( $log_file, '.' ), 1 ) ) {
			wp_send_json_error();
		}

		update_option( 'wp_live_debug_log_file', $log_file );

		wp_send_json_success();
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

		WP_Live_Debug_Helper::create_debug_log();

		$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );

		update_option( 'wp_live_debug_log_file', $log_file );

		wp_send_json_success();
	}
}
