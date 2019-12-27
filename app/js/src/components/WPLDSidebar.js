/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

/**
 * Internal dependencies.
 */
import WPLDToggleDebug from './WPLDToggleDebug';
import WPLDToggleScriptDebug from './WPLDToggleScriptDebug';
import WPLDToggleSavequeries from './WPLDToggleSavequeries';
import WPLDToggleRefresh from './WPLDToggleRefresh';

/**
 * Main.
 */
const WPLDSidebar = () => {
	return (
		<Fragment>
			<div
				className="sidebar"
				role="region"
				aria-label={ __( 'WP Live Debug Settings', 'wp-live-debug' ) }
				tabIndex="-1"
			>
				<Panel>
					<PanelBody
						title={ __( 'Settings', 'wp-live-debug' ) }
						initialOpen={ true }
					>
						<PanelRow>
							<WPLDToggleDebug />
						</PanelRow>
						<PanelRow>
							<WPLDToggleScriptDebug />
						</PanelRow>
						<PanelRow>
							<WPLDToggleSavequeries />
						</PanelRow>
						<PanelRow>
							<WPLDToggleRefresh />
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
			</div>
		</Fragment>
	);
};

export default WPLDSidebar;
