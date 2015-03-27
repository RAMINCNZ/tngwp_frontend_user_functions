<?php
function tngwp_simple_registration() {
//	session_start();
	ob_start();
	?>
	<script language="javascript">
	//<!---------------------------------+
	//  Developed by Roshan Bhattarai, adapted by Heather Feuerhelm 
	//  Visit http://roshanbh.com.np for this script and more.
	//  This notice MUST stay intact for legal use
	// --------------------------------->
	jQuery(document).ready(function()
	{
		jQuery("#userlogin").blur(function()
		{
			var root = "<?php bloginfo('wpurl') ?>";
			//remove all the class add the messagebox classes and start fading
			jQuery('#userlogin').css('border', '1px #CCC solid');
			jQuery('#tick').hide(); jQuery('#cross').hide();
			jQuery("#msgbox").css({'border': '1px #ffc solid','color': '#c93'}).text('Checking...').fadeIn('fast');
			//check the username exists or not from ajax
			jQuery.post(root+'/wp-content/plugins/tngwp_frontend_user_functions/assets/user_availability.php',{ userlogin:jQuery(this).val() } ,function(data)
			{
			  if(data=='no') //if username not avaiable
			  {
				if(jQuery('#userlogin').hasClass('valid')) { jQuery('#userlogin').removeClass('valid').css({'border':'1px solid #800','color':'#800','font-weight':'bold'}); }
				jQuery('#tick').hide();
				jQuery('#cross').css('display', 'inline').fadeIn('fast');
				jQuery("#msgbox").fadeTo(500,0.1,function() //start fading the messagebox
				{ 
				  //add message and change the class of the box and start fading
				  jQuery(this).html('User name exists').css({'color': '#800','font-weight': 'bold'}).fadeTo(500,1);
				});		
			  }
			  else
			  {
				if(jQuery('#userlogin').hasClass('error')) { jQuery('#userlogin').removeClass('error').addClass('valid'); }
				jQuery('#userlogin').css({'border':'1px solid #080','color':'#080','font-weight':'bold'});
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
	//  This notice MUST stay intact for legal use
	// --------------------------------->
	jQuery(document).ready(function()
	{
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
			  if(data=='no') //if user email not avaiable
			  {
				if(jQuery('#user_email').hasClass('valid')) { jQuery('#user_email').removeClass('valid').css({'border':'1px solid #800','color':'#800','font-weight':'bold'}); }
				jQuery('#tick2').hide();
				jQuery('#cross2').css('display', 'inline').fadeIn('fast');
				jQuery("#msgbox2").fadeTo(500,0.1,function() //start fading the messagebox
				{ 
				  //add message and change the class of the box and start fading
				  jQuery(this).html('This email already exists. If this is your email, please login and update your information on your profile page.').css({'color': '#800','font-weight': 'bold'}).fadeTo(500,1);
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
	<script type="text/javascript">	
		jQuery().ready(function() {
			// validate signup form on submit
			jQuery("#register").validate({
				rules: {
					first_name: "required",
					last_name: "required",
					user_login: "required",
					user_pass: "required",
					confirm_pass: "required",
					user_email: "required",
					confirm_email: "required"
				},
				messages: {
					first_name: "Please fill",
					last_name: "Please fill",
					user_login: "Please fill",
					user_pass: "Please fill",
					confirm_pass: "Please fill",
					user_email: "Please fill",
					confirm_email: "Please fill"
				}
			});
		});
		jQuery("#captcha_form").validate({
			submitHandler: function(form) {
				if ( jQuery('#status').text().match('Captcha validation succcessful') ) 
					form.submit();
			}
		});
		</script>
		<script type="text/javascript">	
		jQuery(document).ready(function(){
			jQuery('#first_name').valid8('First name is required');
			jQuery('#last_name').valid8('Last name is required');
			jQuery('.login').valid8({
				'regularExpressions': [
				{ expression: /^.+$/, errormessage: 'Username is required'},	
				{ expression: /^[a-zA-Z0-9]+$/, errormessage: 'You can only use the letters A-Z and numbers'}
				]
			});
			jQuery('.date').valid8({
				'regularExpressions': [
				{ expression: /^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d$/, errormessage: 'Please use the format mm/dd/yyyy'}
				]
			});
			function doesEmailFieldsMatch(values){
				if(values.user_email == values.confirm_email)
					return {valid:true}
				else
					return {valid:false, message:'Emails do not match'}
			}
			jQuery('#user_email').valid8({
				'regularExpressions': [
					{ expression: /^.+$/, errormessage: 'Email is required'},
					{ expression:  /^([a-zA-Z0-9]+[\.|_|\-|�|$|%|&]{0,1})*[a-zA-Z0-9]{1}@([a-zA-Z0-9]+[\.|_|\-|�|$|%|&]{0,1})*([\.]{1}([a-zA-Z]{2,4}))$/
, errormessage: 'Not a valid email'},
				]
			});
			jQuery('#confirm_email').valid8({
				'jsFunctions': [
					{ function: doesEmailFieldsMatch, values: function(){
							return { user_email: jQuery('#user_email').val(), confirm_email: jQuery('#confirm_email').val() }
						}
					}
				]
			});
			function doesPasswordFieldsMatch(values){
				if(values.pass == values.confirm_pass)
					return {valid:true}
				else
					return {valid:false, message:'Passwords do not match'}
			}
			jQuery('#pass').valid8({
				'regularExpressions': [
					{ expression: /(?=.{7,})/, errormessage: 'Minimum length is 7' }
				]
			});
			jQuery('#confirm_pass').valid8({
				'jsFunctions': [
					{ function: doesPasswordFieldsMatch, values: function(){
							return { pass: jQuery('#pass').val(), confirm_pass: jQuery('#confirm_pass').val() }
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
    <script type="text/javascript">
		function myfun(value)
		{
		var root = "<?php bloginfo('wpurl') ?>";  
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				
				//alert(xmlhttp.responseText);
			document.getElementById("status").innerHTML=xmlhttp.responseText;
		   
			
			}
		  }
		  //alert(document.getElementById("txtHint").innerHTML);
		xmlhttp.open("GET", root+"/wp-content/plugins/tngwp_frontend_user_functions/assets/captcha/captcha_ajax.php?captcha="+value,true);			
		xmlhttp.send();
			//alert(xmlhttp.responseText);
			//jQuery("input[type=submit]").prop("disabled", false);
		}
		
		function click_refresh()
		{
			var root = "<?php bloginfo('wpurl') ?>";
			document.getElementById('captcha').src=root+'/wp-content/plugins/tngwp_frontend_user_functions/assets/captcha/captcha.php?'+Math.random();
			document.getElementById('captcha_form').focus();
			document.getElementById('status').innerHTML="";
			document.getElementById('captcha_form').value="";
		}
	</script>
	<form action="<?php echo WP_PLUGIN_URL; ?>/tngwp_frontend_user_functions/assets/simple_registration_processor.php" method="post" id="register" name="register">
	<fieldset>
		<legend>Your Information</legend>
		<p>An <strong>*</strong> indicates a <strong>Required</strong> field.</p>
		<table>
			<tr>
				<td class="label">
					<label for="first_name">First Name*</label>
				</td>
				<td class="input">
					<input class="required" type="text" id="first_name" name="first_name" />
				</td>
				<td width="auto"><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="last_name">Last Name*</label>
				</td>
				<td class="input">
					<input class="required" type="Text" id="last_name" name="last_name" />
				</td>
				<td><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="city">City</label>
				</td>
				<td class="input">
					<input type="Text" id="city" name="city" />
				</td>
				<td><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="state_prov">State/Province</label>
				</td>
				<td class="input">
					<input type="Text" id="state_prov" name="state_prov" />
				</td>
				<td><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="postalcode">Postal Code</label>
				</td>
				<td class="input">
					<input type="Text" id="postalcode" name="postalcode" />
				</td>
				<td><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="country">Country</label>
				</td>
				<td class="input">
					<input type="Text" id="country" name="country" />
				</td>
				<td><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="user_url">Your Website</label>
				</td>
				<td class="input">
					<input type="Text" name="user_url" id="user_url" />
				</td>
				<td><br /><br /></td>
			</tr>
			<tr>
				<td class="label">
					<label for="userlogin">Login Name*</label>
				</td>
				<td class="input">
					<input class="required login" type="Text" name="userlogin" id="userlogin" />
				</td>
				<td>
					<img id="tick" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/tick.png" width="16" height="16"/><img id="cross" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/cross.png" width="16" height="16"/><span id="msgbox"></span>
					Usernames cannot begin with a number and should not contain any punctuation characters (no . , : ; ' " ! \ / [ ] { } + - )
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="user_email">Email Address*</label>
				</td>
				<td class="input">
					<input class="required" type="text" name="user_email" id="user_email" />
				</td>
				<td>
					<img id="tick2" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/tick.png" width="16" height="16"/><img id="cross2" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/cross.png" width="16" height="16"/><span id="msgbox2"></span>
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="confirm_email">Email Again*</label>
				</td>
				<td class="input">
					<input class="required" type="Text" name="confirm_email" id="confirm_email" />
				</td>
				<td>Please make sure you are not blocking mail from the this domain to ensure email from us does not end up in a spam or junk folder.</td>
			</tr>
			<tr>
				<td class="label">
					<label for="user_pass">Password*</label>
				</td>
				<td class="input">
					<input type="password" class="required" name="user_pass" id="pass" onkeyup="pwdStrength(this.value)" />
					<div id="passwordDescription">Password not entered</div>
					<div id="passwordStrength" class="strength0"></div>
					<br />
				</td>
				<td>
					Passwords must be at least 7 characters and include Upper Case letters, lower case letters, 1-3 numbers, and 1-2 of these symbols: ?%&!
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="confirm_pass">Password Again*</label>
				</td>
				<td class="input">
					<input class="required" type="password" name="confirm_pass" id="confirm_pass" />
				</td>
				<td><br /><br /></td>
			</tr>
		</table>
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
		$treeselect = "SELECT gedcom, treename FROM tng_trees ORDER BY treename";
		$treequery = mysqli_query($link, $treeselect) or die ("Cannot execute query");
		$treeresult = mysqli_fetch_array($treequery);
		$tree = $treeresult['gedcom'];
		$treename = $treeresult['treename'];
		mysqli_free_result($treequery);
		?>
		<br /><label for="tree">Please select your family tree:
		<select id="tree" name="tree">
			<option value="">&nbsp;</option>
			<?php echo "<option value=\"$tree\">$treename</option>"; ?>
		</select></label>Note: To request a new tree, leave this blank and give details in the next field.<br />
		<br /><label for="interest">What is your interest in this Family Tree?</label>
		<br /><textarea cols="75" rows="5" name="interest" id="interest"></textarea>
		<br /><br />
		<br /><label for="relationship">Is there someone you believe you are related to in the tree? If so, who?</label>
		<br /><input type="Text" name="relationship" id="relationship" />
		<br /><br />
		<label for="comments">Additional Comments:</label>
		<br /><textarea cols="75" rows="5" name="comments" id="comments"></textarea>
	</fieldset>
	<br />
	<fieldset>
        <legend>Verification</legend>
        <div onload="document.getElementById('captcha_form').focus()">
        <table border="1" cellpadding="0" cellspacing="0" style="width: 90%; margin: 0 auto;">
            <tr>
                <td class="label">Security Text:</td>
                <td class="input"><input class="required" type="text" name="captcha" id="captcha_form" onkeyup="myfun(this.value)" /><br/></td>
                <td><img src="<?php echo WP_PLUGIN_URL; ?>/tngwp_frontend_user_functions/assets/captcha/captcha.php" id="captcha" /><br/></td>
                <td><a href="javascript:void(0)" onclick="click_refresh()" id="change-image"><img src="<?php echo WP_PLUGIN_URL; ?>/tngwp_frontend_user_functions/assets/captcha/ajax-refresh-icon.png" alt="captcha validation" /></a></td>
            </tr>
            <tr>
                <td colspan="4" align="center">Validation Status: <div id="status"></div></td>
            </tr>
        </table>
        </div>
    </fieldset>
	<p style="clear: both;"></p>
	<p style="clear: both;">
	<input type="submit" name="submit" id="submit" value="Submit User Registration" />
	</p>
	</form>
<?php
	$simple_registration_form = ob_get_contents();
	ob_end_clean();
	return $simple_registration_form;
}
add_shortcode('simple_registration_form', 'tngwp_simple_registration');
?>