/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Fragment, Component } from '@wordpress/element';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

/**
 * Internal dependencies.
 */
import Toggle from './Toggle';

/**
 * Main.
 */
class Sidebar extends Component {
	render() {
		return (
			<Fragment>
				<Panel>
					<PanelBody
						title={ __( 'Settings', 'wp-live-debug' ) }
						initialOpen={ true }
					>
						<PanelRow>
							<Toggle name="WP_DEBUG" id="enable-wp-debug" checked={ true } />
						</PanelRow>
						<PanelRow>
							<Toggle name="WP_DEBUG_LOG" id="enable-wp-debug-log" checked={ true } />
						</PanelRow>
						<PanelRow>
							<Toggle name="WP_DEBUG_DISPLAY" id="enable-wp-debug-display" checked={ true } />
						</PanelRow>
						<PanelRow>
							<Toggle name="SCRIPT_DEBUG" id="enable-script-debug" checked={ false } />
						</PanelRow>
						<PanelRow>
							<Toggle name="SAVEQUERIES" id="enable-savequeries" checked={ true } />
						</PanelRow>
						<PanelRow>
							<Toggle name="Auto Refresh" id="enable-auto-refresh" checked={ false } />
						</PanelRow>
					</PanelBody>
				</Panel>
				<Panel>
					<PanelBody
						title={ __( 'More Information', 'wp-live-debug' ) }
						initialOpen={ false }
					>
						<PanelRow>
							<span>
								{ __( 'You will find two extra wp-config.php backups in your WordPress root directory as:', 'wp-live-debug' ) }
							</span>
						</PanelRow>
						<PanelRow>
							<strong>wp-config.WPLD-auto.php <br /> wp-config.WPLD-manual.php</strong>
						</PanelRow>
						<PanelRow>
							<span>
								{ __( 'For more information you can visit', 'wp-live-debug' ) } <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/article/debugging-in-wordpress/">{ __( 'Debugging in WordPress', 'wp-live-debug' ) }</a>.
							</span>
						</PanelRow>
					</PanelBody>
				</Panel>
			</Fragment>
		);
	}
}

export default Sidebar;
