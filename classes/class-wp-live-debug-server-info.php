<?php //phpcs:ignore

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
			add_action( 'wp_ajax_wp-live-debug-server-info-server-info', array( 'WP_Live_Debug_Server_Info', 'server_info' ) );
			add_action( 'wp_ajax_wp-live-debug-server-info-mysql-info', array( 'WP_Live_Debug_Server_Info', 'mysql_info' ) );
			add_action( 'wp_ajax_wp-live-debug-server-info-php-info', array( 'WP_Live_Debug_Server_Info', 'php_info' ) );
		}

		/**
		 * Create the Server page.
		 *
		 * @uses esc_html_e()
		 * @uses WP_Live_Debug_Server_Info::phpinfo_info()
		 *
		 * @return string The html of the page viewed.
		 */
		public static function create_page() {
			?>
				<div class="sui-box">
					<div class="sui-box-body">
						<div class="sui-tabs">
							<div data-tabs>
								<div class="active"><?php esc_html_e( 'Server', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'MySQL', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'PHP', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'phpinfo()', 'wp-live-debug' ); ?></div>
							</div>
							<div data-panes>
								<div id="server-info" class="active">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="mysql-info">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="php-info">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="phpinfo-info">
									<?php WP_Live_Debug_Server_Info::phpinfo_info(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
		}

		/**
		 * Get the Server information.
		 *
		 * @uses WP_Live_Debug_Helper::table_general()
		 *
		 * @return string json success with the response.
		 */
		public static function server_info() {
			$server      = array();
			$server_info = explode( ' ', $_SERVER['SERVER_SOFTWARE'] );
			$server_info = explode( '/', reset( $server_info ) );

			if ( isset( $server_info[1] ) ) {
				$server_version = $server_info[1];
			} else {
				$server_version = 'Unknown';
			}

			$server['Software Name']     = $server_info[0];
			$server['Software Version']  = $server_version;
			$server['Server IP']         = @$_SERVER['SERVER_ADDR']; // phpcs:ignore
			$server['Server Hostname']   = @$_SERVER['SERVER_NAME']; // phpcs:ignore
			$server['Server Admin']      = @$_SERVER['SERVER_ADMIN']; // phpcs:ignore
			$server['Server local time'] = date( 'Y-m-d H:i:s (\U\T\C P)' );
			$server['Operating System']  = @php_uname( 's' ); // phpcs:ignore
			$server['OS Hostname']       = @php_uname( 'n' ); // phpcs:ignore
			$server['OS Version']        = @php_uname( 'v' ); // phpcs:ignore

			$output = WP_Live_Debug_Helper::table_general( $server );

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		/**
		 * Get the Server information.
		 *
		 * @uses wpdb
		 * @uses get_results()
		 * @uses esc_html__()
		 * @uses WP_Live_Debug_Helper::format_num()
		 * @uses WP_Live_Debug_Helper::table_general()
		 *
		 * @return string json success with the response.
		 */
		public static function mysql_info() {
			global $wpdb;

			$mysql      = array();
			$extra_info = array();
			$mysql_vars = array(
				'key_buffer_size'    => true,
				'max_allowed_packet' => false,
				'max_connections'    => false,
				'query_cache_limit'  => true,
				'query_cache_size'   => true,
				'query_cache_type'   => 'ON',
			);

			$variables = $wpdb->get_results( "SHOW VARIABLES WHERE Variable_name IN ( '" . implode( "', '", array_keys( $mysql_vars ) ) . "' )" ); // phpcs:ignore
			$dbh       = $wpdb->dbh;

			if ( is_resource( $dbh ) ) {
				$driver = 'mysql';

				if ( function_exists( 'mysqli_get_server_info' ) ) {
					// phpcs:ignore WordPress.DB.RestrictedFunctions.mysql_mysqli_get_server_info
					$version = mysqli_get_server_info( $dbh );
				} else {
					// phpcs:disable WordPress.DB.RestrictedFunctions.mysql_mysql_get_server_info
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
					$version = esc_html__( 'Unknown', 'wp-live-debug' );
				}

				if ( isset( $dbh->client_info ) ) {
					$extra_info['Driver Version'] = $dbh->client_info;
				}

				if ( isset( $dbh->host_info ) ) {
					$extra_info['Connection'] = $dbh->host_info;
				}
			} else {
				$version = esc_html__( 'Unknown', 'wp-live-debug' );
				$driver  = esc_html__( 'Unknown', 'wp-live-debug' );
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
				$mysql[ $item->Variable_name ] = WP_Live_Debug_Helper::format_num( $item->Value ); // phpcs:ignore
			}

			$output = WP_Live_Debug_Helper::table_general( $mysql );

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		/**
		 * Get the PHP information.
		 *
		 * @uses WP_Live_Debug_Helper::get_php_errors()
		 * @uses WP_Live_Debug_Helper::table_general()
		 *
		 * @return string json success with the response.
		 */
		public static function php_info() {
			$php      = array();
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

			$extensions = get_loaded_extensions();

			natcasesort( $extensions );

			$php['Version'] = phpversion();

			foreach ( $php_vars as $setting ) {
				$php[ $setting ] = ini_get( $setting );
			}

			$php['Error Reporting'] = implode( '<br>', WP_Live_Debug_Helper::get_php_errors() );
			$php['Extensions']      = implode( '<br>', $extensions );

			$output = WP_Live_Debug_Helper::table_general( $php );

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		/**
		 * Get the phpinfo().
		 *
		 * @uses _e()
		 *
		 * @return string phpinfo() contents.
		 */
		public static function phpinfo_info() {
			if ( ! function_exists( 'phpinfo' ) ) {
				?>
				<div class="sui-notice sui-notice-error">
					<p><?php _e( '<code>phpinfo();</code> is disabled. Please contact your hosting provider if you need more information about your PHP setup.', 'wp-live-debug' ); ?></p>
				</div>
				<?php
			} else {
				ob_start();
				phpinfo();

				$phpinfo_output = ob_get_clean();

				preg_match_all( '/<body[^>]*>(.*)<\/body>/siU', $phpinfo_output, $phpinfo );
				preg_match_all( '/<style[^>]*>(.*)<\/style>/siU', $phpinfo_output, $styles );

				$remove_patterns = array( "/a:.+?\n/si", "/body.+?\n/si" );

				if ( isset( $styles[1][0] ) ) {
					$styles = preg_replace( $remove_patterns, '', $styles[1][0] );
					$styles = str_replace( ',', ', #phpinfo-info', $styles );
				}

				$styles = explode( "\n", $styles );
				$styles = array_filter( $styles );

				foreach ( $styles as $key => $value ) {
					$styles[ $key ] = '#phpinfo-info ' . $styles[ $key ];
				}

				$styles = implode( "\n", $styles );

				echo '<style type="text/css">' . $styles . '</style>';

				if ( isset( $phpinfo[1][0] ) ) {
					echo $phpinfo[1][0];
				}
			}
		}
	}
}
