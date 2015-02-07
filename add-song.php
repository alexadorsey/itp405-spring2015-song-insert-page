<?php

require_once 'vendor/autoload.php';
use Symfony\Component\HttpFoundation\Session\Session as Session;

$session = new Session();
$session->start();


// If the submit button was pressed, create a Song object and add to database
if (isset($_POST['submit'])) {  
    $song = new \Itp\Music\Song();
    $song->setTitle($_POST['title']);
    $song->setArtistId($_POST['artist']);
    $song->setGenreId($_POST['genre']);
    $song->setPrice($_POST['price']);
    $song->save();
    
    $message = "<p class='result'>The song <strong>" . $song->getTitle() . "</strong> with an ID of <strong>". $song->getId() . "</strong> was inserted successfully!</p>";
    $session->getFlashBag()->add('song-message', $message);
    header('Location: add-song.php');
    exit;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add a Song</title>
    <link href="bootstrap-3.3.2-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="add-song.css">       
</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <span class="navbar-brand">HW4: Song Insert Page Part 2</span>
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
                            $artistQuery = new \Itp\Music\ArtistQuery();
                            $artists = $artistQuery->getAll();
                        ?>
                        <?php foreach($artists as $artist) : ?>
                            <option value="<?php echo $artist->id ?>">
                                <?php echo $artist->artist_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <span>Genre:
                    <select name="genre" style="width: 180px" required>
                        <option value="" disabled selected>Choose genre</option>
                        <?php
                            $genreQuery = new \Itp\Music\GenreQuery();
                            $genres = $genreQuery->getAll();
                        ?>
                        <?php foreach($genres as $genre) : ?>
                            <option value="<?php echo $genre->id ?>">
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
                
                <?php
                    foreach ($session->getFlashBag()->get('song-message') as $message) {
                        echo $message;
                    } 
                ?>
                
                <br/>
            </form>
            <hr/>
  
        </div>
    </div><!-- /.container -->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
</body>
</html>