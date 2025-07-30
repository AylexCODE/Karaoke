const socket = io('https://socketio-f317.onrender.com');
        
socket.on('connect', () => {
    $("#connectionStatus").html('connecting');

    function tryConnecting(){
        try{
            console.log("Trying to connect");
            connect();
        }catch(error){
            console.log("error", error);
            setTimeout(tryConnecting(), 1000);
        }
    }

    tryConnecting();
/*
    socket.emit('connectToConnection', randomId, (response) => {
        console.log(response.status);
    });*/
});

socket.on('disconnect', () => {
    $("#connectionStatus").html('disconnected');
});
