<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------
	
    class Super_Global
    {
        /** 
         * Constants
         */
        const SG_GET     = 'get';
        const SG_POST    = 'post';
		const SG_FILES   = 'files';
        const SG_SERVER  = 'server';
        const SG_REQUEST = 'request';
        const SG_COOKIE  = 'cookie';
        const SG_SESSION = 'session';
        const CLEAR      = 'clear';
        
        
        /**
         * Get value from super global
         *
         * @access  public
         * @param   string  $global     Shorthand name for global (get, post, request)
         * @param   string  $index      The key for which to return a value
         */
        public static function get( $global, $key = '' )
        {
            $super_global = self::find_super_global( $global );
            
            if ( is_array( $super_global ) )
            {
                // we have a super global to work with
                if ( $key != '' )
                {
                    if ( isset( $super_global[ $key ] ) )
                    {
                        // return key value from super global
                        return $super_global[ $key ];
                    }
                }
                else
                {
                    // no key provided so return full super global array
                    return $super_global;
                }
            }
            
            // if we made it this far then something is missing so return false
            return false;
        }
        
        
        /** 
         * Find the super global
         * 
         * @access  private
         * @param   string  $global
         */
        private static function find_super_global( $global )
        {
            switch ( $global )
            {
                case self::SG_GET:
                    $super_global = $_GET;
                    break;
                    
                case self::SG_POST:
                    $super_global = $_POST;
                    break;
                    
                case self::SG_FILES:
                    $super_global = $_FILES;
                    break;
                    
                case self::SG_REQUEST:
                    $super_global = $_REQUEST;
                    break;
                    
                case self::SG_SERVER:
                    $super_global = $_SERVER;
                    break;
                    
                case self::SG_COOKIE:
                    $super_global = $_COOKIE;
                    break;
                    
                case self::SG_SESSION:
                    $super_global = $_SESSION;
                    break;
            }
            
            if ( isset( $super_global ) )
            {
                return $super_global;
            }
            
            return false;
        }
       
        
        /**
         * Set value of super global
         *
         * @access  public
         * @param   string  $global     Shorthand name for global, can only be session or cookie
         * @param   string  $index      The key for which to set the $value
         * @param   string  $key        The value that will be set
         */
        public static function set( $global, $key, $value = '' )
        {
            $global = $global == self::SG_COOKIE || $global == self::SG_SESSION ? $global : false;
            $value  = $value != '' ? $value : self::CLEAR;
            
            if ( !empty( $global ) )
            {
                return self::$global( $key, $value );
            }
            
            return false;
        }


        /**
         * $_COOKIE
         *
         * @access  private
         * @param   string  $index  The key
         * @param   string  $set    The value to associate with this key ("clear" to clear out the value)
         */
        private static function cookie( $index = NULL, $set = false )
        {
            if ( !empty( $set ) && !empty( $index ) )
            {
                setcookie(
                    $index,                     // cookie name
                    self::clean_value( $set ),  // cookie value
                    time() + (86400 * 30),      // expiration date
                    "/"                         // location
                );
                
                return true;
            }
            
            return !empty( $index ) && !empty( $_COOKIE[ $index ] ) ? self::clean_value( $_COOKIE[ $index ] ) : '';
        }
        
        
        /**
         * $_SESSION
         *
         * @access  private
         * @param   string  $index  The key
         * @param   string  $set    The value to associate with this key ("clear" to clear out the value)
         */
        private static function session( $index = NULL, $set = false )
        {
            if ( $set !== false && $set !== 0 && !empty( $index ) )
            {
                $_SESSION[ $index ] = $set == self::CLEAR ? '' : self::clean_value( $set );
                return true;
            }
            
            return !empty( $index ) && isset( $_SESSION[ $index ] ) ? self::clean_value( $_SESSION[ $index ] ) : '';
        }
        
        
        /**
         * Clean the value before returning
         *
         * @access  private
         * @param   string  $value  String to be cleaned
         */
        private static function clean_value( $value )
        {
            // if set to "true" then default to htmlentities
            # $xss_method = $xss_method === true ? 'htmlentities' : $xss_method;
            
            return $value; # $xss_method ? $xss_method( strip_tags( $value ) ) : $value;
        }
    
    }