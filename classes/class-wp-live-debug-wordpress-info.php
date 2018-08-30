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

			$arrays = array(
				array(
					'label' => __( 'Uploads Directory', 'health-check' ),
					'value' => size_format( $sizes['uploads']['size'], 2 ),
				),
				array(
					'label' => __( 'Themes Directory', 'health-check' ),
					'value' => size_format( $sizes['themes']['size'], 2 ),
				),
				array(
					'label' => __( 'Plugins Directory', 'health-check' ),
					'value' => size_format( $sizes['plugins']['size'], 2 ),
				),
				array(
					'label' => __( 'Database size', 'health-check' ),
					'value' => size_format( $size_db, 2 ),
				),
				array(
					'label' => __( 'Whole WordPress Directory', 'health-check' ),
					'value' => size_format( $sizes['wp']['size'], 2 ),
				),
				array(
					'label' => __( 'Total installation size', 'health-check' ),
					'value' => sprintf(
						'%s %s',
						size_format( $size_total, 2 ),
						( false === $inaccurate ? '' : __( 'Invalid permissions found, some values  may be inaccurate.', 'wp-live-debug' ) )
					),
				),
			);

			$result = '';

			foreach ( $arrays as $array ) {
				$result .= '<tr>';
				$result .= '<td>' . $array['label'] . '</td>';
				$result .= '<td>' . $array['value'] . '</td>';
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
			global $wp_version;

			$wp        = array();
			$wp_consts = array(
				'ABSPATH',
				'WP_CONTENT_DIR',
				'WP_PLUGIN_DIR',
				'WPINC',
				'WP_LANG_DIR',
				'UPLOADBLOGSDIR',
				'UPLOADS',
				'WP_TEMP_DIR',
				'SUNRISE',
				'WP_ALLOW_MULTISITE',
				'MULTISITE',
				'SUBDOMAIN_INSTALL',
				'DOMAIN_CURRENT_SITE',
				'PATH_CURRENT_SITE',
				'SITE_ID_CURRENT_SITE',
				'BLOGID_CURRENT_SITE',
				'BLOG_ID_CURRENT_SITE',
				'COOKIE_DOMAIN',
				'COOKIEPATH',
				'SITECOOKIEPATH',
				'DISABLE_WP_CRON',
				'ALTERNATE_WP_CRON',
				'DISALLOW_FILE_MODS',
				'WP_HTTP_BLOCK_EXTERNAL',
				'WP_ACCESSIBLE_HOSTS',
				'WP_DEBUG',
				'WP_DEBUG_LOG',
				'WP_DEBUG_DISPLAY',
				'ERRORLOGFILE',
				'SCRIPT_DEBUG',
				'WP_LANG',
				'WP_MAX_MEMORY_LIMIT',
				'WP_MEMORY_LIMIT',
			);

			$wp['WordPress Version'] = $wp_version;

			foreach ( $wp_consts as $const ) {
				$wp[ $const ] = WP_Live_Debug::format_constant( $const );
			}

			return $wp;
		}
	}
}
