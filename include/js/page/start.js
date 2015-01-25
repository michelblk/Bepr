$(document).ready(function (){
    musicNews();
    musicSpotlight();
    
    function musicNews (){
        $("#music-news").html("");
        $.ajax({
            url: 'music/search.php?recent&limit=13',
            method: 'GET',
            success: function (data){
                if ($.isArray(data)){
                    $.each(data, function(i, song) {
                        $("#music-news").append("<div class='music-news-song' data-path=\""+song['path']+"\" data-cover=\""+song['cover']+"\" data-id=\""+song['id']+"\" data-title=\""+song['title']+"\" data-interpreter=\""+song['interpreter']+"\" data-album=\""+song['album']+"\">\n<div class='music-news-song-interpreter'>"+song['interpreter']+"</div>\n<div class='music-news-song-title'>"+song['title']+"</div>\n</div>");
                    });
                }else{
                    $("#music-news").html(data);
                }
            },
            error: function (e){
                $("#music-news").html("ERROR");
            }
        })
    }
    $("#music-news").on("click", ".music-news-song", function (){
        window.location.href = "music/?a=recent&songID="+btoa($(this).attr('data-id'));    
    });
    
    function musicSpotlight(){
        $("#music-spotlight").html("");
        $.ajax({
            url: 'music/spotlight.php?f=start&news',
            method: 'GET',
            success: function (data){
                $("#music-spotlight").css('background-image', 'url('+data['background']+')');
                $("#music-spotlight").css('background-color', data['backgroundcolor']);
                $("#music-spotlight").html(data['text']);
                $("#music-spotlight").attr('data-href', data['link']);
            },
            error: function(e){
                $("#music-spotlight").html("ERROR");
            }
        })
    }
    $("#music-spotlight").on("click", function (){
        window.location.href = $(this).attr('data-href');
    })
})