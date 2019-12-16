<?php //phpcs:ignore -- \r\n notice.

if ( ! class_exists( 'WP_Live_Debug_Page' ) ) {
	class WP_Live_Debug_Page {

		/**
		 * Create the page.
		 */
		public static function create_page() {
			$option_log_name = wp_normalize_path( get_option( 'wp_live_debug_log_file' ) );
			$path            = wp_normalize_path( ABSPATH );
			$logs            = array();
			$debug_log       = wp_normalize_path( WP_CONTENT_DIR . '/debug.log' );

			?>

			<h1 class="wp-heading-inline"><?php esc_html_e( 'WP Live Debug', 'wp-live-debug' ); ?></h1>
			<hr class="wp-header-end">

			<p><?php echo esc_html__( 'Viewing:', 'wp-live-debug' ) . ' ' . $option_log_name; ?></p>

			<textarea id="wp-live-debug-area" name="wp-live-debug-area" class="large-text" spellcheck="false"></textarea>
			<p>
				<?php
				foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) ) as $file ) {
					if ( is_file( $file ) && 'log' === $file->getExtension() ) {
						$logs[] = wp_normalize_path( $file );
					}
				}
				?>
				<select id="log-list" name="select-list">
					<?php
					foreach ( $logs as $log ) {
						$selected = '';
						$log_name = wp_date( 'M d Y H:i:s', filemtime( $log ) ) . ' - ' . basename( $log );

						if ( get_option( 'wp_live_debug_log_file' ) === $log ) {
							$selected = 'selected="selected"';
						}

						echo '<option data-nonce="' . wp_create_nonce( $log ) . '" value="' . $log . '" ' . $selected . '>' . $log_name . '</option>';
					}
					?>
				</select>
			</p>
			<div class="sui-box">
				<div class="sui-box-body">
					<div class="sui-row">
						<div class="sui-col-md-4 sui-col-lg-4 text-center">
								<button id="wp-live-debug-clear" data-log="<?php echo $option_log_name; ?>" data-nonce="<?php echo wp_create_nonce( $option_log_name ); ?>" type="button" class="sui-button sui-button-primary"><i class="sui-icon-loader sui-loading" aria-hidden="true"></i> <?php esc_html_e( 'Clear Log', 'wp-live-debug' ); ?></button>
						</div>
						<div class="sui-col-md-4 sui-col-lg-4 text-center">
								<button id="wp-live-debug-delete" data-log="<?php echo $option_log_name; ?>" data-nonce="<?php echo wp_create_nonce( $option_log_name ); ?>" type="button" class="sui-button sui-button-red"><i class="sui-icon-loader sui-loading" aria-hidden="true"></i> <?php esc_html_e( 'Delete Log', 'wp-live-debug' ); ?></button>
						</div>
						<div class="sui-col-md-4 sui-col-lg-4 text-center">
							<label class="sui-toggle">
								<input type="checkbox" id="toggle-auto-refresh" <?php echo ( 'enabled' === get_option( 'wp_live_debug_auto_refresh' ) ) ? 'checked' : ''; ?>>
								<span class="sui-toggle-slider"></span>
							</label>
							<label for="toggle-auto-refresh"><?php esc_html_e( 'Auto Refresh Log', 'wp-live-debug' ); ?></label>
						</div>
					</div>
					<div class="sui-box-settings-row divider"></div>
					<div class="sui-row mt30">
					<?php if ( ! WP_Live_Debug_Live_Debug::check_wp_config_backup() ) { ?>
						<div class="sui-col-lg-12 text-center">
							<button id="wp-live-debug-backup" type="button" class="sui-button sui-button-green"><i class="sui-icon-loader sui-loading" aria-hidden="true"></i> <?php esc_html_e( 'Backup wp-config and show options', 'wp-live-debug' ); ?></button>
						</div>
						<?php } else { ?>
						<div class="sui-col-md-6 sui-col-lg-3 text-center">
							<button id="wp-live-debug-restore" type="button" class="sui-button sui-button-primary"><i class="sui-icon-loader sui-loading" aria-hidden="true"></i> <?php esc_html_e( 'Restore wp-config', 'wp-live-debug' ); ?></button>
						</div>
						<div class="sui-col-md-6 sui-col-lg-3 text-center">
							<span class="sui-tooltip sui-tooltip-top sui-tooltip-constrained" data-tooltip="The WP_DEBUG constant that can be used to trigger the 'debug' mode throughout WordPress. This will enable WP_DEBUG, WP_DEBUG_LOG and disable WP_DEBUG_DISPLAY and display_errors.">
								<label class="sui-toggle">
									<input type="checkbox" id="toggle-wp-debug" <?php echo ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'checked' : ''; ?>>
									<span class="sui-toggle-slider"></span>
								</label>
								<label for="toggle-wp-debug"><?php esc_html_e( 'WP Debug', 'wp-live-debug' ); ?></label>
							</span>
						</div>
						<div class="sui-col-md-6 sui-col-lg-3 text-center">
							<span class="sui-tooltip sui-tooltip-top sui-tooltip-constrained" data-tooltip="The SCRIPT_DEBUG constant will force WordPress to use the 'dev' versions of some core CSS and JavaScript files rather than the minified versions that are normally loaded.">
								<label class="sui-toggle">
									<input type="checkbox" id="toggle-script-debug" <?php echo ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'checked' : ''; ?> >
									<span class="sui-toggle-slider"></span>
								</label>
								<label for="toggle-script-debug"><?php esc_html_e( 'Script Debug', 'wp-live-debug' ); ?></label>
							</span>
						</div>
						<div class="sui-col-md-6 sui-col-lg-3 text-center">
							<span class=" sui-tooltip sui-tooltip-top sui-tooltip-constrained" data-tooltip="The SAVEQUERIES constant causes each query to be saved in the databse along with how long that query took to execute and what function called it. The array is stored in the global $wpdb->queries.">
								<label class="sui-toggle">
									<input type="checkbox" id="toggle-savequeries" <?php echo ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) ? 'checked' : ''; ?> >
									<span class="sui-toggle-slider"></span>
								</label>
								<label for="toggle-savequeries"><?php esc_html_e( 'Save Queries', 'wp-live-debug' ); ?></label>
							</span>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="sui-box-footer">
					<p class="sui-description">
						<?php
						// translators: %1$s WordPress installation path.
						echo sprintf( __( 'If you did not download &amp; verify the wp-config.php backup during activation you can find two extra backups via FTP as well in <code>%1$s</code> as <code>wp-config.wpld-manual-backup.php</code> and <code>wp-config.wpld-original-backup.php</code>.', 'wp-live-debug' ), wp_normalize_path( ABSPATH ) );
						?>
						<br><br>
						<?php _e( "<strong>To manually enable any of the above debugging options you can edit your wp-config.php and add the following constants right above the '/* That's all, stop editing! Happy blogging. */' line.</strong>", 'wp-live-debug' ); ?>
						<br><br>
						<?php _e( "<strong>WP Debug: <code>define( 'WP_DEBUG', true ); define( 'WP_DEBUG_LOG', true ); define( 'WP_DEBUG_DISPLAY', false ); @ini_set( 'display_errors', 0 );</code>", 'wp-live-debug' ); ?>
						<br>
						<?php _e( "<strong>Script Debug: <code>define( 'SCRIPT_DEBUG', true );</code>", 'wp-live-debug' ); ?>
						<br>
						<?php _e( "<strong>Save Queries: <code>define( 'SAVEQUERIES', true );</code>", 'wp-live-debug' ); ?>
						<br><br>
						<?php esc_html_e( 'You can always find more information at', 'wp-live-debug' ); ?> <a target="_blank" rel="noopener noreferrer" href="https://codex.wordpress.org/Debugging_in_WordPress"><?php esc_html_e( 'Debugging in WordPress', 'wp-live-debug' ); ?></a>.
					</p>
				</div>
			</div>
		<?php
		$first_time_running = get_option( 'wp_live_debug_risk' );

		if ( empty( $first_time_running ) ) {
			?>
			<div id="safety-popup-holder">
				<div id="safety-popup-inner">
					<div class="safety-popup-header">
						<h3 class="safety-popup-title">Safety First!</h3>
					</div>
					<div class="safety-popup-body">
						<p>
						<?php
							_e( 'WP LIVE DEBUG enables debugging, checks files and runs various tests to gather information about your installation.', 'wp-live-debug' );
						?>
						</p>
						<p>
						<?php
							_e( 'Make sure to have a <strong>full backup</strong> first before proceeding with any of the tools.', 'wp-live-debug' );
						?>
						</p>
					</div>
					<div class="safety-popup-footer">
						<a href="?page=wp-live-debug&wplddlwpconfig=true" class="sui-modal-close sui-button sui-button-green"><?php esc_html_e( 'Download wp-config', 'wp-live-debug' ); ?></a>
						<button id="riskaccept" class="sui-modal-close sui-button sui-button-blue"><?php esc_html_e( 'I understand', 'wp-live-debug' ); ?></button>
					</div>
				</div>
			</div>
			<?php
			}
		}
	}
}
