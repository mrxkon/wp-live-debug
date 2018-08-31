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
			// silence
		}

		public static function create_page() {
			?>
				<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title">Checksums Check</h2>
					</div>
					<div class="sui-box-body">
					</div>
				</div>
				<div class="sui-box">
					<div class="sui-box-header">
						<h2 class="sui-box-title">wp_mail() Check</h2>
					</div>
					<div class="sui-box-body">
					</div>
				</div>
			<?php
		}
	}
}
