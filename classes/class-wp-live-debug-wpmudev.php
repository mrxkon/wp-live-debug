<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Server_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_WPMUDEV' ) ) {
	class WP_Live_Debug_WPMUDEV {

		/**
		 * WP_Live_Debug_WPMUDEV constructor.
		 *
		 * @uses WP_Live_Debug_WPMUDEV::init()
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
			add_action( 'wp_ajax_wp-live-debug-gather-snapshot-constants', array( 'WP_Live_Debug_WPMUDEV', 'gather_snapshot_constants_info' ) );
			//add_action( 'wp_ajax_wp-live-debug-gather-shipper-constants', array( 'WP_Live_Debug_WPMUDEV', 'gather_shipper_constants_info' ) );
		}

		public static function create_page() {
			?>
				<div class="sui-box">
					<div class="sui-box-body">
						<div class="sui-tabs">
							<div data-tabs>
								<div class="active"><?php esc_html_e( 'Dashboard', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Defender', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Hummingbird', 'wp-live-debug' ); ?></div>
								<!--<div><?php //esc_html_e( 'Shipper', 'wp-live-debug' ); ?></div>-->
								<div><?php esc_html_e( 'Smartcrawl', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Smush', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Snapshot', 'wp-live-debug' ); ?></div>
							</div>
							<div data-panes>
								<div id="wpmudev-dashboard-info">
									Not yet implemented!
								</div>
								<div id="wpmudev-defender-info">
									Not yet implemented!
								</div>
								<div id="wpmudev-hummingbird-info">
									Not yet implemented!
								</div>
								<!-- <div id="wpmudev-shipper-info">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div> -->
								<div id="wpmudev-smartcrawl-info">
									Not yet implemented!
								</div>
								<div id="wpmudev-smush-info">
									Not yet implemented!
								</div>
								<div id="wpmudev-snapshot-info" class="active">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php
		}

		public static function gather_shipper_constants_info() {
			WP_Live_Debug::table_info( WP_Live_Debug_WPMUDEV::get_shipper_constants() );
		}

		public static function get_shipper_constants() {
			$output    = array();
			$constants = array(
				'SHIPPER_EXPORTED_TABLE_CHARSET',
				'SHIPPER_EXPORTED_TABLE_COLLATION',
				'SHIPPER_I_KNOW_WHAT_IM_DOING',
				'SHIPPER_MOCK_API',
				'SHIPPER_MOCK_IMPORT',
				'SHIPPER_MOCK_IMPORT_DB',
				'SHIPPER_MOCK_IMPORT_FS',
				'SHIPPER_QUICK_EXPORT',
				'SHIPPER_QUICK_EXPORT_DB',
				'SHIPPER_QUICK_EXPORT_FS',
				'SHIPPER_RUNNER_PING_TIMEOUT',
			);

			foreach ( $constants as $constant ) {
				$output[ $constant ] = WP_Live_Debug::format_constant( $constant );
			}

			return $output;
		}

		public static function gather_snapshot_constants_info() {
			WP_Live_Debug::table_info( WP_Live_Debug_WPMUDEV::get_snapshot_constants() );
		}

		public static function get_snapshot_constants() {
			$snapshot           = array();
			$snapshot_constants = array(
				'SNAPSHOT_ATTEMPT_SYSTEM_BACKUP',
				'SNAPSHOT_BACKTRACE_ALL',
				'SNAPSHOT_CHANGED_ADMIN_URL',
				'SNAPSHOT_FILESET_CHUNK_SIZE',
				'SNAPSHOT_FILESET_LARGE_FILE_SIZE',
				'SNAPSHOT_FILESET_USE_PRECACHE',
				'SNAPSHOT_FORCE_ZIP_LIBRARY',
				'SNAPSHOT_IGNORE_SYMLINKS',
				'SNAPSHOT_MB_BREADTH_FIRST',
				'SNAPSHOT_NO_SYSTEM_BACKUP',
				'SNAPSHOT_SESSION_PROTECT_DATA',
				'SNAPSHOT_SYSTEM_DEBUG_OUTPUT',
				'SNAPSHOT_SYSTEM_ZIP_ONLY',
				'SNAPSHOT_TABLESET_CHUNK_SIZE',
				'WPMUDEV_SNAPSHOT_DESTINATIONS_EXCLUDE',
			);

			foreach ( $snapshot_constants as $constant ) {
				$snapshot[ $constant ] = WP_Live_Debug::format_constant( $constant );
			}

			return $snapshot;
		}

	}
}
