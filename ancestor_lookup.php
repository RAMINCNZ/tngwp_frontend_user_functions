<?php
function searchnewaccount() {
	$tng_folder = get_option('mbtng_path');
	include($tng_folder."subroot.php");
	include($tng_folder."config.php");
	include($tng_folder."getlang.php");
	$link = mysqli_connect($database_host, $database_username, $database_password, $database_name) or die("Error: TNG is not communicating with your database. Please check your database settings and try again.");
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
	$first = mysqli_real_escape_string($link, stripslashes($_POST['first']));
	$last = mysqli_real_escape_string($link, stripslashes($_POST['last']));
	$id =mysqli_real_escape_string($link, stripslashes($_POST['idea']));
	$real=format_pid( $id );
	if( !empty( $real ))  // retrieve personID from all trees
		$query = "SELECT * FROM $people_table WHERE personID='$real' ORDER BY lastname, firstname ";
	else if( !empty( $first)  && !empty( $last ))
		$query = "SELECT * FROM $people_table WHERE lastname LIKE '%$last%' AND firstname LIKE '%$first%' ORDER BY lastname, firstname ";
	else if (!empty( $last ) )
		$query = "SELECT * FROM $people_table WHERE lastname LIKE '%$last%' ORDER BY lastname, firstname ";
	else if (!empty( $first ) )
		$query = "SELECT * FROM $people_table WHERE firstname LIKE '%$first%' ORDER BY lastname, firstname ";
	else
		$query = ""; 
}

/// if there is a query, process it 
if( !empty( $query ) ) {

	$result = mysqli_query($link, $query) or die (mysqli_error());
	$count = mysqli_num_rows ($result);
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
	while ( $fetch = mysqli_fetch_array($result)) {
        $name = $fetch['firstname']." ".$fetch['lastname'];
		$birthdate = $fetch['birthdate'];
		$deathdate = $fetch['deathdate'];
        if( $fetch['living'] != 0 ) {
			$birthdate = "";
    	}

         $id = $fetch['personID'];
		$options = get_option('tngwp-frontend-user-functions-options');
		$page = $options['registration_form'];
		$permalink = get_permalink( get_page_by_title( $page ) );
        $href = "<a href=\"".$permalink."?id=".$id."\">Select</a>";
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
add_shortcode('lookup_ancestor', 'searchnewaccount');

function format_pid ($pid) {
	$pid = ucfirst (str_replace (" ", "", $pid));
	return $pid = is_numeric ($pid) ? "I$pid" : $pid;
}

?>