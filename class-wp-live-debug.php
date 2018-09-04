<?php
/**
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           4.9.8.2
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
 * Various Defines
 */
define( 'WP_LIVE_DEBUG_VERSION', '4.9.8' );

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
			add_action( 'admin_enqueue_scripts', array( 'WP_Live_Debug', 'load_scripts_styles' ) );
			add_action( 'wp_ajax_wp-live-debug-accept-risk', array( 'WP_Live_Debug', 'accept_risk' ) );
		}

		/**
		 * Accept Risk
		 */
		public static function accept_risk() {
			update_option( 'wp_live_debug_risk', 'yes' );

			$response = array(
				'message' => esc_html__( 'risk accepted.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Activation
		 */
		public static function on_activate() {
			$host = get_site_url();
			$host = str_replace( array( 'http://', 'https://' ), '', $host );
			update_option( 'wp_live_debug_ssl_domain', $host );
		}

		/**
		 * Deactivation
		 */

		public static function on_deactivate() {
			delete_option( 'wp_live_debug_risk' );
			delete_option( 'wp_live_debug_ssl_domain' );
		}

		/**
		 * Create Menus
		 */
		public static function create_menus() {
			if ( ! is_multisite() ) {
				add_action( 'admin_menu', array( 'WP_Live_Debug', 'create_admin_menu' ) );
			} else {
				add_action( 'network_admin_menu', array( 'WP_Live_Debug', 'create_admin_menu' ) );
			}
		}

		/**
		 * Create Admin menu.
		 *
		 * @uses add_menu_page()
		 *
		 * @return void
		 */
		public static function create_admin_menu() {
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
		 * Load scripts and styles
		 */
		public static function load_scripts_styles( $hook ) {
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
		 * Add Shared UI 2.2.10
		 */
		public static function admin_body_classes( $classes ) {
			$classes .= ' sui-2-2-10 ';
			return $classes;
		}

		/**
		 * Create Page
		 */
		public static function create_page() {
			$first_time_running = get_option( 'wp_live_debug_risk' );

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
								<a href="?page=wp-live-debug&subpage=Cron"><?php esc_html_e( 'Scheduled Tasks', 'wp-live-debug' ); ?></a>
							</li>
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'Tools' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=Tools"><?php esc_html_e( 'Tools', 'wp-live-debug' ); ?></a>
							</li>
						</ul>
						<div class="sui-sidenav-hide-lg">
							<select class="sui-mobile-nav" style="display: none;" onchange="location = this.value;">
								<option value="?page=wp-live-debug" <?php echo ( empty( $subpage ) ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Live Debug', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=WordPress" <?php echo ( ! empty( $subpage ) && 'WordPress' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'WordPress', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=Server" <?php echo ( ! empty( $subpage ) && 'Server' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Server', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=Cron" <?php echo ( ! empty( $subpage ) && 'Cron' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Scheduled Tasks', 'wp-live-debug' ); ?></option>
								<option value="?page=wp-live-debug&subpage=Tools" <?php echo ( ! empty( $subpage ) && 'Tools' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Tools', 'wp-live-debug' ); ?></option>
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
							default:
								WP_Live_Debug_Live_Debug::create_page();
						}
					} else {
						WP_Live_Debug_Live_Debug::create_page();
					}
					?>
				</div>
				<?php if ( empty( $first_time_running ) ) : ?>
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
								<button id="riskaccept" class="sui-modal-close sui-button sui-button-blue"><?php esc_html_e( 'I understand', 'wp-live-debug' ); ?></button>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<?php
		}

		public static function table_info( $list ) {
			$output = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			foreach ( $list as $key => $value ) {
				$output .= '<tr><td>' . esc_html( $key ) . '</td><td>' . $value . '</td></tr>';
			}
			$output .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$output .= '</tbody></table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function format_num( $val ) {
			if ( is_numeric( $val ) and ( $val >= ( 1024 * 1024 ) ) ) {
				$val = size_format( $val );
			}

			return $val;
		}

		public static function format_constant( $constant ) {
			if ( ! defined( $constant ) ) {
				return '<em>undefined</em>';
			}

			$val = constant( $constant );

			if ( ! is_bool( $val ) ) {
				return $val;
			} elseif ( ! $val ) {
				return 'FALSE';
			} else {
				return 'TRUE';
			}
		}

	}
	// Activation Hook
	register_activation_hook( __FILE__, array( 'WP_Live_Debug', 'on_activate' ) );

	// Deactivation Hook
	register_deactivation_hook( __FILE__, array( 'WP_Live_Debug', 'on_deactivate' ) );

	// Include extra classes
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-live-debug.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-wordpress-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-server-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-cronjob-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-tools.php';

	// Initialize WP Live Debug.
	new WP_Live_Debug();
	new WP_Live_Debug_WordPress_Info();
	new WP_Live_Debug_Server_Info();
	new WP_Live_Debug_Cronjob_Info();
	new WP_Live_Debug_Tools();
}
