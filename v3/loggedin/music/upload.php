<?php require('../../include/php/auth.php'); require('../../include/php/database.php');

if (isset($_GET['act']) && $_SERVER['REQUEST_METHOD'] == "POST") { //upload
    header('Content-type: text/text;charset=utf-8');
    foreach($_FILES['dateien']['name'] as $key => $filename){
        $title = $_POST['title'][$key];
        $interpreter = $_POST['interpreter'][$key];
        $album = $_POST['album'][$key];
        $genre = implode(",",$_POST['genre'][$key]);
        $musicvideo = $_FILES['dateien']['tmp_name'][$key];
        $cover = $_FILES['cover']['tmp_name'][$key];
        if($_FILES['dateien']['type'][$key] == "video/mp4"){$extension = "mp4";}else{$extension = "mp3";}
        $newPath = $interpreter . " - " . $title . "." . $extension;

        if(file_exists("/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/".$newPath)){http_response_code(409);exit;} // Wenn bereits existiert
        if($_FILES['dateien']['size'][$key] < 100){http_response_code(406);exit;}

        move_uploaded_file ($musicvideo, "/media/NAS/RaspberryPi/01/groups/Musik/Musikvideos/".$newPath); // Audio/Video
        mysql_query("INSERT INTO `music`(`title`, `album`, `interpreter`, `genere`, `path`) VALUES (\"$title\",\"$album\",\"$interpreter\",\"$genre\",\"$newPath\")");
        if($_FILES['cover']['size'][$key] > 0){move_uploaded_file ($cover, "/media/NAS/RaspberryPi/01/Apache/bepr/v3/include/data/music/songcover/".mysql_insert_id());} //Cover
    }
    if(!mysql_errno()){http_response_code(204);}else{http_response_code(500);}
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bepr Musik</title>
    <meta charset="utf-8" />
    <meta name="author" content="Michel Blank" />
    <meta name="keywords" content="Bepr" />
    <meta name="description" content="Be productive!" />
    <!--<meta name="viewport" content="width=980, initial-scale=1" />-->
    <meta name="theme-color" content="#34495e" />
    <link rel="shortcut icon" href="../../favicon.ico" />
    <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
    <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
    <link href="../../include/css/page/music.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="../../include/js/jQuery.js"></script>
    <script type="text/javascript" src="../../include/js/main.js"></script>
    <script type="text/javascript" src="../../include/js/page/music-upload.js"></script>
</head>
<body>
    <header>
        <ul id="menu-main">
            <li id="menu-trigger">
                <a id="menu-trigger-icon"></a>
                <nav id="menu-wrapper">
                    <div id="menu-scroller">
                        <ul id="menu">
                            <li>
                                <a class="menu-icon menu-home-icon" href="../index.php">Home</a>
                            </li>
                            <li>
                                <a class="menu-icon menu-music-icon" data-submenuid="menu-musicsub">
                                    Musik
                                </a>
                                <ul class="submenu" id="menu-musicsub">
                                    <li><a class="menu-icon menu-music-new-icon" href="../music/?a=new" onclick="gotosubpage('new', 'Neu | Bepr Musik');return false;">Neu</a></li>
                                    <li><a class="menu-icon menu-music-all-icon" href="../music/?a=all" onclick="gotosubpage('all', 'Gesamt | Bepr Musik');return false;">Gesamt</a></li>
                                    <li><a class="menu-icon menu-music-playlists-icon" href="../music/?a=playlists" onclick="gotosubpage('playlists', 'Playlisten | Bepr Musik');return false;">Playlisten</a></li>
                                    <li><a class="menu-icon menu-music-suggest-icon" href="../music/?a=suggest" onclick="gotosubpage('suggest', 'Vorschlagen | Bepr Musik');return false;">Vorschlagen</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="menu-icon menu-houseautomation-icon" href="../houseautomation/">Hausautomatisierung</a>
                            </li>
                            <li>
                                <a class="menu-icon menu-settings-icon" href="../settings/">Einstellungen</a>
                            </li>
                            <li>
                                <a class="menu-icon menu-logout-icon" href="../logout.php">Abmelden</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-new-icon" href="index.php?a=new">Neu</a>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-all-icon" href="index.php?a=all">Gesamt</a>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-playlists-icon" href="index.php?a=playlists">Playlisten</a>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-suggest-icon" href="index.php?a=suggest">Vorschlagen</a>
            </li>
            <input type="search" placeholder="Suchen (Momentan keine Alben m&ouml;glich)" id="MusicSearchInput" autocomplete="on" min="2" />
        </ul>
    </header>
    <main>
        <div id="loadingbar">
            <div id="loadingbar-status"></div>
        </div>
        <div id="wrapper" style="padding-top: 10px;">
            <form action="javascript:uploadtodatabase()" id="file-form" method="POST" enctype="multipart/form-data">
              Musik auswählen: <input type="file" name="dateien[]" multiple accept="video/mp4,audio/mp3" />

              <table id="user-files" width="100%">
                  <tr><th>Dateiname</th><th>Titel</th><th>Interpet</th><th>Album</th><th>Genre</th><th>Cover</th></tr>
              </table>
              <input type="submit">
            </form>
        </div>
        <table style="display: none;">
            <tr id="user-files-example">
                <td><input type="text" name="filename[]" disabled/></td>
                <td><input type="text" name="title[]" required placeholder="Titel" /></td>
                <td><input type="text" name="interpreter[]" required placeholder="Interpret" /></td>
                <td><input type="text" name="album[]" placeholder="Album" /></td>
                <td><select name="genre[]" multiple>
                    <?php $query = mysql_query("SELECT * FROM `music-genre`");
                    while ($genre = mysql_fetch_array($query)) {
                        echo "<option value='".$genre['gID']."'>".$genre['name']."</option>\n";
                    }
                    ?>
                </select></td>
                <td><input type="file" name="cover[]" accept="image/jpeg,image/png,image/gif" /></td>
            </tr>
        </table>
    </main>
</body>
</html>
