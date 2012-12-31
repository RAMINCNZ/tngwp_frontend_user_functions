<?php
/************************************************
Registration Form Handler for TNG Registration
************************************************/
require_once('../../../wp-load.php');
if ((isset($_POST['submit']))){
extract($_POST);
//Grab the posted variables
$relative = stripslashes($_POST['relative']);
$relation = stripslashes($_POST['relation']);
$whom = $_POST['whom'];
$personID = $_POST['newid'];

//parents
$father_firstname = stripslashes($_POST['father_firstname']);
$father_lastname = stripslashes($_POST['father_lastname']);
$father_birthdate = stripslashes($_POST['father_birthdate']);
$mother_firstname = stripslashes($_POST['mother_firstname']);
$mother_maidenname = stripslashes($_POST['mother_maidenname']);
$mother_birthdate = stripslashes($_POST['mother_birthdate']);
$parents_mar_date = stripslashes($_POST['parents_mar_date']);
//grandparents
$grandfather_firstname = stripslashes($_POST['grandfather_firstname']);
$grandfather_lastname = stripslashes($_POST['grandfather_lastname']);
$grandfather_birthdate = stripslashes($_POST['grandfather_birthdate']);
$grandmother_firstname = stripslashes($_POST['grandmother_firstname']);
$grandmother_maidenname = stripslashes( $_POST['grandmother_maidenname']);
$grandmother_birthdate = stripslashes($_POST['grandmother_birthdate']);
$grandparents_mar_date = stripslashes($_POST['grandparents_mar_date']);
//great-grandparents
$gr_grandfather_firstname = stripslashes($_POST['gr_grandfather_firstname']);
$gr_grandfather_lastname = stripslashes($_POST['gr_grandfather_lastname']);
$gr_grandfather_birthdate = stripslashes($_POST['gr_grandfather_birthdate']);
$gr_grandmother_firstname = stripslashes($_POST['gr_grandmother_firstname']);
$gr_grandmother_maidenname = stripslashes($_POST['gr_grandmother_maidenname']);
$gr_grandmother_birthdate = stripslashes($_POST['gr_grandmother_birthdate']);
$gr_grandparents_mar_date = stripslashes($_POST['gr_grandparents_mar_date']);

//Spouse
$spouse_firstname = stripslashes($_POST['spouse_firstname']);
$spouse_lastname = stripslashes($_POST['spouse_lastname']);
$spouse_birthdate = stripslashes($_POST['spouse_birthdate']);
$spouse_birthplace = stripslashes($_POST['spouse_birthplace']);
$spouse_mar_date = stripslashes($_POST['spouse_mar_date']);

//self
$first_name =  stripslashes($_POST['first_name']);
$last_name = stripslashes($_POST['last_name']);
$real_name = $_POST['first_name']." ".$_POST['last_name'];
$birthdate = stripslashes($_POST['birthdate']);
$birthplace = stripslashes($_POST['birthplace']);
$telephone = stripslashes($_POST['telephone']);
$address = stripslashes($_POST['address']);
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
$notes = stripslashes($_POST['notes']);

// Process form for mailing
$sendto_email = get_option('admin_email');
$admin_mess = "Congratulations, TNG Admin! A new user joined your site!\n\n";
$date = date("m-d-y, h:m");

// Person's information:
$info =
	$real_name." has provided the following information:\n\n".
	"My Full Name is ".$first_name." ".$last_name."\n".
	"I was born on ".$birthdate." in ".$birthplace.".\n".
	"My Address is:\n".
	"     ".$address."\n     ".
	$city.", ".$state_prov."  ".$postalcode." ".$country."\n\n".
	"My phone number is ".$telephone." and my Email is ".$user_email."\n\n".
	"My Username is: ".$user_login.".\n\n Full sentence of relationship: ".$relative." (".$personID.") is ".$whom." ".$relation.".";
$admin_mess .= $info;

//If this is about a spouse
$wspouse = '';
if (($whom == "Spouse") || ($relation == "Spouse")) {
	$wspouse = "\n\n".
		"My Spouse's information is:\n".
		"My Spouse's Name: ".$spouse_firstname." ".$spouse_lastname."\n".
		"My Spouse's birthdate: ".$spouse_birthdate."\n".
		"My Spouse's birthplace: ".$spouse_birthplace."\n".
		"Our marriage date: ".$spouse_mar_date."\n";
} $admin_mess .= $wspouse;



// this part deals with grandfather/grandmother
$message = '';
if(($relation == "Grandmother") || ($relation == "Grandfather") || ($relation == "Sister") || ($relation == "Brother")){
	$message = "\n\nFather's Name: ".$father_firstname." ".$father_lastname."\n".
		"Father's Birthdate: ".$father_birthdate."\n".
		"Mother's Name: ".$mother_firstname." ".$mother_maidenname."\n".
		"Mother's Birthdate: ".$mother_birthdate."\n".
		"Parent's Marriage Date: ".$parents_mar_date."\n\n";	
} $admin_mess .= $message;

// this part deals with sister or brother of mother/father
$message1 = '';
if(($relation == "FatherSister") || ($relation == "MotherSister") || ($relation == "FatherBrother") || ($relation == "MotherBrother")){
	$message1 = "\n\nFather's Name: ".$father_firstname." ".$father_lastname."\n".
		"Father's Birthdate: ".$father_birthdate."\n".
		"Mother's Name: ".$mother_firstname." ".$mother_maidenname."\n".
		"Mother's Birthdate: ".$mother_birthdate."\n".
		"Parent's Marriage Date: ".$parents_mar_date."\n\n";
} $admin_mess .= $message1;

// this part deals with great grandfather/great grandmother
$message2 = '';
if (($relation == "GrGrandmother") || ($relation == "GrGrandfather")){
	$message2 = "\n\n".
		"Father's Name: ".$father_firstname." ".$father_lastname."\n".
		"Father's Birthdate: ".$father_birthdate."\n".
		"Mother's Name: ".$mother_firstname." ".$mother_maidenname."\n\n".
		"Mother's Birthdate: ".$mother_birthdate."\n".
		"Grandfather's Name: ".$grandfather_firstname." ".$grandfather_lastname."\n".
		"Grandfather's Birthdate: ".$grandfather_birthdate."\n".
		"Grandmother's Name: ".$grandmother_firstname." ".$grandmother_maidenname."\n".
		"Grandmother's Birthdate: ".$grandmother_birthdate."\n";
		"Grandparent's Marriage Date: ".$grandparents_mar_date."\n\n";
} $admin_mess .= $message2;

// this part deals with 2nd great grandfather/2nd great grandmother
$message3 = '';
if (($relation == "2ndGrGrandmother") || ($relation == "2ndGrGrandfather")){
	$message3 = "\n\n".
		"Father's Name: ".$father_firstname." ".$father_lastname."\n".
		"Father's Birthdate: ".$father_birthdate."\n".
		"Mother's Name: ".$mother_firstname." ".$mother_maidenname."\n".
		"Mother's Birthdate: ".$mother_birthdate."\n".
		"Parent's Marriage Date: ".$parents_mar_date."\n\n".
		"Grandfather's Name: ".$grandfather_firstname." ".$grandfather_lastname."\n".
		"Grandfather's Birthdate: ".$grandfather_birthdate."\n".
		"Grandmother's Name: ".$grandmother_firstname." ".$grandmother_maidenname."\n".
		"Grandmother's Birthdate: ".$grandmother_birthdate."\n".
		"Grandparent's Marriage Date: ".$grandparents_mar_date."\n\n".
		"Great Grandfather's Name: ".$gr_grandfather_firstname." ".$gr_grandfather_lastname."\n".
		"Great Grandfather's Birthdate: ".$gr_grandfather_birthdate."\n".
		"Great Grandmother's Name: ".$gr_grandmother_firstname." ".$gr_grandmother_maidenname."\n".
		"Great Grandmother's Birthdate: ".$gr_grandmother_birthdate."\n".
		"Great Grandparent's Marriage Date: ".$gr_grandparents_mar_date."\n\n";
} $admin_mess .= $message3;

//email new registration information to Admin
$senders_email = preg_replace("/[^a-zA-Z0-9s.@-]/", " ", $user_email);
$senders_name = preg_replace("/[^a-zA-Z0-9s]/", " ", $real_name);
$mail_subject = "New User Registration!";
$headers = "From: $senders_name <$senders_email> \r\n";
$mail_message = $date."\n".	$admin_mess."\n";
@mail($sendto_email, $mail_subject, $mail_message, $headers);

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
@mail( $user_email, $defa, $usermessage, $mailheaders2 );

//send user to success page
	$host  = $_SERVER['HTTP_HOST'];
	$post_id = get_option('user_meta_success_page');
	$post = get_post($post_id);
	$page = $post->post_name;
	wp_redirect( get_permalink( $post_id ) );
	
}

// Add the new user to WordPress and TNG User Tables
$userdata = array (
	'user_login' => $user_login,
	'user_pass' => $user_pass,
	'user_nicename' => $first_name.' '.$last_name,
	'user_email' => $user_email,
	'user_url' => $user_url,
	'nickname' => $first_name.' '.$last_name,
	'first_name' => $first_name,
	'last_name' => $last_name,
	'telephone' => $telephone,
	'address' => $address,
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
	update_usermeta( $user_id, $key, $_POST[$key] );
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
}
/*
//Last do the database stuff for TNG
$tng_folder = get_option('mbtng_path');
chdir($tng_folder);
include('begin.php');
include_once($cms['tngpath'] . "genlib.php");
include($cms['tngpath'] . "getlang.php");
include_once($cms['tngpath'] . "{$mylanguage}/text.php");
mbtng_db_connect() or die('Could not connect: ' . mysql_error());

$sql = " 
INSERT INTO tng_newinfo ( related, relation, whom, father_firstname, father_lastname, father_birthdate, mother_firstname, mother_maidenname, mother_birthdate, parents_mar_date, grandfather_firstname, grandfather_lastname, grandfather_birthdate, grandmother_firstname, grandmother_maidenname, grandmother_birthdate, grandparents_mar_date, gr_grandfather_firstname, gr_grandfather_lastname, gr_grandfather_birthdate, gr_grandmother_firstname, gr_grandmother_maidenname, gr_grandmother_birthdate, gr_grandparents_mar_date, spouse_firstname, spouse_lastname, spouse_birthdate, spouse_birthplace, spouse_mar_date, first_name, last_name, birthdate, birthplace )
VALUES ( '$relative',	'$relation', '$whom', '$father_firstname', '$father_lastname', '$father_birthdate', '$mother_firstname', '$mother_maidenname', 	'$mother_birthdate', '$parents_mar_date', '$grandfather_firstname', '$grandfather_lastname', '$grandfather_birthdate', '$grandmother_firstname', 	'$grandmother_maidenname', '$grandmother_birthdate', '$grandparents_mar_date', '$gr_grandfather_firstname', '$gr_grandfather_lastname', '$gr_grandfather_birthdate', '$gr_grandmother_firstname', '$gr_grandmother_maidenname', '$gr_grandmother_birthdate', '$gr_grandparents_mar_date', '$spouse_firstname', '$spouse_lastname', '$spouse_birthdate', '$spouse_birthplace', '$spouse_mar_date', '$first_name', '$last_name', '$birthdate', '$birthplace' )
";
mysql_query($sql);
mbtng_close_tng_table ();
*/
?>