# TNG Role & Capabilities

## Roles
### Guest - guest
* No add rights
	* allow_add = 0
* No edit rights
	* allow_edit = 0
	* tentative_edit = 0
* No delete rights
	* allow_delete = 0
	
### Submitter - subm
* No add rights
	* allow_add = 0
* Allow to submit edits for administrative review
	* allow_edit = 0
	* tentative_edit = 1
* No delete rights
	*allow_delete = 0

### Contributor - contrib
* Allow to add any new data
	* allow_add = 1
* No edit rights
	* allow_edit = 0
	* tentative_edit = 0
* No delete rights
	* allow_delete = 0

### Editor - editor
* Allow to add any new data
	* allow_add = 1
* Allow to edit any existing data
	* allow_edit = 1
	* tentative_edit = 0
* Allow to delete any existing data
	* allow_delete = 1

### Media Contributor - mcontrib
* Allow to add media only
	* allow_add = 3
* No edit rights
	* allow_edit = 0
	* tentative_edit = 0
* No delete rights
	* allow_delete = 0
	
### Media Editor - meditor
* Allow to add media only
	* allow_add = 3
* Allow to edit media only
	* allow_edit = 3
	* tentative_edit = 0
* Allow to delete media only
	* allow_delete = 3
	
### Custom - custom
	
### Administrator - admin
* Allow to add any new data
	* allow_add = 1
* Allow to edit any existing data
	* allow_edit = 1
	* tentative_edit = 0
* Allow to delete any existing data
	* allow_delete = 1

## Capabilities

### Add - allow_add
1		Allow to add any new data

3		Allow to add media only

0		No add rights

### Edit - allow_edit | tentative_edit
1 | 0	Allow to edit any existing data

3 | 0	Allow to edit media only

0 | 1	Allow to submit edits for administrative review (People, Families and Sources only)

0 | 0	No edit rights

### Delete - allow_delete
1		Allow to delete any existing data

3		Allow to delete media only

0		No delete rights

## Extra Permissions

* Allow to view information for living individuals
* Allow to view information for private individuals
* Allow to download GEDCOMs
* Allow to download PDFs
* Allow to view LDS information
* Allow to edit user profile

* Limit to specific tree
* Limit to specific branch

# WordPress Roles and Capabilities

## Roles

* add_role( 'Guest', 'guest' );
* add_role( 'Submitter', 'subm', array( 'tng_submit_edit' => true  ) );
* add_role( 'Contributor', 'contrib', array( 'tng_add_all' => true ) );
* add_role( 'Editor', 'editor', array( 'tng_add_all' => true, 'tng_edit_all' => true, 'tng_delete_all' => true ) );
* add_role( 'Media Contributor', 'mcontrib', array( 'tng_add_media' => true ) );
* add_role( 'Media Editor', 'meditor', array( 'tng_add_media' => true, 'tng_edit_media' => true, 'tng_delete_media' => true ) );

## Capabilities
* tng_add_all
* tng_add_media
* tng_edit_all
* tng_edit_media
* tng_submit_edit
* tng_delete_all
* tng_delete_media
* tng_view_living
* tng_view_private
* tng_dl_gedcom
* tng_dl_pdf
* tng_view_lds
* tng_edit_profile