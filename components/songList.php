<?php
    require_once("../database/db_conn.php");

    if(isset($_POST)){
        $filter = "";
        $search = "";

        if($_POST["filter"] == "withVocals"){
            $filter = "WHERE songs.isVocal = 1 ";
        }elseif($_POST["filter"] == "noVocals"){
            $filter = "WHERE songs.isVocal = 0 ";
        }

        if(!empty($_POST["search"])){
            if(empty($filter)){
                $search = "WHERE songs.title LIKE '%" . $_POST["search"] . "%' OR artists.name LIKE '%" . $_POST["search"] . "%' ";
            }else{
                $search = " AND songs.title LIKE '%" . $_POST["search"] . "%' OR artists.name LIKE '%" . $_POST["search"] . "%' ";
            }
        }

        $q = $conn->prepare("SELECT songs.title AS Title, artists.name AS Artist, songs.isVocal as is_vocal, songs.video_id as VideoID FROM songs INNER JOIN artists ON songs.artist_id = artists.id " . $filter . $search . "ORDER BY songs.title");
        $q->execute();
        $result = $q->fetchAll(PDO::FETCH_ASSOC);

        if(count($result) != 0){
            foreach($result as $song){
            echo "<span class='isvocal" . $song["is_vocal"] . "' onclick='addQueue(&#96;" . htmlspecialchars($song["Title"]) . "&#96;, &#96;" . htmlspecialchars($song["Artist"]) . "&#96;, &#x27;" . $song["VideoID"] . "&#x27;, &#x27;" . $song["is_vocal"] . "&#x27;)'>";
            echo "<p>" . $song["Title"] ."</p>";
            echo "<p>" . $song["Artist"] . "</p>";
            echo "</span>";
            }
        }else{
            echo "No Result...";
        }
    }
?>