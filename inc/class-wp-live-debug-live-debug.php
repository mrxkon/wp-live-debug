<?php // phpcs:ignore

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Live_Debug Class.
 */
if ( ! class_exists( 'WP_Live_Debug_Live_Debug' ) ) {
	class WP_Live_Debug_Live_Debug {

		/**
		 * WP_Live_Debug_Live_Debug constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_wp-live-debug-read-log', array( 'WP_Live_Debug_Live_Debug', 'read_debug_log' ) );
			add_action( 'wp_ajax_wp-live-debug-select-log', array( 'WP_Live_Debug_Live_Debug', 'select_log_file' ) );
			add_action( 'wp_ajax_wp-live-debug-clear-debug-log', array( 'WP_Live_Debug_Live_Debug', 'clear_debug_log' ) );
			add_action( 'wp_ajax_wp-live-debug-delete-debug-log', array( 'WP_Live_Debug_Live_Debug', 'delete_debug_log' ) );
			add_action( 'wp_ajax_wp-live-debug-refresh-debug-log', array( 'WP_Live_Debug_Live_Debug', 'refresh_debug_log' ) );
			add_action( 'wp_ajax_wp-live-debug-create-backup', array( 'WP_Live_Debug_Live_Debug', 'create_wp_config_backup' ) );
			add_action( 'wp_ajax_wp-live-debug-restore-backup', array( 'WP_Live_Debug_Live_Debug', 'restore_wp_config_backup' ) );
			add_action( 'wp_ajax_wp-live-debug-enable', array( 'WP_Live_Debug_Live_Debug', 'enable_wp_debug' ) );
			add_action( 'wp_ajax_wp-live-debug-disable', array( 'WP_Live_Debug_Live_Debug', 'disable_wp_debug' ) );
			add_action( 'wp_ajax_wp-live-debug-enable-script-debug', array( 'WP_Live_Debug_Live_Debug', 'enable_script_debug' ) );
			add_action( 'wp_ajax_wp-live-debug-disable-script-debug', array( 'WP_Live_Debug_Live_Debug', 'disable_script_debug' ) );
			add_action( 'wp_ajax_wp-live-debug-enable-savequeries', array( 'WP_Live_Debug_Live_Debug', 'enable_savequeries' ) );
			add_action( 'wp_ajax_wp-live-debug-disable-savequeries', array( 'WP_Live_Debug_Live_Debug', 'disable_savequeries' ) );
			add_action( 'admin_init', array( 'WP_Live_Debug_Live_Debug', 'download_config_backup' ) );
		}

		/**
		 * Refresh debug log toggle
		 */
		public static function refresh_debug_log() {
			if ( ! empty( $_POST['checked'] ) && 'true' === $_POST['checked'] ) {
				update_option( 'wp_live_debug_auto_refresh', 'enabled' );

				$response = array(
					'message' => esc_html__( 'enabled', 'wp-live-debug' ),
				);
			} else {
				update_option( 'wp_live_debug_auto_refresh', 'disabled' );

				$response = array(
					'message' => esc_html__( 'disabled', 'wp-live-debug' ),
				);
			}

			wp_send_json_success( $response );
		}

		/**
		 * Force download wp-config original backup
		 */
		public static function download_config_backup() {
			if ( ! empty( $_GET['wplddlwpconfig'] ) && 'true' === $_GET['wplddlwpconfig'] ) {
				$filename = 'wp-config-' . str_replace( array( 'http://', 'https://' ), '', get_site_url() ) . '-' . wp_date( 'Ymd-Hi' ) . '-backup.php';
				header( 'Content-type: textplain;' );
				header( 'Content-disposition: attachment; filename= ' . $filename );
				readfile( WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL );
				exit();
			}
		}
		/**
		 * Check if original wp-config.php backup exists.
		 */
		public static function check_wp_config_original_backup() {
			if ( file_exists( WP_LIVE_DEBUG_WP_CONFIG_BACKUP_ORIGINAL ) ) {
				return true;
			}

			return false;
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
		 * Creates a backup of wp-config.php.
		 */
		public static function create_wp_config_backup() {
			if ( ! copy( WP_LIVE_DEBUG_WP_CONFIG, WP_LIVE_DEBUG_WP_CONFIG_BACKUP ) ) {
				$response = array(
					'message' => esc_html__( 'wp-config.php backup failed.', 'wp-live-debug' ),
				);

				wp_send_json_error( $response );
			}

			$response = array(
				'message' => esc_html__( 'wp-config.php backup was created.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Restores a backup of wp-config.php.
		 */
		public static function restore_wp_config_backup() {
			if ( ! copy( WP_LIVE_DEBUG_WP_CONFIG_BACKUP, WP_LIVE_DEBUG_WP_CONFIG ) ) {
				$response = array(
					'message' => esc_html__( 'wp-config.php restore failed.', 'wp-live-debug' ),
				);

				wp_send_json_error( $response );
			}

			unlink( WP_LIVE_DEBUG_WP_CONFIG_BACKUP );

			$response = array(
				'message' => esc_html__( 'wp-config.php backup was restored.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Enables WP_DEBUG.
		 */
		public static function enable_wp_debug() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'WP_DEBUG'" ) || false !== strpos( $line, '"WP_DEBUG"' ) ) {
					$line      = "define( 'WP_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'WP_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );

			WP_Live_Debug_Live_Debug::enable_wp_debug_log();
			WP_Live_Debug_Live_Debug::disable_wp_debug_display();
			WP_Live_Debug_Live_Debug::disable_wp_debug_ini_set_display();

			$response = array(
				'message' => esc_html__( 'WP_DEBUG was enabled.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Disables WP_DEBUG
		 */
		public static function disable_wp_debug() {
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'WP_DEBUG'" ) || false !== strpos( $line, '"WP_DEBUG"' ) ) {
					$line = "define( 'WP_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
				}

				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );

			WP_Live_Debug_Live_Debug::disable_wp_debug_log();
			WP_Live_Debug_Live_Debug::disable_wp_debug_display();
			WP_Live_Debug_Live_Debug::disable_wp_debug_ini_set_display();

			$response = array(
				'message' => esc_html__( 'WP_DEBUG was disabled.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Enable WP_DEBUG_LOG.
		 */
		public static function enable_wp_debug_log() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'WP_DEBUG_LOG'" ) || false !== strpos( $line, '"WP_DEBUG_LOG"' ) ) {
					$line      = "define( 'WP_DEBUG_LOG', true ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'WP_DEBUG_LOG', true ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );
		}

		/**
		 * Disable WP_DEBUG_LOG.
		 */
		public static function disable_wp_debug_log() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'WP_DEBUG_LOG'" ) || false !== strpos( $line, '"WP_DEBUG_LOG"' ) ) {
					$line      = "define( 'WP_DEBUG_LOG', false ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}
				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'WP_DEBUG_LOG', false ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );
		}

		/**
		 * Disable WP_DEBUG_DISPLAY.
		 */
		public static function disable_wp_debug_display() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'WP_DEBUG_DISPLAY'" ) || false !== strpos( $line, '"WP_DEBUG_DISPLAY"' ) ) {
					$line      = "define( 'WP_DEBUG_DISPLAY', false ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'WP_DEBUG_DISPLAY', false ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );
		}

		/**
		 * Disable ini_set display_errors.
		 */
		public static function disable_wp_debug_ini_set_display() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'display_errors'" ) || false !== strpos( $line, '"display_errors"' ) ) {
					$line      = "@ini_set( 'display_errors', 0 ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "@ini_set( 'display_errors', 0 ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );
		}

		/**
		 * Enable SCRIPT_DEBUG.
		 */
		public static function enable_script_debug() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'SCRIPT_DEBUG'" ) || false !== strpos( $line, '"SCRIPT_DEBUG"' ) ) {
					$line      = "define( 'SCRIPT_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}
				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'SCRIPT_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );

			$response = array(
				'message' => esc_html__( 'SCRIPT_DEBUG was enabled.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Disable SCRIPT_DEBUG.
		 */
		public static function disable_script_debug() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'SCRIPT_DEBUG'" ) || false !== strpos( $line, '"SCRIPT_DEBUG"' ) ) {
					$line      = "define( 'SCRIPT_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'SCRIPT_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );

			$response = array(
				'message' => esc_html__( 'SCRIPT_DEBUG was disabled.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Enable SAVEQUERIES.
		 */
		public static function enable_savequeries() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'SAVEQUERIES'" ) || false !== strpos( $line, '"SAVEQUERIES"' ) ) {
					$line      = "define( 'SAVEQUERIES', true ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'SAVEQUERIES', true ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );

			$response = array(
				'message' => esc_html__( 'SAVEQUERIES was enabled.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Disable SAVEQUERIES.
		 */
		public static function disable_savequeries() {
			$not_found        = true;
			$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

			file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

			$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, "'SAVEQUERIES'" ) || false !== strpos( $line, '"SAVEQUERIES"' ) ) {
					$line      = "define( 'SAVEQUERIES', false ); // Added by WP Live Debug" . PHP_EOL;
					$not_found = false;
				}

				fputs( $write_wpconfig, $line );
			}

			if ( $not_found ) {
				$editing_wpconfig = file( WP_LIVE_DEBUG_WP_CONFIG );

				file_put_contents( WP_LIVE_DEBUG_WP_CONFIG, '' );

				$write_wpconfig = fopen( WP_LIVE_DEBUG_WP_CONFIG, 'w' );

				foreach ( $editing_wpconfig as $line ) {
					if ( false !== strpos( $line, 'stop editing!' ) ) {
						$line  = "define( 'SAVEQUERIES', false ); // Added by WP Live Debug" . PHP_EOL;
						$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
					}

					fputs( $write_wpconfig, $line );
				}
			}

			fclose( $write_wpconfig );

			$response = array(
				'message' => esc_html__( 'SAVEQUERIES was disabled.', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Read log.
		 */
		public static function read_debug_log() {
			$log_file = get_option( 'wp_live_debug_log_file' );

			if ( file_exists( $log_file ) ) {
				if ( 2000000 > filesize( $log_file ) ) {
					$debug_contents = file_get_contents( $log_file );
					if ( empty( $debug_contents ) ) {
						// translators: %1$s log filename.
						$debug_contents = sprintf( esc_html__( 'Awesome! %1$s seems to be empty.', 'wp-live-deubg' ), basename( $log_file ) );
					}
				} else {
					// translators: %1$s log filename.
					$debug_contents = sprintf( esc_html__( '%1$s is over 2 MB. Please open it via FTP.', 'wp-live-debug' ), basename( $log_file ) );
				}
			} else {
				// translators: %1$s log filename.
				$debug_contents = sprintf( esc_html__( 'Could not find %1$s file.', 'wp-live-deubg' ), basename( $log_file ) );

			}

			echo $debug_contents;

			wp_die();
		}

		/**
		 * Select log.
		 */
		public static function select_log_file() {
			$nonce    = sanitize_text_field( $_POST['nonce'] );
			$log_file = sanitize_text_field( $_POST['log'] );

			if ( ! wp_verify_nonce( $nonce, $log_file ) ) {
				wp_send_json_error();
			}

			if ( 'log' != substr( strrchr( $log_file, '.' ), 1 ) ) {
				wp_send_json_error();
			}

			update_option( 'wp_live_debug_log_file', $log_file );

			wp_send_json_success();
		}

		/**
		 * Clear log.
		 */
		public static function clear_debug_log() {
			$nonce    = sanitize_text_field( $_POST['nonce'] );
			$log_file = sanitize_text_field( $_POST['log'] );

			if ( ! wp_verify_nonce( $nonce, $log_file ) ) {
				$response = array(
					'message' => esc_html__( 'Could not validate nonce', 'wp-live-debug' ),
				);
				wp_send_json_error( $response );
			}

			if ( 'log' != substr( strrchr( $log_file, '.' ), 1 ) ) {
				$response = array(
					'message' => esc_html__( 'This is not a log file.', 'wp-live-debug' ),
				);

				wp_send_json_error( $response );
			}

			file_put_contents( $log_file, '' );

			$response = array(
				'message' => esc_html__( '.log was cleared', 'wp-live-debug' ),
			);

			wp_send_json_success( $response );
		}

		/**
		 * Delete log.
		 */
		public static function delete_debug_log() {
			$nonce    = sanitize_text_field( $_POST['nonce'] );
			$log_file = sanitize_text_field( $_POST['log'] );

			if ( ! wp_verify_nonce( $nonce, $log_file ) ) {
				wp_send_json_error();
			}

			if ( 'log' != substr( strrchr( $log_file, '.' ), 1 ) ) {
				wp_send_json_error();
			}

			unlink( $log_file );

			WP_Live_Debug_Helper::create_debug_log();

			$log_file = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );

			update_option( 'wp_live_debug_log_file', $log_file );

			wp_send_json_success();
		}
	}
}
