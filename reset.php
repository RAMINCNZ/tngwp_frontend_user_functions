<?php 
require_once('../../../wp-load.php');
get_header();
?>
<link rel="stylesheet" href="<?php echo plugins_url( 'tngwp_frontend_user_functions/assets/css/bootstrap.css' ); ?>" type="text/css" media="screen" />
<script src="<?php echo plugins_url( 'tngwp_frontend_user_functions/assets/js/bootstrap.js' ); ?>"></script>
<div id="primary" class="content-area col-sm-9">
	<main id="main" class="site-main" role="main" style="width: 550px; margin: 0 auto;">
		<h2>Password Reset Request</h2>
		<p style="font-size: 101%;">Your request to reset your password was successful and a temporary password has been emailed to you. Please note that this password IS <strong>temporary</strong>. Once you have been logged in, please go <em>immediately</em> to your profile page (under "user Functions" on the main menu) and change your password to a strong one. Be sure to update any password keeper application you use and/or save it in a secure location. Thank you.</p>
	</main>
</div>
<?php 
get_sidebar();
get_footer();
?>