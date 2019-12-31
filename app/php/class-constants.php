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
 * Constants Class.
 */
class Constants {
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
	 * Check if a given constant is enabled.
	 */
	public static function is_constant_true() {
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		$results = array();

		foreach ( self::$constants as $constant ) {
			switch ( $constant ) {
				case 'WP_DEBUG':
					if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						array_push( $results, 'WP_DEBUG' );
					}
					break;
				case 'WP_DEBUG_LOG':
					if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
						array_push( $results, 'WP_DEBUG_LOG' );
					}
					break;
				case 'WP_DEBUG_DISPLAY':
					if ( defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) {
						array_push( $results, 'WP_DEBUG_DISPLAY' );
					}
					break;
				case 'SCRIPT_DEBUG':
					if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
						array_push( $results, 'SCRIPT_DEBUG' );
					}
					break;
				case 'SAVEQUERIES':
					if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
						array_push( $results, 'SAVEQUERIES' );
					}
					break;
			}
		}

		wp_send_json_success( $results );
	}

	/**
	 * Sets trues/false on a given constant.
	 */
	public static function alter_constant() {
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		if ( ! file_exists( WP_LIVE_DEBUG_WP_CONFIG ) ) {
			wp_send_json_error();
		}

		$constant   = sanitize_text_field( $_POST['constant'] );
		$post_value = (string) sanitize_text_field( $_POST['value'] );

		if ( ! in_array( $constant, self::$constants, true ) ) {
			wp_send_json_error();
		}

		if ( 'true' === $post_value ) {
			$value = 'true';
		} elseif ( 'false' === $post_value ) {
			$value = 'false';
		} else {
			$value = "'{$post_value}'";
		}

		$file = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'r+' );

		if ( ! $file ) {
			wp_send_json_error();
		}

		flock( $file, LOCK_EX );

		$lines = array();

		while ( ! feof( $file ) ) {
			$lines[] = rtrim( fgets( $file ), "\r\n" );
		}

		$new_file = array();
		$added    = false;

		foreach ( $lines as $line ) {
			if ( preg_match( "/define\s?\(\s?[\"|']{$constant}[\"|']/", $line ) ) {
				$added = true;
				$line  = "define( '{$constant}', {$value} ); // Added by WP Live Debug";
			}

			$new_file[] = $line;
		}

		if ( ! $added ) {
			$new_file = array();

			foreach ( $lines as $line ) {
				if ( preg_match( "/\/\* That's all, stop editing!.*/i", $line ) ) {
					$added      = true;
					$new_file[] = "define( '{$constant}', {$value} ); // Added by WP Live Debug";
				}

				$new_file[] = $line;
			}
		}

		if ( ! $added ) {
			$new_file = array();

			foreach ( $lines as $line ) {
				if ( preg_match( '/<\?php/', $line ) ) {
					$new_file[] = '<?php';
					$new_file[] = "define( '{$constant}', {$value} ); // Added by WP Live Debug";
				} else {
					$new_file[] = $line;
				}
			}
		}

		$data = implode( "\n", $new_file );

		fseek( $file, 0 );

		$bytes = fwrite( $file, $data );

		if ( $bytes ) {
			ftruncate( $file, ftell( $file ) );
		}

		fflush( $file );

		flock( $file, LOCK_UN );

		fclose( $file );

		wp_send_json_success();
	}
}
