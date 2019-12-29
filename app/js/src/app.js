/**
 * WordPress dependencies.
 */
import { useState } from '@wordpress/element';

/**
 * Internal Dependencies.
 */
import Header from './components/Header';
import Content from './components/Content';

/**
 * Main.
 */
const App = () => {
	/**
	 * Since we're in an arrow function and using useState(),
	 * altering the state will force a re-render making all of the
	 * initial functions needed to re-run. To avoid this
	 * we add an extra state keeping a "firstRun" to avoid unwanted
	 * looping & re-runs of functions.
	 */
	const [ firstRun, setfirstRun ] = useState( true );

	// Initialize the debug states.
	const [ hasWPDebug, setWPDebug ] = useState( false );
	const [ hasWPDebugLog, setWPDebugLog ] = useState( false );
	const [ hasWPDebugDisplay, setWPDebugDisplay ] = useState( false );
	const [ hasScriptDebug, setScriptDebug ] = useState( false );
	const [ hasSaveQueries, setSaveQueries ] = useState( false );

	// Initialize the backup state.
	const [ hasBackup, setHasBackup ] = useState( false );

	// Initialize the auto refresh state.
	const [ hasAutoRefresh, setAutoRefresh ] = useState( false );

	// Initialize a state for the loading spinner.
	const [ loading, setLoading ] = useState( 'show-spinner' );

	// Initialize the debug.log location state.
	const [ debugLogLocation, setDebugLogLocation ] = useState( '' );

	// Initialize the debug.log content state.
	const [ deubgLogContent, setDebugLogContent ] = useState( '' );

	/**
	 * Check if wp-config.WPLD-auto.php exists.
	 */
	const autoBackupExists = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-check-auto-backup-json';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				// silence.
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Check if wp-config.WPLD-manual.php exists.
	 */
	const manualBackupExists = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-check-manual-backup-json';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					setHasBackup( true );
				}
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * See any of the constants are true and alter their state.
	 */
	const isConstantTrue = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-is-constant-true';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					resp.data.map( ( constant ) => {
						switch ( constant ) {
							case 'WP_DEBUG':
								setWPDebug( true );
								break;
							case 'WP_DEBUG_LOG':
								setWPDebugLog( true );
								break;
							case 'WP_DEBUG_DISPLAY':
								setWPDebugDisplay( true );
								break;
							case 'SCRIPT_DEBUG':
								setScriptDebug( true );
								break;
							case 'SAVEQUERIES':
								setSaveQueries( true );
								break;
						}
					} );
					setLoading( 'hide-spinner' );
				}
			}
		};

		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};


	/**
	 * Find debug.log location.
	 */
	const findDebugLog = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-find-debug-log-json';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				setDebugLogLocation( resp.data.debuglog_path );
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Read the debug.log.
	 */
	const readDebugLog = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-read-debug-log';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				setDebugLogContent( this.response );
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Backup Button Actions.
	 *
	 * @param {Object} e string Event handler.
	 */
	const BackupActions = ( e ) => {
		// Show the spinner.
		setLoading( 'show-spinner' );

		// If we're getting a backup.
		if ( e.target.id === 'wp-live-debug-backup' ) {
			const request = new XMLHttpRequest();
			const url = wp_live_debug_globals.ajax_url;
			const nonce = wp_live_debug_globals.nonce;
			const action = 'wp-live-debug-create-backup';

			request.open( 'POST', url, true );
			request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
			request.onload = function() {
				if ( this.status >= 200 && this.status < 400 ) {
					const resp = JSON.parse( this.response );
					if ( true === resp.success ) {
						setHasBackup( true );
						setLoading( 'hide-spinner' );
					}
				}
			};
			request.send( 'action=' + action + '&_ajax_nonce=' + nonce );

		// Else restore the backup.
		} else {
			const request = new XMLHttpRequest();
			const url = wp_live_debug_globals.ajax_url;
			const nonce = wp_live_debug_globals.nonce;
			const action = 'wp-live-debug-restore-backup';

			request.open( 'POST', url, true );
			request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
			request.onload = function() {
				if ( this.status >= 200 && this.status < 400 ) {
					const resp = JSON.parse( this.response );
					if ( true === resp.success ) {
						setHasBackup( false );
						setLoading( 'hide-spinner' );
					}
				}
			};
			request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
		}
	};

	/**
	 * Alter WP_DEBUG
	 */
	const alterWPDebug = () => {
		console.log( 'alterWPDebug' );
	};

	/**
	 * Alter WP_DEBUG_LOG
	 */
	const alterWPDebugLog = () => {
		console.log( 'alterWPDebugLog' );
	};

	/**
	 * Alter WP_DEBUG_DISPLAY
	 */
	const alterWPDebugDisplay = () => {
		console.log( 'alterWPDebugDisplay' );
	};

	/**
	 * Alter SCRIPT_DEBUG
	 */

	const alterScriptDebug = () => {
		console.log( 'alterScriptDebug' );
	};

	/**
	 * Alter SAVEQUERIES
	 */
	const alterSaveQueries = () => {
		console.log( 'alterSaveQueries' );
	};

	/**
	 * Alter Auto Refresh
	 */
	const alterAutoRefresh = () => {
		console.log( 'alterAutoRefresh' );
	};

	/**
	 * Now we utilize the "firstRun" state so we
	 * can run our 1time functions and then set it
	 * to false so this won't run again until a page refresh.
	 */
	if ( firstRun ) {
		autoBackupExists();
		manualBackupExists();
		findDebugLog();
		isConstantTrue();
		readDebugLog();
		setfirstRun( false );
	}

	/**
	 * Render the UI.
	 */
	return (
		<>
			<Header
				BackupActions={ BackupActions }
				hasBackup={ hasBackup }
			/>
			<Content
				loading={ loading }
				alterWPDebug={ alterWPDebug }
				alterWPDebugLog={ alterWPDebugLog }
				alterWPDebugDisplay={ alterWPDebugDisplay }
				alterScriptDebug={ alterScriptDebug }
				alterSaveQueries={ alterSaveQueries }
				alterAutoRefresh={ alterAutoRefresh }
				hasBackup={ hasBackup }
				debugEnabled={ hasWPDebug }
				debugLogLocation={ debugLogLocation }
				debugLogEnabled={ hasWPDebugLog }
				deubgLogContent={ deubgLogContent }
				debugDisplayEnabled={ hasWPDebugDisplay }
				scriptDebugEnabled={ hasScriptDebug }
				saveQueriesEnabled={ hasSaveQueries }
				autoRefreshEnabled={ hasAutoRefresh }
			/>
		</>
	);
};

export default App;
