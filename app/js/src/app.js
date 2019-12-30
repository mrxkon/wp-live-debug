/**
 * WordPress dependencies.
 */
import { useState, useEffect } from '@wordpress/element';

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

	// Initialize the backups state.
	const [ hasManualBackup, setHasManualBackup ] = useState( false );
	const [ hasAutoBackup, setHasAutoBackup ] = useState( false );

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
				setHasAutoBackup( true );
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
					setHasManualBackup( true );
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
					resp.data.forEach( ( constant ) => {
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
	 * Scroll the LogViewer.
	 */
	const scrollLogViewer = () => {
		const debugArea = document.getElementById( 'wp-live-debug-area' );
		if ( null !== debugArea ) {
			debugArea.scrollTop = debugArea.scrollHeight;
		}
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
				if ( firstRun ) {
					scrollLogViewer();
				}
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Scroll the LogViewer on interval.
	 */
	useEffect( () => {
		const interval = setInterval( () => {
			if ( true === hasAutoRefresh ) {
				readDebugLog();
				scrollLogViewer();
			}
		}, 3000 );

		return () => clearInterval( interval );
	} );

	/**
	 * Backup Button Actions.
	 *
	 * @param {Object} e string.
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
						setHasManualBackup( true );
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
						setHasManualBackup( false );
						setLoading( 'hide-spinner' );
					}
				}
			};
			request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
		}
	};

	/**
	 * Alter Constatns.
	 *
	 * @param {Object} e string.
	 */
	const alterConstant = ( e ) => {
		// Show the spinner.
		setLoading( 'show-spinner' );

		const target = e.target.id;

		let value = 'false';

		switch ( target ) {
			case 'WP_DEBUG':
				value = hasWPDebug ? false : true;
				break;
			case 'WP_DEBUG_LOG':
				value = hasWPDebugLog ? false : debugLogLocation;
				break;
			case 'WP_DEBUG_DISPLAY':
				value = hasWPDebugDisplay ? false : true;
				break;
			case 'SCRIPT_DEBUG':
				value = hasScriptDebug ? false : true;
				break;
			case 'SAVEQUERIES':
				value = hasSaveQueries ? false : true;
				break;
		}

		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-alter-constant';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					switch ( target ) {
						case 'WP_DEBUG':
							setWPDebug( value );
							break;
						case 'WP_DEBUG_LOG':
							if ( false !== value ) {
								value = true;
							}
							setWPDebugLog( value );
							break;
						case 'WP_DEBUG_DISPLAY':
							setWPDebugDisplay( value );
							break;
						case 'SCRIPT_DEBUG':
							setScriptDebug( value );
							break;
						case 'SAVEQUERIES':
							setSaveQueries( value );
							break;
					}

					setLoading( 'hide-spinner' );
				}
			}
		};

		request.send( 'action=' + action + '&_ajax_nonce=' + nonce + '&constant=' + target + '&value=' + value );
	};

	/**
	 * Alter Auto Refresh
	 */
	const alterAutoRefresh = () => {
		setLoading( 'show-spinner' );
		if ( false === hasAutoRefresh ) {
			setAutoRefresh( true );
		} else {
			setAutoRefresh( false );
		}
		setLoading( 'hide-spinner' );
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
				hasManualBackup={ hasManualBackup }
			/>
			<Content
				loading={ loading }
				alterConstant={ alterConstant }
				alterAutoRefresh={ alterAutoRefresh }
				hasManualBackup={ hasManualBackup }
				hasAutoBackup={ hasAutoBackup }
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
