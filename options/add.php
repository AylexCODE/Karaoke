<?php
    require("../database/db_conn.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../vendor/jquery-3.7.1.min.js"></script>
        <link rel="icon" href="../assets/logo.png" type="image/x-icon">
        <style type="text/css">
            @font-face {
                font-family: space-grotesk-regular;
                url: ("../assets/fonts/SpaceGrotesk-Regular.otf");
                src: url("../assets/fonts/SpaceGrotesk-Regular.otf");
            }

            * {
                padding: 0;
                margin: 0;
                font-family: space-grotesk-regular;
            }

            body {
                width: 100dvw;
                height: 100dvh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            form {
                display: flex;
                flex-direction: column;
                border-radius: 1rem;
                padding: 0.5rem 1rem;
                box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            }

            form > label {
                font-size: 0.8rem;
                opacity: 0.7;
            }

            form > input, form > select {
                margin-bottom: 0.5rem;
            }

            button {
                width: 100%;
                padding: 0.3rem 0.6rem;
                background-color: #07DA63;
                text-transform: uppercase;
                border: none;
                border-radius: 0.7rem;
                font-size: 0.8rem;
                font-weight: bold;
                color: #FFF;
            }

            button:disabled {
                background-color: #E8E9EB;
            }

            #notif {
                width: 100%;
                display: grid;
                place-items: center;
                position: fixed;
                left: 0; top: -20dvh;
                height: 40px;
                z-index: 10;
            }

            .success, .error {
                position: relative;
                padding: 0.5rem 1rem;
                border: 1px solid #08F26E;
                border-radius: 0.5rem;
                background-color: #E8E9EB;
                animation: slideDown 7s cubic-bezier(0.19, 1, 0.21, 1);
            }

            .error {
                border-color: #DF2C14;
            }

            #previewer {
                height: 390px;
                width: 350px;
                border: 1px solid #CCC;
            }

            @keyframes slideDown {
                0%, 100% {
                    top: 0dvh;
                }
                20%, 80% {
                    top: calc(21dvh);
                }
            }

        </style>
        <title>Karaoke Dev</title>
    </head>
    <body>
        <span id="notif"></span>
        <form>
            <label for="ytURL">Youtube Link/URL</label>
            <input type="text" oninput="verifyVideoURL(this.value)" id="ytURL">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" oninput="checkForm()" disabled>
            <label for="artist">Artist</label>
            <input list="artists_list" name="artist" id="artist" oninput="checkForm()" disabled>
            <label for="genre">Genre</label>
            <input list="genres_list" name="genre" id="genre" oninput="checkForm()" disabled>
            <label for="is_vocal">Contains Vocal?</label>
            <select id="is_vocal" onchange="checkForm()" disabled>
                <option value="none" selected disabled></option>
                <option value=0>False</option>
                <option value=1>True</option>
            </select>
            <button id="submit" onclick="submitFormData()" disabled>Submit</button>
        </form>
        <p>Preview</p>
        <div id="previewer"></div>
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
        const notification = document.getElementById("notif");
        const submitBtn = document.getElementsByTagName("button")[0];
        const formTitle = document.getElementById("title");
        const formArtist = document.getElementById("artist");
        const formGenre = document.getElementById("genre");
        const formIsVocal = document.getElementById("is_vocal");

        let previewer, videoID;

        function submitFormData(){
            const data = new FormData(form);

            const gTitle = data.get("title");
            const gArtist = data.get("artist");
            const gGenre = data.get("genre");
            const gIsVocal = $("#is_vocal").val();

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
                    const isOk = response == "Added Successfully!" ? "success" : "error";
                    notification.innerHTML = `<span class="${isOk}">${response}</span>`;
                    submitBtn.disabled = true;
                    form.reset();
                },
                error: (error) => {
                    console.log(error);
                }
            });
        }

        function verifyVideoURL(url){
            if(url == "") return;

            let video_id;

            if(url.includes("youtu.be")){
                video_id = url.split("youtu.be/")[1];
                checkVideo(video_id.split("?")[0]);
            }else if(url.includes("youtube.com")){
                checkVideo(url.split("?v=")[1].split("&")[0]);
            }
        }

        function checkVideo(video_id){
            videoID = video_id;

            if(previewer){
                previewer.loadVideoById(video_id);
            }else{
                previewer = new YT.Player('previewer', {
                    height: "390",
                    width: "350",
                    videoId: video_id,
                    playerVars: {
                        'playsinline': 1,
                        'controls': 1,
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
                                formTitle.disabled = false;
                                formArtist.disabled = false;
                                formGenre.disabled = false;
                                formIsVocal.disabled = false;
                                console.log(previewer.videoTitle)
                            }
                            
                        },
                        'onError': (event) => {
                            console.log(event);
                            submitBtn.disabled = true;
                            formTitle.disabled = true; formTitle.value = "";
                            formArtist.disabled = true; formArtist.value = "";
                            formGenre.disabled = true; formGenre.value = "";
                            formIsVocal.disabled = true; formIsVocal.selectedIndex = 0;
                            videoID = "";
                        }
                    }
                });
            }
        }

        function checkForm(){
            if(formTitle.value.trim() == "" || formArtist.value.trim() == "" || formGenre.value.trim() == "" || formIsVocal.value == "none"){
                submitBtn.disabled = true;
            }else{
                submitBtn.disabled = false;
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