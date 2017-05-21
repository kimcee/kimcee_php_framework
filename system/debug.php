<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// Debug
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Debug
    {
        /** 
         * Print R
         * 
         * @access  public
         * @param   mixed   $object
         * @param   string  $ending
         */
        public static function print_r( $object, $ending = '' )
        {
            if ( Config::DEBUG )
            {
                echo '<pre>';
                print_r( $object );
                echo '</pre>';
                
                self::ending( $ending );
            }
        }
        
        
        /** 
         * Ending
         * 
         * @access  private
         * @param   string      $ending
         */
        private static function ending( $ending = '' )
        {
            if ( $ending == 'x' )
            {
                exit;
            }
            else if ( $ending == 'hr' )
            {
                echo '<hr />';
            }
        }
    }