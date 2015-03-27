<?php
require_once('../../../wp-load.php');
get_header();
//echo do_shortcode(['tngwp_login_logout']);
function tngwp_login_form( $item ) {
	$post_id1 = get_option('user_meta_register_start_page');
	$post_id2 = get_option('user_meta_registration_page');
	if($post_id1 != $post_id2) {$post_id = $post_id1;}
	else {$post_id = $post_id2;}
	ob_start();
	if ( is_user_logged_in() ) {
		
		$current_user = wp_get_current_user();
		echo "<h4 style=\"text-align: center;\">Welcome ". $current_user->display_name ."!</h4>\n";
		if ( current_user_can('administrator') ) echo "<h6 style=\"text-align: center;\"><a href=\"". get_admin_url() ."\">Site Admin</a>";
		if ( !(current_user_can('administrator')) ) echo "<h6 style=\"text-align: center;\"><a href=\"". get_site_url() ."/profile\">User Profile</a>";
		echo " | <a href=\"". wp_logout_url( $_SERVER['PHP_SELF'] ) ."\">Log Out</a></h6>";
		return $user_info;
	}
	else {
		$post = get_post($post_id);
		$page = $post->post_name;
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
			<div style="margin: 3em auto; width: 500px; border: #c3c3c3 1px solid; padding: .5em;">
			<h5>Log In</h5>
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
				<p><a href="/'.$page.'">Not Registered? Click Here.</a></p>
				' . apply_filters( 'login_form_bottom', '', $args ) . '
			</form>
			</div>';
		return $form;
	}
	$item = ob_get_contents();
	ob_end_clean();
	return $item;
}

echo tngwp_login_form($item);
get_footer();
?>