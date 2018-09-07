<?php //phpcs:ignore

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
			WP_Live_Debug_WPMUDEV::table_wpmudev_info( WP_Live_Debug_WPMUDEV::get_snapshot_constants() );
		}

		public static function get_snapshot_constants() {
			$snapshot           = array();
			$snapshot_constants = array(
				array(
					'SNAPSHOT_ATTEMPT_SYSTEM_BACKUP',
					'FALSE',
					'Backups: Managed backups only. Expects true or false. If set to true, managed backups will try to use system binaries to do the backup. This should be generally much faster (or even exponentially decrease the time required for a managed backup) than normal managed backups via PHP, but requires: server support for executing binaries from PHP (PHP functions escapeshellarg, escapeshellcmd and exec are present and available), and presence of expected binaries needed to do the backup (zip, ln, rm, mysqdump and, optionally, find). When this option is in effect and the listed prerequisites are not met, we will proceed with default managed backup creation via PHP and log a warning that says this: "Unable to perform requested system backup, proceeding with built in".',
				),
				array(
					'SNAPSHOT_BACKTRACE_ALL',
					'FALSE',
					'Backup: Managed backups only. Expects true or false. If set to true, this define forces logging of all managed backups log calls, regardless of their level (very verbose log file).',
				),
				array(
					'SNAPSHOT_CHANGED_ADMIN_URL',
					'EMPTY STRING',
					'Backup: Managed backups only. This is to be used when the user has moved their admin via .htaccess (e.g. this was initially reported when the user used the WP Hide & Security Enhancer plugin for this). When the admin is moved via .htaccess, scheduled managed backups don\'t work. Expects the URL of the moved admin. For example, if the new admin is http://leomilo.com/dashboard, this define should be: define( \'SNAPSHOT_CHANGED_ADMIN_URL\', \'http://leomilo.com/dashboard\' );',
				),
				array(
					'SNAPSHOT_FILESET_CHUNK_SIZE',
					'250',
					'Backup: Managed backups only. This is the number of files that will be processed for each step of the backup creation. Note: this setting has no effect if the backup is being done via system binaries (i.e. when SNAPSHOT_ATTEMPT_SYSTEM_BACKUP is in effect, v3.1.5 and up).',
				),
				array(
					'SNAPSHOT_FILESET_LARGE_FILE_SIZE',
					'1073741824',
					'Backup: Managed backups only. When processing the files for backup creation, each file will be queried for size. If a processed file size is larger than this threshold value, we will log a warning with this content: "Processing a large file: --filename-- (--filesize--)". By default, changing this value only changes the size that is used to log this warning. It is also possible to auto-reject oversized files with code snippet like this: add_filter( \'snapshot-queue-fileset-reject_oversized\', \'__return_false\' );',
				),
				array(
					'SNAPSHOT_FILESET_USE_PRECACHE',
					'FALSE',
					'Backup: Managed backups only. By default, the list of files is scanned on beginning of each files-processing backup step. When this setting is enabled, this is done only once and the list is cached. Each subsequent steps will work off this cache.',
				),
				array(
					'SNAPSHOT_FORCE_ZIP_LIBRARY',
					'ARCHIVE',
					'Backup: Snapshots and Managed backups. Expects "archive" or "pclzip". This option will force selection of the internal ZIP library used to produce backups.',
				),
				array(
					'SNAPSHOT_IGNORE_SYMLINKS',
					'FALSE',
					'Backup: Managed backups only. A symlink – or symbolic link – is a special file that is actually a reference to another file or folder. With symlinks you can keep your plugins and themes in a separate folder and – using symlinks – you can point it into each installation you have. Each installation uses the same files which makes modifications and maintenance a breeze. Expects true or false. When set, this option will force managed backups to not follow symlinks. The symlinked files will not be included in final backup.',
				),
				array(
					'SNAPSHOT_MB_BREADTH_FIRST',
					'FALSE',
					'Backups: Managed backups only. Expects true of false. If set to true, managed backups will try to use our new engine to process files (just files - databases are not affected by this). If this is active the engine will also process this with the the value set at SNAPSHOT_FILESET_CHUNK_SIZE, so if there are still issues with the files you can use something like define( \'SNAPSHOT_FILESET_CHUNK_SIZE\', 100 ); define( \'SNAPSHOT_MB_BREADTH_FIRST\', true );',
				),
				array(
					'SNAPSHOT_NO_SYSTEM_BACKUP',
					'FALSE',
					'Backup: Managed backups only. Expects true or false. If set to true, this define will force Snapshot plugin to not try and use system binaries for managed backups. In effect, reverses SNAPSHOT_ATTEMPT_SYSTEM_BACKUP define. Limited usability.',
				),
				array(
					'SNAPSHOT_SESSION_PROTECT_DATA',
					'FALSE',
					'Backup: Managed backups only. Expects true or false. If set to true, this define forces the session data to be encrypted. This behavior might be useful in combination with SNAPSHOT_FILESET_USE_PRECACHE option. It will also add some processing overhead to each and every backup step. Limited usability.',
				),
				array(
					'SNAPSHOT_SYSTEM_DEBUG_OUTPUT',
					'FALSE',
					'Backup: Managed backups only. Expects true or false. Only effective when SNAPSHOT_ATTEMPT_SYSTEM_BACKUP is enabled. When set to true, this option will log each actual command passed onto system binaries for execution. This option can be useful for debugging system binaries based managed backups - this is also somewhat of a security risk, because it will expose things such as database passwords in plain text in the log files. Be cautious.',
				),
				array(
					'SNAPSHOT_SYSTEM_ZIP_ONLY',
					'FALSE',
					'Backup: Managed backups only. Expects true or false. When processing files with SNAPSHOT_ATTEMPT_SYSTEM_BACKUP enabled, we will default to using the "find" binary for finding all files first (if find binary is available) and piping the output to zip. This can be prevented by setting this define to true, in which case the find binary won\'t be used at all. In this scenario, we will use zip binary alone and -x flags to process exclusions. The same will happen if there is no find binary available.  When using find, we will also automatically exclude large files from the archive by default - unless the oversized files size getter returns 0 (can be tweaked via filter), in which case we will be including them.',
				),
				array(
					'SNAPSHOT_TABLESET_CHUNK_SIZE',
					'1000',
					'Backup: Managed backups only. This is the number of table rows that will be processed for each step of the backup creation. Size can be defined as the number of rows to backup per table per request. This controls the backup processing when you create a new snapshot. During the backup process, Snapshot will make a request to the server to backup each table. You can see this in the progress meters when you create a new snapshot. In most situations this backup process will attempt to backup the table in one step. But on some server configurations the timeout is set very low or the table size is very large and prevents the backup process from finishing. To control this, the Snapshot backup process will breakup the requests into smaller ‘chunks of work’ requested to the server. For example, let’s say you have a table with 80,000 records. This would take more than the normal 3 minutes or less most servers allow for processing a single request. By setting the segment size to 1000, the Snapshot process will break up the table into 80 small parts. These 1000 records per request should complete within the allowed server timeout period. Note: this setting has no effect if the backup is being done via system binaries (i.e. when SNAPSHOT_ATTEMPT_SYSTEM_BACKUP is in effect).',
				),
				array(
					'WPMUDEV_SNAPSHOT_DESTINATIONS_EXCLUDE',
					'EMPTY STRING',
					'Backup: Snapshot backups only. Expects comma-separated string of destinations to exclude. Possible destinations are: "SnapshotDestinationFTP", "SnapshotDestinationGoogleDrive", "Snapshot_Model_Destination_AWS", SnapshotDestinationDropbox". When set to a non-empty string, the support for matching destination will not even be loaded.',
				),
			);

			foreach ( $snapshot_constants as $key => $constant ) {
				$snapshot[ $key ][0] = $constant[0];
				$snapshot[ $key ][1] = $constant[1];
				$snapshot[ $key ][2] = WP_Live_Debug_WPMUDEV::format_wpmudev_constant( $constant[0] );
				$snapshot[ $key ][3] = $constant[2];
			}

			return $snapshot;
		}

		public static function table_wpmudev_info( $list ) {
			$output = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Default Value', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			foreach ( $list as $key => $value ) {
				$output .= '<tr><td>' . esc_html( $value[0] ) . '</td><td>' . $value[1] . '</td><td>' . $value[2] . '</td></tr>';
				$output .= '<tr><td colspan="3"><em>' . esc_html( $value[3] ) . '</em></td></tr>';
			}
			$output .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Default Value', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$output .= '</tbody></table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function format_wpmudev_constant( $constant ) {

			if ( ! defined( $constant ) ) {
				return '<em>undefined</em>';
			}

			$val = constant( $constant );

			if ( ! is_bool( $val ) ) {
				return $val;
			} elseif ( ! $val ) {
				return 'FALSE';
			} else {
				return 'TRUE';
			}
		}

	}
}
