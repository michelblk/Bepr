$(document).ready(function (){
    
    $('#search-box').keypress(function (e) {
        if (e.which == 13) {
            search();
            return false;
        }
    });
    $("#search-button").click(function (){
        search();    
    });
    
    $("#search-results").on("click", ".search-result-video", function (){
        playSong($(this).attr('data-path'), $(this).attr('data-title')); 
    });
    
    $("#mask").click(function (){
        if ($("#video").is(':visible')){
            $("#video").stop();
            $("#video").attr('src', '');
            $("#mask").hide();
            $("#video").hide();
        }
    })
    
    
});

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
        $.ajax({
            url: 'search.php?s='+q,
            method: 'GET',
            success: function (data){
                if ($.isArray(data)){
                    $.each(data, function(i, video) {
                        $("#search-results").append("<div class='search-result-video' data-path=\""+video['path']+"\" data-cover='"+video['cover']+"' data-id='"+video['id']+"' data-title=\""+video['title']+"\" >\n<div class='search-result-video-title'>"+video['title']+"</div>\n<div class='search-result-video-year'>"+video['year']+"</div>\n</div>");
                    });
                }else{
                    $("#search-results").html(data);
                }
            },
            error: function (e){
                alert("ERROR");
            }
        })    
    })
}

var start = 0;
var limit = 50;
function recent(){
    $(document).ready(function (){
        if (start == 0)$("#search-results").html("");
        $.ajax({
            url: 'search.php?recent&start='+start+'&limit='+limit,
            method: 'GET',
            success: function (data){
                if ($.isArray(data)){
                    $.each(data, function(i, video) {
                        $("#search-results").append("<div class='search-result-video' data-path=\""+video['path']+"\" data-cover='"+video['cover']+"' data-id='"+video['id']+"' data-title=\""+video['title']+"\" >\n<div class='search-result-video-title'>"+video['title']+"</div>\n<div class='search-result-video-year'>"+video['year']+"</div>\n</div>");
                    });
                    start = parseInt(start) + parseInt(limit); 
                }else{
                    if (data != ""){$("#search-results").html(data);}else{$("#loadmore").fadeOut();}
                }
            },
            error: function (e){
                alert("ERROR");
            }
        })
    })
}
    
function playSong(path, title){
    $(document).ready(function (){
        $("#video").attr('src', '');
        $("#video").fadeIn();
        $("#mask").show();
        $("#mask").animate({
            'opacity': 1
        }, 200);
        $("#video").attr('src', "http://"+window.location.hostname+":80/files/bepr/video/"+path);
        $("#video").get(0).play();
    });
};


$(window).bind('beforeunload', function() {
    if (!$("#video").get(0).paused){
      return 'Das Video wuerde beendet werden!';        
    }
});