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
    background-color: #FEFFFE;
    overflow-y: scroll;
    scroll-behavior: smooth;
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
</style>