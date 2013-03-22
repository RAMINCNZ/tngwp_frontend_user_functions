<?php
/*
Plugin Name: TNG Frontend Profile & Registration
Plugin URI: http://uniquelyyours.blogdns.com/enhance-this/
Description: This plugin adapts the WordPress User Profile to accommodate additional TNG information for use with custom registration forms. The plugin provides these shortcodes: Frontend Profile: [tng-profile]; Frontend Login: [user_meta_login_form]; New Account Database Search: [tng_lookup_ancestor]; User Registration Form: [user_registration_form].
Version: 1.2
Release Date: 12/15/2012
Author: Heather Feuerhelm
Author URI: http://uniquelyyours.blogdns.com
License: GPL2
*/

// Set-up Action and Filter Hooks
register_activation_hook( __FILE__, array( 'tng_user_meta_addon_Init', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'tng_user_meta_addon_Init', 'on_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'tng_user_meta_addon_Init', 'on_uninstall' ) );

// Class example (inside ex. filename.php):
if ( ! class_exists('tng_user_meta_addon_Init' ) ) :
/*
 * This class triggers functions that run during activation/deactivation & uninstallation
 * NOTE: All comments are just my *suggestions*.
 */
class tng_user_meta_addon_Init
{
    // Set this to true to get the state of origin, so you don't need to always uninstall during development.
    const STATE_OF_ORIGIN = false;

    function __construct( $case = false )
    {
        if ( ! $case )
            wp_die( 'Busted! You should not call this class directly', 'Doing it wrong!' );

        switch( $case )
        {
            case 'activate' :
                // add_action calls and else
                # @example:
                add_action( 'init', array( &$this, 'activate_cb' ) );
                break;

            case 'deactivate' :
                // reset the options
                # @example:
                add_action( 'init', array( &$this, 'deactivate_cb' ) );
                break;

            case 'uninstall' : 
                // delete the tables
                # @example:
                add_action( 'init', array( &$this, 'uninstall_cb' ) );
                break;
        }
    }

    /**
     * Set up tables, add options, etc. - All preparation that only needs to be done once
     */
    function on_activate()
    {
        new tng_user_meta_addon_Init( 'activate' );
    }

    /**
     * Do nothing like removing settings, etc. 
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    function on_deactivate()
    {
        $case = 'deactivate';
        if ( STATE_OF_ORIGIN )
            $case = 'uninstall';

        new tng_user_meta_addon_Init( $case );
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     * 
     * Will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself
     */
    function on_uninstall()
    {
        // important: check if the file is the one that was registered with the uninstall hook (function)
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;

        new tng_user_meta_addon_Init( 'uninstall' );
    }

    function activate_cb()
    {
        // Stuff like adding default option values to the DB
        wp_die( '<h1>This is run on <code>init</code> during activation.</h1>', 'Activation hook example' );
    }

    function deactivate_cb()
    {
        // if you need to output messages in the 'admin_notices' field, do it like this:
        $this->error( "Some message.<br />" );
        // if you need to output messages in the 'admin_notices' field AND stop further processing, do it like this:
        $this->error( "Some message.<br />", TRUE );
        // Stuff like remove_option(); etc.
        wp_die( '<h1>This is run on <code>init</code> during deactivation.</h1>', 'Deactivation hook example' );
    }

    function uninstall_cb()
    {
        // Stuff like delete tables, etc.
        wp_die( '<h1>This is run on <code>init</code> during uninstallation</h1>', 'Uninstallation hook example' );
    }
    /**
     * trigger_error()
     * 
     * @param (string) $error_msg
     * @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
     * @param unknown_type $error_type
     * @return void
     */
    function error( $error_msg, $fatal_error = false, $error_type = E_USER_ERROR )
    {
        if( isset( $_GET['action'] ) && 'error_scrape' == $_GET['action'] )
        {
            echo "{$error_msg}\n";
            if ( $fatal_error )
                exit;
        }
        else
        {
            trigger_error( $error_msg, $error_type );
        }
    }
}
endif;
//End of hooks and filters


//Initialize the plugin options
add_action('admin_init', 'tng_user_meta_init' );
// Init plugin options to white list our options
function tng_user_meta_init(){
	register_setting( 'tng_user_meta_options', 'user_meta_registration_page' );
	register_setting( 'tng_user_meta_options', 'user_meta_register_start_page' );
	register_setting( 'tng_user_meta_options', 'user_meta_success_page' );
	register_setting( 'tng_user_meta_options', 'user_meta_profile_page' );
}
//Add menu item to Dashboard Admin Menu
add_action( 'admin_menu', 'tng_user_meta_menu' );
function tng_user_meta_menu() {
	add_menu_page('TNG Users', 'TNG Users', 'manage_options', __FILE__, 'tng_user_meta_do_page', plugins_url('tng_user_meta/images/icon.png'));
}

function tng_user_meta_do_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div style="width: 80%" class="wrap">';

	echo '<h1>TNG Frontent Profile &amp; Registration</h1>';
	?>
	<p style="font-size:1.2em;line-height:1.5em;">This plugin is specifically designed to work as a user interface for Wordpress/TNG integrations. It provides shortcodes for two levels of registration, a shortcode for a front-end profile page, a one-line login/logout shortcode, and a login/logout widget. The plugin works best when used with two free WordPress plugins. Even if you choose not to use the TNG Users plugin, I highly recommend these two plugins for additional security purposes:
	<ul style="font-size: 1.2em;"><li><a href="http://wordpress.org/extend/plugins/confirm-user-registration/" target="_blank">Confirm User Registration</a> where Admins have to confirm each user registration and a notification is sent when the account gets approved.</li>
	<li><a href="http://wordpress.org/extend/plugins/stop-spammer-registrations-plugin/" target="_blank">Stop Spammer Registrations Plugin</a>, which checks comments and logins 15 different ways to block spammers.</li></ul></p>
	<?php echo '<h2>Custom User Registration</h2>'; ?>
	<h3>Advanced Registration</h3>
	<p style="font-size:1.2em;line-height:1.5em;">There are two shortcodes provided: [tng_lookup_ancestor] to search for the closest relative (including self) and [user_registration_form] that is the actual registration form. Create a WordPress page with your registration instructions. After the instructions, simply add the [tng_lookup_ancestor] shortcode and set the page in the form below.</p>
	<p style="font-size:1.2em;line-height:1.5em;">Create a second page to hold the registration form. On that page add the shortcode [user_registration_form]. Be sure to come back here and set the page in the form below.</p>

	<h3>Simple Registration</h3>
	<p style="font-size:1.2em;line-height:1.5em;">If you choose not to use the advanced registration process, create the page for the form and place the shortcode [simple_registration_form] on the page. Since this is a one-part form, simply include your instructions at the top of the page before the shortcode. In the form below, use the same page for both of the first two settings.</p>
	<div style="font-size: normal;">
	<?php
//	Select pages below for registration
	$pages = get_pages('sort_column=menu_order');
	echo "<form method=\"post\" action=\"options.php\">\n";
	settings_fields('tng_user_meta_options');
// Roger added a width to this table
	echo "\t<table width=\"800\">\n";
	echo "\t\t<tr>\n";

// Select page for Advanced Registration Start
	echo "\t\t\t<td width=\"200\" style=\"padding: 0.5em 0; font-size:1.2em;\">Show Ancestor Select Form on:</td>\n";
	echo "\t\t\t<td style=\"padding: 0.5em 0;\"><select name=\"user_meta_register_start_page\">";
	foreach ($pages as $page) {
		if (get_bloginfo('wpurl') != get_permalink($page->ID)) { // Don't allow homepage to be selected
			if ($page->ID == get_option('user_meta_register_start_page'))
				$selected = ' selected="selected"';
			else
				$selected='';
			echo "<option value=\"{$page->ID}\"{$selected}>{$page->post_title}</option>";
		}
	}
	echo "\t\t\t</select></td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";

// Select page to show the actual Registration Form
	echo "\t\t\t<td width=\"200\" style=\"padding: 0.5em 0; font-size:1.2em;\">Show Registration Form on:</td>\n";
	echo "\t\t\t<td style=\"padding: 0.5em 0;\"><select name=\"user_meta_registration_page\">";
	foreach ($pages as $page) {
		if (get_bloginfo('wpurl') != get_permalink($page->ID)) { // Don't allow homepage to be selected
			if ($page->ID == get_option('user_meta_registration_page'))
				$selected = ' selected="selected"';
			else
				$selected='';
			echo "<option value=\"{$page->ID}\"{$selected}>{$page->post_title}</option>";
		}
	}
	echo "\t\t\t</select></td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";

// Select page for Success Landing
	echo "\t\t\t<td width=\"200\" style=\"padding: 0.5em 0; font-size:1.2em;\">Landing page for registration success:</td>\n";
	echo "\t\t\t<td style=\"padding: 0.5em 0;\"><select name=\"user_meta_success_page\">";
	foreach ($pages as $page) {
		if (get_bloginfo('wpurl') != get_permalink($page->ID)) { // Don't allow homepage to be selected
			if ($page->ID == get_option('user_meta_success_page'))
				$selected = ' selected="selected"';
			else
				$selected='';
			echo "<option value=\"{$page->ID}\"{$selected}>{$page->post_title}</option>";
		}
	}
	echo "\t\t\t</select></td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";

// Select page to show the actual Registration Form
	echo "\t\t\t<td width=\"200\" style=\"padding: 0.5em 0; font-size:1.2em;\">Select page for Profile:</td>\n";
	echo "\t\t\t<td style=\"padding: 0.5em 0;\"><select name=\"user_meta_profile_page\">";
	foreach ($pages as $page) {
		if (get_bloginfo('wpurl') != get_permalink($page->ID)) { // Don't allow homepage to be selected
			if ($page->ID == get_option('user_meta_profile_page'))
				$selected = ' selected="selected"';
			else
				$selected='';
			echo "<option value=\"{$page->ID}\"{$selected}>{$page->post_title}</option>";
		}
	}
	echo "\t\t\t</select></td>\n";
	echo "\t\t</tr>\n";
	echo "\t\t<tr>\n";
	echo "\t\t\t<td><p class=\"submit\" style=\"padding:0\"><input type=\"submit\" name=\"Submit\" value=\"Save Changes\" /></p></td>\n";
	echo "\t\t</tr>\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"update\" />";
	echo "<input type=\"hidden\" name=\"page_options\" value=\"user_meta_registration_page, user_meta_success_page,user_meta_register_start_page,user_meta_profile_page\" />";
	echo "</form>\n";
	echo "\t</table>\n";
	echo "</div>";
//	Additional Instructions for usage
	echo '<h2>Additional Features</h2>';
	echo '<h3>Front-End Profile</h3>';
	echo '<p style="font-size:1.2em;line-height:1.5em;">The shortcode [tng_profile] replaces both the WordPress dashboard profile page and the TNG profile and displays it on a regular WordPress page. Create a page for the Front-End Profile and give it a title of your choice. Place the shortcode on the page. You don\'t have to make the page visible, but if you do and a user isn\'t logged in, a message will automatically display.';
	echo '<h3>Log In / Log Out</h3>'; ?>
	<p style="font-size:1.2em;line-height:1.5em;"><strong>Sidebar Widget: </strong>A widget has been added under Appearance --> Widgets called &ldquo;User Login / Logout&rdquo;. Simply add it to any sidebar. If a user isn't logged in, it will display the log in form. If a user is logged in, it will display a welcome message, link to the profile page and link to log out. If the user is an Admin, the profile link is replaced by the link to the Dashboard.</p>
	<p style="font-size:1.2em;line-height:1.5em;"><strong>Log In Shortcode: </strong>If you would like to have the log in feature in your template header, footer or some other location, simply place the following code in your template where you would like a one-line log in/log out: <code>&lt;?php echo do_shortcode('[user_meta_login_logout]'); ?&gt;</code></p>

	<?php echo '</div>';
}

//Include external files required by plugin
include('tng_frontend_profile.php');
include('ancestor_lookup.php');
include('advanced_registration.php');
include('simple_registration.php');

// BEGIN Custom User Contact Info
//removes outdated contacts and adds Facebook, Twitter and LinkedIn
 function extra_contact_info($contactmethods) {
 //unset($contactmethods['aim']);
 //unset($contactmethods['yim']);
 //unset($contactmethods['jabber']);
 $contactmethods['facebook'] = 'Facebook';
 $contactmethods['twitter'] = 'Twitter';
 $contactmethods['linkedin'] = 'LinkedIn';
 $contactmethods['telephone'] = 'Phone Number';
 return $contactmethods;
 }
 add_filter('user_contactmethods', 'extra_contact_info');
 /* END Custom User Contact Info */

/* This next section adds Personal Information fields like in TNG */
/* BEGIN New Address Info Section */
function get_user_address_profile_list() {
return Array(
	'address' => 'Address',
	'city' => 'City',
	'state_prov' => 'State/Province',
	'postalcode' => 'Postal Code',
	'country' => 'Country'
);
}
add_action( 'show_user_profile', 'user_address_profile_fields' );
add_action( 'edit_user_profile', 'user_address_profile_fields' );

function user_address_profile_fields( $user ) { ?>
<h3><?php _e("Your Address Information", "blank"); ?></h3>

<table class="form-table">
<?php
	foreach(get_user_address_profile_list() as $key => $value) {
?>
<tr>
	<th>
		<label for="<?php echo $key; ?>"><?php _e($value); ?></label>
	</th>
	<td>
		<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( the_author_meta( $key, $user->ID ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter your $value."); ?></span>
	</td>
</tr>
<?php
	}
?>
</table>
<?php }

add_action( 'personal_options_update', 'save_user_address_profile_fields' );
add_action( 'edit_user_profile_update', 'save_user_address_profile_fields' );

function save_user_address_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

foreach(get_user_address_profile_list() as $key => $value) {
	update_user_meta( $user_id, $key, $_POST[$key] );
}
}
/* END new address information section */

/* BEGIN Relationship Information Section */
function get_user_relationship_profile_list() {
return Array(
'relative' => 'Your Closest Relative in Tree (not yourself)',
'relationship' => 'Your Relationship'
);
}

add_action( 'show_user_profile', 'user_relationship_profile_fields' );
add_action( 'edit_user_profile', 'user_relationship_profile_fields' );

function user_relationship_profile_fields( $user ) { ?>
<h3><?php _e("TNG Relationship Information", "blank"); ?></h3>

<table class="form-table">
<?php
	foreach(get_user_relationship_profile_list() as $key => $value) {
?>
<tr>
	<th>
		<label for="<?php echo $key; ?>"><?php _e($value); ?></label>
	</th>
	<td>
		<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( the_author_meta( $key, $user->ID ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter $value."); ?></span>
	</td>
</tr>
<?php
	}
?>
</table>
<?php }

add_action( 'personal_options_update', 'save_user_relationship_profile_fields' );
add_action( 'edit_user_profile_update', 'save_user_relationship_profile_fields' );
function save_user_relationship_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

foreach(get_user_relationship_profile_list() as $key => $value) {
	update_user_meta( $user_id, $key, $_POST[$key] );
}
}
/* END Relationship Information Section */

### Function: My Login Form
/**
 * My login form hacked from wp_login_form
 */
function user_meta_login_form( $item ) {
	$post_id1 = get_option('user_meta_register_start_page');
	$post_id2 = get_option('user_meta_registration_page');
	if($post_id1 != $post_id2) {$post_id = $post_id1;}
	else {$post_id = $post_id2;}
	$profile = get_option('user_meta_profile_page');
	ob_start();
	global $args, $item, $user_info;
	if ( is_user_logged_in() ) {
		
		$current_user = wp_get_current_user();
		if(empty($current_user->display_name)) {
			echo "<h4 style=\"text-align: center;\">Welcome ". $current_user->user_login ."!</h4>\n";
		} else {
			echo "<h4 style=\"text-align: center;\">Welcome ". $current_user->display_name ."!</h4>\n";
		}
		if ( current_user_can('administrator') ) echo "<h6 style=\"text-align: center;\"><a href=\"". get_admin_url() ."\">Site Admin</a> | ";
		if ( !(current_user_can('administrator')) ) echo "<h6 style=\"text-align: center;\"><a href=\"". get_home_url() ."/".get_permalink($profile)."\">User Profile</a> | ";
		wp_loginout(get_permalink())."</h6>";
		return $user_info;
	}
	else {
		$post = get_post($post_id);
		$page = $post->post_name;
		$defaults = array( 'echo' => false,
			'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
			'form_id' => 'loginform',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in' => __( 'Log In' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'size' => '10',
			'remember' => true,
			'value_username' => '',
			'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
		);
		$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );

		$form = '
			<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', $_SERVER['PHP_SELF'] ) ) . '" method="post">
				' . apply_filters( 'login_form_top', '', $args ) . '
				<p class="login-username">
					<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
					<input style="width:80%;" type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="'.$args['size'].'" tabindex="10" />
				</p>
				<p class="login-password">
					<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
					<input style="width:80%;" type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input" value="" size="'.$args['size'].'" tabindex="20" />
				</p>
				' . apply_filters( 'login_form_middle', '', $args ) . '
				' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever" tabindex="90"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
				<p class="login-submit">
					<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" tabindex="100" />
					<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
				</p>
				<p><a href="'.wp_lostpassword_url().'" title="Lost Password">Lost Password</a>&nbsp;|&nbsp;<a href="/'.$page.'">Click to Register</a></p>
				' . apply_filters( 'login_form_bottom', '', $args ) . '
			</form>';
		return $form;
	}
	$item = ob_get_contents();
	ob_end_clean();
	return $item;
}
//add_shortcode('user_meta_login_form', 'user_meta_login_form');

function my_login_logout() {
	global $args, $user_info;
	$profile = 
	$post_id1 = get_option('user_meta_register_start_page');
	$post_id2 = get_option('user_meta_registration_page');
	if($post_id1 != $post_id2) {$post_id = $post_id1;}
	else {$post_id = $post_id2;}
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		echo "<h5>Welcome ". $current_user->display_name ."!&nbsp;&nbsp;";
		if ( current_user_can('administrator') ) echo "<a href=\"". get_admin_url() ."\">Site Admin</a> | ";
		if ( !(current_user_can('administrator')) ) echo "<a href=\"". get_home_url() ."/".get_option('user_meta_profile_page')."\">User Profile</a> | ";
		wp_loginout(get_permalink()) ."</h5>";
		echo $user_info;
	}
	else {
		$post = get_post($post_id);
		$page = $post->post_name;
		$defaults = array( 'echo' => false,
			'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['HTTP_REFERER'], // Default redirect is back to the referring page
			'form_id' => 'loginform',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in' => __( 'Log In' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'size' => '10',
			'remember' => true,
			'value_username' => '',
			'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
		);
		$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
		$form = '
		<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', $_SERVER['PHP_SELF'] ) ) . '" method="post">
			' . apply_filters( 'login_form_top', '', $args ) . '
			<label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '&nbsp;
			<input style="width: 100px;" type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" /></label>&nbsp;&nbsp;
			<label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '&nbsp;
			<input style="width: 100px;" type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input" value="" /></label>&nbsp;&nbsp;
			<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" tabindex="100" />
			<input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />&nbsp;
			<a href="'.wp_lostpassword_url().'" title="Lost Password">Lost Password</a>&nbsp;|
			<a href="/'.$page.'">Click to Register</a>
			' . apply_filters( 'login_form_bottom', '', $args ) . '
		</form>
		';
		echo $form;
	}
}
add_shortcode('user_meta_login_logout', 'my_login_logout');

//Log In / Log Out Widget
class TNGLoginLogoutWidget extends WP_Widget
{
  function TNGLoginLogoutWidget()
  {
    $widget_ops = array('classname' => 'TNGLoginLogoutWidget', 'description' => 'Displays the login form or user name and links to profile/logout' );
    $this->WP_Widget('TNGLoginLogoutWidget', 'TNG User Login / Logout', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php esc_attr_e($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	$item = '';
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
	$current_user = '';
    if ( is_user_logged_in() ) { $item = $current_user; }
	echo user_meta_login_form( $item );

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("TNGLoginLogoutWidget");') );

### Function: Enqueue Polls JavaScripts/CSS
/**
 * Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
 */
add_action( 'wp_enqueue_scripts', 'tng_user_meta_add_my_stylesheet' );
function tng_user_meta_add_my_stylesheet() {
	// Respects SSL, Style.css is relative to the current file
	wp_register_style( 'registration-style', plugins_url('styles.css', __FILE__) );
	wp_enqueue_style( 'registration-style' );
//	wp_register_style( 'registration-validation', plugins_url('validationEngine.jquery.css', __FILE__) );
	wp_enqueue_style( 'registration-validation' );
	wp_register_style( 'registration-qaptcha', plugins_url('QapTcha.jquery.css', __FILE__) );
	wp_enqueue_style( 'registration-qaptcha' );
	wp_register_style( 'tng_profile', plugins_url('tng_profile.css', __FILE__) );
	wp_enqueue_style( 'tng_profile' );
}

add_action('wp_enqueue_scripts', 'tng_user_meta_scripts');
function tng_user_meta_scripts() {
	wp_enqueue_script('processAncestor', plugins_url('tng_user_meta/js/processAncestor.js'), '', '1.0', true);
	wp_enqueue_script('validate-inline', plugins_url('tng_user_meta/js/jquery.valid8.js'), 'jquery', '1.3', true);
	wp_enqueue_script('validate', plugins_url('tng_user_meta/js/jquery.validate.js'), 'jquery', '1.9', true);
	wp_enqueue_script('validate-language', plugins_url('tng_user_meta/js/additional-methods.js'), 'jquery', '1.9', true);
	wp_enqueue_script('qaptcha', plugins_url('tng_user_meta/js/QapTcha.jquery.js'), 'jquery', '', true);
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script('qaptcha-ui-touch', plugins_url('tng_user_meta/js/jquery.ui.touch.js'), 'jquery', '', true);
}
?>