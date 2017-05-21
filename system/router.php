<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Router
    {
        /** 
         * Variables
         */
        public $methodExists;
        
        
        /** 
         * __construct
         * 
         * @access public
         */
        public function __construct()
        {
            $this->method_exists = false;   
        }
        
        
        /** 
         * Initiate the router
         * 
         * @access  public
         * @param   string  $class
         * @param   string  $method
         */
        public function init( $class = '', $method = '' )
        {
            // set the controller
            $controller = $class != '' ? $class : Config::HOME;
            
            // the default method
            $default_method = Config::ACTION_METHOD . Config::DEF_ACTION;

            // include controller and return object
            $main = load_controller( $controller );
            
            // check for requested method 
            $method = $method != '' ? Config::ACTION_METHOD . $method : $default_method;
                            
            // check if the requested method exists
            $this->method_exists = method_exists( $main, $method ) ? true : false;
            
            // if method does not exist then load the error
            if ( !$this->method_exists )
            {   
                // include error controller file
                $controller = Config::DEF_ERROR;
                
                // include controller and return object
                $main = load_controller( $controller );
                
                // define the "notfound" method
                $method = $default_method;
            }
            
            // now that we now the class and method, check for arguments/parameters
            $params = array();
            $args = new ReflectionMethod( $controller, $method );
            $args = $args->getParameters();
            
            if ( !empty( $args ) )
            {
                // this method has arguments
                $total_params = count( $args );
                $full_url     = full_site_url();
                $url_array    = explode( '/' . str_replace( Config::ACTION_METHOD, '', $method ), $full_url );
                
                if ( !empty( $url_array[ 1 ] ) )
                {
                    $break = explode( '/', trim( $url_array[ 1 ], '/' ) );
                    
                    for ( $c = 0; $c < $total_params; ++$c )
                    {
                        $param = isset( $break[ $c ] ) ? $break[ $c ] : null;
                        
                        if ( !empty( $param ) )
                        {
                            $params[] = $param;
                        }
                    }
                }
            }
            
            // display compiled page
            return call_user_func_array( 
                array( 
                    $main, 
                    $method 
                ), 
                $params 
            );
        }
    }