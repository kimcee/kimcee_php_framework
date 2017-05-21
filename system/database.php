<?php defined( 'CORE_STRAP' ) or die( 'No direct script access.' );
//-------------------------------------------------------------------------------
// Database PDO
// @author Todd Low
// @location system/database.php
//-------------------------------------------------------------------------------

    class Database
    {
        /** 
         * Variables
         * 
         * @access private
         */
        private $DBH;
        
        
        /** 
         * Construct
         * 
         * @access  public
         */
        public function __construct()
        {
            return $this->init();
        }
        
        
        /** 
         * The "construct"
         * 
         * @access  public
         */
        public function init()
        {
            if ( empty( $this->DBH ) )
            {
                try 
                {
                    // MySQL with PDO_MYSQL
                    $this->DBH = new PDO( 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME, Config::DB_USER, Config::DB_PASS );
                    $this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                }
                catch( PDOException $e ) 
                {
                    // not able to connect
                    echo 'error establishing connection with database. please try reloading the page.';
                    exit;
                }
            }
        }
        
        
        /** 
         * Format database name
         * 
         * @access  private
         * @param   string   $database
         */
        private function database( $database )
        {
            return str_replace( '[p]', Config::DB_PREFIX, $database );
        }
        
        

        /**
         * Convert enum values to array
         *
         * @access  public
         * @param 	string   $database 	
         * @param 	string   $column 	
         */
        public function enum_list( $database, $column )
        {
            $database = $this->database( $database );
            $sql  = "SHOW COLUMNS FROM `{$database}` LIKE ?";
            $row  = $this->mq( $sql, array( $column ), 1 );

            return explode( ',', str_replace( array( 'enum(', ')', "'", '"' ), array( '', '', '', '' ), $row[ 'Type' ] ) );
        }



        /**
         * Insert into Database
         * 
         * @access   public
         * @param 	string    $database
         * @param 	array     $columns
         * @param 	array     $values
         */
        public function insert( $database, $columns, $values )    
        {
            // format the database
            $database = $this->database( $database );
            
            // initate db connection
            $this->init();
            
            // variables
            $updateColumns = '';
            $updateValues  = '';
            $params        = array();
            
            // Count the Columns
            $totalColumns  = count( $columns );
            
            // Count the Values
            $totalValues   = count( $values );
            
            // Check to see that the number of columns equals the number of values
            if ( $totalColumns == $totalValues ) 
            {
                $i=0;
                
                while( $i < $totalColumns ) 
                {
                    // Set the Column Name
                    $column   = $columns[ $i ];

                    // Set the Value of the Table
                    $params[] = $values[ $i ] ? $values[ $i ] : '';
                    
                    // If this is the last entry, remove the comma
                    $comma    = $i > 0 ? ', ' : '';
                    
                    $updateColumns .= "{$comma}`{$column}`";
                    $updateValues  .= "{$comma}?";
                    
                    ++$i;
                }
                
                $sql = "INSERT INTO `{$database}` ({$updateColumns}) values ({$updateValues})";
                
                // used to avoid the fetch all call in mq()
                $this->insert = true;
                
                return $this->mq( $sql, $params );
            }
        }



        /**
         * similar to mysql_fetch_aray function
         *
         * @access   public
         * @param 	array     $array
         * @param 	int       $num
         */
        public function mf( $array, $num = '' ) 
        {
            // initate db connection
            $this->init();
            
            $return = array();
            
            // We must have an array and not be empty to continue
            if ( is_array( $array ) && !empty( $array ) )
            {
                $items = count( $array );
                
                if ( $num == 1 )
                {
                    // return only first entry
                    $c = 0;
                    foreach ( $array as $key => $val )
                    {
                        ++$c;
                        
                        if ( $c == 1 )
                        {
                            $return = $val;
                        }
                    }
                }
                else
                {
                    # $return = cleanArray( $array );
                    $return = $array;
                }
            }
            
            return $return;
        }



        /**
         * similar to mysql_query function
         *
         * @access   public
         * @param 	string    $sql
         * @param 	array     $parameters
         * @param 	mixed     $limit         integer or string of "rowset" to return multiple rowsets         
         */
        public function mq( $sql, $parameters = array(), $limit = null ) 
        {
            // format database
            $sql = $this->database( $sql );
            
            // check for stored procedure
            $first_four_chars = strtolower( substr( $sql, 0, 4 ) );
            $sp = $first_four_chars == 'call' || $first_four_chars == 'upda' ? true : false;
            
            // check for delete query
            if ( strpos( $sql, 'delete' ) !== false )
            {
                $this->delete = true;
            }
            
            // initate db connection
            $this->init();
            
            try 
            {
                // prepare sql
                $STH = $this->DBH->prepare( $sql );

                if ( !empty( $parameters ) )
                {
                    // confirm we have an array
                    if ( !is_array( $parameters ) )
                    {
                        // force into an array
                        $parameters = array( $parameters );
                    }
                    
                    $j=0;

                    foreach ( $parameters as $parameter )
                    {
                        ++$j;
                        $STH->bindValue( $j, $parameter );
                    }
                }

                if ( is_object( $STH ) )
                {
                    // perform sql 
                    $STH->execute();
                    
                    if ( empty( $this->update ) && empty( $this->insert ) && empty( $this->delete ) )
                    {
                        // check for multi response
                        if ( $limit == 'rowset' )
                        {
                            // return all data sets
                            $results = array();
                            
                            do {
                                $data = $STH->fetchAll( PDO::FETCH_ASSOC );
                                
                                if ( $data )
                                {
                                    $results[] = $data;
                                }
                                
                            } while ( $STH->nextRowset() );
                        }
                        else if ( $sp )
                        {
                            $results = 1;
                        }
                        else
                        {
                            // single response
                            $results = $STH->fetchAll( PDO::FETCH_ASSOC );
                            
                            if ( $limit === 1 )
                            {
                                $results = $this->mf( $results, 1 );
                            }
                        }

                        return $results;
                    }
                    
                    return true;
                }
            } 
            catch ( PDOException $e ) 
            {
                if ( Config::DEBUG )
                {
                    Debug::print_r( $e->getMessage(), 'x' );
                }
                
                return $e->getMessage();
            }
        }



        /**
         * simulate associative array by removing integer keys
         * 
         * @access   public
         * @param    mixed   $results
         */
        private function remove_duplicates( $results )
        {
            if ( is_array( $results ) && count( $results ) > 0 )
            {
                foreach( $results as $key => $value)
                {
                    foreach ( $value as $k => $v )
                    {
                        if ( is_numeric( $k ) ) 
                        {
                            unset( $results[ $key ][ $k ] );
                        }
                    }
                }
            }
            
            return $results;
        }



        /**
         * similar to myql_insert_id() function
         * 
         * @access   public
         */
        public function new_id()
        {
            // initate db connection
            $this->init();
            
            return $this->DBH->lastInsertId();
        }
    


        /**
         * Update database
         *
         * @access  public
         * @param   string    $database
         * @param 	array     $columns
         * @param 	array     $values
         * @param 	array     $where
         * @param 	array     $whereIs
         */
        public function update( $database, $columns = array(), $values = array(), $where = array(), $whereIs = array() ) 
        {
            // format the database
            $database = $this->database( $database );
            
            // initate db connection
            $this->init();
            
            $updateString = '';
            $whereQuery   = '';
            $parameters   = array();
            
            // Count the Tables
            $totalColumns = count( $columns );
            
            // Count the Values
            $totalValues  = count( $values );
            
            // Check to see that the number of columns equals the number of values
            if ( $totalColumns == $totalValues ) 
            {
                $i=0;
                
                while ( $i < $totalColumns ) 
                {
                    // Set the Column Name
                    $table = !empty( $columns[ $i ] ) ? $columns[ $i ] : false;

                    if ( $table )
                    {
                        // Set the Value of the Column
                        $parameters[] = !empty( $values[ $i ] ) ? $values[ $i ] : '';
                        
                        // If this is the last entry, remove the comma
                        $comma = $i > 0 ? ', ' : '';
                        
                        $updateString .= $comma . "`{$table}` = ?";
                    }
                    
                    ++$i;
                }
                
                $w=0;
                
                foreach ( $where as $value ) 
                {
                    $whereQuery  .= ( $w > 0 ? " and " : "" ) . "`{$value}` = ?";
                    $parameters[] = !empty( $whereIs[ $w ] ) ? $whereIs[ $w ] : '';
                    ++$w;
                }

                $sql = "UPDATE {$database} SET {$updateString} WHERE {$whereQuery}";
                
                // used to avoid the fetchall call in mq()
                $this->update = true;
                
                // perform update
                return $this->mq( $sql, $parameters );
            }
        }
    }