<!doctype html>
    
<?php

require_once __DIR__ . '/Database.php'; // __DIR__ means get the full file path

// ArtistQuery
class ArtistQuery extends Database {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Returns all artists from the artists table ordered by the artist name as PDO objects
    public function getAll() {
        $sql = "
            SELECT artist_name
            FROM artists
            ORDER BY artist_name ASC"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}


// GenreQuery
class GenreQuery extends Database {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Returns all artists from the artists table ordered by the artist name as PDO objects
    public function getAll() {
        $sql = "
            SELECT genre
            FROM genres
            ORDER BY genre ASC"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}

class Song extends Database {
    
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
    public function setArtistId($artist_name) {
        $sql = "
            SELECT id
            FROM artists
            WHERE artist_name = '" . $artist_name . "'"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        $this->artist_id = $result[0]->id;
    }
    
    // Sets a genre_id instance property
    public function setGenreId($genre_name) {
        $sql = "
            SELECT id
            FROM genres
            WHERE genre = '" . $genre_name . "'"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        $this->genre_id = $result[0]->id;
    }
    
    // Sets a price
    public function setPrice($price) {
        $this->price = $price;
    }
    
    // Performs the insert
    public function save() {
        
        // Check if song is already in the database
        if ($this->getId()) {
            echo "<p class='result'>The song <strong>" . $this->getTitle() . "</strong> with an ID of <strong>". $this->getId() . "</strong> is already in the database.</p>
                    <p>Add another!</p>";
        } else {
            // If not, save it to database
            $sql = "
                INSERT INTO songs (title, artist_id, genre_id, price, added)
                VALUES (?, ?, ?, ?, ?)"
            ;
            $statement = static::$pdo->prepare($sql);
            $statement->execute(array($this->title, $this->artist_id, $this->genre_id, $this->price, date("Y-m-d H:i:s")));
            
            echo "<p class='result'>The song <strong>" . $this->getTitle() . "</strong> with an ID of <strong>". $this->getId() . "</strong> was inserted successfully!</p>";
        }
    }
    
    // Returns the song title
    public function getTitle() {
        return $this->title;
    }
    
    // Returns the id column of this song in the database
    public function getId() {
        
        // **
        //  * Because I used this function to check whether the song was already inserted into the database,
        //  * I decided not to use lastInsertId method in case the last song is not the current one
        // **
        //return $pdo->lastInsertId();
        
        $sql = "
            SELECT id
            FROM songs
            WHERE title = '" . $this->title . "'
            AND artist_id = '" . $this->artist_id . "'
            AND genre_id = '" . $this->genre_id ."' 
            AND price = '" . $this->price . "'"
        ;
        $statement = static::$pdo->prepare($sql);
        $statement->execute();
        $song = $statement->fetchAll(PDO::FETCH_OBJ);
        
        if ($song) {
            return $song[0]->id;
        }
        return null;
    }
}


?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add a Song</title>
    <link href="../bootstrap-3.3.2-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="add-song.css">       
</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <span class="navbar-brand">HW3: Song Insert Page</span>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container">
        <div class="box">
            <h1>Add your own song to our database</h1>
            <form method="post">
                <span>Title: <input type="text" name="title" required></span>
                <span>Artist:
                    <select name="artist" required>
                        <option value="" disabled selected>Choose artist</option>
                        <?php
                            $artistQuery = new ArtistQuery();
                            $artists = $artistQuery->getAll();
                        ?>
                        <?php foreach($artists as $artist) : ?>
                            <option>
                                <?php echo $artist->artist_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <span>Genre:
                    <select name="genre" style="width: 180px" required>
                        <option value="" disabled selected>Choose genre</option>
                        <?php
                            $genreQuery = new GenreQuery();
                            $genres = $genreQuery->getAll();
                        ?>
                        <?php foreach($genres as $genre) : ?>
                            <option>
                                <?php echo $genre->genre ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <span>Price: $<input type="text" name="price" style="width: 70px" required></span>
                <br/>
                <br/>
                <button type="submit" name="submit">
                    <a href="#" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-music"></span> Add Song</a>    
                </button>
                
                <br/>
            </form>
            <hr/>
  
                <?php
                // If the submit button was pressed, create a Song object and add to database
                if (isset($_POST['submit'])) {  
                    $song = new Song();
                    $song->setTitle($_POST['title']);
                    $song->setArtistId($_POST['artist']);
                    $song->setGenreId($_POST['genre']);
                    $song->setPrice($_POST['price']);
                    $song->save();
                }
                ?>
        </div>
    </div><!-- /.container -->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
</body>
</html>