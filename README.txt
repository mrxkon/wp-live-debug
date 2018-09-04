=== WP Live Debug ===
Contributors: xkon
Tags: debug
Requires at least: 4.8
Tested up to: 4.9.8
Stable tag: 4.9.8.3
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables debugging and adds various installation information.

== Description ==

Simply enable the plugin and go to the WP Live Debug tab to see your debug.log.

Credits & Licences:
This is a personal project that I use for debugging, but some parts of the code are written by other awesome people.

So props also go to:
WPMU DEV ( https://premium.wpmudev.org ) for parts of debug info & Shared UI
Shared UI ( https://github.com/wpmudev/shared-ui ) - Licence GPLv2

The WordPress.org ( https://wordpress.org ) community for parts of debug info
Health Check ( https://wordpress.org/plugins/health-check/ ) - Licence GPLv2 - ( https://github.com/wordpress/health-check )

Fellow contributors:  Marius L. Jensen ( https://github.com/Clorith ), Nahid F. Mohit ( https://github.com/nfmohit-wpmudev ), Vladislav Bailovic ( https://github.com/vladislavbailovic )

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
