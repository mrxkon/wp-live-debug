<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Cronjob_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_Cronjob_Info' ) ) {
	class WP_Live_Debug_Cronjob_Info {

		/**
		 * WP_Live_Debug_Cronjob_Info constructor.
		 *
		 * @uses WP_Live_Debug_Cronjob_Info::init()
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
			add_action( 'wp_ajax_wp-live-debug-gather-cronjob-info', array( 'WP_Live_Debug_Cronjob_Info', 'gather_cronjob_info' ) );
		}

		public static function create_page() {
			?>
				<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title"><?php esc_html_e( 'Scheduled Tasks', 'wp-live-debug' ); ?></h2>
					</div>
					<div class="sui-box-body" id="cronjob-response">
						<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
					</div>
				</div>
			<?php
		}

		public static function gather_cronjob_info() {
			global $wp_filter;

			if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
				$output = '<div class="sui-notice sui-notice-error"><p>' . esc_html__( 'WP Cron is Disabled!', 'wp-live-debug' ) . '</p></div>';
			} else {
				$output = '<div class="sui-notice sui-notice-success"><p>' . esc_html__( 'WP Cron is Enabled!', 'wp-live-debug' ) . '</p></div>';
			}

			if ( function_exists( '_get_cron_array' ) ) {
				$cronjobs = _get_cron_array();
			} else {
				$cronjobs = get_option( 'cron' );
			}

			$output .= '<table class="sui-table striped">';
			$output .= '<thead><tr><th>' . esc_html__( 'Task', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Action', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Arguments', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Schedule', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Next Run In', 'wp-live-debug' ) . '</tr></thead><tbody>';

			foreach ( $cronjobs as $time => $job ) {
				foreach ( $job as $proc => $task ) {
					if ( has_action( $proc ) ) {
						$action = '';
						if ( isset( $GLOBALS['wp_filter'][ $proc ] ) ) {
							foreach ( $GLOBALS['wp_filter'][ $proc ] as $priority => $taskpriority ) {
								foreach ( $taskpriority as $calls ) {
									foreach ( $calls as $funcs ) {
										if ( ! ( 1 == $funcs ) ) {
											if ( is_array( $funcs ) ) {
												if ( is_object( $funcs[0] ) ) {
													$info = get_class( $funcs[0] ) . '::' . $funcs[1];
												} else {
													$info = print_r( $funcs, true );
												}
												$action .= $info . ' ( ' . $priority . ' ) ' . '<br>';
											} else {
												$action .= $funcs . ' ( ' . $priority . ' ) ' . '<br>';
											}
										}
									}
								}
							}
						}
					} else {
						$action = '-';
					}

					$output .= '<tr>';
					$output .= '<td>' . $proc . '</td>';
					$output .= '<td>' . $action . '</td>';
					foreach ( $task as $md5key => $taskdetails ) {
						if ( ! empty( $taskdetails['args'] ) ) {
							$output .= '<td>';
							foreach ( $taskdetails['args'] as $arg ) {
								$output .= $arg . '<br>';
							}
							$output .= '</td>';
						} else {
							$output .= '<td></td>';
						}
						if ( ! empty( $taskdetails['schedule'] ) ) {
							$output .= '<td>' . $taskdetails['schedule'] . ' ( ' . $taskdetails['interval'] . ' )</td>';
						} else {
							$output .= '<td>' . esc_html__( 'single ( - )', 'wp-live-debug' ) . '</td>';
						}
					}
					$output .= '<td>' . human_time_diff( $time, time() ) . '<br>' . date( 'H:i - F j, Y', $time ) . '</td>';
					$output .= '</tr>';
				}
			}
			$output .= '<tfoot><tr><th>' . esc_html__( 'Task', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Action', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Arguments', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Schedule', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Next Run In', 'wp-live-debug' ) . '</tr></tfoot>';
			$output .= '</tbody></table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}
	}
}
