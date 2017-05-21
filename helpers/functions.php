<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// Helper Functions
// @author Todd Low
// @location helpers/
// @notes these functions are help functions specific the application
//-------------------------------------------------------------------------------
    
    /** 
     * User Specific Functions
     * 
     * @param   string      $attr
     */
    function user( $attr = 'id' )
    {
        global $USER;
        
        return $USER->get( $attr ); 
    }
    
    
    /** 
     * Checks that the user is logged in
     */
    function requires_login()
    {
        $user = user();
        
        if ( empty( $user ) )
        {
            // no user id found, send to login
            Utility::redirect( Utility::login_url() );
        }
    }