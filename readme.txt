=== WPAppsDev - CF7 Form Submission Limit ===
Contributors:      saifulananda
Plugin Name:       WPAppsDev - CF7 Form Submission Limit
Tags:              Contact Form 7, CF7, Form, submission limit
Author URI:        https://saifulananda.me/
Author:            Saiful Islam Ananda
Requires at least: 5.0
Tested up to:      6.3.1
Version:           2.4.0
Stable tag: 	   trunk
Requires PHP:      7.2
License: 		   GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Contact Form 7 form submission limit control plugin.

== Description ==

CF7 Form Submission Limit plugin help you control Contact Form 7 form total submission limit. You can set submission limit settings for each form.

= FEATURES =
* You can configure easily for Contact Form 7 form.
* You can enable and disable the submission limit for a form.
* You can set the total submission limit for a form base on total form submission.
* You can set the total submission limit for a form base on user total submission. **New Feature**
* You can set reset date for the submission limit.
* Automatically resets form submission limit and set the next reset date.
* Form submission limit instant reset.

= Form Configuration =

[youtube https://www.youtube.com/watch?v=Tj7ChYRtajk]

= Privacy Policy =

WPAppsDev - CF7 Form Submission Limit uses [Appsero](https://appsero.com) SDK to collect some telemetry data upon user's confirmation. This helps us to troubleshoot problems faster & make product improvements.

Appsero SDK **does not gather any data by default.** The SDK only starts gathering basic telemetry data **when a user allows it via the admin notice**. We collect the data to ensure a great user experience for all our users.

Integrating Appsero SDK **DOES NOT IMMEDIATELY** start gathering data, **without confirmation from users in any case.**

Learn more about how [Appsero collects and uses this data](https://appsero.com/privacy-policy/).

== Installation ==

Easy way:
1. Go to **WP Admin - Plugins - Add New**
2. Search for "CF7 Form Submission Limit"
3. Install the plugin and then Activate it

or Manual way:
1. Download the plugin zip file
2. Extract it
3. Upload the plugin directory to **wp-content/plugins**
4. Activate **CF7 Form Submission Limit** plugin from **WP Admin - Plugins**

== Frequently Asked Questions ==

= Does it need any plugin other than Contact Form 7 for integration? =

No plugins are needed except Contact Form 7.

== Screenshots ==

1. Submission Limit Form Setting
2. CF7 form template
3. CF7 form base limitation
4. CF7 form base limitation 2
5. CF7 user base limitation
6. CF7 user base limitation 2

== Changelog ==

= 2.4.0 =
* Updated: Localization POT file.
* Updated: Appsero client library files.
* Updated: Reset submission limit functionality.
* Updated: WordPress latest version 6.3.1 compatibility.
* Added: New action and filter hooks.

= 2.3.2 =
* Fixed: Remaining message issue.

= 2.3.1 =
* Fixed: Some string translation issue.

= 2.3.0 =
* Added: Disable reset submission limit setting.
* Added: Disable displaying the remaining message setting.
* Added: Add a setting for control what happened after successfully form submission.
* Updated: Localization POT file.
* Fixed: Coding standard issues.

= 2.2.0 =
* Added: Appsero tracker for plugin analytics.
* Added: Reload form page if form enable submission limit.

= 2.1.0 =
* Added: Form submission limit instant reset functionality.
* Fixed: Contact Form 7 latest version error display compatibility issue.

= 2.0.0 =
* New: Total submission limit for a form base on user total submission.
* Fixed: Issue of displaying form error message has been fixed.
* Updated: Counter tag message.
* Updated: The setting fields have been rearranged.
* Updated: Localization POT file.
* Removed: Unused code.

= 1.1.0 =
* Updated: Changed templates folder dir and folder name.
* Fixed: File including error.
* Updated: Plugin functionality.

= 1.0.2 =
* Fixed: PHP Fatal error: Uncaught Error: Call to undefined function is_plugin_active().

= 1.0.1 =
* Fixed: PHP warning notice.
* Added: Missing translating static text and update language file.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 2.2.0 =
**Important Update** This update added the appsero tracker to get the analytics and performance of the plugin.

= 1.0.0 =
Initial release.