<?php
/**
 * @package WP Live Debug
 * @version 4.9.8
 *
 * Plugin Name:       WP Live Debug
 * Description:       Enables debugging and adds a screen into the WordPress Admin to view the debug.log.
 * Version:           4.9.8
 * Author:            Xenos (xkon) Konstantinos
 * Author URI:        https://xkon.gr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-live-debug
 * Domain Path:       /languages
 *
 */

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug Class.
 */
class WP_Live_Debug {

	/**
	 * WP_Live_Debug constructor.
	 *
	 * @uses WPLiveDebug::init()
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
	public function init() {

		add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
		add_action( 'wp_ajax_wp-live-debug-read-log', array( $this, 'read_debug_log' ) );
		add_action( 'wp_ajax_wp-live-debug-clear-debug-log', array( $this, 'clear_debug_log' ) );
		add_action( 'wp_ajax_wp-live-debug-create-backup', array( $this, 'create_wp_config_backup' ) );
		add_action( 'wp_ajax_wp-live-debug-restore-backup', array( $this, 'restore_wp_config_backup' ) );
		add_action( 'wp_ajax_wp-live-debug-enable', array( $this, 'enable_wp_debug' ) );
		add_action( 'wp_ajax_wp-live-debug-disable', array( $this, 'disable_wp_debug' ) );
		add_action( 'wp_ajax_wp-live-debug-enable-script-debug', array( $this, 'enable_script_debug' ) );
		add_action( 'wp_ajax_wp-live-debug-disable-script-debug', array( $this, 'disable_script_debug' ) );
		add_action( 'wp_ajax_wp-live-debug-enable-savequeries', array( $this, 'enable_savequeries' ) );
		add_action( 'wp_ajax_wp-live-debug-disable-savequeries', array( $this, 'disable_savequeries' ) );

	}

	/**
	 * Create Admin menu.
	 *
	 * @uses add_menu_page()
	 *
	 * @return void
	 */
	public function create_admin_menu() {

		add_menu_page(
			'WP Live Debug',
			'WP Live Debug',
			'manage_options',
			'wp-live-debug',
			array( $this, 'create_debug_page' ),
			'dashicons-media-code',
			'80'
		);

	}

	/**
	 * Create Admin Page
	 *
	 * @uses ini_set()
	 *
	 * @return void
	 */
	public function create_debug_page() {
		?>
		<div class="wrap">
			<h1>WP Live Debug</h1>
			<textarea id="wp-live-debug-area" style="font-family: Consolas,Monaco,monospace; font-size: 14px;width: 100%; height:calc( 100vh - 300px );"></textarea>
			<p id="wp-debug-response-holder"></p>
			<div class="wp-live-debug-buttons">
				<?php if ( ! WP_Live_Debug::check_wp_config_backup() ) { ?>
					<form action="#" id="wp-live-debug-create-wp-debug-backup" method="POST">
						<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Backup wp-config', 'wp-live-debug' ); ?>">
					</form>
				<?php } else { ?>
					<form action="#" id="wp-live-debug-restore-wp-debug-backup" method="POST">
						<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Restore wp-config', 'wp-live-debug' ); ?>">
					</form>
					<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { ?>
						<form action="#" id="wp-live-debug-disable" method="POST">
							<input type="submit" class="button disable" value="<?php esc_html_e( 'Disable', 'wp-live-debug' ); ?> WP_DEBUG">
						</form>
					<?php } else { ?>
						<form action="#" id="wp-live-debug-enable" method="POST">
							<input type="submit" class="button enable" value="<?php esc_html_e( 'Enable', 'wp-live-debug' ); ?> WP_DEBUG">
						</form>
					<?php } ?>
					<?php if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) { ?>
						<form action="#" id="wp-live-debug-disable-script-debug" method="POST">
							<input type="submit" class="button disable" value="<?php esc_html_e( 'Disable', 'wp-live-debug' ); ?> SCRIPT_DEBUG">
						</form>
					<?php } else { ?>
						<form action="#" id="wp-live-debug-enable-script-debug" method="POST">
							<input type="submit" class="button enable" value="<?php esc_html_e( 'Enable', 'wp-live-debug' ); ?> SCRIPT_DEBUG">
						</form>
					<?php } ?>
					<?php if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) { ?>
						<form action="#" id="wp-live-debug-disable-savequeries" method="POST">
							<input type="submit" class="button disable" value="<?php esc_html_e( 'Disable', 'wp-live-debug' ); ?> SAVEQUERIES">
						</form>
					<?php } else { ?>
						<form action="#" id="wp-live-debug-enable-savequeries" method="POST">
							<input type="submit" class="button enable" value="<?php esc_html_e( 'Enable', 'wp-live-debug' ); ?> SAVEQUERIES">
						</form>
					<?php } ?>
				<?php } ?>
				<form action="#" id="wp-live-debug-clear-wp-debug" method="POST">
					<input type="submit" class="button" value="<?php esc_html_e( 'Clear debug.log', 'wp-live-debug' ); ?>">
				</form>
				<form action="#" id="wp-live-debug-start-stop" method="POST">
					<input type="button" id="ss-button" class="button" value="<?php esc_html_e( 'Stop auto refresh', 'wp-live-debug' ); ?>">
					<input type="hidden" id="wp-live-debug-scroll" value="yes">
				</form>
			</div>
		</div>
		<?php
		ob_start();
		require_once 'assets/scripts.php';
		$scripts = ob_get_clean();
		ob_start();
		require_once 'assets/styles.php';
		$styles = ob_get_clean();
		echo $scripts;
		echo $styles;
	}

	/**
	 * Check if wp-config backup exists
	 *
	 * @uses file_exists()
	 *
	 * @return bool true/false depending if the backup exists
	 */
	public function check_wp_config_backup() {
		$wpconfig_backup = ABSPATH . 'wp-config_wpld_backup.php';

		if ( file_exists( $wpconfig_backup ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Creates a backup of wp-config.php
	 *
	 * @uses file_exists()
	 * @uses copy()
	 *
	 * @return string $output Success/Fail
	 */
	public function create_wp_config_backup() {
		$wpconfig        = ABSPATH . 'wp-config.php';
		$wpconfig_backup = ABSPATH . 'wp-config_wpld_backup.php';

		if ( ! copy( $wpconfig, $wpconfig_backup ) ) {
			$response = array(
				'message' => esc_html__( 'wp-config.php backup failed.', 'wp-live-debug' ),
			);
			wp_send_json_error( $response );
		}

		$response = array(
			'message' => esc_html__( 'wp-config.php backup was created.', 'wp-live-debug' ),
		);

		wp_send_json_success( $response );
	}

	/**
	 * Restores a backup of wp-config.php
	 *
	 * @uses copy()
	 * @uses unlink()
	 *
	 * @return string $output Success/Fail
	 */
	public function restore_wp_config_backup() {
		$wpconfig        = ABSPATH . 'wp-config.php';
		$wpconfig_backup = ABSPATH . 'wp-config_wpld_backup.php';

		if ( ! copy( $wpconfig_backup, $wpconfig ) ) {
			$response = array(
				'message' => esc_html__( 'wp-config.php restore failed.', 'wp-live-debug' ),
			);
			wp_send_json_error( $response );
		}

		$response = array(
			'message' => esc_html__( 'wp-config.php backup was restored.', 'wp-live-debug' ),
		);

		unlink( $wpconfig_backup );

		wp_send_json_success( $response );
	}

	/**
	 * Enables WP_DEBUG
	 *
	 * @uses copy()
	 * @uses file()
	 * @uses file_put_contents()
	 * @uses fopen()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function enable_wp_debug() {
		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'WP_DEBUG'" ) || false !== strpos( $line, '"WP_DEBUG"' ) ) {
				$line  = "define( 'WP_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'WP_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

		$response = array(
			'message' => esc_html__( 'WP_DEBUG was enabled.', 'health-check' ),
		);

		$this->enable_wp_debug_log();
		$this->disable_wp_debug_display();
		$this->disable_wp_debug_ini_set_display();

		wp_send_json_success( $response );

	}

	/**
	 * Disables WP_DEBUG
	 *
	 * @uses fopen()
	 * @uses copy()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function disable_wp_debug() {
		$wpconfig = ABSPATH . 'wp-config.php';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'WP_DEBUG'" ) || false !== strpos( $line, '"WP_DEBUG"' ) ) {
				$line = "define( 'WP_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		$response = array(
			'message' => esc_html__( 'WP_DEBUG was disabled.', 'health-check' ),
		);

		$this->disable_wp_debug_log();
		$this->disable_wp_debug_display();
		$this->disable_wp_debug_ini_set_display();

		wp_send_json_success( $response );

	}

	/**
	 * Enables WP_DEBUG_LOG
	 *
	 * @uses copy()
	 * @uses file()
	 * @uses file_put_contents()
	 * @uses fopen()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function enable_wp_debug_log() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'WP_DEBUG_LOG'" ) || false !== strpos( $line, '"WP_DEBUG_LOG"' ) ) {
				$line  = "define( 'WP_DEBUG_LOG', true ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'WP_DEBUG_LOG', true ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

	}

	/**
	 * Disables WP_DEBUG_LOG
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses copy()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function disable_wp_debug_log() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'WP_DEBUG_LOG'" ) || false !== strpos( $line, '"WP_DEBUG_LOG"' ) ) {
				$line  = "define( 'WP_DEBUG_LOG', false ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'WP_DEBUG_LOG', false ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

	}

	/**
	 * Disables WP_DEBUG_DISPLAY
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses copy()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function disable_wp_debug_display() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'WP_DEBUG_DISPLAY'" ) || false !== strpos( $line, '"WP_DEBUG_DISPLAY"' ) ) {
				$line  = "define( 'WP_DEBUG_DISPLAY', false ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'WP_DEBUG_DISPLAY', false ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

	}

	/**
	 * Disables ini_set display_errors
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses copy()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function disable_wp_debug_ini_set_display() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'display_errors'" ) || false !== strpos( $line, '"display_errors"' ) ) {
				$line  = "@ini_set( 'display_errors', 0 ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "@ini_set( 'display_errors', 0 ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

	}

	/**
	 * Enables SCRIPT_DEBUG
	 *
	 * @uses copy()
	 * @uses file()
	 * @uses file_put_contents()
	 * @uses fopen()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function enable_script_debug() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'SCRIPT_DEBUG'" ) || false !== strpos( $line, '"SCRIPT_DEBUG"' ) ) {
				$line  = "define( 'SCRIPT_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'SCRIPT_DEBUG', true ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

		$response = array(
			'message' => esc_html__( 'SCRIPT_DEBUG was enabled.', 'health-check' ),
		);

		wp_send_json_success( $response );

	}

	/**
	 * Disables SCRIPT_DEBUG
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses copy()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function disable_script_debug() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'SCRIPT_DEBUG'" ) || false !== strpos( $line, '"SCRIPT_DEBUG"' ) ) {
				$line  = "define( 'SCRIPT_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'SCRIPT_DEBUG', false ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

		$response = array(
			'message' => esc_html__( 'SCRIPT_DEBUG was disabled.', 'health-check' ),
		);

		wp_send_json_success( $response );

	}

	/**
	 * Enables SAVEQUERIES
	 *
	 * @uses copy()
	 * @uses file()
	 * @uses file_put_contents()
	 * @uses fopen()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function enable_savequeries() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'SAVEQUERIES'" ) || false !== strpos( $line, '"SAVEQUERIES"' ) ) {
				$line  = "define( 'SAVEQUERIES', true ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'SAVEQUERIES', true ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

		$response = array(
			'message' => esc_html__( 'SAVEQUERIES was enabled.', 'health-check' ),
		);

		wp_send_json_success( $response );

	}

	/**
	 * Disables SAVEQUERIES
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses copy()
	 * @uses strpos()
	 * @uses fputs()
	 * @uses fclose()
	 * @uses wp_send_json_error()
	 * @uses wp_send_json_succes()
	 *
	 * @return void
	 */
	public function disable_savequeries() {

		$wpconfig = ABSPATH . 'wp-config.php';

		$found = 'no';

		$editing_wpconfig = file( $wpconfig );

		file_put_contents( $wpconfig, '' );

		$write_wpconfig = fopen( $wpconfig, 'w' );

		foreach ( $editing_wpconfig as $line ) {
			if ( false !== strpos( $line, "'SAVEQUERIES'" ) || false !== strpos( $line, '"SAVEQUERIES"' ) ) {
				$line  = "define( 'SAVEQUERIES', false ); // Added by WP Live Debug" . PHP_EOL;
				$found = 'yes';
			}
			fputs( $write_wpconfig, $line );
		}

		fclose( $write_wpconfig );

		if ( 'no' === $found ) {

			$editing_wpconfig = file( $wpconfig );

			file_put_contents( $wpconfig, '' );

			$write_wpconfig = fopen( $wpconfig, 'w' );

			foreach ( $editing_wpconfig as $line ) {
				if ( false !== strpos( $line, 'stop editing!' ) ) {
					$line  = "define( 'SAVEQUERIES', false ); // Added by WP Live Debug" . PHP_EOL;
					$line .= "/* That's all, stop editing! Happy blogging. */" . PHP_EOL;
				}
				fputs( $write_wpconfig, $line );
			}

			fclose( $write_wpconfig );
		}

		$response = array(
			'message' => esc_html__( 'SAVEQUERIES was disabled.', 'health-check' ),
		);

		wp_send_json_success( $response );

	}

	/**
	 * Read debug.log contents and return them.
	 *
	 * @uses file_exists()
	 * @uses fopen()
	 * @uses die()
	 * @uses fwrite()
	 * @uses fclose()
	 * @uses WP_CONTENT_DIR
	 * @uses wp_die()
	 *
	 * @return string $debug_contents The content of debug.log
	 */
	public function read_debug_log() {
		if ( ! file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {
			$fo = fopen( WP_CONTENT_DIR . '/debug.log', 'w' ) or die( 'Cannot create debug.log!' );
			fwrite( $fo, '' );
			fclose( $fo );
		}

		$debug_contents = file_get_contents( WP_CONTENT_DIR . '/debug.log' );

		echo $debug_contents;

		wp_die();
	}

	/**
	 * Clear debug.log content.
	 *
	 * @uses file_put_contents()
	 * @uses wp_die()
	 *
	 * @return void
	 */
	public function clear_debug_log() {
		file_put_contents( WP_CONTENT_DIR . '/debug.log', '' );

		wp_die();
	}

}

// Initialize WP Live Debug.
new WP_Live_Debug();
