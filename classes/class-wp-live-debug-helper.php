<?php //phpcs:ignore

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_Helper Class.
 */
if ( ! class_exists( 'WP_Live_Debug_Helper' ) ) {
	class WP_Live_Debug_Helper {

		/**
		 * WP_Live_Debug_Helper constructor.
		 *
		 * @uses WP_Live_Debug_Helper::init()
		 *
		 * @return void
		 */
		public function __construct() {
			$this->init();
		}

		/**
		 * Plugin initialization.
		 *
		 * @return void
		 */
		public static function init() {
			// silence
		}

		public static function table_wpmudev_constants( $array ) {
			$table = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Default Value', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			foreach ( $array as $key => $value ) {
				$table .= '<tr><td>' . esc_html( $value[0] ) . '</td><td>' . $value[1] . '</td><td>' . $value[2] . '</td></tr>';
				$table .= '<tr><td colspan="3"><em>' . esc_html( $value[3] ) . '</em></td></tr>';
			}
			$table .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Default Value', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$table .= '</tbody></table>';

			return $table;
		}

		public static function table_wpmudev_actions_filters( $array ) {
			$table  = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Filters', 'wp-live-debug' ) . '</th></tr></thead><tbody>';
			$table .= '<tr><td>';
			foreach ( $array['actions'] as $action ) {
				$table .= 'add_action( \'<strong>' . $action . '</strong>\', \'some_function\' );<br>';
			}
			$table .= '</td><td>';
			foreach ( $array['filters'] as $filter ) {
				$table .= 'add_filter( \'<strong>' . $filter . '</strong>\', \'some_function\' );<br>';
			}
			$table .= '</td><tr>';
			$table .= '<tfoot><tr><th>' . esc_html__( 'Actions', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Filters', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$table .= '</tbody></table>';

			return $table;
		}

		public static function table_general( $array ) {
			$table = '<table class="sui-table striped"><thead><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></thead><tbody>';

			foreach ( $array as $key => $value ) {
				$table .= '<tr><td>' . esc_html( $key ) . '</td><td>' . $value . '</td></tr>';
			}
			$table .= '<tfoot><tr><th>' . esc_html__( 'Title', 'wp-live-debug' ) . '</th><th>' . esc_html__( 'Value', 'wp-live-debug' ) . '</th></tr></tfoot>';
			$table .= '</tbody></table>';

			return $table;
		}

		public static function format_constant( $constant ) {
			if ( ! defined( $constant ) ) {
				return '<em>Undefined</em>';
			}
			$value = constant( $constant );
			if ( ! is_bool( $value ) ) {
				return $value;
			} elseif ( ! $value ) {
				return 'FALSE';
			} else {
				return 'TRUE';
			}
		}

		public static function format_num( $value ) {
			if ( is_numeric( $value ) and ( $value >= ( 1024 * 1024 ) ) ) {
				$value = size_format( $value );
			}
			return $value;
		}

	}
}
