(function( $ ) {
	var responseHolder         = $( '#wp-debug-response-holder' ),
		refreshToggle          = $( '#toggle-auto-refresh' ),
		debugArea              = $( '#wp-live-debug-area' ),
		refreshData            = { 'action': 'wp-live-debug-read-log' },
		clearButton            = $( '#wp-live-debug-clear' ),
		clearData              = { 'action': 'wp-live-debug-clear-debug-log' },
		backupButton           = $( '#wp-live-debug-backup' ),
		createBackupData       = { 'action': 'wp-live-debug-create-backup' },
		restoreButton          = $( '#wp-live-debug-restore' ),
		restoreBackupData      = { 'action': 'wp-live-debug-restore-backup' },
		wpDebugToggle          = $( '#toggle-wp-debug' ),
		enableWPDebugData      = { 'action': 'wp-live-debug-enable' },
		disableWPDebugData     = { 'action': 'wp-live-debug-disable' },
		scriptDebugToggle      = $( '#toggle-script-debug' ),
		enableScriptDebugData  = { 'action': 'wp-live-debug-enable-script-debug' },
		disableScriptDebugData = { 'action': 'wp-live-debug-disable-script-debug' },
		savequeriesToggle      = $( '#toggle-savequeries' ),
		enableSavequeriesData  = { 'action': 'wp-live-debug-enable-savequeries' },
		disableSavequeriesData = { 'action': 'wp-live-debug-disable-savequeries' };

	if ( debugArea.length ) {
		// Scroll the textarea to bottom.
		function scrollDebugAreaToBottom() {
			debugArea.scrollTop( debugArea[0].scrollHeight );
		}
		// Make the initial debug.log read.
		$.post( ajaxurl, refreshData, function( response ) {
			debugArea.html( response );
			scrollDebugAreaToBottom();
		} );
		// Enable / Disable Auto Scroll
		setInterval( function() {
			var checked = refreshToggle.is( ':checked');
			if ( checked ) {
				$.post( ajaxurl, refreshData, function( response ) {
					debugArea.html( response );
					scrollDebugAreaToBottom();
				} );
			}
		}, 2000 );
		// Handle the clear button clicks.
		clearButton.on( 'click', function( e ) {
			e.preventDefault();
			$.post( ajaxurl, clearData, function( response ) {
				debugArea.html( response );
				scrollDebugAreaToBottom();
			} );
		} );
		// Create wp-config backup
		backupButton.on( 'click', function( e ) {
			e.preventDefault();
			$.post( ajaxurl, createBackupData, function( response ) {
				if ( response.success ) {
					window.location.href = window.location.href;
				} else if ( response.error ) {
					responseHolder.html( response.data.message );
				}
			});
		});
		// Restore wp-config backup
		restoreButton.on( 'click', function( e ) {
			e.preventDefault();
			$.post( ajaxurl, restoreBackupData, function( response ) {
				if ( response.success ) {
					window.location.href = window.location.href;
				} else if ( response.error ) {
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
	}
} )( jQuery )
