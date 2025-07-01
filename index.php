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
                <span id="video_player_wrapper">
                    <div id="video_player"></div>
                </span>
                <span class="filler"></span>
                <p id="main_message">Start The Party!!!</p>
            </main>
            <span id="options_activation_area" onmouseenter="controls_state(1)"></span>
            <article>
                <p>Search</p>
                <p id="current_queue">Queue</p>
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
        
        const in_queue = [];
        let video_player, isPlaying = false;
        
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
        
      /*  function play(event, player){
            console.log(event.data, player, in_queue);
            if(event.data == YT.PlayerState.PLAYING){
                if(player == 1){
                    player1_wrapper.style.visibility = "visible";
                    player2_wrapper.style.visibility = "hidden";
                }else{
                    player1_wrapper.style.visibility = "hidden";
                    player2_wrapper.style.visibility = "visible";
                }
            }
            
            if(event.data == YT.PlayerState.ENDED){
                if(active_player == 1){
                    if(video_player2) video_player2.playVideo();
                    video_player1.stopVideo();
                    active_player = 2;
                }else{
                    video_player1.playVideo();
                    video_player2.stopVideo();
                    active_player = 1;
                }

                players_status[`player${player}`] = "available";

                if(players_status.player1 == "available" && players_status.player2 == "available"){
                    isPlaying = false;
                    main_message.style.display = "block";
                }else{
                    set_queue();
                }
            }

            if(firstSong){
                video_player1.playVideo();
                firstSong = false;
            }
        }
*/
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
                            console.log(event);
                            if(event.data == YT.PlayerState.ENDED && in_queue.length == 0){
                                main_message.style.display = "block";
                                $("#video_player_wrapper").css("visibility", "hidden");
                                isPlaying = false;
                            }else if(event.data == YT.PlayerState.ENDED){
                                set_queue(true);
                            }
                        }
                    }
                });
            }else{
                $("#video_player_wrapper").css("visibility", "visible");
                video_player.loadVideoById(video_id);
            }
        }

        function add_queue(s_title, s_artist, s_duration, s_video_id){
            in_queue.push({
                title: s_title,
                artist: s_artist,
                duration: s_duration,
                videoId: s_video_id
            });

            set_queue(false);
        }

        function set_queue(next){
            if((in_queue.length > 0 && !isPlaying) || next){
                main_message.style.display = "none";
                const song_info = in_queue.shift();
                set_player(song_info.videoId);
                isPlaying = true;
            }

                let queue_elements = "";
                in_queue.forEach((song) => {
                    queue_elements += `<span><p>${song.title}</p><p>${song.artist}</}</span>`;
                });
                $("#current_queue").html(queue_elements);
                    /*clearTimeout(play_timer);

                    play_timer = setTimeout(() => {

                    }, (song_info.duration * 1000) - 30000);*/
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