<?php 
require('../include/php/auth.php');
$_SESSION['loggedin'] = FALSE;
unset($_COOKIE['remain']);
setcookie('remain', null, time()-3600, '/');
session_unset();
session_destroy();
header('LOCATION: ../');
?>