/**
 * WordPress dependencies.
 */
import { render } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import App from './app';

/**
 * Initialized the WP Live Debug screen.
 */
render( <App />, document.getElementById( 'wpld-page' ) );
