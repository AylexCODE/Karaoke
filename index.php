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
            <nav>
                <div id="song_list">Loading...</div>
            </nav>
            <main>
                <iframe id="youtube" allow="autoplay" frameborder="0"></iframe>
            </main>
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
        const youtube = document.getElementById("youtube");
        window.onload = () => {
            $.ajax({
                type: 'post',
                url: './components/song_list.php',
                data: { filter: "none" },
                success: (data) => {
                    $("#song_list").html(data);
                },
                error: () => {
                    $("#song_list").html("Error getting songs.");
                }
            });
        }

        function play(video_id){
            youtube.setAttribute("src", `https://www.youtube.com/embed/${video_id.trim()}?controls=0&autoplay=1&rel=0`);
        }
        /*
            youtube.setAttribute("frameborder", "0");
            youtube.setAttribute("allow", "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture");
            youtube.setAttribute("allowfullscreen", "");

//document.getElementsByTagName("main")[0].appendChild(iframe);
}*/
    </script>
</html>