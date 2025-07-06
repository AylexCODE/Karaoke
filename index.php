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
        <link rel="icon" href="./assets/logo.png" type="image/x-icon">
        <title>Karaoke</title>
    </head>
    <body>
        <section class="loading_screen">
            <span>
            </span>
            <span>
                <span>
                    <p>Found&nbsp;<p class="song_count"></p>&nbsp;Songs</p>
                </span>
                <span>
                    <span class="loading_bar"></span>
                </span>
            </span>
        </section>
        <section class="main">
            <span id="nav_activation_area" onmouseenter="nav_state(1)"></span>
            <nav>
                <span>
                    <span class="is_searching">Searching<br>Yolo</span>
                    <p class="entries_found">Found - Entries</p>
                </span>
                <div id="song_list">Loading...</div>
            </nav>
            <main>
                <span id="main_activation_area" onmouseenter="nav_state(0)"></span>
                <span id="video_player_wrapper">
                    <div id="video_player"></div>
                </span>
                <p id="main_message"></p>
            </main>
            <span id="info_activation_area" onmouseenter="show_nav_info()"></span>
            <div id="notification_wrapper">
                <span class="notification">
                    <p id="notif_header">Song added</p>
                    <p id="notif_title">Minecraft Bedrock/Java Edition</p>
                    <p id="notif_artist">Mojang</p>
                    <span id="notif_timer"></span>
                </span>
            </div>
            <article>
                <p>Up next</p>
                <div class="current_queue"></div>
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
        const nav_info = document.getElementsByTagName("article")[0];
        const main_video = document.getElementsByTagName("main")[0];
        const main_message = document.getElementById("main_message");
        const notification_wrapper = document.getElementById("notification_wrapper");
        const randomId = Math.floor(Math.random() * 4000);
        
        const in_queue = [];
        let video_player, isPlaying = false;
        
        function nav_state(state){
            if(state == 1){
                nav.style.left = "0%";
                nav_info.style.bottom = "0%";
                nav_info.style.width = "86.7%";
                main_video.style.width = "86.7%";
                notification_wrapper.style.left = "calc(13.3% + 1rem)";
            }else{
                nav.style.left = "-13.3%";
                nav_info.style.bottom = "-20%";
                main_video.style.width = "100%";
                notification_wrapper.style.left = "calc(0% + 1rem)";
            }
        };

        function show_nav_info(){
            nav_info.style.bottom = "0%";
            nav_info.style.width = "100%";
        }

        window.onload = () => {
            $.ajax({
                type: 'post',
                url: './components/song_list.php',
                data: { filter: "none" },
                success: (data) => {
                    $("#song_list").html(data);
                    $(".entries_found").html(`<span class="${$("#song_list").children().length > 0 ? "ok" : "error"}">Found ${$("#song_list").children().length} Entries</span>`);
                },
                error: () => {
                    $("#song_list").html("Error getting songs.");
                }
            });

            notification_wrapper.innerHTML = "";
            socket.emit('createConnection', randomId);

            socket.on('receivedMessage', (message) => {
                console.log(message);
            })

            setTimeout(() => {
                socket.emit('connectToConnection', randomId);
            }, 3000);

            console.log(randomId)
        }
        
        function set_player(video_id){
            if(!video_player){
                video_player = new YT.Player('video_player', {
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
                            $("#video_player_wrapper").css("visibility", "visible");
                        },
                        'onStateChange': (event) => {
                            yt_player_logging(event.data);

                            if(event.data == YT.PlayerState.ENDED && in_queue.length == 0){
                                main_message.style.display = "block";
                                $("#video_player_wrapper").css("visibility", "hidden");
                                isPlaying = false;
                            }else if(event.data == YT.PlayerState.ENDED){
                                $("#video_player_wrapper").css("visibility", "hidden");
                                set_queue(true);
                            }else if(event.data == YT.PlayerState.PLAYING){
                                $("#video_player_wrapper").css("visibility", "visible");
                                document.querySelector(".current_queue").classList.remove("active");
                            }
                        }
                    }
                });
            }else{
                video_player.loadVideoById(video_id);
            }
        }

        function add_queue(s_title, s_artist, s_video_id){
            in_queue.push({
                title: s_title,
                artist: s_artist,
                videoId: s_video_id
            });

            notification_wrapper.innerHTML = `<span class="notification"><p id="notif_header">Song added</p><p id="notif_title">${s_title}</p><p id="notif_artist">${s_artist}</p><span id="notif_timer"></span></span>`;
            set_queue(false);
        }

        function set_queue(next){
            if((in_queue.length > 0 && !isPlaying) || next){
                main_message.style.display = "none";
                const song_info = in_queue.shift();
                set_player(song_info.videoId);
                isPlaying = true;

                document.querySelector(".current_queue").classList.add("active");
            }
            
            if(in_queue.length == 0){
                document.querySelector("article > p:first-child").style.left = "-50dvw";
            }else{
                document.querySelector("article > p:first-child").style.left = "0dvw";
            }

            let queue_elements = "";
            in_queue.forEach((song) => {
                queue_elements += `<span><p>${song.title}</p><p>${song.artist}</p></span>`;
            });

            $(".current_queue").html(queue_elements);
        }

        const main_msg_template = "START THE PARTY!!!";
        const main_msg_write_delay = [100, 100, 150, 100, 100, 100, 50, 100, 100, 100, 150, 50, 100, 100, 150, 50, 100, 200];
        function set_main_message(i){
            if(i == 18) return;
            setTimeout(() => {
                main_message.innerHTML = main_msg_template.slice(0, i);
                set_main_message(++i);
            }, main_msg_write_delay[i]);
        }

        setTimeout(() => set_main_message(0), 7000);

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