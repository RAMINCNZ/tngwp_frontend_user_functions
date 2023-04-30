<?php
//This will become my frontend profile portion of 
//the TNG Profile Addon
require_once(ABSPATH.'/wp-admin/includes/user.php');
require_once(ABSPATH.'/wp-includes/pluggable.php');
global $current_user, $user_id, $error, $profileuser, $nonce;

function tngwp_frontend_profile(){
ob_start();
//If user isn't logged in, display message and login form
if ( !is_user_logged_in() ) {
	echo '<p class="warning">';
	_e('You must be logged in to edit your profile.', 'profile');
	echo '</p><!-- .warning -->';
	echo '<p>&nbsp;&nbsp;</p>';
	echo '<div style="width: 50%; margin: 0 auto;">';
	echo user_meta_login_form('form');
	echo '</div>';
}
else {

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$profileuser = get_userdata($user_id);
//$wp_http_referer = wp_referer_field( );
wp_create_nonce('update_user_'.$user_id);

?>
		<script type="text/javascript">	
		jQuery(document).ready(function(){
			function doesPasswordFieldsMatch(values){
				if(values.pass1 == values.pass2)
					return {valid:true}
				else
					return {valid:false, message:'Passwords do not match'}
			}
			jQuery('#pass1').valid8({
				'regularExpressions': [
					{ expression: /(?=.{7,})/, errormessage: 'Minimum length is 7' }
				]
			});
			jQuery('#pass2').valid8({
				'jsFunctions': [
					{ function: doesPasswordFieldsMatch, values: function(){
							return { pass1: jQuery('#pass1').val(), pass2: jQuery('#pass2').val() }
						}
					}
				]
			});

		});
		function pwdStrength(password)
			{
				var desc = new Array();
				desc[0] = "Very Weak";
				desc[1] = "Weak";
				desc[2] = "Better";
				desc[3] = "Medium";
				desc[4] = "Strong";
				desc[5] = "Strongest";
				var score   = 0;
				//if password bigger than 6 give 1 point
				if (password.length > 6) score++;
				//if password has both lower and uppercase characters give 1 point      
				if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
				//if password has at least one number give 1 point
				if (password.match(/\d{1,2}/)) score++;
				//if password has 1-2 special caracther give 1 point
				if ( password.match(/\!\$%&\*\?{1,2}/) ) score++;
				//if password bigger than 9 give another 1 point
				if (password.length > 9) score++;
					document.getElementById("passwordDescription").innerHTML = desc[score];
					document.getElementById("passwordStrength").className = "strength" + score;
			}
	</script>

<?php if ( !'IS_PROFILE_PAGE' && is_super_admin( $profileuser->ID ) && current_user_can( 'manage_network_options' ) ) { ?>
	<div class="updated"><p><strong><?php _e('Important:'); ?></strong> <?php _e('This user has super admin privileges.'); ?></p></div>
<?php } ?>
<?php if ( isset($_GET['update_user']) ) { ?>
<div id="message" class="updated">
	<p><strong><?php echo _e('Profile updated.'); ?></strong></p>
</div>
<?php } ?>

<?php
$errors = array();
if (count($errors) > 0) {
	echo '<ul>';
	foreach ($errors as $error) { echo '<li class"error">'.$error.'</li>'; }
	echo '</ul>';
} ?>

<form id="your-profile" action="<?php do_action('update_user'); ?>" method="post">
<?php wp_nonce_field('update_user_'.$user_id) ?>

<input type="hidden" name="from" value="profile" />
<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />


<h3><?php _e('Name') ?></h3>

<table class="form-table">
	<tr>
		<td class="name"><label for="user_login"><?php _e('Username'); ?></label>
		<input type="text" name="user_login" id="user_login" value="<?php echo esc_attr($profileuser->user_login); ?>" disabled="disabled" class="regular-text" /> <br /><span class="description"><?php _e('Usernames cannot be changed.'); ?></span></td>
		<td class="name"><label for="nickname"><?php _e('Nickname'); ?> <span class="required"><?php _e('(required)'); ?></span></label>
		<input type="text" name="nickname" id="nickname" value="<?php echo esc_attr($profileuser->nickname) ?>" class="regular-text" /><br /><span class="description"><?php _e('Default: FirstName LastName. Field cannot be blank.'); ?></span></td>
	</tr>

<?php
if ( is_multisite() && is_network_admin() && ! IS_PROFILE_PAGE && current_user_can( 'manage_network_options' ) && !isset($super_admins) ) { ?>
	<tr>
		<td class="name"><label for="role"><?php _e('Super Admin'); ?></label>
		<?php if ( $profileuser->user_email != get_site_option( 'admin_email' ) ) : ?>
		<p><label><input type="checkbox" id="super_admin" name="super_admin"<?php checked( is_super_admin( $profileuser->ID ) ); ?> /> <?php _e( 'Grant this user super admin privileges for the Network.' ); ?></label></p>
		<?php else : ?>
		<p><?php _e( 'Super admin privileges cannot be removed because this user has the network admin email.' ); ?></p>
		<?php endif; ?>
		</td>
	</tr>
<?php } ?>

	<tr>
		<td class="name"><label for="first_name"><?php _e('First Name') ?></label>
		<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($profileuser->first_name) ?>" class="regular-text" /></td>
		<td class="name"><label for="last_name"><?php _e('Last Name') ?></label>
		<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($profileuser->last_name) ?>" class="regular-text" /></td>
	</tr>

	<tr>
		<td class="name"><label for="display_name"><?php _e('Display name publicly as') ?></label>
			<select name="display_name" id="display_name" style="width: 75%;">
			<?php
				$public_display = array();
				$public_display['display_nickname']  = $profileuser->nickname;
				$public_display['display_username']  = $profileuser->user_login;

				if ( !empty($profileuser->first_name) )
					$public_display['display_firstname'] = $profileuser->first_name;

				if ( !empty($profileuser->last_name) )
					$public_display['display_lastname'] = $profileuser->last_name;

				if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
					$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
					$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
				}

				if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
					$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

				$public_display = array_map( 'trim', $public_display );
				$public_display = array_unique( $public_display );

				foreach ( $public_display as $id => $item ) {
			?>
				<option <?php selected( $profileuser->display_name, $item ); ?>><?php echo $item; ?></option>
			<?php
				} 
			?>
			</select>
		</td>
		<td class="name">&nbsp;</td>
	</tr>
</table><!-- End Name Section -->
<p>&nbsp;</p>
<h3><?php _e('Contact Info') ?></h3>

<table class="form-table">
<tr>
	<th><label for="email"><?php _e('E-mail'); ?> <span class="required"><?php _e('(required)'); ?></span></label></th>
	<td><input type="text" name="email" id="email" value="<?php echo esc_attr($profileuser->user_email) ?>" class="regular-text" />
	<?php
	$new_email = get_option( $current_user->ID . '_new_email' );
	if ( $new_email && $new_email != $current_user->user_email ) : ?>
	<div class="updated inline">
	<p><?php printf( __('There is a pending change of your e-mail to <code>%1$s</code>. <a href="%2$s">Cancel</a>'), $new_email['newemail'], esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ) ) ); ?></p>
	</div>
	<?php endif; ?>
	</td>
</tr>

<tr>
	<th><label for="url"><?php _e('Website') ?></label></th>
	<td><input type="text" name="url" id="url" value="<?php echo esc_attr($profileuser->user_url) ?>" class="regular-text code" /></td>
</tr>

<?php
	foreach (_wp_get_user_contactmethods( $profileuser ) as $name => $desc) {
?>
<tr>
	<th><label for="<?php echo $name; ?>"><?php echo apply_filters('user_'.$name.'_label', $desc); ?></label></th>
	<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr($profileuser->$name) ?>" class="regular-text" /></td>
</tr>
<?php
	}
?>
</table><!-- End Contact Info -->
<p>&nbsp;</p>
<h3><?php _e("Your Address Information"); ?></h3>

<table class="form-table">
<?php
	foreach(get_user_address_profile_list($profileuser) as $key => $value) {
?>
<tr>
	<th>
		<label for="<?php echo $key; ?>"><?php _e($value); ?></label>
	</th>
	<td>
		<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( get_user_meta( $profileuser->ID, $key, $value ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter your $value."); ?></span>
	</td>
</tr>
<?php
	}
?>

</table><!--End Adress Info -->

<p>&nbsp;</p>
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
		<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( get_user_meta( $profileuser->ID, $key, $value ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter your $value."); ?></span>
	</td>
</tr>
<?php
	}
?>
</table><!--End Relationship Info -->

<p>&nbsp;</p>
<h3><?php _e('About Yourself'); ?></h3>

<table class="form-table">
<tr>
	<th><label for="description"><?php _e('Biographical Info'); ?></label></th>
	<td><textarea name="description" id="description" rows="5" cols="50"><?php echo $profileuser->description; // textarea_escaped ?></textarea><br />
	<span class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></span></td>
</tr>

<?php
$show_password_fields = apply_filters('show_password_fields', true, $profileuser);
if ( $show_password_fields ) :
?>
<tr id="password">
	<th><label for="pass1"><?php _e('New Password'); ?></label></th>
	<td><input type="password" name="pass1" id="pass1" size="20" value="" autocomplete="off" onkeyup="pwdStrength(this.value)" /><br /><span class="description"><?php _e("If you would like to change the password, type a new one. Otherwise leave this blank."); ?></span><br style="clear:left;" />
	<div id="passwordDescription">Password not entered</div>
	<div id="passwordStrength" class="strength0"></div>
	<div class="required2" style="clear:left;"><?php _e('Hint: The password must be at least seven characters long and include upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).'); ?></div>
	<br />
	<input type="password" name="pass2" id="pass2" size="20" value="" autocomplete="off" /><br /><span class="description"><?php _e("Type your new password again."); ?></span><br style="clear:left;" />
	</td>
</tr>
<?php endif; ?>
</table> <!-- End Bio and Password section -->
	<p class="form-submit">
		<?php //echo $wp_http_referer; ?>
		<input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update Your Profile', 'profile'); ?>" />
		<?php wp_nonce_field( 'update-user', $user_id ) ?>
		<input name="action" type="hidden" id="action" value="update-user" />
	</p><!-- .form-submit -->

</form><!-- #adduser -->
<?php
	$form = ob_get_contents();
	ob_end_clean();
	return $form;
}
}
add_shortcode('frontend_profile', 'tngwp_frontend_profile');

/*******************************************
Process changes to profile
*******************************************/

$changesSaved = 'no';
$changesSavedNoMatchingPass = 'no';
$changesSavedNoPass = 'no';

/* If profile was saved, update profile. */
function tngwp_frontend_profile_update_user(){
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		if ( empty( $_POST['nickname'] ) )
			$errors['nickname'] = _e('This field cannot be empty. Please choose a nickname.', 'profile');
		
		$pass1 = '';
		$pass2 = '';
		if ( isset( $_POST['pass1'] ) ) {
			$pass1 = trim( $_POST['pass1'] );
		}
		if ( isset( $_POST['pass2'] ) ) {
			$pass2 = trim( $_POST['pass2'] );
		}
		// Checking the password has been typed twice the same.
		if ( ( $update || !empty( $pass1 ) ) && $pass1 != $pass2 ) {
			$errors['pass'] = _e('Passwords do not match. Please enter the same password in both password fields.', 'profile');
		}

		if ( ! empty( $pass1 ) ) {
			$user_pass = $pass1;
		}
			
		$userdata = array (
			'ID'			=> $user_id,
			'user_pass'		=> $pass1,
			'user_url'		=> $_POST['url'],
			'user_email'	=> $_POST['email'],
			'display_name'	=> $_POST['display_name'],
			'nickname'		=> $_POST['nickname'],
			'first_name'	=> $_POST['first_name'],
			'last_name'		=> $_POST['last_name'],
			'description'	=> $_POST['description'],
			'facebook'		=> $_POST['facebook'],
			'twitter'		=> $_POST['twitter'],
			'linkedin'		=> $_POST['linkedin'],
			'phone'			=> $_POST['phone'],
			'address'		=> $_POST['address'],
			'city'			=> $_POST['city'],
			'state_prov'	=> $_POST['state_prov'],
			'postalcode'	=> $_POST['postalcode'],
			'country'		=> $_POST['country'],
			'relative'		=> $_POST['relative'],
			'relationahip'	=> $_POST['relationahip']
		);
		wp_update_user($userdata);
		
		foreach(get_user_address_profile_list() as $key => $value) {
			update_user_meta( $user_id, $key, $_POST[$key] );
		}
		foreach(get_user_relationship_profile_list() as $key => $value) {
			update_user_meta( $user_id, $key, $_POST[$key] );
			}
	}
}
add_action('update_user', 'tngwp_frontend_profile_update_user');
?>