<?php 
require('../php/auth.php');
header("Content-type: text/css");
?>
<?php if ($_SESSION['admin'] == 1){ ?>
.adminOnly {
    display: block !important;       
}
<?php } ?>