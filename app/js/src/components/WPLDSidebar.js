/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

const WPLDSidebar = () => {
	return (
		<div
			className="sidebar"
			role="region"
			aria-label={ __( 'WP Live Debug Settings', 'wp-live-debug' ) }
			tabIndex="-1"
		>
			<div className="section">
				<div className="section-header">
					<h2>{ __( 'Settings', 'wp-live-debug' ) }</h2>
				</div>
				<div className="section-content">
					test
				</div>
			</div>
			<div className="section">
				<div className="section-header">
					<h2>{ __( 'Information', 'wp-live-debug' ) }</h2>
				</div>
				<div className="section-content">
					test
				</div>
			</div>
		</div>
	);
};

export default WPLDSidebar;
