<?php
require('../../include/php/auth.php');
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache");
if (!isset($_GET['songID'])){
    exit;
}
$song = mysql_query("SELECT * FROM music WHERE `id` LIKE '".base64_decode($_GET['songID'])."' LIMIT 1");
if (mysql_num_rows($song) != 1){
    exit;
}

// ALLOW Simultaneous Requests to the PHP Script
// -> Sessions are not required anymore
/* "End the current session and store session data.
Session data is usually stored after your script terminated without the need to call session_write_close(),
but as session data is locked to prevent concurrent writes only one script may operate on a session at any time.
When using framesets together with sessions you will experience the frames loading one by one due to this locking.
You can reduce the time needed to load all the frames by ending the session as soon as all changes to session variables are done."
http://php.net/manual/en/function.session-write-close.php */
session_write_close();


if (!isset($_GET['subtitle'])){
    $filename = mysql_fetch_array($song)['path']; 
    $file = '/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/'.$filename;
    if (!file_exists($file)){
         $file = "/media/NAS/RaspberryPi/01/Apache/bepr/include/php/NotFound.mp4";
         $fp = @fopen($file, 'r'); 
         header('Content-type: video/mp4');
         echo fread($fp, filesize($file));
         fclose($fp);
         exit;
    }
    $fp = @fopen($file, 'r');
    $size   = filesize($file); // File size
    $length = $size;           // Content length
    $start  = 0;               // Start byte
    $end    = $size - 1;       // End byte
    if (pathinfo($file, PATHINFO_EXTENSION) == "mp3"){     // MP3 erkennen
        header('Content-type: audio/mp3');    
    }else{
        header('Content-type: video/mp4');    
    }
    //header("Accept-Ranges: 0-$length");
    header("Accept-Ranges: bytes");
    if (isset($_SERVER['HTTP_RANGE'])) {
        $c_start = $start;
        $c_end   = $end;
        list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
        if (strpos($range, ',') !== false) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            header("Content-Range: bytes $start-$end/$size");
            exit;
        }
        if ($range == '-') {
            $c_start = $size - substr($range, 1);
        }else{
            $range  = explode('-', $range);
            $c_start = $range[0];
            $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
        }
        $c_end = ($c_end > $end) ? $end : $c_end;
        if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            header("Content-Range: bytes $start-$end/$size");
            exit;
        }
        $start  = $c_start;
        $end    = $c_end;
        $length = $end - $start + 1;
        fseek($fp, $start);
        header('HTTP/1.1 206 Partial Content');
    }
    header("Content-Range: bytes $start-$end/$size");
    header("Content-Length: ".$length);
    $buffer = 1024 * 8;
    while(!feof($fp) && ($p = ftell($fp)) <= $end) {
        if ($p + $buffer > $end) {
            $buffer = $end - $p + 1;
        }
        set_time_limit(0);
        echo fread($fp, $buffer);
        flush();
    }
    fclose($fp);
    exit();
}else
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
}
?>