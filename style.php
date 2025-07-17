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

::-webkit-scrollbar {
    display: none;
}

body {
    height: 100dvh;
    width: 100dvw;
    /*background-color: #FEFFFE;*/
    overflow: hidden;
    background: linear-gradient(315deg, rgba(101,0,94,1) 3%, rgba(60,132,206,1) 38%, rgba(48,238,226,1) 68%, rgba(255,25,25,1) 98%);
    animation: gradient 120s ease infinite;
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

.loadingScreen {
    position: fixed;
    height: 100dvh;
    width: 100dvw;
    display: grid;
    z-index: 100;
    pointer-events: none;
}

.loadingScreen > span:nth-child(1) {
    position: absolute;
    top: 0; left: 0;
    background-color: hsl(280deg 100% 99%);
    height: 100%;
    width: 100%;
    opacity: 0;
    animation: loading_anim 6s linear;
}

.loadingScreen > span:nth-child(2){
    position: absolute;
    too: 0; left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.loadingScreen > span:nth-child(2) > span:nth-child(1){
    color: #A29BFE;
    opacity: 0;
    animation: loading_anim2 7s linear;
}

.loadingScreen > span:nth-child(2) > span:nth-child(2){
    height: 1rem;
    opacity: 0;
    width: 80%;
    margin-top: 0.5rem;
    border: 1px solid #DFE6E9;
    background-color: #DFE6E9;
    animation: loading_anim2 6s linear;
}

.loadingBar {
    height: 100%;
    width: 100%;
    background-color: #A29BFE;
    animation: loadingBar 5s ease-in-out;
}

.loadingScreen > span:nth-child(2) > span {
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

@keyframes loadingBar {
    from {
        width: 0%;
    }
    to {
        width: 100%;
    }
}

@property --songCount {
    syntax: "<integer>";
    initial-value: <?php include("./components/songCount.php") ?>;
    inherits: false;
}

.songCount {
    animation: counter 5s ease-in-out;
    counter-reset: num var(--songCount);
}

.songCount::after {
    content: counter(num);
}

@keyframes counter {
    from{
        --songCount: 0;
    }
    to{
        --songCount: <?php include("./components/songCount.php"); ?>;
    }
}

#navActivationArea {
    position: absolute;
    left: 0%; top: 0;
    width: 20%;
    height: 100%;
    z-index: 4;
}

nav {
    position: absolute;
    left: -20%; top: 0;
    height: 100%;
    width: 20%;
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

.isSearching {
    display: none;
}

.entriesFound {
    display: grid;
    place-items: center;
    width: 100%;
    font-size: 0.8rem;
    margin-bottom: 4%;
    border-radius: 15px;
}

.entriesFound > .error, .entriesFound > .ok {
    padding-block: 3%;
    padding-inline: 7%;
    border-radius: 15px;
}

.entriesFound > .error {
    background-color: #FFFF0020;
}

.entriesFound > .ok {
    background-color: #00FFFF20;
}

#songList {
    width: 100%;
    height: 100%;
    overflow-y: scroll;
}

#songList > span {
    display: block;
    padding: 1% 5% 1%;
}

#songList > span:hover {
    border-top: 1px solid #0FFF0F;
    border-bottom: 1px solid #0FFF0F;
}

#songList > span > p {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-size: 1.5rem;
}

#songList > span > p:nth-child(2){
    font-size: 1rem;
}

#songList > span:nth-child(odd){
    background-color: #FFFFFF20;
}

#songList > span:nth-child(even){
    background-color: #FFFFFF10;
}

.isvocal1 > p {
    color: #6C01D6;
}

.isvocal0 > p {
    color: #FFF;
}

#filters {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

#filters > span {
    padding: 0.5rem 1rem;
    border: 1px solid black;
    border-radius: 10px;
    margin: 0.5rem 0rem 0.5rem 0.5rem;
}

#filters > active {
    border: 1px solid green;
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

#mainActivationArea {
    position: absolute;
    right: 0; top: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
}

main > #videoPlayerWrapper {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    visibility: hidden;
}

#mainMessage {
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

#infoActivationArea {
    position: absolute;
    right: 0; bottom: 0;
    width: 86.7%;
    height: 20%;
    z-index: 4;
}

article {
    position: absolute;
    right: 0; bottom: -23%;
    width: 100%;
    height: 23%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    z-index: 5;
}

article > span:first-child > span:first-child {
    position: relative;
    left: -50dvw;
    display: flex;
    flex-direction: row;
    margin-left: 1rem;
    color: #FFF;
    font-weight: bold;
    text-transform: uppercase;
    transition: all 1s cubic-bezier(0.19, 1, 0.21, 1);
}

article > span:first-child > span:first-child > p {
    text-wrap: nowrap;
}

article > span:nth-child(2) {
    display: flex;
    height: 100%;
    flex-direction: column;
    padding: 0.2rem;
    color: #FFF;
    opacity: 0;
    justify-content: flex-end;
}

article > span:first-child > span, #debugInfo > div > span {
    display: flex;
    flex-direction: row;
}

#debugInfo > div {
    width: fit-content;
    position: absolute;
    right: 0;
}

#debugInfo {
    width: 50%;
}

#debugInfo:hover {
    opacity: 1;
}

#qrCode {
    display: block;
    height: 10dvh;
    width: 10dvh;
    border: 1px solid #FFF;
}

#skipBtn {
    margin-left: 1.5rem;
    padding-left: 1.5rem;
    border-left: 1px solid #FFF;
}

.currentQueue {
    position: relative;
}

.currentQueue > span {
    display: flex;
    flex-direction: column;
    margin: 0.5rem 1rem;
    border-radius: 1rem;
    width: fit-content;
    padding: 0.5rem 1rem;
    background-color: #00000050;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.21, 1);
}

.isvocalq0 {
    border: 1px solid #00CFFF;
}

.isvocalq1 {
    border: 1px solid #FFEE41;
}

.currentQueue > span > p {
    color: #FFF;
    font-weight: bold;
    font-size: 1.5rem;
}

.currentQueue > span > p:nth-child(2){
    font-size: 1rem;
    opacity: 0.8;
}

.currentQueue.active > span:first-child{
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

#notificationWrapper {
    position: absolute;
    bottom: 24%;
    left: calc(0% + 1rem);
    z-index: 99;
    width: fit-content;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.21, 1);
}

#notificationWrapper > .notification {
    position: relative;
    left: -100dvw;
    display: block;
    border: 2px solid #FFEE41;
    border-radius: 1rem;
    overflow: hidden;
    background-color: #00000050;
    animation: notificationAnimation 5.5s cubic-bezier(0.19, 1, 0.22, 1);
}

#notificationWrapper > .notification > #notifHeader {
    border-bottom: 1px solid #FFCF00;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: bold;
    color: #FFF;
}

#notificationWrapper > .notification > #notifTitle {
    padding-top: 0.5rem;
    padding-inline: 1rem;
    color: #FFF;
}

#notificationWrapper > .notification > #notifArtist {
    color: #FFF;
    font-size: 0.7rem;
    padding-bottom: 0.5rem;
    padding-inline: 1rem;
}

#notificationWrapper > .notification > #notifTimer {
    width: 0%;
    height: 0.5rem;
    display: block;
    background-color: #FFCF0080;
    animation: notificationTimer 5s linear;
}

@keyframes notificationAnimation {
    0%, 100%{
        left: -100dvw;
    }
    10%, 90% {
        left: 0dvw;
    }
}

@keyframes notificationTimer {
    from {
        width: 100%;
    }
    to {
        width: 0%;
    }
}

#skipBtn {
    margin-left: 3rem;
}

#skipBtn:hover {
    color: #FF2222;
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
    animation: animate 200s linear infinite;
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
    animation-delay: 16s;
    animation-duration: 96s;
}

.circles li:nth-child(3){
    left: 70%;
    width: 20px;
    height: 20px;
    animation-delay: 32s;
}

.circles li:nth-child(4){
    left: 40%;
    width: 60px;
    height: 60px;
    animation-delay: 0s;
    animation-duration: 144s;
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
    animation-delay: 24s;
}

.circles li:nth-child(7){
    left: 35%;
    width: 150px;
    height: 150px;
    animation-delay: 56s;
}

.circles li:nth-child(8){
    left: 50%;
    width: 25px;
    height: 25px;
    animation-delay: 120s;
    animation-duration: 360s;
}

.circles li:nth-child(9){
    left: 20%;
    width: 15px;
    height: 15px;
    animation-delay: 128s;
    animation-duration: 280s;
}

.circles li:nth-child(10){
    left: 85%;
    width: 150px;
    height: 150px;
    animation-delay: 0s;
    animation-duration: 88s;
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