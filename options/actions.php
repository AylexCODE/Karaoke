<?php
    require("../database/db_conn.php");

    if(isset($_POST["video_id"])){
        $title = $_POST["title"];
        $artist = $_POST["artist"];
        $genre = $_POST["genre"];
        $isVocal = $_POST["isvocal"];
        $video_id = $_POST["video_id"];

        $checkArtist = $conn->prepare("SELECT name FROM artists WHERE name = ?");
        $checkArtist->execute([$artist]);

        if(count($checkArtist->fetchAll(PDO::FETCH_ASSOC)) != 0){
            $checkGenre = $conn->prepare("SELECT name FROM genres WHERE name = ?");
            $checkGenre->execute([$genre]);

            if(count($checkGenre->fetchAll(PDO::FETCH_ASSOC)) != 0){
                addSong($title, $artist, $genre, $isVocal, $video_id, $conn);
            }else{
                try{
                    $insertNewGenre = $conn->prepare("INSERT INTO genres (name) VALUE (?)");
                    $insertNewGenre->execute([$genre]);
                    addSong($title, $artist, $genre, $isVocal, $video_id, $conn);
                }catch(PDOException $f){
                    echo "Error Inserting New Genre: " . $f;
                }
            }
        }else{
            try{
                $insertNewArtist = $conn->prepare("INSERT INTO artists (name) VALUE (?)");
                $insertNewArtist->execute([$artist]);

                $checkGenre = $conn->prepare("SELECT name FROM genres WHERE name = ?");
                $checkGenre->execute([$genre]);

                if(count($checkGenre->fetchAll(PDO::FETCH_ASSOC)) != 0){
                    addSong($title, $artist, $genre, $isVocal, $video_id, $conn);
                }else{
                    try{
                        $insertNewGenre = $conn->prepare("INSERT INTO genres (name) VALUE (?)");
                        $insertNewGenre->execute([$genre]);
                        addSong($title, $artist, $genre, $isVocal, $video_id, $conn);
                    }catch(PDOException $f){
                    echo "Error Inserting New Genre: " . $f;
                    }
                }
            }catch(PDOException $e){
                echo "Error Inserting New Artst: " .$e;
            }
        }
    }

    function addSong($title, $artist, $genre, $isVocal, $video_id, $conn){
        try{
            $getArtist_id = $conn->prepare("SELECT id FROM artists WHERE name = ? LIMIT 1;");
            $getArtist_id->execute([$artist]);
            $artist_id = $getArtist_id->fetch(PDO::FETCH_ASSOC)["id"];

            try{
                $getGenre_id = $conn->prepare("SELECT id FROM genres WHERE name = ? LIMIT 1;");
                $getGenre_id->execute([$genre]);
                $genre_id = $getGenre_id->fetch(PDO::FETCH_ASSOC)["id"];

                try{
                    $q = $conn->prepare("INSERT INTO songs VALUES (null, ?, ?, ?, ?, ?);");
                    $q->execute([$artist_id, $title, $genre_id, $isVocal, $video_id]);

                    echo "Added Successfully!";
                }catch(PDOException $g){
                    echo "Error Inserting New Song: " . $g;
                }
            }catch(PDOException $f){
                echo "Error Getting Genre ID: " . $f;
            }
        }catch(PDOException $e){
            echo "Error Getting Artist ID: " . $e;
        }
    }
?>