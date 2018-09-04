<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_WordPress_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_WordPress_Info' ) ) {
	class WP_Live_Debug_WordPress_Info {

		/**
		 * WP_Live_Debug_WordPress_Info constructor.
		 *
		 * @uses WP_Live_Debug_WordPress_Info::init()
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
			add_action( 'wp_ajax_wp-live-debug-gather-constants-info', array( 'WP_Live_Debug_WordPress_Info', 'gather_constants_info' ) );
			add_action( 'wp_ajax_wp-live-debug-get-dir-size', array( 'WP_Live_Debug_WordPress_Info', 'get_installation_size' ) );
			add_action( 'wp_ajax_wp-live-debug-get-dir-perm', array( 'WP_Live_Debug_WordPress_Info', 'get_directory_permissions' ) );
			add_action( 'wp_ajax_wp-live-debug-get-gen-info', array( 'WP_Live_Debug_WordPress_Info', 'general_wp_information' ) );
		}

		public static function create_page() {
			?>
				<div class="sui-box">
					<div class="sui-box-body">
						<div class="sui-tabs">
							<div data-tabs>
								<div class="active"><?php esc_html_e( 'General Information', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Directory Permissions', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Installation Size', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Constants', 'wp-live-debug' ); ?></div>
							</div>
							<div data-panes>
								<div id="gen-info" class="active">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="dir-perm">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="dir-size">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="constants-info">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
		}

		public static function general_wp_information() {
			global $wp_version, $required_php_version, $required_mysql_version, $wp_db_version;

			$theme_updates        = get_theme_updates();
			$all_themes           = wp_get_themes();
			$active_theme         = wp_get_theme();
			$themes_total         = 0;
			$themes_need_updates  = 0;
			$themes_inactive      = 0;
			$has_default_theme    = false;
			$plugins              = get_plugins();
			$plugin_updates       = get_plugin_updates();
			$plugins_have_updates = false;
			$plugins_active       = 0;
			$plugins_total        = 0;
			$plugins_needs_update = 0;

			foreach ( $all_themes as $theme_slug => $theme ) {
				$themes_total++;

				if ( WP_DEFAULT_THEME === $theme_slug ) {
					$has_default_theme = true;
				}

				if ( array_key_exists( $theme_slug, $theme_updates ) ) {
					$themes_need_updates++;
				}
			}

			foreach ( $plugins as $plugin_path => $plugin ) {
				$plugins_total++;

				if ( is_plugin_active( $plugin_path ) ) {
					$plugins_active++;
				}

				$plugin_version = $plugin['Version'];

				if ( array_key_exists( $plugin_path, $plugin_updates ) ) {
					$plugins_needs_update++;
					$plugins_have_updates = true;
				}
			}

			$themes_output = $themes_total . ' ' . '( ' . $themes_need_updates . ' ' . esc_html__( 'out of date', 'wp-live-debug' ) . ' )';

			if ( ! $has_default_theme ) {
				$themes_output .= '<br>' . esc_html__( 'Your site does not have the default theme installed', 'wp-live-debug' ) . ' ( ' . WP_DEFAULT_THEME . ' ) ';
			}

			$plugin_output = $plugins_total . ' ' . '( ' . $plugins_active . ' ' . esc_html__( 'active', 'wp-live-debug' ) . ' / ' . $plugins_needs_update . ' ' . esc_html__( 'out of date', 'wp-live-debug' ) . ' )';

			$extension_loaded = extension_loaded( 'json' );
			$functions_exist  = function_exists( 'json_encode' ) && function_exists( 'json_decode' );
			$functions_work   = function_exists( 'json_encode' ) && ( '' != json_encode( 'my test string' ) );

			if ( $extension_loaded && $functions_exist && $functions_work ) {
				$json_support = esc_html__( 'Yes', 'wp-live-debug' );
			} else {
				$json_support = esc_html__( 'No', 'wp-live-debug' );
			}

			$wp_dotorg = wp_remote_get( 'https://wordpress.org', array(
				'timeout' => 10,
			) );

			if ( ! is_wp_error( $wp_dotorg ) ) {
				$dotorg = esc_html__( 'Connected successfully', 'wp-live-debug' );
			} else {
				$dotorg  = esc_html__( 'Could not connect', 'wp-live-debug' );
				$dotorg .= '<br>' . $wp_dotorg->get_error_message();
			}

			$cookies = wp_unslash( $_COOKIE );
			$timeout = 10;
			$headers = array(
				'Cache-Control' => 'no-cache',
			);

			if ( isset( $_SERVER['PHP_AUTH_USER'] ) && isset( $_SERVER['PHP_AUTH_PW'] ) ) {
				$headers['Authorization'] = 'Basic ' . base64_encode( wp_unslash( $_SERVER['PHP_AUTH_USER'] ) . ':' . wp_unslash( $_SERVER['PHP_AUTH_PW'] ) );
			}

			$url = admin_url();
			$r   = wp_remote_get( $url, compact( 'cookies', 'headers', 'timeout' ) );

			if ( is_wp_error( $r ) ) {
				$loopback_status  = esc_html__( 'The loopback request to your site failed', 'wp-live-debug' );
				$loopback_status .= '<br>' . wp_remote_retrieve_response_code( $r ) . ' ' . $r->get_error_message();
			} elseif ( 200 !== wp_remote_retrieve_response_code( $r ) ) {
				$loopback_status  = esc_html__( 'The loopback request to your site failed', 'wp-live-debug' );
				$loopback_status .= '<br>' . esc_html__( 'Unexpected status: ' ) . wp_remote_retrieve_response_code( $r );
			} else {
				$loopback_status = esc_html__( 'The loopback request was successfull', 'wp-live-debug' );
			}

			if ( is_multisite() ) {
				$total_users = get_user_count();

				$networks    = new WP_Network_Query();
				$network_ids = $networks->query( array(
					'fields'        => 'ids',
					'number'        => 100,
					'no_found_rows' => false,
				) );

				$total_networks = $networks->found_networks;

				$total_sites = 0;
				foreach ( $network_ids as $network_id ) {
					$total_sites += get_blog_count( $network_id );
				}
			} else {
				$total_networks = 0;
				$total_sites    = 1;
				$total_users    = count_users();
				$total_users    = $total_users['total_users'];
			}

			$wp = array(
				array(
					'label' => esc_html__( 'WordPress Version', 'wp-live-debug' ),
					'value' => $wp_version,
				),
				array(
					'label' => esc_html__( 'Database Version', 'wp-live-debug' ),
					'value' => $wp_db_version,
				),
				array(
					'label' => esc_html__( 'Required PHP Version', 'wp-live-debug' ),
					'value' => $required_php_version,
				),
				array(
					'label' => esc_html__( 'Required MySQL Version', 'wp-live-debug' ),
					'value' => $required_mysql_version,
				),
				array(
					'label' => esc_html__( 'Users', 'wp-live-debug' ),
					'value' => $total_users,
				),
				array(
					'label' => esc_html__( 'Networks', 'wp-live-debug' ),
					'value' => $total_networks,
				),
				array(
					'label' => esc_html__( 'Sites', 'wp-live-debug' ),
					'value' => $total_sites,
				),
				array(
					'label' => esc_html__( 'Themes', 'wp-live-debug' ),
					'value' => $themes_output,
				),
				array(
					'label' => esc_html__( 'Plugins', 'wp-live-debug' ),
					'value' => $plugin_output,
				),
				array(
					'label' => esc_html__( 'JSON Support', 'wp-live-debug' ),
					'value' => $json_support,
				),
				array(
					'label' => esc_html__( 'Connection with WordPress.org', 'wp-live-debug' ),
					'value' => $dotorg,
				),
				array(
					'label' => esc_html__( 'Loopback', 'wp-live-debug' ),
					'value' => $loopback_status,
				),
			);

			$output  = '<table class="sui-table striped">';
			$output .= '<thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '<th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead>';
			$output .= '<tbody>';

			foreach ( $wp as $info ) {
				$output .= '<tr>';
				$output .= '<td>' . $info['label'] . '</td>';
				$output .= '<td>' . $info['value'] . '</td>';
				$output .= '</tr>';
			}

			$output .= '</tbody>';
			$output .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '<th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$output .= '</table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function get_directory_permissions() {
			$uploads_dir = wp_upload_dir();

			if ( defined( 'WP_TEMP_DIR' ) ) {
				$tmp_dir  = WP_TEMP_DIR;
				$writable = ( wp_is_writable( $tmp_dir ) ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' );
			} else {
				$tmp_dir  = sys_get_temp_dir();
				$writable = ( wp_is_writable( $tmp_dir ) ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' );
			}

			$directories = array(
				array(
					'label' => wp_normalize_path( ABSPATH ),
					'value' => ( wp_is_writable( ABSPATH ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => wp_normalize_path( WP_CONTENT_DIR ),
					'value' => ( wp_is_writable( WP_CONTENT_DIR ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => wp_normalize_path( $uploads_dir['basedir'] ),
					'value' => ( wp_is_writable( $uploads_dir['basedir'] ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => wp_normalize_path( WP_CONTENT_DIR ),
					'value' => ( wp_is_writable( WP_PLUGIN_DIR ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => wp_normalize_path( get_template_directory() . '/..' ),
					'value' => ( wp_is_writable( get_template_directory() . '/..' ) ? esc_html__( 'Writable', 'wp-live-debug' ) : esc_html__( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => wp_normalize_path( $tmp_dir ),
					'value' => $writable,
				),
			);

			$output  = '<table class="sui-table striped">';
			$output .= '<thead><tr><th>' . esc_html__( 'Directory', 'wp-live-debug' ) . '<th>' . esc_html__( 'Permissions', 'wp-live-debug' ) . '</th></tr></thead>';
			$output .= '<tbody>';

			foreach ( $directories as $directory ) {
				$output .= '<tr>';
				$output .= '<td>' . $directory['label'] . '</td>';
				$output .= '<td>' . $directory['value'] . '</td>';
				$output .= '</tr>';
			}

			$output .= '</tbody>';
			$output .= '<tfoot><tr><th>' . esc_html__( 'Directory', 'wp-live-debug' ) . '<th>' . esc_html__( 'Permissions', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$output .= '</table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function get_installation_size() {
			$uploads_dir = wp_upload_dir();

			$sizes = array(
				'wp'      => array(
					'path' => ABSPATH,
					'size' => 0,
				),
				'themes'  => array(
					'path' => trailingslashit( get_theme_root() ),
					'size' => 0,
				),
				'plugins' => array(
					'path' => WP_PLUGIN_DIR,
					'size' => 0,
				),
				'uploads' => array(
					'path' => $uploads_dir['basedir'],
					'size' => 0,
				),
			);

			$inaccurate = false;

			foreach ( $sizes as $size => $attributes ) {
				try {
					$sizes[ $size ]['size'] = WP_Live_Debug_WordPress_Info::get_directory_size( $attributes['path'] );
				} catch ( Exception $e ) {
					$inaccurate = true;
				}
			}

			$size_db = WP_Live_Debug_WordPress_Info::get_database_size();

			$size_total = $sizes['wp']['size'] + $size_db;

			$directories = array(
				array(
					'label' => esc_html__( 'Uploads Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['uploads']['size'], 2 ),
				),
				array(
					'label' => esc_html__( 'Themes Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['themes']['size'], 2 ),
				),
				array(
					'label' => esc_html__( 'Plugins Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['plugins']['size'], 2 ),
				),
				array(
					'label' => esc_html__( 'WordPress Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['wp']['size'], 2 ),
				),
				array(
					'label' => esc_html__( 'Database Size', 'wp-live-debug' ),
					'value' => size_format( $size_db, 2 ),
				),
				array(
					'label' => esc_html__( 'Total installation Size', 'wp-live-debug' ),
					'value' => sprintf(
						'%s %s',
						size_format( $size_total, 2 ),
						( false === $inaccurate ? '' : esc_html__( 'Invalid permissions found, some values  may be inaccurate.', 'wp-live-debug' ) )
					),
				),
			);

			$output  = '<table class="sui-table striped">';
			$output .= '<thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '<th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead>';
			$output .= '<tbody>';

			foreach ( $directories as $directory ) {
				$output .= '<tr>';
				$output .= '<td>' . $directory['label'] . '</td>';
				$output .= '<td>' . $directory['value'] . '</td>';
				$output .= '</tr>';
			}

			$output .= '</tbody>';
			$output .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '<th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$output .= '</table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function get_directory_size( $path ) {
			$size = 0;

			foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) ) as $file ) {
				$size += $file->getSize();
			}

			return $size;
		}

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

		public static function gather_constants_info() {
			WP_Live_Debug::table_info( WP_Live_Debug_WordPress_Info::get_wp_constants() );
		}

		public static function get_wp_constants() {
			$wp        = array();
			$wp_consts = array(
				'ABSPATH',
				'ADMIN_COOKIE_PATH',
				'ALTERNATE_WP_CRON',
				'AUTH_COOKIE',
				'AUTOSAVE_INTERVAL',
				'BLOG_ID_CURRENT_SITE',
				'BLOGID_CURRENT_SITE',
				'COMPRESS_CSS',
				'COMPRESS_SCRIPTS',
				'CONCATENATE_SCRIPTS',
				'COOKIE_DOMAIN',
				'COOKIEHASH',
				'COOKIEPATH',
				'DISABLE_WP_CRON',
				'DISALLOW_FILE_EDIT',
				'DISALLOW_FILE_MODS',
				'DOMAIN_CURRENT_SITE',
				'EMPTY_TRASH_DAYS',
				'ERRORLOGFILE',
				'FORCE_SSL_ADMIN',
				'FORCE_SSL_LOGIN',
				'FS_METHOD',
				'LOGGED_IN_COOKIE',
				'MEDIA_TRASH',
				'MULTISITE',
				'MUPLUGINDIR',
				'PATH_CURRENT_SITE',
				'PLUGINDIR',
				'PLUGINS_COOKIE_PATH',
				'RELOCATE',
				'SCRIPT_DEBUG',
				'SECURE_AUTH_COOKIE',
				'SHORTINIT',
				'SITE_ID_CURRENT_SITE',
				'SITECOOKIEPATH',
				'STYLESHEETPATH',
				'SUBDOMAIN_INSTALL',
				'SUNRISE',
				'TEMPLATEPATH',
				'TEST_COOKIE',
				'UPLOADBLOGSDIR',
				'UPLOADS',
				'USER_COOKIE',
				'WP_ACCESSIBLE_HOSTS',
				'WP_ALLOW_MULTISITE',
				'WP_AUTO_UPDATE_CORE',
				'WP_CACHE',
				'WP_CONTENT_DIR',
				'WP_CONTENT_URL',
				'WP_CRON_LOCK_TIMEOUT',
				'WP_DEBUG',
				'WP_DEBUG_DISPLAY',
				'WP_DEBUG_LOG',
				'WP_DEFAULT_THEME',
				'WP_HOME',
				'WP_HTTP_BLOCK_EXTERNAL',
				'WP_LANG',
				'WP_LANG_DIR',
				'WP_LOCAL_DEV',
				'WP_MAX_MEMORY_LIMIT',
				'WP_MEMORY_LIMIT',
				'WP_PLUGIN_DIR',
				'WP_PLUGIN_URL',
				'WP_POST_REVISIONS',
				'WP_SITEURL',
				'WP_TEMP_DIR',
				'WPINC',
				'WPMU_PLUGIN_DIR',
				'WPMU_PLUGIN_URL',
			);

			foreach ( $wp_consts as $const ) {
				$wp[ $const ] = WP_Live_Debug::format_constant( $const );
			}

			return $wp;
		}
	}
}
