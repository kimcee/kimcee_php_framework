<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Login_Model extends Database
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
         * Login the user
         * 
         * @access  public
         * @param   string  $email
         * @param   string  $passwrod
         */
        public function login_user( $email, $password )
        {
            // query for user
            $user = $this->mq( 
                "select `id` from `" . User::DB_TABLE_USERS . "` where `email` = ? and `password` = ?"
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
        
        
        /** 
         * Save token in db
         * 
         * @access  public
         * @param   int     $user_id
         * @param   string  $token
         */
        public function save_user_token( $user_id, $token )
        {
            // update user's token
            $this->update( 
                User::DB_TABLE_USERS
                , array( 'token', 'token_expires' )
                , array( $token, date( 'Y-m-d', strtotime( '+1 day' ) ) )
                , array( 'id' )
                , array( $user_id )
            );
            
            return true;
        }
    }