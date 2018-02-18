<?php
/**
 * @package WP Live Debug
 * @version 4.9.4.2
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           4.9.4.2
 * Author:            Xenos (xkon) Konstantinos
 * Author URI:        https://xkon.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-live-debug
 * Domain Path:       /languages
 *
 */

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

class WP_Live_Debug {

	/**
	 * WP_Live_Debug constructor.
	 *
	 * @uses WPLiveDebug::init()
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Plugin initialization.
	 *
	 * @uses add_action()
	 *
	 * @return void
	 */
	public function init() {

		add_action( 'plugins_loaded', array( $this, 'enable_error_report' ) );
		add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
		add_action( 'wp_ajax_wp_live_debug_read_log', array( $this, 'read_debug_log' ) );
		add_action( 'wp_ajax_wp_live_debug_clear_log', array( $this, 'clear_debug_log' ) );

	}

	/**
	 * Enable Error Reporting.
	 *
	 * @uses ini_set()
	 *
	 * @return void
	 */
	public function enable_error_report() {

		ini_set( 'error_reporting', E_ALL );
		ini_set( 'log_errors', 1 );
		ini_set( 'display_errors', 0 );
		ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );

	}

	/**
	 * Create Admin menu.
	 *
	 * @uses add_menu_page()
	 *
	 * @return void
	 */
	public function create_admin_menu() {

		add_menu_page(
			'WP Live Debug',
			'WP Live Debug',
			'manage_options',
			'wp-live-debug',
			array( $this, 'create_debug_page' ),
			'dashicons-media-code',
			'80'
		);

	}



	/**
	 * Create Admin Page
	 *
	 * @uses ini_set()
	 *
	 * @return void
	 */
	public function create_debug_page() {

		// Start the debug process.
		ini_set( 'error_reporting', E_ALL );
		ini_set( 'log_errors', 1 );
		ini_set( 'display_errors', 0 );
		ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );

		// Start the page output.
		?>
		<div class="wrap">
			<h1>WP Live Debug</h1>
			<textarea id="wp-live-debug-area" style="font-family: Consolas,Monaco,monospace; font-size: 14px;width: 100%; height:calc( 100vh - 300px );"></textarea>
			<p class="wp-live-debug-buttons">
				<input type="submit" id="wp-live-debug-start-stop" class="button button-primary" value="<?php esc_html_e( 'Stop auto refresh', 'wp-live-debug' ); ?>" />
				<input type="submit" id="wp-live-debug-clear-log" class="button button-primary" value="<?php esc_html_e( 'Clear the debug.log', 'wp-live-debug' ); ?>" />
				<input type="hidden" id="wp-live-debug-scroll" value="yes" />
			</p>
		</div>
		<script>
			(function( $ ) {

				var doScroll         = $( '#wp-live-debug-scroll' );
				var debugArea        = $( '#wp-live-debug-area' );
				var debugLiveButton  = $( '#wp-live-debug-start-stop' );
				var debugClearButton = $( '#wp-live-debug-clear-log' );
				var refreshData      = { 'action': 'wp_live_debug_read_log' };
				var clearData        = { 'action': 'wp_live_debug_clear_log' };

				// Scroll the textarea to bottom.
				function scrollDebugAreaToBottom() {

					debugArea.scrollTop( debugArea[0].scrollHeight );

				}

				// Make the initial ajax call.
				$.post( ajaxurl, refreshData, function( response ) {

					debugArea.html( response );
					scrollDebugAreaToBottom();

				} );

				// Make the ajax calls every 3 seconds if enabled.
				setInterval( function() {

					if ( doScroll.val() === 'yes' ) {

						$.post( ajaxurl, refreshData, function( response ) {

							debugArea.html( response );
							scrollDebugAreaToBottom();

						} );

					}

				}, 3000 );

				// Handle the pause button clicks.
				debugLiveButton.on( 'click', function() {

					if ( doScroll.val() === 'yes' ) {

						doScroll.val( 'no' );
						debugLiveButton.val( '<?php esc_html_e( 'Start auto refresh', 'wp-live-debug' ); ?>' );

					} else {

						doScroll.val( 'yes' );
						debugLiveButton.val( '<?php esc_html_e( 'Stop auto refresh', 'wp-live-debug' ); ?>' );

					}

				} );

				// Handle the clear button clicks.
				debugClearButton.on( 'click', function() {

					$.post( ajaxurl, clearData, function( response ) {

						debugArea.html( response );
						scrollDebugAreaToBottom();

					} );

				} );

			} )( jQuery )
		</script>
		<?php
	}

	/**
	 * Read debug.log contents and return them.
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses die()
	 * @uses fwrite()
	 * @uses fclose()
	 * @uses WP_CONTENT_DIR
	 * @uses wp_die()
	 *
	 * @return string $debug_contents The content of debug.log
	 */
	public function read_debug_log() {

		if ( ! file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {

			$fo = fopen( WP_CONTENT_DIR . '/debug.log', 'w' ) or die( 'Cannot create debug.log!' );
			fwrite( $fo, '' );
			fclose( $fo );

		}

		$debug_contents = file_get_contents( WP_CONTENT_DIR . '/debug.log' );
		echo $debug_contents;
		wp_die();

	}

	/**
	 * Clear debug.log content.
	 *
	 * @uses file_put_contents()
	 * @uses wp_die()
	 *
	 * @return void
	 */
	public function clear_debug_log() {

		file_put_contents( WP_CONTENT_DIR . '/debug.log', '' );
		esc_html_e( 'The debug.log has been cleared.', 'wp-live-debug' );
		wp_die();

	}

}

// Initialize WP Live Debug.
new WP_Live_Debug();
