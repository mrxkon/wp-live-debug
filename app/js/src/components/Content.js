/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import LogViewer from './LogViewer';
import Sidebar from './Sidebar';

/**
 * Main.
 */
class Content extends Component {
	render() {
		return (
			<Fragment>
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
							debugEnabled={ this.props.debugEnabled }
							debugLogEnabled={ this.props.debugLogEnabled }
							debugDisplayEnabled={ this.props.debugDisplayEnabled }
							scriptDebugEnabled={ this.props.scriptDebugEnabled }
							saveQueriesEnabled={ this.props.saveQueriesEnabled }
							autoRefreshEnabled={ this.props.autoRefreshEnabled }
						/>
					</div>
				</div>
			</Fragment>
		);
	}
}

export default Content;
