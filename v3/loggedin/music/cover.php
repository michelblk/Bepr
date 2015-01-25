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

function grabSongImage ($songID){
        /*$seconds_to_cache = 30*60;
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
        header("Expires: $ts");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds_to_cache");*/

        if (file_exists("../../include/data/music/songcover/".$songID)){
            //header('Content-Type: text/base64image');
            if (isset($_GET['size']) && $_GET['size'] == "n"){
                $path = "../../include/data/music/songcover/".$songID;
                //$type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
                return $base64;
                exit;    
            }else{
                if (!file_exists("../../include/data/music/songcover/small/".$songID)){
                    $img = resizeImageFromHDD("../../include/data/music/songcover/".$songID, 250, 250);
                    imagejpeg($img, "../../include/data/music/songcover/small/".$songID);
                    imagedestroy($img);
                    $path = "../../include/data/music/songcover/small/".$songID;
                    //$type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
                    return $base64;
                    exit;   
                }else{
                    $path = "../../include/data/music/songcover/small/".$songID;
                    //$type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
                    return $base64;
                    exit;       
                }
                exit;    
            }
        }else{
            return "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBkPSJNMCAwaDI0djI0aC0yNHoiIGZpbGw9ImdyZXkiLz48cGF0aCBkPSJNNCA0aDd2LTJoLTdjLTEuMSAwLTIgLjktMiAydjdoMnYtN3ptNiA5bC00IDVoMTJsLTMtNC0yLjAzIDIuNzEtMi45Ny0zLjcxem03LTQuNWMwLS44My0uNjctMS41LTEuNS0xLjVzLTEuNS42Ny0xLjUgMS41LjY3IDEuNSAxLjUgMS41IDEuNS0uNjcgMS41LTEuNXptMy02LjVoLTd2Mmg3djdoMnYtN2MwLTEuMS0uOS0yLTItMnptMCAxOGgtN3YyaDdjMS4xIDAgMi0uOSAyLTJ2LTdoLTJ2N3ptLTE2LTdoLTJ2N2MwIDEuMS45IDIgMiAyaDd2LTJoLTd2LTd6IiBmaWxsPSIjQzBDMEMwIi8+PC9zdmc+";
            exit;
        }
}

function grabPlaylistImage ($playlistID){

        if (file_exists("../../include/data/music/playlistcover/".$playlistID)){
             if (!file_exists("../../include/data/music/playlistcover/small/".$playlistID)){
                    $img = resizeImageFromHDD("../../include/data/music/playlistcover/".$playlistID, 250, 250);
                    imagejpeg($img, "../../include/data/music/playlistcover/small/".$playlistID);
                    imagedestroy($img);
                    $path = "../../include/data/music/playlistcover/small/".$playlistID;
                    //$type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
                    return $base64;
                    exit;
                }else{
                    $path = "../../include/data/music/playlistcover/small/".$playlistID;
                    //$type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/jpeg;base64,' . base64_encode($data);
                    return $base64;
                    exit;
                }
                exit;
        }else{
            return "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBkPSJNMCAwaDI0djI0aC0yNHoiIGZpbGw9ImdyZXkiLz48cGF0aCBkPSJNNCA0aDd2LTJoLTdjLTEuMSAwLTIgLjktMiAydjdoMnYtN3ptNiA5bC00IDVoMTJsLTMtNC0yLjAzIDIuNzEtMi45Ny0zLjcxem03LTQuNWMwLS44My0uNjctMS41LTEuNS0xLjVzLTEuNS42Ny0xLjUgMS41LjY3IDEuNSAxLjUgMS41IDEuNS0uNjcgMS41LTEuNXptMy02LjVoLTd2Mmg3djdoMnYtN2MwLTEuMS0uOS0yLTItMnptMCAxOGgtN3YyaDdjMS4xIDAgMi0uOSAyLTJ2LTdoLTJ2N3ptLTE2LTdoLTJ2N2MwIDEuMS45IDIgMiAyaDd2LTJoLTd2LTd6IiBmaWxsPSIjQzBDMEMwIi8+PC9zdmc+";
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