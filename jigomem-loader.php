<?php
/**
 * Plugin Name: Jigoshop Membership
 * Plugin URI:  https://github.com/Themekraft/BP-Shop-Integration
 * Description: Transforms a type of product into a membership option
 * Author:      BP Shop Dev Team
 * Version:     1.0.1
 * Author URI:  https://github.com/Themekraft/BP-Shop-Integration
 * Network:     true
 * 
 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class JIGOMEM_Loader
{
	/**
	 * The plugin version
	 */
	const VERSION 	= '1.0.1';
	
	/**
	 * Minimum required WP version
	 */
	const MIN_WP 	= '3.2.1';
	
	/**
	 * Minimum required BP version
	 */
	const MIN_BP 	= '1.5';

	/**
	 * Minimum required Jigoshop version
	 */
	const MIN_JIGO 	= '0.9.9';
		
	/**
	 * Minimum required PHP version
	 */
	const MIN_PHP 	= '5.2.4';

	/**
	 * Name of the plugin folder
	 */
	static $plugin_name;

	/**
	 * Can the plugin be executed
	 */
	static $active = false;
	
	/**
	 * PHP5 constructor
	 * 
	 * @since 	1.0
	 * @access 	public
	 * @uses	plugin_basename()
	 * @uses	add_action()
	 */
	public function init()
	{
		self::$plugin_name = plugin_basename( __FILE__ );

		self::constants();
		
		add_action( 'plugins_loaded', 	array( __CLASS__, 'check_requirements' ), 10 );
		add_action( 'plugins_loaded', 	array( __CLASS__, 'start' 			   ), 12 );
	}

	/**
	 * Load all related files
	 * 
	 * Attached to bp_include. Stops the plugin if certain conditions are not met.
	 * 
	 * @since 	1.0
	 * @access 	public
	 */
	public function start()
	{
		if( self::$active === false )
			return false;

		// core component
		require( JIGOMEM_ABSPATH .'core/jigomem-core.php' );
		
		if( is_admin() )
			require( JIGOMEM_ABSPATH .'core/jigomem-admin.php' );
	}
	
	/**
	 * Check for required versions
	 * 
	 * Checks for WP, BP, PHP and Jigoshop versions
	 * 
	 * @since 	1.0
	 * @access 	private
	 * @global 	string 	$wp_version 	Current WordPress version
	 * @return 	boolean
	 */
	public function check_requirements()
	{		
		global $wp_version;

		$error = false;
		
		// BuddyPress checks
		if( ! defined( 'BP_VERSION' ) )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'Jigoshop Membership needs BuddyPress to be installed. <a href="%s">Download it now</a>!\', "jigomem" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			$error = true;
		}
		elseif( version_compare( BP_VERSION, self::MIN_BP, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'Jigoshop Membership works only under BuddyPress %s or higher. <a href="%s">Upgrade now</a>!\', "jigomem" ) . \'</strong></p></div>\', JIGOMEM_Loader::MIN_BP, admin_url("update-core.php") );' ) );
			$error = true;
		}
		
		// Jigoshop checks
		if( ! defined( 'JIGOSHOP_VERSION' ) )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'Jigoshop Membership needs Jigoshop to be installed. <a href="%s">Download it now</a>!\', "jigomem" ) . \'</strong></p></div>\', admin_url("plugin-install.php") );' ) );
			$error = true;
		}		
		elseif( version_compare( JIGOSHOP_VERSION, self::MIN_JIGO, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'Jigoshop Membership works only under Jigoshop %s or higher. <a href="%s">Upgrade now</a>!\', "jigomem" ) . \'</strong></p></div>\', JIGOMEM_Loader::MIN_JIGO, admin_url("update-core.php") );' ) );
			$error = true;
		}
		
		// WordPress check
		if( version_compare( $wp_version, self::MIN_WP, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'Jigoshop Membership works only under WordPress %s or higher. <a href="%s">Upgrade now</a>!\', "jigomem" ) . \'</strong></p></div>\', JIGOMEM_Loader::MIN_WP, admin_url("update-core.php") );' ) );
			$error = true;
		}
		
		// PHP check
		if( version_compare( PHP_VERSION, self::MIN_PHP, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Shop works only under PHP %s or higher. Please ask your hosting company for support!\', "jigomem" ) . \'</strong></p></div>\', JIGOMEM_Loader::MIN_PHP );' ) );
			$error = true;
		}
		
		self::$active = ( ! $error ) ? true : false;
	}
		
	/**
	 * Declare all constants
	 * 
	 * @since 	1.0
	 * @access 	private
	 */
	private function constants()
	{
		define( 'JIGOMEM_PLUGIN', 	self::$plugin_name );
		define( 'JIGOMEM_VERSION',	self::VERSION );
		define( 'JIGOMEM_FOLDER',	plugin_basename( dirname( __FILE__ ) ) );
		define( 'JIGOMEM_ABSPATH',	trailingslashit( str_replace( "\\", "/", WP_PLUGIN_DIR .'/'. JIGOMEM_FOLDER ) ) );
		define( 'JIGOMEM_URLPATH',	trailingslashit( plugins_url( '/'. JIGOMEM_FOLDER ) ) );
	}
}

// Get it on!!
JIGOMEM_Loader::init();
?>