<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// Build out theme frame 
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------
    
    class Theme
    {
        /** 
         * Variables
         * 
         * @access  private
         */ 
        private $theme;


        /** 
         * Variables
         * 
         * @access  public
         */ 
        public $theme_slug;
        
        
        /** 
         * Build the frame
         * 
         * @access  public
         * @param   int     $theme_slug   Theme slug
         */
        public function theme( $theme_slug )
        {
            // format the location
            $file = Config::DIR_THEMES . '/' . $theme_slug . '/' . Config::FRAME;
            
            if ( is_file( $file ) )
            {    
                $this->theme_slug = $theme_slug;
                $this->theme = file_get_contents( $file );
            }
        }
        
        
        /** 
         * Combine the body with the frame
         * 
         * @access  public
         * @param   object  $object     Template class
         */
        public function build_display( $object )
        {
            // get the body
            $this->contents = $object->display();
            
            // get the theme
            $this->theme = empty( $this->theme ) ? '' : $this->theme;
            
            // combine them together
            $this->contents = str_replace( 
                Config::JUMP 
                , $this->contents 
                , $this->theme 
            );
            
            // apply global variables
            $this->add_global_variables();
            
            return $this->contents;
        }
        
        
        /** 
         * Add global variables
         * 
         * @access private
         */
        private function add_global_variables()
        {
            $this->contents = str_replace( 
                array( 
                    '{site_url}'
                    , '{root_url}'
                    , '{site_name}'
                    , '{year}'
                    , '{theme_url}'
                    , '{stylesheets}'
                    , '{javascripts}'
                    , '{menu}'
                    , '{footer}'
                    , '{segment_one}'
                    , '{segment_two}'
                    , '{segment_three}'
                    , '{segment_four}'
                    , '{segment_five}'
                    , '{segment_six}'
                    , '{segment_seven}'
                    , '{segment_eight}'
                    , '{segment_nine}'
                    , '{segment_ten}'
                )
                , array(
                    site_url()
                    , root_url()
                    , site_name()
                    , date( 'Y' )
                    , $this->theme_url()
                    , $this->stylesheets()
                    , $this->javascripts()
                    , $this->menu()
                    , $this->footer()
                    , segment_one()
                    , segment_two()
                    , segment_three()
                    , segment_four()
                    , segment_five()
                    , segment_six()
                    , segment_seven()
                    , segment_eight()
                    , segment_nine()
                    , segment_ten()
                )
                , $this->contents
            );
            
            return true;
        }
        
        
        /** 
         * Build this page's header
         * 
         * @access  private
         */
        private function page_header()
        {   
            // return base with theme directory appended
            return '';
        }
        
        
        /** 
         * Build theme url
         * 
         * @access  private
         */
        private function theme_url()
        {   
            // return base with theme directory appended
            
            return theme_url() . '/' . $this->theme_slug;
        }
        
        
        /** 
         * Build stylesheets
         * 
         * @access  private
         */
        private function stylesheets()
        {
            $styles = '<link rel="stylesheet" href="' . $this->theme_url() . '/css/' . Config::MAIN_CSS . '" />';
            
            return $styles;
        }
        
        
        /** 
         * Build javascripts
         * 
         * @access  private
         */
        private function javascripts()
        {   
            $scripts  = '<script src="' . $this->theme_url() . '/js/' . Config::MAIN_JS . '"></script>';
            $scripts .= '<script src="' . root_url() . '/' . Config::DIR_VIEWS . '/' . segment_one() . '/' . segment_one() . '.js"></script>';
            
            return $scripts;
        }
        
        
        /** 
         * Build menu
         * 
         * @access  private
         */
        private function menu()
        {   
            $menu  = '';
            $menu .= '';
            
            return $menu;
        }
        
        
        /** 
         * Build footer
         * 
         * @access  private
         */
        private function footer()
        {   
            $footer  = '';
            $footer .= '';
            
            return $footer;
        }
    }



