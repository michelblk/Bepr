<?php

/////////////////////////////
//http_response_code(503); // Vorrübergehend nicht verfügbar
//echo "<div style='text-align: center;'><h1 style='font-family: \"Segoe UI\",\"Helvetica Neue\",Helvetica,Arial,sans-serif; text-align: center; font-weight: 200;'>Aufgrund von Festplattenproblemen deaktiviert</h1><img src='http://vignette4.wikia.nocookie.net/cloudywithachanceofmeatballs/images/c/cd/Warning_sign.png/revision/latest?cb=20140228025438' height='200px' /></div>";
//exit;
/////////////////////////////

session_start();
if ($_SESSION['loggedin'] != True){
    $path = $_SERVER['REQUEST_URI'];
    if ($_SERVER['SERVER_PORT'] != 5000){  // ACHTUNG!
        header('LOCATION: /bepr/v3/?login=noLogin&ref='.base64_encode($path));
    }else{
        header('LOCATION: /v3/?login=noLogin&ref='.base64_encode($path));
    }
}
?>