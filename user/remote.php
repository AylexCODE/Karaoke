<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../vendor/jquery-3.7.1.min.js"></script>
        <script src="../vendor/socketio-4.8.1.min.js"></script>
        <link rel="icon" href="./assets/logo.png" type="image/x-icon">
        <style type="text/css">
            * {
                padding: 0;
                margin: 0;
            }

            body {
                height: 100dvh;
                width: 100dvw;
            }

            nav {
                width: calc(100% - 2rem);
                height: 40px;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding-inline: 1rem;
                box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 27px 0px;
            }

            nav p {
                text-wrap: nowrap;
            }

            nav > span > button {
                text-wrap: nowrap;
                background-color: #07DA63;
            }

            nav > span:nth-child(2){
                display: flex;
                flex-direction: row;
                gap: 0.5rem;
            }
        </style>
        <title>Karaoke Remote</title>
    </head>
    <body>
        <div popover="auto" id="karaokeId">
            <input type="text" oninput="setId(this.value)"><p id="connectionId">Not set</p>
        </div>
        <nav>
            <p class="entriesFound">Found - Entries</p>
            <span>
                <p id="connectionIdStatus">Null</p>
                <button popovertarget="karaokeId">Set ID</button>
            </span>
        </nav>
        <div id="songList"></div>
        <span onclick="sendCommand()">Add Song</span>
    </body>
    <script type="text/javascript" src="../scripts/socketio.js"></script>
    <script type="text/javascript">
        let hostId = 0, delay;
        let s_title, s_artist, s_video_id;

        function sendCommand(){
            socket.emit('sendMessage', {
                id: hostId,
                message: "AddQueue",
                songInfo: {
                    title: s_title,
                    artist: s_artist,
                    videoId: s_video_id
                }
            });
        }

        function setId(id){
            clearTimeout(delay);
            delay = setTimeout(() => {
                hostId = id;
                socket.emit('connectToConnection', id, (response) => {
                    $("#connectionIdStatus").html(response.status == "Connected" ? response.status : "Invalid");
                    $("#connectionId").html(response.status);
                    console.log(response.status);
                });
            }, 1000);
        }

        function addQueue(title, artist, video_id){
            s_title = title;
            s_artist = artist;
            s_video_id = video_id;
        }

        window.onload = () => {
            $.ajax({
                type: 'post',
                url: '../components/songList.php',
                data: { filter: "none" },
                success: (data) => {
                    $("#songList").html(data);
                    $(".entriesFound").html(`<span class="${$("#songList").children().length > 0 ? "ok" : "error"}">Found ${$("#songList").children().length} Entries</span>`);
                },
                error: () => {
                    $("#songList").html("Error getting songs.");
                }
            });
        }
    </script>
</html>