# LocalWeb All In One

* Contributors: sajdoko
* Tags: localweb, local web, chat, wim, web instant messenger, aio
* Requires at least: 4.8.5
* Tested up to: 5.3.2
* Stable tag: 5.3
* Requires PHP: 5.6
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt

LocalWeb All In One should be installed only on websites created by Local Web S.R.L, because it extends certain functionalities of the website which may send certain data to LocalWeb's servers. This is to make possible showing data on LocalWeb App.

>Note: The plugin uses services from https://localweb.it/ and is intended ONLY for Local Web S.R.L clients.
---
## Description
* Web Instant Messenger Integration
* Google Analytics Integration
* Contact Form 7 Integration

This plugin inserts in `wp_footer` the https://www.webinstantmessenger.it/ script which activates the Web Instant Messenger on site.

It call\'s the external script from localweb, ```www.localweb.it/chat/widget/ultimate_chat_widget.js``` which then builds the bottom right chat window.

All the chats and info inserted in the chat window is passed to localweb.it.

>Note: The plugin uses services from https://localweb.it/ and is intended ONLY for Local Web S.R.L clients.
---
## Installation

Upload the plugin folder lw-all-in-one and all it’s contents to the /wp-content/plugins/ directory, or install the plugin through the WordPress plugins screen directly.

Activate the plugin through the ‘Plugins’ screen in WordPress.

A new options page \"LW AIO Options\" will appear on the left side menu.
From there can be activated/deactivated the services.

---
## Changelog
* 1.4.0 - Added new options TAB 'Plugin Options'. Added options for data retention and to delete plugin data on uninstall.
* 1.3.0 - Implemented WP_List_Table to Saved GA Events and Saved CF7 pages.
* 1.2.5 - Fixes scheduled hook function for CF7 sync with localweb server.
* 1.2.4 - Overall code design pattern ottimisations.
* 1.2.3 - Added option to insert Packet Type and Id in LW AIO Options instead of hidden field on contact forms.
* 1.1.3 - Fixed time format when saving CF7 form on database.
* 1.1.2 - First version online
___