const socket = io('https://socketio-f317.onrender.com');
        
socket.on('connect', () => {
    $("#connectionStatus").html('connected');

    socket.emit('connectToConnection', randomId, (response) => {
        console.log(response.status);
    });
});

socket.on('disconnect', () => {
    $("#connectionStatus").html('disconnected');
});
