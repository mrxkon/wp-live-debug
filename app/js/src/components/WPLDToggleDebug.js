/**
 * WordPress dependencies.
 */
import { FormToggle } from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import { withState } from '@wordpress/compose';

/**
 * Main.
 */

// async function getWPDebugState() {
// 	let isChecked = false;
// 	await axios( {
// 		method: 'post',
// 		url: wp_live_debug_globals.ajax_url,
// 		params: {
// 			action: 'wp-live-debug-is-wp-debug-enabled',
// 			_ajax_nonce: wp_live_debug_globals.nonce,
// 		},
// 	} ).then( function( response ) {
// 		if ( true === response.data.success ) {
// 			return {
// 				isChecked: true,
// 			};
// 		}
// 	} ).catch( function( error ) {
// 		console.log( error );
// 	} );

// 	return {
// 		isChecked: false,
// 	};
// }

// let isChecked = getWPDebugState();
// console.log( isChecked );
const WPLDToggleDebug = withState( { checked: true } )( ( { checked, setState } ) => {
	return (
		<Fragment>
			<label
				htmlFor="enable-wp-debug"
				className="components-toggle-control__label"
			>WP_DEBUG</label>
			<FormToggle
				id="enable-wp-debug"
				checked={ checked }
				onChange={ () => setState( ( state ) => ( { checked: ! state.checked } ) ) }
				// onClick={ getWPDebugState() }
			/>
		</Fragment>
	);
}
);
export default WPLDToggleDebug;
