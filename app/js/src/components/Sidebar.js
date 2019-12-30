/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Panel, PanelBody, PanelRow, FormToggle, Spinner, Button } from '@wordpress/components';

/**
 * Main.
 *
 * @param {Object} props
 */
const Sidebar = ( props ) => {
	return (
		<>
			<Panel header={ __( 'Settings & Information', 'wp-live-debug' ) }>
				<PanelBody title={ __( 'Constants Settings', 'wp-live-debug' ) } initialOpen={ true } className={ props.loading } icon={ <Spinner /> }>
					{ props.hasManualBackup ? (
						<>
							<PanelRow>
								<label
									htmlFor="WP_DEBUG"
									className="components-toggle-control__label"
								>WP_DEBUG</label>
								<FormToggle
									id="WP_DEBUG"
									checked={ props.debugEnabled }
									onClick={ props.alterConstant }
								/>
							</PanelRow>
							<PanelRow>
								<label
									htmlFor="WP_DEBUG_LOG"
									className="components-toggle-control__label"
								>WP_DEBUG_LOG</label>
								<FormToggle
									id="WP_DEBUG_LOG"
									checked={ props.debugLogEnabled }
									onClick={ props.alterConstant }
								/>
							</PanelRow>
							<PanelRow>
								<label
									htmlFor="WP_DEBUG_DISPLAY"
									className="components-toggle-control__label"
								>WP_DEBUG_DISPLAY</label>
								<FormToggle
									id="WP_DEBUG_DISPLAY"
									checked={ props.debugDisplayEnabled }
									onClick={ props.alterConstant }
								/>
							</PanelRow>
							<PanelRow>
								<label
									htmlFor="SCRIPT_DEBUG"
									className="components-toggle-control__label"
								>SCRIPT_DEBUG</label>
								<FormToggle
									id="SCRIPT_DEBUG"
									checked={ props.scriptDebugEnabled }
									onClick={ props.alterConstant }
								/>
							</PanelRow>
							<PanelRow>
								<label
									htmlFor="SAVEQUERIES"
									className="components-toggle-control__label"
								>SAVEQUERIES</label>
								<FormToggle
									id="SAVEQUERIES"
									checked={ props.saveQueriesEnabled }
									onClick={ props.alterConstant }
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
					<PanelRow>
						<Button
							isLink
							onClick={ props.clearLog }
						>
							{ __( 'Clear log.', 'wp-live-debug' ) }
						</Button>
						<Button
							isLink
							isDestructive
							onClick={ props.deleteLog }
						>
							{ __( 'Delete log.', 'wp-live-debug' ) }
						</Button>
					</PanelRow>
				</PanelBody>
			</Panel>
			<Panel>
				<PanelBody title={ __( 'More Information', 'wp-live-debug' ) } initialOpen={ false }>
					<PanelRow>
						<span>
							{ __( 'You will find extra wp-config.php backups in your WordPress root directory as:', 'wp-live-debug' ) }
						</span>
					</PanelRow>
					<PanelRow>
						<span>
							{ props.hasAutoBackup &&
								<><strong>wp-config.WPLD-auto.php </strong><br /></>
							}
							{ props.hasManualBackup &&
								<strong>wp-config.WPLD-manual.php</strong>
							}
						</span>
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
