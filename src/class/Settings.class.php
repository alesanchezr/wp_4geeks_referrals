<?php
if(!class_exists('WP_Geeks_Referrals_Settings'))
{
	class WP_Geeks_Referrals_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('WP_Geeks_Referrals-group', 'setting_a');

        	// add your settings section
        	add_settings_section(
        	    'WP_Geeks_Referrals-section', 
        	    '4Geeks Referrals Settings', 
        	    array(&$this, 'settings_section_WP_Plugin_Template'), 
        	    'WP_Geeks_Referrals'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'WP_Geeks_Referrals-setting_a', 
                'Referrals detination email', 
                array(&$this, 'settings_field_input_text'), 
                'WP_Geeks_Referrals', 
                'WP_Geeks_Referrals-section',
                array(
                    'field' => 'setting_a'
                )
            );

            add_action( 
                'admin_enqueue_scripts', 
                array(&$this, 'load_custom_wp_admin_style') 
                );

        } // END public static function activate
        
        public function settings_section_WP_Plugin_Template()
        {
            // Think of this as help text for the section.
            echo 'These settings do things for the WP Plugin Template.';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" class="form-control" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)        
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    '4Geeks Referrals Settings', 
        	    '4Geeks Referrals', 
        	    'manage_options', 
        	    'WP_Geeks_Referrals', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
            $model = new WP_Geeks_Referrals_Model();
            $view_data["referrals"] = $model->getReferralLinks();

            
        	// Render the settings template
        	include(sprintf("%s/../templates/settings.php", dirname(__FILE__)));

        } // END public function plugin_settings_page()

        public function load_custom_wp_admin_style($hook_suffix) {

            if($hook_suffix == 'settings_page_WP_Geeks_Referrals')
            {
                wp_register_style( 'custom_wp_admin_css', plugins_url('wp-geeks-referrals') . '/css/styles.css', false, '1.0.0' );
                wp_enqueue_style( 'custom_wp_admin_css' );

                wp_enqueue_script( 'bootstrap_js', plugins_url('wp-geeks-referrals') . '/js/bootstrap.min.js', array('jquery'), '1.0.0', true );
            }
        }

    } // END class WP_Geeks_Referrals_Settings
} // END if(!class_exists('WP_Geeks_Referrals_Settings'))
