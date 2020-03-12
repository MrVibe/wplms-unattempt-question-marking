<?php
/**
 * Configure Wplms_Unattempt_Question_Filters
 *
 * @class       Wplms_Unattempt_Question_Filters
 * @author      VibeThemes
 * @category    Admin
 * @package     Wplms_Unattempt_Question_Filters
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 



class Wplms_Unattempt_Question_Filters{


	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new Wplms_Unattempt_Question_Filters();
        return self::$instance;
    }

	private function __construct(){
		
	}


}

Wplms_Unattempt_Question_Filters::init();