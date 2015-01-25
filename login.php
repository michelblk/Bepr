<?php 
session_start();

if (isset($_GET['remain']) && isset($_COOKIE["remain"])){
    require('include/php/database.php');
    $cookie = $_COOKIE["remain"];
    $query = mysql_query("SELECT * FROM user WHERE `remainID` LIKE '".$cookie."' LIMIT 1");
    if (mysql_num_rows($query) == 1){
        $data = mysql_fetch_array($query);
        if ($data['disabled'] == 1){
         header('LOCATION: index.php?login=disabled');
            exit;
        }
        $_SESSION['loggedin'] = True;
        $_SESSION['email'] = $data['email'];
        $_SESSION['userid'] = $data['id'];
        $_SESSION['admin'] = $data['admin'];
        $_SESSION['firstname'] = $data['firstname'];
        $_SESSION['lastname'] = $data['lastname'];
        $_SESSION['userurl'] = $data['userurl'];
        setcookie("remain", $cookie, strtotime( '+365 days' ), "/");
        if (!isset($_GET['ref'])){
            header('LOCATION: loggedin/');
        }else{
           header('LOCATION: '.base64_decode($_GET['ref']));
        }
    }else{
        unset($_COOKIE['remain']);
        setcookie('remain', null, time()-3600, '/');
        header('LOCATION: index.php?login=expired');
    }
    exit;
}

if (!isset($_GET['a']) || $_SERVER['REQUEST_METHOD'] != "POST"){
    header('LOCATION: error.php?a=nopermission');
    exit;    
}

if ($_GET['a'] == "sign-in" && isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    require('include/php/database.php');
    $query = mysql_query("SELECT * FROM user WHERE `email` LIKE '".$email."' AND `password` LIKE '".$password."' LIMIT 1");
    if (mysql_num_rows($query) == 1){
        $_SESSION['loggedin'] = True;
        $_SESSION['email'] = $email;
        $data = mysql_fetch_array($query);
        $_SESSION['userid'] = $data['id'];
        $_SESSION['firstname'] = $data['firstname'];
        $_SESSION['lastname'] = $data['lastname'];
        $_SESSION['userurl'] = $data['userurl'];
        if (isset($_POST['remain'])){
            if(!isset($data['remainID'])){
                $remainID = base64_encode($_SESSION['userid']."/".md5(uniqid(rand(), true)));
            }else{
                $remainID = $data['remainID'];
            }
            setcookie("remain", $remainID, strtotime( '+365 days' ), "/");
            mysql_query("UPDATE `user` SET `remainID`='$remainID' WHERE `id` LIKE '".$_SESSION['userid']."' LIMIT 1");
        }
        if (!isset($_GET['ref'])){
            header('LOCATION: loggedin/');
        }else{
            header('LOCATION: '.base64_decode($_GET['ref']));
        }
        exit;        
    }else{
        if (!isset($_GET['ref'])){
            header('LOCATION: index.php?login=failed');
        }else{
            header('LOCATION: index.php?login=failed&ref='.$_GET['ref']);
        }   
    }
}else if ($_GET['a'] == "sign-up"){
    /*require_once('include/php/recaptchalib.php'); //OLD
    $secret = "6LcIOgUTAAAAADaogZsNvgQ2-G-y_vDgNB999dun";
    $recaptcha = new \ReCaptcha\ReCaptcha($secret);           $_SER
    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_IP']);
    if ($resp->isSuccess()) {
        // verified!
    } else {
        $errors = $resp->getErrorCodes();
    } */
    echo "This function has been disabled";
    exit;   
}
else{
    header('LOCATION: index.php?login=failed');
}
?>