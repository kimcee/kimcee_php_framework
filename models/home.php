<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Home_Model extends Database
    {
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            
        }
        
        
        /** 
         * Testing functionality
         * 
         * @access  public
         */
        public function testing()
        {
            return 'food';
        }
    }