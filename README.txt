=== WP Live Debug ===
Contributors: xkon
Tags: debug
Requires at least: 5
Tested up to: 5.3.2
Stable tag: 5.3.2
Requires PHP: 7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables debugging and adds various installation information.

== Description ==

I've found myself needing to have a "live view" of the debug.log in many occasions when dealing with code remotely. It's useful for debugging in general or quickly checking what's the latest in the logs if there's no server-side access.

This plugin allows you to control the debug related constants and have a debug.log view inside your wp-admin area.

Credits & Licences:
This plugin utilizes `@wordpress/components`, `@wordpress/element`, `@wordpress/i18n` and other packages.

Props to all the contributors that made this possible :) .

== Installation ==

**From within WordPress**

* Visit Plugins > Add New
* Search for WP Live Debug
* Click the Install Now button to install the plugin
* Click the Activate button to activate the plugin

**Manually**

* Unzip the downloaded package
* Upload `wp-live-debug` directory to the /wp-content/plugins/ directory
* Activate the plugin through the ‘Plugins’ menu in WordPress

== Screenshots ==

1. WP Live Debug

== Changelog ==

= 5.3.2 =
* Refactored PHP.
* Refactored JS.
* Convert to React.
* Utilize @wordpress packages.
* Refurbish the UI.

= 4.9.8.6 =
* Log viewer UI changes.
* Add full log path on viewer title.
* Clear any log.
* Delete any log.
* Various fixes.
* PHPCS fixes.
* Code docs.
* Keeping 1 extra wp-config.php backup on activation.
* Disable debug toggles until 1 action is finished.
* Loading icons on Clear Log, Delete Log, Backup and Restore wp-config.
* Download wp-config on activation.

= 4.9.8.5 =
* Add Info to Snapshot

= 4.9.8.4 =
* Introduce WPMU DEV Tab
* Snapshot constants
* Global installation .log reader

= 4.9.8.3 =
* Added scheduled task nonces
* Fixed the SSL form to work via 'enter' also
* Minor fixes

= 4.9.8.2 =
* Adds SSL verification for any domain
* Security fixes ( thanks Vladislav Bailovic ( https://github.com/vladislavbailovic ) ! )

= 4.9.8.1 =
* Added Tabs view to shorten the pages length ( thanks Nahid F. Mohit ( https://github.com/nfmohit-wpmudev ) ! )
* Refactored SSL tests the wp way

= 4.9.8 =
* Refactored Live Debugging
* Added Server Information
* Added General WordPress Information
* Added PHP Information
* Added Scheduled Tasks Information
* Added Checksums tool to verify core files
* Added a simple wp_mail() check
* Added SSL/TLS Information

= 4.9.4.2 =
* Update required PHP to 5.3.
* Update required WordPress version to 4.8.

= 4.9.4.1 =
* Fixed removing 'n' from the debug view.

= 4.9.4 =
* Code refactoring.
* Minor fixes.

= 4.9.2.1 =

* Fixed faulty Plugin URI.

= 4.9.2 =

* Checks if file exists first to avoid errors.
* Bumping version to follow WordPress releases.

= 1.0.1 =

* Fixed `No such file or directory` warning

= 1.0.0 =

* Initial Release

== Upgrade Notice ==

= 4.9.8.6 =
Log viewer UI changes. Add full log path on viewer title. Clear any log. Delete any log. Various fixes. PHPCS fixes. Code docs. Keeping 1 extra wp-config.php backup on activation. Disable debug toggles until 1 action is finished. Loading icons on Clear Log, Delete Log, Backup and Restore wp-config. Download wp-config on activation.

= 4.9.8.5 =
Add Info to Snapshot.

= 4.9.8.4 =
Introduce WPMU DEV Tab. Snapshot constants. Global installation .log reader.

= 4.9.8.3 =
Added scheduled task nonces. Fixed the SSL form to work via 'enter' also. Minor fixes

= 4.9.8.2 =
Adds SSL verification for any domain. Security fixes ( thanks Vladislav Bailovic ( https://github.com/vladislavbailovic ) ! ).

= 4.9.8.1 =
Added Tabs view to shorten the pages length ( thanks Nahid F. Mohit ( https://github.com/nfmohit-wpmudev ) ! ). Refactored SSL tests the wp way.

= 4.9.8 =

Refactored Live Debugging. Added Server Information. Added General WordPress Information. Added PHP Information. Added Scheduled Tasks Information. Added Checksums tool to verify core files. Added a simple wp_mail() check. Added SSL/TLS Information.

= 4.9.4.2 =

Update required PHP to 5.3. Update required WordPress version to 4.8.

= 4.9.4.1 =

Fixed removing 'n' from the debug view.

= 4.9.4 =

Code refactoring. Minor fixes.

= 4.9.2.1 =

Fixed faulty Plugin URI.

= 4.9.2 =

Minor fixes. Bumping version to follow WordPress releases.

= 1.0.1 =

Fixed `No such file or directory` warning

= 1.0.0 =

Initial Release
