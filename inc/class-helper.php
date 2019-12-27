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

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;

/**
 * Helper Class.
 */
class Helper {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// silence.
	}

	/**
	 * Create the debug.log if it doesn't exist.
	 */
	public static function create_debug_log() {
		$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );

		if ( ! file_exists( $log_file ) ) {
			$fo = fopen( $log_file, 'w' ) or die( 'Cannot create debug.log!' );

			fwrite( $fo, '' );

			fclose( $fo );
		}

		update_option( 'wp_live_debug_log_file', $log_file );
	}

	/**
	 * Create the wp-config.wpld-original-backup.php
	 */
	public static function get_first_backup() {
		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG ) ) {
			copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL );
		}
	}

	/**
	 * Delete the wp-config.wpld-manual-backup.php on deactivation
	 */
	public static function clear_manual_backup() {
		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG_BACKUP ) ) {
			unlink( WP_LIVE_DEBUG_WP_CONFIG_BACKUP );
		}
	}

	/**
	 * Check if manual wp-config.php backup exists.
	 */
	public static function check_wp_config_backup() {
		if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG_BACKUP ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Gather log files.
	 */
	public static function gather_log_files() {
		$skip_names = array(
			'changelog',
			'CHANGELOG',
			'readme',
			'README',
			'license',
			'LICENSE',
			'copying',
			'COPYING',
			'contributors',
			'CONTRIBUTORS',
			'license.commercial',
			'LICENSE.COMMERCIAL',
		);

		$accept_names = array( 'error_log' );

		$extensions = array(
			'log',
			'txt',
		);

		$skip_dirs = array(
			'.git',
			'vendor',
			'node_modules',
		);

		// Initialize log_files array.
		$log_files = array();

		// Create filter to skip folders.
		$filter = function ( $file, $key, $iterator ) use ( $skip_dirs ) {
			if ( $iterator->hasChildren() && ! in_array( $file->getFilename(), $skip_dirs, true ) ) {
				return true;
			}

			return $file->isFile();
		};

		// Go through the folders and files to gather information.
		$directory = new RecursiveDirectoryIterator( ABSPATH, RecursiveDirectoryIterator::SKIP_DOTS );
		$iterator = new RecursiveIteratorIterator( new RecursiveCallbackFilterIterator( $directory, $filter ) );

		foreach ( $iterator as $file ) {
			if ( is_file( $file ) ) {
				$filename = $file->getBasename( '.' . $file->getExtension() );
				if ( ! in_array( $filename, $skip_names, true ) && in_array( $file->getExtension(), $extensions, true ) ||
					! in_array( $filename, $skip_names, true ) && in_array( $filename, $accept_names, true ) ) {
					$log_files[] = wp_normalize_path( $file->getPathname() );
				}
			}
		}

		return $log_files;
	}
}
