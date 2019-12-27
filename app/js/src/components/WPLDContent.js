/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import WPLDLogViewer from './WPLDLogViewer';

const WPLDContent = () => {
	return (
		<div
			className="content"
			role="region"
			aria-label={ __( 'Log view', 'wp-live-debug' ) }
			tabIndex="-1"
		>
			<WPLDLogViewer />
		</div>
	);
};

export default WPLDContent;
