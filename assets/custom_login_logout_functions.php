<?php
/*
* Added in version 4.0:
* Password reset function to create a shortcode to replace the custom password reset page.
*/
global $wpdb;
//Variables to be used as Global in the following functions.
$myoptions = get_option('tngwp-frontend-user-functions-options');
$profile_id = $myoptions['profile_page'];
$post_id1 = $myoptions['ancestor_lookup'];
$post_id2 = $myoptions['registration_form'];
$lostpassword_id = $myoptions['lost_password_page'];
$resetpassword_id = $myoptions['reset_password_page'];
$post1 = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $post_id1 ));
$post2 = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $post_id2 ));
$profile = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $profile_id ));
$lost_password = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $lostpassword_id ));
$reset_password = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='page'", $resetpassword_id ));

function user_meta_login_form( $item ) {
	global $tngusername, $wpdb, $post1, $post2, $profile, $lost_password;
	if($post1 != $post2) {$register = $post1;}
	else {$register = $post2;}
	$page = get_permalink($register);
	$profile = get_permalink($profile);
	$lostpassword = get_permalink($lost_password);
	ob_start();
	global $args, $item, $user_info, $current_user;
	if ( is_user_logged_in() ) {		
		$current_user = wp_get_current_user();
		if(empty($current_user->display_name)) {
			echo "<h4 style=\"text-align: center;\">Welcome ". $current_user->user_login ."!</h4>\n";
		} else {
			echo "<h4 style=\"text-align: center;\">Welcome ". $current_user->display_name ."!</h4>\n";
		}
		if ( current_user_can('administrator') ) echo "<h6 style=\"text-align: center;\"><a href=\"". get_admin_url() ."\">Site Admin</a> | ";
		if ( !(current_user_can('administrator')) ) echo "<h6 style=\"text-align: center;\"><a href=\"". $profile."\">User Profile</a> | ";
		wp_loginout(get_permalink())."</h6>";
		return $user_info;
	}
	else {
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
				<p><a href="'.$lostpassword.'" title="Lost Password">Lost Password?</a>&nbsp;<!--|&nbsp;<a href="'.get_permalink($register).'">Click to Register</a>--></p>
				' . apply_filters( 'login_form_bottom', '', $args ) . '
			</form>';
		return $form;
	}
	$item = ob_get_contents();
	ob_end_clean();
	return $item;
	
}

function tngwp_login_shortcode() {
	global $item;
		$current_user = '';
    if ( is_user_logged_in() ) { $item = $current_user; }
	echo user_meta_login_form( $item );
}
add_shortcode('tngwp_login_logout', 'tngwp_login_shortcode');

//Log In / Log Out Widget
class TNGLoginLogoutWidget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'TNGLoginLogoutWidget',
			'TNG User Login/Logout',
			array('description' => 'Displays the login form or user name and links to profile/logout' )
		);
	}

	public function widget($args, $instance) {
		$item = '';
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

		if (!empty($title))
		  echo $before_title . $title . $after_title;

		// WIDGET CODE GOES HERE
		$current_user = '';
		if ( is_user_logged_in() ) { $item = $current_user; }
		echo user_meta_login_form( $item );

		echo $after_widget;
	}

	public function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
		name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php esc_attr_e($title); ?>" /></label></p>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
 
}

add_action( 'widgets_init', 'tngwp_register_widgets' );
function tngwp_register_widgets() {
	register_widget( 'TNGLoginLogoutWidget' );
}


/* Lost password functions */
add_shortcode('tngwp_forgot_pwd_form', 'tngwp_forgot_pwd_form_callback');
function tngwp_forgot_pwd_form_callback() {
	ob_start();

	if (!is_user_logged_in()) {
		global $getPasswordError, $getPasswordSuccess;

		if (!empty($getPasswordError)) {
		?>
			<div class="alert alert-danger">
			<?php echo $getPasswordError; ?>
			</div>
		<?php } ?>

		<?php if (!empty($getPasswordSuccess)) { ?>
			<div class="alert alert-success">
			<?php echo $getPasswordSuccess; ?>
			</div>
		<?php } ?>
		<form method="post" class="wc-forgot-pwd-form">
		<br /><p>PLEASE NOTE: The password you receive in the email is temporary. After you login, please be sure to visit your profile page and update your password. Thank you.</p>
		<div class="forgot_pwd_form">
			<p class="log_user">
				<label for="user_login">Please enter your email address:</label>
				<?php $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : ''; ?>
				<input type="text" name="user_login" id="user_login" value="<?php echo $user_login; ?>" />
			</p>
			<p class="log_user">
				<?php
				ob_start();
				do_action('lostpassword_form');
				echo ob_get_clean();
				?>
				<?php wp_nonce_field('userGetPassword', 'formType'); ?>
				<button type="submit" class="get_new_password">Get New Password</button>
			</p>
		</div>
		</form>
	<?php
	}

	$forgot_pwd_form = ob_get_clean();
	return $forgot_pwd_form;
}

add_action('wp', 'tngwp_user_forgot_pwd_callback');
function tngwp_user_forgot_pwd_callback() {
	if (isset($_POST['formType']) && wp_verify_nonce($_POST['formType'], 'userGetPassword')) {
		global $getPasswordError, $getPasswordSuccess;

		$email = trim($_POST['user_login']);

		if (empty($email)) {
			$getPasswordError = '<strong>Error! </strong>Enter a e-mail address.';
		} else if (!is_email($email)) {
			$getPasswordError = '<strong>Error! </strong>Invalid e-mail address.';
		} else if (!email_exists($email)) {
			$getPasswordError = '<strong>Error! </strong>There is no user registered with that email address.';
		} else {
			// lets generate our new password
			$random_password = wp_generate_password(12, false);

			// Get user data by field and data, other field are ID, slug, slug and login
			$user = get_user_by('email', $email);

			$update_user = wp_update_user(array(
			'ID' => $user->ID,
			'user_pass' => $random_password
			));

			// if update user return true then lets send user an email containing the new password
			if ($update_user) {
				$to = $email;
				$subject = 'Your new password';
				$sender = get_bloginfo('name');

				$message = 'Your new password is: ' . $random_password . 'PLEASE NOTE: This password is temporary. Once you have logged in, please go to your profile page and update your password.';
				$headers = array('Content-Type: text/html; charset=UTF-8');

				$mail = wp_mail($to, $subject, $message, $headers);
				if ($mail) {
					$getPasswordSuccess = '<strong>Success! </strong>Check your email address for you new password.';
				}
			} else {
				$getPasswordError = '<strong>Error! </strong>Oops something went wrong.';
			}
		}
	}
}
?>