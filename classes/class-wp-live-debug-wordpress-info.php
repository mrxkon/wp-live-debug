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
						<h2 class="sui-box-title">Constants</h2>
					</div>
					<div class="sui-box-body">
						<table class="sui-table striped">
							<tbody>
								<?php WP_Live_Debug::table_info( WP_Live_Debug_WordPress_Info::get_wp_info() ); ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php
		}

		public static function get_wp_info() {
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
