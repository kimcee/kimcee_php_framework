<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location controllers/
//-------------------------------------------------------------------------------

    class Logout extends Theme
    {   
        /** 
         * Constants
         */
        const ROOT = 'logout';
        
        
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
            // the template
            $this->tpl->load_template( $this->_index );
            
            // the template
            $this->model->clear_token();
            
            // clear session
            Super_Global::set( 'session', 'token', null );
            
            // return the results
            return $this->build_display( $this->tpl );
        }
    }