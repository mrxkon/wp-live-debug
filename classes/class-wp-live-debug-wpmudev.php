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
			add_action( 'wp_ajax_wp-live-debug-gather-snapshot-constants', array( 'WP_Live_Debug_WPMUDEV', 'gather_snapshot_info' ) );
			add_action( 'wp_ajax_wp-live-debug-gather-shipper-constants', array( 'WP_Live_Debug_WPMUDEV', 'gather_shipper_info' ) );
			add_action( 'wp_ajax_wp-live-debug-gather-dashboard-constants', array( 'WP_Live_Debug_WPMUDEV', 'gather_dashboard_info' ) );
		}

		/**
		 * Create the WPMU DEV page.
		 *
		 * @uses esc_html__()
		 *
		 * @return string html The html of the page viewed.
		 */
		public static function create_page() {
			?>
				<div class="sui-box">
					<div class="sui-box-body">
						<div class="sui-tabs">
							<div data-tabs>
								<div class="active"><?php esc_html_e( 'Dashboard', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Shipper', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Snapshot', 'wp-live-debug' ); ?></div>
							</div>
							<div data-panes>
								<div id="wpmudev-dashboard-info" class="active">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="wpmudev-shipper-info" class="active">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
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

		/**
		 * Gather Dashboard plugin information.
		 *
		 * @uses WP_Live_Debug_Helper::format_constant()
		 * @uses WP_Live_Debug_Helper::table_wpmudev_constants()
		 * @uses WP_Live_Debug_Helper::table_wpmudev_actions_filters()
		 * @uses wp_send_json_success()
		 *
		 * @return string json success with the response.
		 */
		public static function gather_dashboard_info() {
			$defines = array(
				array(	
					'WPMUDEV_API_AUTHORIZATION',
					'FALSE',
					'No idea what this does!',
				),
				array(	
					'WPMUDEV_API_DEBUG',
					'FALSE',
					'Activates Dashboard API Debug',
				),
				array(	
					'WPMUDEV_API_DEBUG_ALL',
					'FALSE',
					'Enable Debugging for ALL Dashboard API Actions',
				),
				array(	
					'WPMUDEV_API_DEBUG_CRAZY',
					'FALSE',
					'Enable Crazy Debugging options for Repo Projects',
				),
				array(	
					'WPMUDEV_API_SSLVERIFY',
					'TRUE',
					'Verify SSL Connection to API Server.',
				),
				array(	
					'WPMUDEV_API_UNCOMPRESSED',
					'FALSE',
					'No idea what this does!',
				),
				array(	
					'WPMUDEV_APIKEY',
					'',
					'Manually define the member\'s WPMUDEV API Key',
				),
				array(	
					'WPMUDEV_BETATEST',
					'FALSE',
					'Not sure what this does',
				),
				array(	
					'WPMUDEV_CUSTOM_API_SERVER',
					'',
					'Change URL to WPMUDEV API Server',
				),
				array(	
					'WPMUDEV_DISABLE_REMOTE_ACCESS',
					'FALSE',
					'Disable WPMUDEV Support Access from within Support Options page',
				),
				array(
					'WPMUDEV_LIMIT_TO_USER',
					'',
					'Limits Dashboard access to specified user_id (Seperate multiple id\'s with a comma)',
				),
				array(	
					'WPMUDEV_MENU_LOCATION',
					'3.012',
					'Manually set the Dashboard Plugin menu position',
				),
				array(	
					'WPMUDEV_NO_AUTOACTIVATE',
					'FALSE',
					'Don\'t AutoActivate plugin',
				),
				array(	
					'WPMUDEV_OVERRIDE_LOGOUT',
					'FALSE',
					'Override WPMUDEV Plugin logout',
				),
				array(	
					'WPMUDEV_REMOTE_SKIP_SYNC',
					'FALSE',
					'Skips sending stats to WPMUDEV after Synching',
				),
			);

                        foreach ( $defines as $key => $define ) {
				$constants[ $key ][0] = $define[0];
				$constants[ $key ][1] = $define[1];
				$constants[ $key ][2] = WP_Live_Debug_Helper::format_constant( $define[0] );
				$constants[ $key ][3] = $define[2];
			}

			$output = WP_Live_Debug_Helper::table_wpmudev_constants( $constants );

			$actions_filters = array(
				'actions' => array(
					'wpmudev_dashboard_action-admin-add',
					'wpmudev_dashboard_action-admin-remove',
					'wpmudev_dashboard_action-check-updates',
					'wpmudev_dashboard_action-remote-grant',
					'wpmudev_dashboard_action-remote-revoke',
					'wpmudev_dashboard_action-remote-extend',
					'wpmudev_dashboard_action-staff-note',
					'wpmudev_dashboard_after-{$name}',
					'wpmudev_dashboard_api_init',
					'wpmudev_dashboard_init',
					'wpmudev_dashboard_notice',
					'wpmudev_dashboard_notice-dashboard',
					'wpmudev_dashboard_notice_init',
					'wpmudev_dashboard_notice-plugins',
					'wpmudev_dashboard_notice-settings',
					'wpmudev_dashboard_notice-support',
					'wpmudev_dashboard_notice-themes',
					'wpmudev_dashboard_setup_menu',
					'wpmudev_dashboard_site_init',
					'wpmudev_dashboard_ui_init',
					'wpmudev_override_notice',
					'wpmudev_plugin_ui_enqueued',
				),
				'filters' => array(
					'auto_core_update_send_email',
					'secure_auth_cookie',
					'wdp_register_hub_action',
					'wpmudev-admin-notice',
					'wpmudev_api_project_extra_data-{$pid}',
					'wpmudev_api_project_data',
					'wpmudev_dashboard_before-{$name}',
					'wpmudev_dashboard_get_membership_data',
					'wpmudev_dashboard_get_projects_data',
					'wpmudev_project_auto_update_projects',
					'wpmudev_project_ignore_updates',
					'wpmudev_project_upgrade_url',
				),
			);

                        $output .= WP_Live_Debug_Helper::table_wpmudev_actions_filters( $actions_filters );

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

                /**
		 * Gather Shipper plugin information.
		 *
		 * @uses WP_Live_Debug_Helper::format_constant()
		 * @uses WP_Live_Debug_Helper::table_wpmudev_constants()
		 * @uses WP_Live_Debug_Helper::table_wpmudev_actions_filters()
		 * @uses wp_send_json_success()
		 *
		 * @return string json success with the response.
		 */
		public static function gather_shipper_info() {
			$defines = array(
				array(
					'SHIPPER_EXPORTED_TABLE_CHARSET',
					'',
					'',
				),
				array(
					'SHIPPER_EXPORTED_TABLE_COLLATION',
					'',
					'',
				),
				array(
					'SHIPPER_I_KNOW_WHAT_IM_DOING',
					'',
					'',
				),
				array(
					'SHIPPER_MOCK_API',
					'',
					'',
				),
				array(
					'SHIPPER_MOCK_IMPORT',
					'',
					'',
				),
				array(
					'SHIPPER_MOCK_IMPORT_DB',
					'',
					'',
				),
				array(
					'SHIPPER_MOCK_IMPORT_FS',
					'',
					'',
				),
				array(
					'SHIPPER_QUICK_EXPORT',
					'',
					'',
				),
				array(
					'SHIPPER_QUICK_EXPORT_DB',
					'',
					'',
				),
				array(
					'SHIPPER_QUICK_EXPORT_FS',
					'',
					'',
				),
				array(
					'SHIPPER_RUNNER_PING_TIMEOUT',
					'',
					'',
				),
			);

			foreach ( $defines as $key => $define ) {
				$constants[ $key ][0] = $define[0];
				$constants[ $key ][1] = $define[1];
				$constants[ $key ][2] = WP_Live_Debug_Helper::format_constant( $define[0] );
				$constants[ $key ][3] = $define[2];
			}

			$output = WP_Live_Debug_Helper::table_wpmudev_constants( $constants );

			$actions_filters = array(
				'actions' => array(
					'shipper_runner_pre_request_tick',
					'shipper_migration_complete',
					'shipper_migration_cancel_local',
					'shipper_migration_cancel',
				),
				'filters' => array(
					'shipper_api_mock_local',
					'shipper_internals_is_in_debug_mode',
					'shipper_runner_tick_validity_interval',
					'shipper_runner_ping_is_blocking',
					'shipper_runner_is_auth_requiring_env',
					'shipper_runner_ping_timeout',
					'shipper_migration_log_clear',
					'shipper_assets_shipper_icon',
					'shipper_hash_obfuscation_key',
					'shipper_helper_system_disabled',
					'shipper_helper_system_changeable',
					'shipper_helper_system_safemode',
					'shipper_path_include_file',
					'shipper_thresholds_max_file_size',
					'shipper_thresholds_max_package_size',
					'shipper_checks_hub_dashboard_present',
					'shipper_checks_hub_dashboard_active',
					'shipper_checks_hub_dashboard_apikey',
					'shipper_api_service_url',
					'shipper_api_request_args',
					'shipper_path_include_table',
					'shipper_export_tables_row_limit',
					'shipper_export_tables_create_{something}',
					'shipper_import_mock_files',
					'shipper_task_import_tables_list',
					'shipper_import_tables_row_limit',
					'shipper_import_mock_tables',
					'shipper_await_cancel_{$identifier}_max',
					'shipper_await_cancel_{$identifier}_step',
					'shipper_site_uniqid',
				),
			);

			$output .= WP_Live_Debug_Helper::table_wpmudev_actions_filters( $actions_filters );

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		/**
		 * Gather Snapshot plugin information.
		 *
		 * @uses WP_Live_Debug_Helper::format_constant()
		 * @uses WP_Live_Debug_Helper::table_wpmudev_constants()
		 * @uses WP_Live_Debug_Helper::table_wpmudev_actions_filters()
		 * @uses wp_send_json_success()
		 *
		 * @return string json success with the response.
		 */
		public static function gather_snapshot_info() {
			$defines = array(
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

			foreach ( $defines as $key => $define ) {
				$constants[ $key ][0] = $define[0];
				$constants[ $key ][1] = $define[1];
				$constants[ $key ][2] = WP_Live_Debug_Helper::format_constant( $define[0] );
				$constants[ $key ][3] = $define[2];
			}

			$output = WP_Live_Debug_Helper::table_wpmudev_constants( $constants );

			$actions_filters = array(
				'actions' => array(
					'snapshot-full_backups-restore-tables',
					'snapshot-destinations-render_list-before',
					'snapshot_destinations_loaded',
					'snapshot_register_destination',
					'snapshot-config-loaded',
					'snapshot_class_loader_pre_processing',
					'Snapshot_Controller_Full_Ajax::get_filter( \'ajax-error-stop\' );',
					'Snapshot_Controller_Full_Cron::get_filter( \'cron-error-stop\' );',
				),
				'filters' => array(
					'snapshot_home_path',
					'snapshot_current_path',
					'snapshot-queue-tableset-full',
					'snapshot-queue-fileset-preprocess',
					'snapshot-queue-fileset-reject_oversized',
					'snapshot-queue-fileset-filesize_threshold',
					'snapshot-mocks-api_response-code',
					'snapshot-mocks-api_response-body',
					'snapshot-full_backups-log_enabled',
					'snapshot-full_backups-log_enabled-explicit',
					'snapshot-full_backups-log_enabled-implicit',
					'snapshot_limit_of_files_per_session',
					'Snapshot_Model_Full_Remote_Storage::get_filter( \'api-space-used\' );',
					'Snapshot_Model_Full_Remote_Storage::get_filter( \'api-space-total\' );',
					'Snapshot_Model_Full_Remote_Storage::get_filter( \'api-space-free\' );',
					'Snapshot_Model_Full_Remote_Storage::get_filter( \'backups-get\' );',
					'Snapshot_Model_Full_Remote_Storage::get_filter( \'backups-refresh\' );',
					'Snapshot_Model_Full_Remote_Storage::get_filter( \'cache_expiration\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'get_backups\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'has_dashboard\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'is_dashboard_active\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'has_dashboard_key\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'is_active\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'schedule_frequencies\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'schedule_frequency\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'schedule_times\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'schedule_time\' );',
					'Snapshot_Model_Full_Backup::get_filter( \'has_backups\' );',
					'Snapshot_Model_Full_Remote_Api::get_filter( \'domain\' );',
					'Snapshot_Model_Full_Remote_Api::get_filter( \'api_key\' );',
					'Snapshot_Model_Full_Remote_Help::get_filter( \'help_url\' );',
					'Snapshot_Model_Time::get_filter( \'local_timestamp\' );',
					'Snapshot_Model_Time::get_filter( \'utc_timestamp\' );',
					'Snapshot_Controller_Full_Cron::get_filter( \'kickstart-delay\' );',
					'Snapshot_Controller_Full_Cron::get_filter( \'next_backup_start\' );',
					'Snapshot_Controller_Full_Cron::get_filter( \'backup-kickstart\' );',
				),
			);

			$output .= WP_Live_Debug_Helper::table_wpmudev_actions_filters( $actions_filters );

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}
	}
}
