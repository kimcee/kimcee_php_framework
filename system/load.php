<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// All system files
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    // initiate time
    // $time = microtime();

    // set the time zone to Chicago
    date_default_timezone_set( 'America/New_York' );

    // include all system files
    include( 'system/config.php'       );
    include( 'system/debug.php'        );
    include( 'system/functions.php'    );
    include( 'system/database.php'     );
    include( 'system/utility.php'      );
    include( 'system/segments.php'     );
    include( 'system/super_global.php' );
    include( 'system/template.php'     );
    include( 'system/theme.php'        );
    include( 'system/load_class.php'   );
    include( 'system/controller.php'   );
    include( 'system/model.php'        );
    include( 'system/router.php'       );

    // loop through all helper files and include
    foreach ( glob( 'helpers/*.php' ) as $helper_file )
    {
        include( $helper_file );
    }

    // initate sessions
    session_name( Config::SESSION_NAME );
    session_start();
    
    // check for debug
    if ( Config::DEBUG )
    {
        // error reporting
        error_reporting( E_ALL );
        ini_set( 'display_errors', '1' );
    }
    
    // url segments
    $SEGMENTS = new Segments;
    
    // router
    $ROUTER  = new Router;
    
    // needed for router
    $class  = segment_one();
    $method = segment_two();

    // Debug::print_r( $class );
    // Debug::print_r( $method, 'x' );

    
    // return results
    echo $ROUTER->init( $class, $method );
    
    // time check
    // $end  = microtime();
    // $diff = number_format( ( $end - $time ), 3 );
    // echo "\n\n<!-- Loaded in {$diff} seconds -->";
    
    // close session
    session_write_close();