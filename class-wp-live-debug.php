<?php //phpcs:ignore
/**
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           4.9.8.6
 * Author:            Konstantinos (xkon) Xenos
 * Author URI:        https://xkon.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-live-debug
 * Domain Path:       /languages
 *
 */

/*
Copyright Konstantinos Xenos ( https://xkon.gr )

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
Credits & Licences:
This is a personal project that I use for debugging, but some parts of the code are written by other awesome people.

So props also go to:
WPMU DEV ( https://premium.wpmudev.org ) for parts of debug info & Shared UI
- Shared UI ( https://github.com/wpmudev/shared-ui ) - Licence GPLv2

The WordPress.org ( https://wordpress.org ) community for parts of debug info
- Health Check ( https://wordpress.org/plugins/health-check/ ) - Licence GPLv2 - ( https://github.com/wordpress/health-check )
*/

/**************************************************/
/****************** Plugin Start ******************/
/**************************************************/

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Define the plugin version for internal use
 */
define( 'WP_LIVE_DEBUG_VERSION', '4.9.8.6' );
define( 'WP_LIVE_DEBUG_WP_CONFIG', ABSPATH . 'wp-config.php' );
define( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL', ABSPATH . 'wp-config.wpld-original-backup.php' );
define( 'WP_LIVE_DEBUG_WP_CONFIG_BACKUP', ABSPATH . 'wp-config.wpld-manual-backup.php' );
/**
 * WP_Live_Debug Class.
 */
if ( ! class_exists( 'WP_Live_Debug' ) ) {
	class WP_Live_Debug {

		/**
		 * WP_Live_Debug constructor.
		 *
		 * @uses WP_Live_Debug::init()
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
			add_action( 'init', array( 'WP_Live_Debug', 'create_menus' ) );
			add_action( 'admin_enqueue_scripts', array( 'WP_Live_Debug', 'enqueue_scripts_styles' ) );
			add_action( 'wp_ajax_wp-live-debug-accept-risk', array( 'WP_Live_Debug', 'accept_risk' ) );
		}

		/**
		 * Accept Risk Popup.
		 *
		 * @uses update_option()
		 * @uses esc_html__()
		 * @uses wp_send_json_success()
		 *
		 * @return string json success with the response.
		 */
		public static function accept_risk() {
			update_option( 'wp_live_debug_risk', 'yes' );

			$response = array(
				'message' => esc_html__( 'risk accepted.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Activation Hook.
		 *
		 * @uses get_site_url()
		 * @uses update_option()
		 * @uses WP_Live_Debug_Helper::create_debug_log()
		 * @uses WP_Live_Debug_Helper::get_first_backup()
		 *
		 * @return void
		 */
		public static function on_activate() {
			$host = str_replace( array( 'http://', 'https://' ), '', get_site_url() );

			update_option( 'wp_live_debug_ssl_domain', $host );

			update_option( 'wp_live_debug_auto_refresh', 'disabled' );

			WP_Live_Debug_Helper::create_debug_log();
			WP_Live_Debug_Helper::get_first_backup();
		}

		/**
		 * Deactivation Hook.
		 *
		 * @uses delete_option()
		 * @uses WP_Live_Debug_Helper::clear_manual_backup()
		 *
		 * @return void
		 */
		public static function on_deactivate() {
			delete_option( 'wp_live_debug_risk' );
			delete_option( 'wp_live_debug_ssl_domain' );
			delete_option( 'wp_live_debug_log_file' );
			delete_option( 'wp_live_debug_auto_refresh' );

			WP_Live_Debug_Helper::clear_manual_backup();
		}

		/**
		 * Create the Admin Menus.
		 *
		 * @uses is_multisite()
		 * @uses add_action()
		 *
		 * @return void
		 */
		public static function create_menus() {
			if ( ! is_multisite() ) {
				add_action( 'admin_menu', array( 'WP_Live_Debug', 'populate_admin_menu' ) );
			} else {
				add_action( 'network_admin_menu', array( 'WP_Live_Debug', 'populate_admin_menu' ) );
			}
		}

		/**
		 * Populate the Admin menu.
		 *
		 * @uses add_menu_page()
		 * @uses esc_html__()
		 *
		 * @return void
		 */
		public static function populate_admin_menu() {
			add_menu_page(
				esc_html__( 'WP Live Debug', 'wp-live-debug' ),
				esc_html__( 'WP Live Debug', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug',
				array( 'WP_Live_Debug', 'create_page' ),
				'dashicons-media-code'
			);
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @param string $hook WordPress generated class for the current page.
		 *
		 * @uses wp_enqueue_style()
		 * @uses plugin_dir_url()
		 * @uses add_filter()
		 *
		 * @return void
		 */
		public static function enqueue_scripts_styles( $hook ) {
			if ( 'toplevel_page_wp-live-debug' === $hook ) {
				wp_enqueue_style(
					'wphb-wpmudev-sui',
					plugin_dir_url( __FILE__ ) . 'assets/sui/css/shared-ui.min.css',
					'2.2.10'
				);
				wp_enqueue_script(
					'wphb-wpmudev-sui',
					plugin_dir_url( __FILE__ ) . 'assets/sui/js/shared-ui.min.js',
					array( 'jquery' ),
					'2.2.10',
					true
				);
				wp_enqueue_style(
					'wp-live-debug',
					plugin_dir_url( __FILE__ ) . 'assets/styles.css',
					array( 'wphb-wpmudev-sui' ),
					WP_LIVE_DEBUG_VERSION
				);
				wp_enqueue_script(
					'wp-live-debug',
					plugin_dir_url( __FILE__ ) . 'assets/scripts.js',
					array( 'wphb-wpmudev-sui' ),
					WP_LIVE_DEBUG_VERSION,
					true
				);
				add_filter( 'admin_body_class', array( 'WP_Live_Debug', 'admin_body_classes' ) );
			}
		}

		/**
		 * Add Shared UI Classes to body.
		 *
		 * @param string $classes Maybe existing classes.
		 *
		 * @return string $classes Updated classes list including the Shared-UI classes.
		 */
		public static function admin_body_classes( $classes ) {
			$classes .= ' sui-2-2-10 ';

			return $classes;
		}

		/**
		 * Create the WP Live Debug page.
		 *
		 * @uses get_option()
		 * @uses esc_attr()
		 * @uses esc_html_e()
		 * @uses _e()
		 * @uses WP_Live_Debug_WordPress_Info::create_page()
		 * @uses WP_Live_Debug_Server_Info::create_page();
		 * @uses WP_Live_Debug_Cronjob_Info::create_page();
		 * @uses WP_Live_Debug_Tools::create_page();
		 * @uses WP_Live_Debug_WPMUDEV::create_page();
		 * @uses WP_Live_Debug_Live_Debug::create_page();
		 * @uses WP_Live_Debug_Live_Debug::create_page();
		 *
		 * @return string html The html of the page viewed.
		 */
		public static function create_page() {
			if ( ! empty( $_GET['subpage'] ) ) {
				$subpage = esc_attr( $_GET['subpage'] );
			}
			?>
			<div class="sui-wrap">
				<div class="sui-header">
					<h1 class="sui-header-title">WP Live Debug</h1>
				</div>
				<div class="sui-row-with-sidenav">
					<div class="sui-sidenav">
						<ul class="sui-vertical-tabs sui-sidenav-hide-md">
							<li class="sui-vertical-tab <?php echo ( empty( $subpage ) ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug"><?php esc_html_e( 'Live Debug', 'wp-live-debug' ); ?></a>
							</li>
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'WordPress' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=WordPress"><?php esc_html_e( 'WordPress', 'wp-live-debug' ); ?></a>
							</li>
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'Server' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=Server"><?php esc_html_e( 'Server', 'wp-live-debug' ); ?></a>
							</li>
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'Cron' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=Cron"><?php esc_html_e( 'Scheduled Events', 'wp-live-debug' ); ?></a>
							</li>
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'Tools' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=Tools"><?php esc_html_e( 'Tools', 'wp-live-debug' ); ?></a>
							</li>
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'WPMUDEV' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=WPMUDEV"><?php esc_html_e( 'WPMU DEV', 'wp-live-debug' ); ?></a>
							</li>
						</ul>
						<div class="sui-sidenav-hide-lg">
							<select class="sui-mobile-nav" style="display: none;" onchange="location = this.value;">
								<option value="?page=wp-live-debug" <?php echo ( empty( $subpage ) ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Live Debug', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=WordPress" <?php echo ( ! empty( $subpage ) && 'WordPress' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'WordPress', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=Server" <?php echo ( ! empty( $subpage ) && 'Server' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Server', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=Cron" <?php echo ( ! empty( $subpage ) && 'Cron' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Scheduled Events', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=Tools" <?php echo ( ! empty( $subpage ) && 'Tools' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Tools', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=WPMUDEV" <?php echo ( ! empty( $subpage ) && 'WPMUDEV' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'WPMU DEV', 'wp-live-debug' ); ?></option>
							</select>
						</div>
					</div>
					<?php
					if ( ! empty( $subpage ) ) {
						switch ( $subpage ) {
							case 'WordPress':
								WP_Live_Debug_WordPress_Info::create_page();
								break;
							case 'Server':
								WP_Live_Debug_Server_Info::create_page();
								break;
							case 'Cron':
								WP_Live_Debug_Cronjob_Info::create_page();
								break;
							case 'Tools':
								WP_Live_Debug_Tools::create_page();
								break;
							case 'WPMUDEV':
								WP_Live_Debug_WPMUDEV::create_page();
								break;
							default:
								WP_Live_Debug_Live_Debug::create_page();
						}
					} else {
						WP_Live_Debug_Live_Debug::create_page();
					}
					?>
				</div>
				<?php
				$first_time_running = get_option( 'wp_live_debug_risk' );

				if ( empty( $first_time_running ) ) {
					?>
					<div class="sui-dialog sui-dialog-sm" aria-hidden="true" tabindex="-1" id="safety-popup">
						<div class="sui-dialog-overlay" data-a11y-dialog-hide></div>
						<div class="sui-dialog-content" aria-labelledby="dialogTitle" aria-describedby="dialogDescription" role="dialog">
							<div class="sui-box" role="document">
								<div class="sui-box-header">
									<h3 class="sui-box-title">Safety First!</h3>
								</div>
								<div class="sui-box-body">
									<p>
									<?php
										_e( 'WP LIVE DEBUG enables debugging, checks files and runs various tests to gather information about your installation.', 'wp-live-debug' );
									?>
									</p>
									<p>
									<?php
										_e( 'Make sure to have a <strong>full backup</strong> first before proceeding with any of the tools.', 'wp-live-debug' );
									?>
									</p>
								</div>
								<div class="sui-box-footer">
									<a href="?page=wp-live-debug&wplddlwpconfig=true" class="sui-modal-close sui-button sui-button-green"><?php esc_html_e( 'Download wp-config', 'wp-live-debug' ); ?></a>
									<button id="riskaccept" class="sui-modal-close sui-button sui-button-blue"><?php esc_html_e( 'I understand', 'wp-live-debug' ); ?></button>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}

	// Activation Hook
	register_activation_hook( __FILE__, array( 'WP_Live_Debug', 'on_activate' ) );

	// Deactivation Hook
	register_deactivation_hook( __FILE__, array( 'WP_Live_Debug', 'on_deactivate' ) );

	// Require extra files
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-live-debug.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-wordpress-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-server-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-cronjob-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-tools.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-wpmudev.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-helper.php';

	// Initialize Classes.
	new WP_Live_Debug();
	new WP_Live_Debug_Live_Debug();
	new WP_Live_Debug_WordPress_Info();
	new WP_Live_Debug_Server_Info();
	new WP_Live_Debug_Cronjob_Info();
	new WP_Live_Debug_Tools();
	new WP_Live_Debug_WPMUDEV();
	new WP_Live_Debug_Helper();
}
