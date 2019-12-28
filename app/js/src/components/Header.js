/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';

/**
 * Main.
 */
class Header extends Component {
	render() {
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
	}
}

export default Header;
