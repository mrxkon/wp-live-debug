<?php

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_WordPress_Info Class.
 */
if ( ! class_exists( 'WP_Live_Debug_WordPress_Info' ) ) {
	class WP_Live_Debug_WordPress_Info {

		/**
		 * WP_Live_Debug_WordPress_Info constructor.
		 *
		 * @uses WP_Live_Debug_WordPress_Info::init()
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
						<h2 class="sui-box-title">WordPress</h2>
					</div>
					<div class="sui-box-body">
						<table class="sui-table striped">
							<tbody>
								<tr>
									<td>
										a
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			<?php
		}
	}
}
