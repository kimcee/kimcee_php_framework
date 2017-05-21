<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

	class Template 
    {    
        /** 
         * Variables
         */
        public $contents;
        
        
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            
        }
        
        
        /** 
         * The template
         * 
         * @access  public
         * @param   string  $file_ID  the view file
         */
		public function load_template( $file_ID, $plugin = false )
        {
            $this->build_template( $file_ID, 'contents', $plugin );
		}
        
        
        /** 
         * Build the template
         * 
         * @access  private
         * @param   string      $file_ID
         * @param   string      $location
         */
        private function build_template( $file_ID, $location, $plugin = false )
        {
            if ( is_numeric( $file_ID ) )
            {
                // grab the file contents from the database
                // @notes coming soon
            }
            else if ( $file_ID != '' )
            {   
                // format the location
                $file = ( $plugin ? Config::ABSOLUTE_PATH . '/' . Config::DIR_PLUGINS : Config::DIR_VIEWS ) . '/' . $file_ID . Config::TEMPLATE_EXT;
                
                if ( is_file( $file ) )
                {
                    $this->$location = file_get_contents( $file );
                }
            }
        }
		
        
		/** 
         * Build curly brace variables
         * 
         * @access  public
         * @param   string  $var
         * @param   string  $value
         */
		public function build_var( $var, $value ) 
        {
			$this->contents = str_replace( 
                '{' . $var . '}'
                , $value 
                , $this->contents 
            );
		}
        
				
		/** 
         * Build the foreach loop and return the response
         *  
         * @access  public
         * @param   string      $name    the name of the foreach identifier
         * @param   array       $array   the array to be used for the loop
         */ 
		public function build_foreach( $name, $array ) 
        {	
            // loop for the foreach
			preg_match( 
                "/\<FOREACH {$name}\>(.*)\<\/FOREACH {$name}\>/isU" 
                , $this->contents 
                , $var 
            );
			
            // check for response from preg_match
			if ( isset( $var[ 1 ] ) ) 
            {
				$return = '';
				
				foreach( $array as $key => $val ) 
                {
					if ( is_array( $val ) ) 
                    {
						$html = $var[ 1 ];
                        
						foreach ( $val as $k => $v ) 
                        {
							if ( is_string( $v ) )
							{
								$html = str_replace( 
									'{' . $k . '}' 
									, $v 
									, $html 
								);	
							}
						}
                        
                        // run if
                        // @todo build an if statement inside a loop
                        
						$return .= $html;
					}
				}
				
                // update the 
				$this->contents = str_replace( 
                    $var[ 0 ] 
                    , $return 
                    , $this->contents 
                );
			}
            
            return true;
		}
        
		
		/** 
         * Build the if
         * 
         * @access  public
         * @param   string  $name
         */
		public function build_if( $name ) 
        {
            $if_name = "IF {$name}>";
            
            $this->contents = str_replace(
                array( '<' . $if_name, '</' . $if_name ) 
                , '' 
                , $this->contents
            );
            
            return true;
		}
        
		
		/** 
         * Build the blocks
         * 
         * @access  public
         * @param   string  $name
         * @param   string  $block
         */
		// public function build_block( $name, $block ) 
        // {
        //     Debug::print_r( 'build_block DEPRECATED', 'x' );
            
		// 	preg_match_all(
        //         "|\<BLOCK {$name} ([^>]+)\>(.*)\<\/BLOCK {$name} [^>]+\>|isU" 
        //         , $this->contents 
        //         , $block_array 
        //         , PREG_SET_ORDER
        //     );
            
        //     $this->format_blocks( $block, $block_array );
		// }
        

		/** 
         * Clean up the view
         * 
         * @access  private
         */
		private function clean() 
        {
            // looks for any other blocks that 
            // were not used and removes them
            // preg_match_all(
            //     "|\<BLOCK (.*)\>.*\<\/BLOCK \\1\>|isU"
            //     , $this->contents 
            //     , $block_array
            //     , PREG_SET_ORDER
            // );
            
            // $this->format_blocks( NULL, $block_array );
            
            // clear all unused ifs
            preg_match_all(
                "|\<IF (.*)\>.*\<\/IF \\1\>|isU"
                , $this->contents 
                , $block_array
                , PREG_SET_ORDER
            );
            
            $this->format_blocks( NULL, $block_array );
		}
        
        
        /** 
         * Convert blocks to desired output
         * 
         * @access  private
         * @param   string  $block
         * @param   array   $block_array
         */
        private function format_blocks( $block, $block_array )
        {
			if ( is_array( $block_array ) ) 
            {
				foreach ( $block_array as $key => $value ) 
                {
					if ( is_array( $value ) ) 
                    {
                        $one = $value[ 0 ];
                        $two = $value[ 1 ] != $block ? NULL : $value[ 2 ];
                        
                        $this->contents = str_replace(
                            $one 
                            , $two 
                            , $this->contents
                        );
					}
				}
			}
        }
			
 
		/** 
         * Return the compiled results
         * 
         * @access  public
         */
		public function display() 
        {	
            // clean all unused blocks
            $this->clean();
            
            return $this->contents;
		}

	}
