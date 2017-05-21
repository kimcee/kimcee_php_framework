<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location controllers/
//-------------------------------------------------------------------------------

    class Home extends Theme
    {   
        /** 
         * Constants
         */
        const ROOT = 'home';
        
        
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            // the model
            $this->model = load_model( self::ROOT );
            
            // the theme
            $this->theme( 'main' );
            
            // the template
            $this->tpl = new Template;
            
            // all templates
            $this->_root    = self::ROOT . '/';
            $this->_index   = $this->_root . 'index';
            $this->_welcome = $this->_root . 'welcome';
        }
        
        
        /** 
         * Index
         *
         * @access  public
         */
        public function action_index()
        {
            // first we need to define the template
            $this->tpl->load_template( $this->_index );
            
            // Build your blocks
            $this->tpl->build_if( "first_one" );
            
            // Build your loop
            $array = [
                [ 'name' => $this->model->testing() ], 
                [ 'name' => 'water' ],
                [ 'name' => 'music' ]
            ];
            
            if ( !empty( $array ) ) 
            {
                $this->tpl->build_foreach( "my_loop", $array );
                $this->tpl->build_if( "show" );
            }
            else
            {
                $this->tpl->build_if( "empty" );
            }
            
            // Build your variables
            $this->tpl->build_var( "name", "World" );
            
            $welcome_display = $this->build_welcome_display();

            $this->tpl->build_var( "welcome_template", $welcome_display );
            
            $this->tpl->build_var( 
                "quick_template", 
                quick_template( 
                    'home/quick', 
                    [
                        'name' => 'beat maker', 
                        'instrument' => 'drums', 
                        'verb' => 'working' 
                    ] 
                ) 
            );
            
            return $this->build_display( $this->tpl );
        }
        
        
        /** 
         * Custom View Example
         * 
         * @access  private
         */
        private function build_welcome_display()
        {
            // initiate the template
            $welcome_template = new Template;
            
            // the template
            $welcome_template->load_template( $this->_welcome );
            
            // the variable
            $welcome_template->build_var( "band", "WEEZER" );
            
            // show the test one block
            $welcome_template->build_if( "test_one" );
            
            // return the tpl
            return $welcome_template->display();
        }
    }