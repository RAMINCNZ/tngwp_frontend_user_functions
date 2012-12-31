<?php
	require_once('../../../wp-load.php');
//	$_POST['userlogin'] = "HeatherF";
    $userlogin = $_POST['userlogin'];
    if ( username_exists( $userlogin ) )
		echo "no";
    else
		echo "yes";
?>