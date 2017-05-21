<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class User extends Database
    {
        /** 
         * Variables
         * 
         * @access  private
         */
        private $id;
        private $token;
        private $user_data;
        
        
        /** 
         * Variables
         * 
         * @access  public
         */
        public $logged_in;


        /** 
         * Constants
         *
         * @access  public
         */
        const ADMIN_URL           = '/admin';  
        const LOGIN_URL           = '/login'; 
        const DEF_THEME_LOGIN     = 'login';
        const DB_TABLE_USERS      = '[p]users';
        const DB_TABLE_USERS_META = '[p]users_meta';
        
        
        /** 
         * The construct
         * 
         * @access  public
         * @param   int     $user_id    If empty then return logged in user data
         */
        public function __construct( $user_id = null )
        {
            $this->logged_in = false;
            
            if ( !empty( $user_id ) )
            {
                // custom user defined
                $this->find_token( $user_id );
            }
            else
            {
                // check for logged in user
                $this->find_token();
            }
            
            if ( !empty( $this->token ) )
            {
                // we have a logged in user
                $this->get_user_data();
            }
        }
        
        
        /** 
         * Look for token and validate
         * 
         * @access  private
         * @param   int     $user_id    optional
         */
        private function find_token( $user_id = null )
        {
            if ( $user_id != '' )
            {
                $this->get_token = $this->mq( 
                    "select `token` from `" . self::DB_TABLE_USERS . "` where `id` = ?"
                    , $user_id
                    , 1
                );
                
                if ( !empty( $this->get_token[ 'token' ] ) )
                {
                    $this->token = (string) $this->get_token[ 'token' ];
                }
            }
            else
            {
                // look for session token
                $this->token = Super_Global::get( 'session', 'token' );
                
                // set logged_in flag
                $this->logged_in = true;
                
                // @todo
                // validate token to confirm it has not expired  
            }
        }
        
        
        /** 
         * Get user attribute 
         * 
         * @access  public
         * @param   string  $attribute
         */
        public function get( $attribute = '' )
        {
            if ( $attribute != '' ) 
            {
                return isset( $this->user_data[ $attribute ] ) ? $this->user_data[ $attribute ] : null;
            }
            
            return $this->id;
        }
        
        
        /** 
         * Get user data from DB
         * 
         * @access  private
         */
        private function get_user_data()
        {
            // get standard user data
            $this->user_data = $this->mq( 
                "select * from `" . self::DB_TABLE_USERS . "` where `token` = ? limit 1" 
                , $this->token 
                , 1 
            );
            
            // see if we have user data
            if ( !empty( $this->user_data[ 'id' ] ) )
            {
                // set user id
                $this->id = $this->user_data[ 'id' ];
                
                // return all meta data
                $this->user_meta = $this->mq( 
                    "select `key`, `value` from `" . self::DB_TABLE_USERS_META . "` where `user` = ?" 
                    , $this->id
                );
                
                // validate we have user meta data
                if ( !empty( $this->user_meta ) && is_array( $this->user_meta ) )
                {
                    // loop through all user meta data and add to user data object
                    foreach ( $this->user_meta as $user_meta )
                    {
                        $key = $user_meta[ 'key'   ];
                        $val = $user_meta[ 'value' ];
                        
                        $this->user_data[ $key ] = $val;
                    }
                }
            }
            
            return true;
        }
    }