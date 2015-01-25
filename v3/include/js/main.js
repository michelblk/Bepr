$(document).ready(function (){
    $(".submenu a").last().css({"border-bottom-width": "0px"});

   $("#menu-trigger-icon").on("click", function (){
       $("#menu-wrapper").css({'width': '340px'});
   });
   $("#menu-wrapper").on("mouseover", function (){
       $("#menu-wrapper").css({'width': '340px'});
   }).on("mouseleave", function (){
       $("#menu-wrapper").css({'width': '60px'});
   });
   $("#menu-trigger").on("mouseover", function (){
        $("#menu-wrapper").css({
            "webkit-transform": "translateX(0px)",
            "-moz-transform": "translateX(0px)",
            "transform": "translateX(0px)"
        });
   }).on("mouseleave", function (){
       $("#menu-wrapper").css({
            "webkit-transform": "translateX(-60px)",
            "-moz-transform": "translateX(-60px)",
            "transform": "translateX(-60px)"
        });
        $("#menu-wrapper").css({'width': '60px'});
        $(".submenu").slideUp(100);
   });
   $("#menu-trigger-icon").on("mouseover", function (){
        $(".submenu").hide();
   });
   $(".menu-icon[data-submenuid]").on("click", function (){
        var thissubmenu = $(this);
        if (!$("#"+thissubmenu.attr('data-submenuid')).is(':visible')){
            $("#"+thissubmenu.attr('data-submenuid')).slideDown(200);    
        }else{
            $("#"+thissubmenu.attr('data-submenuid')).slideUp(100);    
        }
   })
});


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

/////////////// Zu anderer Subseite wechseln //////////////////////
function gotosubpage (subpage, title) {
    $(document).ready(function (){
        $("div[data-persistent=no]").remove();
        $.ajax({
                url: 'index.php?a='+subpage,
                method: 'GET',
                success: function (data){
                    $("#wrapper").append('<div data-persistent="no" data-a="'+subpage+'"></div>');
                    $('div[data-a='+subpage+']').css({opacity: 0}).html($(data).find('div[data-a='+subpage+']').html()); 
                },
                complete: function (){
                    $('body').stop().animate({scrollTop:$('#wrapper').position().top}, '500');
                    $('div[data-a='+subpage+']').stop().animate({opacity: 1}, 500);    
                },
                error: function (e){
                    alert(e.status);
                }
        });
        
        newURL(window.location.protocol+"//"+window.location.host+window.location.pathname+'?'+'a='+subpage, title);
    });
}