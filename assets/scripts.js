(function( $ ) {
	// TODO: Backup Button, Clear Button, Auto Scroll Toggles.
	var doScroll               = $( '#wp-live-debug-scroll' ),
		responseHolder         = $( '#wp-debug-response-holder' ),
		debugLiveButton        = $( '#wp-live-debug-start-stop #ss-button' ),
		debugArea              = $( '#wp-live-debug-area' ),
		refreshData            = { 'action': 'wp-live-debug-read-log' },
		debugClearButton       = $( '#wp-live-debug-clear-wp-debug' ),
		clearData              = { 'action': 'wp-live-debug-clear-debug-log' },
		restoreBackupForm      = $( '#wp-live-debug-restore-wp-debug-backup' ),
		restoreBackupData      = { 'action': 'wp-live-debug-restore-backup' },
		createBackupForm       = $( '#wp-live-debug-create-wp-debug-backup' ),
		createBackupData       = { 'action': 'wp-live-debug-create-backup' },
		wpDebugToggle          = $( '#toggle-wp-debug' ),
		enableWPDebugData      = { 'action': 'wp-live-debug-enable' },
		disableWPDebugData     = { 'action': 'wp-live-debug-disable' },
		scriptDebugToggle      = $( '#toggle-script-debug' ),
		enableScriptDebugData  = { 'action': 'wp-live-debug-enable-script-debug' },
		disableScriptDebugData = { 'action': 'wp-live-debug-disable-script-debug' },
		savequeriesToggle      = $( '#toggle-savequeries' ),
		enableSavequeriesData  = { 'action': 'wp-live-debug-enable-savequeries' },
		disableSavequeriesData = { 'action': 'wp-live-debug-disable-savequeries' };

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
	// Enable / Disable WP DEDUG
	wpDebugToggle.on( 'change', function( e ) {
		e.preventDefault();
		var checked = $(this).is( ':checked');
		if ( checked ) {
			$.post( ajaxurl, enableWPDebugData, function( response ) {
				if (response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		} else if ( ! checked ) {
			$.post( ajaxurl, disableWPDebugData, function( response ) {
				if (response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		}
	});
	// Enable / Disable SCRIPT DEDUG
	scriptDebugToggle.on( 'change', function( e ) {
		e.preventDefault();
		var checked = $(this).is( ':checked');
		if ( checked ) {
			$.post( ajaxurl, enableScriptDebugData, function( response ) {
				if ( response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		} else if ( ! checked ) {
			$.post( ajaxurl, disableScriptDebugData, function( response ) {
				if ( response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		}
	});
	// Enable / Disable SAVEQUERIES
	savequeriesToggle.on( 'change', function( e ) {
		e.preventDefault();
		var checked = $(this).is( ':checked');
		if ( checked ) {
			$.post( ajaxurl, enableSavequeriesData, function( response ) {
				if ( response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		} else if ( ! checked ) {
			$.post( ajaxurl, disableSavequeriesData, function( response ) {
				if ( response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		}
	});
} )( jQuery )
