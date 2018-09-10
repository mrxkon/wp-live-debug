<?php //phpcs:ignore

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Helper Class.
 */
if ( ! class_exists( 'WP_Live_Debug_Helper' ) ) {
	class WP_Live_Debug_Helper {

		/**
		 * WP_Live_Debug_Helper constructor.
		 *
		 * @uses WP_Live_Debug_Helper::init()
		 *
		 * @return void
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Plugin initialization.
		 *
		 * @return void
		 */
		public static function init() {
			// silence
		}

		/**
		 * Get the wp-config.original.php backup
		 *
		 * @return void
		 */
		public static function get_first_backup() {
			copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL );
		}

		/**
		 * Create the debug.log if it doesn't exist.
		 *
		 * @uses wp_normalize_path()
		 * @uses update_option()
		 *
		 * @return void
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
		 * Create table for WPMU DEV plugin constants.
		 *
		 * @param array $array The array of constants.
		 *
		 * @uses esc_html()
		 *
		 * @return string html of the table.
		 */
		public static function table_wpmudev_constants( $array ) {
			$table = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Default Value', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			foreach ( $array as $key => $value ) {
				$table .= '<tr><td>' . esc_html( $value[0] ) . '</td><td>' . $value[1] . '</td><td>' . $value[2] . '</td></tr>';

				if ( ! empty( $value[3] ) ) {
					$table .= '<tr><td colspan="3"><em>' . esc_html( $value[3] ) . '</em></td></tr>';
				}
			}

			$table .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Default Value', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$table .= '</tbody></table>';

			return $table;
		}

		/**
		 * Create table for WPMU DEV plugin actions and filters.
		 *
		 * @param array $array The array of actions and filters.
		 *
		 * @uses esc_html()
		 *
		 * @return string html of the table.
		 */
		public static function table_wpmudev_actions_filters( $array ) {
			$table  = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Filters', 'wp-live-debug' ) . '</th></tr></thead><tbody>';
			$table .= '<tr><td>';

			foreach ( $array['actions'] as $action ) {
				$table .= $action . '<br>';
			}

			$table .= '</td><td>';

			foreach ( $array['filters'] as $filter ) {
				$table .= $filter . '<br>';
			}

			$table .= '</td><tr>';
			$table .= '<tfoot><tr><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Filters', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$table .= '</tbody></table>';

			return $table;
		}

		/**
		 * Create a general use table.
		 *
		 * @param array $array The array of table cells.
		 *
		 * @uses esc_html()
		 *
		 * @return string html of the table.
		 */
		public static function table_general( $array ) {
			$table = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			foreach ( $array as $key => $value ) {
				$table .= '<tr><td>' . esc_html( $key ) . '</td><td>' . $value . '</td></tr>';
			}

			$table .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$table .= '</tbody></table>';

			return $table;
		}

		/**
		 * Format constant.
		 *
		 * @param string $constant The constant to format.
		 *
		 * @uses esc_html()
		 *
		 * @return string Constant value.
		 */
		public static function format_constant( $constant ) {
			if ( ! defined( $constant ) ) {
				return '<em>' . esc_html__( 'Undefined', 'wp-live-debug' ) . '</em>';
			}

			$value = constant( $constant );

			if ( ! is_bool( $value ) ) {
				return $value;
			} elseif ( ! $value ) {
				return 'FALSE';
			} else {
				return 'TRUE';
			}
		}

		/**
		 * Format constant.
		 *
		 * @param int $value The number to format.
		 *
		 * @uses size_format()
		 *
		 * @return string $value Formatted number.
		 */
		public static function format_num( $value ) {
			if ( is_numeric( $value ) and ( $value >= ( 1024 * 1024 ) ) ) {
				$value = size_format( $value );
			}

			return $value;
		}

		/**
		 * Get database size.
		 *
		 * @uses wpdb
		 * @uses get_results()
		 *
		 * @return string $size Database size.
		 */
		public static function get_database_size() {
			global $wpdb;

			$size = 0;
			$rows = $wpdb->get_results( 'SHOW TABLE STATUS', ARRAY_A );

			if ( $wpdb->num_rows > 0 ) {
				foreach ( $rows as $row ) {
					$size += $row['Data_length'] + $row['Index_length'];
				}
			}

			return $size;
		}

		/**
		 * Get directory size.
		 *
		 * @param string $dir The directory to search.
		 *
		 * @uses RecursiveIteratorIterator
		 * @uses getSize()
		 *
		 * @return string $size Directory size.
		 */
		public static function get_directory_size( $dir ) {
			$size = 0;

			foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir ) ) as $file ) {
				$size += $file->getSize();
			}

			return $size;
		}

		/**
		 * Get PHP errors.
		 *
		 * @return string $size PHP errors.
		 */
		public static function get_php_errors() {
			$errors          = array();
			$error_reporting = error_reporting();
			$constants       = array(
				'E_ERROR',
				'E_WARNING',
				'E_PARSE',
				'E_NOTICE',
				'E_CORE_ERROR',
				'E_CORE_WARNING',
				'E_COMPILE_ERROR',
				'E_COMPILE_WARNING',
				'E_USER_ERROR',
				'E_USER_WARNING',
				'E_USER_NOTICE',
				'E_STRICT',
				'E_RECOVERABLE_ERROR',
				'E_DEPRECATED',
				'E_USER_DEPRECATED',
				'E_ALL',
			);

			foreach ( $constants as $error ) {
				if ( defined( $error ) ) {
					$c = constant( $error );

					if ( $error_reporting & $c ) {
						$errors[ $c ] = $error;
					}
				}
			}

			return $errors;
		}
	}
}
