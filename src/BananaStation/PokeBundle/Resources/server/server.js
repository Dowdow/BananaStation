var md5 = require("blueimp-md5");
var io = require('socket.io')(process.env.PORT || 3000);

var rooms = {};

io.sockets.on('connection', function (socket) {

    var user = {};
    var currentroom = '';

    for (var r in rooms) {
        if (rooms.hasOwnProperty(r)) {
            socket.emit('newroom', rooms[r]);
        }
    }

    socket.on('login', function (name) {
        user.id = md5(name + Date.now() + socket.request.connection.remoteAddress);
        user.name = name;
        socket.emit('login', user);
    });

    socket.on('createroom', function (name) {
        var crypto = md5(name + Date.now());
        rooms[crypto] = {
            'name': name,
            'crypto': crypto,
            'users': {}
        };
        rooms[crypto].users[user.id] = user;
        currentroom = crypto;
        io.sockets.emit('newroom', {'name': name, 'crypto': crypto});
        socket.emit('joinroom', {'name': name, 'crypto': crypto});
    });

    socket.on('joinroom', function (room) {
        if (typeof rooms[room] != undefined) {
            rooms[room].users[user.id] = user;
            currentroom = room;
            socket.emit('joinroom', {'name': rooms[room].name, 'crypto': rooms[room].crypto});
            // On informe les utilisateurs déjà présents dans la salle qu'une personne entre
            io.sockets.emit('newuser', {'user': user, 'room': room});

            // On communique à l'utilisateur les occupants actuels
            for (var u in rooms[room].users) {
                if (rooms[room].users.hasOwnProperty(u)) {
                    socket.emit('newuser', {'user': rooms[room].users[u], 'room': room});
                }
            }
        } else {
            socket.emit('error', 'This room does not exist');
        }
    });

    socket.on('quitroom', function (room) {
        if (typeof rooms[room] != undefined) {
            delete rooms[room].users[user.id];
            currentroom = '';
            io.sockets.emit('quituser', {'user': user, 'room': room});
            if (Object.keys(rooms[room].users).length < 1) {
                io.sockets.emit('deleteroom', room);
                delete rooms[room];
            }
        } else {
            socket.emit('error', 'This room does not exist');
        }
    });

    socket.on('poke', function (poke) {
        io.sockets.emit('poke', poke);
    });

    socket.on('disconnect', function () {
        if (currentroom !== '') {
            delete rooms[currentroom].users[user.id];
            io.sockets.emit('quituser', {'user': user, 'room': currentroom});
            if (Object.keys(rooms[currentroom].users).length < 1) {
                io.sockets.emit('deleteroom', currentroom);
                delete rooms[currentroom];
            }
        }
    });
});