# TNG User Management
## Features
* Sync users with TNG
* Custom registration fields to match user fields in TNG
* Add users and set TNG privileges through WordPress.
	* Create a submenu page under Users in WordPress to activate in TNG
* Select relative from TNG during registration

## Development 
### Roadmap
* Advanced Registration Form
	* Shortcode
	* Custom form on page
* Simple Registration Form
	* Shortcode
	* Perhaps use existing registration form with extra fields added
* Front-End Profile Form
	* Shortcode
	* Custom form on page
* Sync Users in WordPress and TNG
* No captcha, easily defeated with simple javascript. Replace with Akismet.
* Prevent users from logging in before activation by site admin.

### Pieces
* Plugin activation
	1. Check if base TNG/WP plugin is installed
	2. If not, stop install 
* Add user settings to existing TNG/WP settings page
* Get list of users in TNG and create WordPress users with appropriate roles and capabilities (maybe)
* Add Akismet support to help block spam registrations
* Use add_action( ‘init’, ‘function_name’ ) to process registration and profile forms

### Code Structure
* /tng-user-mgmt
	* tng-user-mgmt.php
	* /inc
		* class-registration.php
		* class-profile.php
		* class-tng-db.php
		* class-utilities.php
	* /admin
		* class-settings.php
	* /css
	* /js

### Potential TNG/WP Plugin Changes
* Remove user management from the base plugin
