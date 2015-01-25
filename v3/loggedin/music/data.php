<?php
require('../../include/php/auth.php');
require('../../include/php/database.php');
if (!isset($_GET['songID'])){
    exit;
}


if (isset($_GET['subtitle'])){
    $file = "../../include/data/music/subtitle/".base64_decode($_GET['songID']);
    header('Content-type: text/vtt;charset=utf-8');
    if (!file_exists($file)){
        exit;
    }else{
        $fp = fopen($file, 'r');
        if (filesize($file) == 0){
            exit;
        }
        echo fread($fp, filesize($file));
        fclose($fp);
    }
    exit;
} else

if (isset($_GET['cover'])){
    $seconds_to_cache = 30*60;
    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
    header("Expires: $ts");
    header("Pragma: cache");
    header("Cache-Control: max-age=$seconds_to_cache");
    $path = "../../include/data/music/songcover/small/".base64_decode($_GET['songID']);
    //$type = pathinfo($path, PATHINFO_EXTENSION);
    if (file_exists($path)){
        header('Content-Type: image/jpeg');
        $data = file_get_contents($path);
        echo $data;
        exit;
    }else if (file_exists("../../include/data/music/songcover/".base64_decode($_GET['songID']))){
        header('Content-Type: image/jpeg');
        $path = "../../include/data/music/songcover/".base64_decode($_GET['songID']);
        $data = file_get_contents($path);
        echo $data;
        exit;
    }else{
        header('Content-Type: image/svg+xml');
        $data = file_get_contents("../../include/image/svg/nocover.svg");
        echo $data;
        exit;
    }
} else

if (isset($_GET['getInfo'])) {
    header('Content-Type: application/json; charset=utf-8');
    $songID = base64_decode($_GET['songID']);
    $data = mysql_fetch_array(mysql_query("SELECT * FROM `music` WHERE `id` LIKE '".$songID."'"));
    if (pathinfo($data['path'])['extension'] == "mp3"){$videoavailable = false;}else if (pathinfo($data['path'])['extension'] == "mp4"){$videoavailable = true;}
    $data["videoavailable"] = $videoavailable;
    $data = json_encode($data);
    header('Content-Length: '.strlen($data));
    echo $data;
    exit;
} else

if (isset($_GET['markasplayed'])) {
    if ($_GET['songID'] == $_POST['songID'] && $_SERVER['REQUEST_METHOD'] == "POST"){
        header('Content-Type: application/json; charset=utf-8');
        echo "[]";
    }else{
        http_response_code(403);
        echo "ERROR";
    }
    exit;
}else

if (isset($_GET['addtoPlaylists']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['playlists']) && isset($_POST['songID']) && $_GET['songID'] == $_POST['songID']){
    $playlists = $_POST['playlists'];
    foreach($playlists as $playlist){
        $changes = false;
        $playlistID = base64_decode($playlist[0]);
        $inplaylist = $playlist[1];
        $playlistDatabaseContent = mysql_fetch_array(mysql_query("SELECT `content` FROM `music-playlist` WHERE `id` LIKE '$playlistID' LIMIT 1"))['content'];
        if ($playlistDatabaseContent != ""){
            $playlistDatabaseContent = json_decode($playlistDatabaseContent);
            if ($inplaylist == "true" && !in_array($_GET['songID'], $playlistDatabaseContent, true)){ // Wenn es ausgewählt ist, aber nicht in der Datenbank
                array_push($playlistDatabaseContent, $_GET['songID']);
                $changes = true;
            }
            if ($inplaylist == "false" && in_array($_GET['songID'], $playlistDatabaseContent, true)){ // Wenn es nicht ausgewählt ist, aber in der Datenbank
                $playlistDatabaseContent1 = array_values(array_diff($playlistDatabaseContent, [$_GET['songID']]));
                if ($playlistDatabaseContent1 != null){
                    $playlistDatabaseContent = $playlistDatabaseContent1;
                    $changes = true;
                }else{
                    http_response_code(501);
                    exit;
                }
            }
            if ($changes = true){
                $erg = json_encode($playlistDatabaseContent);
                mysql_query("UPDATE `music-playlist` SET `content` = '$erg' WHERE `id` LIKE '$playlistID' LIMIT 1");
            }
        }
        //print_r($playlist);
    }
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Length: '.strlen("[]"));
    echo "[]";
    exit;
}else
if (isset($_GET['removeFromPlaylist']) && isset($_GET['playlistID']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['songID']) && isset($_POST['playlistID'])){
    $playlistID = base64_decode($_POST['playlistID']);
    $mysqlfetch = mysql_fetch_array(mysql_query("SELECT * FROM `music-playlist` WHERE `id` LIKE '$playlistID' LIMIT 1"));
    if ($mysqlfetch['userid'] != $_SESSION['userid']) {
        http_response_code(401);
        exit;
    }
    $playlistDatabaseContent = $mysqlfetch['content'];
    if ($playlistDatabaseContent != ""){
        $playlistDatabaseContent = json_decode($playlistDatabaseContent);
        $playlistDatabaseContent1 = array_values(array_diff($playlistDatabaseContent, [$_POST['songID']]));
        if ($playlistDatabaseContent1 != null){
            $playlistDatabaseContent = $playlistDatabaseContent1;
        }else{
            http_response_code(501);
            exit;
        }
        $playlistDatabaseContent = json_encode($playlistDatabaseContent);
        mysql_query("UPDATE `music-playlist` SET `content` = '$playlistDatabaseContent' WHERE `id` LIKE '$playlistID' LIMIT 1");
        http_response_code(204);
        exit;
    }else{
        http_response_code(406);
        exit;
    }
}
?>
