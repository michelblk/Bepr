<?php require('../../include/php/auth.php');
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
if (!isset($_GET['a'])){
    header('LOCATION: ?a=recent');
} ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Musik | Bepr</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <meta name="viewport" content="width=980, initial-scale=1" />
        <meta name="theme-color" content="#0370B8" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
        <link href="../../include/css/main.php" type="text/css" rel="stylesheet" />
        <link href="../../include/css/page/music.css" type="text/css" rel="stylesheet" />
        <script src="../../include/js/jQuery.js"></script>
        <script src="../../include/js/page/music.js"></script>
    </head>
    <body>
        <div id="nav-sidebar">
            <div id="sidebar-content">
                <div id="logo"></div>
                <nav>
                    <dl>
                        <dt>Home</dt>
                        <div>
                            <dd>
                                <a href="../index.php">Home</a>
                            </dd>
                        </div>
                        <dt>Musik</dt>
                        <div>
                            <dd>
                                <a href="../music/?a=all">Jede Musik</a>
                                <a href="../music/?a=genre">Genre</a>
                                <a href="../music/?a=playlist">Playlisten</a>
                                <a href="../music/?a=upload" class="adminOnly">Hochladen</a>
                            </dd>
                        </div>
                        <dt>Videos</dt>
                        <div>
                            <dd>
                                <a href="../videos/?a=search" data-a="search">Suchen</a>
                                <a href="../videos/?a=recent" data-a="recent">Neu hinzugef&uuml;gt</a>
                                <a href="../videos/?a=movies" data-a="movies">Filme</a>
                                <a href="../videos/?a=series" data-a="series">Serien</a>
                                <a href="../videos/?a=add" data-a="add" class="adminOnly">Hinzuf&uuml;gen</a>
                            </dd>
                        </div>
                        <dt>Pr&auml;sentationen</dt>
                        <div>
                            <dd>
                                <a href="../presentation/?a=create" data-a="create">Erstellen</a>
                                <a href="../presentation/?a=edit" data-a="edit">Bearbeiten</a>
                                <a href="../presentation/?a=present" data-a="present">Pr&auml;sentieren</a>
                            </dd>
                        </div>
                        <dt>Dateien</dt>
                        <div>
                            <dd>
                                <a href="../nas/?a=home" data-a="home">Eigene</a>
                                <a href="../nas/?a=groups" data-a="groups">Gruppen</a>
                            </dd>
                        </div>
                        <dt>Einstellungen</dt>
                        <div>
                            <dd>
                                <a href="../settings/?a=account" data-a="account">Account</a>
                                <a href="../settings/?a=privacy" data-a="privacy">Datenschutz</a>
                                <a href="../settings/?a=safety" data-a="safety">Sicherheit</a>
                            </dd>
                        </div>
                    </dl>
                    <div id="userinfo">
                        <div id="image"></div>
                        <div id="name"><?php echo $_SESSION['firstname']." ".$_SESSION['lastname']; ?></div>
                        <div id="logout"><a href="../logout.php">Abmelden</a></div>
                    </div>
                </nav>
            </div>
        </div>
        <main>
            <div id="wrapper">
                <div id="loading"></div>
                <?php if ($_GET['a'] == "all"){ ?>
                <div data-a="recent" data-page="music" class="non-durable-content">
                    <script><?php if(isset($_GET['sortBy'])){echo "grabmusic.orderBy('".$_GET['sortBy']."', '".$_GET['sortOrder']."');";}else{echo "grabmusic.orderBy('id', 'DESC');";} if (isset($_GET['songID'])){echo "musicplayer.play(\"".$_GET['songID']."\");";}?></script>
                    <div id="allSongs"></div>
                </div> 
                <?php } ?>
                <div data-page="music" class="durable-content">
                    <div id="loadingbar">
                        <div id="loadingbar-status"></div>
                    </div>
                    <div id="musicplayer">
                        <video id="musicplayer-content" width="100%" height="100%" controls>
                            <track kind="subtitle" id="music-subtitle" srclang="en" label="English subtitles" />
                        </video>
                        <div id="musicplayer-controls">  <!-- Design info http://yensdesign.com/tutorials/musicplayer/ -->
                            <div id="music-playpause"></div>
                            <div id="music-previous"></div>
                            <div id="music-info"></div>
                            <div id="music-next"></div>
                            <div id="music-volume"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>