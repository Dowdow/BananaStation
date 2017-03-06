var $grid = document.getElementById('grid');
var $music = document.getElementById('music');
var $musiccross = document.getElementById('music-cross');
var $previous = document.getElementById('previous');
var $next = document.getElementById('next');
var $playerdiv = document.getElementById('player-div');
var $playerclone = document.getElementById('player').cloneNode(true);
var $musictitle = document.getElementById('music-title');
var $more = document.getElementById('more');

var player;
var musics = [];
var current = 0;
var xhr = getXMLHttpRequest();

$musiccross.onclick = function (event) {
    event.preventDefault();
    $music.style.height = '0';
    setTimeout(function () {
        cleanPlayer();
    }, 200);
};

$previous.onclick = function (event) {
    event.preventDefault();
    previous();
};

$next.onclick = function (event) {
    event.preventDefault();
    next();
};

$more.onclick = function (event) {
    event.preventDefault();
    more();
};

function openPlayer(event) {
    if (event.target.tagName == 'IMG' || event.target.tagName == 'H3') {
        event.preventDefault();
        current = parseInt(event.target.parentNode.getAttribute('data'));
    } else if (event.target.tagName == 'A') {
        return;
    } else {
        event.preventDefault();
        current = parseInt(event.target.getAttribute('data'));
    }
    loadPlayer();
    $music.style.height = '100%';
    window.scrollTo(0, 0);
}

function previous() {
    if (current == 0) {
        current = musics.length - 1;
    } else {
        current--;
    }
    player.loadVideoById({videoId: musics[current].youtubeid});
}

function next() {
    if (current >= musics.length - 1) {
        current = 0;
    } else {
        current++;
    }
    player.loadVideoById({videoId: musics[current].youtubeid});
}

function cleanPlayer() {
    while ($playerdiv.firstChild) {
        $playerdiv.removeChild($playerdiv.firstChild);
    }
    $playerdiv.appendChild($playerclone);
}

function loadPlayer() {
    cleanPlayer();
    player = new YT.Player('player', {
        height: '315',
        width: '560',
        videoId: musics[current].youtubeid,
        playerVars: {
            'autoplay': 1,
            'iv_load_policy': 3
        },
        events: {
            'onReady': function (event) {
                event.target.playVideo();
            },
            'onStateChange': onPlayerStateChange
        }
    });
    $musictitle.innerHTML = musics[current].title;
}

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.ENDED) {
        next();
    }
}

function onYouTubeIframeAPIReady() {
}

function getXMLHttpRequest() {
    var xhr = null;
    if (window.XMLHttpRequest || window.ActiveXObject) {
        if (window.ActiveXObject) {
            try {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
        } else {
            xhr = new XMLHttpRequest();
        }
    } else {
        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
        return null;
    }
    return xhr;
}

function more() {
    NProgress.start();
    pages = pages.replace(/[0-9]+(?!.*[0-9])/, function (match) {
        return parseInt(match) + 1;
    });
    xhr.open('GET', pages, true);
    xhr.send(null);
}

xhr.onreadystatechange = function () {
    switch (xhr.readyState) {
        case 1:
            NProgress.set(0.25);
            break;
        case 2:
            NProgress.set(0.5);
            break;
        case 3:
            NProgress.set(0.75);
            break;
        case 4:
            if (xhr.status == 200 || xhr.status == 0) {
                NProgress.done();
                addMusics(JSON.parse(xhr.responseText));
            }
            break;
    }
};

function addMusics(json) {
    if (json.musics.length < 20) {
        $more.parentNode.removeChild($more);
    }

    json.musics.forEach(function (element) {
        var cindex = musics.push(element) - 1;
        var div = document.createElement('div');
        div.id = element.youtubeid;
        div.className = getColor(element.style);
        div.setAttribute('data', cindex);
        div.onclick = openPlayer;
        var img = document.createElement('img');
        img.src = "http://img.youtube.com/vi/" + element.youtubeid + "/0.jpg";
        img.alt = element.title;
        div.appendChild(img);
        var h3 = document.createElement('h3');
        h3.innerHTML = element.title;
        div.appendChild(h3);
        if (admin) {
            var aedit = document.createElement('a');
            aedit.href = element.edit;
            aedit.innerHTML = "Edit";
            var adelete = document.createElement('a');
            adelete.href = element.delete;
            adelete.innerHTML = "Delete";
            div.appendChild(aedit);
            div.appendChild(adelete);
        }
        $grid.appendChild(div);
    });
}

function getColor(style) {
    switch (style) {
        case 'G':
            style = 'red';
            break;
        case 'T':
            style = 'orange';
            break;
        case 'M':
            style = 'yellow';
            break;
        case 'E':
            style = 'blue';
            break;
        case 'D':
            style = 'green';
            break;
        case 'R':
            style = 'grey';
            break;
        case 'S':
            style = 'cyan';
    }
    return style;
}
