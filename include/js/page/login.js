$(document).ready(function (){
    $(".nav").click(function (){
        action = $(this).attr('data-action');
        $(".login-section").hide();
        $("#"+action).show();
        $(".nav").removeClass('activ');
        $(this).addClass('activ');
    })
})