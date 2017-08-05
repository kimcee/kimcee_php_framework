<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Admin_Model extends Database
    {
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            // code
        }
        
        
        /** 
         * Get tracking data
         * 
         * @access  public
         */
        public function get_tracking_data()
        {
            // query for user
            $user = $this->mq( 
                "select * from `" . User::DB_TABLE_USERS . "` where `email` = ? and `password` = ?"
                , array( $email, $password )
                , 1
            );
            
            // check for token
            if ( !empty( $user[ 'id' ] ) )
            {
                // generate token
                $token = uniqid();
                
                return array( 
                    'user_id' => $user[ 'id' ]
                    , 'token' => $token
                );
            }
            
            // no token found, return false
            return false;
        }
    }