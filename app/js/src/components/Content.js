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
					<LogViewer />
				</div>
				<div className="sidebar">
					<Sidebar
						alterWPDebug={ props.alterWPDebug }
						alterWPDebugLog={ props.alterWPDebugLog }
						alterWPDebugDisplay={ props.alterWPDebugDisplay }
						alterScriptDebug={ props.alterScriptDebug }
						alterSaveQueries={ props.alterSaveQueries }
						alterAutoRefresh={ props.alterAutoRefresh }
						hasBackup={ props.hasBackup }
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
