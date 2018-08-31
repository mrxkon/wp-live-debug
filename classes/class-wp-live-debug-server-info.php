<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Server_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_Server_Info' ) ) {
	class WP_Live_Debug_Server_Info {

		/**
		 * WP_Live_Debug_Server_Info constructor.
		 *
		 * @uses WP_Live_Debug_Server_Info::init()
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
		public static function init() {
			// silence
		}

		public static function create_page() {
			?>
<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title">Server</h2>
					</div>
					<div class="sui-box-body">
						<table class="sui-table striped">
							<tbody>
								<?php WP_Live_Debug::table_info( WP_Live_Debug_Server_Info::get_server_info() ); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title">MySQL</h2>
					</div>
					<div class="sui-box-body">
						<table class="sui-table striped">
							<tbody>
								<?php WP_Live_Debug::table_info( WP_Live_Debug_Server_Info::get_mysql_info() ); ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title">PHP</h2>
					</div>
					<div class="sui-box-body">
						<table class="sui-table striped">
							<tbody>
								<?php WP_Live_Debug::table_info( WP_Live_Debug_Server_Info::get_php_info() ); ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php
		}

		public static function get_server_info() {
			$server      = array();
			$server_info = explode( ' ', $_SERVER['SERVER_SOFTWARE'] );
			$server_info = explode( '/', reset( $server_info ) );

			if ( isset( $server_info[1] ) ) {
				$server_version = $server_info[1];
			} else {
				$server_version = 'Unknown';
			}
			$lt = localtime();

			$server['Software Name']     = $server_info[0];
			$server['Software Version']  = $server_version;
			$server['Server IP']         = @$_SERVER['SERVER_ADDR']; // phpcs:ignore
			$server['Server Hostname']   = @$_SERVER['SERVER_NAME']; // phpcs:ignore
			$server['Server Admin']      = @$_SERVER['SERVER_ADMIN']; // phpcs:ignore
			$server['Server local time'] = date( 'Y-m-d H:i:s (\U\T\C P)' );
			$server['Operating System']  = @php_uname( 's' ); // phpcs:ignore
			$server['OS Hostname']       = @php_uname( 'n' ); // phpcs:ignore
			$server['OS Version']        = @php_uname( 'v' ); // phpcs:ignore

			return  $server;
		}

		public static function get_mysql_info() {
			global $wpdb;

			$mysql = array();

			$mysql_vars = array(
				'key_buffer_size'    => true,
				'max_allowed_packet' => false,
				'max_connections'    => false,
				'query_cache_limit'  => true,
				'query_cache_size'   => true,
				'query_cache_type'   => 'ON',
			);

			$extra_info = array();

			$variables = $wpdb->get_results( "SHOW VARIABLES WHERE Variable_name IN ( '" . implode( "', '", array_keys( $mysql_vars ) ) . "' )" ); // phpcs:ignore

			$dbh = $wpdb->dbh;

			if ( is_resource( $dbh ) ) {
				$driver = 'mysql';
				if ( function_exists( 'mysqli_get_server_info' ) ) {
					// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_get_server_info
					$version = mysqli_get_server_info( $dbh );
				} else {
					// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysql_get_server_info
					$version = mysql_get_server_info( $dbh );
				}
			} elseif ( is_object( $dbh ) ) {
				$driver = get_class( $dbh );
				if ( method_exists( $dbh, 'db_version' ) ) {
					$version = $dbh->db_version();
				} elseif ( isset( $dbh->server_info ) ) {
					$version = $dbh->server_info;
				} elseif ( isset( $dbh->server_version ) ) {
					$version = $dbh->server_version;
				} else {
					$version = __( 'Unknown', 'wp-live-debug' );
				}
				if ( isset( $dbh->client_info ) ) {
					$extra_info['Driver Version'] = $dbh->client_info;
				}
				if ( isset( $dbh->host_info ) ) {
					$extra_info['Connection'] = $dbh->host_info;
				}
			} else {
				$version = __( 'Unknown', 'wp-live-debug' );
				$driver  = __( 'Unknown', 'wp-live-debug' );
			}

			$extra_info['Database']     = $wpdb->dbname;
			$extra_info['Charset']      = $wpdb->charset;
			$extra_info['Collate']      = $wpdb->collate;
			$extra_info['Table Prefix'] = $wpdb->prefix;

			$mysql['Version'] = $version;
			$mysql['Driver']  = $driver;

			foreach ( $extra_info as $key => $val ) {
				$mysql[ $key ] = $val;
			}

			foreach ( $mysql_vars as $key => $val ) {
				$mysql[ $key ] = $val;
			}

			foreach ( $variables as $item ) {
				$mysql[ $item->Variable_name ] = WP_Live_Debug::format_num( $item->Value ); // phpcs:ignore
			}

			return $mysql;
		}

		public static function get_php_info() {
			$php = array();

			$php_vars = array(
				'max_execution_time',
				'open_basedir',
				'memory_limit',
				'upload_max_filesize',
				'post_max_size',
				'display_errors',
				'log_errors',
				'track_errors',
				'session.auto_start',
				'session.cache_expire',
				'session.cache_limiter',
				'session.cookie_domain',
				'session.cookie_httponly',
				'session.cookie_lifetime',
				'session.cookie_path',
				'session.cookie_secure',
				'session.gc_divisor',
				'session.gc_maxlifetime',
				'session.gc_probability',
				'session.referer_check',
				'session.save_handler',
				'session.save_path',
				'session.serialize_handler',
				'session.use_cookies',
				'session.use_only_cookies',
			);

			$php['Version'] = phpversion();

			foreach ( $php_vars as $setting ) {
				$php[ $setting ] = ini_get( $setting );
			}

			$php['Error Reporting'] = implode( ', ', WP_Live_Debug_Server_Info::get_php_error_info() );
			$extensions             = get_loaded_extensions();

			natcasesort( $extensions );

			$php['Extensions'] = implode( ', ', $extensions );

			return $php;
		}

		public static function get_php_error_info() {
			$errors = array();

			$error_reporting = error_reporting();

			$constants = array(
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
