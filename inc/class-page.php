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
	die( 'Nope :)' );
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

		<div class="header">
			<div class="page-title">
				<h1 class="header-title"><?php esc_html_e( 'WP Live Debug', 'wp-live-debug' ); ?></h1>
			</div>
			<div class="backup-restore">
				<?php if ( ! Helper::check_wp_config_backup() ) : ?>
					<button id="wp-live-debug-backup" type="button" class="button button-primary"><?php esc_html_e( 'Backup wp-config', 'wp-live-debug' ); ?></button>
				<?php else: ?>
					<button id="wp-live-debug-restore" type="button" class="button button-primary"><?php esc_html_e( 'Restore wp-config', 'wp-live-debug' ); ?></button>
				<?php endif; ?>
			</div>
		</div>
		<div class="content">
			<div class="main">
				<div class="debug-area">
					<label for="log-list">
						<?php echo esc_html__( 'Viewing:', 'wp-live-debug' ); ?>
					</label>
					<select id="log-list" name="select-list">
						<?php foreach ( $logs as $log ) : ?>
							<option	data-nonce="<?php echo wp_create_nonce( $log ); ?>" value="<?php echo $log; ?>"<?php selected( $log, $selected_log ); ?>><?php echo wp_date( 'M d Y H:i:s', filemtime( $log ) ) . ' - ' . $log; ?></option>
						<?php endforeach; ?>
					</select>
					<textarea id="wp-live-debug-area" name="wp-live-debug-area" spellcheck="false"></textarea>
				</div>
			</div><!-- .main -->
			<div class="sidebar">
				<div class="panel-header">
					<h2><span><?php esc_attr_e( 'General Options', 'wp-live-debug' ); ?></span></h2>
				</div><!-- .panel-header -->
				<div class="panel-content">
					<div class="row">
						<button id="wp-live-debug-clear" data-log="<?php echo $option_log_name; ?>" data-nonce="<?php echo wp_create_nonce( $option_log_name ); ?>" type="button" class="button"><?php esc_html_e( 'Clear Log', 'wp-live-debug' ); ?></button>
						<button id="wp-live-debug-delete" data-log="<?php echo $option_log_name; ?>" data-nonce="<?php echo wp_create_nonce( $option_log_name ); ?>" type="button" class="button button-link-delete"><?php esc_html_e( 'Delete Log', 'wp-live-debug' ); ?></button>
					</div>
					<div class="row">
						<fieldset>
							<legend class="screen-reader-text"><span><?php esc_html_e( 'Auto Refresh Log', 'wp-live-debug' ); ?></span></legend>
							<label for="toggle-auto-refresh">
								<input name="" id="toggle-auto-refresh" type="checkbox" <?php checked( get_option( 'wp_live_debug_auto_refresh' ), 'enabled' ); ?> />
								<span><?php esc_html_e( 'Auto Refresh Log', 'wp-live-debug' ); ?></span>
							</label>
						</fieldset>
					</div>
				</div><!-- .panel-content -->
				<div class="panel-header">
					<h2><?php esc_attr_e( 'Debugging Options', 'wp-live-debug' ); ?></h2>
				</div><!-- .panel-header -->
				<div class="panel-content">
					<?php if ( ! Helper::check_wp_config_backup() ) : ?>
						<div class="row">
							<?php esc_html_e( 'Backup first to see the options!', 'wp-live-debug' ); ?>
						</div>
					<?php else : ?>
						<div class="row">
							<fieldset>
								<legend class="screen-reader-text"><span>WP_DEBUG</span></legend>
								<label for="toggle-wp-debug">
								<input type="checkbox" id="toggle-wp-debug" <?php echo ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'checked' : ''; ?>>
									<span>WP_DEBUG</span>
								</label>
							</fieldset>
						</div>
						<div class="row">
							<fieldset>
								<legend class="screen-reader-text"><span>SCRIPT_DEBUG</span></legend>
								<label for="toggle-script-debug">
									<input type="checkbox" id="toggle-script-debug" <?php echo ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'checked' : ''; ?> >
									<span>SCRIPT_DEBUG</span>
								</label>
							</fieldset>
						</div>
						<div class="row">
							<fieldset>
								<legend class="screen-reader-text"><span>SAVEQUERIES</span></legend>
								<label for="toggle-script-debug">
									<input type="checkbox" id="toggle-savequeries" <?php echo ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) ? 'checked' : ''; ?> >
									<span>SAVEQUERIES</span>
								</label>
							</fieldset>
						</div>
					<?php endif; ?>
				</div><!-- .panel-content -->
				<div class="panel-header">
					<h2><?php esc_attr_e( 'Information', 'wp-live-debug' ); ?></h2>
				</div><!-- .panel-header -->
				<div class="panel-content">
					<div class="row">
						<?php esc_html_e( 'You will find two extra backups that have been automatically kept along with your wp-config.php as:', 'wp-live-debug' ); ?>
					</div>
					<div class="row">
						<strong><?php echo WP_LIVE_DEBUG_AUTO_BACKUP_NAME . '<br />' . WP_LIVE_DEBUG_MANUAL_BACKUP_NAME; ?></strong>
					</div>
					<div class="row">
						<?php
							printf(
								// translators: %1$s: link to wordpress.org Debuggin in WordPress page.
								__( 'You can find more information about <a target="_blank" rel="noopener noreferrer" href="%s">Debugging in WordPress</a> at the official documentation.', 'wp-live-debug' ),
								'https://wordpress.org/support/article/debugging-in-wordpress/'
							);
						?>
					</div>
				</div><!-- .panel-content -->
			</div><!-- .sidebar -->
		</div><!-- .content -->
		<?php
		self::safety_popup();
	}

	/**
	 * Display safety popup.
	 */
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
