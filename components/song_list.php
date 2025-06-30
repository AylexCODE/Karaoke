<?php
    require_once("../database/db_conn.php");

    if(isset($_POST)){
        $q = mysqli_query($conn, "SELECT songs.title AS Title, artists.name AS Artist, songs.duration AS Duraction, songs.video_id as VideoID FROM songs INNER JOIN artists ON songs.artist_id = artists.id");

        if(mysqli_num_rows($q) != 0){
            while($song = mysqli_fetch_assoc($q)){
            echo "<span onclick='play(&#x27 " . $song["VideoID"] . " &#x27)'>";
            echo "<p>" . $song["Title"] ."</p>";
            echo "<p>" . $song["Artist"] . "</p>";
            echo "</span>";
            }
        }else{
            echo "No Results...";
        }
    }
?>