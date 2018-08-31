<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Server_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_Tools' ) ) {
	class WP_Live_Debug_Tools {

		/**
		 * WP_Live_Debug_Tools constructor.
		 *
		 * @uses WP_Live_Debug_Tools::init()
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
			add_action( 'wp_ajax_wp-live-debug-checksums-check', array( 'WP_Live_Debug_Tools', 'run_checksums_check' ) );
			add_action( 'wp_ajax_wp-live-debug-view-diff', array( 'WP_Live_Debug_Tools', 'view_file_diff' ) );
			add_action( 'wp_ajax_wp-live-debug-mail', array( 'WP_Live_Debug_Tools', 'send_mail' ) );
		}

		public static function create_page() {
			$current_user = wp_get_current_user();
			$wp_address   = get_bloginfo( 'url' );
			$wp_name      = get_bloginfo( 'name' );
			$date         = date( 'F j, Y' );
			$time         = date( 'g:i a' );

			// translators: %s: website url.
			$email_subject = sprintf( esc_html__( 'Test Message from %s', 'wp-live-debug' ), $wp_address );

			$email_body = sprintf(
				// translators: %1$s: website name. %2$s: website url. %3$s: additional message from user.
				esc_html__( 'Hi. This test message was sent from %1$s (%2$s) on %3$s at %4$s. Since you’re reading this, it obviously works!', 'wp-live-debug' ),
				$wp_name,
				$wp_address,
				$date,
				$time
			);
			?>
				<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title"><?php esc_html_e( 'Checksums Check', 'wp-live-debug' ); ?></h2>
						<div class="sui-actions-right">
							<button id="run-checksums" class="sui-button sui-button-primary"><?php esc_html_e( 'Scan Files', 'wp-live-debug' ); ?></button>
						</div>
					</div>
					<div class="sui-box-body" id="checksums-response">
						<i id="checksums-loading" class="sui-icon-loader sui-loading" aria-hidden="true"></i>
					</div>
				</div>
				<div class="sui-box" id="mail-check-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title"><?php esc_html_e( 'wp_mail() Check', 'wp-live-debug' ); ?></h2>
					</div>
					<div class="sui-box-body">
						<form action="#" id="wp-live-debug-mail-check" method="POST">
							<div class="sui-form-field">
								<label for="email" class="sui-label">E-mail</label>
								<input type="email" id="email" name="email" class="sui-form-control" value="<?php echo $current_user->user_email; ?>">
							</div>
							<div class="sui-form-field">
								<label for="email_subject" class="sui-label">Subject</label>
								<input type="text" id="email_subject" name="email_subject" class="sui-form-control" value="<?php echo $email_subject; ?>">
							</div>
							<div class="sui-form-field">
								<label for="email_message" class="sui-label">Message</label>
								<textarea id="email_message" name="email_message" class="sui-form-control"><?php echo $email_body; ?></textarea>
							</div>
							<div class="sui-form-field">
								<input type="submit" class="sui-button sui-button-green" value="<?php esc_html_e( 'Send test mail', 'wp-live-debug' ); ?>">
							</div>
						</form>
					</div>
				</div>
				<div class="sui-dialog sui-dialog-lg" aria-hidden="true" tabindex="-1" id="checksums-popup">
					<div class="sui-dialog-overlay" data-a11y-dialog-hide></div>
					<div class="sui-dialog-content" aria-labelledby="dialogTitle" aria-describedby="dialogDescription" role="dialog">
						<div class="sui-box" role="document">
							<div class="sui-box-header">
								<h3 class="sui-box-title"></h3>
								<div class="sui-actions-right">
									<button data-a11y-dialog-hide class="sui-dialog-close" aria-label="Close this dialog window"></button>
								</div>
							</div>
							<div class="sui-box-body">
								<div class="diff-holder"></div>
							</div>
						</div>
					</div>
				</div>
			<?php
		}

		/**
		 * Gathers checksums from WordPress API and cross checks the core files in the current installation.
		 *
		 * @return void
		 */
		static function run_checksums_check() {
			$checksums = WP_Live_Debug_Tools::call_checksums_api();

			$files = WP_Live_Debug_Tools::parse_checksums_results( $checksums );

			WP_Live_Debug_Tools::create_the_response( $files );

		}

		/**
		* Calls the WordPress API on the checksums endpoint
		*
		* @uses get_bloginfo()
		* @uses get_locale()
		* @uses ABSPATH
		* @uses wp_remote_get()
		* @uses get_bloginfo()
		* @uses strpos()
		* @uses unset()
		*
		* @return array
		*/
		static function call_checksums_api() {
			// Setup variables.
			$wpversion = get_bloginfo( 'version' );
			$wplocale  = get_locale();

			// Setup API Call.
			$checksumapi = wp_remote_get( 'https://api.wordpress.org/core/checksums/1.0/?version=' . $wpversion . '&locale=' . $wplocale, array( 'timeout' => 10000 ) );

			// Encode the API response body.
			$checksumapibody = json_decode( wp_remote_retrieve_body( $checksumapi ), true );

			// Remove the wp-content/ files from checking
			foreach ( $checksumapibody['checksums'] as $file => $checksum ) {
				if ( false !== strpos( $file, 'wp-content/' ) ) {
					unset( $checksumapibody['checksums'][ $file ] );
				}
			}

			return $checksumapibody;
		}

		/**
		* Parses the results from the WordPress API call
		*
		* @uses file_exists()
		* @uses md5_file()
		* @uses ABSPATH
		*
		* @param array $checksums
		*
		* @return array
		*/
		static function parse_checksums_results( $checksums ) {
			$filepath = ABSPATH;
			$files    = array();
			// Parse the results.
			foreach ( $checksums['checksums'] as $file => $checksum ) {
				// Check the files.
				if ( file_exists( $filepath . $file ) && md5_file( $filepath . $file ) !== $checksum ) {
					$reason = '<button id="wp-live-debug-diff" class="sui-button sui-button-red" data-file="' . $file . '">' . esc_html__( 'View Changes', 'wp-live-debug' ) . '</button>';
					array_push( $files, array( $file, $reason ) );
				} elseif ( ! file_exists( $filepath . $file ) ) {
					$reason = esc_html__( 'File not found', 'wp-live-debug' );
					array_push( $files, array( $file, $reason ) );
				}
			}
			return $files;
		}

		/**
		* Generates the response
		*
		* @uses wp_send_json_success()
		* @uses wp_die()
		* @uses ABSPATH
		*
		* @param null|array $files
		*
		* @return void
		*/
		static function create_the_response( $files ) {
			$filepath = ABSPATH;
			$output   = '';

			if ( empty( $files ) ) {
				$output .= '<div class="sui-notice sui-notice-success"><p>';
				$output .= esc_html__( 'All checksums have passed. Everything seems to be ok!', 'wp-live-debug' );
				$output .= '</p></div>';
			} else {
				$output .= '<div class="sui-notice sui-notice-error"><p>';
				$output .= esc_html__( 'It appears as if some files may have been modified.', 'wp-live-debug' );
				$output .= '<br>' . esc_html__( "This might be a false-positive if your installation contains translated versions. An easy way to fix this and re-check is to re-install WordPress. Don't worry though as this will only affect WordPress' core files.", 'wp-live-debug' );
				$output .= '</p></div><table class="sui-table striped"><thead><tr><th>';
				$output .= esc_html__( 'File', 'wp-live-debug' );
				$output .= '</th><th>';
				$output .= esc_html__( 'Reason', 'wp-live-debug' );
				$output .= '</th></tr></thead><tbody>';
				foreach ( $files as $tampered ) {
					$output .= '<tr>';
					$output .= '<td>' . $filepath . $tampered[0] . '</td>';
					$output .= '<td>' . $tampered[1] . '</td>';
					$output .= '</tr>';
				}
				$output .= '</tbody>';
				$output .= '</table>';
			}

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		/**
		* Generates Diff view
		*
		* @uses get_bloginfo()
		* @uses wp_remote_get()
		* @uses wp_remote_retrieve_body()
		* @uses wp_send_json_success()
		* @uses wp_die()
		* @uses ABSPATH
		* @uses FILE_USE_INCLUDE_PATH
		* @uses wp_text_diff()
		*
		*
		* @return void
		*/
		static function view_file_diff() {
			$filepath         = ABSPATH;
			$file             = $_POST['file'];
			$wpversion        = get_bloginfo( 'version' );
			$local_file_body  = file_get_contents( $filepath . $file, FILE_USE_INCLUDE_PATH );
			$remote_file      = wp_remote_get( 'https://core.svn.wordpress.org/tags/' . $wpversion . '/' . $file );
			$remote_file_body = wp_remote_retrieve_body( $remote_file );
			$diff_args        = array(
				'show_split_view' => true,
			);

			$output   = '<table class="diff"><thead><tr class="diff-sub-title"><th>';
			$output  .= esc_html__( 'Original', 'wp-live-debug' );
			$output  .= '</th><th>';
			$output  .= esc_html__( 'Modified', 'wp-live-debug' );
			$output  .= '</th></tr></table>';
			$output  .= wp_text_diff( $remote_file_body, $local_file_body, $diff_args );
			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		/**
		 * Checks if wp_mail() works.
		 *
		 * @uses sanitize_email()
		 * @uses wp_mail()
		 * @uses wp_send_json_success()
		 * @uses wp_die()
		 *
		 * @return void
		 */
		static function send_mail() {
			$output        = '';
			$sendmail      = false;
			$email         = sanitize_email( $_POST['email'] );
			$email_subject = sanitize_text_field( $_POST['email_subject'] );
			$email_message = sanitize_textarea_field( $_POST['email_message'] );

			$sendmail = wp_mail( $email, $email_subject, $email_message );

			if ( ! empty( $sendmail ) ) {
				$output .= '<div class="sui-notice sui-notice-success"><p>';
				$output .= __( "You've just sent an e-mail using <code>wp_mail()</code> and it seems to work. Please check your inbox and spam folder to see if you received it.", 'wp-live-debug' );
				$output .= '</p></div>';
			} else {
				$output .= '<div class="sui-notice sui-notice-error"><p>';
				$output .= esc_html__( 'There was a problem sending the e-mail.', 'wp-live-debug' );
				$output .= '</p></div>';
			}

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}
	}
}
