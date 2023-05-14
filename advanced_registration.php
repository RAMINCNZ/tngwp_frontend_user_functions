<?php
function tngwp_advanced_registration() {
	$wp = 'wordpress';
	$tng_folder = get_option('mbtng_path');
	include($tng_folder."subroot.php");
	include($tng_folder."config.php");
	include($tng_folder."getlang.php");
	$link = mysqli_connect($database_host, $database_username, $database_password, $database_name) or die("Error: TNG is not communicating with your database. Please check your database settings and try again.");
	$id = $_GET['id'];
	ob_start();
	//Check to make sure the variable has been passed correctly. If not, error message.
	if (!isset($_GET['id'])){
		echo "<p style=\"color: #f00;\"><b>You can't register without showing your relationship in our tree ... Please go <a href=\"".$_SERVER['HTTP_REFERER']."\">back and search for your relative first.</a></b></p><br/><br/>";
	}

	//Get the relevant row from the database
	$select = "SELECT * FROM $people_table WHERE personID='$id'";
	$query = mysqli_query($link, $select);
	$result = mysqli_fetch_array($query);
	$first = $result['firstname'];
	$last = $result['lastname'];
	$ancestor_name = ($first.' '.$last);
	$readonly = "<input type=\"text\" name=\"relative\" value=\"$ancestor_name\" size=\"40\" maxlength=\"40\" readonly=\"readonly\"/>";
	$read = "<input type=\"text\" name=\"newid\" id=\"x\" value=\"$id\" readonly=\"readonly\"/>";
	//$url="http://".$_SERVER['HTTP_HOST']."/user-registration/success/?try=1";
	$personID = $_POST['newid'];

	//Parent Info Table
	$parents = '
	<fieldset>
		<legend>Parent\'s Information</legend>
		<span>Please provide the following information for your/your spouse\'s parents:</span>
	<table>
		<tr>
			<td>
				<label for "father_firstname">Father\'s First Name</label>
				<input type="text" id="father_firstname" class="required" name="father_firstname" />
			</td>
			<td>
				<label for "father_lastname">Father\'s Last Name</label>
				<input type="text" id="father_lastname" class="required" name="father_lastname" />
			</td>
			<td>
				<label for "father_birthdate">Father\'s Date of Birth</label>
				<input type="text" id="father_birthdate" class="required date" name="father_birthdate" />
			</td>
		</tr>
		<tr>
			<td>
				<label for "mother_firstname">Mother\'s First Name</label>
				<input type="text" id="mother_firstname" class="required" name="mother_firstname" />
			</td>
			<td>
				<label for "mother_maidenname">Mother\'s Maiden Name</label>
				<input type="text" id="mother_maidenname" class="required" name="mother_maidenname" />
			</td>
			<td>
				<label for "mother_birthdate">Mother\'s Date of Birth</label>
				<input type="text" id="mother_birthdate" class="required date" name="mother_birthdate" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<label for="parents_mar_date">Marriage Date for this couple</label>
				<input type="text" id="parents_mar_date" class="required date" name="parents_mar_date" />
			</td>
		</tr>
	</table>
	</fieldset>
	';

	//Add Grandparent Info
	$grandparents = '
	<fieldset>
		<legend>Grandparent\'s Information</legend>
		<span>Please provide the following information for your/your spouse\'s grandparents:</span>
	<table>
		<tr>
			<td><label for "grandfather_firstname">Grandfather\'s First Name</label>
				<input type="text" id="grandfather_firstname" class="required" name="grandfather_firstname" />
			</td>
			<td>
				<label for "grandfather_lastname">Grandfather\'s Last Name</label>
				<input type="text" id="grandfather_lastname" class="required" name="grandfather_lastname" />
			</td>
			<td>
				<label for "grandfather_birthdate">Grandfather\'s Date of Birth</label>
				<input type="text" id="grandfather_birthdate" class="required date" name="grandfather_birthdate" />
			</td>
		</tr>
		<tr>
			<td>
				<label for "grandmother_firstname">Grandmother\'s First Name</label>
				<input type="text" id="grandmother_firstname" class="required" name="grandmother_firstname" />
			</td>
			<td>
				<label for "grandmother_maidenname">Grandmother\'s Maiden Name</label>
				<input type="text" id="grandmother_maidenname" class="required" name="grandmother_maidenname" />
			</td>
			<td>
				<label for "grandmother_birthdate">Grandmother\'s Date of Birth</label>
				<input type="text" id="grandmother_birthdate" class="required date" name="grandmother_birthdate" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<label for="grandparents_mar_date">Marriage Date for this couple</label>
				<input type="text" id="grandparenst_mar_date" class="required date" name="grandparents_mar_date" />
			</td>
		</tr>
	</table>
	</fieldset>
	';

	//Add Great-Grandparent's Information
	$gr_grandparents = '
	<fieldset>
		<legend>Great-Grandparent\'s Information</legend>
		<span>Please provide the following information for your/your spouse\'s great-grandparents:</span>
	<table>
		<tr>
			<td><label for "gr_grandfather_firstname">Great-Grandfather\'s First Name</label>
				<input type="text" id="gr_grandfather_firstname" class="required" name="gr_grandfather_firstname" />
			</td>
			<td>
				<label for "gr_grandfather_lastname">Great-Grandfather\'s Last Name</label>
				<input type="text" id="gr_grandfather_lastname" class="required" name="gr_grandfather_lastname" />
			</td>
			<td>
				<label for "gr_grandfather_birthdate">Great-Grandfather\'s Date of Birth</label>
				<input type="text" id="gr_grandfather_birthdate" class="required date" name="gr_grandfather_birthdate" />
			</td>
		</tr>
		<tr>
			<td>
				<label for "gr_grandmother_firstname">Great-Grandmother\'s First Name</label>
				<input type="text" id="gr_grandmother_firstname" class="required" name="gr_grandmother_firstname" />
			</td>
			<td>
				<label for "gr_grandmother_maidenname">Great-Grandmother\'s Maiden Name</label>
				<input type="text" id="gr_grandmother_maidenname" class="required" name="gr_grandmother_maidenname" />
			</td>
			<td>
				<label for "gr_grandmother_birthdate">Great-Grandmother\'s Date of Birth</label>
				<input type="text" id="gr_grandmother_birthdate" class="required date" name="gr_grandmother_birthdate" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<label for="gr_grandparents_mar_date">Marriage Date for this couple</label>
				<input type="text" id="gr_grandparents_mar_date" class="required date" name="gr_grandparents_mar_date" />
			</td>
		</tr>
	</table>
	</fieldset>
	';
	?>
	<script language="javascript">
	//<!---------------------------------+
	//  Developed by Roshan Bhattarai, adapted by Heather Feuerhelm 
	//  Visit http://roshanbh.com.np for this script and more.
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
				  jQuery(this).html('User name exists').css({'color': '#800','font-weight': 'bold'}).fadeTo(500,1);
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
					whom: "required",
					relation: "required",
					father_firstname: "required",
					father_lastname: "required",
					father_birthdate: "required",
					mother_firstname: "required",
					mother_maidenname: "required",
					mother_birthdate: "required",
					parents_mar_date: "required",
					grandfather_firstname: "required",
					grandfather_lastname: "required",
					grandfather_birthdate: "required",
					grandmother_firstname: "required",
					grandmother_maidenname: "required",
					grandmother_birthdate: "required",
					grandparents_mar_date: "required",
					gr_grandfather_firstname: "required",
					gr_grandfather_lastname: "required",
					gr_grandfather_birthdate: "required",
					gr_grandmother_firstname: "required",
					gr_grandmother_maidenname: "required",
					gr_grandmother_birthdate: "required",
					gr_grandparents_mar_date: "required",
					spouse_firstname: "required",
					spouse_lastname: "required",
					spouse_birthdate: "required",
					spouse_mar_date: "required",
					first_name: "required",
					last_name: "required",
					birthdate: "required",
					telephone: "required",
					address: "required",
					city: "required",
					state_prov: "required",
					postalcode: "required",
					country: "required",
					user_login: "required",
					user_pass: "required",
					confirm_pass: "required",
					user_email: "required",
					confirm_email: "required"
				},
				messages: {
					required: "please fill"
				}
			});
    </script>
	<script type="text/javascript">	
		jQuery(document).ready(function(){
			jQuery('#whom').valid8('Required field');
			jQuery('#relation').valid8('Required field');
			jQuery('.required').valid8('Required field');
			jQuery('#last_name').valid8('Required field');
			jQuery('#birthdate').valid8('Required field');
			jQuery('#telephone').valid8('Required field');
			jQuery('#address').valid8('Required field');
			jQuery('#city').valid8('Required field');
			jQuery('#state_prov').valid8('Required field');
			jQuery('#postalcode').valid8('Required field');
			jQuery('#country').valid8('Required field');
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

	<script src="https://www.google.com/recaptcha/api.js"></script>
	<script>
		//Part of Google reCaptcha
		function onSubmit(token) {
			document.getElementById("register").submit();
		}
	</script>

	<form action="<?php echo WP_PLUGIN_URL; ?>/tngwp_frontend_user_functions/assets/advanced_registration_processor.php" method="post" id="register" name="register">
	<fieldset>
		<legend>How are you related to this person?</legend>
	<table>
		<tr>
			<td>
				<span style="display: inline-block;"><?php echo $read.$readonly; ?></span>
				&nbsp;
				<span style="display: inline-block;">
				<label style="display: inline-block;" for="whom">is</label>
				<input id="whom" type ="radio" name="whom" class="required" value="My" onclick="document.getElementById ('spouse').style.display = 'none';">My &nbsp;
				<input id="whom" type ="radio" class="required" name="whom" onclick="document.getElementById ('spouse').style.display = 'block';" value="Spouse"/>My Spouse's
				</span>
				&nbsp;
				<span style="display: inline-block;">
				<select id="relation" name="relation" onchange="processAncestor();" class="required">
					<option value="Self">Self</option>
					<option value="Spouse">Spouse</option>
					<option value="Father">Father</option>
					<option value="Mother">Mother</option>
					<option value="FatherSister">Sister of Father</option>
					<option value="MotherSister">Sister of Mother</option>
					<option value="FatherBrother">Brother of Father</option>
					<option value="MotherBrother">Brother of Mother</option>
					<option value="Brother">Brother</option>
					<option value="Sister">Sister</option>
					<option value="Grandfather">Grandfather</option>
					<option value="Grandmother">Grandmother</option>
					<option value="GrGrandfather">Great Grandfather</option>
					<option value="GrGrandmother">Great Grandmother</option>
					<option value="2ndGrGrandfather">2nd Great Grandfather</option>
					<option value="2ndGrGrandmother" >2nd Great Grandmother</option>
					<option value="Select" selected="selected">Select a Relationship</option>
				</select>
				</span>
			</td>
		</tr>
		<tr>
			<td>
				<span style="font-weight: bold;">If none of the available relationships properly describes your relationship to this person, you will need to email us directly to explain.</span>
				<p>If you selected "My" above, we are looking for <em>your</em> relative's information. If you selected "My Spouse" above, then we are looking for your <em>spouse's</em> relative's information. All Fields are Required. Dates should be entered using the following format: <strong>mm/dd/yyyy</strong></p>
			</td>
		</tr>
	</table>
	
	<div id="spouse">
	<fieldset>
		<legend>Spouse's Information</legend>
	<span>Please complete the following information about your spouse:</span>
	<table>
		<tr>
			<td>
				<label for "spouse_firstname">Spouse Name</label>
				<input type="text" id="spouse_firstname" class="required" name="spouse_firstname" />
			</td>
			<td>
				<label for "spouse_lastname">Spouse Surname/Maiden Name</label>
				<input type="text" id="spouse_lastname" class="required" name="spouse_lastname" />
			</td>
			<td>
				<label for "spouse_birthdate">Spouse Date of Birth</label>
				<input type="text" id="spouse_birthdate" class="required date" name="spouse_birthdate" />
			</td>
		</tr>
		<tr>
			<td>
				<label for "spouse_birthplace">Spouse Location Of Birth</label>
				<input type="text" id="spouse_birthplace" class="required" name="spouse_birthplace" />
			</td>
			<td>
				<label for "md">Your Marriage Date</label>
				<input type="text" id="spouse_mar_date" class="required date" name="spouse_mar_date" />
			</td>
		</tr>
	</table>
	</fieldset>
	</div>
	<div id="parents">
	<?php echo $parents; ?>
	</div>

	<div id="grandparents">
	<?php echo $grandparents; ?>
	</div>

	<div id="gr_grandparents">
	<?php echo $gr_grandparents; ?>
	</div>
	</fieldset>
	
	<fieldset>
		<legend>Your Information</legend>
	<div id="self">
	<p>Please complete the following information about yourself for the User Registration:
	<br />An <strong>*</strong> indicates a <strong>Required</strong> field.</p>

	<table style="width: 100%;">
		<tr>
			<td width="20%">
				<label for="first_name">First Name*</label>
			</td>
			<td width="20%">
				<input type="text" class="required" name="first_name" id="first_name" />
			</td>
			<td width="60%"><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="last_name">Last Name/Maiden Name*</label>
			</td>
			<td>
				<input type="Text" class="required" name="last_name" id="last_name" />
			</td>
			<td>If you are female and married, please be sure to use your maiden name.</td>
		</tr>
		<tr>
			<td>
				<label for="birthdate">Date of birth*</label>
			</td>
			<td>
				<input type="Text" class="required date" name="birthdate" id="birthdate" />
			</td>
			<td>Please use date format: mm/dd/yyyy</td>
		</tr>
		<tr>
			<td>
				<label for="birthplace">Location of birth</label>
			</td>
			<td>
				<input type="Text" id="birthplace" name="birthplace" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="telephone">Phone Number*</label>
			</td>
			<td>
				<input type="text" id="telephone" class="required" name="telephone" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="address">Address*</label>
			</td>
			<td>
				<input type="Text" class="required" name="address" id="address" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="city">City*</label>
			</td>
			<td>
				<input type="Text" class="required" name="city" id="city" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="state_prov">State/Province*</label>
			</td>
			<td>
				<input type="Text" class="required" name="state_prov" id="state_prov" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="postalcode">Postal Code*</label>
			</td>
			<td>
				<input type="Text" class="required" name="postalcode" id="postalcode" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="country">Country*</label>
			</td>
			<td>
				<input type="Text" class="required" name="country" id="country" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="user_url">Your Website</label>
			</td>
			<td>
				<input type="Text" name="user_url" id="user_url" />
			</td>
			<td><br /><br /></td>
		</tr>
		<tr>
			<td>
				<label for="user_login">Login Name*</label>
			</td>
			<td>
				<input type="Text" class="required login" name="user_login" id="user_login" />
			</td>
			<td>
				<img id="tick" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/images/tick.png" width="16" height="16"/><img id="cross" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/images/cross.png" width="16" height="16"/><span id="msgbox"></span>
				Usernames cannot begin with a number and should not contain any punctuation characters (no . , : ; ' " ! \ / [ ] { } + - )
			</td>
		</tr>
		<tr>
			<td>
				<label for="user_email">Email Address*</label>
			</td>
			<td>
				<input type="text" class="required" name="user_email" id="user_email" />
			</td>
			<td>
				<img id="tick2" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/tick.png" width="16" height="16"/><img id="cross2" src="<?php bloginfo('wpurl') ?>/wp-content/plugins/tngwp_frontend_user_functions/assets/images/cross.png" width="16" height="16"/><span id="msgbox2"></span>
			</td>
		</tr>
		<tr>
			<td>
				<label for="confirm_email">Confirm Email*</label>
			</td>
			<td>
				<input type="Text" class="required" name="confirm_email" id="confirm_email" />
			</td>
			<td>Please make sure you are not blocking mail from the this domain to ensure email from us does not end up in a spam or junk folder.</td>
		</tr>
		<tr>
			<td>
				<label for="user_pass">Password*</label>
			</td>
			<td>
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
			<td>
				<label for="confirm_pass">Confirm Password*</label>
			</td>
			<td>
				<input type="password" class="required" name="confirm_pass" id="confirm_pass" />
			</td>
			<td><br /><br /></td>
		</tr>
	</table>
	<br /><br />
	<?php
		$treeselect = "SELECT gedcom, treename FROM $trees_table ORDER BY treename";
		$treequery = mysqli_query($link, $treeselect) or die ("Cannot execute query");
		$treeresult = mysqli_fetch_array($treequery);
		$tree = $treeresult['gedcom'];
		$treename = $treeresult['treename'];
	?>
	<br /><label for="tree">Please select your family tree:
	<select id="tree" name="tree">
		<option value="">&nbsp;</option>
		<?php echo "<option value=\"$tree\">$treename</option>"; ?>
	</select></label>Note: To request a new tree, leave this blank and give details in the next field.<br />
	<?php mysqli_close($link); ?>
	<label for="notes">Notes:</p>
	<textarea cols="75" rows="5" name="notes" id="notes"></textarea>
	</div>
	</fieldset>
	<p style="clear: both;"></p>
	<p style="clear: both;">
	<?php $options = get_option('tngwp-frontend-user-functions-options'); ?>
	<input type="submit" class= "button" name="submit" id="submit" value="Submit User Registration" class="g-recaptcha" data-sitekey="<?php echo $options['recaptcha_sitekey']; ?>" data-callback='onSubmit' data-action='submit' />
	</p>
	</form>
	
	<!-- Tell the user what happened last time through --><?php
	$advanced_registration_form = ob_get_contents();
	ob_end_clean();
	return $advanced_registration_form;
}
add_shortcode('advanced_registration_form', 'tngwp_advanced_registration');
?>
