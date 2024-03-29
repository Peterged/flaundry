<?php

namespace App\Libraries;

include __DIR__ . "/../config/config.php";

class Database extends \PDO
{
    public function __construct($username = null, $password = null)
    {
        $options = array(\PDO::ATTR_PERSISTENT => true, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_AUTOCOMMIT => 0);
        $dsn = "mysql:host=" . \DB_HOST . ";dbname=" . \DB_NAME;

        try {
            parent::__construct($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}
