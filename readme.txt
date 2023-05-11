=== TNG-WP Frontend User Functions ===
Contributors: HeatherFeuer
Tags: profile, TNG, login, registration
Requires at least: 3.0
Tested up to: 6.2
Stable tag: 4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adapts the WordPress User Profile to accommodate additional TNG information for use with custom registration forms. It provides shortcodes to display two levels of user registration, a front-end profile page, a login-form, and a reset password form. There is also a sidebar login/logout widget. New registrations are seamlessly integrated with TNG. The login form (either shortcode or widget) will log the user in to both WordPress and TNG. Version 4.0 is a complete re-write of the plugin so if you are upgrading, you will need to completely uninstall the old plugin before installing this one. It is also strongly recommended that you select to remove the data on uninstalling as well.

== Description ==

This plugin is specifically designed to work as a user interface for Wordpress/TNG integrations. It provides shortcodes for both an advanced registration and a simple registration, a front-end profile page, and login/lost password pages. There is also a login/logout widget. This latest version also incorporates Google ReCaptcha v3. You can find more information here: https://www.google.com/recaptcha/about/

Please note that this plugin requires [The Next Generation of Genealogy Sitebuilding](http://www.tngsitebuilding.com/); however, the tng-wordpress-plugin is no longer required. This plugin will now work as a stand-alone solution for any form of integration used.

== Installation ==

* Download the zip file [tng_user_meta_4-0.zip](https://uniquelyyourshosting.net/wp-content/uploads/tng_user_meta_4-0.zip) OR on [Github](https://github.com/HeatherFeuer/tngwp_frontend_user_functions) to your computer.
* In Plugins --> Add New, select 'upload,' browse to the file you downloaded and click on 'Install Now.'
* Activate the plugin through the 'Plugins' menu in WordPress
* The Settings page is located under the Users menu in the Admin dashboard.

== Screenshots ==

1. Simple Registration form
2. Advanced Registration Search Ancestor form
3. Advanced Registration Search Results
4. Advanced Registration Form
5. Profile Page

== Changelog ==
= 4.0 =
* Updated code to work with PHP 8.0
* Incorporated Google ReCaptcha 3.0 and removed old captcha.
* Replaced special login and lost password pages with shortcodes.
* Other minor tweaks and fixes to improve functionality.
= 3.0 =
* Added login/logout/delete user functions so that the standard TNG plugin is no longer required.
* Changed validation for captcha so that the submit button is no longer disabled.
= 2.1 =
* Fixed bug in captcha form so that submit button becomes enabled after correct captcha entered.
= 2.0 =
* Upgraded captcha to a more secure version.
* Added function to process lost password.
* Added function to log user IP and notify admin when users log in. This feature can be turned off if not wanted.
* Added autoupdate feature.
* Rewritten to make code standards-compliant.
= 1.5 =
* Bug fixes
* Additional styling
= 1.0 =
* Initial Release