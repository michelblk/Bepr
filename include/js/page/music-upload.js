$(document).ready(function (){
    $("input#title").keyup(function (){
        $("input#path").val($("input#path").val().split(' - ')[0]+" - "+$(this).val()+".mp4");
    });
    $("input#interpreter").keyup(function (){ 
        if (!$("input#path").val()){
            $("input#path").val("undefined - .mp4");
        }
        $("input#path").val($(this).val()+" - "+$("input#path").val().split(' - ')[1].split('.mp4')[0]+".mp4");
    }); 
    
    $("#video").change(function (){
        $("input#title").val($(this).val().split(' - ')[1].split('.mp4')[0]);
        $("input#interpreter").val($(this).val().split('\\').pop().split(' - ')[0]);   
    });   
});

function uploadtodatabase(){
    $(".status").hide();
    $("#submit-button").hide();
    var data = new FormData();
    data.append('cover', $("#cover")[0].files[0]);
    data.append('video', $("#video")[0].files[0]);
    var other_data = $('#new-database-entry-form').serializeArray();
    $.each(other_data,function(key,input){
        data.append(input.name,input.value);
    });
    $.ajax({
        url: 'upload.php?database&new',
        type: 'POST',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress',loadingbar, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        success: function (){ 
            $("#upload-success-status").slideDown();
            $("#new-database-entry-form input").not('MAX_FILE_SIZE').val("");
            $("#submit-button").show();
            setTimeout(function(){
                $("#upload-success-status").slideUp();    
            }, 5000); 
              
        },
        error: function (){
            $("#upload-error-status").slideDown();
            setTimeout(function(){
                $("#upload-error-status").slideUp();
            }, 10000);   
        }
    });   
}