<?php 
if (isset($_GET['p']) && $_GET['p'] == "me"){
    require('../php/auth.php');
    require('../php/database.php');
    $id = $_SESSION['userid'];
    $imageURL = mysql_fetch_array(mysql_query("SELECT `image` from user WHERE `id`='$id' LIMIT 1"))['image'];
    $imageURL = mysql_fetch_array(mysql_query("SELECT `path` FROM image WHERE `id`='$imageURL' LIMIT 1"))['path'];
    header("Content-type: image/png");
    echo resizeImage("user/".$imageURL,60, 60);
    exit;
}

function resizeImage($filename, $newwidth, $newheight){
    list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);    
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return imagejpeg($thumb);
}
?>