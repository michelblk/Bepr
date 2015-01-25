$(document).ready(function () {
    if ($.cookie('remain') && $.cookie('remain') != "null") {
        $("#stayloggedin").attr({'checked': true});
    }
    if(typeof(Storage) !== "undefined") {
        if (localStorage.MusicStreamNoMargin){
            $("#musicstreamnomargin").attr({'checked': true});   
        }
        if (!localStorage.MusicQueue || !localStorage.MusicQueueP) {
            $("#resetmusicqueue").attr('disabled', true);  // Warteschlange bereits leer  
        }
    }else{ //Localstorage wird nicht unterstützt
        $("#resetmusicqueue").attr('disabled', true);
        $("#musicstreamnomargin").attr({'disabled': true}); 
    }
    
});

var setting = new(function(){
    this.stayloggedin = function (){
        if ($.cookie('remain') && $.cookie('remain') != "null"){
            $.cookie("remain", null, { path: '/' });
        }else{
            $("#stayloggedin").attr({'checked': false, 'disabled': true});
            location.href="../logout.php?remain=check&ref="+btoa(location.pathname);
        }
    }
    this.resetremainid = function (){
        
    }
    this.changename = function (){
        
    }
    this.changeemail = function (){
        
    }
    this.changepassword = function (){
        
    }
    this.musicstreamnomargin = function (){
        if (typeof(Storage) !== "undefined") {
            if (localStorage.MusicStreamNoMargin){
                localStorage.MusicStreamNoMargin = "";   
            }else{
                localStorage.MusicStreamNoMargin = 1;    
            }
        }
    }
    this.resetmusicqueue = function (){
        var queue = [];
        var queueP = -1;
        if(typeof(Storage) !== "undefined") {
            if (localStorage.MusicQueue || localStorage.MusicQueueP) {
                localStorage.MusicQueue = [];
                localStorage.MusicQueueP = -1;
            }
        }
        $("#resetmusicqueue").val('OK!').attr('disabled', true);
    }
    this.deletemusicplaylists = function (){
        
    }
    this.deactivateaccount = function (){
        
    }
});