<?php

class WP_4G_Notice
{
	const ADMIN_ERROR = 'admin_error';

	public static function throw_notice($type, $msg) {

		switch($type)
		{
			case "admin_error":
				selft::print_notice('error',$msg);
			break;
			default:
				throw new Exception("Notice type not recognized.", 1);
			break;
				
		}

	}

	private static function print_notice($class, $msg){
	    
	    echo '<div class="'.$class.'"><p>'.$msg.'</p></div>';
	}
}