/**
 * WordPress dependencies.
 */
import { Fragment, Component } from '@wordpress/element';
import axios from 'axios';

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

	componentDidMount() {
		// fetch the initial debug information.
		const states = this;
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-wp-debug-enabled',
				_ajax_nonce: wp_live_debug_globals.nonce,
			},
		} ).then( function( response ) {
			if ( true === response.data.success ) {
				states.setState( { debugEnabled: true } );
			}
		} ).catch( function( error ) {
			console.log( error );
		} );
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
