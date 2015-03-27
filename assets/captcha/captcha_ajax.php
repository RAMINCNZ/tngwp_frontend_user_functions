<?php
session_start();

if (!empty($_REQUEST['captcha'])) {
    if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
		echo "<div style='color:#cc0000;'>Please enter correct Captcha text</div>";
       
    } else {
    	echo "<div style='color:#006600;'>Captcha validation succcessful</div>";
		
    }

    $request_captcha = htmlspecialchars($_REQUEST['captcha']);

   // unset($_SESSION['captcha']);
}

?>