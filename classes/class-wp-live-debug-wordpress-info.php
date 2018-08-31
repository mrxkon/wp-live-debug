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
			// silence
		}

		public static function create_page() {
			?>
			<div class="sui-box">
				<div class="sui-box-header">
					<h2 class="sui-box-title">General Information</h2>
				</div>
				<div class="sui-box-body">
					<table class="sui-table striped">
						<tbody>
							<?php WP_Live_Debug_WordPress_Info::general_wp_information(); ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="sui-box">
				<div class="sui-box-header">
					<h2 class="sui-box-title">Directory Permissions</h2>
				</div>
				<div class="sui-box-body">
					<table class="sui-table striped">
						<tbody>
							<?php WP_Live_Debug_WordPress_Info::get_directory_permissions(); ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="sui-box">
				<div class="sui-box-header">
					<h2 class="sui-box-title">Installation Size</h2>
				</div>
				<div class="sui-box-body">
					<table class="sui-table striped">
						<tbody>
							<?php WP_Live_Debug_WordPress_Info::get_installation_size(); ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="sui-box">
				<div class="sui-box-header">
					<h2 class="sui-box-title">Constants</h2>
				</div>
				<div class="sui-box-body">
					<table class="sui-table striped">
						<tbody>
							<?php WP_Live_Debug::table_info( WP_Live_Debug_WordPress_Info::get_wp_constants() ); ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php
		}

		public static function general_wp_information() {
			global $wp_version, $required_php_version, $required_mysql_version, $wp_db_version;

			$wp = array(
				array(
					'label' => __( 'WordPress Version', 'wp-live-debug' ),
					'value' => $wp_version,
				),
				array(
					'label' => __( 'Database Version', 'wp-live-debug' ),
					'value' => $wp_db_version,
				),
				array(
					'label' => __( 'Required PHP Version', 'wp-live-debug' ),
					'value' => $required_php_version,
				),
				array(
					'label' => __( 'Required MySQL Version', 'wp-live-debug' ),
					'value' => $required_mysql_version,
				),
			);

			$result = '';

			foreach ( $wp as $info ) {
				$result .= '<tr>';
				$result .= '<td>' . $info['label'] . '</td>';
				$result .= '<td>' . $info['value'] . '</td>';
				$result .= '</tr>';
			}

			echo $result;
		}

		public static function get_directory_permissions() {
			$uploads_dir = wp_upload_dir();

			if ( defined( WP_TEMP_DIR ) ) {
				$tmp_dir  = WP_TEMP_DIR;
				$writable = ( wp_is_writable( $tmp_dir ) ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' );
			} else {
				$tmp_dir  = sys_get_temp_dir();
				$writable = ( wp_is_writable( $tmp_dir ) ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' );
			}

			$directories = array(
				array(
					'label' => ABSPATH,
					'value' => ( wp_is_writable( ABSPATH ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => WP_CONTENT_DIR,
					'value' => ( wp_is_writable( WP_CONTENT_DIR ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => $uploads_dir['basedir'],
					'value' => ( wp_is_writable( $uploads_dir['basedir'] ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => WP_PLUGIN_DIR,
					'value' => ( wp_is_writable( WP_PLUGIN_DIR ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => get_template_directory() . '/..',
					'value' => ( wp_is_writable( get_template_directory() . '/..' ) ? __( 'Writable', 'wp-live-debug' ) : __( 'Not writable', 'wp-live-debug' ) ),
				),
				array(
					'label' => $tmp_dir,
					'value' => $writable,
				),
			);

			$result = '';

			foreach ( $directories as $directory ) {
				$result .= '<tr>';
				$result .= '<td>' . $directory['label'] . '</td>';
				$result .= '<td>' . $directory['value'] . '</td>';
				$result .= '</tr>';
			}

			echo $result;
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
					'label' => __( 'Uploads Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['uploads']['size'], 2 ),
				),
				array(
					'label' => __( 'Themes Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['themes']['size'], 2 ),
				),
				array(
					'label' => __( 'Plugins Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['plugins']['size'], 2 ),
				),
				array(
					'label' => __( 'Database size', 'wp-live-debug' ),
					'value' => size_format( $size_db, 2 ),
				),
				array(
					'label' => __( 'Whole WordPress Directory', 'wp-live-debug' ),
					'value' => size_format( $sizes['wp']['size'], 2 ),
				),
				array(
					'label' => __( 'Total installation size', 'wp-live-debug' ),
					'value' => sprintf(
						'%s %s',
						size_format( $size_total, 2 ),
						( false === $inaccurate ? '' : __( 'Invalid permissions found, some values  may be inaccurate.', 'wp-live-debug' ) )
					),
				),
			);

			$result = '';

			foreach ( $directories as $directory ) {
				$result .= '<tr>';
				$result .= '<td>' . $directory['label'] . '</td>';
				$result .= '<td>' . $directory['value'] . '</td>';
				$result .= '</tr>';
			}

			echo $result;
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
