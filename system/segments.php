<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// Segments
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Segments
    {
        /** 
         * Variables
         */
        private $url_array;
        
        
        /** 
         * The construct
         * 
         * @access  public
         */
        public function __construct()
        {
            // run the segment generator
            $this->set_segments();
        }
        
        
        /** 
         * Set Segments
         *
         * @access  public
         * @param   string  $explode_by
         */
        public function set_segments( $explode_by = '' )
        {
            // how we break apart the url
            $explode_by = $explode_by == '' ? Config::ROOT : $explode_by;
            
            $this->explode_url( $explode_by );
        }
        
        
        /** 
         * Explode By
         * 
         * @access  private
         * @param   string  $explode_by
         */
        private function explode_url( $explode_by )
        {
            // start with the full url
            $url = parse_url( full_site_url(), PHP_URL_PATH );

            // explode the url
            if ( $explode_by != '' && $explode_by != '/' )
            {
                $url = explode( $explode_by, $url );
            }
            
            // grab everything after $explode_by
            $trim = $url;

            if ( is_array( $url ) )
            {
                $trim = $url[ 1 ];
            }
            
            $url = $trim != '' ? trim( $trim, '/' ) : '';
            
            // set the $url_array array
            $this->url_array = explode( '/', $url );
        }
        
        
        /** 
         * Retrieve a segment
         * 
         * @access  public
         */
        public function get( $segment = 0 )
        {
            $segment = $segment == 0 ? $segment : --$segment;
            
            return !empty( $this->url_array[ $segment ] ) ? $this->url_array[ $segment ] : NULL;
        }
    }