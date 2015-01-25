var circle = parseInt($("#loading").attr('r'));
$("#loading").attr('stroke-dasharray', Math.PI*circle*2);
$("#loadingS").attr('stroke-dasharray', Math.PI*circle*2);

var presentationData = [];
var page = 1;

$(document).ready(function (){
    loadPresentation(getParameter('id'));   
});

function loadPresentation(id){
    $.ajax({
        url: 'get.php?a=presentation&id='+id,
        method: 'GET',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            myXhr.addEventListener('progress',loading, false);
            return myXhr;
        },
        success: function (data){
            $("#presentation").html('');
            if ($.isArray(data)){
                var pages = data[1].length;
                $("#presentation").css('background-color', data[0].backgroundcolor);
                $("#presentation").css('background-image', data[0].backgroundimage);
                $("#presentation").css('font-family', data[0].fontfamily);
                $("#presentation").css('font-size', data[0].fontsize);
                $("#presentation").css('font-weight', data[0].fontweight);
                $("#presentation").css('color', data[0].color);
                $("#presentation").css('width', data[0].width);
                $("#presentation").css('height', data[0].height);
                $("#presentation").css('position', "absolute");
                $("#presentation").css('left', "50%");
                $("#presentation").css('margin-left', parseInt(data[0].width)/2*(-1));
                $("#presentation").css('top', "50%");
                $("#presentation").css('margin-top', parseInt(data[0].height)/2*(-1));
                presentationSize();
                document.title = data[0].title;
                presentationData = data[1];
                loadpage(1);
            }else{
                
            }
        },
        error: function (e){
            
        }
    })
}

$(window).resize(function() {
    presentationSize();
});
$(window).mousedown(function(event) {
    event.preventDefault();
    switch (event.which) {
        case 1:
            page++;
            loadpage(page);
            break;
        case 2:
            page = 1;
            loadpage(page);
            break;
        case 3:
            page--;
            loadpage(page);
            break;
        default:
            break;
    }
});
$(document).bind("contextmenu", function(event) {
    event.preventDefault();
});

function presentationSize (){
    var scale = $(window).width()/$("#presentation").width();
    if (($(window).height()/$("#presentation").height()) < scale){
        scale = $(window).height()/$("#presentation").height();
    }
    $("#presentation").css({'zoom': scale, '-moz-transform': 'scale('+scale+')'});
}

function loadpage(pageN){
    pageN--;
    $("#presentation").html(presentationData[pageN].html);
}

function loading(e){
    if(e.lengthComputable){
        loadingcircle(Math.round(e.loaded/e.total*100));
    }
}

function loadingcircle (percent) {
	if (percent > 100){percent = 100;}
	if (percent < 0){percent = 0;}
    $("#loading").css({'stroke-dashoffset': (((100-percent)/100)*Math.PI*parseInt($("#loading").attr('r'))*2)});
    $("#loadingS").css({'stroke-dashoffset': (((100-percent)/100)*Math.PI*parseInt($("#loading").attr('r'))*2)-Math.PI*parseInt($("#loading").attr('r'))*2});
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