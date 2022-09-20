=== LocalWeb All In One ===
Contributors: sajdoko
Tags: localweb, local web, chat, wim, web instant messenger, aio
Requires at least: 4.8.5
Tested up to: 6.0.2
Stable tag: trunk
Requires PHP: 5.6
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

LocalWeb All In One should be installed only on websites created by Local Web S.R.L, because it extends certain functionalities of the website which may send certain data to LocalWeb's servers. This is to make possible showing data on LocalWeb App.

> Note: The plugin uses services from [LocalWeb](https://localweb.it/ "Web Marketing Agency") and is intended ONLY for Local Web S.R.L clients.

== Description ==
* Web Instant Messenger Integration
* Google Analytics Integration
* Contact Form 7 Integration

This plugin inserts in `wp_footer` the [Web Instant Messenger](https://www.webinstantmessenger.it/) 's script which activates the Web Instant Messenger on site.
It call's the external script from LocalWeb, `www.localweb.it/chat/widget/ultimate_chat_widget.js` which then builds the bottom right chat window.
All the chats and info inserted in the chat window is passed to [LocalWeb].

> Note: The plugin uses services from [LocalWeb](https://localweb.it/ "Web Marketing Agency") and is intended ONLY for Local Web S.R.L clients.

== Installation ==
Upload the plugin folder lw-all-in-one and all it’s contents to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
Activate the plugin through the ‘Plugins’ screen in WordPress
A new options page "LW AIO Options" will appear on the left side menu.
From there can be activated/deactivated the services

== Changelog ==
= 1.6.9 =
* Removed Speed Tab Purify service, & code cleanup.
= 1.6.8 =
* Added support for GA4 and Google Tag Manager.
= 1.6.7 =
* Updated to support for contact form 7 events update.
= 1.6.6 =
* Removed forgoten test variable from \admin\class-lw-all-in-one-admin.php line 127.
= 1.6.5 =
* Updated to WordPress Plugin Directory guidelines by adding proper sanitizations. Also added sanitizations on host localweb.it.
= 1.6.4 =
* Updated Google Analytics tracking code validation function.
= 1.6.3 =
* WordPress Plugin Directory guidlines.
= 1.6.2 =
* Added option to purify CSS.
= 1.6.1 =
* Added option to dequeue CF7 scripts/styles if shortcode is not set.
= 1.6.0 =
* Better WIM API.
= 1.5.9 =
* Added option to reset plugin options to default.
= 1.5.8 =
* Fixed error with header/footer scripts.
= 1.5.7 =
* Added filter to automatically update plugin on future updates.
= 1.5.6 =
* Updated privacy pages.
= 1.5.5 =
* Added option to add custom analytics tracking events.
= 1.4.5 =
* Integrated WIM options to deactivate the service on lw server.
* Other small tweaks.
= 1.4.3 =
* Added confirm box on saved record delete actions.
* Added check function for data retention option.
= 1.4.1 =
* Fixed issue with options on plugin upgrade.
= 1.4.0 =
* Added new options TAB 'Plugin Options'.
* Added options for data retention and to delete plugin data on uninstall.
= 1.3.0 =
* Implemented WP_List_Table to Saved GA Events and Saved CF7 pages.
= 1.2.5 =
* Fixes scheduled hook function for CF7 sync with localweb server.
= 1.2.4 =
* Overall code design pattern ottimisations.
= 1.1.3 =
* Added option to insert Packet Type and Id in LW AIO Options instead of hidden field on contact forms.
= 1.1.2 =
* Fixed time format when saving CF7 form on database.
= 1.1.2 =
* First version online