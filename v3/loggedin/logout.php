<?php 
require('../include/php/auth.php');
$_SESSION['loggedin'] = FALSE;
unset($_COOKIE['remain']);
setcookie('remain', null, time()-3600, '/');
session_unset();
session_destroy();
$get = "?";
if (isset($_GET['ref'])){
    $get .= "ref=".$_GET['ref'];   
}
if (isset($_GET['remain'])){
    $get .= "&remain=".$_GET['remain'];       
}
header('LOCATION: ../'.$get);
?>