<?php 
    class Database extends PDO {
        public function __construct($username = null, $password = null) {
            $options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . $dbName;
            parent::__construct($dsn, $username, $password, $options);
            
        }
    }  
?>