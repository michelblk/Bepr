var loadingbar = new(function (){
    this.connecting = function (){
        $(document).ready(function (){
            $("#loadingbar-status").css({'width': '0px', 'background': 'rgb(248, 143, 4)'});
            if ($("#loadingbar").is(':hidden')){
                $("#loadingbar").show();
            }
            $("#loadingbar-status").animate({
                width: '100px'
            }, 100);    
        })
    }
    this.update = function (e){
        if(e.lengthComputable){
        if ($("#loadingbar").is(':hidden')){
            $("#loadingbar-status").css({'width': '0px', 'background': 'rgba(0,0,0,0.6)'});
            $("#loadingbar").show();
        }
        $("#loadingbar-status").stop().animate({
            width: $("#loadingbar").width()*(e.loaded/(e.total-100))
        }, 500);
    }
    }
    this.stop = function (){
        $("#loadingbar-status").css({'background': 'rgba(0,0,0,0.6)'});
        setTimeout(function (){
            $("#loadingbar").stop().fadeOut(700);
        }, 1000);
        $("#loading").hide();
    }
});

var start = 0;
var limit = 50;
var grabmusic = new(function(){
    this.orderBy = function (orderby, order){
        loadingbar.connecting();
        if (start == 0)$("#allSongs").html("");
        $.ajax({
            url: 'search.php?orderBy='+orderby+'&start='+start+'&limit='+limit+'&order='+order,
            method: 'GET',
            xhr: function() {  // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                myXhr.addEventListener('progress',loadingbar.update, false);
                return myXhr;
            },
            success: function (data){
                if ($.isArray(data)){
                    $.each(data, function(i, song) {
                        grabmusic.addSong("allSongs", song);
                    });
                    start = parseInt(start) + parseInt(limit);
                }else{
                    if (data != ""){$("#allSongs").html(data);}else{$("#loadmore").css('visibility', 'hidden');}
                }
            },
            error: function (e){
                error.ajax(e.status);
            },
            complete: function (){
                loadingbar.stop();
            }
        })   
    }
    this.addSong = function (id, data){
        $("#"+id).append(data['interpreter']);    
    }                       
});
 
var musicplayer = new (function(){
    this.play = function (id){
            
    }
    this.nextSong = function(){
        
    }
    this.addtoQueue = function(id){
        
        // ...
        
        // if(typeof(Storage) !== "undefined") {
        //    localStorage.setItem("MusicQueue", "Smith");
        // }
    }
});