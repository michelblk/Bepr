<?php 
require('../../include/php/auth.php');
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache");
if (isset($_GET['s'])){
    $request = urldecode($_GET['s']);
    $query = mysql_query("SELECT * FROM `video` WHERE `title` REGEXP '$request' OR `year` REGEXP '$request' ORDER BY `id` DESC");
    $results = array();
    if (mysql_num_rows($query) == 0){
        echo "No DATA"; exit;
    }else{
        header('Content-Type: application/json; charset=utf-8');
        while ($song = mysql_fetch_array($query)){
            if (!$song['cover']){$cover = "NO";}else{$cover = "YES";}
            array_push($results, array("title" => utf8_encode($song['title']), "path" => (utf8_encode($song['path'])), "id" => $song['id'], "cover" => $cover, "year" => utf8_encode($song['year'])));
        }
        echo json_encode($results);
    }
}else
if (isset($_GET['recent'])){
    if (!isset($_GET['limit']) OR $_GET['limit'] > 100){
        $limit = 30;
    }else{
        $limit = $_GET['limit'];
    }
    if (!isset($_GET['start'])){
        $start = 0;
    }else{
        $start = $_GET['start'];
    }
    if ($start > mysql_num_rows(mysql_query("SELECT `id` FROM `video`"))){
        exit;
    }
    $query = mysql_query("SELECT * FROM `video` ORDER BY `id` DESC LIMIT $start, $limit");
    $results = array();
    if (mysql_num_rows($query) == 0){
        echo "No DATA"; exit;
    }else{
        header('Content-Type: application/json; charset=utf-8');
        while ($song = mysql_fetch_array($query)){
            if (!$song['cover']){$cover = "NO";}else{$cover = "YES";}
            array_push($results, array("title" => utf8_encode($song['title']), "path" => (utf8_encode($song['path'])), "id" => $song['id'], "cover" => $cover, "year" => utf8_encode($song['year'])));
        }
        echo json_encode($results);
    }    
}else
{
    exit;
}

?>