# Kimcee PHP Framework
* [Setup](#setup)
* [Getting Started](#getting-started)
* [Templating System](#templating-system)
* [Helper Files](#helper-files)
* [User Login/Logout](#user-loginlogout)

## Setup

Create a new file in the root of your application *(same location as index.php)* and name it `.application.php`.  Then add the following:
```php
<?php

    // application specific
    define( 'SITE_NAME',       'Kimcee' ); // Site Name
    define( 'SESSION_NAME',    'kimcee' ); // session name
    define( 'HOME_CONTROLLER', 'home' ); // default controller: 'home' equates to controllers/home.php
    define( 'ABSOLUTE_PATH',   '' ); // system file path, no trailing slash, example: '/home/servername/public_html/'
    define( 'BASE_URL',        '' ); // url to index of site, no trailing slash, example: 'http://domain.com'
    define( 'SUB_DIR',         '' ); // sub-directory starting with slash, no trailing slash, example: '/subdir'

    // database
    define( 'DB_HOST',   '' ); // database host
    define( 'DB_USER',   '' ); // database user
    define( 'DB_PASS',   '' ); // database password
    define( 'DB_NAME',   '' ); // database name
    define( 'DB_PREFIX', '' ); // [p] in database class gets replaced with this value, example: 'kimcee_' would render [p]pages as kimcee_pages
```

# Getting Started

By default, `controller/home.php` will be called which contains the class Home.  In this class the default function is `action_index()`

## Creating New Pages

To create a new page like `/about` you would need to create the following
1. controllers/about.php
2. views/about/index.html
3. views/about/about.js *(Auto added to the page so you can use it for javascript specific to this page)*

#### About Controller

For `controllers/about.php` you'll need the following:

```php
<?php
    defined( 'CORE_STRAP' ) or die( 'No direct script access.' );

    class About extends Theme
    {
        public function __construct() {   
            // the theme
            $this->theme( 'main' );
            
            // the template
            $this->tpl = new Template;
        }
        
        public function action_index() {
            // view template
            $this->tpl->load_template( 'index' );

            // variable
            $this->tpl->build_var( "var", "This is a variable defined from the controller" );
            
            return $this->build_display( $this->tpl );
        }
    }
```

####  About View Template

For `views/about/index.html`

```html
    <p>Hello, this is a new page and here is a variable: {var}</p>
```

#### Creating a Model 

If your new page will need its own model you can add it in `model/about.php` 

```php
<?php
    defined( 'CORE_STRAP' ) or die( 'No direct script access.' );

    class Home_Model extends Database
    {
        public function __construct()
        {
            // construct
        }
    }
```

#### Creating a sub page

Should you need a sub page like `/about/team` you would need to create a new function inside your About controller and most likely a new view template.

**controllers/about.php**
```php
    public function action_team()
    {
        // view template 
        $this->tpl->load_template( 'team' );
        
        return $this->build_display( $this->tpl );
    }
```

**views/about/team.html**
```html
    <h1>Team</h1>
```

# Templating System

The Kimcee PHP Framework has its own templating system.  The idea is to force developers to keep as much logic as possible out of the views.  Using html templates made this possible.  The templating system is basic but easy to use.

1. Initiate *(must be from within a controller function)*
```php
    // index resolves to index.html
    $this->tpl->load_template( 'index' );
```

2. Output
  * Once all template variables have been defined you'll need to output the results like so
```php
    return $this->build_display( $this->tpl );
```

2. Variables `build_var`
  * **controller** The `build_var` function takes two (2) parameters: variable_name (string), and variable_value (string):
```php
    $this->tpl->build_var( "variable_name", "This is the value of thevariable" );
```
  * **template** In the template file you can reference the variable based on the first parameter of `build_var` using curly brackets:
```html
    <p>Here is the varlue of the variable: {variable_name}</p>
```

3. If Conditionals `build_if`
  * **controller** The `build_if` function takes only one (1) parameter: 
```php
    $this->tpl->build_if( "if_block" );
```
  * **template** In the template file you can reference the if block from the parameter of `build_if`:
```html
    <IF if_block>
        <p>If if_block is true then this will be displayed</p>
    </IF if_block>
```

4. Foreach `build_foreach`
  * **controller** The `build_foreach` function takes two (2) parameters: foreach_name (string), and array_of_results (array):
```php
    $this->tpl->build_foreach( 
        "my_foreach", 
        [ 
            [ "variable_name" => "Coffee" ], 
            [ "variable_name" => "Water" ], 
            [ "variable_name" => "Juice" ]
        ] 
    );
```
  * **template** In the template file you can reference the variable based on the first paramtere of `build_foreach` which will loop through the array of arrays defined by the second parameter:
```html
    <ul>
    <FOREACH my_foreach>
        <li>{variable_name}</li>
    </FOREACH my_foreach>
    </ul>
```

5. **Quick Templates** Sometimes you just need a simple template loaded into a variable in which case using the function `quick_templates()` which accepts four (4) parameters
  * $template *string*
  * $variables *array* (optional)
  * $if *array* (optional)
  * $foreach *array* (optional)

Example usage in controller:
```php
    $this->tpl->build_var( 
        "quick_template", 
        quick_template( 
            'about/quick', 
            [
                'name' => 'beat maker', 
                'instrument' => 'drums', 
                'verb' => 'working' 
            ] 
        ) 
    );
```

# Helper Files

For application specific helper files (functions or classes) you can add them in the `helpers/` directory.  Files in this directory will be auto included in the framework.

# User Login/Logout

By default I included a simple user login/logout function which requires a MySQL database.  The following helper files were created for the user login/logout:
1. helpers/functions.php
2. helpers/user.php

Here is the sql I used for this implementation.  Register function is being worked on.  Once added, then insert a new entry into the users table.  Only need to define the email and password.  The password needs to be set with SHA1.

```sql
--
-- Table structure for table `kimcee_users`
--

CREATE TABLE IF NOT EXISTS `kimcee_users` (
  `id` int(24) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(240) NOT NULL,
  `password` varchar(240) NOT NULL,
  `token` varchar(240) NOT NULL,
  `token_expires` varchar(50) NOT NULL,
  `reg_date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `token` (`token`),
  KEY `status` (`status`),
  KEY `super_admin` (`super_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Table structure for table `kimcee_users_meta`
--

CREATE TABLE IF NOT EXISTS `kimcee_users_meta` (
  `id` int(24) NOT NULL AUTO_INCREMENT,
  `user` int(24) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
```