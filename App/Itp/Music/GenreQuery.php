<?php

namespace Itp\Music;
use PDO as PDO;


class GenreQuery extends \Itp\Base\Database {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Returns all artists from the artists table ordered by the artist name as PDO objects
    public function getAll() {
        $sql = "
            SELECT *
            FROM genres
            ORDER BY genre ASC"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}

?>