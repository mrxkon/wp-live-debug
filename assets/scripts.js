( function( $ ) {
	var safetyPopup            = $( '#safety-popup' ),
		acceptRisk             = $( '#riskaccept' ),
		acceptRiskData         = { 'action': 'wp-live-debug-accept-risk' },
		responseHolder         = $( '#wp-debug-response-holder' ),
		refreshToggle          = $( '#toggle-auto-refresh' ),
		debugArea              = $( '#wp-live-debug-area' ),
		refreshData            = { 'action': 'wp-live-debug-read-log' },
		clearButton            = $( '#wp-live-debug-clear' ),
		deleteButton           = $( '#wp-live-debug-delete' ),
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
		disableSavequeriesData = { 'action': 'wp-live-debug-disable-savequeries' },
		selectLog              = $( '#log-list' );

	// Select different log
	selectLog.on( 'change', function( e ) {
		var log = $( this ).val(),
			nonce = $( this ).find(':selected').data( 'nonce' ),
			data;
		e.preventDefault();
		data = {
			'action': 'wp-live-debug-select-log',
			'log': log,
			'nonce': nonce
		};
		$.post( ajaxurl, data, function( response ) {
			if ( response.success ) {
				window.location.href = window.location.href;
			}
		} );
	} );
	// Scroll the textarea to bottom.
	function scrollDebugAreaToBottom() {
		debugArea.scrollTop( debugArea[0].scrollHeight );
	}
	// Refresh toggle setup
	refreshToggle.on( 'click', function( e ) {
		var checked = $( this ).is( ':checked' ),
			data;
		data = {
			'action': 'wp-live-debug-refresh-debug-log',
			'checked': checked
		};
		$.post( ajaxurl, data, function( response ) {
			if ( response.success ) {
				// silence
			}
		} );
	} );
	// Debug View
	if ( debugArea.length ) {
		// Make the initial debug.log read.
		$.post( ajaxurl, refreshData, function( response ) {
			console.log('hey');
			debugArea.html( response );
			scrollDebugAreaToBottom();
		} );
		// Enable / Disable Auto Scroll
		setInterval( function() {
			var checked = refreshToggle.is( ':checked' );
			if ( checked ) {
				$.post( ajaxurl, refreshData, function( response ) {
					debugArea.html( response );
					scrollDebugAreaToBottom();
				} );
			}
		}, 2000 );
		// Handle the clear button clicks.
		clearButton.on( 'click', function( e ) {
			var nonce = $( this ).data( 'nonce' ),
				log   = $( this ).data( 'log' ),
				data;
			e.preventDefault();
			$( this ).find( '.sui-icon-loader' ).css( 'display', 'inline-block' );
			data = {
				'action': 'wp-live-debug-clear-debug-log',
				'log': log,
				'nonce': nonce
			};
			$.post( ajaxurl, data, function( response ) {
				if ( response.success ) {
					$( clearButton ).find( '.sui-icon-loader' ).css( 'display', 'none' );
					scrollDebugAreaToBottom();
				}
			} );
		} );
		// Handle the delete button clicks.
		deleteButton.on( 'click', function( e ) {
			var nonce = $( this ).data( 'nonce' ),
				log   = $( this ).data( 'log' ),
				data;
			e.preventDefault();
			$( this ).find( '.sui-icon-loader' ).css( 'display', 'inline-block' );
			data = {
				'action': 'wp-live-debug-delete-debug-log',
				'log': log,
				'nonce': nonce
			}
			$.post( ajaxurl, data, function( response ) {
				if ( response.success ) {
					window.location.href = window.location.href;
				}
			} );
		} );
		// Create wp-config backup
		backupButton.on( 'click', function( e ) {
			e.preventDefault();
			$( this ).find( '.sui-icon-loader' ).css( 'display', 'inline-block' );
			$.post( ajaxurl, createBackupData, function( response ) {
				if ( response.success ) {
					window.location.href = window.location.href;
				} else {
					responseHolder.html( response.data.message );
				}
			} );
		} );
		// Restore wp-config backup
		restoreButton.on( 'click', function( e ) {
			e.preventDefault();
			$( this ).find( '.sui-icon-loader' ).css( 'display', 'inline-block' );
			$.post( ajaxurl, restoreBackupData, function( response ) {
				if ( response.success ) {
					window.location.href = window.location.href;
				} else {
					responseHolder.html( response.data.message );
				}
			} );
		} );
		// Enable / Disable WP DEDUG
		wpDebugToggle.on( 'change', function( e ) {
			e.preventDefault();
			var checked = $(this).is( ':checked');
			backupButton.prop( 'disabled', true );
			restoreButton.prop( 'disabled', true );
			wpDebugToggle.prop( 'disabled', true );
			scriptDebugToggle.prop( 'disabled', true );
			savequeriesToggle.prop( 'disabled', true );
			if ( checked ) {
				$.post( ajaxurl, enableWPDebugData, function( response ) {
					if ( response.success ) {
						backupButton.prop( 'disabled', false );
						restoreButton.prop( 'disabled', false );
						wpDebugToggle.prop( 'disabled', false );
						scriptDebugToggle.prop( 'disabled', false );
						savequeriesToggle.prop( 'disabled', false );
					}
				} );
			} else if ( ! checked ) {
				$.post( ajaxurl, disableWPDebugData, function( response ) {
					if ( response.success ) {
						backupButton.prop( 'disabled', false );
						restoreButton.prop( 'disabled', false );
						wpDebugToggle.prop( 'disabled', false );
						scriptDebugToggle.prop( 'disabled', false );
						savequeriesToggle.prop( 'disabled', false );
					}
				} );
			}
		} );
		// Enable / Disable SCRIPT DEDUG
		scriptDebugToggle.on( 'change', function( e ) {
			e.preventDefault();
			var checked = $(this).is( ':checked');
			backupButton.prop( 'disabled', true );
			restoreButton.prop( 'disabled', true );
			wpDebugToggle.prop( 'disabled', true );
			scriptDebugToggle.prop( 'disabled', true );
			savequeriesToggle.prop( 'disabled', true );
			if ( checked ) {
				$.post( ajaxurl, enableScriptDebugData, function( response ) {
					if ( response.success ) {
						backupButton.prop( 'disabled', false );
						restoreButton.prop( 'disabled', false );
						wpDebugToggle.prop( 'disabled', false );
						scriptDebugToggle.prop( 'disabled', false );
						savequeriesToggle.prop( 'disabled', false );
					}
				} );
			} else if ( ! checked ) {
				$.post( ajaxurl, disableScriptDebugData, function( response ) {
					if ( response.success ) {
						backupButton.prop( 'disabled', false );
						restoreButton.prop( 'disabled', false );
						wpDebugToggle.prop( 'disabled', false );
						scriptDebugToggle.prop( 'disabled', false );
						savequeriesToggle.prop( 'disabled', false );
					}
				} );
			}
		} );
		// Enable / Disable SAVEQUERIES
		savequeriesToggle.on( 'change', function( e ) {
			e.preventDefault();
			var checked = $(this).is( ':checked');
			backupButton.prop( 'disabled', true );
			restoreButton.prop( 'disabled', true );
			wpDebugToggle.prop( 'disabled', true );
			scriptDebugToggle.prop( 'disabled', true );
			savequeriesToggle.prop( 'disabled', true );
			if ( checked ) {
				$.post( ajaxurl, enableSavequeriesData, function( response ) {
					if ( response.success ) {
						backupButton.prop( 'disabled', false );
						restoreButton.prop( 'disabled', false );
						wpDebugToggle.prop( 'disabled', false );
						scriptDebugToggle.prop( 'disabled', false );
						savequeriesToggle.prop( 'disabled', false );
					}
				} );
			} else if ( ! checked ) {
				$.post( ajaxurl, disableSavequeriesData, function( response ) {
					if ( response.success ) {
						backupButton.prop( 'disabled', false );
						restoreButton.prop( 'disabled', false );
						wpDebugToggle.prop( 'disabled', false );
						scriptDebugToggle.prop( 'disabled', false );
						savequeriesToggle.prop( 'disabled', false );
					}
				} );
			}
		} );
	}
	// Safety Dialog
	if ( safetyPopup.length ) {
		const sp = document.getElementById( 'safety-popup' );
		const safety = new A11yDialog( sp );
		setTimeout( function() {
			safety.show();
		}, 300 );
		acceptRisk.on( 'click', function( e ) {
			e.preventDefault();
			safety.hide();
			$.post( ajaxurl, acceptRiskData, function( response ) {
				if ( ! response.success ) {
					responseHolder.html( response.data.message );
				}
			} );
		} );
	}
} )( jQuery );
