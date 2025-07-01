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
        <link rel="icon" href="./assets/logo.png" type="image/x-icon">
      <!--  <meta http-equiv="refresh" content="2; url='./main'" />-->
        <title>Karaoke</title>
    </head>
    <body>
        <section class="loading_screen">
            <span>
                <p>Found&nbsp;<span class="song_count"></p>&nbsp;Songs</p>
            </span>
        </section>
        <section class="main">
            <span id="nav_activation_area" onmouseenter="controls_state(1)"></span>
            <nav>
                <span>
                    <span class="is_searching">Searching<br>Yolo</span>
                    <p class="entries_found">Found - Entries</p>
                </span>
                <div id="song_list">Loading...</div>
            </nav>
            <main>
                <span id="main_activation_area" onmouseenter="controls_state(0)"></span>
                <span class="filler"></span>
                <span id="video_player1_wrapper">
                    <div id="video_player1"></div>
                </span>
                <span id="video_player2_wrapper"><div id="video_player2"></div>
                </span>
                <span class="filler"></span>
                <p id="main_message">Start The Party!!!</p>
            </main>
            <span id="options_activation_area" onmouseenter="controls_state(1)"></span>
            <article>
                <p>Search</p>
                <p>Info</p>
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
    <script type="text/javascript">
        const nav = document.getElementsByTagName("nav")[0];
        const nav_options = document.getElementsByTagName("article")[0];
        const main_video = document.getElementsByTagName("main")[0];
        const main_video_filler = document.getElementsByClassName("filler");
        const main_message = document.getElementById("main_message");
        const player1_wrapper = document.getElementById("video_player1_wrapper");
        const player2_wrapper = document.getElementById("video_player2_wrapper");
        
        const in_queue = [];
        let play_timer, video_player1, video_player2, video_player = 1, isPlaying = false, players_status = {
            player1: "available",
            player2: "available"
        }
        
        function controls_state(state){
            if(state == 1){
                nav.style.left = "0%";
                nav_options.style.bottom = "0%";
                main_video.style.width = "86.7%";
                main_video_filler[0].classList.remove("active");
                main_video_filler[1].classList.remove("active");
            }else{
                nav.style.left = "-13.3%";
                nav_options.style.bottom = "-20%";
                main_video.style.width = "100%";
                main_video_filler[0].classList.add("active");
                main_video_filler[1].classList.add("active");
            }
        };

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
        }
        
        function play(event, player){
            console.log(event.data, event.data == YT.PlayerState.ENDED, player);
            if(event.data == YT.PlayerState.ENDED || event.data == YT.PlayerState.CUED){
                if(player == 1){
                    player1_wrapper.style.visibility = "hidden";
                    player2_wrapper.style.visibility = "visible";
                }else{
                    player1_wrapper.style.visibility = "visible";
                    player2_wrapper.style.visibility = "hidden";
                }
            }
            if(event.data == YT.PlayerState.ENDED || event.data == YT.PlayerState.CUED){
                players_status[`player${player}`] = "available";
                set_queue();
            }
        }

        function set_player(video_id, duration){
            if(video_player == 1){
                video_player = 2;

                if(!video_player1){
                    video_player1 = new YT.Player('video_player1', {
                        height: "100%",
                        width: "100%",
                        videoId: video_id,
                        playerVars: {
                            'playsinline': 1,
                            'controls': 0,
                            'rel': 0
                        },
                        events: {
                            'onReady': (event) => event.target.playVideo(),
                            'onStateChange': (event) => {
                                play(event, 1);
                            }
                        }
                    });

                    setTimeout(() => {
                        video_player1.stopVideo();
                    }, 10000);
                }else{
                    video_player1.loadVideoById(video_id);
                }

                players_status.player1 = "unavailable";
            }else{
                video_player = 1;

                if(!video_player2){
                    video_player2 = new YT.Player('video_player2', {
                        height: "100%",
                        width: "100%",
                        videoId: video_id,
                        playerVars: {
                            'playsinline': 1,
                            'controls': 0,
                            'rel': 0
                        },
                        events: {
                            'onStateChange': (event) => {
                                play(event, 2);
                            }
                        }
                    });
                }else{
                    video_player2.loadVideoById(video_id);
                }

                players_status.player2 = "unavailable";
            }

            if(!isPlaying){
                isPlaying = true;
                main_message.style.visibility = "hidden";
                player1_wrapper.style.visibility = "visible";
            }
        }

        function add_queue(s_title, s_artist, s_duration, s_video_id){
            in_queue.push({
                title: s_title,
                artist: s_artist,
                duration: s_duration,
                videoId: s_video_id
            });

            set_queue();
        }

        function set_queue(){
            if(in_queue.length > 0){
                if(players_status.player1 == "available" || players_status.player2 == "available"){
                    const song_info = in_queue.shift();
                    set_player(song_info.videoId);
                }
                    /*clearTimeout(play_timer);

                    play_timer = setTimeout(() => {

                    }, (song_info.duration * 1000) - 30000);*/
            }else{
                isPlaying = false;
            }
        }
        /*
            youtube.setAttribute("frameborder", "0");
            youtube.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture");
            youtube.setAttribute("allowfullscreen", "");

//document.getElementsByTagName("main")[0].appendChild(iframe);
}*/
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    </script>
</html>