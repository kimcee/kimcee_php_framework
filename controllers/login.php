<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location controllers/
//-------------------------------------------------------------------------------

    class Login extends Theme
    {   
        /** 
         * Constants
         */
        const ROOT        = 'login';
        const SESSION     = 'session';
        const POST        = 'post';
        const TOKEN       = 'token';
        const EMAIL       = 'email';
        const PASSWORD    = 'password';
        const LOGIN_ERROR = 'login_error';
        const ERROR_1     = 'All fields are required';
        const ERROR_2     = 'Invalid email';
        const ERROR_3     = 'Incorrect email/password combination';
        
        
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            // the model
            $this->model = load_model( self::ROOT );
            
            // theme
            $this->theme( User::DEF_THEME_LOGIN );
            
            // the template
            $this->tpl = new Template;
            
            // all templates
            $this->_root    = self::ROOT . '/';
            $this->_index   = $this->_root . 'index';
        }
        
        
        /** 
         * Index
         *
         * @access  public
         */
        public function action_index()
        {
            // no need to show this page if the user is already logged in
            $this->check_if_logged_in();
            
            // the template
            $this->tpl->load_template( $this->_index );
            
            $error = Super_Global::get( self::SESSION, self::LOGIN_ERROR );
            
            if ( $error != '' )
            {
                $this->tpl->build_if( 'login_fail' );
                $this->tpl->build_var( 'response', $error );
            }
            
            // clear errors
            $this->clear_errors();
            
            // check for login attempt
            $this->attempt();
            
            // return the results
            return $this->build_display( $this->tpl );
        }
        
        
        /** 
         * Check if the user is logged in
         * 
         * @access  private
         */
        private function check_if_logged_in()
        {
            $user = user();
            
            if ( !empty( $user ) )
            {
                $this->send_to_admin();
            }
        }
        
        
        /** 
         * Clear errors
         * 
         * @access  private
         */
        private function clear_errors()
        {
            // clear session for login errors
            Super_Global::set( self::SESSION, self::LOGIN_ERROR, null );
        }
        
        
        /** 
         * Attempt
         * 
         * @access  private
         */
        private function attempt()
        {
            if ( Super_Global::get( self::POST, self::EMAIL ) )
            {
                $this->retry  = false;
                $this->email  = Super_Global::get( self::POST, self::EMAIL    );
                $this->pass   = Super_Global::get( self::POST, self::PASSWORD );
                
                // check for empty fields
                $this->check_for_empty_fields();
                
                // check for valid fields
                $this->check_for_valid_fields();
                
                // validate login attempt
                $login_status = $this->check_for_valid_login();
                
                // do we need to retry?
                $this->check_for_retry();
                
                // we have logged in
                if ( $login_status )
                {
                    // redirect to admin url
                    $this->send_to_admin();
                }
            }
        }
        
        
        /** 
         * Redirect to admin
         * 
         * @access  private
         */
        private function send_to_admin()
        {
            // redirect to admin
            Utility::redirect( site_url() . User::ADMIN_URL );
        }
        
        
        /** 
         * Confirm no empty fields
         * 
         * @access  private
         */
        private function check_for_empty_fields()
        {
            // validate we have all fields
            if ( empty( $this->email ) || empty( $this->pass ) )
            {
                Super_Global::set( 
                    self::SESSION
                    , self::LOGIN_ERROR
                    , self::ERROR_1
                );
                
                $this->retry = true;
            }
        }
        
        
        /** 
         * Check that all fields are validated
         * 
         * @access  private
         */
        private function check_for_valid_fields()
        {
            // validate we have all fields
            if ( !Utility::is_email( $this->email ) )
            {
                Super_Global::set( 
                    self::SESSION
                    , self::LOGIN_ERROR
                    , self::ERROR_2
                );
                
                $this->retry = true;
            }
        }
        
        
        /** 
         * Try to login the user
         * 
         * @access  private
         */
        private function check_for_valid_login()
        {
            // encrypted password
            $formatted_email = strtolower( $this->email );
            $encrypted_pass  = Utility::encrypt_password( $this->pass );
            
            // attempt to login
            $login_success = $this->model->login_user( 
                $formatted_email
                , $encrypted_pass 
            );
            
            // check that we were able to login
            if ( empty( $login_success[ 'user_id' ] ) )
            {
                Super_Global::set( 
                    self::SESSION
                    , self::LOGIN_ERROR
                    , self::ERROR_3
                );
                
                $this->retry = true;
                
                return false;
            }
            else
            {
                // set the token
                $this->set_token( 
                    $login_success[ 'user_id' ]
                    , $login_success[ 'token' ] 
                );
                
                return true;
            }
        }
        
        
        /** 
         * Set Token
         * 
         * @access  private
         * @param   int     $user_id
         * @param   string  $token
         */
        private function set_token( $user_id, $token )
        {
            // update session
            Super_Global::set(
                self::SESSION
                , self::TOKEN
                , $token
            );
            
            // update database
            $this->model->save_user_token( $user_id, $token );
            
            // validate that session was saved
            if ( Super_Global::get( self::SESSION, self::TOKEN ) == $token )
            {
                return true;
            }
            
            return false;
        } 
        
        
        /** 
         * Check if we need to retry the login process
         * 
         * @access  private
         */
        private function check_for_retry()
        {
            // refresh the page to retry login
            if ( $this->retry )
            {
                Utility::redirect( site_url() . User::LOGIN_URL );
            }
        }
    }