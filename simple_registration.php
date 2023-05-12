<?php
function tngwp_simple_registration() {
//	session_start();
	include('/assets/css/style.css');
	ob_start();
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
	<script language="javascript">
	//<!---------------------------------+
	//  Developed by Roshan Bhattarai, adapted by Heather Feuerhelm 
	//  Visit http://roshanbh.com.np for this script and more.
	//  This script checks the WordPress database for existing username.
	//  This notice MUST stay intact for legal use
	// --------------------------------->
	jQuery(document).ready(function()
	{
		jQuery("#user_login").blur(function()
		{
			var root = "<?php bloginfo('wpurl') ?>";
			//remove all the class add the messagebox classes and start fading
			jQuery('#user_login').css('border', '1px #CCC solid');
			jQuery('#tick').hide(); jQuery('#cross').hide();
			jQuery("#msgbox").css({'border': '1px #ffc solid','color': '#c93'}).text('Checking...').fadeIn('fast');
			//check the username exists or not from ajax
			jQuery.post(root+'/wp-content/plugins/tngwp_frontend_user_functions/assets/user_availability.php',{ user_login:jQuery(this).val() } ,function(data)
			{
			  if(data=='no') //if username not avaiable
			  {
				if(jQuery('#user_login').hasClass('valid')) { jQuery('#user_login').removeClass('valid').css({'border':'1px solid #800','color':'#800','font-weight':'bold'}); }
				jQuery('#tick').hide();
				jQuery('#cross').css('display', 'inline').fadeIn('fast');
				jQuery("#msgbox").fadeTo(500,0.1,function() //start fading the messagebox
				{ 
				  //add message and change the class of the box and start fading
				  jQuery(this).html('User name exists or not provided').css({'color': '#800','font-weight': 'bold'}).fadeTo(500,1);
				});		
			  }
			  else
			  {
				if(jQuery('#user_login').hasClass('error')) { jQuery('#user_login').removeClass('error').addClass('valid'); }
				jQuery('#user_login').css({'border':'1px solid #080','color':'#080','font-weight':'bold'});
				jQuery('#cross').hide();
				jQuery('#tick').fadeIn('fast');
				jQuery("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
				{ 
				  //add message and change the class of the box and start fading
				  jQuery(this).html('Username available').css({'color': '#080','font-weight': 'bold'}).fadeTo(500,1);	
				});
			  }
					
			});
	 
		});
	}); 
	</script>
	<script language="javascript">
	//<!---------------------------------+
	//  Developed by Roshan Bhattarai, adapted by Heather Feuerhelm 
	//  Visit http://roshanbh.com.np for this script and more.
	//  This script checks the WordPress database for existing email address.
	//  This notice MUST stay intact for legal use
	// --------------------------------->
	jQuery(document).ready(function() {
		jQuery("#user_email").blur(function()
		{
			var root = "<?php bloginfo('wpurl') ?>";
			//remove all the class add the messagebox classes and start fading
			jQuery('#user_email').css('border', '1px #CCC solid');
			jQuery('#tick2').hide(); jQuery('#cross2').hide();
			jQuery("#msgbox2").css({'border': '1px #ffc solid','color': '#c93'}).text('Checking...').fadeIn('fast');
			//check the user email exists or not from ajax
			jQuery.post(root+'/wp-content/plugins/tngwp_frontend_user_functions/assets/email_availability.php',{ user_email:jQuery(this).val() } ,function(data)
			{
			  if(data=='no') //if user email not avaiable or not provided
			  {
				if(jQuery('#user_email').hasClass('valid')) { jQuery('#user_email').removeClass('valid').css({'border':'1px solid #800','color':'#800','font-weight':'bold'}); }
				jQuery('#tick2').hide();
				jQuery('#cross2').css('display', 'inline').fadeIn('fast');
				jQuery("#msgbox2").fadeTo(500,0.1,function() //start fading the messagebox
				{ 
				  //add message and change the class of the box and start fading
				  jQuery(this).html('Email not provided OR this email already exists. If this is your email, please login and update your information on your profile page.').css({'color': '#800','font-weight': 'bold'}).fadeTo(500,1);
				});		
			  }
			  else
			  {
				if(jQuery('#user_email').hasClass('error')) { jQuery('#user_email').removeClass('error').addClass('valid'); }
				jQuery('#user_email').css({'border':'1px solid #080','color':'#080','font-weight':'bold'});
				jQuery('#cross2').hide();
				jQuery('#tick2').fadeIn('fast');
				jQuery("#msgbox2").fadeTo(200,0.1,function()  //start fading the messagebox
				{ 
				  //add message and change the class of the box and start fading
				  jQuery(this).html('This email is okay to use.').css({'color': '#080','font-weight': 'bold'}).fadeTo(500,1);	
				});
			  }
					
			});
	 
		});
	});
	</script>
	<script type="module" language="javascript">
		var strength = {
				0: "Worst ☹",
				1: "Bad ☹",
				2: "Weak ☹",
				3: "Good ☺",
				4: "Strong ☻"
		}

		var password = document.getElementById('password');
		var meter = document.getElementById('password-strength-meter');
		var text = document.getElementById('password-strength-text');

		password.addEventListener('input', function()
		{
			var val = password.value;
			var result = zxcvbn(val);
			
			// Update the password strength meter
			meter.value = result.score;
		   
			// Update the text indicator
			if(val !== "") {
				text.innerHTML = "Strength: " + "<strong>" + strength[result.score] + "</strong>" + "<span class='feedback'>" + result.feedback.warning + " " + result.feedback.suggestions + "</span"; 
			}
			else {
				text.innerHTML = "";
			}
		});
	</script>
	 <script src="https://www.google.com/recaptcha/api.js"></script>
	<script>
		//Part of Google reCaptcha
		function onSubmit(token) {
			document.getElementById("register").submit();
		}
	</script>
	<?php
	$error_message1=""; 
	$first_nameError=""; 
	$last_nameError=""; 
	$user_loginError=""; 
	$user_emailError=""; 
	$passwordError="";
	if((isset($_POST['submit']))){
		$valid = true;

		if(empty($_POST['first_name'])){
			$valid=false;
			$first_nameError = "First name is missing".PHP_EOL;
			$first_nameError = nl2br($first_nameError);
		}
		if(empty($_POST['last_name'])){
			$valid=false;
			$last_nameError = "Last name is missing".PHP_EOL;
			$last_nameError = nl2br($last_nameError);
		}
		if(empty($_POST['user_login'])){
			$valid=false;
			$user_loginError = "User name is missing".PHP_EOL;
			$user_loginError = nl2br($user_loginError);
		}
		if(empty($_POST['user_email'])){
			$valid=false;
			$user_emailError = "Email address is missing".PHP_EOL;
			$user_emailError = nl2br($user_emailError);
		}
		if(empty($_POST['password'])){
			$valid=false;
			$passwordError = "Password is missing".PHP_EOL;
			$passwordError = nl2br($passwordError);
		}
		if ($valid==false) {
			$error_message1 = "Please correct the following errors and resubmit (don't forget to confirm your email and password):".PHP_EOL;
			$error_message1 = nl2br($error_message1);
		}
		if($valid){
			include_once(WP_PLUGIN_DIR.'/tngwp_frontend_user_functions/assets/simple_registration_processor.php');
			tngwp_process_simple_registration();
			exit();
		}
	}
	?>
	<form action="" method="post" id="register" name="register">
	<div style="color:red;font-weight:bold;"><?php echo $error_message1; echo $first_nameError; echo $last_nameError; echo $user_loginError; echo $user_emailError; echo $passwordError; ?></div>
	<fieldset>
		<legend>Your Information</legend>
		<p>Note: Required fields are shown in <em><span style="color: #c63800;">colored</span> italic</em></p>
		<!-- First Name Field (required) -->
		<div class="fieldgroup">
			<label class="required" for="first_name">First Name:  </label>
			<input type="text" id="first_name" name="first_name" class="input" <?php if (!empty($_POST['first_name'])) {echo "value=\"" . htmlspecialchars($_POST["first_name"]) . "\"";} ?> />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<title>check-circle</title>
			<g fill="none">
			  <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<title>exclamation-circle</title>
			<g fill="none">
			  <path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			</g>
			</svg>
			<div class="error-message"></div>
		</div> <!-- End First Name Field -->
		
		<!-- Last Name Field (required) -->
		<div class="fieldgroup">
			<label class="required" for="last_name">Last Name:  </label>
			<input type="text" id="last_name" name="last_name" class="input" <?php if (!empty($_POST['last_name'])) {echo "value=\"" . htmlspecialchars($_POST["last_name"]) . "\"";} ?> />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<title>check-circle</title>
			<g fill="none">
			  <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			<title>exclamation-circle</title>
			<g fill="none">
			  <path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
			</g>
			</svg>
			<div class="error-message"></div>
		</div> <!-- End Last Name Field -->
		
		<!-- City Field (not required) -->
		<div class="fieldgroup">
			<label for="city">City:  </label>
			<input type="text" id="city" name="city" class="input" <?php if (!empty($_POST['city'])) {echo "value=\"" . htmlspecialchars($_POST["city"]) . "\"";} ?> />
		</div>
		
		 <!-- State/Province Field (not required) -->
		<div class="fieldgroup">
			<label for="state_prov">State/Province:  </label>
			<input type="text" id="state_prov" name="state_prov" class="input" <?php if (!empty($_POST['state_prov'])) {echo "value=\"" . htmlspecialchars($_POST["state_prov"]) . "\"";} ?> />
		</div>
		
		 <!-- Postal Code Field (not required) -->
		<div class="fieldgroup">
			<label for="postalcode">Postal Code:  </label>
			<input type="text" id="postalcode" name="postalcode" class="input" <?php if (!empty($_POST['postalcode'])) {echo "value=\"" . htmlspecialchars($_POST["postalcode"]) . "\"";} ?> />
		</div>
		
		 <!-- Country Field (not required) -->
		<div class="fieldgroup">
			<label for="country">Country:  </label>
			<input type="text" id="country" name="country" class="input" <?php if (!empty($_POST['country'])) {echo "value=\"" . htmlspecialchars($_POST["country"]) . "\"";} ?> />
		</div>
		
		<!-- Website Field (not required) -->
		<div class="fieldgroup">
			<label for="user_url">Your Website:  </label>
			<input type="url" id="user_url" name="user_url" class="input" <?php if (!empty($_POST['user_url'])) {echo "value=\"" . htmlspecialchars($_POST["user_url"]) . "\"";} ?> />
		</div>
		
		<!-- Login Field (required) -->
		<div class="fieldgroup">
			<label class="required" for="user_login">Login Name:  </label>
			<input type="text" id="user_login" name="user_login" class="input" <?php if (!empty($_POST['user_login'])) {echo "value=\"" . htmlspecialchars($_POST["user_login"]) . "\"";} ?> />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>check-circle</title>
				<g fill="none">
					<path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>exclamation-circle</title>
				<g fill="none">
					<path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<div class="error-message"></div>
			<img id="tick" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/tick.png" width="16" height="16"/>
			<img id="cross" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/cross.png" width="16" height="16"/><span id="msgbox"></span>
			<p class="field-message">Usernames cannot begin with a number and should not contain any punctuation characters (no . , : ; ' " ! \ / [ ] { } + - )</p>
		</div> <!-- End Login Field -->
		
		<!-- User Email Field (required) -->
		<div class="fieldgroup">
			<label class="required" for="user_email">Email Address:  </label>
			<input type="email" id="user_email" name="user_email" class="input" <?php if (!empty($_POST['user_email'])) {echo "value=\"" . htmlspecialchars($_POST["user_email"]) . "\"";} ?> />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>check-circle</title>
				<g fill="none">
					<path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>exclamation-circle</title>
				<g fill="none">
					<path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<div class="error-message"></div>
			<img id="tick2" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/tick.png" width="16" height="16"/>
			<img id="cross2" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/cross.png" width="16" height="16"/><span id="msgbox2"></span>
			<div class="field-message" style="clear:both;">Please make sure you are not blocking mail from the this domain to ensure email from us does not end up in a spam or junk folder.</div>
		</div> <!-- End User Email Field -->
		
		<!-- Confirm Email Field (required) -->
		<div class="fieldgroup">
			<label class="required" for="confirm_email">Confirm Email:  </label>
			<input type="email" id="confirm_email" name="confirm_email" class="input" <?php if (!empty($_POST['confirm_email'])) {echo "value=\"" . htmlspecialchars($_POST["confirm_email"]) . "\"";} ?> />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>check-circle</title>
				<g fill="none">
					<path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>exclamation-circle</title>
				<g fill="none">
					<path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<div class="error-message"></div>
		</div> <!-- End Confirm Email Field -->
		
		<!-- Password Field (required) -->
		<div class="fieldgroup">
			<label class="label required" for"user_email">Password:  </label>
			<input type="password" id="password" name="password" class="input" />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>check-circle</title>
				<g fill="none">
					<path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>exclamation-circle</title>
				<g fill="none">
					<path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<div class="error-message"></div>
			<div class="field-message">
				<meter max="4" id="password-strength-meter"></meter>
				<div id="password-strength-text"></div>
				Passwords must be between 12 and 20 characters and include Upper Case letters, lower case letters, 1-3 numbers, and 1-2 of these symbols: !@%$? 
			</div>
		</div> <!-- End Password Field -->
		
		<!-- Confirm Password Field (required) -->
		<div class="fieldgroup">
			<label class="required" for"confirm_pass">Confirm Password:  </label>
			<input type="password" id="confirm_pass" name="confirm_pass" class="input" />
			<svg class="icon icon-success hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>check-circle</title>
				<g fill="none">
					<path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<svg class="icon icon-error hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<title>exclamation-circle</title>
				<g fill="none">
					<path d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
				</g>
			</svg>
			<div class="error-message"></div>
			<div class="field-message">Please make sure you save your password in a protected location.</div>
		</div> <!-- End Confirm Password Field -->
	</fieldset>
	<br />
	<fieldset>
		<legend>Additional Information</legend>
		<?php
		$tng_folder = get_option('mbtng_path');
		include($tng_folder."subroot.php");
		include($tng_folder."config.php");
		include($tng_folder."getlang.php");
		$link = mysqli_connect($database_host, $database_username, $database_password, $database_name) or die("Error: TNG is not communicating with your database. Please check your database settings and try again.");
		$treeselect = "SELECT gedcom, treename FROM $trees_table ORDER BY treename";
		$treequery = mysqli_query($link, $treeselect) or die ("Cannot execute query");
		$treeresult = mysqli_fetch_array($treequery);
		$tree = $treeresult['gedcom'];
		$treename = $treeresult['treename'];
		mysqli_free_result($treequery);
		?>
		<label for="tree" class="additionalinfo">Please select your family tree:
		<select id="tree" name="tree">
			<option value="">&nbsp;</option>
			<?php echo "<option value=\"$tree\">$treename</option>"; ?>
		</select></label>Note: To request a new tree, leave this blank and give details in the next field.<br />
		<br /><label for="interest" class="additionalinfo">What is your interest in this Family Tree?</label>
		<br /><textarea cols="75" rows="5" name="interest" id="interest"><?php if (!empty($_POST['interest'])) {echo "value=\"" . htmlspecialchars($_POST["interest"]) . "\"";} ?></textarea>
		<br /><br />
		<br /><label for="relationship" class="additionalinfo">Is there someone you believe you are related to in the tree? If so, who?</label>
		<br /><input type="Text" name="relationship" id="relationship" <?php if (!empty($_POST['relationship'])) {echo "value=\"" . htmlspecialchars($_POST["relationship"]) . "\"";} ?> />
		<br /><br />
		<label for="comments" class="additionalinfo">Additional Comments:</label>
		<br /><textarea cols="75" rows="5" name="comments" id="comments"><?php if (!empty($_POST['comments'])) {echo "value=\"" . htmlspecialchars($_POST["comments"]) . "\"";} ?></textarea>
	</fieldset>
	<p style="clear: both;"></p>
	<p style="clear: both;">
	<?php $options = get_option('tngwp-frontend-user-functions-options'); ?>
	<input type="submit" class= "button" name="submit" id="submit" value="Submit User Registration" class="g-recaptcha" data-sitekey="<?php echo $options['recaptcha_sitekey']; ?>" data-callback='onSubmit' data-action='submit' />
	</p>
	</form>
<?php
	$simple_registration_form = ob_get_contents();
	ob_end_clean();
	return $simple_registration_form;
}
add_shortcode('simple_registration_form', 'tngwp_simple_registration');
?>
