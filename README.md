### WP Live Debug [![View on WordPress.org](https://img.shields.io/badge/View%20on-WordPress.org-blue.svg)](https://wordpress.org/plugins/wp-live-debug/) [![Build Status](https://travis-ci.org/mrxkon/wp-live-debug.svg?branch=master)](https://travis-ci.org/mrxkon/wp-live-debug) [![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-F37F40.svg)](http://www.gnu.org/licenses/gpl-2.0.html)

`PHP: 7.2 / 7.1 / 7.0 / 5.6 / 5.5 / 5.4 -- PHPCS: WordPress Coding Standards`

Requires at least: WordPress 4.8

Tested up to: WordPress 4.9.8

Stable tag: 4.9.8

Requires PHP: 5.4

[![My Website](https://img.shields.io/badge/My-Website-F37F40.svg)](https://xkon.gr)  [![WordPress Profile](https://img.shields.io/badge/WordPress-Profile-blue.svg)](https://profiles.wordpress.org/xkon)

### Description

Enables debugging and adds various installation information. Simply enable the plugin and go to the WP Live Debug tab to see your debug.log.

### Credits & Licences:

This is a personal project that I use for debugging, but some parts of the code are written by other awesome people.

So props also go to:
[WPMU DEV]( https://premium.wpmudev.org ) for parts of debug info & Shared UI

- [Shared UI]( https://github.com/wpmudev/shared-ui ) - Licence GPLv2

The [WordPress.org]( https://wordpress.org ) community for parts of debug info
- [Health Check]( https://wordpress.org/plugins/health-check/ ) - Licence GPLv2 - [GitHub]( https://github.com/wordpress/health-check )

Fellow contributors: [Marius L. Jensen]( https://github.com/Clorith ), [Nahid F. Mohit]( https://github.com/nfmohit-wpmudev ), [Vladislav Bailovic ]( https://github.com/vladislavbailovic )

### Installation

**From within WordPress**

* Visit Plugins > Add New
* Search for WP Live Debug
* Click the Install Now button to install the plugin
* Click the Activate button to activate the plugin

**Manually**

* Unzip the downloaded package
* Upload `wp-live-debug` directory to the /wp-content/plugins/ directory
* Activate the plugin through the ‘Plugins’ menu in WordPress

### Screenshots

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot1.png)

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot2.png)

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot3.png)

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot4.png)

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot5.png)

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot6.png)

![Screenshot](https://raw.githubusercontent.com/mrxkon/wp-live-debug/master/assets/screenshot7.png)

### Changelog

#### 4.9.8.1
* Added Tabs view to shorten the pages length ( thanks [Nahid F. Mohit]( https://github.com/nfmohit-wpmudev ) ! )
* Refactored SSL tests the wp way

##### 4.9.8
* Refactored Live Debugging
* Added Server Information
* Added General WordPress Information
* Added PHP Information
* Added Scheduled Tasks Information
* Added Checksums tool to verify core files
* Added a simple wp_mail() check
* Added SSL/TLS Information

##### 4.9.4.2
* Update required PHP to 5.3.
* Update required WordPress version to 4.8.

##### 4.9.4.1
* Fixed removing 'n' from the debug view.

##### 4.9.4
* Code refactoring.
* Minor fixes.

##### 4.9.2.1

* Fixed faulty Plugin URI.

##### 4.9.2

* Checks if file exists first to avoid errors.
* Bumping version to follow WordPress releases.

##### 1.0.1

* Fixed `No such file or directory` warning

##### 1.0.0

* Initial Release
