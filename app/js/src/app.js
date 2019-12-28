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
	constructor( props ) {
		super( props );

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
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'WP_DEBUG',
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				this.setState( function() {
					return {
						debugEnabled: true,
					};
				} );
			}
		} );
	}

	/**
	 * See if WP_DEBUG_LOG is true.
	 */
	isDebugLogEnabled() {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'WP_DEBUG_LOG',
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				this.setState( function() {
					return {
						debugLogEnabled: true,
					};
				} );
			}
		} );
	}

	/**
	 * See if WP_DEBUG_DISPLAY is true.
	 */
	isDebugDisplayEnabled() {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'WP_DEBUG_DISPLAY',
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				this.setState( function() {
					return {
						debugDisplayEnabled: true,
					};
				} );
			}
		} );
	}

	/**
	 * See if SCRIPT_DEBUG is true.
	 */
	isScriptDebugEnabled() {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'SCRIPT_DEBUG',
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				this.setState( function() {
					return {
						scriptDebugEnabled: true,
					};
				} );
			}
		} );
	}

	/**
	 * See if SAVEQUERIES is true.
	 */
	isSaveQueriesEnabled() {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-is-constant-true',
				_ajax_nonce: wp_live_debug_globals.nonce,
				constant: 'SAVEQUERIES',
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				this.setState( function() {
					return {
						saveQueriesEnabled: true,
					};
				} );
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
