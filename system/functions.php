<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// System Functions
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------
    
    /** 
     * Quick template, only loads in variables
     * 
     * @param   string      $template   path to template file
     * @param   array       $variables  associative array of variables
     * @param   string      $foreach    key for the foreach element
     * @param   boolean     $plugin     if this is a plugin set to true
     */
    function quick_template( $template, $variables = null, $if = '', $foreach = '', $plugin = false )
    {
        $tpl = new Template;
        $tpl->load_template( $template, $plugin );
        
        if ( !empty( $variables ) && is_array( $variables ) )
        {
            foreach ( $variables as $key => $val )
            {
                $tpl->build_var( $key, $val );
            }
        }
        
        if ( !empty( $if ) && is_array( $if ) )
        {
            foreach ( $if as $var )
            {
                $tpl->build_if( $var );
            }
        }
        
        if ( !empty( $foreach ) && is_array( $foreach ) )
        {
            foreach ( $foreach as $key => $data )
            {
                $tpl->build_foreach( $key, $data );
            }
        }
        
        return $tpl->display();
    }
    
     
    /** 
     * Load Controller
     * 
     * @param   string      $controller_file
     */
    function load_controller( $controller_file )
    {
        $controller = new Controller( $controller_file );
        $controller->init();
        
        return $controller->controller;
    }
    
    
    /** 
     * Load Model
     * 
     * @param   string      $model_file
     */
    function load_model( $model_file )
    {
        $model = new Model( $model_file );
        $model->init();
        
        return $model->model;
    }
    
    
    /** 
     * URL Segment
     * 
     * @param   int     $int_segment
     */
    function segment( $int_segment )
    {
        global $SEGMENTS;
        
        return Utility::sanitize( $SEGMENTS->get( $int_segment ) );
    }
    
    
    /** 
     * Individual segment functions
     */
    function segment_one()   { return segment( 1  ); }
    function segment_two()   { return segment( 2  ); }
    function segment_three() { return segment( 3  ); }
    function segment_four()  { return segment( 4  ); }
    function segment_five()  { return segment( 5  ); }
    function segment_six()   { return segment( 6  ); }
    function segment_seven() { return segment( 7  ); }
    function segment_eight() { return segment( 8  ); }
    function segment_nine()  { return segment( 9  ); }
    function segment_ten()   { return segment( 10 ); }


    /** 
     * Site name
     */
    function site_name()
    {
        return Config::SITE_NAME;
    }


    /** 
     * Site url
     */
    function site_url()
    {
        return Utility::site_url();
    }
    
    
    /** 
     * Full site url
     */
    function full_site_url()
    {
        return "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }
    
    
    /** 
     * Root url
     */
    function root_url()
    { 
        return Config::BASE_URL . Config::SUB_DIR;
    }
    

    /** 
     * Theme url
     */
    function theme_url()
    { 
        return root_url() . '/' . Config::DIR_THEMES; 
    }
    