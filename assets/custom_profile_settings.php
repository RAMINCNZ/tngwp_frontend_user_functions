<?php
// BEGIN Custom User Contact Info
//removes outdated contacts and adds Facebook, Twitter and LinkedIn
 function extra_contact_info($contactmethods) {
 //unset($contactmethods['aim']);
 //unset($contactmethods['yim']);
 //unset($contactmethods['jabber']);
 $contactmethods['facebook'] = 'Facebook';
 $contactmethods['twitter'] = 'Twitter';
 $contactmethods['linkedin'] = 'LinkedIn';
 $contactmethods['telephone'] = 'Phone Number';
 return $contactmethods;
 }
 add_filter('user_contactmethods', 'extra_contact_info');
 /* END Custom User Contact Info */

/* This next section adds Personal Information fields like in TNG */
/* BEGIN New Address Info Section */
function get_user_address_profile_list() {
return Array(
	'address' => 'Address',
	'city' => 'City',
	'state_prov' => 'State/Province',
	'postalcode' => 'Postal Code',
	'country' => 'Country'
);
}
add_action( 'show_user_profile', 'user_address_profile_fields' );
add_action( 'edit_user_profile', 'user_address_profile_fields' );

function user_address_profile_fields( $user ) { ?>
<h3><?php _e("Your Address Information", "blank"); ?></h3>

<table class="form-table">
<?php
	foreach(get_user_address_profile_list() as $key => $value) {
?>
<tr>
	<th>
		<label for="<?php echo $key; ?>"><?php _e($value); ?></label>
	</th>
	<td>
		<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( the_author_meta( $key, $user->ID ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter your $value."); ?></span>
	</td>
</tr>
<?php
	}
?>
</table>
<?php }

add_action( 'personal_options_update', 'save_user_address_profile_fields' );
add_action( 'edit_user_profile_update', 'save_user_address_profile_fields' );

function save_user_address_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

foreach(get_user_address_profile_list() as $key => $value) {
	update_user_meta( $user_id, $key, $_POST[$key] );
}
}
/* END new address information section */

/* BEGIN Relationship Information Section */
function get_user_relationship_profile_list() {
return Array(
'relative' => 'Your Closest Relative in Tree (not yourself)',
'relationship' => 'Your Relationship'
);
}

add_action( 'show_user_profile', 'user_relationship_profile_fields' );
add_action( 'edit_user_profile', 'user_relationship_profile_fields' );

function user_relationship_profile_fields( $user ) { ?>
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
		<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( the_author_meta( $key, $user->ID ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter $value."); ?></span>
	</td>
</tr>
<?php
	}
?>
</table>
<?php }

add_action( 'personal_options_update', 'save_user_relationship_profile_fields' );
add_action( 'edit_user_profile_update', 'save_user_relationship_profile_fields' );
function save_user_relationship_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

foreach(get_user_relationship_profile_list() as $key => $value) {
	update_user_meta( $user_id, $key, $_POST[$key] );
}
}
/* END Relationship Information Section */
?>