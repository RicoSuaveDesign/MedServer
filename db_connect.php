<?php
 
/**
 * A class file to connect to database
 */
class DB_CONNECT {

    
 
    private $conn = null;
    // constructor
    function __construct() {
        // connecting to database
       $this->conn =  $this->connect();
    }

    function showconn(){
        return $this->conn;
    }
 
    // destructor
   function __destruct() {
       // closing db connection
        $this->close();
    }
 
    /**
     * Function to connect with database
     */
    function connect() {
        // import database connection variables
        require_once __DIR__ . '/db_config.php';
 
        // Connecting to mysql database
        $con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD);

        if (!$con) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
 
        // Selecing database
        $db = mysqli_select_db($con, DB_DATABASE);

        if (!$db) {
            echo "Error: Unable to select DB." . PHP_EOL;
            exit;
        }
 
        // returing connection cursor
        return $con;
    }
 
    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
       mysqli_close($this->conn);
    }
 
}
 
?>
