<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Load_Class 
    {
        /** 
         * Variables
         * 
         * @access  public
         */
        public $type;
        public $available;
        
           
        /** 
         * The construct
         * 
         * @access  public
         * @param   string  $type       controller or model
         * @param   string  $file_path  file name to be loaded
         */
        public function include_file( $type = '', $file_name )
        {
            $this->available  = false;
            $this->type       = $type != '' ? $type : Config::MODEL;
            $this->name       = $this->build_class_name( $file_name );
            $file_path        = ( $this->type == Config::MODEL ? Config::DIR_MODELS : Config::DIR_CONTROLLERS ) . '/' . $file_name . '.php';
            
            if ( is_file( $file_path ) )
            {
                include( $file_path );
                $this->available = true;    
            }
        }
        
        
        /**
         * Return the class name
         * 
         * @access  private
         * @param   string   $file_name
         */
        private function build_class_name( $file_name )
        {
            $class_name = '';
            $explode    = explode( '_', $file_name );
            
            foreach ( $explode as $name )
            {
                $class_name .= ( $class_name != '' ? '_' : '' ) . ucfirst( strtolower( $name ) );
            }
            
            return $class_name . ( $this->type == Config::MODEL ? Config::MODEL_SUFFIX : '' );
        }
        
        
        /** 
         * Initiate controller and attach to variable 
         * 
         * @access  public
         */
        public function init()
        {
            if ( $this->available )
            {
                $class = $this->type;
                $name  = $this->name;
                
                $this->$class = new $name;
            } 
        }
    }