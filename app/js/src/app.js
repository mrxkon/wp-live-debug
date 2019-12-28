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
	/**
	 * Set state.
	 */
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

	/**
	 * See if WP_DEBUG is true.
	 */
	isDebugEnabled() {
		const states = this;
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'WP_DEBUG',
			},
		} ).then( function( response ) {
			if ( true === response.data.success ) {
				states.setState( { debugEnabled: true } );
			}
		} );
	}

	/**
	 * See if WP_DEBUG_LOG is true.
	 */
	isDebugLogEnabled() {
		const states = this;
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'WP_DEBUG_LOG',
			},
		} ).then( function( response ) {
			if ( true === response.data.success ) {
				states.setState( { debugLogEnabled: true } );
			}
		} );
	}

	/**
	 * See if WP_DEBUG_DISPLAY is true.
	 */
	isDebugDisplayEnabled() {
		const states = this;
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'WP_DEBUG_DISPLAY',
			},
		} ).then( function( response ) {
			if ( true === response.data.success ) {
				states.setState( { debugDisplayEnabled: true } );
			}
		} );
	}

	/**
	 * See if SCRIPT_DEBUG is true.
	 */
	isScriptDebugEnabled() {
		const states = this;
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'SCRIPT_DEBUG',
			},
		} ).then( function( response ) {
			if ( true === response.data.success ) {
				states.setState( { scriptDebugEnabled: true } );
			}
		} );
	}

	/**
	 * See if SAVEQUERIES is true.
	 */
	isSaveQueriesEnabled() {
		const states = this;
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'SAVEQUERIES',
			},
		} ).then( function( response ) {
			if ( true === response.data.success ) {
				states.setState( { saveQueriesEnabled: true } );
			}
		} );
	}

	componentDidMount() {
		// fetch the initial debug information.
		this.isDebugEnabled();
		this.isDebugLogEnabled();
		this.isDebugDisplayEnabled();
		this.isScriptDebugEnabled();
		this.isSaveQueriesEnabled();
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
