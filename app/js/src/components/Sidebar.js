/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

/**
 * Internal dependencies.
 */
import Toggle from './Toggle';

/**
 * Main.
 */
const Sidebar = ( { debugEnabled, debugLogEnabled, debugDisplayEnabled, scriptDebugEnabled, saveQueriesEnabled, autoRefreshEnabled } ) => {
	return (
		<>
			<Panel>
				<PanelBody
					title={ __( 'Settings', 'wp-live-debug' ) }
					initialOpen={ true }
				>
					<PanelRow>
						<Toggle name="WP_DEBUG" id="debugEnabled" checked={ debugEnabled } />
					</PanelRow>
					<PanelRow>
						<Toggle name="WP_DEBUG_LOG" id="debugLogEnabled" checked={ debugLogEnabled } />
					</PanelRow>
					<PanelRow>
						<Toggle name="WP_DEBUG_DISPLAY" id="debugDisplayEnabled" checked={ debugDisplayEnabled } />
					</PanelRow>
					<PanelRow>
						<Toggle name="SCRIPT_DEBUG" id="scriptDebugEnabled" checked={ scriptDebugEnabled } />
					</PanelRow>
					<PanelRow>
						<Toggle name="SAVEQUERIES" id="saveQueriesEnabled" checked={ saveQueriesEnabled } />
					</PanelRow>
					<PanelRow>
						<Toggle name="Auto Refresh" id="autoRefreshEnabled" checked={ autoRefreshEnabled } />
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
		</>
	);
};

export default Sidebar;
