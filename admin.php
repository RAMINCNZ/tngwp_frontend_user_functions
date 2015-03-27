<?php

/**
 * The user interface and activation/deactivation methods for administering
 * the Object Oriented Plugin Template Solution plugin
 *
 * This plugin abstracts WordPress' Settings API to simplify the creation of
 * a settings admin interface.  Read the docblocks for the set_sections() and
 * set_fields() methods to learn how to create your own settings.
 *
 * A table is created in the activate() method and is dropped in the
 * deactivate() method.  If your plugin needs tables, adjust the table
 * definitions and removals as needed.  If you don't need a table, remove
 * those portions of the activate() and deactivate() methods.
 *
 * This plugin is coded to be installed in either a regular, single WordPress
 * installation or as a network plugin for multisite installations.  So, by
 * default, multisite networks can only activate this plugin via the
 * Network Admin panel.  If you want your plugin to be configurable for each
 * site in a multisite network, you must do the following:
 *
 * + Search admin.php and oop-plugin-template-solution.php
 *   for is_multisite() if statements.  Remove the true parts and leave
 *   the false parts.
 * + In oop-plugin-template-solution.php, go to the initialize() method
 *   and remove the $wpdb->get_blog_prefix(0) portion of the
 *   $this->table_login assignment.
 *
 * Beyond that, you're advised to leave the rest of this file alone.
 *
 * @package wp-tng_frontend_user_functions
 * @link http://wordpress.org/extend/plugins/wp-tng_frontend_user_functions/
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @author HeatherFeuerhelm <heather@uniquelyyourshosting.com>
 * @copyright Uniquely Yours Web Services, 2013
 *
 * This plugin used the Object-Oriented Plugin Template Solution as a skeleton
 * REPLACE_PLUGIN_URI
 */

/**
 * The user interface and activation/deactivation methods for administering
 * the TNG-WP Frontend User Functions plugin
 *
 * @package tngwp_frontend_user_functions
 * @link http://wordpress.org/extend/plugins/wp-tng_frontend_user_functions/
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @author HeatherFeuerhelm <heather@uniquelyyourshosting.com>
 * @copyright Uniquely Yours Web Services, 2013
 *
 * This plugin used the Object-Oriented Plugin Template Solution as a skeleton
 * REPLACE_PLUGIN_URI
 */
class tngwp_frontend_user_functions_admin extends tngwp_frontend_user_functions {
	/**
	 * The WP privilege level required to use the admin interface
	 * @var string
	 */
	protected $capability_required;

	/**
	 * Metadata and labels for each element of the plugin's options
	 * @var array
	 */
	protected $fields;

	/**
	 * URI for the forms' action attributes
	 * @var string
	 */
	protected $form_action;

	/**
	 * Name of the page holding the options
	 * @var string
	 */
	protected $page_options;

	/**
	 * Metadata and labels for each settings page section
	 * @var array
	 */
	protected $settings;

	/**
	 * Title for the plugin's settings page
	 * @var string
	 */
	protected $text_settings;


	/**
	 * Sets the object's properties and options
	 *
	 * @return void
	 *
	 * @uses tngwp_frontend_user_functions::initialize()  to set the object's
	 *	     properties
	 * @uses tngwp_frontend_user_functions_admin::set_sections()  to populate the
	 *       $sections property
	 * @uses tngwp_frontend_user_functions_admin::set_fields()  to populate the
	 *       $fields property
	 */
	public function __construct() {
		$this->initialize();
		$this->set_sections();
		$this->set_fields();

		// Translation already in WP combined with plugin's name.
		$this->text_settings = self::NAME . ' ' . __('Settings');

		if (is_multisite()) {
			$this->capability_required = 'manage_network_options';
			$this->form_action = '../options.php';
			$this->page_options = 'settings.php';
		} else {
			$this->capability_required = 'manage_options';
			$this->form_action = 'options.php';
			$this->page_options = 'users.php';
		}
	}

	/*
	 * ===== ACTIVATION & DEACTIVATION CALLBACK METHODS =====
	 */

	/**
	 * Establishes the tables and settings when the plugin is activated
	 * @return void
	 */
	public function activate() {
		global $wpdb;

		if (is_multisite() && !is_network_admin()) {
			die($this->hsc_utf8(sprintf(__("%s must be activated via the Network Admin interface when WordPress is in multistie network mode.", self::ID), self::NAME)));
		}

		/*
		 * Create or alter the plugin's tables as needed.
		 */

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Note: dbDelta() requires two spaces after "PRIMARY KEY".  Weird.
		// WP's insert/prepare/etc don't handle NULL's (at least in 3.3).
		// It also requires the keys to be named and there to be no space
		// the column name and the key length.
		$sql = "CREATE TABLE `$this->table_login` (
				login_id BIGINT(20) NOT NULL AUTO_INCREMENT,
				user_login VARCHAR(60) NOT NULL DEFAULT '',
				date_login TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (login_id),
				KEY user_login (user_login(5))
				)";

		dbDelta($sql);
		if ($wpdb->last_error) {
			die($wpdb->last_error);
		}
		
		/*
		 * Save this plugin's options to the database.
		 */

		if (is_multisite()) {
			switch_to_blog(1);
		}
		update_option($this->option_name, $this->options);
		if (is_multisite()) {
			restore_current_blog();
		}
	}

	/**
	 * Removes the tables and settings when the plugin is deactivated
	 * if the deactivate_deletes_data option is turned on
	 * @return void
	 */
	public function deactivate() {
		global $wpdb;

		$prior_error_setting = $wpdb->show_errors;
		$wpdb->show_errors = false;
		$denied = 'command denied to user';

		$wpdb->show_errors = $prior_error_setting;

		$package_id = self::ID;
		$wpdb->escape_by_ref($package_id);

		$wpdb->query("DELETE FROM `$wpdb->options`
				WHERE option_name LIKE '$package_id%'");

		$wpdb->query("DELETE FROM `$wpdb->usermeta`
				WHERE meta_key LIKE '$package_id%'");
	}

	/*
	 * ===== ADMIN USER INTERFACE =====
	 */

	/**
	 * Sets the metadata and labels for each settings page section
	 *
	 * Settings pages have sections for grouping related fields.  This plugin
	 * uses the $sections property, below, to define those sections.
	 *
	 * The $sections property is a two-dimensional, associative array.  The top
	 * level array is keyed by the section identifier (<sid>) and contains an
	 * array with the following key value pairs:
	 *
	 * + title:  a short phrase for the section's header
	 * + callback:  the method for rendering the section's description.  If a
	 *   description is not needed, set this to "section_blank".  If a
	 *   description is helpful, use "section_<sid>" and create a corresponding
	 *   method named "section_<sid>()".
	 *
	 * @return void
	 * @uses tngwp_frontend_user_functions_admin::$sections  to hold the data
	 */
	protected function set_sections() {
		$this->sections = array(
			'overview' => array(
				'title' => __(" ", self::ID),
				'callback' => 'section_overview',
			),
			'login' => array(
				'title' => __("Login Policies", self::ID),
				'callback' => 'section_login',
			),
			'mbtng_path' => array(
				'callback' => 'tngwp_search_for_tng',
			),
			'registration' => array(
				'title' => __("Custom User Registration", self::ID),
				'callback' => 'section_registration',
			),
			'misc' => array(
				'title' => __("Miscellaneous Policies", self::ID),
				'callback' => 'section_blank',
			),
		);
	}

	/**
	 * Sets the metadata and labels for each element of the plugin's
	 * options
	 *
	 * The $fields property is a two-dimensional, associative array.  The top
	 * level array is keyed by the field's identifier and contains an array
	 * with the following key value pairs:
	 *
	 * + section:  the section identifier (<sid>) for the section this
	 *   setting should be displayed in
	 * + label:  a very short title for the setting
	 * + text:  the long description about what the setting does.  Note:
	 *   a description of the default value is automatically appended.
	 * + type:  the data type ("int", "string", or "bool").  If type is "bool,"
	 *   the following two elements are also required:
	 * + bool0:  description for the button indicating the option is off
	 * + bool1:  description for the button indicating the option is on
	 *
	 * WARNING:  Make sure to keep this propety and the
	 * tngwp_frontend_user_functions_admin::$options_default
	 * property in sync.
	 *
	 * @return void
	 * @uses tngwp_frontend_user_functions_admin::$fields  to hold the data
	 */
	protected function set_fields() {
		$this->fields = array(
			'track_logins' => array(
				'section' => 'login',
				'label' => __("Track Logins", self::ID),
				'text' => __("Should the time of each user's login be stored?", self::ID),
				'type' => 'bool',
				'bool0' => __("No, don't track logins.", self::ID),
				'bool1' => __("Yes, track logins.", self::ID),
			),
			'ancestor_lookup' => array(
				'section' => 'registration',
				'label' => __("Show Ancestor Select Form on:", self::ID),
				'text' => __("If you are using the simple registration, select the same page the form will be on.", self::ID),
				'type' => 'select',
			),
			'registration_form' => array(
				'section' => 'registration',
				'label' => __("Show Registration Form on:", self::ID),
				'text' => __("This is the page that shows the actual form", self::ID),
				'type' => 'select',
			),
			'success_page' => array(
				'section' => 'registration',
				'label' => __("Landing page for registration success:", self::ID),
				'text' => __("This is the page the user is redirected to after a successful registration.", self::ID),
				'type' => 'select',
			),
			'deactivate_deletes_data' => array(
				'section' => 'misc',
				'label' => __("Deactivation", self::ID),
				'text' => __("Should deactivating the plugin remove all of the plugin's data and settings?", self::ID),
				'type' => 'bool',
				'bool0' => __("No, preserve the data for future use.", self::ID),
				'bool1' => __("Yes, please delete the data.", self::ID),
			),
		);
	}

	/**
	 * A filter to add a "Settings" link in this plugin's description
	 *
	 * NOTE: This method is automatically called by WordPress for each
	 * plugin being displayed on WordPress' Plugins admin page.
	 *
	 * @param array $links  the links generated thus far
	 * @return array
	 */
	public function plugin_action_links($links) {
		// Translation already in WP.
		$links[] = '<a href="' . $this->hsc_utf8($this->page_options)
				. '?page=' . self::ID . '">'
				. $this->hsc_utf8(__('Settings')) . '</a>';
		return $links;
	}

	/**
	 * Declares a menu item and callback for this plugin's settings page
	 *
	 * NOTE: This method is automatically called by WordPress when
	 * any admin page is rendered
	 */
	public function admin_menu() {
		add_submenu_page(
			$this->page_options,
			$this->text_settings,
			self::NAME,
			$this->capability_required,
			self::ID,
			array(&$this, 'page_settings')
		);
	}

	/**
	 * Declares the callbacks for rendering and validating this plugin's
	 * settings sections and fields
	 *
	 * NOTE: This method is automatically called by WordPress when
	 * any admin page is rendered
	 */
	public function admin_init() {
		register_setting(
			$this->option_name,
			$this->option_name,
			array(&$this, 'validate')
		);

		// Dynamically declares each section using the info in $sections.
		foreach ($this->sections as $id => $section) {
			add_settings_section(
				self::ID . '-' . $id,
				$this->hsc_utf8($section['title']),
				array(&$this, $section['callback']),
				self::ID
			);
		}

		// Dynamically declares each field using the info in $fields.
		foreach ($this->fields as $id => $field) {
			add_settings_field(
				$id,
				$this->hsc_utf8($field['label']),
				array(&$this, $id),
				self::ID,
				self::ID . '-' . $field['section']
			);
		}
	}

	/**
	 * The callback for rendering the settings page
	 * @return void
	 */
	public function page_settings() {
		if (is_multisite()) {
			// WordPress doesn't show the successs/error messages on
			// the Network Admin screen, at least in version 3.3.1,
			// so force it to happen for now.
			include_once ABSPATH . 'wp-admin/options-head.php';
		}

		echo '<h2>' . $this->hsc_utf8($this->text_settings) . '</h2>';
		echo '<form action="' . $this->hsc_utf8($this->form_action) . '" method="post">' . "\n";
		settings_fields($this->option_name);
		do_settings_sections(self::ID);
		submit_button();
		echo '</form>';
	}

	/**
	 * The callback for "rendering" the sections that don't have descriptions
	 * @return void
	 */
	public function section_blank() {
	}

	/**
	 * The callback for rendering the "Overview" section description
	 * @return void
	 */
	public function section_overview() {
		echo '<p>';
		echo $this->hsc_utf8(__("This plugin is specifically designed to work as a user interface for Wordpress/TNG integrations. It provides shortcodes for registration, a front-end profile page, a login/logout shortcode, and a login/logout widget. The plugin also includes a built-in captcha for added security. ", self::ID));
		echo '</p>';
		echo '<h4>Frontend Profile</h4>';
		echo '<p>';
		echo $this->hsc_utf8(__("The shortcode [frontend_profile] replaces both the WordPress dashboard profile page and the TNG profile and displays it on a regular WordPress page. Create a page for the Front-End Profile and give it a title of your choice. Place the shortcode on the page. You don't have to make the page visible, but if you do and a user isn't logged in, they will be presented with the login form.", self::ID));
		echo '</p>';
		echo '<h4>Login / Logout</h4>';
		echo '<p>';
		echo $this->hsc_utf8(__("SIDEBAR WIDGET: A widget has been added under Appearance --> Widgets called “User Login / Logout”. Simply add it to any sidebar. If a user isn't logged in, it will display the log in form. If a user is logged in, it will display a welcome message, link to the profile page and link to log out. If the user is an Admin, the profile link is replaced by the link to the Dashboard.", self::ID));
		echo '</p>';
	}
	
	/**
	 * The callback for rendering the "Login Policies" section description
	 * @return void
	 */
	public function section_login() {
		echo '<p>';
		echo $this->hsc_utf8(__("This option, if set to yes, will email the site admin the time and name of a user whenever that user logs in to the site. If you have a very active site, you may wish to set this to no to keep your inbox from being cluttered.", self::ID));
		echo '</p>';
	}
	
		/**
	 * The callback for rendering the "TNG Directory" section description
	 * @return void
	 */
	public function tngwp_search_for_tng() {
		global $mbtng_path;
		$file = 'ahnentafel.php';
		$path = realpath($_SERVER['DOCUMENT_ROOT']);
		$objects = new RecursiveIteratorIterator(
					   new RecursiveDirectoryIterator($path), 
					   RecursiveIteratorIterator::SELF_FIRST);
		
		foreach($objects as $name => $object){
			if($object->getFilename() === 'ahnentafel.php') {
				$mbtng_path = trailingslashit( dirname($object->getPathname()) );
				//$mbtng_path = dirname($object->getPathname());
				update_option('mbtng_path', $mbtng_path);
			}
		}
		return $mbtng_path;
}

	/**
	 * The callback for rendering the "Custom Registration" section description
	 * @return void
	 */
	public function section_registration() {
		echo '<h4>Advanced Registration</h4>';
		echo '<p>';
		echo $this->hsc_utf8(__("There are two shortcodes provided: [lookup_ancestor] to search for the closest relative (including self) and [advanced_registration_form] that is the actual registration form. Create a WordPress page with your registration instructions. After the instructions, simply add the [lookup_ancestor] shortcode and set the page in the form below. Create a second page to hold the registration form. On that page add the shortcode [advanced_registration_form]. Be sure to come back here and set the page in the form below.", self::ID));
		echo '</p>';
		echo '<h4>Simple Registration</h4>';
		echo '<p>';
		echo $this->hsc_utf8(__("If you choose not to use the advanced registration process, create the page for the form and place the shortcode [simple_registration_form] on the page. Since this is a one-part form, simply include your instructions at the top of the page before the shortcode. In the form below, use the same page for both of the first two settings.", self::ID));
		echo '</p>';
		echo '<p>';
		echo $this->hsc_utf8(__("Select the pages below to display the registration form(s) and the successful landing.", self::ID));
		echo '</p>';
	}

	/**
	 * The callback for rendering the fields
	 * @return void
	 *
	 * @uses tngwp_frontend_user_functions_admin::input_int()  for rendering
	 *       text input boxes for numbers
	 * @uses tngwp_frontend_user_functions_admin::input_radio()  for rendering
	 *       radio buttons
	 * @uses tngwp_frontend_user_functions_admin::input_string()  for rendering
	 *       text input boxes for strings
	 */
	public function __call($name, $params) {
		if (empty($this->fields[$name]['type'])) {
			return;
		}
		switch ($this->fields[$name]['type']) {
			case 'bool':
				$this->input_radio($name);
				break;
			case 'int':
				$this->input_int($name);
				break;
			case 'string':
				$this->input_string($name);
				break;
			case 'select':
				$this->input_select($name);
				break;
		}
	}

	/**
	 * Renders the radio button inputs
	 * @return void
	 */
	protected function input_radio($name) {
		echo $this->hsc_utf8($this->fields[$name]['text']) . '<br/>';
		echo '<input type="radio" value="0" name="'
			. $this->hsc_utf8($this->option_name)
			. '[' . $this->hsc_utf8($name) . ']"'
			. ($this->options[$name] ? '' : ' checked="checked"') . ' /> ';
		echo $this->hsc_utf8($this->fields[$name]['bool0']);
		echo '<br/>';
		echo '<input type="radio" value="1" name="'
			. $this->hsc_utf8($this->option_name)
			. '[' . $this->hsc_utf8($name) . ']"'
			. ($this->options[$name] ? ' checked="checked"' : '') . ' /> ';
		echo $this->hsc_utf8($this->fields[$name]['bool1']);
	}

	/**
	 * Renders the text input boxes for editing integers
	 * @return void
	 */
	protected function input_int($name) {
		echo '<input type="text" size="3" name="'
			. $this->hsc_utf8($this->option_name)
			. '[' . $this->hsc_utf8($name) . ']"'
			. ' value="' . $this->hsc_utf8($this->options[$name]) . '" /> ';
		echo $this->hsc_utf8($this->fields[$name]['text']
				. ' ' . __('Default:', self::ID) . ' '
				. $this->options_default[$name] . '.');
	}

	/**
	 * Renders the text input boxes for editing strings
	 * @return void
	 */
	protected function input_string($name) {
		echo '<input type="text" size="25" name="'
			. $this->hsc_utf8($this->option_name)
			. '[' . $this->hsc_utf8($name) . ']"'
			. ' value="' . $this->hsc_utf8($this->options[$name]) . '" /> ';
		echo '<br />';
		echo $this->hsc_utf8($this->fields[$name]['text']
				. ' ' . __('Default:', self::ID) . ' '
				. $this->options_default[$name] . '.');
	}
	
		/**
	 * Renders the text input boxes for selecting options
	 * @return void
	 */
	protected function input_select($name) {
		$pages = get_pages('sort_column=menu_order');
		$my_options = get_option('tngwp-frontend-user-functions-options');
		echo '<select name="' 
			. $this->hsc_utf8($this->option_name)
			. '[' . $this->hsc_utf8($name) . ']">';
		foreach ($pages as $page) {
			$selected = ( $page->post_title == $this->options[$name] ) ? ' selected="selected"' : '';
			echo "<option value=\"".$page->post_title."\"".$selected.">".$page->post_title."</option>";
		}
		echo '</select>';
		echo '<input type="hidden" name="page_options" value="' . $this->hsc_utf8($this->options[$name]) . '" />';
		echo $this->hsc_utf8($this->fields[$name]['text']
				. ' ' . __(' ', self::ID) . ' '
				. $this->options_default[$name]);
		
	}

	/**
	 * Validates the user input
	 *
	 * NOTE: WordPress saves the data even if this method says there are
	 * errors.  So this method sets any inappropriate data to the default
	 * values.
	 *
	 * @param array $in  the input submitted by the form
	 * @return array  the sanitized data to be saved
	 */
	public function validate($in) {
		$out = $this->options_default;
		if (!is_array($in)) {
			// Not translating this since only hackers will see it.
			add_settings_error($this->option_name,
					$this->hsc_utf8($this->option_name),
					'Input must be an array.');
			return $out;
		}

		$gt_format = __("must be >= '%s',", self::ID);
		$default = __("so we used the default value instead.", self::ID);

		// Dynamically validate each field using the info in $fields.
		foreach ($this->fields as $name => $field) {
			if (!array_key_exists($name, $in)) {
				continue;
			}

			if (!is_scalar($in[$name])) {
				// Not translating this since only hackers will see it.
				add_settings_error($this->option_name,
						$this->hsc_utf8($name),
						$this->hsc_utf8("'" . $field['label'])
								. "' was not a scalar, $default");
				continue;
			}

			switch ($field['type']) {
				case 'bool':
					if ($in[$name] != 0 && $in[$name] != 1) {
						// Not translating this since only hackers will see it.
						add_settings_error($this->option_name,
								$this->hsc_utf8($name),
								$this->hsc_utf8("'" . $field['label']
										. "' must be '0' or '1', $default"));
						continue 2;
					}
					break;
				case 'int':
					if (!ctype_digit($in[$name])) {
						add_settings_error($this->option_name,
								$this->hsc_utf8($name),
								$this->hsc_utf8("'" . $field['label'] . "' "
										. __("must be an integer,", self::ID)
										. ' ' . $default));
						continue 2;
					}
					if (array_key_exists('greater_than', $field)
						&& $in[$name] < $field['greater_than'])
					{
						add_settings_error($this->option_name,
								$this->hsc_utf8($name),
								$this->hsc_utf8("'" . $field['label'] . "' "
										. sprintf($gt_format, $field['greater_than'])
										. ' ' . $default));
						continue 2;
					}
					break;
			}
			$out[$name] = $in[$name];
		}

		return $out;
	}
}
