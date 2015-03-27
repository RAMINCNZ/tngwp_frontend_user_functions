<?php
//Start session and populated variables for logged-in users
add_action('init', 'StartSession', 1);
add_action('wp-login', 'StartSession', 3);
function StartSession() {

    //Check if current user is logged in WordPress
    if( is_user_logged_in() ) {

        if(!session_id()) {
            session_start();
        }
        //You may need some check here of inside tngwp_processlogin() to avoid running
        //the same process over and over again if it is not needed
        tngwp_processlogin();

    }
}

//Destroy session if user logout or login in another account
function tngwp_session_destroy() {
	$tng_folder = get_option('mbtng_path');
	chdir($tng_folder);
	include("config.php");
	if ($_SESSION['currentuser'] != '')
		include('logout.php');
    // destroy the session 
    //session_destroy();
}
add_action( 'wp_logout', 'tngwp_session_destroy', 3 );

function tngwp_processlogin() {
    global $wpdb, $current_user, $tng_path;
	$tng_path = get_option('mbtng_path');
    get_currentuserinfo();
    $username = $current_user->user_login;
    $tng_folder = $tng_path;
    include($tng_folder.'config.php');
    include($tng_folder."subroot.php");
    $session_language = $_SESSION['session_language'];
    $session_charset = $_SESSION['session_charset'];
    $languages_path = "languages/";
    include($tng_folder.'getlang.php');
	if(isset($sitever))
		setcookie("tng_siteversion", $sitever, time()+31536000, "/");
	else if(isset($_COOKIE['tng_siteversion']))
	$sitever = $_COOKIE['tng_siteversion'];
	
	include_once($tng_folder."siteversion.php");
	if(!$sitever)
		$sitever = getSiteVersion();
	include_once($tng_folder.'globallib.php');
    $tng_user = $wpdb->get_row("
                SELECT * 
                FROM tng_users 
                WHERE username = '$username'", 
                ARRAY_A
            );
    $newdate = date ("Y-m-d H:i:s", time() + ( 3600 * $time_offset ) );
    $userid = $tng_user['userID'];
    $wpdb->update( 
        'tng_users', 
        array( 'lastlogin' => $newdate ), 
        array( 'userID' => $userid ), 
        array( '%s' ), 
        array( '%d' )
    );

    $newroot = ereg_replace( "/", "", $rootpath );
    $newroot = ereg_replace( " ", "", $newroot );
    $newroot = ereg_replace( "\.", "", $newroot );
    setcookie("tnguser_$newroot", $tng_user['username'], time()+31536000, "/");
    setcookie("tngpass_$newroot", $tng_user['password'], time()+31536000, "/");
    setcookie("tngpasstype_$newroot", $tng_user['password_type'], time()+31536000, "/");

    $_SESSION['currentuser'] = $tng_user['username'];
    if ( $tng_user['role']=='admin' ) {
		$home_url = $continue ? "admin_main.php" : "admin.php";
		$login_url = getURL("admin_login", 1);
		$dest_url = $_SESSION['destinationpage8'] && $continue ? $_SESSION['destinationpage8'] : $home_url;
        $_SESSION['allow_admin'] = 1;
        setcookie("tngloggedin_$newroot", "1", 0, "/");
    }
    else { $_SESSION['allow_admin'] = 0; }
    $logged_in = $_SESSION['logged_in'] = 1;
    $allow_edit = $_SESSION['allow_edit'] = ($tng_user['allow_add'] == 1 ? 1 : 0);
    $allow_add = $_SESSION['allow_add'] = ($tng_user['allow_add'] == 1 ? 1 : 0);
    $tentative_edit = $_SESSION['tentative_edit'] = $tng_user['tentative_edit'];
    $allow_delete = $_SESSION['allow_delete'] = ($tng_user['allow_delete'] == 1 ? 1 : 0);

    $allow_media_edit = $_SESSION['allow_media_edit'] = ($tng_user['allow_edit'] ? 1 : 0);
    $allow_media_add = $_SESSION['allow_media_add'] = ($tng_user['allow_add'] ? 1 : 0);
    $allow_media_delete = $_SESSION['allow_media_delete'] = ($tng_user['allow_delete'] ? 1 : 0);

    $_SESSION['mygedcom'] = $tng_user['mygedcom'];
    $_SESSION['mypersonID'] = $tng_user['personID'];

    $allow_living = $_SESSION['allow_living'] = $tng_user['allow_living'];
    $allow_private = $_SESSION['allow_private'] = $tng_user['allow_private'];

    $allow_ged = $_SESSION['allow_ged'] = $tng_user['allow_ged'];
    $allow_pdf = $_SESSION['allow_pdf'] = $tng_user['allow_pdf'];
    $allow_profile = $_SESSION['allow_profile'] = $tng_user['allow_profile'];

    $allow_lds = $_SESSION['allow_lds'] = $tng_user['allow_lds'];

    $assignedtree = $_SESSION['assignedtree'] = $tng_user['gedcom'];
    $assignedbranch = $_SESSION['assignedbranch'] = $tng_user['branch'];
    $currentuser = $tng_user['username'];
    $_SESSION['currentuser'] = $currentuser;
    $currentuserdesc = $_SESSION['currentuserdesc'] = $tng_user['description'];
    $session_rp = $_SESSION['session_rp'] = $rootpath;

    $wpdb->flush();
    return $tngusername;
 }

// Deletes TNG users when deleted from Wordpress
function tngwp_delete_user($user_ID) {
	global $wpdb, $user_meta;
	$user_meta = get_userdata( $user_ID );
	$tng_user = $user_meta->user_login;
	$wpdb->delete( 'tng_users', array( 'username' => $tng_user ), array( '%s' ) );
}
add_action( 'delete_user', 'tngwp_delete_user', 10 );
?>