var queue = [];         // Set/Clear queue
var pastSongs = [];     // Past Songs
var nVideoSize = true;  // Is Musicvideo normal sized?
var content = [];       // Playlist content


/*--------------------------------------------------------------------
 * -----------------------------Events--------------------------------
 * -----------------------------------------------------------------*/
$(document).ready(function (){
    $( window ).resize(function() {
        resultMargin();
    });
    
    // Neue Titel seit Version drei ///////////////////////
    $.ajax({
        url: 'search.php?newsongssinceversionthree',
        method: 'GET',
        success: function (data) {
            $("#newsongssinceversionthree").html(data);
        }
    });


    /*----------- Search ----------------*/
    $('#wrapper').on('keypress', '#search-box', function (e) {
        if (e.which == 13) {
            search();
            return false;
        }
    });
    $('#wrapper').on('click', '#search-button', function (){
        search();    
    });
    
    /*------------ Click on a Song ---------------*/
    $("#wrapper").on("click", ".search-result-song", function (){
        if (queue.length > 0){
            //infoBox("Your queue has been overwritten", "SVG/ic_clear_all_24px_white.svg");
        } 
        queue.unshift({interpreter: $(this).closest('.search-result-song').children('.info').children(".search-result-song-interpreter").html(), title: $(this).closest('.search-result-song').children('.info').children(".search-result-song-title").html(), id: btoa($(this).closest('.search-result-song').attr('data-id'))});
        pastSongs.push({interpreter: $(this).closest('.search-result-song').children('.info').children(".search-result-song-interpreter").html(), title: $(this).closest('.search-result-song').children('.info').children(".search-result-song-title").html(), id: btoa($(this).closest('.search-result-song').attr('data-id'))});
        playSong($(this).closest('.search-result-song').children('.info').children(".search-result-song-interpreter").html(), $(this).closest('.search-result-song').children('.info').children(".search-result-song-title").html(), btoa($(this).closest('.search-result-song').attr('data-id'))); 
        normalVideoSize();
    }).on('click', ".addtoQueue", function (e) {
        e.stopPropagation();
        queue.push({interpreter: $(this).closest('.search-result-song').children('.info').children(".search-result-song-interpreter").html(), title: $(this).closest('.search-result-song').children('.info').children(".search-result-song-title").html(), id: btoa($(this).closest('.search-result-song').attr('data-id'))});
        infoBox ("\""+$(this).closest('.search-result-song').children('.info').children(".search-result-song-interpreter").html()+"\" - \""+$(this).closest('.search-result-song').children('.info').children(".search-result-song-title").html()+"\" has been added to your queue successfully", "SVG/ic_list_24px_white.svg");
    }).on('click', '.addtoPlaylist', function (e){
        e.stopPropagation();
        addtoPlaylist();    
    });
    
    /*---------Playlist------*/
    $("#wrapper").on("click", ".playlist", function (){
       playlist(btoa($(this).attr('data-id')), $(this).children(".playlist-name").html());
    });
    
    $("#mask").click(function (){
        if ($("#musicvideo").is(':visible')){
            if ($("#musicvideo").get(0).ended){
                $("#mask").hide();
                $("#musicvideo").hide();
                newURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+getParameters('songID').substr(0,getParameters('songID').length - 1), "Bepr Music");
            }else{
                minimizeVideo();
            }
        }
    });
    
    /*-----Playlist Music click------*/
    $("#wrapper").on("click", ".playlist-song-title", function (){
        len = content.length;
        idloop: for(var i = 0; i < len; i++){
            pastSongs.push({'interpeter': content[i]['interpreter'], 'title': content[i]['title'], 'id': content[i]['id']});
            if (content[i].id == $(this).parent().attr('data-id')){
                break idloop;    
            }
        }
        queue = content.slice(i, content.length);
        playSong($(this).parent().children('.playlist-song-interpreter').html(),$(this).parent().children('.playlist-song-title').html(), $(this).parent().attr('data-id'))
    });
    
    /*----------- Minimize Video -----------------*/
    // Save normal position; Reset
    var mh = $("#musicvideo").css('max-height');
    var mw = $("#musicvideo").css('max-width');
    var l = $("#musicvideo").css('left');
    var b = $("#musicvideo").css('bottom');
    var t = $("#musicvideo").css('top');
    function normalVideoSize () {
        nVideoSize = true;
        $("#musicvideo").css({
            'max-width': mw,
            'max-height': mh,
            'width': 'calc(100% - 100px)',
            left: l,
            bottom: b,
            top: t
        });
        $("body").css('overflow', 'hidden');
        resultMargin();
    }
    function minimizeVideo (){
        nVideoSize = false;
        $("#musicvideo").animate({
            'max-width': '200px',
            'max-height': '200px',
            left: '5px',
            bottom: '5px'
        }, 300).css({'top': 'auto'}); 
        $("body").css('overflow', 'auto');
        resultMargin();
    }

    
    $("#musicvideo").click(function (e){
        if (nVideoSize != true){
            normalVideoSize();
            $("#mask").show();
            $("#mask").animate({
                'opacity': 1
            }, 200);
            e.preventDefault();
            return false;
        }
    });
    $("#musicvideo").bind("contextmenu", function (e){
        e.preventDefault();
        addtoPlaylist(pastSongs[pastSongs.length-1]['interpreter'], pastSongs[pastSongs.length-1]['title'], pastSongs[pastSongs.length-1]['id']);    
    })
    
    $("#musicvideo").bind("ended", function() {
        if(queue.length > 0){
            pastSongs.push({'interpreter': queue[0]["interpreter"], 'title': queue[0]["title"], 'id': queue[0]["id"]});
            playSong(queue[0]["interpreter"],queue[0]["title"],queue[0]["id"]);
        }else{
            minimizeVideo();
            $("#musicvideo").hide();
            $(".page-title").fadeOut(1000);
            $("#mask").click();
        }
    });
    
});


$(window).bind('beforeunload', function() {
    if (!$("#musicvideo").get(0).paused){
      return 'This will stop the music.';
    }
});

$(window).scroll(function() {
   if(($(window).scrollTop() + $(window).height() > $(document).height() - 150) && $("#loadmore").is(":visible") && scroll == true) {
       scroll = false;
       recent();
   }
});

$(window).keydown(function (e){
    if (e.which == 39){ // right arrow
        if(queue.length > 0){
            pastSongs.push({'interpreter': queue[0]["interpreter"], 'title': queue[0]["title"], 'id': queue[0]["id"]});
            playSong(queue[0]["interpreter"],queue[0]["title"],queue[0]["id"]);
        }else{
            infoBox("There are no more queued tracks", "SVG/ic_clear_all_24px_white.svg");    
        }
        e.preventDefault();
        return false;
    }else
    if (e.which == 37){ // left arrow
        if (pastSongs.length > 0){
            queue.unshift({'interpreter': pastSongs[pastSongs.length-1]['interpreter'], 'title': pastSongs[pastSongs.length-1]['title'], 'id': pastSongs[pastSongs.length-1]['id']});
            pastSongs = pastSongs.slice(0, -1);
            /*if ($("#musicvideo")[0].currentTime < 5){
                queue.unshift({'interpreter': pastSongs[pastSongs.length-1]['interpreter'], 'title': pastSongs[pastSongs.length-1]['title'], 'id': pastSongs[pastSongs.length-1]['id']});
                pastSongs = pastSongs.slice(0, -1);
            } */
           playSong(queue[0]["interpreter"],queue[0]["title"],queue[0]["id"]);  
        }else{
            infoBox("There are no more past songs", "SVG/ic_clear_all_24px_white.svg");   
        }   
    }else
    if (e.which == 27){ // ESC
        if (nVideoSize == true){
            $("#mask").click();    
        }else
        if (nVideoSize != true && (!$("#musicvideo")[0].paused || $("#musicvideo")[0].stopped)){
            $("#musicvideo")[0].pause();
            $("#musicvideo").hide();
            $("#mask").hide();
            infoBox("Music stopped!", "SVG/ic_volume_off_24px_white.svg");    
        }
        e.preventDefault();
        return false;
    }else
    if (e.which == 32 && nVideoSize == true && $("#musicvideo").is(':visible')){ // space & video normal sized
        if ($("#musicvideo")[0].paused){
            $("#musicvideo")[0].play();    
        }else{
            $("#musicvideo")[0].pause();    
        }
        e.preventDefault();
        return false;
    }else
    if (e.which == 19){ // pause/break
        $("#musicvideo")[0].pause();
        $("#musicvideo").hide();
        $("#mask").hide();
        infoBox("Music stopped!", "SVG/ic_volume_off_24px_white.svg");        
    }/*else{
        alert(e.which);
    } */   
})


/*--------------------------------------------------------------------
 * ---------------------------Functions-------------------------------
 * -----------------------------------------------------------------*/
 

/*--------------------------Loading---------------------------------*/

function search (q){
    $(document).ready(function (){    
        if (!q){
            if (!$("#search-box").val()){
                return false;
            }
            q = $("#search-box").val();
        }else{
            $("#search-box").val(q);
        }

        $("#search-results").html("");
        loadingbarStart();
        $.ajax({
            url: 'search.php?q='+encodeURIComponent(q),
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar, false);
                return myXhr;
            },
            success: function (data){ console.log(data);
                    if (data['interpreter'].length != 0){
                        $("#search-results").append("<div id=\"search-results-interpreter\"><div class=\"search-results-type\">Interpreter ("+data['interpreter'].length+")</div></div>");
                        $.each(data['interpreter'], function (i, interpreter){
                            addInterpreterToSearchresults(interpreter);
                        });
                        $("#search-results-interpreter").append("<div class=\"clear\" style=\"clear: both\"></div>");    
                    }
                    if (data['album'].length != 0){
                        $("#search-results").append("<div id=\"search-results-albums\"><div class=\"search-results-type\">Albums ("+data['album'].length+")</div></div>");
                        $.each(data['album'], function (i, album){
                            addAlbumToSearchresults (album);
                        });
                        $("#search-results-albums").append("<div class=\"clear\" style=\"clear: both\"></div>"); 
                    }
                    if (data['song'].length != 0){
                        $("#search-results").append("<div id=\"search-results-songs\"><div class=\"search-results-type\">Songs ("+data['song'].length+")</div></div>");
                        $.each(data['song'], function (i, song){
                            addSongToSearchresults(song);
                        });
                        $("#search-results-songs").append("<div class=\"clear\" style=\"clear: both\"></div>"); 
                    }
                    //replaceURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+getParameters('q')+'q='+q, q+" | Bepr Music");
                    //resultMargin ();       
            },
            error: function (e){
                ajaxError(e.status);
            }
        })    
    })
}

function addSongToSearchresults (song) { 
    $("#search-results-songs").append("<div class='search-result-song' data-cover='"+song['cover']+"' data-id='"+song['id']+"' data-album=\""+song['album']+"\">\n<div class=\"info\"><div class='search-result-song-interpreter'>"+song['interpreter']+"</div>\n<div class='search-result-song-title'>"+song['title']+"</div></div>\n<div class='addtoPlaylist'></div>\n<div class='addtoQueue'></div>\n</div>");
    if (song['cover'] == 1){
        $("[data-id='"+song['id']+"']").css({'background-image': 'url("cover.php?songID='+btoa(song['id'])+'")'});
    }else{
        $("[data-id='"+song['id']+"']").css({'background-image': 'url("../../include/image/SVG/nocover.svg")'});
    }
}
function addInterpreterToSearchresults (interpreter){
    $("#search-results-interpreter").append("<div class='search-result-interpreter' data-cover='"+interpreter['cover']+"'>\n<div class=\"info\"><div class='search-result-interpreter'>"+interpreter['interpreter']+"</div></div>\n</div>");
}
function addAlbumToSearchresults (album){

}

var start = 0;
var limit = 50;
var scroll = true;
function recent(){
    //$(document).ready(function (){
        if (start == 0)$("#search-results").html("");
        loadingbarStart();
        $.ajax({
            url: 'search.php?recent&start='+start+'&limit='+limit,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar, false);
                return myXhr;
            },
            success: function (data){
                if ($.isArray(data)){
                    $.each(data, function(i, song) {
                        addSongToResults(song);    
                    });
                    start = parseInt(start) + parseInt(limit);
                    scroll = true;
                    resultMargin (); 
                }else{
                    if (data != ""){$("#search-results").html(data);}else{$("#loadmore").css('visibility', 'hidden');}
                }
            },
            error: function (e){
                ajaxError(e.status);
            }
        })
    //})
}

function addSongToResults (song){
    $("#search-results").append("<div class='search-result-song' data-cover='"+song['cover']+"' data-id='"+song['id']+"' data-album=\""+song['album']+"\">\n<div class=\"info\"><div class='search-result-song-interpreter'>"+song['interpreter']+"</div>\n<div class='search-result-song-title'>"+song['title']+"</div></div>\n<div class='addtoPlaylist'></div>\n<div class='addtoQueue'></div>\n</div>");                    
    if (song['cover'] == 1){
        $("[data-id='"+song['id']+"']").css({'background-image': 'url("cover.php?songID='+btoa(song['id'])+'")'});    
    }else{
        $("[data-id='"+song['id']+"']").css({'background-image': 'url("../../include/image/SVG/nocover.svg")'});    
    }    
}

function resultMargin (){
        $(".search-result-song").css({'margin-left': Math.floor(($("#search-results").width()-(Math.floor($("#search-results").width()/$(".search-result-song").width())*250))/(Math.floor($("#search-results").width()/$(".search-result-song").width()*2)))});
        $(".search-result-song").css({'margin-right': Math.floor(($("#search-results").width()-(Math.floor($("#search-results").width()/$(".search-result-song").width())*250))/(Math.floor($("#search-results").width()/$(".search-result-song").width()*2)))});
}                                                                                                           

function playlists(){
    $(document).ready(function (){
        $("#playlist").hide();
        $("#playlists").show().html('');
        loadingbarStart();
    })
    $.ajax({
            url: 'search.php?playlists',
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar, false);
                return myXhr;
            },
            success: function (data){
                if ($.isArray(data)){
                    $.each(data, function(i, playlist) {
                        $("#playlists").append("<div class='playlist' data-id='"+playlist['id']+"'><div class='playlist-cover'></div>\n<div class='playlist-name'>"+playlist['name']+"</div>\n<div class='playlist-tracks'>"+playlist['tracks']+" tracks</div><br />\n<div class='playlist-preview'>"+playlist['preview']+"</div>\n<div class='playlist-creator'>"+playlist['creator']+"</div>\n</div>");
                        if (playlist['cover'] == "1"){
                            $("#playlists").find(".playlist[data-id='" +playlist['id']+ "']").children(".playlist-cover").css({'background-image': 'url("cover.php?playlist&size=500&id='+playlist['id']+'")', 'background-size': 'cover'});
                        }
                    });
                }else{
                    // Do nothing
                }
            },
            error: function (e){
                ajaxError(e.status);
            }
    })    
}
function playlist(id, name){
    newURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+getParameters('playlistID')+'playlistID='+id, name+' | Bepr Music Playlist');
    $(document).ready(function (){
        $("#playlists").hide();
        $("#playlist").show().html('');
        loadingbarStart();
    }) 
     $.ajax({
            url: 'search.php?playlist='+id,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar, false);
                return myXhr;
            },
            success: function (data){
                $("#playlist").append("<div class='playlist-header'><div class='playlist-header-cover'></div><div class='playlist-header-title'>Title</div><div class='playlist-header-album'>Album</div><div class='playlist-header-interpreter'>Interpreter</div></div>");
                if ($.isArray(data)){
                    creator = data[0]['creator'];
                    playlistpublic = data[0]['public'];
                    preview = data[0]['preview'];
                    content = data[0]['content'];
                    $.each(content, function(i, song) {
                        $("#playlist").append("<div class='playlist-song' data-id='"+song['id']+"'><div class='playlist-song-cover'></div><div class='playlist-song-title'>"+song['title']+"</div><div class='playlist-song-album'>"+song['album']+"</div><div class='playlist-song-interpreter'>"+song['interpreter']+"</div></div>");
                    });
                }else{
                    $('#playlist').html('Playlist not found');
                }
            },
            error: function (e){
                ajaxError(e.status);
            }
    })    
}

/*-------------------------------Play-------------------------------*/
    
function playSong(interpreter, title, id){
    queue.shift();
    $(document).ready(function (){
        $("#musicvideo").fadeIn();
        $("#mask").show();
        $("#mask").animate({
            'opacity': 1
        }, 200);
        $("#interpreter.page-title").fadeOut(500, function (){
            $(this).html(interpreter).fadeIn(1000);    
        });
        $("#title.page-title").fadeOut(500, function (){
            $(this).html(title).fadeIn(1000);   
        });
        $("#subtitle").attr('src', 'video.php?subtitle&songID='+id);
        $("#musicvideo").attr('src', 'video.php?songID='+id);
        $("#musicvideo").attr('poster', 'cover.php?songID='+id+'&size=n');
        $("#musicvideo").get(0).play();
        newURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+getParameters('songID')+'songID='+id, unescape("%u25B6")+interpreter+" - "+title+" | Bepr Music");
    });
};

/*--------------------------Loading---------------------------------*/

function loadingbar (e){
    if(e.lengthComputable){
        if ($("#loading-bar").is(':hidden')){
            $("#loading-bar-status").css({'width': '0px'});
            $("#loading-bar").show();
        }
        $("#loading-bar-status").stop().animate({
            width: $(window).width()*(e.loaded/(e.total-100))
        }, 500);
        if (e.loaded == e.total){
            setTimeout(function (){
                $("#loading-bar").fadeOut(700);
            }, 1000);
        }
    }
}

function loadingbarStart (e){
    if ($("#loading-bar").is(':hidden')){
        $("#loading-bar-status").css({'width': '0px'});
        $("#loading-bar").show();
    }
    $("#loading-bar-status").stop().animate({
        width: '100px'
    }, 500);
}

/*-------------------------Error & Info-----------------------------*/

function ajaxError (statuscode){
    $("#error-code").html(statuscode);
    $("#error").slideDown();
    setTimeout(function(){
        $("#error").slideUp();
    }, 10000);
}

function infoBox (text, icon){
    $("#info-message").html(text);
    $("#info-image").attr('src', "../../include/image/"+icon);
    $("#info").stop().slideDown();
    setTimeout(function(){
        $("#info").slideUp();
    }, 2000);
}

/*--------------------------------URL-------------------------------*/

function replaceURL (url, title){
    history.replaceState({}, title, url);
    document.title = title;
    /* Songs are replacing the search-result page */
}
function newURL (url, title){
    history.pushState({}, title, url);
    document.title = title;
}

/*----------------------------replace-------------------------------*/

function goTo(musicpage){
    start = 0;
    $(".music-a").remove();
    $.ajax({
            url: 'index.php?a='+musicpage,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar, false);
                return myXhr;
            },
            success: function (data){
                $("#wrapper").append('<div data-a="'+musicpage+'" class="music-a"></div>');
                $('div[data-a='+musicpage+']').html($(data).find('div[data-a='+musicpage+']').html()).show();   
            },
            error: function (e){
                ajaxError(e.status);
            }
    })
    newURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+'a='+musicpage, musicpage.charAt(0).toUpperCase()+musicpage.slice(1)+" | bepr");
    $("#chapter-title").html(musicpage.charAt(0).toUpperCase()+musicpage.slice(1));
    $("#mask").click();
}

function addtoPlaylist(interpreter, title, id){
    infoBox("This function is not available, yet!", "SVG/ic_announcement_24px_white.svg");
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
function getParameters (without) {
  var query = window.location.search.substring(1);
  var pairs = query.split('&');
  var result = "";
  for (var i = 0; i < pairs.length; i++) {
    var pair = pairs[i].split('=');
    if(pair[0] != without) {
        result += pair[0]+"="+pair[1]+"&";
    }
  }
  return result;
  //return undefined;
};

function DoFullScreen() {

    var isInFullScreen = (document.fullScreenElement && document.fullScreenElement !== null) ||    // alternative standard method  
            (document.mozFullScreen || document.webkitIsFullScreen);

    var docElm = document.documentElement;
    if (!isInFullScreen) {

        if (docElm.requestFullscreen) {
            docElm.requestFullscreen();
        }
        else if (docElm.mozRequestFullScreen) {
            docElm.mozRequestFullScreen();
            console.log("Mozilla entering fullscreen!");
        }
        else if (docElm.webkitRequestFullScreen) {
            docElm.webkitRequestFullScreen();
            console.log("Webkit entering fullscreen!");
        }
    }
}   