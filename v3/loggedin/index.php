<?php require('../include/php/auth.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <!--<meta name="viewport" content="width=980, initial-scale=1" />-->
        <meta name="theme-color" content="#34495e" />
        <link rel="shortcut icon" href="../favicon.ico" />
        <link href="../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../include/css/main.css" type="text/css" rel="stylesheet" />
        <script language="javascript" type="text/javascript" src="../include/js/jQuery.js"></script>
        <script language="javascript" type="text/javascript" src="../include/js/main.js"></script>
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
                                    <a class="menu-icon menu-home-icon" href="index.php">Home</a>
                                </li>
                                <li>
                                    <a class="menu-icon menu-music-icon" data-submenuid="menu-musicsub">
                                        Musik
                                    </a>
                                    <ul class="submenu" id="menu-musicsub">
                                            <li><a class="menu-icon menu-music-new-icon" href="music/?a=new">Neu</a></li>
                                            <li><a class="menu-icon menu-music-all-icon" href="music/?a=all">Gesamt</a></li>
                                            <li><a class="menu-icon menu-music-playlists-icon" href="music/?a=playlists">Playlisten</a></li>
                                            <li><a class="menu-icon menu-music-suggest-icon"  href="music/?a=suggest">Vorschlagen</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="menu-icon menu-houseautomation-icon" href="houseautomation/">Hausautomatisierung</a>
                                </li>
                                <li>
                                    <a class="menu-icon menu-settings-icon" href="settings/">Einstellungen</a>
                                </li>
                                <li>
                                    <a class="menu-icon menu-logout-icon" href="logout.php">Abmelden</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </li>
            </ul>
        </header>
        <main>
            <div id="wrapper">
                <center>Diese Bepr Version befindet sich derzeit in der Entwicklungsphase.
                    <div style="background-image: url('../include/image/music-ad.png'); background-repeat: no-repeat; background-position: center; background-size: cover; width: 400px; height: 400px; position: absolute; z-index: -1; border-radius: 100%; left: calc(50% - 200px); margin-top: 50px; cursor: pointer;" onclick="location.href='music/?a=new'"></div>
                    <br />
                </center>
            </div>
        </main>
    </body>
</html>
