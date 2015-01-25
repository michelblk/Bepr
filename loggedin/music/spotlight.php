<?php 
header('Content-Type: application/json; charset=utf-8');
$background = "//static.guim.co.uk/sys-images/Guardian/Pix/pictures/2015/3/5/1425559092870/Years--Years.-009.jpg";
$bgcolor = "#989BA4";
$text = "Years & Years";
$interpreter = "Years %26 Years";
$album = "";
$song = "";
$link = "music/?a=search&q=".$interpreter;

$spotlight = array("text" => $text, "background" => $background, "backgroundcolor" => $bgcolor, "interpreter" => $interpreter, "songtitle" => $song, "link" => $link, "album" => $album);
echo json_encode($spotlight);
?>