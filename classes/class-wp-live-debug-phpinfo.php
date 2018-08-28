<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_PHPINFO Class.
 */
if ( ! class_exists( 'WP_Live_Debug_PHPINFO' ) ) {
	class WP_Live_Debug_PHPINFO {

		/**
		 * WP_Live_Debug_PHPINFO constructor.
		 *
		 * @uses WP_Live_Debug_PHPINFO::init()
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

		public static function create_phpinfo_page() {
			?>
			<div class="sui-wrap">
				<div class="sui-header">
					<h1 class="sui-header-title">WP Live Debug</h1>
				</div>
				<div class="sui-box">
					<div class="sui-box-body">
						<?php WP_Live_Debug_PHPINFO::show_php_info(); ?>
					</div>
				</div>
			<?php
		}

		public static function show_php_info() {
			if ( ! function_exists( 'phpinfo' ) ) {
			?>
				<div class="sui-notice sui-notice-error">
					<p><?php _e( 'The <code>phpinfo();</code> function is disabled. Please contact your hosting provider if you need more information about your PHP setup.', 'wp-live-debug' ); ?></p>
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

	new WP_Live_Debug_PHPINFO();
}
