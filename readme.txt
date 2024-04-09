=== LocalWeb All In One ===
Contributors: sajdoko
Tags: localweb, local web, chat, wim, web instant messenger
Requires at least: 4.8.5
Tested up to: 6.5
Stable tag: 1.8
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
**LocalWeb All In One** is a WordPress plugin exclusively designed for Local Web S.R.L clients, seamlessly integrating Web Instant Messenger, Google Analytics, and Contact Form 7 functionalities to enhance your website's engagement and performance.
LocalWeb All In One provides the following integrations:

**Web Instant Messenger Integration:** This plugin embeds the script from [Web Instant Messenger](https://www.webinstantmessenger.it/) into the `wp_footer` section of your website. This script activates the Web Instant Messenger on your site. The external script is fetched from LocalWeb at `www.localweb.it/chat/widget/ultimate_chat_widget.js`, which constructs the chat window in the bottom right corner of your website. All chat interactions and information entered into the chat window are transmitted to [LocalWeb].
**Google Analytics Integration:** The plugin facilitates integration with Google Analytics, including support for the latest Google Analytics 4 (GA4) and Google Tag Manager (GTM).
**Contact Form 7 Integration:** Integration with Contact Form 7 is supported. The plugin provides options to customize the behavior of Contact Form 7 scripts and styles based on your needs.

> :warning: **Note:** This plugin utilizes services provided by [LocalWeb](https://localweb.it/ "Web Marketing Agency") and is exclusively intended for Local Web S.R.L clients.


For more information, updates, and support, please visit [LocalWeb's official website](https://localweb.it/).

## Installation

1. Upload the plugin folder `lw-all-in-one` and its contents to the `/wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' screen in your WordPress admin panel.
3. A new options page named "LW AIO Options" will appear in the left-side menu of your WordPress admin panel.
4. From the "LW AIO Options" page, you can activate or deactivate the various services provided by the plugin.

## Changelog

### 1.8.2
- Updated translation files

### 1.8.0
- Added functionalities to display cookie banner consent.

### 1.7.4
- Added analytics tracking functionalities for WooCommerce data.

### 1.7.3
- Added new Spanish language.

### 1.7.2
- Added backward compatibility down to PHP 5.6.

### 1.7.1
- Fixed error with str_contains() in older versions of PHP.

### 1.7.0
- Added support for PHP v8.2.

### 1.6.9
- Removed Speed Tab Purify service and performed code cleanup.

### 1.6.8
- Added support for GA4 and Google Tag Manager.

### 1.6.7
- Updated to support Contact Form 7 events update.

### 1.6.6
- Removed forgotten test variable from `\admin\class-lw-all-in-one-admin.php` line 127.

### 1.6.5
- Updated to comply with WordPress Plugin Directory guidelines by implementing proper sanitizations. Added sanitizations for the host `localweb.it`.

### 1.6.4
- Updated the Google Analytics tracking code validation function.

### 1.6.3
- Adjusted the plugin to align with WordPress Plugin Directory guidelines.

### 1.6.2
- Introduced an option to purify CSS.

### 1.6.1
- Added an option to dequeue Contact Form 7 scripts/styles if the shortcode is not set.

### 1.6.0
- Improved WIM (Web Instant Messenger) API.

### 1.5.9
- Added an option to reset plugin options to their default settings.

### 1.5.8
- Fixed an error related to header/footer scripts.

### 1.5.7
- Added a filter to enable automatic plugin updates in the future.

### 1.5.6
- Updated privacy pages.

### 1.5.5
- Added an option to add custom analytics tracking events.

### 1.4.5
- Integrated WIM options to deactivate the service on LocalWeb's server. Also included other minor improvements.

### 1.4.3
- Added confirmation prompts for deleting saved records and implemented a function for checking data retention options.

### 1.4.1
- Resolved issues with plugin options during plugin upgrades.

### 1.4.0
- Introduced a new 'Plugin Options' tab with settings for data retention and data deletion on uninstall.

### 1.3.0
- Implemented WP_List_Table for managing saved GA events and CF7 (Contact Form 7) pages.

### 1.2.5
- Fixed the scheduled hook function for CF7 synchronization with the LocalWeb server.

### 1.2.4
- Optimized the overall code design patterns.

### 1.1.3
- Added an option to insert Packet Type and Id in LW AIO Options instead of using a hidden field in contact forms.

### 1.1.2
- Fixed the time format when saving CF7 form data to the database.

### 1.1.2
- Initial version online.