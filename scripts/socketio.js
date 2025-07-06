const socket = io('https://socketio-f317.onrender.com');
        
socket.on('connect', () => {
    $("#current_queue").html('Realtime Update is Active');
});

socket.on('disconnect', () => {
    $("#current_queue").html('Realtime is not realtiming');
});
