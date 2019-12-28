/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import LogList from './LogList';
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
						<div className="debug-area">
							<LogList />
							<LogViewer />
						</div>
					</div>
					<div className="sidebar">
						<Sidebar />
					</div>
				</div>
			</Fragment>
		);
	}
}

export default Content;
