<?php //phpcs:ignore -- \r\n notice.

/**
 * This file comes with "wp-live-debug".
 *
 * Author:      Konstantinos Xenos
 * Author URI:  https://xkon.gr
 * Repo URI:    https://github.com/mrxkon/wp-live-debug/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace WP_Live_Debug;

use WP_Live_Debug\Helper as Helper;

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Page Class.
 */
class Page {
	/**
	 * Accept Risk Popup.
	 */
	public static function accept_risk() {
		update_option( 'wp_live_debug_risk', 'yes' );

		$response = array(
			'message' => esc_html__( 'risk accepted.', 'wp-live-debug' ),
		);

		wp_send_json_success( $response );
	}

	/**
	 * Create the page.
	 */
	public static function create() {
		$option_log_name = wp_normalize_path( get_option( 'wp_live_debug_log_file' ) );
		$selected_log    = get_option( 'wp_live_debug_log_file' );
		$logs            = Helper::gather_log_files();
		?>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'WP Live Debug', 'wp-live-debug' ); ?></h1>
		<hr class="wp-header-end">

		<div class="wrap">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<!-- main content -->
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<h2><span><?php echo esc_html__( 'Viewing:', 'wp-live-debug' ) . ' ' . $option_log_name; ?></span></h2>
								<div class="inside">
									<textarea id="wp-live-debug-area" name="wp-live-debug-area" spellcheck="false"></textarea>
									<p>
										<select id="log-list" name="select-list">
											<?php foreach ( $logs as $log ) : ?>
												<option	data-nonce="<?php echo wp_create_nonce( $log ); ?>" value="<?php echo $log; ?>"<?php selected( $log, $selected_log ); ?>><?php echo wp_date( 'M d Y H:i:s', filemtime( $log ) ) . ' - ' . basename( $log ); ?></option>
											<?php endforeach; ?>
										</select>
									</p>
								</div><!-- .inside -->
							</div><!-- .postbox -->
						</div><!-- .meta-box-sortables .ui-sortable -->
					</div><!-- post-body-content -->
					<!-- /main content -->
					<!-- sidebar -->
					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="inside">
									<h3><span><?php esc_attr_e( 'General Options', 'wp-live-debug' ); ?></span></h3>
									<p>
										<button id="wp-live-debug-clear" data-log="<?php echo $option_log_name; ?>" data-nonce="<?php echo wp_create_nonce( $option_log_name ); ?>" type="button" class="button"><?php esc_html_e( 'Clear Log', 'wp-live-debug' ); ?></button>
										<button id="wp-live-debug-delete" data-log="<?php echo $option_log_name; ?>" data-nonce="<?php echo wp_create_nonce( $option_log_name ); ?>" type="button" class="button button-link-delete"><?php esc_html_e( 'Delete Log', 'wp-live-debug' ); ?></button>
									</p>
									<p>
									<fieldset>
										<legend class="screen-reader-text"><span><?php esc_html_e( 'Auto Refresh Log', 'wp-live-debug' ); ?></span></legend>
										<label for="toggle-auto-refresh">
											<input name="" id="toggle-auto-refresh" type="checkbox" <?php checked( get_option( 'wp_live_debug_auto_refresh' ), 'enabled' ); ?> />
											<span><?php esc_html_e( 'Auto Refresh Log', 'wp-live-debug' ); ?></span>
										</label>
									</fieldset>
									</p>
									<h3><span><?php esc_attr_e( 'wp-config.php Options', 'wp-live-debug' ); ?></span></h3>
									<?php if ( ! Helper::check_wp_config_backup() ) : ?>
										<?php esc_html_e( 'Backup wp-config first to see the options!', 'wp-live-debug' ); ?>
										<p>
											<button id="wp-live-debug-backup" type="button" class="button button-primary"><?php esc_html_e( 'Backup wp-config', 'wp-live-debug' ); ?></button>
										</p>
									<?php else : ?>
										<p>
											<fieldset>
												<legend class="screen-reader-text"><span>WP_DEBUG</span></legend>
												<label for="toggle-wp-debug">
												<input type="checkbox" id="toggle-wp-debug" <?php echo ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'checked' : ''; ?>>
													<span>WP_DEBUG</span>
												</label>
											</fieldset>
										</p>
										<p>
											<fieldset>
												<legend class="screen-reader-text"><span>SCRIPT_DEBUG</span></legend>
												<label for="toggle-script-debug">
													<input type="checkbox" id="toggle-script-debug" <?php echo ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'checked' : ''; ?> >
													<span>SCRIPT_DEBUG</span>
												</label>
											</fieldset>
										</p>
										<p>
											<fieldset>
												<legend class="screen-reader-text"><span>SAVEQUERIES</span></legend>
												<label for="toggle-script-debug">
													<input type="checkbox" id="toggle-savequeries" <?php echo ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) ? 'checked' : ''; ?> >
													<span>SAVEQUERIES</span>
												</label>
											</fieldset>
										</p>
										<p>
											<button id="wp-live-debug-restore" type="button" class="button button-primary"><?php esc_html_e( 'Restore wp-config', 'wp-live-debug' ); ?></button>
										<p>
									<?php endif; ?>
								</div><!-- .inside -->
							</div><!-- .postbox -->

							<div class="postbox">
								<div class="inside">
									<h3><span><?php esc_attr_e( 'Information', 'wp-live-debug' ); ?></span></h3>
									<p>
										<?php
										echo sprintf(
											// translators: %1$s WordPress installation path.
											__( 'If you did not download &amp; verify your wp-config.php during activation you will find two extra backups that are automatically kept as <code>%1$s</code> and <code>%2$s</code>.', 'wp-live-debug' ),
											WP_LIVE_DEBUG_AUTO_BACKUP_NAME,
											WP_LIVE_DEBUG_MANUAL_BACKUP_NAME
										);
										?>
									</p>
									<p>
										<?php esc_html_e( 'You can always find more information at', 'wp-live-debug' ); ?> <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/article/debugging-in-wordpress/"><?php esc_html_e( 'Debugging in WordPress', 'wp-live-debug' ); ?></a>.
									</p>
								</div><!-- .inside -->
							</div><!-- .postbox -->
						</div><!-- .meta-box-sortables -->
					</div><!-- #postbox-container-1 .postbox-container -->
					<!-- /sidebar -->
				</div><!-- #post-body .metabox-holder .columns-2 -->
				<br class="clear">
			</div><!-- #poststuff -->
		</div> <!-- .wrap -->
		<?php
		self::safety_popup();
	}

	public static function safety_popup() {
		$first_time_running = get_option( 'wp_live_debug_risk' );

		if ( empty( $first_time_running ) ) {
			?>
			<div id="safety-popup-holder">
				<div id="safety-popup-inner">
					<h3 class="safety-popup-title">Safety First!</h3>
					<p>
						<?php _e( 'WP LIVE DEBUG alters your wp-config.', 'wp-live-debug' ); ?>
					</p>
					<p>
						<?php _e( 'Make sure to keep a <strong>backup</strong> first before proceeding.', 'wp-live-debug' ); ?>
					</p>
					<p>
					<a href="?page=wp-live-debug&wplddlwpconfig=true" class="button button-primary"><?php esc_html_e( 'Download wp-config', 'wp-live-debug' ); ?></a>
					<button id="riskaccept" class="button"><?php esc_html_e( 'I understand', 'wp-live-debug' ); ?></button>
					</p>
				</div>
			</div>
			<?php
		}
	}
}
