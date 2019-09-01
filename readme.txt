=== WP Personalize ===
Contributors: lusionlabs
Donate link: https://lusionlabs.com/
Tags: personalize, custom, html, js, javascript, css, php, multisite, single, site
Requires at least: 4.5
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Personalize and customize your WordPress single site or multisite (the entire network or individual sites), with your own CSS, Javascript, HTML and PHP scripts without changing any WordPress core files, plugin files or template files.
= Features =
* **Languages:** HTML, CSS, Javscript and PHP.
* **Locations:** '&#60;head&#62;', '&#60;body&#62;' or the footer section.
* **Areas:** Site, admin or both.
* **Control:** For multisite installations, you are able to control which languages, locations and areas can be selected on single site level.

= NOTE =
This plugin is for people with some level of knowledge for HTML, CSS, Javascript or PHP.

= The possibilities are endless for personalizing and customizing your WordPress site or multisite: =
* **Hide Elements: With custom CSS or Javascript (jQuery) you are able to hide HTML elements.
* **Style Elements:** With custom CSS you are able to override any styling.
* **Add Elements:** Add HTML elements to the '&#60;head&#62;', '&#60;body&#62; or the footer section.
* **Control Elements:** With custom Javascript (jQuery) you are able to control any HTML element or add further actions on a specific event.
* **PHP Code:** Implement your own PHP code in the '&#60;head&#62;', '&#60;body&#62;' or the footer section.
* **Much More:** Use your imagination.

== Installation ==

1. Upload `wp-personalize` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. The "WP Personalize" menu page is located under "Settings" for both single sites and multisite.

== Frequently Asked Questions ==

= Plugin doesn't work =

Please specify and provide as much information as you can to help us debug the problem.
Also please send us screenshots of any errors you are receiving.

== Changelog ==

= 2.2.0 =
* Adds modern build tools and TravisCI integration.
* Applies all automatically fixable sniffs from the WordPress Coding Standards.
* Updates documentation.
* Includes license file for GPL 2.0.
* Resolves internal line endings on two files.
* Improves security by applying best practices for data escaping and sanitization.
* Improves security by requiring nonce validation on all Admin AJAX requests.

= 2.1.0 =
* Fixed minor bugs.

= 2.0.1 =
* Updated readme.txt file.

= 2.0.0 =
* Added MultiSite support.
* Added online script editor for HTML, CSS, Javascript and PHP.
* Added Network wide script possibility.
* Added Single site script possibility.

= 1.0 =
* First release.

== Screenshots ==

1. Single site admin settings page
1. MultiSite network admin settings page

== Upgrade Notice ==

= 2.0.0 =
If you are upgrading from version 1.0 to 2.0.0, you will need to move the code or scripts from the files located in '*../wp-content/wp-personalize/...*' to the built-in script editor.
