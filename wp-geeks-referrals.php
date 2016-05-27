<?php
/*
Plugin Name: 4Geeks Referals
Plugin URI: https://portfolio.alesanchezr.com/4Geeks_Referrals
Description: Generate referral URLS and track all user referrals
Version: 1.0
Author: Alejandro Sanchez
Author URI: http://www.alesanchezr.com
License: GPL2
*/
/*
Copyright 2012  Alejandro Sanchez  (email : aalejo@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('WP_Geeks_Referrals'))
{
	require_once('class/Model.class.php');
	require_once('class/Response.class.php');
	require_once('class/4geeks/WP_4G_Notice.class.php');
	
	class WP_Geeks_Referrals
	{
		/** Refers to a single instance of this class. */
    	private static $instance = null;

		const GEEKS_REFERRALS_VERSION	= "geeks_referrals_version";
		private $model = null;
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once('class/Settings.class.php');
			$WP_Geeks_Referrals_Settings = new WP_Geeks_Referrals_Settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));

			$this->model = new WP_Geeks_Referrals_Model();
		} // END public function __construct

	    /**
	     * Creates or returns an instance of this class.
	     *
	     * @return  Foo A single instance of this class.
	     */
	    public static function get_instance() {
	 
	        if ( null == self::$instance ) {
	            self::$instance = new self;
	        }
	 
	        return self::$instance;
	 
	    } // end get_instance;

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			WP_Geeks_Referrals::plugin_create_db();
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=WP_Geeks_Referrals">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		private static function plugin_create_db()
		{
			$version = get_option( self::GEEKS_REFERRALS_VERSION, '1.0' );
			
        	try
        	{
	            WP_Geeks_Referrals_Model::create_db($version);
	        }
        	catch(Exeption $e)
        	{
        		WP_4G_Notice::throw_notice(WP_4G_Notice::ADMIN_ERROR,$e->getMessage());
        	}

		}

		/**
		 * Summary.
		 *
		 * This function is called from the theme / website. Stores a new visitor and
		 * generates a new referral code.
		 *
		 * @return type Returns a JSON with the reslt of the operation.
		 */
        public function save_new_referral($externalId, $referralHash, $otherData='') {

        	try
        	{
		        $hash = $this->model->get_random_hash();

		        if(!$hash or $hash=='') $hash = '1'.rand(0,6);
		        $referral = $this->model->save_referral(array( 
		                'external_id' => $externalId, 
		                'referred_by' => $referralHash, 
		                'other' => $otherData, 
		                'user_hash' => $hash
		            ));

		        return $referral;

		    }catch(Exeption $e){
		    	WP_4G_Notice::throw_notice(WP_4G_Notice::ADMIN_ERROR,$e->getMessage());
		    }

		    return null;

        }

		/**
		 * Summary.
		 *
		 * This function is called internally in the plugin, generates a referral code.
		 *
		 * @return type Returns a JSON with the reslt of the operation.
		 */
        public function save_geeks_referrals() 
        {
        	header('Content-Type: application/json');

        	try
        	{
	            $hash = $this->model->get_random_hash();

	            $obj = $this->model->save_referral(array( 
	                    'external_id' => $_POST['external_id'],
	                    'referred_by' => $_POST['referred_by'],
	                    'other' => $_POST['other'],
	                    'user_hash' => $hash
	                ));

	            echo Response::wrapResult($obj);

        	}
        	catch(Exeption $e)
        	{
            	echo Response::wrapFault($e->getMessage());
        	}

            exit;
        }

		/**
		 * Summary.
		 *
		 * This function is called internally in the plugin, searchs of an "id" in the _POST array
		 * and deletes any referral with that id.
		 *
		 * @return type Returns a JSON with the reslt of the operation.
		 */
        public function delete_geeks_referrals() 
        {
        	header('Content-Type: application/json');

        	try
        	{
            	$this->model->delete_referral($_POST['id']);
            	
            	echo Response::wrapResult("ok");
        	}
        	catch(Exeption $e)
        	{
            	echo Response::wrapFault($e->getMessage());
        	}

            exit;
        }

	} // END class WP_Geeks_Referrals
} // END if(!class_exists('WP_Geeks_Referrals'))

if(class_exists('WP_Geeks_Referrals'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_Geeks_Referrals', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_Geeks_Referrals', 'deactivate'));


	// instantiate the plugin class
	$WP_Geeks_Referrals = WP_Geeks_Referrals::get_instance();

	//For internal use of the plugin, 2 methods to save and delete referrals
	add_action( 'wp_ajax_save-geeks-referrals', array($WP_Geeks_Referrals, 'save_geeks_referrals'));
	add_action( 'wp_ajax_delete-geeks-referrals', array($WP_Geeks_Referrals, 'delete_geeks_referrals'));
	
	/**
	*	Here i'm going to store referral code of any incoming visitor, if the URL has the "rhs" GET param, that
	*	means that is comming referred by another visitor. 
	*/
	if ( !session_id() )
	{
		session_start();
		if(isset($_GET["rhs"])) $_SESSION['geeks_referral_url-ref-hash'] = $_GET["rhs"];
	}
}
