/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { useState } from '@wordpress/element';
import axios from 'axios';

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

	/**
	 * Check if wp-config.WPLD-auto.php exists.
	 */
	const autoBackupExists = () => {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-check-auto-backup-json',
				_ajax_nonce: wp_live_debug_globals.nonce,
			},
		} ).then( ( response ) => {
			if ( false === response.data.success ) {
				console.log( 'Could not create an auto backup' );
			}
		} );
	};

	/**
	 * Find debug.log location.
	 */
	const findDebugLog = () => {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-find-debug-log-json',
				_ajax_nonce: wp_live_debug_globals.nonce,
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				setDebugLogLocation( response.data.data.debuglog_path );
			}
		} );
	};

	/**
	 * Check if wp-config.WPLD-manual.php exists.
	 */
	const manualBackupExists = () => {
		axios( {
			method: 'post',
			url: wp_live_debug_globals.ajax_url,
			params: {
				action: 'wp-live-debug-check-manual-backup-json',
				_ajax_nonce: wp_live_debug_globals.nonce,
			},
		} ).then( ( response ) => {
			if ( true === response.data.success ) {
				// If there's a alter the hasBackup state.
				setHasBackup( true );
			}
		} );
	};

	/**
	 * See any of the constants are true and alter their state.
	 */
	const isConstantTrue = () => {
		const constants = [ 'WP_DEBUG', 'WP_DEBUG_LOG', 'WP_DEBUG_DISPLAY', 'SCRIPT_DEBUG', 'SAVEQUERIES' ];

		constants.map( ( constant ) =>
			axios( {
				method: 'post',
				url: wp_live_debug_globals.ajax_url,
				params: {
					action: 'wp-live-debug-is-constant-true',
					_ajax_nonce: wp_live_debug_globals.nonce,
					constant,
				},
			} ).then( ( response ) => {
				if ( true === response.data.success ) {
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

					setLoading( 'hide-spinner' );
				}
			} ),
		);
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
		setfirstRun( false );
	}

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
			axios( {
				method: 'post',
				url: wp_live_debug_globals.ajax_url,
				params: {
					action: 'wp-live-debug-create-backup',
					_ajax_nonce: wp_live_debug_globals.nonce,
				},
			} ).then( ( response ) => {
				if ( true === response.data.success ) {
					// Set the state of backup to true.
					setHasBackup( true );
				}
				// Hide the spinner.
				setLoading( 'hide-spinner' );
			} );

		// Else restore the backup.
		} else {
			axios( {
				method: 'post',
				url: wp_live_debug_globals.ajax_url,
				params: {
					action: 'wp-live-debug-restore-backup',
					_ajax_nonce: wp_live_debug_globals.nonce,
				},
			} ).then( ( response ) => {
				if ( true === response.data.success ) {
					// Set the state of backup to false.
					setHasBackup( false );
				}
				// Hide the spinner.
				setLoading( 'hide-spinner' );
			} );
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
				debugLogEnabled={ hasWPDebugLog }
				debugDisplayEnabled={ hasWPDebugDisplay }
				scriptDebugEnabled={ hasScriptDebug }
				saveQueriesEnabled={ hasSaveQueries }
				autoRefreshEnabled={ hasAutoRefresh }
			/>
		</>
	);
};

export default App;
