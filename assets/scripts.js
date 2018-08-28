(function( $ ) {
	var doScroll                  = $( '#wp-live-debug-scroll' ),
		responseHolder            = $( '#wp-debug-response-holder' ),
		debugLiveButton           = $( '#wp-live-debug-start-stop #ss-button' ),
		debugArea                 = $( '#wp-live-debug-area' ),
		refreshData               = { 'action': 'wp-live-debug-read-log' },
		debugClearButton          = $( '#wp-live-debug-clear-wp-debug' ),
		clearData                 = { 'action': 'wp-live-debug-clear-debug-log' },
		restoreBackupForm         = $( '#wp-live-debug-restore-wp-debug-backup' ),
		restoreBackupData         = { 'action': 'wp-live-debug-restore-backup' },
		createBackupForm          = $( '#wp-live-debug-create-wp-debug-backup' ),
		createBackupData          = { 'action': 'wp-live-debug-create-backup' },
		enableWPDebugForm         = $( '#wp-live-debug-enable' ),
		enableWPDebugData         = { 'action': 'wp-live-debug-enable' },
		disableWPDebugForm        = $( '#wp-live-debug-disable' ),
		disableWPDebugData        = { 'action': 'wp-live-debug-disable' },
		enableScriptDebugForm     = $( '#wp-live-debug-enable-script-debug' ),
		enableScriptDebugData     = { 'action': 'wp-live-debug-enable-script-debug' },
		disableScriptDebugForm    = $( '#wp-live-debug-disable-script-debug' ),
		disableScriptDebugData    = { 'action': 'wp-live-debug-disable-script-debug' },
		enableSavequeriesForm     = $( '#wp-live-debug-enable-savequeries' ),
		enableSavequeriesData     = { 'action': 'wp-live-debug-enable-savequeries' },
		disableSavequeriesForm    = $( '#wp-live-debug-disable-savequeries' ),
		disableSavequeriesData    = { 'action': 'wp-live-debug-disable-savequeries' };

	// Scroll the textarea to bottom.
	function scrollDebugAreaToBottom() {
		debugArea.scrollTop( debugArea[0].scrollHeight );
	}
	// Make the initial ajax call.
	$.post( ajaxurl, refreshData, function( response ) {
		debugArea.html( response );
		scrollDebugAreaToBottom();
	} );
	// Make the ajax calls every 3 seconds if enabled.
	setInterval( function() {
		if ( doScroll.val() === 'yes' ) {
			$.post( ajaxurl, refreshData, function( response ) {
				debugArea.html( response );
				scrollDebugAreaToBottom();
			} );
		}
	}, 2000 );
	// Handle the pause button clicks.
	debugLiveButton.on( 'click', function() {
		if ( doScroll.val() === 'yes' ) {
			doScroll.val( 'no' );
			debugLiveButton.val( 'Start auto refresh' );
		} else {
			doScroll.val( 'yes' );
			debugLiveButton.val( 'Stop auto refresh' );
		}
	} );
	// Handle the clear button clicks.
	debugClearButton.on( 'submit', function() {
		$.post( ajaxurl, clearData, function( response ) {
			debugArea.html( response );
			scrollDebugAreaToBottom();
		} );
	} );
	// Create wp-config backup
	createBackupForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, createBackupData, function( response ) {
			if ( response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Restore wp-config backup
	restoreBackupForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, restoreBackupData, function( response ) {
			if ( response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Enable WP DEDUG
	enableWPDebugForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, enableWPDebugData, function( response ) {
			if ( response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Disable WP DEBUG
	disableWPDebugForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, disableWPDebugData, function( response ) {
			if (response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Enable SCRIPT DEDUG
	enableScriptDebugForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, enableScriptDebugData, function( response ) {
			if ( response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Disable SCRIPT DEBUG
	disableScriptDebugForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, disableScriptDebugData, function( response ) {
			if (response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Enable SAVEQUERIES
	enableSavequeriesForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, enableSavequeriesData, function( response ) {
			if ( response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
	// Disable SAVEQUERIES
	disableSavequeriesForm.submit( function( e ) {
		e.preventDefault();
		$.post( ajaxurl, disableSavequeriesData, function( response ) {
			if (response.success ) {
				window.location.href = window.location.href;
			} else if ( 'error' === response.data.status ) {
				responseHolder.html( response.data.message );
			}
		});
	});
} )( jQuery )
