<?php 
require('../../include/php/auth.php');
require('../../include/php/database.php');
if (isset($_GET['songID'])){
    $songID = $_GET['songID'];
    $cover = mysql_fetch_array(mysql_query("SELECT `cover` FROM `music` WHERE `id` LIKE '$songID' LIMIT 1"))['cover'];
    if (!$cover){
        header('Content-Type: image/svg+xml');
        echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M18 3v2h-2v-2h-8v2h-2v-2h-2v18h2v-2h2v2h8v-2h2v2h2v-18h-2zm-10 14h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2v-2h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2v-2h2v2z\"/><path d=\"M0 0h24v24h-24z\" fill=\"none\"/></svg>";    
    }else{
        header('Content-Type: image/png');
        echo $cover;
    }
}
?>