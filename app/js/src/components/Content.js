/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import LogViewer from './LogViewer';
import Sidebar from './Sidebar';

/**
 * Main.
 *
 * @param {Object} props
 */
const Content = ( props ) => {
	return (
		<>
			<div
				className="content"
				role="region"
				aria-label={ __( 'Log view', 'wp-live-debug' ) }
				tabIndex="-1"
			>
				<div className="main">
					<h2>{ __( 'Viewing:', 'wp-live-debug' ) } { props.debugLogLocation }</h2>
					<LogViewer deubgLogContent={ props.deubgLogContent } />
				</div>
				<div className="sidebar">
					<Sidebar
						loading={ props.loading }
						alterConstant={ props.alterConstant }
						alterAutoRefresh={ props.alterAutoRefresh }
						hasManualBackup={ props.hasManualBackup }
						hasAutoBackup={ props.hasAutoBackup }
						debugEnabled={ props.debugEnabled }
						debugLogEnabled={ props.debugLogEnabled }
						debugDisplayEnabled={ props.debugDisplayEnabled }
						scriptDebugEnabled={ props.scriptDebugEnabled }
						saveQueriesEnabled={ props.saveQueriesEnabled }
						autoRefreshEnabled={ props.autoRefreshEnabled }
					/>
				</div>
			</div>
		</>
	);
};

export default Content;
