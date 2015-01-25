<?php
exit; /////////////////////////////////////////////////////////////////////////////////////////////////////
header('Content-type: text/text;charset=utf-8');
require('../../include/php/database.php');
$query = mysql_query("SELECT * FROM `music`");
while($musik = mysql_fetch_array($query)) {
    if ($musik['genere'] != "") {
        $genres = explode(",", $musik['genere']);
        $newgenres = array();
        foreach($genres as $key => $value){
            $gQuery = mysql_query("SELECT `gID` FROM `music-genre` WHERE `name` LIKE '$value'");
            echo "val: ".$value;
            if (mysql_num_rows($gQuery) < 1){
                mysql_query("INSERT INTO `music-genre`(`name`) VALUES ('".$value."')");
                $replacement = mysql_insert_id();
            }else{
                $replacement = mysql_fetch_array($gQuery)['gID'];
            }
            $newgenres[] = $replacement;
        }
        $result = implode(",", $newgenres);
        echo " => IDs: ".$result."\n";
        mysql_query("UPDATE `music` SET `genere`='".$result."' WHERE `id` LIKE '".$musik['id']."'");
    }else{
        echo "//\n";
    }
}
?>