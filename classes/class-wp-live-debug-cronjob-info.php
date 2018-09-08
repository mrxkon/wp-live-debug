<?php //phpcs:ignore

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
			add_action( 'wp_ajax_wp-live-debug-cronjob-info-list', array( 'WP_Live_Debug_Cronjob_Info', 'list' ) );
			add_action( 'wp_ajax_wp-live-debug-cronjob-info-run', array( 'WP_Live_Debug_Cronjob_Info', 'run' ) );
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

		public static function list() {

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
			$output .= '<thead><tr><th>' . esc_html__( 'Task', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Schedule', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			$events = WP_Live_Debug_Cronjob_Info::get_events();

			foreach ( $events as $id => $event ) {
				$output .= '<tr>';
				$output .= '<td>' . $event->hook . '<br>';
				$output .= '<a href="#" data-do="run-job" data-nonce="' . wp_create_nonce( $event->hook ) . '" data-hook="' . $event->hook . '" data-sig="' . $event->sig . '">Run Now</a>';
				$output .= '</td>';
				$output .= '<td>';
				$actions = array();
				foreach ( WP_Live_Debug_Cronjob_Info::get_actions( $event->hook ) as $action ) {
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
				$output .= '</tr>';
			}
			$output .= '<tfoot><tr><th>' . esc_html__( 'Task', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Schedule', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$output .= '</tbody></table>';

			$response = array(
				'message' => $output,
			);

			wp_send_json_success( $response );
		}

		public static function run() {
			$hook  = sanitize_text_field( $_POST['hook'] );
			$sig   = sanitize_text_field( $_POST['sig'] );
			$nonce = sanitize_text_field( $_POST['nonce'] );

			if ( ! wp_verify_nonce( $nonce, $hook ) ) {
				wp_send_json_error();
			}

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

		public static function get_events() {
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

		public static function get_actions( $name ) {
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

		public static function populate_actions( $actions ) {
			if ( is_string( $actions['function'] ) && ( false !== strpos( $actions['function'], '::' ) ) ) {
				$actions['function'] = explode( '::', $actions['function'] );
			}

			if ( is_array( $actions['function'] ) ) {
				if ( is_object( $actions['function'][0] ) ) {
					$class  = get_class( $actions['function'][0] );
					$access = '->';
				} else {
					$class  = $actions['function'][0];
					$access = '::';
				}

				$actions['name'] = $class . $access . $actions['function'][1] . '()';
			} elseif ( is_object( $actions['function'] ) ) {
				if ( is_a( $actions['function'], 'Closure' ) ) {
					$actions['name'] = 'Closure';
				} else {
					$class          = get_class( $actions['function'] );
					$actions['name'] = $class . '->__invoke()';
				}
			} else {
				$actions['name'] = $actions['function'] . '()';
			}

			return $actions;
		}
	}
}
