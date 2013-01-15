<?php

/**
 *  Plugin Name:   TNG User Management
 *  Plugin URI:    http://heatherfeuer.github.com/TNG_User_Meta/
 *  Description:   Manage your TNG and WordPress users all from WordPress. The plugin provides shortcodes for custom registration and a front-end user profile page. 
 *  Version: 	   2.0
 *  Date:          1/4/13
 *  Author:        Heather Feuerhelm
 *  Author URI:    http://uniquelyyours.blogdns.com
 */
 
TNGUserMgmt::init(); 

/** 
*	TNG User Mgmt
*
*	This class sets up all the required files and constants for the plugin.
*
*	@author		Nate Jacobs
*	@date		1/4/13
*	@since		2.0
*/
class TNGUserMgmt
{
	/** 
	*	Initialize
	*
	*	Hook into WordPress and prepare all the methods as necessary.
	*
	*	@author		Nate Jacobs
	*	@date		1/4/13
	*	@since		2.0
	*
	*	@param		
	*/
	public static function init()
	{
		add_action( 'plugins_loaded', array( __CLASS__, 'constants' ), 1 );
		add_action( 'plugins_loaded', array( __CLASS__, 'includes' ), 2 );
		add_action( 'plugins_loaded', array( __CLASS__, 'admin' ), 3 );
		add_action( 'init', array( __CLASS__, 'localization' ) );
		
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
		register_deactivation_hook( __FILE__, array( __CLASS__, 'deletion' ) );
	}

	/** 
 	*	Localization
 	*
 	*	Add support for localization
 	*
 	*	@author		Nate Jacobs
 	*	@date		1/4/13
 	*	@since		2.0
 	*
 	*	@param		
 	*/
 	public function localization() 
 	{
  		load_plugin_textdomain( 'tng-user-mgmt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}

	/**
	 *	Plugin Constants
	 *
	 *	Constants used throughout plugin are defined for later use.
	 *
	 *	@author		Nate Jacobs
	 *	@date		1/4/13
	 *	@since 		2.0
	 *
	 *	@param		null
	 */	
	public function constants() 
	{
		define( 'TNGUSERMGMT_VERSION', '2.0' );
		define( 'TNGUSERMGMT_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'TNGUSERMGMT_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'TNGUSERMGMT_INCLUDES', TNGUSERMGMT_DIR.trailingslashit( 'inc' ) );
		define( 'TNGUSERMGMT_ADMIN', TNGUSERMGMT_DIR.trailingslashit( 'admin' ) );
	}
	
	/**
	 *	Include Files
	 *
	 *	Lists the files used for plugin actions in the includes folder.
	 *	They are stored in the inc folder. These files are able to be accessed from both front and back end of site.
	 *
	 *	@author		Nate Jacobs
	 *	@date		1/4/13
	 *	@since 		2.0
	 *
	 *	@param		null
	 */
	public function includes()
	{
		require_once( TNGUSERMGMT_INCLUDES . 'class-registration.php' );
		require_once( TNGUSERMGMT_INCLUDES . 'class-utilities.php' );
		require_once( TNGUSERMGMT_INCLUDES . 'class-profile.php' );
	}
	
	/**
	 *	Admin Files
	 *
	 *	Lists the files used for plugin actions in the admin dashboard. 
	 *	They are stored in the admin folder. These files are only able to accessed from the back end of the site
	 *
	 *	@author		Nate Jacobs
	 *	@date		1/4/13
	 *	@since 		2.0
	 *
     *	@param		null
	 */
	public function admin()
	{
		if ( is_admin() ) 
		{
			require_once( TNGUSERMGMT_ADMIN . 'class-settings.php' );
		}
	}
	
	/** 
	*	Activation
	*
	*	Runs the method when the plugin is activated.
	*	Verifies the TNG plugin is installed and activated
	*
	*	@author		Nate Jacobs
	*	@date		1/4/13
	*	@since		2.0
	*
	*	@param		null
	*/
	public function activation()
	{
		// checking if plugin is inactive or not installed.
		if( is_plugin_inactive( 'tng-wordpress-plugin/tng.php' ) )
		{
			// okay, tng-wp plugin is missing
			// get the data from the this plugin file
			$plugin_data = get_plugin_data( __FILE__, false );
			
			// deactivate this plugin
			deactivate_plugins( plugin_basename( __FILE__ ) );
			
			// let the user know to install or activate the tng-wp integration plugin
			wp_die( "<strong>".$plugin_data['Name']." version ".$plugin_data['Version']."</strong> requires the <a href='http://wordpress.org/extend/plugins/tng-wordpress-plugin/'>TNG WordPress Integration plugin</a> to be activated. ".$plugin_data['Name']." has been deactivated. Please install and activate the Integration plugin and try again.", 'TNG User Management Activation Error', array( 'back_link' => true ) );
		}
	}
	
	/** 
	*	Deletion
	*
	*	Runs the method when the plugin is deleted.
	*
	*	@author		Nate Jacobs
	*	@date		1/4/13
	*	@since		2.0
	*
	*	@param	null	
	*/
	public function deletion()
	{
		// remove all settings
	}
}
