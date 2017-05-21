<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Utility
    {
        /** 
         * Domain
         * 
         * @acces   public
         * @note    update http to https 
         */
        public static function domain()
        {
            return Config::BASE_URL;
        }
        
        
        /** 
         * Site URL
         * 
         * @acces   public
         */
        public static function site_url()
        {
            return self::domain() . ( Config::SUB_DIR != '' ? Config::SUB_DIR : '' ) . ( Config::ROOT != '' ? Config::ROOT : '' ); 
        }
        
        
        /** 
         * Header location / redirect
         * 
         * @access  public
         * @param   string      $url
         */
        public static function redirect( $url )
        {
            header( 'Location: ' . $url );
            exit;
        }
        
        
        /** 
         * Meta refresh
         * 
         * @access  public
         * @param   string      $url
         */
        public static function refresh( $url )
        {
            return '<p>meta refresh coming soon</p>';
        }
    
    
        /** 
         * sanitize string
         * 
         * @param   string      $string
         */
        public static function sanitize( $string = '' )
        {
            // remove html and php
            $string = self::nohtml( $string );
            
            // remove other nonsense characters
            return preg_replace( "/[^a-z0-9\.]/", '', strtolower( $string ) );
        }
        
        
        /** 
         * Remove html
         * 
         * @param   string      $string
         */
        public static function nohtml( $string = '' )
        {
            return strip_tags( $string );
        }
        
        
        /** 
         * Validate email
         * 
         * @param   string      $email
         */
        public static function is_email( $email )
        {
            return !filter_var( $email, FILTER_VALIDATE_EMAIL ) ? false : true;
        }
        
        
        /** 
         * Encrypt Password
         * 
         * @param   string      $password
         */
        public static function encrypt_password( $password )
        {
            return sha1( $password );
        }
        
        
        /** 
         * Slugify Name
         * 
         * @param   string      $string
         * @note    need to modify this
         */
        public static function slug( $string )
        {
            $spacer = '00000';
            $string = str_replace( ' ', $spacer, strtolower( $string ) );
            $string = preg_replace( "/[^A-Za-z0-9 ]/" , '' , $string );
            $string = str_replace( $spacer, '-', $string );
            $string = implode( '-', array_unique( explode( '-', $string ) ) );
            
            return $string;
        }
        
        
        /** 
         * Integer to string
         * 
         * @param   int     $int
         */
        public function int_to_str( $int )
        {
            $string = 'zero';
            
            switch ( $int )
            {
                case 1; $string = 'one'; break;
                case 2; $string = 'two'; break;
                case 3; $string = 'three'; break;
                case 4; $string = 'four'; break;
                case 5; $string = 'five'; break;
                case 6; $string = 'six'; break;
                case 7; $string = 'seven'; break;
                case 8; $string = 'eight'; break;
                case 9; $string = 'nine'; break;
                case 10; $string = 'ten'; break;
            }
            
            return $string;
        }
         
    }