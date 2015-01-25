$(document).ready(function (){
    $("input[name='dateien[]']").change(function() {
        $("#user-files tr:not(:nth-child(1))").remove();
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            newFile($(this).get(0).files[i].name, i);
        }
    });

    function newFile(filename, newFileNr) {
        var clone = $("#user-files-example").clone().appendTo("#user-files");
        clone.removeAttr('id');
        clone.children().children("select[name='genre[]']").attr({'name': "genre["+newFileNr+"][]"});
        clone.children().children("input[name='filename[]']").val(filename);
        autoFillIn(clone, filename);
    }

    function autoFillIn(clone, filename) {
        var re = new RegExp(". - .");
        if(re.test(filename)){
            clone.children().children("input[name='title[]']").val(filename.split(' - ')[1].split('.mp4')[0]);
            clone.children().children("input[name='interpreter[]']").val(filename.split('\\').pop().split(' - ')[0]);
        }
    }
});

function uploadtodatabase(){
    var data = new FormData();
    $.each($("input[name='dateien[]']")[0].files, function (key, file){
        data.append('dateien[]', file);
    });
    $.each($("input[name='cover[]']"), function (key, file){
        file = file.files[0];
        data.append('cover[]', file);
    });
    var other_data = $('#file-form').serializeArray();
    $.each(other_data,function(key,input){
        data.append(input.name,input.value);
    });
    $.ajax({
        url: 'upload.php?act',
        type: 'POST',
        data: data,
        cache: false,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        beforeSend: function (){
            loadingbar.connecting();
        },
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress',loadingbar.update, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        success: function (){
            console.log('ERFOLG');
            $('#file-form')[0].reset();
            $("#user-files tr:not(:nth-child(1))").remove();

        },
        error: function (){
            console.log('FEHLER');
        },
        complete: function (){
            loadingbar.stop();
        }
    });
}

var loadingbar = new(function (){
    this.connecting = function (){
        $(document).ready(function (){
            $("#loadingbar-status").css({'width': '0px'});
            if ($("#loadingbar").is(':hidden')){
                $("#loadingbar").fadeIn(50);
            }
            $("#loadingbar-status").animate({
                width: '100px'
            }, 50);
        })
    }
    this.update = function (e){
        if(e.lengthComputable){
            if ($("#loadingbar").is(':hidden')){
                $("#loadingbar-status").css({'width': '0px'});
                $("#loadingbar").show();
            }
            $("#loadingbar-status").stop().animate({
                width: $("#loadingbar").width()*(e.loaded/(e.total-100))
            }, 500);
        }
    }
    this.stop = function (){
        $("#loadingbar-status").stop().css({'width': $("#loadingbar").width()});
        setTimeout(function (){
            $("#loadingbar").stop().fadeOut(300);
        }, 300);
        $("#loading").hide();
    }
});