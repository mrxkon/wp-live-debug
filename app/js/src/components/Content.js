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
const Content = ( { debugEnabled, debugLogEnabled, debugDisplayEnabled, scriptDebugEnabled, saveQueriesEnabled, autoRefreshEnabled } ) => {
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
						debugEnabled={ debugEnabled }
						debugLogEnabled={ debugLogEnabled }
						debugDisplayEnabled={ debugDisplayEnabled }
						scriptDebugEnabled={ scriptDebugEnabled }
						saveQueriesEnabled={ saveQueriesEnabled }
						autoRefreshEnabled={ autoRefreshEnabled }
					/>
				</div>
			</div>
		</>
	);
};

export default Content;
