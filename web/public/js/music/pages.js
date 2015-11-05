// Global
var $grid = $('#grid');

// More
var page = 2;
var request = getXMLHttpRequest();
var state = 0;

/**
 * Get more music from AJAX
 */
function more(arg) {
    state = arg;
    NProgress.start();
    request.open('GET', '/app_dev.php/music/page/' + encodeURIComponent(style.toLowerCase()) + '/' + page++, true);
    request.send(null);
}

/**
 * XHR callback
 */
request.onreadystatechange = function(){
    switch(request.readyState) {
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
            if (request.status == 200 || request.status == 0) {
                NProgress.done();
                addMusics(JSON.parse(request.responseText));
            }
            break;
    }
};

/**
 * Add the new music in the DOM
 * @param json
 */
function addMusics(json) {
    if(json.musics.length < 20) {
        $('#grid-page').remove();
    }
    json.musics.forEach(function(element, index, array){
        $grid.append('<div id="' + element.youtubeid + '" class="item" onclick="showMusic(\'' + element.youtubeid + '\')" style="background: ' + getColorRandom(element.style) + ';">' +
            '<img src="http://img.youtube.com/vi/' + element.youtubeid +'/0.jpg" alt="'+ element.title +'" />' +
            '<div class="item-info">' +
            '<h3>' + element.title + '</h3>'+
            '</div>'+
            (admin ? '<div class="item-admin">'+
                '<a href="/app_dev.php/admin/edit/' + element.id + '">Edit</a>'+
                '<a href="/app_dev.php/admin/delete/' + element.id + '">Delete</a>'+
                '</div>': '') +
            '</div>');
    });

    if(state == 1) {
        showMusic($('#' + youtubeID).next().attr('id'));
    }
}

/**
 * Create and return a XmlHttpRequest
 * @returns {*}
 */
function getXMLHttpRequest() {
    var xhr = null;
    if (window.XMLHttpRequest || window.ActiveXObject) {
        if (window.ActiveXObject) {
            try {
                xhr = new ActiveXObject("Msxml2.XMLHTTP");
            } catch(e) {
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

/**
 * Return the normal color
 * @param style
 * @returns {string}
 */
function getColorNormal(style) {
    return getColor(style) + ',1)';
}

/**
 * Return a random color for music by style
 * @param style
 * @returns {string}
 */
function getColorRandom(style) {
    return getColor(style) + ',' + ((Math.floor(Math.random() * (10 - 7 + 1)) + 7) / 10) + ')';
}

/**
 * Return a random color for music by style
 * @param style
 * @returns {string}
 */
function getColor(style) {
    var color = 'rgba(';
    switch(style) {
        case 'G':
            color += '255,65,54';
            break;
        case 'H':
            color += '255,133,27';
            break;
        case 'M':
            color += '255,220,0';
            break;
        case 'E':
            color += '0,116,217';
            break;
        case 'D':
            color += '46,204,64';
            break;
        case 'R':
            color += '170,170,170';
            break;
    }
    return color;
}