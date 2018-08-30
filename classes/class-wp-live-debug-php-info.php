<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_PHP_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_PHP_Info' ) ) {
	class WP_Live_Debug_PHP_Info {

		/**
		 * WP_Live_Debug_PHP_Info constructor.
		 *
		 * @uses WP_Live_Debug_PHP_Info::init()
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
			// silence
		}

		public static function create_page() {
			?>
			<div class="sui-box">
				<div class="sui-box-body">
					<div class="sui-accordion">
						<div class="sui-accordion-item">
						</div>
					</div>
					<?php WP_Live_Debug_PHP_Info::get_info(); ?>
				</div>
			</div>
			<?php
		}

		public static function get_info() {
			if ( ! function_exists( 'phpinfo' ) ) {
			?>
				<div class="sui-notice sui-notice-error">
					<p><?php _e( '<code>phpinfo();</code> is disabled. Please contact your hosting provider if you need more information about your PHP setup.', 'wp-live-debug' ); ?></p>
				</div>
			<?php
			} else {
				ob_start();
				phpinfo();
				$phpinfo_output = ob_get_clean();

				preg_match_all( '/<body[^>]*>(.*)<\/body>/siU', $phpinfo_output, $phpinfo );
				preg_match_all( '/<style[^>]*>(.*)<\/style>/siU', $phpinfo_output, $styles );

				$remove_patterns = array( "/a:.+?\n/si", "/body.+?\n/si" );

				if ( isset( $styles[1][0] ) ) {
					$styles = preg_replace( $remove_patterns, '', $styles[1][0] );
					echo '<style type="text/css">' . $styles . '</style>';
				}

				if ( isset( $phpinfo[1][0] ) ) {
					echo $phpinfo[1][0];
				}
			}
		}
	}
}
