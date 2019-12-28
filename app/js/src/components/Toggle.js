/**
 * WordPress dependencies.
 */
import { Fragment, Component } from '@wordpress/element';
import { FormToggle } from '@wordpress/components';


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

/**
 * Main.
 */
class Toggle extends Component {
	render() {
		return (
			<Fragment>
				<label
					htmlFor={ this.props.id }
					className="components-toggle-control__label"
				>{ this.props.name }</label>
				<FormToggle
					id={ this.props.id }
					checked={ this.props.checked }
					//onChange={ () => setState( ( state ) => ( { checked: ! state.checked } ) ) }
					// onClick={ getWPDebugState() }
				/>
			</Fragment>
		);
	}
}

export default Toggle;
