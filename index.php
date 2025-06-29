<?php
    require("./database/db_conn.php");

    include_once("./style.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </body>
</html>