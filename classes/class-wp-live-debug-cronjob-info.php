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
			add_action( 'wp_ajax_wp-live-debug-run-cronjob', array( 'WP_Live_Debug_Cronjob_Info', 'run_cron' ) );
		}

		public static function create_page() {
			?>
				<div style="display:none;" id="job-success" class="sui-notice-top sui-notice-success sui-can-dismiss">
					<div class="sui-notice-content">
						<p><strong><span class="hookname"></span></strong>&nbsp;<?php esc_html_e( 'has run successfully!', 'wp-live-debug' ); ?></p>
					</div>
					<span class="sui-notice-dismiss"><a role="button" href="#" aria-label="Dismiss" class="sui-icon-check"></a>
					</span>
				</div>
				<div style="display:none;" id="job-error" class="sui-notice-top sui-notice-error sui-can-dismiss">
					<div class="sui-notice-content">
						<p><strong><span class="hookname"></span></strong>&nbsp;<?php esc_html_e( 'could not run.', 'wp-live-debug' ); ?></p>
					</div>
					<span class="sui-notice-dismiss"><a role="button" href="#" aria-label="Dismiss" class="sui-icon-check"></a></span>
				</div>
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
			$output .= '<thead><tr><th>' . esc_html__( 'Task', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Schedule', 'wp-live-debug' ) . '</th><th></th></tr></thead><tbody>';

			$events = WP_Live_Debug_Cronjob_Info::get_cronjobs();

			foreach ( $events as $id => $event ) {
				$output .= '<tr>';
				$output .= '<td>' . $event->hook . '</td>';
				$output .= '<td>';
				$actions = array();
				foreach ( WP_Live_Debug_Cronjob_Info::get_cronjobs_actions( $event->hook ) as $action ) {
					$actions[] = $action['callback']['name'] . ' ( ' . $action['priority'] . ' )<br>';
				}
				$output .= implode( '', $actions );
				$output .= '</td>';
				if ( ! empty( $event->schedule ) ) {
						$output .= '<td>' . $event->schedule . ' ( ' . $event->interval . ' )';
				} else {
					$output .= '<td>' . esc_html__( 'single', 'wp-live-debug' );
				}
				$output .= '<br><strong>' . esc_html__( 'Next run in', 'wp-live-debug' ) . ':</strong> ' . human_time_diff( $event->time, time() ) . '<br>' . date( 'H:i - F j, Y', $event->time ) . '</td>';
				$output .= '<td><button class="sui-button" data-do="run-job" data-hook="' . $event->hook . '" data-sig="' . $event->sig . '">Run Now</button></td>';
				$output .= '</tr>';
			}
			$output .= '<tfoot><tr><th>' . esc_html__( 'Task', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Schedule', 'wp-live-debug' ) . '</th><th></th></tr></tfoot>';
			$output .= '</tbody></table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function get_cronjobs() {
			if ( function_exists( '_get_cron_array' ) ) {
				$cronjobs = _get_cron_array();
			} else {
				$cronjobs = get_option( 'cron' );
			}
			$events = array();
			foreach ( $cronjobs as $time => $cron ) {
				foreach ( $cron as $hook => $tasks ) {
					foreach ( $tasks as $md5key => $data ) {
						$events[ "$hook-$md5key-$time" ] = (object) array(
							'hook'     => $hook,
							'time'     => $time,
							'sig'      => $md5key,
							'args'     => $data['args'],
							'schedule' => $data['schedule'],
							'interval' => isset( $data['interval'] ) ? $data['interval'] : null,
						);
					}
				}
			}
			return $events;
		}

		public static function get_cronjobs_actions( $name ) {
			global $wp_filter;
			$actions = array();
			if ( isset( $wp_filter[ $name ] ) ) {
				$action = $wp_filter[ $name ];
				foreach ( $action as $priority => $callbacks ) {
					foreach ( $callbacks as $callback ) {
						$callback = WP_Live_Debug_Cronjob_Info::populate_actions( $callback );

						$actions[] = array(
							'priority' => $priority,
							'callback' => $callback,
						);
					}
				}
			}
			return $actions;
		}

		public static function populate_actions( array $action ) {

			if ( is_string( $action['function'] ) && ( false !== strpos( $action['function'], '::' ) ) ) {
				$action['function'] = explode( '::', $action['function'] );
			}

			if ( is_array( $action['function'] ) ) {
				if ( is_object( $action['function'][0] ) ) {
					$class  = get_class( $action['function'][0] );
					$access = '->';
				} else {
					$class  = $action['function'][0];
					$access = '::';
				}

				$action['name'] = $class . $access . $action['function'][1] . '()';
			} elseif ( is_object( $action['function'] ) ) {
				if ( is_a( $action['function'], 'Closure' ) ) {
					$action['name'] = 'Closure';
				} else {
					$class          = get_class( $action['function'] );
					$action['name'] = $class . '->__invoke()';
				}
			} else {
				$action['name'] = $action['function'] . '()';
			}

			return $action;

		}

		public static function run_cron() {
			if ( function_exists( '_get_cron_array' ) ) {
				$cronjobs = _get_cron_array();
			} else {
				$cronjobs = get_option( 'cron' );
			}
			foreach ( $cronjobs as $time => $cron ) {
				if ( isset( $cron[ $_POST['hook'] ][ $_POST['sig'] ] ) ) {
					$args = $cron[ $_POST['hook'] ][ $_POST['sig'] ]['args'];
					delete_transient( 'doing_cron' );
					wp_schedule_single_event( time() - 1, $_POST['hook'], $args );
					spawn_cron();
					wp_send_json_success();
				}
			}
			wp_send_json_error();
		}
	}
}
