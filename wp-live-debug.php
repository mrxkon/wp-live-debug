<?php //phpcs:ignore -- \r\n notice.

/**
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           5.3.2
 * Author:            Konstantinos Xenos
 * Author URI:        https://xkon.gr
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-live-debug
 * Domain Path:       /languages
 *
 * Copyright (C) 2019 Konstantinos Xenos (https://xkon.gr).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://www.gnu.org/licenses/.
 */

namespace WP_Live_Debug;

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Setup various constants.
 */
define( 'WP_LIVE_DEBUG_VERSION', '5.3.2' );
define( 'WP_LIVE_DEBUG_WP_CONFIG', ABSPATH . 'wp-config.php' );
define( 'WP_LIVE_DEBUG_AUTO_BACKUP', ABSPATH . 'wp-config.WPLD-auto.php' );
define( 'WP_LIVE_DEBUG_MANUAL_BACKUP', ABSPATH . 'wp-config.WPLD-manual.php' );
define( 'WP_LIVE_DEBUG_DIR', wp_normalize_path( dirname( __FILE__ ) ) . '/' );
define( 'WP_LIVE_DEBUG_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoload.
 */
spl_autoload_register(
	function( $class ) {
		$prefix = 'WP_Live_Debug\\';
		$len    = strlen( $prefix );

		if ( 0 !== strncmp( $prefix, $class, $len ) ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$path           = explode( '\\', strtolower( str_replace( '_', '-', $relative_class ) ) );
		$file           = array_pop( $path );
		$file           = WP_LIVE_DEBUG_DIR . 'app/php/class-' . $file . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

/**
 * Activation Hook
 */
register_activation_hook( __FILE__, array( '\\WP_Live_Debug\\Setup', 'activate' ) );

/**
 * Dectivation Hook
 */
register_deactivation_hook( __FILE__, array( '\\WP_Live_Debug\\Setup', 'deactivate' ) );

/**
 * Load plugin.
 */
add_action( 'plugins_loaded', array( '\\WP_Live_Debug\\Setup', 'get_instance' ) );
