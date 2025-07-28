<?php
    require("../database/db_conn.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../vendor/jquery-3.7.1.min.js"></script>
        <link rel="icon" href="./assets/logo.png" type="image/x-icon">
        <title>Karaoke Dev</title>
    </head>
    <body>
        <form>
            <p>Youtube Link/URL</p>
            <input type="text" oninput="verifyVideoURL(this.value)">
            <p>Title</p>
            <input type="text" id="title" name="title">
            <p>Artist</p>
            <input list="artists_list" name="artist">
            <p>Genre</p>
            <input list="genres_list" name="genre">
            <p>Contains Vocal?</p>
            <select id="is_vocal">
                <option value="0">False</p>
                <option value="1">True</p>
            </select>
            <div id="submit" onclick="submitFormData()">Submit</div>
            <p>Preview</p>
            <div id="previewer"></div>
        </form>
        <datalist id="artists_list">
            <?php
                $getArtists = $conn->prepare("SELECT name FROM artists;");
                $getArtists->execute();
                $artists = $getArtists->fetchAll(PDO::FETCH_ASSOC);
                foreach($artists as $artist){
                    echo "<option value='" . $artist["name"] . "'</option>";
                }
            ?>
        </datalist>
        <datalist id="genres_list">
            <?php
                $getGenres = $conn->prepare("SELECT name FROM genres;");
                $getGenres->execute();
                $genres = $getGenres->fetchAll(PDO::FETCH_ASSOC);
                
                foreach($genres as $genre){
                        echo "<option value='" . $genre["name"] . "'</option>";
                }
            ?>
        </datalist>
    </body>
    <script type="text/javascript">
        const title = document.getElementById("title");
        const form = document.getElementsByTagName("form")[0];

        let previewer, videoID;

        function submitFormData(){
            const data = new FormData(form);

            const gTitle = data.get("title");
            const gArtist = data.get("artist");
            const gGenre = data.get("genre");
            const gIsVocal = data.get("is_vocal");

            $.ajax({
                type: 'POST',
                url: './actions.php',
                data: {
                    title: gTitle,
                    artist: gArtist,
                    genre: gGenre,
                    isvocal: gIsVocal,
                    video_id: videoID
                },
                success: (response) => {
                    console.log(response);
                },
                error: (error) => {
                    console.log(error);
                }
            });
        }

        function verifyVideoURL(url){
            let video_id;

            if(url.includes("youtu.be")){
                video_id = url.split("youtu.be/")[1];
                checkVideo(video_id.split("?")[0]);
            }else if(url.includes("youtube.com")){
                checkVideo(url.split("?v=")[1]);
            }
        }

        function checkVideo(video_id){
            videoID = video_id;

            if(previewer){
                previewer.loadVideoById(video_id);
            }else{
                previewer = new YT.Player('previewer', {
                    height: "390",
                    width: "640",
                    videoId: video_id,
                    playerVars: {
                        'playsinline': 1,
                        'controls': 0,
                        'rel': 0,
                    },
                    events: {
                            'onReady': (event) => {
                                event.target.playVideo();
                            },
                            'onStateChange': (event) => {
                                yt_player_logging(event.data);
                            if(event.data == YT.PlayerState.PLAYING){
                                title.value = previewer.videoTitle;
                                console.log(previewer.videoTitle)
                            }
                            
                        }
                    }
                });
            }
        }

        function yt_player_logging(event){
            switch(event){
                case -1: console.log("Unstarted"); break;
                case 0: console.log("Ended"); break;
                case 1: console.log("Playing"); break;
                case 2: console.log("Paused"); break;
                case 3: console.log("Buffering"); break;
                case 5: console.log("Cued"); break;
            }
        }

        var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    </script>
</html>
