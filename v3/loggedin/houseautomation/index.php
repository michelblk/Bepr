<?php require('../../include/php/auth.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr Hausautomatisierung</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <!--<meta name="viewport" content="width=980, initial-scale=1" />-->
        <meta name="theme-color" content="#34495e" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
        <link href="../../include/css/page/houseautomation.css" type="text/css" rel="stylesheet" />
        <script language="javascript" type="text/javascript" src="../../include/js/jQuery.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/jQuery.cookie.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/main.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/page/houseautomation.js"></script>
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
                                            <li><a class="menu-icon menu-music-new-icon" href="../music/?a=new">Neu</a></li>
                                            <li><a class="menu-icon menu-music-all-icon" href="../music/?a=all">Gesamt</a></li>
                                            <li><a class="menu-icon menu-music-playlists-icon" href="../music/?a=playlists">Playlisten</a></li>
                                            <li><a class="menu-icon menu-music-suggest-icon" href="../music/?a=suggest">Vorschlagen</a></li>
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
            </ul>
        </header>
        <main>
            <div id="wrapper">
                <div id="achtung" style="text-align: center; font-size: 1.3em;">Zur Zeit nur begrenzt verf&uuml;gbar!<br /><br />Achtung: Diese Funktion ist noch nicht über SSL verfügbar!</div>
                <iframe src="http://bepr.ddns.net/Service/mqtt/?md5=c8fc9965b552857c94dd9fa0a8095f66" style="margin-left: 25%; width: 50%; height: 300px; border: 1px solid grey;"></iframe>
            </div>
        </main>
    </body>
</html>
