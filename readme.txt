=== Plugin Name ===
Contributors: xfinx
Tags: Bol.com partner programma
Requires at least: 3.9
Tested up to: 4.0
Version: 0.9.4.1
Stable tag: 0.9.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use tags or categories to generate results from bol.com partner programme which match closely to your post or page.

== Description ==

An easy to use plugin which allows you to show results of Bol.com using your own partner programme credentials.
This plugin uses the API version 4

Functionalities are:

*   Available as shortcode [bol_partner_programme grid="true" item_width="25%"]
*	Available as widget
*   Customisable colours font-sizes and aligning
*   Clean HTML and CSS Code.
*	Uses latest API (v4)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `/bol-com-partner/` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Update settings in settings > bol.com menu
1. use shortcode or widget to display results.

== Frequently Asked Questions ==

= What is the shortcode =

[bol_partner_programme grid="true(or false)" item_width="25%"]o

= Questions / Remarks

if you have questions or remarks, please let me know via bjorn [at] boonen.eu

== Changelog ==

= 0.9.4.1 =
Removed console.log

= 0.9.4 =
Added code to make async work

= 0.9.2 =
Fixed location of output 

= 0.9.1 =
Now an keyword override is added, if Bol.com has a promo, all keywords can be replaced by the promo keyword
A default keyword can be set now to be always included in the search

= 0.9 =
Updated version number as it is near to version 1

= 0.3.8 =
Added keyword option as custom field.
Now you can add a custom field to your post bol-keyword. If custom-field is set in the settings it will read the keyword

= 0.3.7 =
Added default keyword

= 0.3.5 =
If no searchword is given, don't search

= 0.3.4 =
Added checks to prevent Invalid argument issues

= 0.3.3 =
Made path of css dynamic

= 0.3.2 =
Checked if session is valid before doing while loop

= 0.3.1 =
minor tweak in loading css

= 0.3 =
* Upgraded API version to v4
* Removed deprecated settings
* Fixed bug: proper clickout Url

= 0.2 =
* Tabbed admin menu
* Code optimisations

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.9.4 =
Async option is built now

= 0.9.2 =
Minor fixes and ready for wordpress 4

= 0.9 =
Updated version number as it is near to version 1

= 0.3.8 =
Added keyword option as custom field.
Now you can add a custom field to your post bol-keyword. If custom-field is set in the settings it will read the keyword

= 0.3.7 =
If no keyword can be retrieved, at takes the default keyword

= 0.3.5 =
Minor bug fixes

= 0.3.4 =
Minor bug fixes

= 0.3.3 =
In some cases the folder of the plugin has a name which is not what was expected. Now the folder is checked before anything else uses it

= 0.3.2 =
If connection is lost, the while loop won't run now

= 0.3.1 =
It seemed CSS was not working properly, although it was on my test environment.

= 0.3 =
Moved API Version to Version 4. Please check your settings

= 0.2 =
More clear forms

= 0.1 =
Initial release

