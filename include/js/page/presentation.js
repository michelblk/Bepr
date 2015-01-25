$(document).ready(function (){
    $("#presentations").on('click', '.presentation', function (){
        window.location.href = 'act.php?a='+getParameter('a')+'&id='+$(this).attr('data-id');
    });
})

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