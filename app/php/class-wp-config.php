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
 * WP_Config Class.
 */
class WP_Config {
	/**
	 * The array of accepted constants.
	 *
	 * @var array $constants
	 */
	private static $constants = array(
		'WP_DEBUG',
		'WP_DEBUG_LOG',
		'WP_DEBUG_DISPLAY',
		'SCRIPT_DEBUG',
		'SAVEQUERIES',
	);

	/**
	 * Creates a backup of wp-config.php.
	 */
	public static function create_wp_config_backup() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// If we can't create a wp-config backup then send an error.
		if ( ! copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_WP_CONFIG_BACKUP ) ) {
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
	 * Restores a backup of wp-config.php.
	 */
	public static function restore_wp_config_backup() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// If we can't restore the wp-config then send an error.
		if ( ! copy( WP_LIVE_DEBUG_WP_CONFIG_BACKUP, WP_LIVE_DEBUG_WP_CONFIG ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'wp-config.php restore failed.', 'wp-live-debug' ),
				)
			);
		}

		// Remove the backup file.
		unlink( WP_LIVE_DEBUG_WP_CONFIG_BACKUP );

		// Send success.
		wp_send_json_success(
			array(
				'message' => esc_html__( 'wp-config.php backup was restored.', 'wp-live-debug' ),
			)
		);
	}

	/**
	 * Force download wp-config original backup
	 */
	public static function download_config_backup() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// Make sure to create a backup if it doesn't exist.
		if ( ! file_exists( WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL ) ) {
			Helper::get_first_backup();
		}

		if ( ! empty( $_GET['wplddlwpconfig'] ) && 'true' === $_GET['wplddlwpconfig'] ) {
			$filename = 'wp-config-' . str_replace( array( 'http://', 'https://' ), '', get_site_url() ) . '-' . wp_date( 'Ymd-Hi' ) . '-backup.php';
			header( 'Content-type: textplain;' );
			header( 'Content-disposition: attachment; filename= ' . $filename );
			readfile( WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL );
			exit();
		}
	}

	/**
	 * Check if original wp-config.php backup exists.
	 */
	public static function check_wp_config_original_backup() {
		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		file_exists( WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL ) ? wp_send_json_success() : wp_send_json_error();
	}

	/**
	 * Check if a given constant is enabled.
	 */
	public static function is_constant_true() {
		// Set result to false by default.
		$result = false;

		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// Sanitize the accepted constant.
		$constant = sanitize_text_field( $_GET['constant'] );

		// Only continue if the constant fits our needs.
		if ( ! in_array( $constant, self::$constants, true ) ) {
			wp_send_json_error();
		}

		// Test for results.
		switch ( $constant ) {
			case 'WP_DEBUG':
				defined( 'WP_DEBUG' ) && WP_DEBUG ? $result = true : $result = false;
				break;
			case 'WP_DEBUG_LOG':
				defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ? $result = true : $result = false;
				break;
			case 'WP_DEBUG_DISPLAY':
				defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ? $result = true : $result = false;
				break;
			case 'SCRIPT_DEBUG':
				defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? $result = true : $result = false;
				break;
			case 'SAVEQUERIES':
				defined( 'SAVEQUERIES' ) && SAVEQUERIES ? $result = true : $result = false;
				break;
		}

		// Send result depending on the situation.
		$result ? wp_send_json_success() : wp_send_json_error();
	}

	/**
	 * Enables WP_DEBUG.
	 */
	// public static function enable_wp_debug() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'WP_DEBUG'" ) || false !== strpos( $line, '"WP_DEBUG"' ) ) {
	// 			$line      = "define( 'WP_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'WP_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );

	// 	self::enable_wp_debug_log();
	// 	self::disable_wp_debug_display();
	// 	self::disable_wp_debug_ini_set_display();

	// 	$response = array(
	// 		'message' => esc_html__( 'WP_DEBUG was enabled.', 'wp-live-debug' ),
	// 	);

	// 	wp_send_json_success( $response );
	// }

	// /**
	//  * Disables WP_DEBUG
	//  */
	// public static function disable_wp_debug() {
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'WP_DEBUG'" ) || false !== strpos( $line, '"WP_DEBUG"' ) ) {
	// 			$line = "define( 'WP_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	fclose( $write_wpconfig );

	// 	self::disable_wp_debug_log();
	// 	self::disable_wp_debug_display();
	// 	self::disable_wp_debug_ini_set_display();

	// 	$response = array(
	// 		'message' => esc_html__( 'WP_DEBUG was disabled.', 'wp-live-debug' ),
	// 	);

	// 	wp_send_json_success( $response );
	// }

	// /**
	//  * Enable WP_DEBUG_LOG.
	//  */
	// public static function enable_wp_debug_log() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'WP_DEBUG_LOG'" ) || false !== strpos( $line, '"WP_DEBUG_LOG"' ) ) {
	// 			$line      = "define( 'WP_DEBUG_LOG', true ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'WP_DEBUG_LOG', true ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );
	// }

	// /**
	//  * Disable WP_DEBUG_LOG.
	//  */
	// public static function disable_wp_debug_log() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'WP_DEBUG_LOG'" ) || false !== strpos( $line, '"WP_DEBUG_LOG"' ) ) {
	// 			$line      = "define( 'WP_DEBUG_LOG', false ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}
	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'WP_DEBUG_LOG', false ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );
	// }

	// /**
	//  * Disable WP_DEBUG_DISPLAY.
	//  */
	// public static function disable_wp_debug_display() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'WP_DEBUG_DISPLAY'" ) || false !== strpos( $line, '"WP_DEBUG_DISPLAY"' ) ) {
	// 			$line      = "define( 'WP_DEBUG_DISPLAY', false ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'WP_DEBUG_DISPLAY', false ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );
	// }

	// /**
	//  * Disable ini_set display_errors.
	//  */
	// public static function disable_wp_debug_ini_set_display() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'display_errors'" ) || false !== strpos( $line, '"display_errors"' ) ) {
	// 			$line      = "@ini_set( 'display_errors', 0 ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "@ini_set( 'display_errors', 0 ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );
	// }

	// /**
	//  * Enable SCRIPT_DEBUG.
	//  */
	// public static function enable_script_debug() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'SCRIPT_DEBUG'" ) || false !== strpos( $line, '"SCRIPT_DEBUG"' ) ) {
	// 			$line      = "define( 'SCRIPT_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}
	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'SCRIPT_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );

	// 	$response = array(
	// 		'message' => esc_html__( 'SCRIPT_DEBUG was enabled.', 'wp-live-debug' ),
	// 	);

	// 	wp_send_json_success( $response );
	// }

	// /**
	//  * Disable SCRIPT_DEBUG.
	//  */
	// public static function disable_script_debug() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'SCRIPT_DEBUG'" ) || false !== strpos( $line, '"SCRIPT_DEBUG"' ) ) {
	// 			$line      = "define( 'SCRIPT_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'SCRIPT_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );

	// 	$response = array(
	// 		'message' => esc_html__( 'SCRIPT_DEBUG was disabled.', 'wp-live-debug' ),
	// 	);

	// 	wp_send_json_success( $response );
	// }

	// /**
	//  * Enable SAVEQUERIES.
	//  */
	// public static function enable_savequeries() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'SAVEQUERIES'" ) || false !== strpos( $line, '"SAVEQUERIES"' ) ) {
	// 			$line      = "define( 'SAVEQUERIES', true ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'SAVEQUERIES', true ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );

	// 	$response = array(
	// 		'message' => esc_html__( 'SAVEQUERIES was enabled.', 'wp-live-debug' ),
	// 	);

	// 	wp_send_json_success( $response );
	// }

	// /**
	//  * Disable SAVEQUERIES.
	//  */
	// public static function disable_savequeries() {
	// 	$not_found        = true;
	// 	$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 	file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 	$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 	foreach ( $editing_wpconfig as $line ) {
	// 		if ( false !== strpos( $line, "'SAVEQUERIES'" ) || false !== strpos( $line, '"SAVEQUERIES"' ) ) {
	// 			$line      = "define( 'SAVEQUERIES', false ); // Added by WP Live Debug" . PHP_EOL;
	// 			$not_found = false;
	// 		}

	// 		fputs( $write_wpconfig, $line );
	// 	}

	// 	if ( $not_found ) {
	// 		$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

	// 		file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

	// 		$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

	// 		foreach ( $editing_wpconfig as $line ) {
	// 			if ( false !== strpos( $line, 'stop editing!' ) ) {
	// 				$line  = "define( 'SAVEQUERIES', false ); // Added by WP Live Debug" . PHP_EOL;
	// 				$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
	// 			}

	// 			fputs( $write_wpconfig, $line );
	// 		}
	// 	}

	// 	fclose( $write_wpconfig );

	// 	$response = array(
	// 		'message' => esc_html__( 'SAVEQUERIES was disabled.', 'wp-live-debug' ),
	// 	);

	// 	wp_send_json_success( $response );
	// }
}
