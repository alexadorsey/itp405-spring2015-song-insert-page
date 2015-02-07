<?php

namespace Itp\Music;
use PDO as PDO;


class Song extends \Itp\Base\Database {
    
    private $title;
    private $artist_id;
    private $genre_id;
    private $price;
    
    public function __contruct() {
        parent::__construct();
    }
    
    // Sets a title instance property
    public function setTitle($title) {
        $this->title = $title;
    }
    
    // Sets an artist_id instance property
    public function setArtistId($artist_id) {
        $this->artist_id = $artist_id;
    }
    
    // Sets a genre_id instance property
    public function setGenreId($genre_id) {
        $this->genre_id = $genre_id;
    }
    
    // Sets a price
    public function setPrice($price) {
        $this->price = $price;
    }
    
    // Performs the insert
    public function save() {
        $sql = "
                INSERT INTO songs (title, artist_id, genre_id, price, added)
                VALUES (?, ?, ?, ?, ?)"
            ;
        date_default_timezone_set('America/Los_Angeles');
        $statement = static::$pdo->prepare($sql);
        $statement->execute(array($this->title, $this->artist_id, $this->genre_id, $this->price, date("Y-m-d H:i:s")));
    }
    
    // Returns the song title
    public function getTitle() {
        return $this->title;
    }
    
    // Returns the id column of this song in the database
    public function getId() {
        return static::$pdo->lastInsertId();
    }
}


?>