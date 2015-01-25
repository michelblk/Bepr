$(document).ready(function (){
    $("#navigation-button").click(function (){
        $("#side-nav").show();
        $("#mask").show();
        $("#side-nav").animate({
            'margin-left': 0
        }, 200);
        $("#mask").animate({
            'opacity': 1
        }, 200);
    });
    $("#mask").click(function (){
        $("#side-nav").hide();
        $("#side-nav").animate({
            'margin-left': -240
        }, 200);
        $("#mask").animate({
            'opacity': 0
        }, 200, function (){
            $("#mask").hide();
        });
    })
    $("#side-nav dt").click(function (){
        if ($(this).next().is(':visible')){
            $(this).next().slideUp(200);
        }else{
            $(this).next().slideDown(200);
        }
    })
                                
    $('head').append("<!-- Chrome Theme Color --><meta name=\"theme-color\" content=\""+$("header").css('background-color')+"\" >");
})