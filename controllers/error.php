<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location controllers/
//-------------------------------------------------------------------------------

    class Error extends Theme
    {   
        /** 
         * Constants
         */
        const ROOT = 'error';
        
        
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            // theme
            $this->theme( Config::DEF_THEME_ERROR );
            
            // the template
            $this->tpl   = new Template;
            
            // all templates
            $this->_root  = self::ROOT . '/';
            $this->_404   = $this->_root . '404';
        }
        
        
        /** 
         * Index
         *
         * @access  public
         */
        public function action_index()
        {
            // the template
            $this->tpl->load_template( $this->_404 );
            
            // return the results
            return $this->build_display( $this->tpl );
        }
    }