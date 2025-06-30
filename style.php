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
    background: linear-gradient(315deg, rgba(101,0,94,1) 3%, rgba(60,132,206,1) 38%, rgba(48,238,226,1) 68%, rgba(255,25,25,1) 98%);
    animation: gradient 15s ease infinite;
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
    place-items: center;
}

.loading_screen > span {
    display: flex;
    flex-direction: row;
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

nav {
    position: absolute;
    left: 0; top: 0;
    height: 100%;
    width: 13.2%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 5;
}

#search {

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

main {
    position: absolute;
    right: 0; top: 0;
    width: 87%;
    height: 100%;
}

main > iframe {
    height: 100%;
    width: 100%;
}

article {
    position: absolute;
    right: 0; bottom: 0;
    width: 86.8%;
    height: 20%;
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
    animation: animate 25s linear infinite;
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
    animation-delay: 2s;
    animation-duration: 12s;
}

.circles li:nth-child(3){
    left: 70%;
    width: 20px;
    height: 20px;
    animation-delay: 4s;
}

.circles li:nth-child(4){
    left: 40%;
    width: 60px;
    height: 60px;
    animation-delay: 0s;
    animation-duration: 18s;
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
    animation-delay: 3s;
}

.circles li:nth-child(7){
    left: 35%;
    width: 150px;
    height: 150px;
    animation-delay: 7s;
}

.circles li:nth-child(8){
    left: 50%;
    width: 25px;
    height: 25px;
    animation-delay: 15s;
    animation-duration: 45s;
}

.circles li:nth-child(9){
    left: 20%;
    width: 15px;
    height: 15px;
    animation-delay: 2s;
    animation-duration: 35s;
}

.circles li:nth-child(10){
    left: 85%;
    width: 150px;
    height: 150px;
    animation-delay: 0s;
    animation-duration: 11s;
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
</style>