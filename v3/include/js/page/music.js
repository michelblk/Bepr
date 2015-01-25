/*-----------------------------------
 *------------BEPR MUSIC-------------
 *---------------------------------*/


var queue = []; // Warteschlange
var queueP = -1; // Warteschlangenposition

var MusicStreamNoMargin = false;

var markedasplayed = false; // Datenbank (bei 25% als abgespielt markieren)


// Local Storage
$(document).ready(function (){
    if(typeof(Storage) !== "undefined") {
        if (localStorage.bg) { // Benutzerdefinierte Hintergrundfarbe
            $("body").css({"background-color": localStorage.bg});
        }
        if (localStorage.MusicStreamNoMargin) {
            MusicStreamNoMargin = localStorage.MusicStreamNoMargin;
        }
        if (!localStorage.MusicQueue || !localStorage.MusicQueueP) {
            localStorage.MusicQueue = JSON.stringify(queue);
            localStorage.MusicQueueP = JSON.stringify(queueP);
        }else{
            queue = JSON.parse(localStorage.MusicQueue);
            queueP = JSON.parse(localStorage.MusicQueueP);
        }
        $(window).on('beforeunload', function(){ // Warteschlange Speichern
            localStorage.MusicQueue = JSON.stringify(queue);
            localStorage.MusicQueueP = JSON.stringify(queueP);
        });
    }
    if (queue[queueP] != "" && typeof(queue[queueP]) != "undefined" ) {
        play(queue[queueP]);
        $("#musicplayer-video")[0].pause();
        $("#musicplayer-seek-bar").val(0);
        $("#musicplayer-seek-bar-shadow").css({'width': 0+'px'});
    }
});


//////Holen und darstellen////////
loadmorescrolling = true;
$(document).ready(function (){
    $(window).scroll(function() {
        if ($("#loadmore").length) {
            if(($(window).scrollTop() + $(window).height() > $(document).height() - 250) && $("#loadmore").is(":visible") && loadmorescrolling == true) {
                $("#loadmore").click();
                loadmorescrolling = false;
                $("#loadmore").html('Lädt...');
            }else {
                if (loadmorescrolling == true) $("#loadmore").html('Mehr laden');
            }
        }
    });
});

var start = 0;
var limit = 50;
var getSongs = new(function(){
    this.all = function (){
        loadmorescrolling = false;
        $.ajax({
            url: 'search.php?a=all&start='+start+'&limit='+limit,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){
                if (start == 0) {
                    $("#allSongs").html("");
                }
                if ($.isArray(data)){
                    $.each(data, function(i, song) {
                        getSongs.addSong("allSongs", song);
                    });
                    start = parseInt(start) + parseInt(limit);
                    loadmorescrolling = true;
                }else{
                    if (data != ""){$("#allSongs").html(data);}else{$("#loadmore").css('visibility', 'hidden');}
                }
            },
            error: function (e){
                error.ajax(e.status);
            },
            complete: function (){
                loadingbar.stop();
                MusicStreamMargin();
            }
        });
    }
    this.neww = function (){
        loadmorescrolling = false;
        $.ajax({
            url: 'search.php?a=neww&start='+start+'&limit='+limit,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){
                if (start == 0) {
                    $("#newwSongs").html("");
                }
                if ($.isArray(data)){
                    $.each(data, function(i, song) {
                        getSongs.addSong("newwSongs", song);
                    });
                    start = parseInt(start) + parseInt(limit);
                    loadmorescrolling = true;
                }else{
                    if (data != ""){$("#newwSongs").html(data);}else{$("#loadmore").css('visibility', 'hidden');}
                }
            },
            error: function (e){
                error.ajax(e.status);
            },
            complete: function (){
                loadingbar.stop();
                MusicStreamMargin();
            }
        });
    }
    this.addSong = function (id, data){
        $("#"+id).append("<div data-title=\""+data['title']+"\" data-album=\""+data['album']+"\" data-interpreter=\""+data['interpreter']+"\" data-id=\""+data['id']+"\" data-videoavailable=\""+data['videoavailable']+"\" class=\"MusicCoverStream\" style=\"background-image: url("+data['cover']+");\"><div class=\"MusicCoverStreamInfo\"><div class=\"MusicCoverStreamInfotext\" data-info=\"interpreter\">"+data['interpreter']+"</div><div class=\"MusicCoverStreamInfotext\" data-info=\"title\">"+data['title']+"</div><div class=\"MusicCoverStreamAddToPlaylist\"></div><div class=\"MusicCoverStreamVideoAvailable\" data-videoavailable=\""+data['videoavailable']+"\"></div><div class=\"MusicCoverStreamAddToQueue\"></div></div></div>");
    }
    this.info = function (id){
        return $.ajax({
            url: 'data.php?getInfo&songID='+id,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){

            },
            error: function (e){
                if (e.status == 401) {
                    location.reload();
                }else{
                    error.ajax(e.status);
                }
            },
            complete: function (){
                loadingbar.stop();
            }
        });
    }
    this.search = function(q) {
        $.ajax({
            url: 'search.php?a=search&q='+q,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){
                $("#search-loading").hide();
                var datainterpreter = data["interpreter"];
                if (datainterpreter.length > 0) {
                    /*$.each(datainterpreter, function(i, interpreter) {
                        $("#search-interpreter").append("<div class=\"SearchCoverStream\" onclick=\"gotoInterpreter('"+interpreter['interpreter']+"');\" style=\"background-image: url('../../include/image/svg/interpreter.svg');\"><div class=\"SearchCoverStreamInfo\"><div class=\"SearchCoverStreamInfotext\" data-info=\"interpreter\">"+interpreter['interpreter']+"</div></div></div>");
                    });*/
                    $.each(datainterpreter, function(i, data) {
                        $("#search-interpreter").append("<div data-title=\""+data['title']+"\" data-album=\""+data['album']+"\" data-interpreter=\""+data['interpreter']+"\" data-id=\""+data['id']+"\" data-videoavailable=\""+data['videoavailable']+"\" class=\"MusicCoverStream SearchCoverStream\" style=\"background-image: url("+data['cover']+");\"><div class=\"MusicCoverStreamInfo  SearchCoverStreamInfo\"><div class=\"MusicCoverStreamInfotext  SearchCoverStreamInfotext\" data-info=\"interpreter\">"+data['interpreter']+"</div><div class=\"MusicCoverStreamInfotext  SearchCoverStreamInfotext\" data-info=\"title\">"+data['title']+"</div><div class=\"MusicCoverStreamVideoAvailable  SearchCoverStreamVideoAvailable\" data-videoavailable=\""+data['videoavailable']+"\"></div><div class=\"MusicCoverStreamAddToPlaylist SearchCoverStreamAddToPlaylist\"></div><div class=\"MusicCoverStreamAddToQueue SearchCoverStreamAddToQueue\"></div></div></div>");
                    });
                }else {
                    $("#search-interpreter").hide();
                }
                var dataalbum = data["album"];
                if (dataalbum.length > 0) {
                    $.each(dataalbum, function(i, album) {
                        $("#search-album").append("<div class=\"SearchCoverStream\" onclick=\"gotoAlbum('"+album['album']+"', '"+album['interpreter']+"');\" style=\"background-image: url('"+album['cover']+"');\"><div class=\"SearchCoverStreamInfo\"><div class=\"SearchCoverStreamInfotext\" data-info=\"interpreter\">"+album['interpreter']+"</div><div class=\"SearchCoverStreamInfotext\" data-info=\"album\">"+album['album']+"</div></div></div>");
                    });
                }else{
                    $("#search-album").hide();
                }
                var datasong = data["song"];
                if (datasong.length > 0) {
                    $.each(datasong, function(i, data) {
                        $("#search-song").append("<div data-title=\""+data['title']+"\" data-album=\""+data['album']+"\" data-interpreter=\""+data['interpreter']+"\" data-id=\""+data['id']+"\" data-videoavailable=\""+data['videoavailable']+"\" class=\"MusicCoverStream SearchCoverStream\" style=\"background-image: url("+data['cover']+");\"><div class=\"MusicCoverStreamInfo  SearchCoverStreamInfo\"><div class=\"MusicCoverStreamInfotext  SearchCoverStreamInfotext\" data-info=\"interpreter\">"+data['interpreter']+"</div><div class=\"MusicCoverStreamInfotext  SearchCoverStreamInfotext\" data-info=\"title\">"+data['title']+"</div><div class=\"MusicCoverStreamVideoAvailable  SearchCoverStreamVideoAvailable\" data-videoavailable=\""+data['videoavailable']+"\"></div><div class=\"MusicCoverStreamAddToPlaylist SearchCoverStreamAddToPlaylist\"></div><div class=\"MusicCoverStreamAddToQueue SearchCoverStreamAddToQueue\"></div></div></div>");
                    });
                }else{
                    $("#search-song").hide();
                }
            },
            error: function (e){
                error.ajax(e.status);
            },
            complete: function (){
                loadingbar.stop();
                MusicStreamMargin();

                if(getParameter("re") == "search" && !$('#search-interpreter').is(':visible') && !$('#search-album').is(':visible') && !$('#search-song').is(':visible')){
                    location.href='https://google.de/search?q='+q;
                }
                if(getParameter("re") == "search"){
                    $("#search-interpreter, #search-song, #search-album").append("<a href='https://google.de/search?q="+q+"' style='color: grey; text-decoration: none;'>Auf Google suchen</a>");
                }
            }
        });
    }
});

var getPlaylist = new(function (){
    this.Playlists = function () {
        $.ajax({
            url: 'search.php?a=playlists',
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){
                $("#playlist-loading").hide();
                var dataown = data["own"];
                $.each(dataown, function(i, playlist) {
                    $("#playlists-own").append("<div class=\"PlaylistCoverStream\" onclick=\"gotoplaylist('"+playlist['id']+"');\" style=\"background-image: url('"+playlist['cover']+"');\" data-id=\""+playlist['id']+"\"><div class=\"PlaylistCoverStreamInfo\"><div class=\"PlaylistCoverStreamInfotext\" data-info=\"creator\">"+playlist['creatorname']+"</div><div class=\"PlaylistCoverStreamInfotext\" data-info=\"name\">"+playlist['name']+"</div></div></div>");
                });
                var datapublic = data["public"];
                $.each(datapublic, function(i, playlist) {
                    $("#playlists-public").append("<div class=\"PlaylistCoverStream\" onclick=\"gotoplaylist('"+playlist['id']+"');\" style=\"background-image: url('"+playlist['cover']+"');\" data-id=\""+playlist['id']+"\"><div class=\"PlaylistCoverStreamInfo\"><div class=\"PlaylistCoverStreamInfotext\" data-info=\"creator\">"+playlist['creatorname']+"</div><div class=\"PlaylistCoverStreamInfotext\" data-info=\"name\">"+playlist['name']+"</div></div></div>");
                });
            },
            error: function (e){
                error.ajax(e.status);
            },
            complete: function (){
                loadingbar.stop();
            }
        });
    }
    this.Playlist = function (id) {
        $.ajax({
            url: 'search.php?a=playlist&playlistID='+id,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){
                $("#playlist-loading").hide();
                $("#playlist-name").html(data['name']);
                $("#playlist-cover").css({'background-image': 'url("'+data['cover']+'")'});
                $("#playlist-creator").html(data['creatorname']).attr('data-userid', data['creatorid']);
                $("#playlist-tracks").html(data['tracks']);
                $("#playlist-public").attr('data-public', data['public']);
                $("#playlist-description").html(data['description']);
                $("#playlist").attr({'data-id': id});
                if (data['description']){
                    $("#playlist-description").attr('data-description', '1');
                }
                $("#js-playlist-content").append("<tr><th>#</th><th></th><th>Titel</th><th>Album</th><th>Interpreter</th><th>Optionen</th></tr>");
                content = data['content'];
                $.each(content, function(i, song) {
                    if (song.album == "") {
                        song.album = "<span style=\"font-style: italic;\">Single</span>"
                    }
                    $("#js-playlist-content").append("<tr data-id=\""+song['id']+"\" class=\"playlistEntry\"><td class=\"playlistEntryNum\">"+(i+1)+"</td><td><div class='playlistEntryCover' style=\"background-image: url('"+song['cover']+"');\"></div></td><td>"+song['title']+"</td><td>"+song['album']+"</td><td>"+song['interpreter']+"</td><td class='playlistSongOptions'><a class='playlistSongDelete playlistSongOptionsIcon'></a></td></tr>\n");
                });
            },
            error: function (e){
                error.ajax(e.status);
            },
            complete: function (){
                loadingbar.stop();
            }
        });
    }

    this.PlaylistPopup = function (songID){
        $("div[data-persistent='no']").append("<div class='addtoPlaylists-Popup' data-songID='"+songID+"' data-loaded='false'><div class='popup-content'><div class='popup-loading'></div></div></div>");
        $.ajax({
            url: 'search.php?a=PlaylistsPopup&songID='+songID,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
            },
            success: function (data){
                $(".addtoPlaylists-Popup[data-songID='"+songID+"']").attr('data-loaded', 'true');
                $(".addtoPlaylists-Popup[data-songID='"+songID+"'] .popup-content").html("<div class='playlists-popup-header'><span class='playlists-popup-songtitle'>\""+data['songtitle']+"\"</span> hinzufügen zu:</div><div class='playlists-popup-content'></div>");
                var playlists = data['playlists'];
                $.each(playlists, function (i, playlist){
                    if (playlist['alreadyin'] == true) {
                        var checked = "checked";
                    }else{
                        var checked = "";
                    }
                    $(".addtoPlaylists-Popup[data-songID='"+songID+"'] .playlists-popup-content").append("<div class='playlists-popup-playlists'><div class='playlists-popup-checkbox'><label><input type='checkbox' name='"+playlist['id']+"' value='true' "+checked+"/><span></span></label></div><div class='playlists-popup-name' data-public='"+playlist["public"]+"'>"+playlist['name']+"</div></div>");
                });
                $(".addtoPlaylists-Popup[data-songID='"+songID+"'] .popup-content").append("<input type='button' value='Abbrechen' onclick=\"$(document).ready(function(){$('.addtoPlaylists-Popup').remove();});\" class='playlists-popup-button playlists-popup-abort' /><input type='button' value='Speichern' onclick=\"getPlaylist.savePlaylistPopupchanges('"+songID+"');\" class='playlists-popup-button playlists-popup-save' />");
            },
            error: function (e) {
                error.playlist(e.status);
            },
            complete: function (){
                loadingbar.stop();
            }
        });
    }
    this.savePlaylistPopupchanges = function (songID) {
        $(".addtoPlaylists-Popup[data-songID='"+songID+"']").attr('data-loaded', 'false');
        var data = $(".addtoPlaylists-Popup[data-songID='"+songID+"'] .playlists-popup-playlists input[type='checkbox']");
        $(".addtoPlaylists-Popup[data-songID='"+songID+"'] .popup-content").html("<div class='popup-loading'></div>");
        var playlists = [];
        $(data).each(function (i, playlist){
            playlists.push([$(playlist).attr('name'), $(playlist).is(":checked")]);
        });
        getPlaylist.addtoPlaylists(playlists, songID);
    }
    this.addtoPlaylists = function (playlistIDs, songID){ //multiple playlists allowed
        $.ajax({
            url: 'data.php?addtoPlaylists&songID='+songID+"&multiple",
            method: 'POST',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            beforeSend: function (){
                loadingbar.connecting();
                if (!$(".addtoPlaylists-Popup").is(":visible")) {
                    console.log("Nicht erlaubt!");
                    return false;
                }
            },
            data: {"playlists": playlistIDs, "songID": songID, "user": "click"},
            success: function (data){
                $(".addtoPlaylists-Popup").remove();
            },
            error: function (e){
                error.playlist(e.status);
            },
            complete: function (){
                loadingbar.stop();
            }
        });
    }
});


var loadingbar = new(function (){
    this.connecting = function (){
        $(document).ready(function (){
            $("#loadingbar-status").css({'width': '0px'});
            if ($("#loadingbar").is(':hidden')){
                $("#loadingbar").fadeIn(50);
            }
            $("#loadingbar-status").animate({
                width: '100px'
            }, 50);
        })
    }
    this.update = function (e){
        if(e.lengthComputable){
            if ($("#loadingbar").is(':hidden')){
                $("#loadingbar-status").css({'width': '0px'});
                $("#loadingbar").show();
            }
            $("#loadingbar-status").stop().animate({
                width: $("#loadingbar").width()*(e.loaded/(e.total-100))
            }, 500);
        }
    }
    this.stop = function (){
        $("#loadingbar-status").stop().css({'width': $("#loadingbar").width()});
        setTimeout(function (){
            $("#loadingbar").stop().fadeOut(300);
        }, 300);
        $("#loading").hide();
    }
});

var error = new (function (){
    this.ajax = function (e){
        if (e == 0) {
            $("#musicplayer-current-interpreter").html("<span style='color: red'>Keine Internetverbindung!</span>");
            $("#musicplayer-current-album").html("<span style='color: red'>Bitte laden Sie die Seite neu!</span>");
            $("#musicplayer-current-title").html ("");
        }else if (e == 440){
            $("#musicplayer-current-interpreter").html("<span style='color: red'>Pfad nicht gefunden!</span>");
            $("#musicplayer-current-album").html("<span style='color: red'>Bitte warten Sie eine Minute</span>");
            $("#musicplayer-current-title").html ("<span style='color: red'>und laden Sie danach die Seite neu!</span>");
        }else {
            alert("AJAX Fehler " + e);
        }
    }
    this.playlist = function (e){
        error.ajax(e);
    }
});

$(window).bind('beforeunload', function() {
    if (!$("#musicplayer-video")[0].paused){
      return 'Die Musik würde gestoppt';
    }
});

function MusicStreamMargin (){
    if (MusicStreamNoMargin){
        MusicStreamMargin2();
    }else{
        var margin = Math.floor(($(".MusicCoverStream").parent().width()-(Math.floor($(".MusicCoverStream").parent().width()/$(".MusicCoverStream").width())*$(".MusicCoverStream").width()))/(Math.floor($(".MusicCoverStream").parent().width()/$(".MusicCoverStream").width()*2)));
        $(".MusicCoverStream").css({'margin-left': margin, 'margin-right': margin});
    }
}
function MusicStreamMargin2 (){
    var count = Math.floor(($(".MusicCoverStream").parent().width()/250));
    var size = $(".MusicCoverStream").parent().width()/count;
    $(".MusicCoverStream").css({'width' : size, 'height': size});
    $(".MusicCoverStream").css({'margin': 0, 'box-shadow': 'none'});
}

// Videocanvas an Fenster anpassen
function musicvideoresize () {
    if (document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen) {
        musicvideoresizeM (0);
    }else{
        musicvideoresizeM (90);
    }

}
function musicvideoresizeM (marginbottom) {
    if ($("#musicplayer-video")[0].videoHeight == 0 || $("#musicplayer-video")[0].videoWidth == 0){
        $("#musicvideo").css({'height': '0px', 'width': '0px', 'top': '0px', 'bottom': 'auto', left: '0px'});
        $("#musicvideo").attr({'width': '0px', 'height': '0px'});
    }else{
      if ($("#musicplayer-video")[0].videoHeight/$("#musicplayer-video")[0].videoWidth > ($(window).height()-marginbottom)/$(window).width()) {
          canvaswidth = $("#musicplayer-video")[0].videoWidth / ($("#musicplayer-video")[0].videoHeight / ($(window).height()-marginbottom));
          canvasheight = $("#musicplayer-video")[0].videoHeight / ($("#musicplayer-video")[0].videoWidth / canvaswidth);
          $("#musicplayer-video").stop().animate({'height': canvasheight+'px', 'width': canvaswidth+'px', 'top': '0px', 'left': ($(window).width() - canvaswidth)/2+'px', 'bottom': 'auto'},500);
      }else {
          canvasheight = $("#musicplayer-video")[0].videoHeight / ($("#musicplayer-video")[0].videoWidth / $(window).width());
          canvaswidth = $(window).width();
          $("#musicplayer-video").stop().animate({'height': canvasheight+'px', 'width': canvaswidth+'px', 'top': ($(window).height()-marginbottom - canvasheight)/2+'px', 'bottom': ($(window).height()-marginbottom - $("#musicplayer-video").height())/2+'px', left: '0px'},500);
      }
      if ($("#musicplayer-video").attr('width') != $("#musicplayer-video")[0].videoWidth || $("#musicplayer-video").attr('height') != $("#musicplayer-video")[0].videoHeight) {
          $("#musicplayer-video").attr({'width': $("#musicplayer-video")[0].videoWidth+'px', 'height': $("#musicplayer-video")[0].videoHeight+'px'});
      }
    }
}

//////Abspielen////////

var showvideo = false;
var autoplay = true; //CropVideo - turn off when moved manually
var videoCrop;

$(document).ready(function (){
    // Auf CoverStream klicken
    $("#wrapper").on("click", ".MusicCoverStream", function (){
        var id = $(this).attr('data-id');
        queueP++;
        queue.splice(queueP, 0, id);
        play(id);
    }).on('click', ".MusicCoverStreamAddToQueue", function (e) {
        e.stopPropagation();
        var id = $(this).parent().parent().attr('data-id');
        queue[queue.length] = id;
    }).on('click', ".MusicCoverStreamAddToPlaylist", function (e) {
        e.stopPropagation();
        var id = $(this).parent().parent().attr('data-id');
        getPlaylist.PlaylistPopup(id);
    });
    // Play/Pause Button
    $("#wrapper").on("click", "#musicplayer-play-pause", function (){
        if ($("#musicplayer-video")[0].paused == true) {
          // Video abspielen
          $("#musicplayer-video")[0].play();
        } else {
          // Video Pausieren
          $("#musicplayer-video")[0].pause();
        }
    });
    // Stumm Button
    $("#wrapper").on("click", "#musicplayer-mute", function (){
        if ($("#musicplayer-video")[0].muted == true) {
          // Video Sound aktivieren
          $("#musicplayer-video")[0].muted = false;
          // Stumm Knopf aktualisieren
          $("#musicplayer-mute").attr('data-show', 'mute');
        } else {
          // Video stumm schalten
          $("#musicplayer-video")[0].muted = true;
          // Stumm Knopf aktualisieren
          $("#musicplayer-mute").attr('data-show', 'sound');
        }
    });
    $("#wrapper").on("click", "#musicplayer-videoCrop", function (){
        if ($("#musicplayer-videoCrop").attr('data-mode') == "0" || autoplay == false){
             $("#musicplayer-videoCrop").attr('data-mode', 1);
             autoplay = true;
             $("#musicplayer-videoCrop").removeAttr('data-auto');
        }else{
            $("#musicplayer-videoCrop").attr('data-mode', 0);
        }
    });

    // Zeitleiste
    var updateSeekBar = true;
    $("#musicplayer-video").bind("timeupdate", function() {
        if (updateSeekBar == true) {
            $("#musicplayer-seek-bar")[0].value = (100 / $("#musicplayer-video")[0].duration) * $("#musicplayer-video")[0].currentTime;
            $("#musicplayer-seek-bar-shadow").css({'width': ($("#musicplayer-progress-bar").width()/$("#musicplayer-video")[0].duration) * $("#musicplayer-video")[0].currentTime+'px'});
        }

        if (autoplay == true && videoCrop != "" && $("#musicplayer-videoCrop").attr("data-mode") == "1") {
            $.each(videoCrop, function (index, value){
                if (typeof(videoCrop[index][1]) == "undefined" || videoCrop[index][1] == "") {
                    videoCrop[index][1] = $("#musicplayer-video")[0].duration;
                }
                if (parseFloat(videoCrop[index][0]) <= parseFloat($("#musicplayer-video")[0].currentTime) && parseFloat(videoCrop[index][1]) >= parseFloat($("#musicplayer-video")[0].currentTime)){
                    return false;
                }
                if (parseFloat(videoCrop[index][0]) >= $("#musicplayer-video")[0].currentTime) {
                    $("#musicplayer-video")[0].currentTime = parseFloat(videoCrop[index][0]); //Zu Zeit springen
                    return false;
                }
                if (parseFloat(videoCrop[index][1]) <= $("#musicplayer-video")[0].currentTime && index+1 == videoCrop.length) { //Ende erreicht
                    $("#musicplayer-video")[0].pause();
                    playNext();
                }
            });
        }

        if ($("#musicplayer-video")[0].currentTime > $("#musicplayer-video")[0].duration*0.25 && $("#musicplayer-video")[0].duration > 0 &&  markedasplayed == false) {
            // 25% des Videos wurden abgespielt
            markedasplayed = true;

            $.ajax({
                url: 'data.php?markasplayed&songID='+queue[queueP],
                method: 'POST',
                success: function (data){

                },
                data: {'songID': queue[queueP], 'position': ($("#musicplayer-video")[0].currentTime/$("#musicplayer-video")[0].duration)},
                error: function (e){
                    error.ajax(e.status);
                }
            })
        }

        $("#musicplayer-current-time span[data-class='now'] span[data-class='minute']").html(("0" + parseInt(parseInt($("#musicplayer-video")[0].currentTime) / 60) % 60).slice(-2));
        $("#musicplayer-current-time span[data-class='now'] span[data-class='second']").html(("0" + parseInt($("#musicplayer-video")[0].currentTime) % 60).slice(-2));
        $("#musicplayer-current-time span[data-class='total'] span[data-class='minute']").html(("0" + parseInt(parseInt($("#musicplayer-video")[0].duration) / 60) % 60).slice(-2));
        $("#musicplayer-current-time span[data-class='total'] span[data-class='second']").html(("0" + parseInt($("#musicplayer-video")[0].duration) % 60).slice(-2));
    });
    $("#musicplayer-seek-bar").bind("mousedown", function() {
        updateSeekBar = false;
    }).bind("mouseup", function (){
        updateSeekBar = true;
    });
    $("#musicplayer-seek-bar").bind("change", function () {
        $("#musicplayer-video")[0].currentTime = $("#musicplayer-video")[0].duration * ($("#musicplayer-seek-bar")[0].value / 100);
        if($("#musicplayer-videoCrop").attr("data-mode")!="0"){
            autoplay = false;
            $("#musicplayer-videoCrop").attr("data-auto", "off");
        }
        //$("#musicplayer-video")[0].play();
    });
    $("#musicplayer-video").on("progress", function () {
        if ($("#musicplayer-video")[0].buffered.length >= 1) {
            $("#musicplayer-seek-bar-buffered").css({'left': ($("#musicplayer-progress-bar").width()/$("#musicplayer-video")[0].duration)*$("#musicplayer-video")[0].buffered.start($("#musicplayer-video")[0].buffered.length-1)+'px','width': ($("#musicplayer-progress-bar").width()/$("#musicplayer-video")[0].duration) * ($("#musicplayer-video")[0].buffered.end($("#musicplayer-video")[0].buffered.length-1)-$("#musicplayer-video")[0].buffered.start($("#musicplayer-video")[0].buffered.length-1))+'px'});
        }
    });

    ////////VIDEO////////
    var loadedmetadata = false;
    var resizedVideo = false;
    var canvaswidth;
    var canvasheight;

    $("#musicplayer-video").bind("loadedmetadata", function () {
        loadedmetadata = true;
    });


    /////Events/////////

    // Video zuende
    $("#musicplayer-video").bind("ended", function () {
        if(queue.length > 0 && queue[queueP+1] != "undefined"){
            playNext();
        }
    });

    // Video pausiert
    $("#musicplayer-video").bind("pause", function () {
        $("#musicplayer-play-pause").attr('data-show', 'play');
    });

    // Video fortgesetzt/gestartet
    $("#musicplayer-video").bind("play", function () {
        $("#musicplayer-play-pause").attr('data-show', 'pause');
    });

    // Video hat neue Größe Bekommen (mp4/mp3)
    $("#musicplayer-video").bind("resize", function () {
        $("#musicplayer-video").bind("loadedmetadata", function () {
            resizedVideo = false;
            if (showvideo == true) {
                musicvideoresize();
                resizedVideo = true;
            }
        });
    });


    // Mauszeiger nach 2 Sekunden ausblenden
    var mousetimer;
    $("#wrapper").on("mousemove", "#musicvideo-container", function () {
        clearTimeout(mousetimer);
        mousetimer = 0;
        $("#musicvideo-container").css({
            'cursor': 'auto'
        });

        mousetimer = setTimeout(function () {
            $("#musicvideo-container").css({
                'cursor': 'none'
            });
        }, 2000);
    });

    // Video zeigen
    $("#wrapper").on("click", "#musicplayer-cover", function() {
        if (showvideo == true) {
            $("#musicvideo-container").hide();
            $("#shield").hide();
            showvideo = false;
            $("body").css({
                'overflow': 'auto'
            });
            MusicStreamMargin();
        }else{
            $("#musicvideo-container").show();
            $("#shield").show();
            showvideo = true;
            $("body").css({
                'overflow': 'hidden'
            });
            musicvideoresize();
        }
    });
	// Rechtsclick auf Cover -> Zu Playlist hinzufügen
	$("#wrapper").on("contextmenu", "#musicplayer-cover", function (e) { //Rechtsklick auf Musikvideo-Cover
        getPlaylist.PlaylistPopup(queue[queueP]);
        return false;
    });
    // Auf shield geklickt -> Video ausblenden
    $("#wrapper").on("click", "#shield", function() { //Shield ausblenden
        $("#musicplayer-cover").click();
    });

    $("#wrapper").on("dblclick", "#musicvideo-container", function (e) { //Doppelklick auf Musikvideo-Canvas
        MusicVideoFullscreen();
    });

    $("#wrapper").on("contextmenu", "#musicvideo-container", function (e) { //Rechtsklick auf Musikvideo-Canvas
        getPlaylist.PlaylistPopup(queue[queueP]);
        return false;
    });

    // Fenstergröße ändert sich
    $( window ).resize(function() {
        MusicStreamMargin();
        if (showvideo == true) {
            musicvideoresize()
        }
    });


    // Tastendruck events
    $(document).keydown(function(e) {
        if ($(e.target).is('#MusicSearchInput') && e.which == 13) { // Enter
            if ($("#MusicSearchInput").val().length < 3) {
                return false;
            }
            gotosubpage("search", "Suchen | Bepr Musik");
            getSongs.search($("#MusicSearchInput").val());
            e.preventDefault();
        }
        if ($(e.target).is('input, textarea')) {
            return;
        }
        if (e.which == 39){ // Pfeiltaste rechts
            playNext();
            e.preventDefault();
        }else
        if (e.which == 37){ // Pfeiltaste links
            playPrevious();
            e.preventDefault();
        }else
        if (e.which == 27){ // Esc
            if ($("#shield").is(':visible')){
                $("#shield").click();
            }
        }else
        if (e.which == 32){ // Leertaste
            if ($("#musicplayer-video")[0].paused){
                $("#musicplayer-video")[0].play();
            }else{
                $("#musicplayer-video")[0].pause();
            }
            e.preventDefault();
        }else
        if (e.which == 70) { // f
            MusicVideoFullscreen();
        }
    });
});

function play (songID) {
    if (queue.length == 0){
        queue[queue.length] = songID;
        queueP++;
    }
    $("#musicplayer-left").show();
    $("#musicplayer-progress-bar").show();
    $("#musicplayer").fadeIn(200);
    var data = getSongs.info(songID);
    videoCrop = "";
    autoplay = true;
    $("#musicplayer-videoCrop").removeAttr("data-auto");
    data.success(function (data){
        $("#musicplayer-current-interpreter").html(data.interpreter);
        if (data.album == "") {
            data.album = "<span style=\"font-style: italic;\">Single</span>"
        }
        $("#musicplayer-current-album").html(data.album);
        $("#musicplayer-current-title").html(data.title);
        $("#musicplayer").attr('data-videoavailable', data.videoavailable);

        if (data.videoCrop != "" && $("#musicplayer-videoCrop").attr('data-mode') == "1"){
            $("#musicplayer-video")[0].currentTime = parseFloat($.parseJSON(data.videoCrop)[0][0]);
            videoCrop = $.parseJSON(data.videoCrop);
        }

        newMusicHistoryEntry(songID, data.interpreter + " - " + data.title + " | Bepr Musik");
    });
    $("#musicplayer-cover").css("background-image", "url(data.php?cover&songID="+songID+")");
    loadedmetadata = false;
    $("#musicplayer-video").attr("src", "video.php?songID="+songID);
    $("#musicplayer-video")[0].play();
    markedasplayed = false; //Datenbank
}

function newMusicHistoryEntry (songID, title) {
	history.pushState({}, title, window.location.protocol+"//"+window.location.host+window.location.pathname+'?a=listen&songID='+songID);
    document.title = title;
}

function playNext () {
    if(queue.length > 0 && typeof(queue[queueP+1]) != "undefined"){
        queueP++;
        play(queue[queueP]);
    }
}
function playPrevious () {
    if(queue.length > 0 && typeof(queue[queueP-1]) != "undefined"){
        queueP--;
        play(queue[queueP]);
    }
}

////////////Playlists///////////
$(document).ready(function (){
    $("#wrapper").on("click", ".playlistEntry", function (e){
        if($(e.target).is($(".playlistSongOptions")) || $(e.target).parents(".playlistSongOptions").length){return;} //Nicht beim Klick auf einer der Optionen
        queueP++;
        queue.splice(queueP, 0, $(this).attr('data-id'));
        play($(this).attr('data-id'));
        queue = queue.slice(0,queueP+1); //Bevorstehende Warteschlange löschen
        var entrynum = parseInt($(this).children(".playlistEntryNum").html());
        var i = 1;
        $(this).siblings().each(function() {
            if (parseInt($(this).children(".playlistEntryNum").html()) > entrynum){
                queue.splice(parseInt(queueP+i), 0, $(this).attr('data-id'));
                i++;
            }
        });
    });
    $("#wrapper").on("click", ".playlistSongDelete", function (e){
        var targetSongID = $(this).parents(".playlistEntry").attr('data-id');
        var PositionInPlaylist = $(this).parents(".playlistEntry").children($(".playlistEntryNum")).html();
        var playlistID = $(this).parents("#playlist").attr('data-id');

        $.ajax({
            url: 'data.php?removeFromPlaylist&songID='+targetSongID+"&m=single&playlistID="+playlistID,
            method: "POST",
            data: {"songID": targetSongID, "positionInPlaylist": PositionInPlaylist, "playlistID": playlistID},
            success: function (){
                $(".playlistEntry[data-id='"+targetSongID+"']").remove();
                var i = 1;
                $(".playlistEntry").each(function() { //Neu Nummerieren
                    $(this).children(".playlistEntryNum").html(i);
                    i++;
                });
            },
            error: function (e){
                error.ajax(e.status);
            }
        });
    });
});

function gotoplaylist (id) {
    $("div[data-persistent=no]").remove();
    $.ajax({
            url: 'index.php?a=playlist&playlistID='+id,
            method: 'GET',
            success: function (data){
                $("#wrapper").append('<div data-persistent="no" data-a="playlist" data-playlistID="'+id+'"></div>');
                $('div[data-a=playlist]').html($(data).find('div[data-a=playlist]').html());
            },
            error: function (e){
                alert(e.status);
            }
    });

    newURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+'a=playlist&playlistID='+id, "Playlist | Bepr Musik");
}

function gotoAlbum (album, interpreter) {
    alert("Noch nicht verfügbar :/");
}
function gotoInterpreter (interpreter) {
    alert("Noch nicht verfügbar :/");
}
function MusicVideoFullscreen () {
    if (document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen) {
        if(document.exitFullscreen) {
            document.exitFullscreen();
        } else if(document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if(document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
        return false;
    }
    if (!$("#musicvideo-container").is(":visible")) {
        $("#musicplayer-cover").click();
    }
    var element = document.getElementById("musicvideo-container");
    if (element.requestFullScreen) {
        element.requestFullScreen();
    } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullScreen) {
        element.webkitRequestFullScreen();
    }
}

/* NO COPYRIGHT */
function getParameter (key) {
  var query = window.location.search.substring(1);
  var pairs = query.split('&');
  for (var i = 0; i < pairs.length; i++) {
    var pair = pairs[i].split('=');
    if(pair[0] == key) {
      if(pair[1].length > 0)
        return pair[1];
    }
  }
  //return undefined;
};
