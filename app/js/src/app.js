/**
 * WordPress dependencies.
 */
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
	 * Since we're not in a Class and using useState(),
	 * altering the state will force a re-render making the Axios
	 * run on a loop due to altering the states as well.
	 * We add an extra state keeping a "firstRun" to avoid looping.
	 */
	const [ firstRun, setfirstRun ] = useState( true );

	// Initialize the debug states.
	const [ hasWPDebug, setWPDebug ] = useState( false );
	const [ hasWPDebugLog, setWPDebugLog ] = useState( false );
	const [ hasWPDebugDisplay, setWPDebugDisplay ] = useState( false );
	const [ hasScriptDebug, setScriptDebug ] = useState( false );
	const [ hasSaveQueries, setSaveQueries ] = useState( false );

	// Initialize the backup state.
	const [ hasBackup, setHasBackup ] = useState( true );

	// Initialize the auto refresh state.
	const [ hasAutoRefresh, setAutoRefresh ] = useState( false );

	/**
	 * Check if backup exists.
	 */

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
		isConstantTrue();
		setfirstRun( false );
	}

	/**
	 * Backup Button Actions.
	 *
	 * @param {Object} e string Event handler.
	 */
	const BackupActions = ( e ) => {
		if ( e.target.id === 'wp-live-debug-backup' ) {
			setHasBackup( true );
		} else {
			setHasBackup( false );
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
