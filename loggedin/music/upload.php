<?php
require('../../include/php/auth.php');
require('../../include/php/database.php'); 
if (isset($_GET['database'])){
    if (isset($_GET['new'])){
        $title = $_POST['title'];
        $album = $_POST['album'];
        $genere = $_POST['genere'];
        $interpreter = $_POST['interpreter'];
        $path = $interpreter." - ".$title.".mp4";
        if ($_FILES['video']['size'] > 0 && $_FILES['video']['type'] == "audio/mp3"){
            $path = $interpreter." - ".$title.".mp3";    
        }
        if ($_FILES['video']['size'] > 0 && !file_exists("/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/".$path)){
            move_uploaded_file ($_FILES['video']['tmp_name'], "/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/".$path);
        }else
        if ($_FILES['video']['size'] > 0 && file_exists("/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/".$path)){
            header("HTTP/1.0 416 Requested range not satisfiable");
            exit;
        }
        if (!file_exists("/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/".$path)) {
            header("HTTP/1.0 416 Requested range not satisfiable");
            exit;
        } 
        $cover = $_FILES['cover']['tmp_name'];
        mysql_query("INSERT INTO `music`(`title`, `album`, `interpreter`, `genere`, `path`) VALUES (\"$title\",\"$album\",\"$interpreter\",\"$genere\",\"$path\")");
        if ($_FILES['cover']['size'] > 0){
            move_uploaded_file ($_FILES['cover']['tmp_name'], "/media/NAS/RaspberryPi/01/Apache/bepr/v3/include/data/music/songcover/".mysql_insert_id());
        }    
    }
}
?>