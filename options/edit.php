<?php
    require("../database/db_conn.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <script src="../vendor/jquery-3.7.1.min.js"></script>
        <link rel="icon" type="image/x-icon" href="../assets/images/logo.svg">
        <style type="text/css">
            @font-face{
                font-family: space-grotesk-regular;
                url: ("../assets/fonts/SpaceGrotesk-Regular.otf");
                src: url("../assets/fonts/SpaceGrotesk-Regular.otf");
            }

            *{
                padding: 0;
                margin: 0;
                font-family: space-grotesk-regular;
            }

            body{
                width: 100dvw;
                height: 100dvh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            form {
                display: flex;
                flex-direction: column;
                width: 80%;
                box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 27px 0px;
                padding: 1rem 1rem;
                border-radius: 0.5rem;
            }

            form > label {
                opacity: 0.7;
                font-size: 0.7rem;
            }

            form > input, form > select {
                margin-bottom: 0.5rem;
            }

            section {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 60%;
                margin-top: 1rem;
                width: 100%;
            }

            #songList {
                height: 100%;
                width: 100%;
                display: flex;
                flex-direction: column;
                overflow-y: scroll;
            }

            #songList > span {
                width: calc(100% - 3.5rem - 2px);
                margin: 0.5rem 1rem 0.25rem 1rem;
                border-radius: 10px;
                padding: 0.5rem 0.75rem;
            }

            #songList > span > p {
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            #songList > span > p:last-child {
                opacity: 0.7;
                font-size: 0.7rem;
            }

            .isvocal0 {
                border: 1px solid #503537;
                transition: all 1s ease-in;
            }

            .isvocal1 {
                border: 1px solid #6C01D6;
                color: #6C01D6;
                transition: all 1s ease-in;
            }

            .isvocal0:active , .isvocal1:active {
                border-color: #FFF;
                background-color: #503537;
                color: #FFF;
                transition: all 0s ease-in;
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
                position: fixed;
                left: 0; top: -20dvh;
                display: grid;
                place-items: center;
                height: 40px;
                z-index: 10;
            }

            .success, .error {
                position: relative;
                padding: 0.5rem 1rem;
                border: 1px solid #08F26E;
                border-radius: 0.5rem;
                background-color: #E8E9EB;
                opacity: 0;
                animation: slideDown 7s cubic-bezier(0.19, 1, 0.21, 1);
            }

            .error {
                border-color: #DF2C14;
            }

            @keyframes slideDown {
                0%, 100% {
                    top: 0dvh;
                    opacity: 0;
                }
                20%, 80% {
                    top: 21dvh;
                    opacity: 1;
                }
            }
        </style>
        <title>JKaraoke Dev</title>
    </head>
    <body>
        <span id="notif"></span>
        <form onsubmit="return false">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" oninput="checkSong('title')">
            <label for="artist">Artist</label>
            <input type="text" name="artist" id="artist" oninput="checkSong('artist')">
            <label for="genre">Genre</label>
            <input list="genreList" name="genre" id="genre" disabled oninput="checkSong('genre')">
            <label for="isvocal">Contains Vocal?</label>
            <select name="isvocal" id="isvocal" onchange="checkSong('vocal')">
                <option value="none" selected disabled></option>
                <option value=0>False</option>
                <option value=1>True</option>
            </select>
            <input type="hidden" name="videoid" id="videoid">
            <input type="hidden" name="prevartist" id="prevartist">
            <button type="submit" id="submit" onclick="submitFormData()" disabled>Submit</button>
        </form>
        <section>
            <p class="entriesFound">Found - Entries</p>
            <div id="songList"></div>
        </section>
    </body>
    <script type="text/javascript">
        const form = document.getElementsByTagName("form")[0];
        const title = document.getElementById("title");
        const artist = document.getElementById("artist");
        const videoId = document.getElementById("videoid");
        const prevArtist = document.getElementById("prevartist");
        // const genre = document.getElementById("genre");
        const isVocal = document.getElementById("isvocal");
        const submitBtn = document.getElementById("submit");
        const notification = document.getElementById("notif");
        let formType = "none", delay;

        function addQueue(s_title, s_artist, s_videoId, isvocal){
            videoId.value = s_videoId;
            title.value = s_title;
            artist.value = s_artist;
            prevartist.value = s_artist;
            isVocal.selectedIndex = (parseInt(isvocal) + 1);
            submitBtn.disabled = true;
        }

        function submitFormData(){
            const data = new FormData(form);

            $.ajax({
                type: "POST",
                url: "./actions.php",
                data: {
                    title: data.get("title"),
                    artist: data.get("artist"),
                    prevartist: data.get("prevartist"),
                    //genre: data.get("genre"),
                    isvocal: data.get("isvocal"),
                    videoid: data.get("videoid")
                    },
                success: function(response){
                    console.log(response);
                    if(response == "Artist Name Changed" || response == "Song Information Changed"){
                        notification.innerHTML = `<span class="success">${response}</span>`;
                        form.reset();
                        getSongs("");
                    }
                },
                error: function (error){
                    console.log(error);
                }
            });
        }

        function checkSong(type){
            if(artist.value.trim() == ""){
                submitBtn.disabled = true;
                return;
            }

            submitBtn.disabled = false;
            switch(type){
                case "title":
                    if(title.value.trim() == ""){
                        isVocal.disabled = true;
                    }else{
                        isVocal.disabled = false;
                    }
                    break;
            }
        }

        function getSongs(q){
            $.ajax({
                type: 'post',
                url: '../components/songList.php',
                data: { filter: "none", search: q },
                success: (data) => {
                    $("#songList").html(data);
                    $(".entriesFound").html(`<span class="${$("#songList").children().length > 0 ? "ok" : "error"}">Found ${$("#songList").children().length} Entries</span>`);
                },
                error: () => {
                    $("#songList").html("Error getting songs.");
                }
            });
        }

        /*function search(q){
            songSearch = q;
            
            clearTimeout(delay);
            delay = setTimeout(() => {
                getSongs(q);
            }, 1000);
        }*/

        window.onload = () => {
            getSongs("");
        }
    </script>
</html>