<?php 
require('../../include/php/auth.php');
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache");
if (isset($_GET['q'])){
    $request = urldecode($_GET['q']);
    $results = array("interpreter" => array(), "album" => array(), "song" => array());
    header('Content-Type: application/json; charset=utf-8');
    // Interpeter
    $query = mysql_query("SELECT * FROM `music` WHERE `interpreter` REGEXP \"$request\" GROUP BY `interpreter` ORDER BY `id` DESC");
    if (mysql_num_rows($query) != 0){
        while ($song = mysql_fetch_array($query)){
            if (!file_exists("../../v3/include/data/music/songcover/".$song['id'])){$cover = "0";}else{$cover = "1";}
            array_push($results['interpreter'], array("interpreter" => $song['interpreter'], "id" => $song['id'], "cover" => $cover));
        }
    }
    // Album
    $query = mysql_query("SELECT * FROM `music` WHERE `album` REGEXP \"$request\" GROUP BY `album` ORDER BY `id` DESC");
    if (mysql_num_rows($query) != 0){
        while ($song = mysql_fetch_array($query)){
            if (!file_exists("../../v3/include/data/music/songcover/".$song['id'])){$cover = "0";}else{$cover = "1";}
            array_push($results['album'], array("interpreter" => $song['interpreter'], "id" => $song['id'], "album" => $song['album'], "cover" => $cover, "subtitles" => utf8_encode($song['subtitles'])));
        }
    }
    // Songs
    $query = mysql_query("SELECT * FROM `music` WHERE `title` REGEXP \"$request\" ORDER BY `id` DESC");
    if (mysql_num_rows($query) != 0){
        while ($song = mysql_fetch_array($query)){
            if (!file_exists("../../v3/include/data/music/songcover/".$song['id'])){$cover = "0";}else{$cover = "1";}
            array_push($results['song'], array("interpreter" => $song['interpreter'], "title" => $song['title'], "album" => $song['album'], "id" => $song['id'], "cover" => $cover, "subtitles" => utf8_encode($song['subtitles'])));
        }    
    }
    $results = json_encode($results);
    header('Content-Length: '.strlen($results)); // XHR Loading Bar works
    echo $results;
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
    if ($start > mysql_num_rows(mysql_query("SELECT `id` FROM `music`"))){
        exit;
    }
    $query = mysql_query("SELECT * FROM `music` WHERE `id` <= 780 ORDER BY `id` DESC LIMIT $start, $limit");
    $results = array();
    if (mysql_num_rows($query) == 0){
        header('Content-Length: '.strlen("No DATA"));echo "No DATA"; exit;
    }else{
        header('Content-Type: application/json; charset=utf-8');
        while ($song = mysql_fetch_array($query)){
            if (!file_exists("../../v3/include/data/music/songcover/".$song['id'])){$cover = "0";}else{$cover = "1";}
            array_push($results, array("interpreter" =>  $song['interpreter'], "title" => $song['title'], "album" => $song['album'], "id" => $song['id'], "cover" => $cover, "subtitles" => utf8_encode($song['subtitles'])));
        }
        $results = json_encode($results);
        header('Content-Length: '.strlen($results)); // XHR Loading Bar works
        echo $results;
    }    
}else
if (isset($_GET['playlists'])){
    $query = mysql_query("SELECT * FROM `music-playlist` WHERE `userid` LIKE '".$_SESSION['userid']."' OR `public` LIKE '1' ORDER BY `id` DESC");
    $results = array();
    if (mysql_num_rows($query) == 0){
        exit;
    }else{
        header('Content-Type: application/json; charset=utf-8');
        while ($playlist = mysql_fetch_array($query)){
            if (!$playlist['cover']){$cover = "0";}else{$cover = "1";}
            $creator = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id LIKE '".$playlist['userid']."' LIMIT 1"));
            $creator = $creator['firstname']." ".$creator['lastname'];
            $i = 0;
            $content = json_decode($playlist['content'], true);
            $preview = "";
            while ($i < count($content) && $i < 10){
                if ($i != 0){
                    $preview .= ", ";
                }
                $preview .= $content[$i]["title"]; 
                $i++;   
            }
            array_push($results, array("id" => $playlist['id'], "cover" => $cover, "name" => $playlist['name'], "content" => $content, "tracks" => count($content), "creator" => $creator, "public" => $playlist['public'], "preview" => $preview));
        }
        $results = json_encode($results);
        header('Content-Length: '.strlen($results)); // XHR Loading Bar works
        echo $results;
    }   
}else
if (isset($_GET['playlist'])){
    $id = base64_decode($_GET['playlist']);
    $query = mysql_query("SELECT * FROM `music-playlist` WHERE `id` LIKE '".$id."' LIMIT 1");
    $results = array();
    if (mysql_num_rows($query) == 0){
        exit;
    }else{
        header('Content-Type: application/json; charset=utf-8');
        $playlist = mysql_fetch_array($query);
        if (!$playlist['cover']){$cover = "0";}else{$cover = "1";}
        $creator = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id LIKE '".$playlist['userid']."' LIMIT 1"));
        $creator = $creator['firstname']." ".$creator['lastname'];
        $i = 0;
        $content = json_decode($playlist['content'], true);
        foreach($content as $ascontent){
            $album = mysql_fetch_array(mysql_query("SELECT `album` FROM `music` WHERE `id` LIKE '".base64_decode($ascontent['id'])."' LIMIT 1"))['album'];
            if (!$album){
                $album = "";
            }
            $content[$i]["album"] = $album;
            $i++; 
        }
        $i = 0;
        while ($i < count($content) && $i < 10){
            if ($i != 0){
                $preview .= ", ";
            }
            $preview .= $content[$i]["title"];
            $i++;
        }
        array_push($results, array("id" => $playlist['id'], "cover" => $cover, "name" => $playlist['name'], "content" => $content, "tracks" => count($content), "creator" => $creator, "public" => $playlist['public'], "preview" => $preview));
        $results = json_encode($results);
        header('Content-Length: '.strlen($results)); // XHR Loading Bar works
        echo $results;
    }    
}else
if (isset($_GET['newsongssinceversionthree'])){
    $num = mysql_num_rows(mysql_query("SELECT * FROM `music`")) - 780;
    echo $num;
    exit;
}else
{
    exit;
}

?>