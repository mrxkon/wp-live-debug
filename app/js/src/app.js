/**
 * WordPress dependencies.
 */
import { Fragment, Component } from '@wordpress/element';

/**
 * Internal Dependencies.
 */
import Header from './components/Header';
import Content from './components/Content';

/**
 * Main.
 */
class App extends Component {
	constructor() {
		super();

		// Set default states all to false.
		this.state = {
			debugEnabled: false,
			debugLogEnabled: false,
			debugDisplayEnabled: false,
			scriptDebugEnabled: false,
			saveQueriesEnabled: false,
			autoRefreshEnabled: false,
			hasBackup: false,
		};
	}

	render() {
		return (
			<Fragment>
				<Header hasBackup={ this.state.hasBackup } />
				<Content
					debugEnabled={ this.state.debugEnabled }
					debugLogEnabled={ this.state.debugLogEnabled }
					debugDisplayEnabled={ this.state.debugDisplayEnabled }
					scriptDebugEnabled={ this.state.scriptDebugEnabled }
					saveQueriesEnabled={ this.state.saveQueriesEnabled }
					autoRefreshEnabled={ this.state.autoRefreshEnabled }
				/>
			</Fragment>
		);
	}
}

export default App;

/** Notes */
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