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
		// Set result to false by default.
		$results = array();

		// Send error if wrong referer.
		if ( ! check_ajax_referer( 'wp-live-debug-nonce' ) ) {
			wp_send_json_error();
		}

		// Test for results.
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

		// Send result depending on the situation.
		wp_send_json_success( $results );
	}
}
