<?php
/************************************************
Registration Form Handler for TNG Registration
************************************************/
require('../../../../wp-load.php');
global $wpdb;
//Begin processing form data
if ((isset($_POST['submit']))) {
	extract($_POST);
	//Grab the posted variables
	$first_name = ($_POST['first_name']);
	$last_name = stripslashes($_POST['last_name']);
	$city = stripslashes($_POST['city']);
	$state_prov = stripslashes($_POST['state_prov']);
	$postalcode = stripslashes($_POST['postalcode']);
	$country = stripslashes($_POST['country']);
	$user_url = stripslashes($_POST['user_url']);
	$user_login = stripslashes($_POST['userlogin']);
	$user_email = stripslashes($_POST['user_email']);
	$email = stripslashes($_POST['confirm_email']);
	$user_pass = stripslashes($_POST['user_pass']);
	$pass = md5($user_pass);
	$mbtng_tree = stripslashes($_POST['tree']);
	$interest = stripslashes($_POST['interest']);
	$relationship = stripslashes($_POST['relationship']);
	$comments = stripslashes($_POST['comments']);

	//Process emails
	$sendto_email = get_option('admin_email');
	$admin_mess = "Congratulations, Administrator! A new user joined your site.\n\n";
	$date = date("m-d-y, h:m");
	
	$info =
		$real_name." has provided the following information:\n\n".
		"My Full Name is ".$first_name." ".$last_name."\n".
		"My Location is:\n".
		$city.", ".$state_prov."  ".$postalcode." ".$country."\n\n".
		"My Email is ".$user_email."\n\n".
		"My Username is: ".$user_login." and my Password is: ".$user_pass.".\n\n
		Additional notes:\n
		Interest: ".$interest."\n
		Relationship: ".$relationship."\n
		Comments: ".$comments;
	$admin_mess .= $info;

	//email new registration information to Admin
	$senders_email = preg_replace("/[^a-zA-Z0-9s.@-]/", " ", $user_email);
	$senders_name = preg_replace("/[^a-zA-Z0-9s]/", " ", $real_name);
	$mail_subject = "New User Registration!";
	$mailheaders = "From: $senders_name <$senders_email> \r\n";
	$mail_message = $date ."\n". $admin_mess."\n";
	mail($sendto_email, $mail_subject, $mail_message, $mailheaders);

	$usermessage = "Hello ".$real_name.",\n\n
	Your request for a user account has been received.\n\n
	Your registered username and password are:\n
	\t\t\t\t User Name: ".$user_login."\n
	\t\t\t\t Password:  ".$user_pass."\n\n
	Your account will remain inactive until it has been reviewed by the site administrator. You will be notified by email when your login is ready for use.\n\n
	Thank you for registering with us!\n";
	$mailheaders2 = "From: ".get_option('blogname')." <$sendto_email> \r\n";
	$defa = "Registration Information for ".get_option('blogname');
	//email message to new user
	mail( $user_email, $defa, $usermessage, $mailheaders2 );
}

$wpdb->query( $wpdb->prepare(
    "INSERT INTO $users_table (description, username, password, password_type, role, allow_living, realname, email, dt_registered) VALUES ( %s, %s, %s, %s, %s, %d, %s, %s, %s )",
    array(
        $real_name,
        $user_login,
        $pass,
		'md5',
		'guest',
		-1,
		$real_name,
		$user_email,
		$date
    )
));
$wpdb->flush();

// Add the new user to WordPress
$userdata = array (
	'user_login' => $user_login,
	'user_pass' => $user_pass,
	'user_nicename' => $first_name.' '.$last_name,
	'user_email' => $user_email,
	'user_url' => $user_url,
	'nickname' => $first_name.' '.$last_name,
	'first_name' => $first_name,
	'last_name' => $last_name,
	'city' => $city,
	'state_prov' => $state_prov,
	'postalcode' => $postalcode,
	'country' => $country,
	'comment_shortcuts' => 1,
	'rich_editing' => 1,
	'show_admin_bar_front' => 0
);
$new_user_id = tng_meta_insert_user($userdata);

//my function to create new WordPress user
function tng_meta_insert_user($userdata) {
	global $wpdb;

	extract($userdata, EXTR_SKIP);

	// Are we updating or creating?
	if ( !empty($ID) ) {
		$ID = (int) $ID;
		$update = true;
		$old_user_data = WP_User::get_data_by( 'id', $ID );
	} else {
		$update = false;
		// Hash the password
		$user_pass = wp_hash_password($user_pass);
	}

	$user_login = sanitize_user($user_login, true);
	$user_login = apply_filters('pre_user_login', $user_login);

	//Remove any non-printable chars from the login string to see if we have ended up with an empty username
	$user_login = trim($user_login);

	if ( empty($user_login) )
		return new WP_Error('empty_user_login', __('Cannot create a user with an empty login name.') );

	if ( !$update && username_exists( $user_login ) )
		return new WP_Error('existing_user_login', __('This username is already registered.') );

	if ( empty($user_nicename) )
		$user_nicename = sanitize_title( $user_login );
	$user_nicename = apply_filters('pre_user_nicename', $user_nicename);

	if ( empty($user_url) )
		$user_url = '';
	$user_url = apply_filters('pre_user_url', $user_url);

	if ( empty($user_email) )
		$user_email = '';
	$user_email = apply_filters('pre_user_email', $user_email);

	if ( !$update && ! defined( 'WP_IMPORTING' ) && email_exists($user_email) )
		return new WP_Error('existing_user_email', __('This email address is already registered.') );

	if ( empty($display_name) )
		$display_name = $user_login;
	$display_name = apply_filters('pre_user_display_name', $display_name);

	if ( empty($nickname) )
		$nickname = $user_login;
	$nickname = apply_filters('pre_user_nickname', $nickname);

	if ( empty($first_name) )
		$first_name = '';
	$first_name = apply_filters('pre_user_first_name', $first_name);

	if ( empty($last_name) )
		$last_name = '';
	$last_name = apply_filters('pre_user_last_name', $last_name);

	if ( empty($description) )
		$description = '';
	$description = apply_filters('pre_user_description', $description);

	if ( empty($rich_editing) )
		$rich_editing = 'true';

	if ( empty($comment_shortcuts) )
		$comment_shortcuts = 'false';

	if ( empty($admin_color) )
		$admin_color = 'fresh';
	$admin_color = preg_replace('|[^a-z0-9 _.\-@]|i', '', $admin_color);

	if ( empty($use_ssl) )
		$use_ssl = 0;

	if ( empty($user_registered) )
		$user_registered = gmdate('Y-m-d H:i:s');

	if ( empty($show_admin_bar_front) )
		$show_admin_bar_front = 'false';

	$user_nicename_check = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_nicename = %s AND user_login != %s LIMIT 1" , $user_nicename, $user_login));

	if ( $user_nicename_check ) {
		$suffix = 2;
		while ($user_nicename_check) {
			$alt_user_nicename = $user_nicename . "-$suffix";
			$user_nicename_check = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->users WHERE user_nicename = %s AND user_login != %s LIMIT 1" , $alt_user_nicename, $user_login));
			$suffix++;
		}
		$user_nicename = $alt_user_nicename;
	}

	$data = compact( 'user_pass', 'user_email', 'user_url', 'user_nicename', 'display_name', 'user_registered' );
	$data = stripslashes_deep( $data );

	if ( $update ) {
		$wpdb->update( $wpdb->users, $data, compact( 'ID' ) );
		$user_id = (int) $ID;
	} else {
		$wpdb->insert( $wpdb->users, $data + compact( 'user_login' ) );
		$user_id = (int) $wpdb->insert_id;
	}

	$user = new WP_User( $user_id );

	foreach ( _get_additional_user_keys( $user ) as $key ) {
		if ( isset( $$key ) )
			update_user_meta( $user_id, $key, $$key );
	}

	foreach(get_user_address_profile_list() as $key => $value) {
	update_user_meta( $user_id, $key, $_POST[$key] );
	}

	if ( isset($role) )
		$user->set_role($role);
	elseif ( !$update )
		$user->set_role(get_option('default_role'));

	wp_cache_delete($user_id, 'users');
	wp_cache_delete($user_login, 'userlogins');

	if ( $update )
		do_action('profile_update', $user_id, $old_user_data);
	else
		do_action('user_register', $user_id);

	return $user_id;
	$wpdb->flush();
}

//send user to success page
	$host  = $_SERVER['HTTP_HOST'];
	$options = get_option('tngwp-frontend-user-functions-options');
	$page = $options['success_page'];
	$permalink = get_permalink( get_page_by_title( $page ) );
	wp_redirect( $permalink );
?>
