/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Panel, PanelBody, PanelRow, FormToggle } from '@wordpress/components';

/**
 * Main.
 *
 * @param {Object} props
 */
const Sidebar = ( props ) => {
	return (
		<>
			<Panel>
				<PanelBody title={ __( 'Settings', 'wp-live-debug' ) } initialOpen={ true }>
					{ props.hasBackup ? (
					<>
					<PanelRow>
						<label
							htmlFor="alter-wp-debug"
							className="components-toggle-control__label"
						>WP_DEBUG</label>
						<FormToggle
							id="alter-wp-debug"
							checked={ props.debugEnabled }
							onClick={ props.alterWPDebug }
						/>
					</PanelRow>
					<PanelRow>
						<label
							htmlFor="alter-wp-debug-log"
							className="components-toggle-control__label"
						>WP_DEBUG_LOG</label>
						<FormToggle
							id="alter-wp-debug-log"
							checked={ props.debugLogEnabled }
							onClick={ props.alterWPDebugLog }
						/>
					</PanelRow>
					<PanelRow>
						<label
							htmlFor="alter-wp-debug-display"
							className="components-toggle-control__label"
						>WP_DEBUG_DISPLAY</label>
						<FormToggle
							id="alter-wp-debug-display"
							checked={ props.debugDisplayEnabled }
							onClick={ props.alterWPDebugDisplay }
						/>
					</PanelRow>
					<PanelRow>
						<label
							htmlFor="alter-wp-script-debug"
							className="components-toggle-control__label"
						>SCRIPT_DEBUG</label>
						<FormToggle
							id="alter-wp-script-debug"
							checked={ props.scriptDebugEnabled }
							onClick={ props.alterScriptDebug }
						/>
					</PanelRow>
					<PanelRow>
						<label
							htmlFor="alter-wp-savequeries"
							className="components-toggle-control__label"
						>SAVEQUERIES</label>
						<FormToggle
							id="alter-wp-savequeries"
							checked={ props.saveQueriesEnabled }
							onClick={ props.alterSaveQueries }
						/>
					</PanelRow>
					</>
					) : (
					<PanelRow>
						<span>{ __( 'Backup wp-config for more settings!', 'wp-live-debug' ) }</span>
					</PanelRow>
					) }
					<PanelRow>
						<label
							htmlFor="alterAutoRefresh"
							className="components-toggle-control__label"
						>Auto Refresh</label>
						<FormToggle
							id="alterAutoRefresh"
							checked={ props.autoRefreshEnabled }
							onClick={ props.alterAutoRefresh }
						/>
					</PanelRow>
				</PanelBody>
			</Panel>
			<Panel>
				<PanelBody title={ __( 'More Information', 'wp-live-debug' ) } initialOpen={ false }>
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
