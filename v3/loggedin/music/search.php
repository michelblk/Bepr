<?php
require('../../include/php/auth.php');
if (!isset($_GET['a'])){exit;}
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache");
header('Content-Type: application/json; charset=utf-8');

if ($_GET['a'] == "all" || $_GET['a'] == "neww"){
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
    if ($start > mysql_num_rows(mysql_query("SELECT `id` FROM `music`"))){
        exit;
    }
    if ($_GET['a'] == "all"){$query = mysql_query("SELECT * FROM `music` ORDER BY `interpreter` LIMIT $start, $limit");}
    else if ($_GET['a'] == "neww"){$query = mysql_query("SELECT * FROM `music` ORDER BY `id` DESC LIMIT $start, $limit");}
    $results = array();
    if (mysql_num_rows($query) == 0){
        header('Content-Length: '.strlen("No DATA"));echo "No DATA"; exit;
    }else{
        require_once('cover.php');
        while ($song = mysql_fetch_array($query)){
            if (pathinfo($song['path'])['extension'] == "mp3"){$videoavailable = false;}else if (pathinfo($song['path'])['extension'] == "mp4"){$videoavailable = true;}
            array_push($results, array("interpreter" =>  $song['interpreter'], "title" => $song['title'], "album" => $song['album'], "id" => base64_encode($song['id']), "cover" => grabSongImage($song['id']), "videoavailable" => $videoavailable));
        }
        $results = json_encode($results);
        header('Content-Length: '.strlen($results)); // XHR Loading Bar works
        echo $results;
        exit;
    }    
}else
if ($_GET['a'] == "playlists"){
    require_once('cover.php');
    $results = array();
    // Eigene
    $eigene = array();
    $query = mysql_query("SELECT * FROM `music-playlist` WHERE `userid` LIKE '".$_SESSION['userid']."' AND `id` >= 5 ORDER BY `id` DESC");  // ID >= 5 weil nicht abwärtskompatibel
    while ($playlist = mysql_fetch_array($query)){
        $creator = mysql_fetch_array(mysql_query("SELECT `firstname`,`lastname` FROM user WHERE id LIKE '".$playlist['userid']."' LIMIT 1"));
        $creator = $creator['firstname']." ".$creator['lastname'];
        $content = json_decode($playlist['content'], true);
        array_push($eigene, array("id" => base64_encode($playlist['id']), "name" => $playlist['name'], "tracks" => count($content), "creatorname" => $creator, "creatorid" => base64_encode($playlist['userid']), "public" => $playlist['public'], "cover" => grabPlaylistImage($playlist['id'])));
    }
    $results['own'] = $eigene;
    // Öffentliche
    $oeffentliche = array();
    $query = mysql_query("SELECT * FROM `music-playlist` WHERE (`public` LIKE '1' AND `userid` NOT LIKE '".$_SESSION['userid']."') AND `id` >= 5 ORDER BY `id` DESC");  // ID >= 5 weil nicht abwärtskompatibel
    while ($playlist = mysql_fetch_array($query)){
        $creator = mysql_fetch_array(mysql_query("SELECT `firstname`,`lastname` FROM user WHERE id LIKE '".$playlist['userid']."' LIMIT 1"));
        $creator = $creator['firstname']." ".$creator['lastname'];
        $content = json_decode($playlist['content'], true);
        array_push($oeffentliche, array("id" => base64_encode($playlist['id']), "name" => $playlist['name'], "tracks" => count($content), "creatorname" => $creator, "creatorid" => base64_encode($playlist['userid']), "public" => $playlist['public'], "cover" => grabPlaylistImage($playlist['id'])));
    }
    $results['public'] = $oeffentliche;
    
    $results = json_encode($results);
    header('Content-Length: '.strlen($results)); // XHR Loading Bar works
    echo $results;
}else
if ($_GET['a'] == "playlist" && isset($_GET['playlistID'])){
    require_once('cover.php');
    $playlistid = base64_decode($_GET['playlistID']);
    $query = mysql_query("SELECT * FROM `music-playlist` WHERE (`userid` LIKE '".$_SESSION['userid']."' OR `public` LIKE '1') AND `id` LIKE '$playlistid'");
    if (mysql_num_rows($query) != 1){
        http_response_code (203);
        $results = array("name" => "Keine Berechtigung!", "tracks" => "0", "creatorname" => "---", "creatorid" => "00", "description" => "Sie haben keine Berechtigungen, um auf diese Playlist zuzugreifen.", "public" => "0");
        $results = json_encode($results);
        header('Content-Length: '.strlen($results));
        echo $results;
        exit;
    }
    $playlist = mysql_fetch_array($query);
    $creator = mysql_fetch_array(mysql_query("SELECT `firstname`,`lastname` FROM user WHERE id LIKE '".$playlist['userid']."' LIMIT 1"));
    $creator = $creator['firstname']." ".$creator['lastname'];
    $content = json_decode($playlist['content'], true);
    $results = array("id" => base64_encode($playlist['id']), "name" => $playlist['name'], "tracks" => count($content), "creatorname" => $creator, "creatorid" => base64_encode($playlist['userid']), "description" => $playlist['description'], "public" => $playlist['public'], "cover" => grabPlaylistImage($playlist['id']));
    $playlistcontent = array();
    foreach($content as $playlistEntry){
        $playlistEntryID = base64_decode($playlistEntry);
        $playlistEntryQuery = mysql_fetch_array(mysql_query("SELECT * FROM `music` WHERE `id` LIKE '$playlistEntryID' LIMIT 1"));
        array_push($playlistcontent, array("id" => base64_encode($playlistEntryID), "title" => $playlistEntryQuery['title'], "album" => $playlistEntryQuery['album'], "interpreter" => $playlistEntryQuery['interpreter'], "cover" => grabSongImage($playlistEntryID)));    
    }
    $results["content"] = $playlistcontent;
    $results = json_encode($results);
    header('Content-Length: '.strlen($results)); // XHR Loading Bar works
    echo $results;
}else
if ($_GET['a'] == "search" && isset($_GET['q'])){
    require_once('cover.php');
    $q = urldecode($_GET['q']);
    if (strlen($q) < 3) {
        echo "[]";
        exit;
    }
    $results = array("q" => $q);
    $searchInterpreter = array();
    /*$query = mysql_query("SELECT * FROM `music` WHERE `interpreter` LIKE \"$q\" OR `interpreter` LIKE \"% $q %\" OR `interpreter` LIKE \"$q %\" OR `interpreter` LIKE \"% $q\" GROUP BY `interpreter` ORDER BY `interpreter` LIMIT 50"); // ACHTUNG: Suchbegriffe am Anfang oder Ende von Zeichen, die nicht Leerzeichen sind, werden nicht erkannt 
    while ($song = mysql_fetch_array($query)){
        array_push($searchInterpreter, array("interpreter" =>  $song['interpreter'], "cover" => grabSongImage($song['id'])));
    }*/
    $query = mysql_query("SELECT * FROM `music` WHERE `interpreter` LIKE \"$q\" ORDER BY `title` LIMIT 50"); //OR `interpreter` LIKE \"% $q %\" OR `interpreter` LIKE \"%$q %\" OR `interpreter` LIKE \"% $q%\"
    while ($song = mysql_fetch_array($query)){
        if (pathinfo($song['path'])['extension'] == "mp3"){$videoavailable = false;}else if (pathinfo($song['path'])['extension'] == "mp4"){$videoavailable = true;}
        array_push($searchInterpreter, array("interpreter" =>  $song['interpreter'], "title" => $song['title'], "album" => $song['album'], "id" => base64_encode($song['id']), "cover" => grabSongImage($song['id']), "videoavailable" => $videoavailable));
    }
    $searchAlbum = array();
    $query = mysql_query("SELECT * FROM `music` WHERE `album` LIKE \"$q\" OR `album` LIKE \"% $q %\" OR `album` LIKE \"%$q %\" OR `album` LIKE \"% $q%\" GROUP BY `interpreter` ORDER BY `album` LIMIT 50");
    while ($song = mysql_fetch_array($query)){
        array_push($searchAlbum, array("interpreter" =>  $song['interpreter'], "album" => $song['album'], "cover" => grabSongImage($song['id'])));
    }
    $searchSong = array();
    $query = mysql_query("SELECT * FROM `music` WHERE `title` LIKE \"$q\" OR `title` LIKE \"% $q %\" OR `title` LIKE \"%$q %\" OR `title` LIKE \"% $q%\" ORDER BY `title` LIMIT 50");
    while ($song = mysql_fetch_array($query)){
        if (pathinfo($song['path'])['extension'] == "mp3"){$videoavailable = false;}else if (pathinfo($song['path'])['extension'] == "mp4"){$videoavailable = true;}
        array_push($searchSong, array("interpreter" =>  $song['interpreter'], "title" => $song['title'], "album" => $song['album'], "id" => base64_encode($song['id']), "cover" => grabSongImage($song['id']), "videoavailable" => $videoavailable));
    }
    
    $results['interpreter'] = $searchInterpreter;
    $results['album'] = $searchAlbum;
    $results['song'] = $searchSong;
    $results = json_encode($results);
    header('Content-Length: '.strlen($results)); // XHR Loading Bar works
    echo $results;
}else
if ($_GET['a'] == "PlaylistsPopup" && isset($_GET['songID'])){
    header('Content-Type: application/json; charset=utf-8');
    $erg = array();
    $songID = base64_decode($_GET['songID']);
    $songdata = mysql_fetch_array(mysql_query("SELECT `title`,`album`,`interpreter` FROM `music` WHERE `id` LIKE '".$songID."'"));
    $erg['songID'] = $_GET['songID'];
    $erg['songtitle'] = $songdata['title'];
    $erg['songalbum'] = $songdata['album'];
    $erg['songinterpreter'] = $songdata['interpreter'];
    $data = mysql_query("SELECT * FROM `music-playlist` WHERE `userid` LIKE '".$_SESSION['userid']."' AND `id` >= 5 ORDER BY `id` DESC"); //Playlisten aus Bepr2 werden nicht unterstützt
    $playlistdata = array();
    while ($playlist = mysql_fetch_array($data)){
        $content = json_decode($playlist['content'], true);
        array_push($playlistdata, array("id" => base64_encode($playlist['id']), "name" => $playlist['name'], "tracks" => count($content), "public" => $playlist['public'], "alreadyin" => in_array($_GET['songID'], $content, true)));        
    }
    $erg['playlists'] = $playlistdata;
    $erg = json_encode($erg);
    header('Content-Length: '.strlen($erg)); 
    echo $erg;
}else
{
    exit;
}

?>