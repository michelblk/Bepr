<?php 
session_start();
if ($_SESSION['loggedin'] != True){
    $path = $_SERVER['REQUEST_URI'];
    if ($_SERVER['SERVER_PORT'] != 5000){  // ACHTUNG!
        header('LOCATION: /bepr/v2/?login=noLogin&ref='.base64_encode($path));
    }else{
        header('LOCATION: /v2/?login=noLogin&ref='.base64_encode($path));
    }
}
?>