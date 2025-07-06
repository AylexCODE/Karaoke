<style type="text/css">
@font-face{
    font-family: space-grotesk-regular;
    url: ("./assets/fonts/SpaceGrotesk-Regular.otf");
    src: url("./assets/fonts/SpaceGrotesk-Regular.otf");
}
@font-face{
    font-family: space-grotesk-semibold;
    url: ("./assets/fonts/SpaceGrotesk-SemiBold.otf");
    src: url("./assets/fonts/SpaceGrotesk-SemiBold.otf");
}
* {
    margin: 0;
    padding: 0;
    font-family: space-grotesk-regular, monospace;
    font-size: 16px;
    box-sizing: border-box;
    user-select: none;
}

/*::-webkit-scrollbar {
    display: none;
}*/

body{
    height: 100dvh;
    width: 100dvw;
    /*background-color: #FEFFFE;*/
    overflow: hidden;
}

body {
    background: linear-gradient(315deg, rgba(101,0,94,1) 3%, rgba(60,132,206,1) 38%, rgba(48,238,226,1) 68%, rgba(255,25,25,1) 98%);
    animation: gradient 60s ease infinite;
    background-size: 400% 400%;
    background-attachment: fixed;
}

@keyframes gradient {
    0% {
        background-position: 0% 0%;
    }
    50% {
        background-position: 100% 100%;
    }
    100% {
        background-position: 0% 0%;
    }
}

.loading_screen {
    position: fixed;
    height: 100dvh;
    width: 100dvw;
    display: grid;
    z-index: 100;
    pointer-events: none;
}

.loading_screen > span:nth-child(1) {
    position: absolute;
    top: 0; left: 0;
    background-color: hsl(280deg 100% 99%);
    height: 100%;
    width: 100%;
    opacity: 0;
    animation: loading_anim 6s linear;
}

.loading_screen > span:nth-child(2){
    position: absolute;
    too: 0; left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.loading_screen > span:nth-child(2) > span:nth-child(1){
    color: #A29BFE;
    opacity: 0;
    animation: loading_anim2 7s linear;
}

.loading_screen > span:nth-child(2) > span:nth-child(2){
    height: 1rem;
    opacity: 0;
    width: 80%;
    margin-top: 0.5rem;
    border: 1px solid #DFE6E9;
    background-color: #DFE6E9;
    animation: loading_anim2 6s linear;
}

.loading_bar {
    height: 100%;
    width: 100%;
    background-color: #A29BFE;
    animation: loading_bar 5s ease-in-out;
}

.loading_screen > span:nth-child(2) > span {
    display: flex;
    flex-direction: row;
}

@keyframes loading_anim {
    0%, 80% {
        opacity: 1;
        background-color: hsl(280deg 100% 99%);
    }

    85% {
        background-color: hsl(285deg 100% 50%);
    }

    100% {
        opacity: 0;
    }
}

@keyframes loading_anim2 {
    0%, 85% {
        opacity: 1;
    }

    100% {
        opacity: 0;
    }
}

@keyframes loading_bar {
    from {
        width: 0%;
    }
    to {
        width: 100%;
    }
}

@property --song-count {
    syntax: "<integer>";
    initial-value: <?php include("./components/song_count.php") ?>;
    inherits: false;
}

.song_count {
    animation: counter 5s ease-in-out;
    counter-reset: num var(--song-count);
}

.song_count::after {
    content: counter(num);
}

@keyframes counter {
    from{
        --song-count: 0;
    }
    to{
        --song-count: <?php include("./components/song_count.php"); ?>;
    }
}

#nav_activation_area {
    position: absolute;
    left: 0%; top: 0;
    width: 13.3%;
    height: 100%;
    z-index: 4;
}

nav {
    position: absolute;
    left: -13.3%; top: 0;
    height: 100%;
    width: 13.3%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: left;
    padding-block: 0.5%;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    z-index: 5;
}

nav > span {
    display: grid;
    place-items: center;
    width: 100%;
    opacity: 0.7;
}

nav > span > span {
    display: block;
    width: 100%;
    text-align: center;
}

.is_searching {
    display: none;
}

.entries_found {
    display: grid;
    place-items: center;
    width: 100%;
    font-size: 0.8rem;
    margin-bottom: 4%;
    border-radius: 15px;
}

.entries_found > .error, .entries_found > .ok {
    padding-block: 3%;
    padding-inline: 7%;
    border-radius: 15px;
}

.entries_found > .error {
    background-color: #FFFF0020;
}

.entries_found > .ok {
    background-color: #00FFFF20;
}

#song_list {
    width: 100%;
    height: 80%;
    overflow-y: scroll;
}

#song_list > span {
    display: block;
    padding: 1% 5% 1%;
}

#song_list > span > p {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

#song_list > span > p:nth-child(2){
    font-size: .7rem;
}

#song_list > span:nth-child(odd){
    background-color: #FFFFFF20;
}

#song_list > span:nth-child(even){
    background-color: #FFFFFF10;
}

main {
    position: absolute;
    right: 0; top: 0;
    width: 100%;
    height: 100%;
    display: grid;
    place-items: center;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    z-index: 1;
}

main > .filler {
    position: fixed;
    top: 0;
    height: 100%;
    width: 6.6%;
    opacity: 0;
    transition: opacity 1s ease-out;
}

main > span:nth-child(2){
    left: 0;
}

main > span:nth-child(4){
    right: 0;
}

main > .filler.active {
    opacity: 1;
    transition: opacity 5s ease-in;
}

#main_activation_area {
    position: absolute;
    right: 0; top: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
}

main > #video_player_wrapper {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    visibility: hidden;
}

#main_message {
    position: fixed;
    top: calc((100dvh - 3rem)/2);
    display: block;
    font-size: 3rem;
    font-weight: bold;
    font-family: space-grotest-semibold, monospace;
    width: 86.7%;
    text-align: center;
    background: linear-gradient(45deg, rgb(250, 218, 97) 0%, rgb(255, 145, 136) 50%, rgb(255, 90, 205) 100%);
    animation: gradient 10s ease infinite;
    background-attachment: fixed;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

#info_activation_area {
    position: absolute;
    right: 0; bottom: 0;
    width: 86.7%;
    height: 20%;
    z-index: 4;
}

article {
    position: absolute;
    right: 0; bottom: -20%;
    width: 100%;
    height: 20%;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    z-index: 5;
}

article > p:first-child {
    position: relative;
    left: 0dvw;
    margin-left: 1rem;
    color: #FFF;
    font-weight: bold;
    text-transform: uppercase;
    transition: all 1s cubic-bezier(0.19, 1, 0.21, 1);
}

.current_queue {
    position: relative;
}

.current_queue > span {
    display: flex;
    flex-direction: column;
    margin: 0.5rem 1rem;
    border: 1px solid #00CFFF;
    border-radius: 1rem;
    width: fit-content;
    padding: 0.5rem 1rem;
    background-color: #00000050;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.21, 1);
}

.current_queue > span > p {
    color: #FFF;
}

.current_queue > span > p:nth-child(2){
    font-size: 0.7rem;
    opacity: 0.8;
}

.current_queue.active > span:first-child{
    animation: next_queue 1s cubic-bezier(0.19, 1, 0.21, 1);
}

@keyframes next_queue {
    from{
        background-color: #FFFFFF75;
        color: #000;
        border-color: #0F0;
    }
    to{
        background-color: #00000050;
        color: #FFF;
        border-color: #00CFFF;
    }
}

#notification_wrapper {
    position: absolute;
    bottom: 21%;
    left: calc(0% + 1rem);
    z-index: 99;
    width: fit-content;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.21, 1);
}

#notification_wrapper > .notification {
    position: relative;
    left: -50dvw;
    display: block;
    border: 2px solid #FFEE41;
    border-radius: 1rem;
    overflow: hidden;
    background-color: #00000050;
    animation: notification_animation 5.5s cubic-bezier(0.19, 1, 0.22, 1);
}

#notification_wrapper > .notification > #notif_header {
    border-bottom: 1px solid #FFCF00;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: bold;
    color: #FFF;
}

#notification_wrapper > .notification > #notif_title {
    padding-top: 0.5rem;
    padding-inline: 1rem;
    color: #FFF;
}

#notification_wrapper > .notification > #notif_artist {
    color: #FFF;
    font-size: 0.7rem;
    padding-bottom: 0.5rem;
    padding-inline: 1rem;
}

#notification_wrapper > .notification > #notif_timer {
    width: 0%;
    height: 0.5rem;
    display: block;
    background-color: #FFCF0080;
    animation: notification_timer 5s linear;
}

@keyframes notification_animation {
    0%, 100%{
        left: -50dvw;
    }
    10%, 90% {
        left: 0dvw;
    }
}

@keyframes notification_timer {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

.area{ 
    width: 100%;
    height:100%;
}

.circles{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.circles li{
    position: absolute;
    display: block;
    list-style: none;
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.2);
    animation: animate 90s linear infinite;
    bottom: -150px;
    
}

.circles li:nth-child(1){
    left: 25%;
    width: 80px;
    height: 80px;
    animation-delay: 0s;
}


.circles li:nth-child(2){
    left: 10%;
    width: 20px;
    height: 20px;
    animation-delay: 30s;
    animation-duration: 50s;
}

.circles li:nth-child(3){
    left: 70%;
    width: 20px;
    height: 20px;
    animation-delay: 60s;
}

.circles li:nth-child(4){
    left: 40%;
    width: 60px;
    height: 60px;
    animation-delay: 0s;
    animation-duration: 120s;
}

.circles li:nth-child(5){
    left: 65%;
    width: 20px;
    height: 20px;
    animation-delay: 0s;
}

.circles li:nth-child(6){
    left: 75%;
    width: 110px;
    height: 110px;
    animation-delay: 70s;
}

.circles li:nth-child(7){
    left: 35%;
    width: 150px;
    height: 150px;
    animation-delay: 40s;
}

.circles li:nth-child(8){
    left: 50%;
    width: 25px;
    height: 25px;
    animation-delay: 60s;
    animation-duration: 45s;
}

.circles li:nth-child(9){
    left: 20%;
    width: 15px;
    height: 15px;
    animation-delay: 90s;
    animation-duration: 90s;
}

.circles li:nth-child(10){
    left: 85%;
    width: 150px;
    height: 150px;
    animation-delay: 0s;
    animation-duration: 70s;
}

@keyframes animate {
    0%{
        transform: translateY(0) rotate(0deg);
        opacity: 1;
        border-radius: 0;
    }

    100%{
        transform: translateY(-1000px) rotate(720deg);
        opacity: 0;
        border-radius: 50%;
    }
}

@keyframes beat {
    0%, 50%, 100% {
        transform: scale(1, 1);
    }

    30%, 80% {
        transform: scale(0.92, 0.95);
    }
}
</style>