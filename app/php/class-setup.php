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
 * Setup Class.
 */
class Setup {
	/**
	 * Instance.
	 *
	 * @var $instance.
	 */
	private static $instance = null;

	/**
	 * Return class instance.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Create the Admin menu.
		add_action( 'init', array( $this, 'create_menus' ) );

		// Enqueue necessary scripts & styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Set text_domain.
		add_action( 'init', array( $this, 'text_domain' ) );

		// Log related actions.
		add_action( 'wp_ajax_wp-live-debug-find-debug-log-json', array( '\\WP_Live_Debug\\Log', 'find_debug_log_json' ) );
		add_action( 'wp_ajax_wp-live-debug-read-debug-log', array( '\\WP_Live_Debug\\Log', 'read_debug_log' ) );

		// Backup actions.
		add_action( 'wp_ajax_wp-live-debug-create-backup', array( '\\WP_Live_Debug\\Config', 'create_manual_backup' ) );
		add_action( 'wp_ajax_wp-live-debug-restore-backup', array( '\\WP_Live_Debug\\Config', 'restore_manual_backup' ) );
		add_action( 'wp_ajax_wp-live-debug-check-auto-backup-json', array( '\\WP_Live_Debug\\Config', 'check_auto_backup_json' ) );
		add_action( 'wp_ajax_wp-live-debug-check-manual-backup-json', array( '\\WP_Live_Debug\\Config', 'check_manual_backup_json' ) );

		// Constant actions.
		add_action( 'wp_ajax_wp-live-debug-is-constant-true', array( '\\WP_Live_Debug\\Constants', 'is_constant_true' ) );
		add_action( 'wp_ajax_wp-live-debug-alter-constant', array( '\\WP_Live_Debug\\Constants', 'alter_constant' ) );
	}

	/**
	 * Activation Hook.
	 */
	public static function activate() {
		// Initialize auto refresh and make it disabled.
		update_option( 'wp_live_debug_auto_refresh', 'disabled' );

		// Initialize debug.log location option.
		update_option( 'wp_live_debug_debug_log_location', '' );

		// Keep a first backup of wp-config.php.
		Config::get_auto_backup();
	}

	/**
	 * Deactivation Hook.
	 */
	public static function deactivate() {
		// Remove the auto refresh option.
		delete_option( 'wp_live_debug_auto_refresh' );

		// Remove the debug.log location option.
		delete_option( 'wp_live_debug_debug_log_location' );

		// Remove the manual backup.
		Config::remove_manual_backup();
	}

	/**
	 * Create the Admin Menus.
	 */
	public function create_menus() {
		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array( $this, 'populate_admin_menu' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'populate_admin_menu' ) );
		}
	}

	/**
	 * Populate the Admin menu.
	 */
	public function populate_admin_menu() {
		add_menu_page(
			esc_html__( 'WP Live Debug', 'wp-live-debug' ),
			esc_html__( 'WP Live Debug', 'wp-live-debug' ),
			'manage_options',
			'wp-live-debug',
			array( '\\WP_Live_Debug\\Setup', 'page' ),
			'dashicons-media-code'
		);
	}

	/**
	 * Create a page wrapper for the UI.
	 */
	public static function page() {
		?>
			<div id="wpld-page"></div>
		<?php
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @param string $hook The page name.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_wp-live-debug' === $hook ) {
			// Automated dependencies array.
			$asset_file = include( WP_LIVE_DEBUG_DIR . 'app/js/build/index.asset.php' );

			wp_enqueue_style(
				'wp-live-debug',
				WP_LIVE_DEBUG_URL . 'app/css/styles.css',
				array( 'editor-buttons', 'wp-components' ),
				WP_LIVE_DEBUG_VERSION
			);

			wp_enqueue_script(
				'wp-live-debug',
				WP_LIVE_DEBUG_URL . 'app/js/build/index.js',
				$asset_file['dependencies'],
				$asset_file['version'],
				true
			);

			wp_localize_script(
				'wp-live-debug',
				'wp_live_debug_globals',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'wp-live-debug-nonce' ),
				)
			);
		}
	}

	/**
	 * Load text-domain.
	 */
	function text_domain() {
		load_plugin_textdomain( 'wp-live-debug', false, WP_LIVE_DEBUG_DIR . 'languages' );
	}
}
