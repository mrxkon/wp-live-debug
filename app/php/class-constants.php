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
}
