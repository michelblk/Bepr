<?php 
require('../../include/php/auth.php');
require('../../include/php/database.php');

// ALLOW Simultaneous Requests to the PHP Script
// -> Sessions are not required anymore
/* "End the current session and store session data.
Session data is usually stored after your script terminated without the need to call session_write_close(),
but as session data is locked to prevent concurrent writes only one script may operate on a session at any time.
When using framesets together with sessions you will experience the frames loading one by one due to this locking.
You can reduce the time needed to load all the frames by ending the session as soon as all changes to session variables are done."
http://php.net/manual/en/function.session-write-close.php */
session_write_close();

if (isset($_GET['songID'])){
    $seconds_to_cache = 30*60;
    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
    header("Expires: $ts");
    header("Pragma: cache");
    header("Cache-Control: max-age=$seconds_to_cache");

    $songID = base64_decode($_GET['songID']);
    /*$cover = mysql_fetch_array(mysql_query("SELECT `cover` FROM `music` WHERE `id` LIKE '$songID' LIMIT 1"))['cover'];
    if (!$cover){
        //header('Content-Type: image/svg+xml');
        //echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M18 3v2h-2v-2h-8v2h-2v-2h-2v18h2v-2h2v2h8v-2h2v2h2v-18h-2zm-10 14h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2v-2h2v2zm10 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2v-2h2v2z\"/><path d=\"M0 0h24v24h-24z\" fill=\"none\"/></svg>";    
        header('Content-Type: image/svg+xml');
        echo "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\"><path d=\"M0 0h24v24h-24z\" fill=\"grey\"/><path d=\"M4 4h7v-2h-7c-1.1 0-2 .9-2 2v7h2v-7zm6 9l-4 5h12l-3-4-2.03 2.71-2.97-3.71zm7-4.5c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5.67 1.5 1.5 1.5 1.5-.67 1.5-1.5zm3-6.5h-7v2h7v7h2v-7c0-1.1-.9-2-2-2zm0 18h-7v2h7c1.1 0 2-.9 2-2v-7h-2v7zm-16-7h-2v7c0 1.1.9 2 2 2h7v-2h-7v-7z\" fill=\"#C0C0C0\"/></svg>"; 
    }else{ */
    if (file_exists("../../include/image/music/songcover/".$songID)){
        header('Content-Type: image/jpg');
        if (isset($_GET['size']) && $_GET['size'] == "n"){
            $f = fopen("../../include/image/music/songcover/".$songID, "r");
            $data = fread($f, filesize("../../include/image/music/songcover/".$songID));
            fclose($f);
            echo $data;
            exit;    
        }else{
            if (!file_exists("../../include/image/music/songcover/small/".$songID)){
                $img = resizeImageFromHDD("../../include/image/music/songcover/".$songID, 250, 250);
                imagejpeg($img, "../../include/image/music/songcover/small/".$songID);
                imagejpeg($img);  // Ressourcen!!
                imagedestroy($img); 
                exit;   
            }else{
                $f = fopen("../../include/image/music/songcover/small/".$songID, "r");
                $data = fread($f, filesize("../../include/image/music/songcover/small/".$songID));
                fclose($f);
                echo $data; 
                exit;       
            }
            exit;    
        }
    }else{
        header('LOCATION: ../../include/image/SVG/nocover.svg');
        exit;
    }
    //}
}else
if (isset($_GET['playlist']) && isset($_GET['id']) && isset($_GET['size'])){
    $id = $_GET['id'];
    $cover = mysql_fetch_array(mysql_query("SELECT `cover` FROM `music-playlist` WHERE `id` LIKE '$id' LIMIT 1"))['cover'];
    if (!$cover){
        exit;
    }else{
        header('Content-Type: image/png');
        echo resizeImage($cover, $_GET['size'], $_GET['size']);
        exit;    
    }
}

function resizeImage($file, $newwidth, $newheight){
    list($width, $height) = getimagesizefromstring ($file);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $image = imagecreatefromstring($file);
    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return imagejpeg($thumb);
    imagedestroy($thumb);
}

function resizeImageFromHDD($filename, $newwidth, $newheight){
    list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromfile($filename);
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $thumb;
}

function imagecreatefromfile( $filename ) {
    switch (exif_imagetype($filename)) {
        case 2:
            return imagecreatefromjpeg($filename);
        break;

        case 3:
            return imagecreatefrompng($filename);
        break;

        case 1:
            return imagecreatefromgif($filename);
        break;

        default:
            exit;
        break;
    }
}
?>