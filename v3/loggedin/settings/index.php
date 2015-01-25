<?php require('../../include/php/auth.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr Einstellungen</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <!--<meta name="viewport" content="width=980, initial-scale=1" />-->
        <meta name="theme-color" content="#34495e" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
        <link href="../../include/css/page/settings.css" type="text/css" rel="stylesheet" />
        <script language="javascript" type="text/javascript" src="../../include/js/jQuery.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/jQuery.cookie.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/main.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/page/settings.js"></script>
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
                <div id="achtung" style="text-align: center; font-size: 1.3em;">Zur Zeit nur begrenzt verf&uuml;gbar!</div>
                <div class="setting">
                    <div class="setting-label">Angemeldet bleiben</div>
                    <div class="setting-content">
                        <div class="onoffswitch">
                            <input type="checkbox" name="stayloggedin" class="onoffswitch-checkbox" id="stayloggedin" onchange="setting.stayloggedin();" />
                            <label class="onoffswitch-label" for="stayloggedin"></label>
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">"Angemeldet bleiben"-ID zur&uuml;cksetzen</div>
                    <div class="setting-content">
                        <div class="yesbutton">
                            <input type="button" name="resetremainid" class="yesbutton-button" id="resetremaindid" value="Ja!" onclick="setting.resetremainid();" disabled />
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">Namen &auml;ndern</div>
                    <div class="setting-content">
                        <div class="yesbutton">
                            <input type="button" name="changename" class="yesbutton-button" id="changename" value="Ja!" onclick="setting.changename();" disabled />
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">E-Mail &auml;ndern</div>
                    <div class="setting-content">
                        <div class="yesbutton">
                            <input type="button" name="changeemail" class="yesbutton-button" id="changeemail" value="Ja!" onclick="setting.changeemail();" disabled />
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">Passwort &auml;ndern</div>
                    <div class="setting-content">
                        <div class="yesbutton">
                            <input type="button" name="changepassword" class="yesbutton-button" id="changepassword" value="Ja!" onclick="setting.changepassword();" disabled />
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">Musik Stream ohne Abstand</div>
                    <div class="setting-content">
                        <div class="onoffswitch">
                            <input type="checkbox" name="musicstreamnomargin" class="onoffswitch-checkbox" id="musicstreamnomargin" onchange="setting.musicstreamnomargin();" />
                            <label class="onoffswitch-label" for="musicstreamnomargin"></label>
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">Musikverlauf & -Warteschlange l&ouml;schen</div>
                    <div class="setting-content">
                        <div class="yesbutton">
                            <input type="button" name="resetmusicqueue" class="yesbutton-button" id="resetmusicqueue" value="Ja!" onclick="setting.resetmusicqueue();" />
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">Musik Playlisten l&ouml;schen</div>
                    <div class="setting-content">
                        <div class="yesbutton">
                            <input type="button" name="deletemusicplaylists" class="yesbutton-button" id="deletemusicplaylists" value="Ja!" onclick="setting.deletemusicplaylists();" disabled />
                        </div>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-label">Account deaktivieren</div>
                    <div class="setting-content">
                        <div class="onoffswitch">
                            <input type="checkbox" name="deactiveaccount" class="onoffswitch-checkbox" id="deactiveaccountswitch" onchange="setting.deactivateaccount();" disabled />
                            <label class="onoffswitch-label" for="deactiveaccountswitch"></label>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
