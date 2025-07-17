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
        </style>
        <title>JKaraoke Dev</title>
    </head>
    <body>
        <span id="notif"></span>
        <form onsubmit="return false">
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
            <label for="artist">Artist</label>
            <input type="text" name="artist" id="artist">
            <label for="genre">Genre</label>
            <input list="genreList" id="genre">
            <label for="isvocal">Contains Vocal?</label>
            <select name="isvocal" id="isvocal">
                <option value="none" selected disabled></option>
                <option value=0>False</option>
                <option value=1>True</option>
            </select>
            <button type="submit" disabled>Submit</button>
        </form>
    </body>
    <script type="text/javascript">
        const title = document.getElementById("title");
        const artist = document.getElementById("arist");
        const genre = document.getElementById("genre");
        const isVocal = document.getElementById("isvocal");
        let formType = "none";


    </script>
</html>