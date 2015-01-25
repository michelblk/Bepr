<?php
require('../../include/php/auth.php');
require('../../include/php/database.php');
if (!isset($_GET['a'])){
    header('LOCATION: index.php');
} ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr Presentations</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="description" content="Be productive!" />
        <meta name="viewport" content="width=990, initial-scale=0" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
    </head>
    <body>        
<?php
if (isset($_GET['a']) && $_GET['a'] == "present" && isset($_GET['id'])){
    $id = base64_decode($_GET['id']);
    ?>
    <script src="../../include/js/page/presentation-present.js"></script>
    <link href="../../include/css/page/presentation-present.css" type="text/css" rel="stylesheet" />
    <div id="presentation">
        <div style="text-align: center;" id="loadingCSS">
            <svg width="500px" height="500px">
                <circle cx="250px" cy="250px" r="200px" id="loading" fill="transparent" stroke-dasharray="1259" stroke-dashoffset="0" style="stroke-dashoffset: 0px; stroke: #3F88F0; stroke-width: 1em; transition: all 0.5s ease-out; transform: rotate(-90deg); transform-origin: 250px 250px;" />
                <circle cx="250px" cy="250px" r="200px" id="loadingS" fill="transparent" stroke-dasharray="1259" stroke-dashoffset="0" style="stroke-dashoffset: 0px; stroke: #B3CEED; stroke-width: 1em; transition: all 0.5s ease-out; transform: rotate(-90deg); transform-origin: 250px 250px;" /> 
            </svg>
        </div>
    </div>
    <?php
}

?>
    </body>
</html>