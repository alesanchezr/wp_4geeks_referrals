<?php


if(!class_exists('WP_Geeks_Referrals_Model'))
{
	class WP_Geeks_Referrals_Model
	{

        public static function create_db($version)
        {
            global $wpdb;
            
            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . 'geeks_referrals';

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
                external_id tinytext NOT NULL,
                referred_by tinytext NOT NULL,
                user_hash tinytext NOT NULL,
                other longtext NOT NULL,
                UNIQUE KEY id (id)
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
        /**
         * hook into WP's admin_init action hook
         */
        public function getReferralLinks()
        {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'geeks_referrals ORDER BY time DESC', OBJECT );  

			if ($results === false) throw new Exception("Error Processing Request", 1);    	
			return $results;
        }

        public function get_random_hash()
        {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT SUBSTRING(MD5(RAND()) FROM 1 FOR 6) AS random_number
											FROM '.$wpdb->prefix.'geeks_referrals
											WHERE "random_number" NOT IN (SELECT user_hash FROM '.$wpdb->prefix.'geeks_referrals) LIMIT 1', OBJECT );  
			
            if ($results === false) throw new Exception("Error Processing Request", 1);
            
            if(count($results)>0) return $results[0]->random_number; 
            else return null;
        }

        public function save_referral($referral)
        {
            global $wpdb; // this is how you get access to the database
            
            $result = $wpdb->insert( 
                $wpdb->prefix.'geeks_referrals', $referral
                );

            if ($result === false) throw new Exception("Error Processing Request", 1);

            $objResult = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'geeks_referrals WHERE id = '.$wpdb->insert_id, OBJECT );
            if(count($objResult)>0) return $objResult[0];
            else return null;
        }

        public function delete_referral($id)
        {
            global $wpdb; // this is how you get access to the database

            $result = $wpdb->delete( 
                $wpdb->prefix.'geeks_referrals', array( 'id' => $id )
                );

            if ($result === false) throw new Exception("Error Processing Request", 1);
            return $result;
        }
	}
}

?>