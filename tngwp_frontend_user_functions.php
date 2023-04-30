<?php

/**
 * Plugin Name: TNG-WP Frontend User Functions
 *
 * Description: This plugin adapts the WordPress User Profile to accommodate additional TNG information for use with custom registration forms. It provides shortcodes to display two levels of user registration, a front-end profile page, a login-form, and a reset password form. There is also a sidebar login/logout widget. New registrations are are seamlessly integrated with TNG. The login form (either shortcode or widget) will log the user in to both WordPress and TNG.
 *
 * Plugin URI: https://www.uniquelyyourshosting.net/
 * Version: 4.0
 *         
 * Author: Heather Feuerhelm
 * Author URI: https://www.uniquelyyourshosting.com/
 * License: GPLv2
 * @package tngwp_frontend_user_functions
 *
 * This plugin used the Object-Oriented Plugin Template Solution as a skeleton
 * Plugin URI: https://www.uniquelyyourshosting.net
 */

/**
 * The instantiated version of this plugin's class
 */
$GLOBALS['tngwp_frontend_user_functions'] = new tngwp_frontend_user_functions;

/**
 * TNG-WP Frontend User Functions
 *
 * @package tngwp_frontend_user_functions
 * @link http://www.uniquelyyourshosting.net/
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @author Heather Feuerhelm <heather@uniquelyyourshosting.com>
 * @copyright Uniquely Yours Web Services, 2013
 *
 * This plugin used the Object-Oriented Plugin Template Solution as a skeleton
 * REPLACE_PLUGIN_URI
 */
class tngwp_frontend_user_functions {
	/**
	 * This plugin's identifier
	 */
	const ID = 'tngwp-frontend-user-functions';

	/**
	 * This plugin's name
	 */
	const NAME = 'TNG-WP Frontend User Functions';

	/**
	 * This plugin's version
	 */
	const VERSION = '2.1';

	/**
	 * This plugin's table name prefix
	 * @var string
	 */
	protected $prefix = 'tngwp_frontend_user_functions';


	/**
	 * Has the internationalization text domain been loaded?
	 * @var bool
	 */
	protected $loaded_textdomain = false;

	/**
	 * This plugin's options
	 *
	 * Options from the database are merged on top of the default options.
	 *
	 * @see tngwp_frontend_user_functions::set_options()  to obtain the saved
	 *      settings
	 * @var array
	 */
	protected $options = array();

	/**
	 * This plugin's default options
	 * @var array
	 */
	protected $options_default = array(
		'registration' => 1,
	);

	/**
	 * Our option name for storing the plugin's settings
	 * @var string
	 */
	protected $option_name;

	/**
	 * Name, with $table_prefix, of the table tracking login failures
	 * @var string
	 */
	protected $table_login;

	/**
	 * Our usermeta key for tracking when a user logged in
	 * @var string
	 */
	protected $umk_login_time;


	/**
	 * Declares the WordPress action and filter callbacks
	 *
	 * @return void
	 * @uses oop_plugin_template_solution::initialize()  to set the object's
	 *       properties
	 */
	public function __construct() {
		$this->initialize();

		if ($this->options['track_logins']) {
			add_action('wp_login', array(&$this, 'wp_login'), 1, 2);
		}

		if (is_admin()) {
			$this->load_plugin_textdomain();

			require_once dirname(__FILE__) . '/admin.php';
			$admin = new tngwp_frontend_user_functions_admin;

			if (is_multisite()) {
				$admin_menu = 'network_admin_menu';
				$admin_notices = 'network_admin_notices';
				$plugin_action_links = 'network_admin_plugin_action_links_tngwp_frontend_user_functions/tngwp_frontend_user_functions.php';
			} else {
				$admin_menu = 'admin_menu';
				$admin_notices = 'admin_notices';
				$plugin_action_links = 'plugin_action_links_tngwp_frontend_user_functions/tngwp_frontend_user_functions.php';
			}

			add_action($admin_menu, array(&$admin, 'admin_menu'));
			add_action('admin_init', array(&$admin, 'admin_init'));
			add_filter($plugin_action_links, array(&$admin, 'plugin_action_links'));

			register_activation_hook(__FILE__, array(&$admin, 'activate'));
			if (isset($_POST[ 'deactivate_deletes_data' ]))
			if ($this->options['deactivate_deletes_data']) {
				register_deactivation_hook(__FILE__, array(&$admin, 'deactivate'));
			}
		}
	}

	/**
	 * Sets the object's properties and options
	 *
	 * This is separated out from the constructor to avoid undesirable
	 * recursion.  The constructor sometimes instantiates the admin class,
	 * which is a child of this class.  So this method permits both the
	 * parent and child classes access to the settings and properties.
	 *
	 * @return void
	 *
	 * @uses oop_plugin_template_solution::set_options()  to replace the default
	 *       options with those stored in the database
	 */
	protected function initialize() {
		global $wpdb;

		$this->table_login = $wpdb->get_blog_prefix(0) . $this->prefix . 'login';

		$this->option_name = self::ID . '-options';
		$this->umk_login_time = self::ID . '-login-time';

		$this->set_options();
	}

	/*
	 * ===== ACTION & FILTER CALLBACK METHODS =====
	 */

	/**
	 * Stores the time a user logs in
	 *
	 * NOTE: This method is automatically called by WordPress when users
	 * successfully log in.
	 *
	 * @param string $user_name  the user name from the current login form
	 * @param WP_User $user  the current user
	 * @return void
	 */
	public function wp_login($user_name, $user) {
		if (!$user_name) {
			return;
		}
		$this->insert_login($user_name);
		$this->set_metadata_login_time($user->ID);
		$this->notify_login($user_name);
	}

	/*
	 * ===== INTERNAL METHODS ====
	 */

	/**
	 * Log the user out and send them to the lost password page
	 *
	 * This is here solely for demonstration of unit testing.
	 */
	protected function force_retrieve_pw() {
		wp_logout();
		wp_redirect(wp_login_url() . '?action=retrievepassword');
	}

	/**
	 * Obtains the email addresses the notifications should go to
	 * @return string
	 */
	protected function get_admin_email() {
		return get_site_option('admin_email');
	}

	/**
	 * Obtains the timestamp of the given user's last "login time"
	 *
	 * @param int $user_ID  the current user's ID number
	 * @return int  the Unix timestamp of the user's last login
	 */
	protected function get_metadata_login_time($user_ID) {
		return (int) get_user_meta($user_ID, $this->umk_login_time, true);
	}

	/**
	 * Sanitizes output via htmlspecialchars() using UTF-8 encoding
	 *
	 * Makes this program's native text and translated/localized strings
	 * safe for displaying in browsers.
	 *
	 * @param string $in   the string to sanitize
	 * @return string  the sanitized string
	 */
	protected function hsc_utf8($in) {
		return htmlspecialchars($in, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Saves the login info in the database
	 *
	 * @param string $user_login  the user name from the current login form
	 * @return void
	 */
	protected function insert_login($user_login) {
		global $wpdb;

		$wpdb->insert(
			$this->table_login,
			array(
				'user_login' => $user_login,
			),
			array('%s')
		);
	}

	/**
	 * A centralized way to load the plugin's textdomain for
	 * internationalization
	 * @return void
	 */
	protected function load_plugin_textdomain() {
		if (!$this->loaded_textdomain) {
			load_plugin_textdomain(self::ID, false, self::ID . '/languages');
			$this->loaded_textdomain = true;
		}
	}

	/**
	 * Sends an email to the blog's administrator telling them of a login
	 *
	 * @param string $user_name  the user name from the current login form
	 * @return bool
	 *
	 * @uses wp_mail()  to send the messages
	 */
	protected function notify_login($user_name) {
		$this->load_plugin_textdomain();

		$to = $this->sanitize_whitespace($this->get_admin_email());
		$blog = get_option('blogname');

		$subject = sprintf(__("LOGIN TO %s", self::ID), $blog);
		$subject = $this->sanitize_whitespace($subject);

		$message = sprintf(__("%s just logged in to %s.", self::ID),
				$user_name, $blog) . "\n";

		return wp_mail($to, $subject, $message);
	}

	/**
	 * Replaces all whitespace characters with one space
	 * @param string $in  the string to clean
	 * @return string  the cleaned string
	 */
	protected function sanitize_whitespace($in) {
		return preg_replace('/\s+/u', ' ', $in);
	}

	/**
	 * Stores the present time in the given user's "login time" metadata
	 *
	 * @param int $user_ID  the current user's ID number
	 * @return int|bool  the record number if added, TRUE if updated, FALSE
	 *                   if error
	 */
	protected function set_metadata_login_time($user_ID) {
		return update_user_meta($user_ID, $this->umk_login_time, time());
	}

	/**
	 * Replaces the default option values with those stored in the database
	 * @uses login_security_solution::$options  to hold the data
	 */
	protected function set_options() {
		if (is_multisite()) {
			switch_to_blog(1);
			$options = get_option($this->option_name);
			restore_current_blog();
		} else {
			$options = get_option($this->option_name);
		}
		if (!is_array($options)) {
			$options = array();
		}
		$this->options = array_merge($this->options_default, $options);
	}
}

	function redirect_login_page() {
		$login_page  = plugin_dir_url( __FILE__ ).'login.php';
		$page_viewed = basename($_SERVER['REQUEST_URI']);
	
		if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
			wp_redirect($login_page);
			exit;
		}
		if( $page_viewed == "genealogy/login.php") {
			wp_redirect($login_page);
			exit;
		}
	}
	add_action('init','redirect_login_page');
	
	function login_failed() {
		$login_page  = plugin_dir_url( __FILE__ ).'login.php';
		wp_redirect( $login_page . '?login=failed' );
		exit;
	}
	add_action( 'wp_login_failed', 'login_failed' );
	
	function verify_username_password( $user, $username, $password ) {
		$login_page  = plugin_dir_url( __FILE__ ).'login.php';
		if( $username == "" || $password == "" ) {
			wp_redirect( $login_page . "?login=empty" );
			exit;
		}
	}

//Include external files required by plugin
include('tngwp_frontend_profile.php');
include('ancestor_lookup.php');
include('advanced_registration.php');
include('simple_registration.php');
include('assets/custom_profile_settings.php');
include('assets/tngwp_user_functions.php');
include('assets/custom_login_logout_functions.php');

### Function: Enqueue Polls JavaScripts/CSS
/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'tng_user_meta_add_my_stylesheet' );
function tng_user_meta_add_my_stylesheet() {
	// Respects SSL, Style.css is relative to the current file
	wp_register_style( 'registration-style', plugins_url('assets/css/wptng_styles.css', __FILE__) );
	wp_enqueue_style( 'registration-style' );
	wp_register_style( 'tng_profile', plugins_url('assets/css/tng_profile.css', __FILE__) );
	wp_enqueue_style( 'tng_profile' );
}

add_action('wp_enqueue_scripts', 'tng_user_meta_scripts');
function tng_user_meta_scripts() {
	wp_enqueue_script('processAncestor', plugins_url('tngwp_frontend_user_functions/assets/js/processAncestor.js'), '', '1.0', true);
	wp_enqueue_script('validate_registration', plugins_url('tngwp_frontend_user_functions/assets/js/validate_registration.js'), '', '1.0', true);
	wp_enqueue_script('validate-inline', plugins_url('tngwp_frontend_user_functions/assets/js/jquery.valid8.js'), 'jquery', '1.3', true);
}
