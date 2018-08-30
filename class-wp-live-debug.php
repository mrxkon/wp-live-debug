<?php
/**
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           4.9.8
 * Author:            Xenos (xkon) Konstantinos
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
This is a personal project that I use for debugging, but some parts of the code are written by other awesome developers.

So props also go to:
Calum Brash, Aaron Edwards, Philipp Stracker, Victor Ivanov, Vladislav Bailovic, Jeffri H, Marko Miljus
WPMU DEV Shared UI - Licence GPLv2 - ( https://premium.wpmudev.org ) - ( https://github.com/wpmudev/shared-ui )

The WordPress.org community
Health Check - Licence GPLv2 - ( https://wordpress.org/plugins/health-check/ ) - ( https://github.com/wordpress/health-check )
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
			add_action( 'init', array( 'WP_Live_Debug', 'create_menus' ) );
			add_action( 'admin_enqueue_scripts', array( 'WP_Live_Debug', 'load_scripts_styles' ) );
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
				__( 'WP Live Debug', 'wp-live-debug' ),
				__( 'WP Live Debug', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug',
				array( 'WP_Live_Debug', 'create_page' ),
				'dashicons-media-code'
			);
			add_submenu_page(
				'wp-live-debug',
				__( 'PHP', 'wp-live-debug' ),
				__( 'PHP', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug-php-info',
				array( 'WP_Live_Debug_PHP_Info', 'create_page' )
			);
			// add_submenu_page(
			// 	'wp-live-debug',
			// 	__( 'WordPress', 'wp-live-debug' ),
			// 	__( 'WordPress', 'wp-live-debug' ),
			// 	'manage_options',
			// 	'wp-live-debug-wp-info',
			// 	array( 'WP_Live_Debug_WP_Info', 'create_page' )
			// );
			add_submenu_page(
				'wp-live-debug',
				__( 'Server', 'wp-live-debug' ),
				__( 'Server', 'wp-live-debug' ),
				'manage_options',
				'wp-live-debug-server-info',
				array( 'WP_Live_Debug_Server_Info', 'create_page' )
			);
		}

		/**
		 * Load scripts and styles
		 */
		public static function load_scripts_styles( $hook ) {
			if ( 'toplevel_page_wp-live-debug' === $hook ) {
				wp_enqueue_style(
					'wp-live-debug',
					plugin_dir_url( __FILE__ ) . 'assets/styles.css',
					WP_LIVE_DEBUG_VERSION
				);
				wp_enqueue_script(
					'wp-live-debug',
					plugin_dir_url( __FILE__ ) . 'assets/scripts.js',
					array( 'jquery' ),
					WP_LIVE_DEBUG_VERSION,
					true
				);
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

				add_filter( 'admin_body_class', array( 'WP_Live_Debug', 'admin_body_classes' ) );
			}
		}

		/**
		 * Add Shared UI 2.2.10
		 */
		public static function admin_body_classes( $classes ) {
			$classes .= 'sui-2-2-10';
			return $classes;
		}

		/**
		 * Create Page
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
							<li class="sui-vertical-tab <?php echo ( ! empty( $subpage ) && 'PHP' === $subpage ) ? 'current' : ''; ?>">
								<a href="?page=wp-live-debug&subpage=PHP"><?php esc_html_e( 'PHP Info', 'wp-live-debug' ); ?></a>
							</li>
						</ul>
						<div class="sui-sidenav-hide-lg">
							<select class="sui-mobile-nav" style="display: none;">
								<option value="#livedebug" <?php echo ( empty( $subpage ) ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Live Debug', 'wp-live-debug' ); ?></option>
								<option value="#WordPress" <?php echo ( ! empty( $subpage ) && 'WordPress' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'WordPress', 'wp-live-debug' ); ?></option>
								<option value="#Server" <?php echo ( ! empty( $subpage ) && 'Server' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Server', 'wp-live-debug' ); ?></option>
								<option value="#PHP" <?php echo ( ! empty( $subpage ) && 'PHP' === $subpage ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'PHP', 'wp-live-debug' ); ?></option>
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
							case 'PHP':
								WP_Live_Debug_PHP_Info::create_page();
								break;
							default:
								WP_Live_Debug_Live_Debug::create_page();
						}
					} else {
						WP_Live_Debug_Live_Debug::create_page();
					}
					?>
				</div>
			</div>
			<?php
		}

	}
	// Include extra classes
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-live-debug.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-php-info.php';
	require_once plugin_dir_path( __FILE__ ) . '/classes/class-wp-live-debug-server-info.php';
	// Initialize WP Live Debug.
	new WP_Live_Debug();
}
