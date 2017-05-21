<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Logout_Model extends Database
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
         * Clear token
         * 
         * @access  public
         */
        public function clear_token()
        {
            $user_token = user( 'token' );
            
            if ( !empty( $user_token ) )
            {
                return $this->update( 
                    User::DB_TABLE_USERS
                    , array( 'token' )
                    , array( '' )
                    , array( 'token' )
                    , array( user( 'token' ) )
                );
            }
            
            return true;
        }
    }