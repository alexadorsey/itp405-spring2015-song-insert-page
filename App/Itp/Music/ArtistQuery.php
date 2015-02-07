<?php

namespace Itp\Music;
use PDO as PDO;


class ArtistQuery extends \Itp\Base\Database {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Returns all artists from the artists table ordered by the artist name as PDO objects
    public function getAll() {
        $sql = "
            SELECT *
            FROM artists
            ORDER BY artist_name ASC"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}

?>