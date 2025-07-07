<?php
    require_once("../database/db_conn.php");

    if(isset($_POST)){
        $q = $conn->prepare("SELECT songs.title AS Title, artists.name AS Artist, songs.video_id as VideoID FROM songs INNER JOIN artists ON songs.artist_id = artists.id");
        $q->execute();
        $result = $q->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) != 0){
            foreach($result as $song){
            echo "<span onclick='addQueue(&#x27;" . $song["Title"] . "&#x27;, &#x27;" . $song["Artist"] . "&#x27;, &#x27;" . $song["VideoID"] . "&#x27;)'>";
            echo "<p>" . $song["Title"] ."</p>";
            echo "<p>" . $song["Artist"] . "</p>";
            echo "</span>";
            }
        }else{
            echo "No Results...";
        }
    }
?>