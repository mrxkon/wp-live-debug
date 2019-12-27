/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Main.
 */
const WPLDHeader = () => {
	return (
		<div
			className="header"
			role="region"
			aria-label={ __( 'WP Live Debug Top Bar', 'wp-live-debug' ) }
			tabIndex="-1"
		>
			<h1 className="header-title">{ __( 'WP Live Debug', 'wp-live-debug' ) }</h1>
		</div>
	);
};

export default WPLDHeader;
