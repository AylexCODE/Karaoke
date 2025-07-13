<?php
    require("./database/db_conn.php");

    include_once("./style.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="./vendor/jquery-3.7.1.min.js"></script>
        <script src="./vendor/socketio-4.8.1.min.js"></script>
        <link rel="icon" href="./assets/images/logo.svg" type="image/x-icon">
        <title>Karaoke</title>
    </head>
    <body>
        <section class="loadingScreen">
            <span>
            </span>
            <span>
                <span>
                    <p>Found&nbsp;<p class="songCount"></p>&nbsp;Songs</p>
                </span>
                <span>
                    <span class="loadingBar"></span>
                </span>
            </span>
        </section>
        <section class="main">
            <span id="navActivationArea" onmouseenter="nav_state(1)"></span>
            <nav>
                <span>
                    <span class="isSearching">Searching<br>Yolo</span>
                    <p class="entriesFound">Found - Entries</p>
                </span>
                <div id="songList">Loading...</div>
                <div id="filters">
                <span class="filterAll" onclick="filterSongs('all')">All</span>
                <span class="filterWithVocals" onclick="filterSongs('withVocals')">With Vocals</span>
                <span class="filerWithNoVocals" onclick="filterSongs('noVocals')">No Vocals</span>
                </div>
            </nav>
            <main>
                <span id="mainActivationArea" onmouseenter="nav_state(0)"></span>
                <span id="videoPlayerWrapper">
                    <div id="videoPlayer"></div>
                </span>
                <p id="mainMessage"></p>
            </main>
            <span id="infoActivationArea" onmouseenter="showNavInfo()"></span>
            <div id="notificationWrapper">
                <span class="notification">
                    <p id="notifHeader">Song added</p>
                    <p id="notifTitle">Minecraft Bedrock/Java Edition</p>
                    <p id="notifArtist">Mojang</p>
                    <span id="notifTimer"></span>
                </span>
            </div>
            <article>
                <span>
                    <span>
                        <p>Up next</p>
                        <p id="skipBtn" onclick="setQueue(true)">Skip</p>
                    </span>
                    <div class="currentQueue"></div>
                </span>
                <span id="debugInfo" onclick="$('#debugInfo').css('opacity', '1'); setTimeout(()=>{$('#debugInfo').css('opacity', '0')}, 10000)">
                    <span>Connection ID:&nbsp;<p id="connectionID">0000</p>&nbsp;(<p id="connectionStatus">disconnected</p>)</span>
                    <span>Screen Resolution:&nbsp;<p id="screenRes">1920x1080</p></span>
                </span>
            </article>
            <div class="area" >
                <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </section>
    </body>
    <script type="text/javascript" src="./scripts/socketio.js"></script>
    <script type="text/javascript">
        const nav = document.getElementsByTagName("nav")[0];
        const navInfo = document.getElementsByTagName("article")[0];
        const mainVideo = document.getElementsByTagName("main")[0];
        const mainMessage = document.getElementById("mainMessage");
        const notificationWrapper = document.getElementById("notificationWrapper");
        const randomId = Math.floor(Math.random() * 9999), debugInfo = document.getElementById("debugInfo");
        
        const in_queue = [];
        let videoPlayer, isPlaying = false;
        
        function nav_state(state){
            if(state == 1){
                nav.style.left = "0%";
                navInfo.style.bottom = "0%";
                navInfo.style.width = "80%";
                mainVideo.style.width = "80%";
                notificationWrapper.style.left = "calc(20% + 1rem)";
            }else{
                nav.style.left = "-20%";
                navInfo.style.bottom = "-20%";
                mainVideo.style.width = "100%";
                notificationWrapper.style.left = "calc(0% + 1rem)";
            }
        };

        function showNavInfo(){
            navInfo.style.bottom = "0%";
            navInfo.style.width = "100%";
        }

        function filterSongs(s_filter){
            $.ajax({
                type: 'post',
                url: './components/songList.php',
                data: { filter: s_filter },
                success: (data) => {
                    $("#songList").html(data);
                    $(".entriesFound").html(`<span class="${$("#songList").children().length > 0 ? "ok" : "error"}">Found ${$("#songList").children().length} Entries</span>`);
                },
                error: () => {
                    $("#songList").html("Error getting songs.");
                }
            });
        }

        window.onload = () => {
            filterSongs("noVocals");

keepAlive();
            notificationWrapper.innerHTML = "";
            socket.emit('createConnection', randomId);

            $("#screenRes").html(`${window.innerWidth}x${window.innerHeight}`);
            window.onresize = () => {
                $("#screenRes").html(`${window.innerWidth}x${window.innerHeight}`);
            }
            
            $("#connectionID").html(randomId);

            socket.on('receivedMessage', (data) => {
                if(data.message == "AddQueue"){
                    const { title, artist, videoId } = data.songInfo;
                    console.log(title, artist, videoId)
                    addQueue(title, artist, videoId);
                }
                console.log(data);
            });

            console.log(randomId);
            setInterval(keepAlive, 30000);
        }
        
        function set_player(video_id){
            if(!videoPlayer){
                videoPlayer = new YT.Player('videoPlayer', {
                    height: "100%",
                    width: "100%",
                    videoId: video_id,
                    playerVars: {
                        'playsinline': 1,
                        'controls': 0,
                        'rel': 0,
                    },
                    events: {
                        'onReady': (event) => {
                            event.target.playVideo();
                            $("#videoPlayerWrapper").css("visibility", "visible");
                        },
                        'onStateChange': (event) => {
                            yt_player_logging(event.data);

                            if(event.data == YT.PlayerState.ENDED && in_queue.length == 0){
                                mainMessage.style.display = "block";
                                $("#videoPlayerWrapper").css("visibility", "hidden");
                                isPlaying = false;
                            }else if(event.data == YT.PlayerState.ENDED){
                                $("#videoPlayerWrapper").css("visibility", "hidden");
                                setQueue(true);
                            }else if(event.data == YT.PlayerState.PLAYING){
                                $("#videoPlayerWrapper").css("visibility", "visible");
                                document.querySelector(".currentQueue").classList.remove("active");
                            }
                        }
                    }
                });
            }else{
                videoPlayer.loadVideoById(video_id);
            }
        }

        function addQueue(s_title, s_artist, s_video_id, s_isVocal){
            in_queue.push({
                title: s_title,
                artist: s_artist,
                videoId: s_video_id,
                isVocal: s_isVocal
            });

            notificationWrapper.innerHTML = `<span class="notification"><p id="notifHeader">Song added</p><p id="notifTitle">${s_title}</p><p id="notifArtist">${s_artist}</p><span id="notifTimer"></span></span>`;
            setQueue(false);
        }

        function setQueue(next){
            if((in_queue.length > 0 && !isPlaying) || next){
                mainMessage.style.display = "none";
                const song_info = in_queue.shift();
                set_player(song_info.videoId);
                isPlaying = true;

                document.querySelector(".currentQueue").classList.add("active");
            }
            
            if(in_queue.length == 0){
                document.querySelector("article > span > span:first-child").style.left = "-50dvw";
            }else{
                document.querySelector("article > span > span:first-child").style.left = "0dvw";
            }

            let queue_elements = "";
            in_queue.forEach((song) => {
                queue_elements += `<span class='isvocalq${song.isVocal}'><p>${song.title}</p><p>${song.artist}</p></span>`;
            });

            $(".currentQueue").html(queue_elements);
        }

        const main_msg_template = "START THE PARTY!!!";
        const main_msg_write_delay = [100, 100, 150, 100, 100, 100, 50, 100, 100, 100, 150, 50, 100, 100, 150, 50, 100, 200];
        // function set_mainMessage(i){
        //     if(i == 18) return;
        //     setTimeout(() => {
        //         mainMessage.innerHTML = main_msg_template.slice(0, i);
        //         set_mainMessage(++i);
        //     }, main_msg_write_delay[i]);
        // }

        //setTimeout(() => set_mainMessage(0), 7000);

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

        const keepAlive = () => {
            $.ajax({
                type: 'GET',
                url: 'https://socketio-f317.onrender.com',
                success: (response) => {
                    console.log(response, "Ok");
                },
                error: (error) => {
                    console.log(error, "Error");
                }
            });
        }

        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    </script>
</html>