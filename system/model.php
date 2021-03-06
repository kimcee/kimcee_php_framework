<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Model extends Load_Class
    {
        /** 
         * Variables
         * 
         * @access  public
         */
        public $model;
        
           
        /** 
         * The construct
         * 
         * @access  public
         * @param   string  $file_name
         */
        public function __construct( $file_name )
        {
            $this->include_file( Config::MODEL, $file_name );
        }
    }