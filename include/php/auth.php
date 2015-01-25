<?php

session_start();
if ($_SESSION['loggedin'] != TRUE){
    $path = $_SERVER['REQUEST_URI'];
    if ($_SERVER['SERVER_PORT'] != 5000){
        header('LOCATION: /bepr/?a=noLogin&ref='.base64_encode($path));
    }else{
        header('LOCATION: /?a=noLogin&ref='.base64_encode($path));
    }
}
?>