<?php
function searchnewaccount() {
	$tng_folder = get_option('mbtng_path');
	chdir($tng_folder);
	include_once('begin.php');
	mbtng_db_connect() or exit;
$form = '
<form style="margin: 0 auto; text-align: center;" name="getSurnames" method="post" action="">
	<label for="first">First Name</label>
	<input type="text" name="first" id="first\"/>
	<br /><br />
	<label for="last">Last Name</label>
	<input type="text" name="last" id="last"/>
	<br /><br />
	<span style="text-align: center;">OR</span>
	<br /><br />
	<label for="id">Search by ID: </label>
	<input type="text" size="4" name="idea"/><br />
	<br /><br />
	<input type="submit" name="submit" value="Search"/>
</form>
';
ob_start();
if (isset($_POST['submit'])) {
	$first = mysql_real_escape_string(stripslashes($_POST['first']));
	$last = mysql_real_escape_string(stripslashes($_POST['last']));
	$id =mysql_real_escape_string(stripslashes($_POST['idea']));
	$real=format_pid( $id );
	if( !empty( $real ))  // retrieve personID from all trees
		$query = "SELECT * FROM $people_table WHERE personID='$real' ORDER BY lastname, firstname ";
	else if( !empty( $first)  && !empty( $last ))
		$query = "SELECT * FROM $people_table WHERE lastname LIKE '%$last%' AND firstname LIKE '%$first%' ORDER BY lastname, firstname ";
	else if (!empty( $last ) )
		$query = "SELECT * FROM $people_table WHERE lastname LIKE '%$last%' ORDER BY lastname, firstname ";
	else if (!empty( $first ) )
		$query = "SELECT * FROM $people_table WHERE firstname LIKE '%$last%' ORDER BY lastname, firstname ";
	else
		$query = ""; 
}

/// if there is a query, process it 
if( !empty( $query ) ) {

	$result = mysql_query($query) or die (mysql_error());
	$count = mysql_num_rows ($result);
	if (!$count )
		echo "<p class=\"warning\">No Results. Try using Last Name only or partial Last Name.</p>".$form;
	else { // we have database records
		// print table header row before any processing
		$mytable = "<table cellpadding='3' cellspacing='0' border='0' width='100%' class='whiteback'>
		<tr>
		<td class=\"fieldnameback\"><span class=\"fieldname\"><strong>ID</strong></span></td>
		<td class=\"fieldnameback\"><span class=\"fieldname\"><strong>Name</strong></span></td>
		<td class=\"fieldnameback\"><span class=\"fieldname\"><strong>Birth&nbsp;Date</strong></span></td>
		<td class=\"fieldnameback\"><span class=\"fieldname\"><strong>Death&nbsp;Date</strong></span></td>
		<td class=\"fieldnameback\"><span class=\"fieldname\"><strong>Select This Person</strong></span></td>
		</tr>
		";
	while ( $fetch = mysql_fetch_array($result)) {
        $name = $fetch['firstname']." ".$fetch['lastname'];
		$birthdate = $fetch['birthdate'];
		$deathdate = $fetch['deathdate'];
         if( $fetch['living'] != 0 ) {
			 $birthdate = "";
       		 }

         $id = $fetch['personID'];
		 $page = get_option('user_meta_registration_page');

         $a = "/".$page."?id=".$id;
         $href = "<a href=\"".get_permalink($page)."?id=".$id."\">Select</a>";
         $mytable = $mytable . "<tr><td class=\"databack\"><span class=\"normal\">$id </span></td>
		<td class=\"databack\"><span class=\"normal\">$name</span></td>
		<td class=\"databack\"><span class=\"normal\">$birthdate</span></td>
		<td class=\"databack\"><span class=\"normal\">$deathdate</span></td>
		<td class=\"databack\"><span class=\"normal\">$href</span></td></tr>
		";
	}  //while
		$mytable = $mytable . "</table>
		";
	}  // we have database records
	echo $mytable;
} // we have a query

else {
	echo $form;
}
	$searchform = ob_get_contents();
	ob_end_clean();

	return $searchform;
}
add_shortcode('tng_lookup_ancestor', 'searchnewaccount');

function format_pid ($pid) {
	$pid = ucfirst (str_replace (" ", "", $pid));
	return $pid = is_numeric ($pid) ? "I$pid" : $pid;
}

?>