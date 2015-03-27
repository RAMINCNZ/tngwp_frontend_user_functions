<?php
	require_once('../../../wp-load.php');
	$useremail = $_POST['user_email'];
    if ( email_exists( $useremail ) )
		echo "no";
    else
		echo "yes";
?>