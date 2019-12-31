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
	 * Initialize states.
	 */
	const [ firstRun, setfirstRun ] = useState( true );
	const [ hasWPDebug, setWPDebug ] = useState( false );
	const [ hasWPDebugLog, setWPDebugLog ] = useState( false );
	const [ hasWPDebugDisplay, setWPDebugDisplay ] = useState( false );
	const [ hasScriptDebug, setScriptDebug ] = useState( false );
	const [ hasSaveQueries, setSaveQueries ] = useState( false );
	const [ hasManualBackup, setHasManualBackup ] = useState( false );
	const [ hasAutoBackup, setHasAutoBackup ] = useState( false );
	const [ hasAutoRefresh, setAutoRefresh ] = useState( false );
	const [ loading, setLoading ] = useState( 'show-spinner' );
	const [ debugLogLocation, setDebugLogLocation ] = useState( '' );

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
				const debugArea = document.getElementById( 'wp-live-debug-area' );
				if ( null !== debugArea ) {
					debugArea.value = this.response;
				}
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
	 * Clear the log.
	 */
	const clearLog = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-clear-debug-log';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					// silence
				}
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Delete the log.
	 */
	const deleteLog = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-delete-debug-log';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					// silence
				}
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Backup Button Actions.
	 *
	 * @param {Object} event
	 */
	const BackupActions = ( event ) => {
		setLoading( 'show-spinner' );

		const target = event.target.id;

		if ( target === 'wp-live-debug-backup' ) {
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
						window.location.reload();
					}
				}
			};
			request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
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
						window.location.reload();
					}
				}
			};
			request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
		}
	};

	/**
	 * Alter Constants.
	 *
	 * @param {Object} event
	 */
	const alterConstant = ( event ) => {
		setLoading( 'show-spinner' );

		const target = event.target.id;

		let value;

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
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-alter-auto-refresh';

		let value;

		if ( false === hasAutoRefresh ) {
			value = 'enabled';
			setAutoRefresh( true );
		} else {
			value = 'disabled';
			setAutoRefresh( false );
		}

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					setLoading( 'hide-spinner' );
				}
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce + '&value=' + value );
	};

	/**
	 * Find if Auto Refresh is enabled.
	 */
	const isAutoRefresh = () => {
		const request = new XMLHttpRequest();
		const url = wp_live_debug_globals.ajax_url;
		const nonce = wp_live_debug_globals.nonce;
		const action = 'wp-live-debug-auto-refresh-is';

		request.open( 'POST', url, true );
		request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		request.onload = function() {
			if ( this.status >= 200 && this.status < 400 ) {
				const resp = JSON.parse( this.response );
				if ( true === resp.success ) {
					if ( 'disabled' === resp.data ) {
						setAutoRefresh( false );
					} else if ( 'enabled' === resp.data ) {
						setAutoRefresh( true );
					}
				}
			}
		};
		request.send( 'action=' + action + '&_ajax_nonce=' + nonce );
	};

	/**
	 * Run these only one time.
	 */
	if ( firstRun ) {
		autoBackupExists();
		manualBackupExists();
		findDebugLog();
		isConstantTrue();
		isAutoRefresh();
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
				debugDisplayEnabled={ hasWPDebugDisplay }
				scriptDebugEnabled={ hasScriptDebug }
				saveQueriesEnabled={ hasSaveQueries }
				autoRefreshEnabled={ hasAutoRefresh }
				clearLog={ clearLog }
				deleteLog={ deleteLog }
			/>
		</>
	);
};

export default App;
