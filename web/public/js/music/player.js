// Global
var $grid = $('#grid');

// Music
var use = 0;
var youtubeID;

/**
 * Function called when the Iframe API is loaded
 */
function onYouTubeIframeAPIReady() {
}

/**
 * Function called when the video state changed
 * @param event
 */
function onPlayerStateChange(event) {
    if(event.data == YT.PlayerState.ENDED) {
        nextMusic();
    }
}

/**
 * Edit the music content
 */
function editMusic() {
    var $music = $('#music');
    var player;
    $music.append('<div class="music-cross" onclick="hideMusic()">X</div>');
    $music.append('<div>' +
        '<img src="' + previous + '" alt="previous" onclick="previousMusic()">' +
        '<div id="player"></div>' +
        '<img src="' + next+ '" alt="next" onclick="nextMusic()">' +
        '</div>');
    $music.append('<h2>' + $('#'+youtubeID + " > div.item-info > h3").text().trim() + '</h2>');
    player = new YT.Player('player',{
        height: '315',
        width: '560',
        videoId: youtubeID,
        playerVars: {
            iv_load_policy: 3
        },
        events: {
            'onReady': function(event) {
                event.target.playVideo();
            },
            'onStateChange': onPlayerStateChange
        }
    });
}

/**
 * Show the selected music div
 * @param youtubeid
 */
function showMusic(youtubeid) {
    youtubeID = youtubeid;
    if(use == 0) {
        $grid.prepend('<div id="music" class="music" style="display: none"></div>');
        $('#music').toggle(300, editMusic());
        use = 1;
    } else {
        $('#music').children().remove();
        editMusic();
    }

    $('html, body').animate({
        scrollTop:$('body').offset().top
    }, 'slow');
}

/**
 * Hide and remove the current music div
 */
function hideMusic() {
    $('#music').hide(300, function(){
        $('#music').remove();
        use = 0;
    });
}

/**
 * Go to the previous music
 */
function previousMusic() {
    var previous = $('#' + youtubeID).prev().attr('id');
    if(previous == 'music') {
        previous = $('#' + youtubeID).prev().prev().attr('id');
    }
    console.log(previous);
    if(previous == null) {
        showMusic($('div.item').last().attr('id'));
    } else {
        showMusic(previous);
    }
}

/**
 * Go to the next music
 */
function nextMusic() {
    var next = $('#' + youtubeID).next().attr('id');
    if(next == null) {
        var page = $('#grid-page').length;
        if(page == 1) {
            more(1);
        } else {
            showMusic($('div.item').first().attr('id'));
        }
    } else {
        showMusic(next);
    }
}

// Scroll

/**
 * Scroll to the top of the page
 */
function up() {
    $('html, body').animate({
        scrollTop:$('body').offset().top
    }, 'slow');
}

/**
 * Scroll to the bottom of the page
 */
function down () {
    $('html, body').animate({
        scrollTop:$('#footer').offset().top
    }, 'slow');
}

//Search

/**
 * Show or hide the search input
 */
function showInputSearch() {
    $('#input-search').toggle(300);
}