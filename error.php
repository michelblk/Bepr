<?php 
if (isset($_GET['a'])){
    if ($_GET['a'] == "nopermission"){
        echo "<h1>Forbidden!</h1>";
        exit;
    }
}
?>