document.getElementById('app').style.display = 'none';
document.getElementById('room').style.display = 'none';
document.getElementById('leave-room').style.display = 'none';

var rooms = {};
var me;
var image = document.getElementById('image-select').value;
var sound = document.getElementById('sound');
sound.volume = 0.3;

buildNotification({'title': 'Welcome to Banana Poke', 'message': '', 'icon': image});

socket.on('login', function (user) {
    me = user;
    document.getElementById('login').style.display = 'none';
    document.getElementById('room').style.display = 'block';
    document.getElementById('header-small').innerHTML = me.name;
});

socket.on('newroom', function (room) {
    document.getElementById('room-list').appendChild(buildRoom(room));
});

socket.on('newuser', function (obj) {
    if (typeof me.room !== 'undefined' && me.room.crypto === obj.room && me.id !== obj.user.id) {
        document.getElementById('users').appendChild(buildUser(obj.user));
    }
});

socket.on('joinroom', function (room) {
    me.room = room;
    document.getElementById('fast-poke-room').value = room.crypto;
    document.getElementById('poke-room').value = room.crypto;
    document.getElementById('room').style.display = 'none';
    document.getElementById('app').style.display = 'block';
    document.getElementById('leave-room').style.display = 'block';
});

socket.on('poke', function (poke) {
    if (poke.id == '') {
        if (poke.room == me.room.crypto) {
            buildNotification(poke);
        }
    } else {
        if (poke.id == me.id) {
            buildNotification(poke);
        }
    }
});

socket.on('quituser', function (obj) {
    if (typeof me.room != 'undefined' && me.room.crypto === obj.room) {
        var parent = document.getElementById('users');
        var child = document.getElementById(obj.user.id);
        parent.removeChild(child);
    }
});

socket.on('deleteroom', function (room) {
    if (typeof room != 'undefined') {
        var parent = document.getElementById('room-list');
        var child = document.getElementById(room);
        parent.removeChild(child);
    }
});

socket.on('error', function (message) {
    buildNotification({'title': 'An error happens', 'message': message})
});

document.getElementById('login-form').onsubmit = function (event) {
    login(event);
};

document.getElementById('login-button').onclick = function (event) {
    login(event);
};

function login(event) {
    event.preventDefault();
    var name = document.getElementById('login-name').value;
    if (name != '') {
        socket.emit('login', name);
    }
}

document.getElementById('room-create-form').onsubmit = function (event) {
    createRoom(event);
};

document.getElementById('room-create-button').onclick = function (event) {
    createRoom(event);
};

function createRoom(event) {
    event.preventDefault();
    var name = document.getElementById('room-name').value;
    if (name != '') {
        socket.emit('createroom', name);
    }
}

document.getElementById('leave-room').onclick = function (event) {
    event.preventDefault();
    socket.emit('quitroom', me.room.crypto);
    delete me.room;
    document.getElementById('room').style.display = 'block';
    document.getElementById('app').style.display = 'none';
    document.getElementById('leave-room').style.display = 'none';
    var users = document.getElementById('users');
    while (users.hasChildNodes()) {
        users.removeChild(users.lastChild);
    }
};

document.getElementById('volume').onchange = function () {
    sound.volume = document.getElementById('volume').value / 10;
};

document.getElementById('fast-poke-room').onclick = function (event) {
    event.preventDefault();
    var target = event.target || event.srcElement;
    var room = target.value;
    var poke = buildPoke('', room, me.name + ' pokes the room !', 'Room poke received');
    socket.emit('poke', poke);
};

document.getElementById('poke-room').onclick = function (event) {
    event.preventDefault();
    var target = event.target || event.srcElement;
    var room = target.value;
    var message = window.prompt('Enter your message here', '');
    var poke;
    if (message != null) {
        poke = buildPoke('', room, me.name + ' pokes the room !', message);
    } else {
        poke = buildPoke('', room, me.name + ' pokes the room !', 'Room poke received');
    }
    socket.emit('poke', poke);
};

document.getElementById('image-select').onchange = function () {
    image = document.getElementById('image-select').value;
};

document.getElementById('audio-select').onchange = function () {
    sound.src = document.getElementById('audio-select').value;
    sound.load();
};

function joinroom(event) {
    event.preventDefault();
    var target = event.target || event.srcElement;
    var id = target.id;
    socket.emit('joinroom', id);
}

function fastpoke(event) {
    event.preventDefault();
    var target = event.target || event.srcElement;
    var id = target.value;
    var poke = buildPoke(id, '', me.name + ' pokes you !', 'Poke received');
    socket.emit('poke', poke);
}

function poke(event) {
    event.preventDefault();
    var target = event.target || event.srcElement;
    var id = target.value;
    var message = window.prompt('Enter your message here', '');
    var poke;
    if (message != null) {
        poke = buildPoke(id, '', me.name + ' pokes you !', message);
    } else {
        poke = buildPoke(id, '', me.name + ' pokes you !', 'Poke received');
    }
    socket.emit('poke', poke);
}

function buildRoom(room) {
    var a = document.createElement('a');
    a.innerHTML = room.name;
    a.id = room.crypto;
    a.onclick = joinroom;
    return a;
}

function buildUser(user) {
    var div = document.createElement('div');
    div.id = user.id;
    var h4 = document.createElement('h4');
    h4.innerHTML = user.name;
    var buttondiv = document.createElement('div');
    var button1 = document.createElement('button');
    button1.innerHTML = 'Fast Poke';
    button1.value = user.id;
    button1.onclick = fastpoke;
    var button2 = document.createElement('button');
    button2.innerHTML = 'Poke';
    button2.value = user.id;
    button2.onclick = poke;
    div.appendChild(h4);
    div.appendChild(buttondiv);
    buttondiv.appendChild(button1);
    buttondiv.appendChild(button2);
    return div;
}

function buildPoke(id, room, title, message) {
    return {
        'id': id,
        'room': room,
        'title': title,
        'message': message,
        'icon': image
    };
}

function buildNotification(poke) {
    if (!('Notification' in window)) {
        alert('Desktop notifications are not supported :(');
    }
    else if (Notification.permission === 'granted') {
        var notification = new Notification(poke.title, {'body': poke.message, 'icon': poke.icon});
        playNotification();
        setTimeout(notification.close.bind(notification), 5000);
    }
    else if (Notification.permission !== 'denied') {
        Notification.requestPermission(function (permission) {
            if (!('permission' in Notification)) {
                Notification.permission = permission;
            }
            if (permission === 'granted') {
                var notification = new Notification(poke.title, {'body': poke.message, 'icon': poke.icon});
                playNotification();
                setTimeout(notification.close.bind(notification), 5000);
            }
        });
    }
}

function playNotification() {
    sound.pause();
    sound.currentTime = 0;
    sound.play();
}
