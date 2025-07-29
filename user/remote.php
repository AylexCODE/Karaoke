<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../vendor/jquery-3.7.1.min.js"></script>
        <script src="../vendor/socketio-4.8.1.min.js"></script>
        <link rel="icon" href="./assets/logo.png" type="image/x-icon">
        <style type="text/css">
            @font-face {
                font-family: space-grotesk-regular;
                url: ("../assets/fonts/SpaceGrotesk-Regular.otf");
                src: url("../assets/fonts/SpaceGrotesk-Regular.otf");
            }

            * {
                padding: 0;
                margin: 0;
                font-family: space-grotesk-regular;
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
                border: none;
                border-radius: 10px;
                padding: 0.3rem 0.6rem;
                color: #FFF;
                font-weight: bold;
            }

            nav > span:nth-child(2){
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
            }

            section {
                display: flex;
                flex-direction: row;
                justify-content: space-around;
                height: 30px;
                width: calc(100% - 1rem);
                margin-top: 0.5rem;
                padding-inline: 0.5rem;
                gap: 0.3rem;
            }

            #songSearch {
                border: 1px solid #503537;
                border-radius: 10px;
                padding: 0.3rem 0.6rem;
                width: 100%;
            }

            section > button {
                border: 1px solid #503537;
                border-radius: 10px;
                padding: 0.3rem 0.6rem;
                text-wrap: nowrap;
            }

            #songList {
                height: calc(100% - 78px);
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

            #karaokeId {
                top: 30%;
                width: 104px;
                left: calc(50% - 52px - 2rem);
                padding: 1rem 1rem;
            }

            #karaokeId > input {
                width: 104px;
            }

            #karaokeId > p {
                width: 100%;
                text-align: center;
            }
        </style>
        <title>Karaoke Remote</title>
    </head>
    <body>
        <div popover="auto" id="karaokeId">
            <input type="text" oninput="setId(this.value)" placeholder="Enter Karaoke ID"><p id="connectionId">Not set</p>
        </div>
        <nav>
            <p class="entriesFound">Found - Entries</p>
            <span>
                <p id="connectionIdStatus">Null</p>
                <button popovertarget="karaokeId">Set ID</button>
            </span>
        </nav>
        <section>
            <input type="search" placeholder="Search" id="songSearch" oninput="search(this.value)"></input>
            <button onclick="setFilter('withVocals')" id="fBtnVocal">Vocal</button>
            <button onclick="setFilter('noVocals')" id="fBtnNoVocal">No-Vocal</button>
        </section>
        <div id="songList"></div>
    </body>
    <script type="text/javascript" src="../scripts/socketio.js"></script>
    <script type="text/javascript">
        let hostId = "NaN", delay, songFilter = "none", songSearch = "";

        function addQueue(s_title, s_artist, s_video_id, s_isvocal){
            if(hostId == "NaN") return document.getElementById("karaokeId").togglePopover();

            socket.emit('sendMessage', {
                id: hostId,
                message: "AddQueue",
                songInfo: {
                    title: s_title,
                    artist: s_artist,
                    videoId: s_video_id,
                    isVocal: s_isvocal
                }
            });
        }

        function setId(id){
            clearTimeout(delay);
            delay = setTimeout(() => {
                socket.emit('connectToConnection', {type:'client', id: id}, (response) => {
                    $("#connectionIdStatus").html(response.status == "Connected" ? response.status : "Invalid");
                    $("#connectionId").html(response.status);
                    response.status == "Connected" ? hostId = id : hostId = "NaN";
                    console.log(response.status);
                });
            }, 1000);
        }

        function getSongs(){
            $.ajax({
                type: 'post',
                url: '../components/songList.php',
                data: { filter: songFilter, search: songSearch },
                success: (data) => {
                    $("#songList").html(data);
                    $(".entriesFound").html(`<span class="${$("#songList").children().length > 0 ? "ok" : "error"}">Found ${$("#songList").children().length} Entries</span>`);
                },
                error: () => {
                    $("#songList").html("Error getting songs.");
                }
            });
        }

        function search(q){
            songSearch = q;
            
            clearTimeout(delay);
            delay = setTimeout(() => {
                getSongs(q);
            }, 1000);
        }

        function setFilter(value){
            switch(value){
                case "noVocals":
                    if(songFilter == value){
                        songFilter = "none";
                        $("#fBtnNoVocal").css("background-color", "buttonface");
                        $("#fBtnNoVocal").css("color", "#000");
                    }else{
                        songFilter = value;
                        $("#fBtnNoVocal").css("background-color", "#303537");
                        $("#fBtnNoVocal").css("color", "#FFF");
                    }

                    $("#fBtnVocal").css("background-color", "buttonface");
                    $("#fBtnVocal").css("color", "#000");
                    break;
                case "withVocals":
                if(songFilter == value){
                        songFilter = "none";
                        $("#fBtnVocal").css("background-color", "buttonface");
                        $("#fBtnVocal").css("color", "#000");
                    }else{
                        songFilter = value;
                        $("#fBtnVocal").css("background-color", "#303537");
                        $("#fBtnVocal").css("color", "#FFF");
                    }

                    $("#fBtnNoVocal").css("background-color", "buttonface");
                    $("#fBtnNoVocal").css("color", "#000");
                    break;
            }

            getSongs("");
        }
        
        window.onload = () => {
            getSongs("");

            let urlParams = window.location.search;
            urlParams = new URLSearchParams(urlParams);

            if(urlParams.has("id")){
                const paramId = urlParams.get("id");
                setId(paramId);
                console.log(paramId);
            }
            console.log(urlParams);

            socket.on('update', (data) => {
                if(data.message == "AddSong"){
                    getSongs("");
                }
            });
        }
    </script>
</html>
