/**
 * WordPress dependencies.
 */
import { render } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import App from './app';

/**
 * Render the WP Live Debug screen.
 */
render( <App />, document.getElementById( 'wpld-page' ) );
