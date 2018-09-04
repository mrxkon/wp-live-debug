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
			add_action( 'wp_ajax_wp-live-debug-get-ssl-information', array( 'WP_Live_Debug_Tools', 'get_ssl_information' ) );
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
				// translators: %1$s: website name. %2$s: website url. %3$s: date. %4$s: time
				esc_html__( 'Hi. This test message was sent from %1$s (%2$s) on %3$s at %4$s. Since you’re reading this, it obviously works!', 'wp-live-debug' ),
				$wp_name,
				$wp_address,
				$date,
				$time
			);
			?>
				<div class="sui-box">
					<div class="sui-box-body">
						<div class="sui-tabs">
							<div data-tabs>
								<div class="active"><?php esc_html_e( 'SSL Information', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'Checksums Check', 'wp-live-debug' ); ?></div>
								<div><?php esc_html_e( 'wp_mail() Check', 'wp-live-debug' ); ?></div>
							</div>
							<div data-panes>
								<div id="ssl-response" class="active"></div>
								<div id="checksums-response">
									<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
								</div>
								<div id="mail-check-box">
									<div class="sui-box-body">
										<form action="#" id="wp-live-debug-mail-check" method="POST">
											<div class="sui-form-field">
												<label for="email" class="sui-label"><?php esc_html_e( 'E-mail', 'wp-live-debug' ); ?></label>
												<input type="email" id="email" name="email" class="sui-form-control" value="<?php echo $current_user->user_email; ?>">
											</div>
											<div class="sui-form-field">
												<label for="email_subject" class="sui-label"><?php esc_html_e( 'Subject', 'wp-live-debug' ); ?></label>
												<input type="text" id="email_subject" name="email_subject" class="sui-form-control" value="<?php echo $email_subject; ?>">
											</div>
											<div class="sui-form-field">
												<label for="email_message" class="sui-label"><?php esc_html_e( 'Message', 'wp-live-debug' ); ?></label>
												<textarea id="email_message" name="email_message" class="sui-form-control" rows="4"><?php echo $email_body; ?></textarea>
											</div>
											<div class="sui-form-field">
												<input type="submit" class="sui-button sui-button-green" value="<?php esc_html_e( 'Send test mail', 'wp-live-debug' ); ?>">
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
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
		 * SSL Information
		 */
		public static function get_ssl_information() {

			$host = get_site_url();
			$host = str_replace( array( 'http://', 'https://' ), '', $host );

			$api_response = wp_remote_get( 'https://api.ssllabs.com/api/v3/analyze', array(
				'body' => array(
					'host'            => $host,
					'publish'         => 'off',
					'start_new'       => 'on',
					'from_cache'      => 'off',
					'max_age'         => null,
					'all'             => 'done',
					'ignore_mismatch' => 'off',
				),
			));

			if ( is_wp_error( $api_response ) ) {
				$error_message = $api_response->get_error_message();
				$output        = '<div class="sui-notice sui-notice-error"><p>';
				$output       .= esc_html( 'Something went wrong', 'wp-live-debug' ) . ': ' . $error_message; // phpcs:ignore
				$output       .= '</p></div>';
				$output       .= '<table class="sui-table striped">';
			} else {
				$call = json_decode( wp_remote_retrieve_body( $api_response ), true );
				if ( 'IN_PROGRESS' === $call['status'] ) {
					$progress       = 0;
					$progress_count = 0;
					foreach ( $call['endpoints'] as $key => $endpoint ) {
						if ( ! empty( $call['endpoints'][ $key ]['progress'] ) ) {
							$progress = $progress + $call['endpoints'][ $key ]['progress'];
							$progress_count++;
						}
					}
					if ( 0 != $progress ) {
						$prototal = floor( $progress / $progress_count );
					} else {
						$prototal = 0;
					}
					$output  = '<div class="sui-notice sui-notice-info"><p>';
					$output .= esc_html__( 'Currently testing and gathering information. This might take a while so make sure to check back!' , 'wp-live-debug' ); // phpcs:ignore
					$output .= '</p></div>';
					$output .= '<div class="sui-progress-block"><div class="sui-progress"><div class="sui-progress-text sui-icon-loader sui-loading">';
					$output .= '<span>' . $prototal . '%</span>';
					$output .= '</div><div class="sui-progress-bar">';
					$output .= '<span style="width: ' . $prototal . '%"></span>';
					$output .= '</div></div></div>';
				} elseif ( 'ERROR' === $call['status'] ) {
					$output  = '<div class="sui-notice sui-notice-error"><p>';
					$output .= $call['status'] . ': ' . $call['statusMessage']; // phpcs:ignore
					$output .= '</p></div>';
					$output .= '<table class="sui-table striped">';
					$output .= '<thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead>';
					$output .= '<tbody>';
					$output .= '<tr><td>' . esc_html__( 'Host', 'wp-live-debug' ) . '</td><td>' . $call['host'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Port', 'wp-live-debug' ) . '</td><td>' . $call['port'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Protocol', 'wp-live-debug' ) . '</td><td>' . $call['protocol'] . '</td></tr>';
					$output .= '</tbody>';
					$output .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
					$output .= '</table>';
				} elseif ( 'READY' === $call['status'] ) {
					$output  = '<div class="sui-notice sui-notice-success"><p>';
					$output .= esc_html__( 'Success! Valid SSL information received for', 'wp-live-debug' ) . ': ' . $call['host'];
					$output .= '</p></div>';
					$output .= '<table class="sui-table striped">';
					$output .= '<thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead>';
					$output .= '<tbody>';
					$output .= '<tr><td>' . esc_html__( 'Host', 'wp-live-debug' ) . '</td><td>' . $call['host'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Port', 'wp-live-debug' ) . '</td><td>' . $call['port'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Protocol', 'wp-live-debug' ) . '</td><td>' . $call['protocol'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Alternative Names', 'wp-live-debug' ) . '</td><td>';
					$output .= implode( '<br>', $call['certs'][0]['altNames'] );
					$output .= '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'IP Address', 'wp-live-debug' ) . '</td><td>' . $call['endpoints'][0]['ipAddress'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Issuer', 'wp-live-debug' ) . '</td><td>' . $call['certs'][1]['commonNames'][0] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Certificate ID', 'wp-live-debug' ) . '</td><td>' . $call['certs'][0]['id'] . '</td></tr>';
					$output .= '<tr><td>' . esc_html__( 'Protocols', 'wp-live-debug' ) . '</td><td>';
					foreach ( $call['endpoints'][0]['details']['protocols'] as $protocol ) {
						$output .= $protocol['name'] . $protocol['version'] . '<br>';
					}
					$output .= '</td></tr>';
					$output .= '</tbody>';
					$output .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
					$output .= '</table>';
				} else {
					$output  = '<div class="sui-notice sui-notice-info"><p>';
					$output .= esc_html__( 'Currently testing and gathering information. This might take a while so make sure to check back!' , 'wp-live-debug' ); // phpcs:ignore
					$output .= '</p></div>';
					$output .= '<div class="sui-progress-block"><div class="sui-progress"><div class="sui-progress-text sui-icon-loader sui-loading">';
					$output .= '<span>0%</span>';
					$output .= '</div><div class="sui-progress-bar">';
					$output .= '<span style="width: 0"></span>';
					$output .= '</div></div></div>';
				}
			}

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}
		/**
		 * Gathers checksums from WordPress API and cross checks the core files in the current installation.
		 *
		 * @return void
		 */
		public static function run_checksums_check() {
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
		public static function call_checksums_api() {
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
		public static function parse_checksums_results( $checksums ) {
			$filepath = ABSPATH;
			$files    = array();
			// Parse the results.
			foreach ( $checksums['checksums'] as $file => $checksum ) {
				// Check the files.
				if ( file_exists( $filepath . $file ) && md5_file( $filepath . $file ) !== $checksum ) {
					$reason = '<button data-do="wp-live-debug-diff" class="sui-button sui-button-red" data-file="' . $file . '">' . esc_html__( 'View Changes', 'wp-live-debug' ) . '</button>';
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
		public static function create_the_response( $files ) {
			$filepath = ABSPATH;
			$output   = '';

			if ( empty( $files ) ) {
				$output .= '<div class="sui-notice sui-notice-success"><p>';
				$output .= esc_html__( 'All checksums have passed. Everything seems to be ok!', 'wp-live-debug' );
				$output .= '</p></div>';
			} else {
				$output .= '<div class="sui-notice sui-notice-error"><p>';
				$output .= esc_html__( 'It appears that some files have been modified.', 'wp-live-debug' );
				$output .= '<br>' . esc_html__( "This might be a false-positive if your installation contains translated versions. An easy way to fix this is to re-install WordPress but don't worry as this will only affect the core WordPress files.", 'wp-live-debug' );
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
				$output .= '<tfoot><tr><th>' . esc_html__( 'File', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Reason', 'wp-live-debug' ) . '</th></tr></tfoot>';
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
		public static function view_file_diff() {
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
		public static function send_mail() {
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
