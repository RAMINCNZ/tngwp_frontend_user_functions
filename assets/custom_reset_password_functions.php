<?php
/*
* Added in version 4.0:
* Password reset function to create a shortcode to replace the custom password reset page.
*/
//Variables to be used as Global in the following functions.
global $wpdb, $lost_password, $reset_password, $login;
$myoptions = get_option('tngwp-frontend-user-functions-options');
$lostpassword_id = $myoptions['lost_password_page'];
$resetpassword_id = $myoptions['reset_password_page'];
$login_id = $myoptions['login_page'];
$lost_password = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $lostpassword_id ));
$reset_password = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $resetpassword_id ));
$login = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $login_id ));

add_action('tngwp_lost_password', 'tngwp_lost_password_form');
function tngwp_lost_password_form( $pwdreset ) {
	global $reset_password, $user_ID, $wpdb;
	$resetpassword = get_permalink($reset_password);
	ob_start();
	if (!$user_ID) { //block logged in users

		if(isset($_GET['key']) && $_GET['action'] == "tngwp_reset_pwd") {
			$reset_key = $_GET['key'];
			$user_login = $_GET['login'];
			$user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));
			
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
			
			if(!empty($reset_key) && !empty($user_data)) {
				$new_password = wp_generate_password(15, false);
					//echo $new_password; exit();
					wp_set_password( $new_password, $user_data->ID );
					//mailing reset details to the user
				$message = __('Your new password for the account at:') . "\r\n\r\n";
				$message .= get_bloginfo('url') . "\r\n\r\n";
				$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
				$message .= sprintf(__('Password: %s'), $new_password) . "\r\n\r\n";
				$message .= __('You can now login with your new password at: ') . get_bloginfo('url'). "/" . $lost_password . "\r\n\r\n";
				
				if ( $message && !wp_mail($user_email, 'Requested Temporary Password', $message) ) {
					echo "<div class='error'>Email failed to send for some unknown reason</div>";
					exit();
				}
				else {
					$redirect_to = $lost_password."?action=reset_success";
					wp_safe_redirect($redirect_to);
					exit();
				}
			} 
			else exit('Not a Valid Key.');
			
		}
		//exit();

		if(isset($_POST['action']) && $_POST['action'] == "tngwp_pwd_reset"){
			if(empty($_POST['user_input'])) {
				echo "<div class='error'>Please enter your Username or E-mail address</div>";
				exit();
			}
			//We shall SQL escape the input
			$user_input = $wpdb->escape(trim($_POST['user_input']));
			
			if ( strpos($user_input, '@') ) {
				$user_data = get_user_by_email($user_input);
				if(empty($user_data)) { //delete the condition $user_data->caps[administrator] == 1, if you want to allow password reset for admins also
					echo "<div class='error'>Invalid E-mail address!</div>";
					exit();
				}
			}
			else {
				$user_data = get_userdatabylogin($user_input);
				if(empty($user_data)) { //delete the condition $user_data->caps[administrator] == 1, if you want to allow password reset for admins also
					echo "<div class='error'>Invalid Username!</div>";
					exit();
				}
			}
			
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
			
			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
			if(empty($key)) {
				//generate reset key
				$key = wp_generate_password(15, false);
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));	
			}
			
			//mailing reset details to the user
			$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
			$message .= get_bloginfo('url') . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
			$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
			$message .= tngwp_validate_url() . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . "\r\n";
			
			if ( $message && !wp_mail($user_email, 'Password Reset Request', $message) ) 
			{
				echo '<div class="error">Email failed to send for some unknown reason.</div>';
				exit();
			}
			else {
				//echo "<div id='success'>We have just sent you an email with Password reset instructions.</div>";
				exit();
			}
			
		} else { //Display the form
		?>
			<form class="user_form" id="wp_pass_reset" action="" method="post">
				<p><label for="user_input">Please enter your user name or email:</label></p>
				<p><input type="text" class="text" name="user_input" value="" /></p>
				<input type="hidden" name="action" value="tngwp_pwd_reset" />
				<input type="hidden" name="tngwp_pwd_nonce" value="'.wp_create_nonce("tngwp_pwd_nonce").'" />
				<p><input type="submit" id="submitbtn" class="reset_password" name="submit" value="Reset Password" /></p>
			</form>
			<div id="result"></div> <!-- To hold validation results -->
					<div style="clear: both;"></div>
			<script type="text/javascript">  						
				jQuery("#wp_pass_reset").submit(function() {			
				jQuery('#result').html('<span class="loading">Validating...</span>').fadeIn();
				var input_data = jQuery('#wp_pass_reset').serialize();
				jQuery.ajax({
				type: "POST",
				//url:  "<?php echo $lostpassword; ?>",
				data: input_data,
				success: function(msg){
				jQuery('.loading').remove();
				jQuery('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
				jQuery('form').hide();
				jQuery('#success').html('<span class="loading">We have just sent you an email with Password reset instructions.</span>').fadeIn();
				}
				});
				return false;

				});
			</script>
			<?php
			return $form;
		}
	}
	$pwdreset = ob_get_contents();
	ob_end_clean();
	return $pwdreset;
}


function tngwp_validate_url() {
	global $post, $reset_password;
	$page_url = esc_url( get_permalink($reset_password ));
	$urlget = strpos($page_url, "?");
	if ($urlget === false) {
		$concate = "?";
	} else {
		$concate = "&";
	}
	return $page_url.$concate;
}