<?php
require('../../include/php/auth.php');
require('../../include/php/database.php');
$query = mysql_query("SELECT * FROM presentation WHERE id LIKE '".base64_decode($_GET['id'])."' LIMIT 1");
$filename = mysql_fetch_array($query)['path'];
$file = "/media/NAS/RaspberryPi/01/Apache/bepr/include/data/presentation/user/".$filename;
header('Content-type: application/json;charset=utf-8');
if (!file_exists($file)){
    exit;
}else{
    $fp = fopen($file, 'r');
    if (filesize($file) == 0){
        exit;
    }
    $result = fread($fp, filesize($file));
    header('Content-Length: '.strlen($result)); // XHR Loading Bar works
    echo $result;
    fclose($fp);
}
exit;
?>