<?php

namespace Itp\Base;
use PDO as PDO;

class Database {
    private $host = 'itp460.usc.edu';
    private $dbname = 'music';
    private $user = 'student';
    private $password = 'ttrojan';
    protected static $pdo;
    
    public function __construct() {
        if (!static::$pdo) {
            $connectionString = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
            static::$pdo = new PDO($connectionString, $this->user, $this->password);
        }
    }
}

?>