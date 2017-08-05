<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location controllers/
//-------------------------------------------------------------------------------

    class Admin extends Theme
    {   
        /** 
         * Constants
         */
        const ROOT = 'admin';
        
        
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
            $this->theme( 'main' );
            
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
            
            if ( empty( $user ) )
            {
                header( "Location: " . Utility::site_url() );
                exit;
            }
        }
    }