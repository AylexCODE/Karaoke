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
                <iframe id="youtube" allow="autoplay" frameborder="0"></iframe>
                <iframe id="youtubee" allow="autoplay" frameborder="0"></iframe>
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
            </div >
        </section>
    </body>
    <script type="text/javascript">
        const nav = document.getElementsByTagName("nav")[0];
        const nav_options = document.getElementsByTagName("article")[0];
        const main_video = document.getElementsByTagName("main")[0];
        const main_video_filler = document.getElementsByClassName("filler");
        const main_message = document.getElementById("main_message");
        const youtube = document.getElementById("youtube");
        const youtubee = document.getElementById("youtubee");

        const in_queue = [];
        let play_timer, video_player = 1, isPlaying = false;
        
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

        function play(video_id){
            if(!isPlaying) isPlaying = true;
            main_message.style.visibility = "hidden";

            if(video_player == 1){
                youtube.setAttribute("src", `https://www.youtube.com/embed/${video_id}?controls=0&autoplay=1&rel=0`);
                video_player = 2;
                setTimeout(() => {
                    youtube.style.visibility= "visible";
                }, 2000);
            }else{
                youtubee.setAttribute("src", `https://www.youtube.com/embed/${video_id}?controls=0&autoplay=1&rel=0`);
                video_player = 1;
                setTimeout(() => {
                    youtubee.style.visibility = "visible";
                }, 2000);
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
            if(!isPlaying || next){
                if(in_queue.length > 0){
                    play(in_queue[0].videoId);
                    clearTimeout(play_timer);
                    play_timer = setTimeout(() => {
                        set_queue(true);
                        if(video_player == 1){
                            youtube.style.visibility = "hidden";
                        }else{
                            youtubee.style.visibility = "hidden";
                        }
                    }, in_queue.shift().duration * 1000);
                }else{
                    isPlaying = false;
                }
            }
        }
        /*
            youtube.setAttribute("frameborder", "0");
            youtube.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture");
            youtube.setAttribute("allowfullscreen", "");

//document.getElementsByTagName("main")[0].appendChild(iframe);
}*/
    </script>
</html>