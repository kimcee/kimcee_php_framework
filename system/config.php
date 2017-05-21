<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// File Description
// @author Todd Low
// @location system/
//-------------------------------------------------------------------------------

    class Config 
    {
        // debug
        const DEBUG           = true;           // used in debug class and error_reporting (boolean)
        
        // database
        const DB_HOST         = '';             // database host
        const DB_USER         = '';             // database user
        const DB_PASS         = '';             // database password
        const DB_NAME         = '';             // database name
        const DB_PREFIX       = '';             // [p] in database class gets replaced with this value

        // application specific
        const ABSOLUTE_PATH   = '';             // system file path, no trailing slash
        const BASE_URL        = '';             // url to index of site, no trailing slash
        const SUB_DIR         = '';             // sub-directory starting with slash, no trailing slash
        const SITE_NAME       = 'Kimcee';       // name of site
        const SESSION_NAME    = 'kimcee';       // session name
        const HOME            = 'home';         // default controller

        // Router
        const DIR_THEMES      = 'themes';       // themes folder
        const DIR_CONTROLLERS = 'controllers';  // controllers folder
        const DIR_MODELS      = 'models';       // models folder
        const DIR_VIEWS       = 'views';        // views folder
        
        // Do not change
        const TEMPLATE_EXT    = '.html';        // template/view files extension
        const FRAME           = 'frame.html';   // theme's frame file which includes header, footer, and jump code
        const DEF_THEME       = 'main';         // default theme for all sites
        const DEF_THEME_ERROR = 'error';        // default theme for error pages
        const CONTROLLER      = 'controller';   // name of controller
        const MODEL           = 'model';        // name of model
        const ROOT            = '/index.php';   // root index file
        const ACTION_METHOD   = 'action_';      // page based method prefix
        const DEF_ACTION      = 'index';        // default method
        const DEF_ERROR       = 'error';        // default error class
        const JUMP            = '{BODY}';       // jump code for theme frame used to separate header and footer
        const MODEL_SUFFIX    = '_Model';       // appended to model class name to separate from controller class name
        const MAIN_CSS        = 'main.css';     // main css file for all themes
        const MAIN_JS         = 'main.js';      // main js file for all themes
    }

    