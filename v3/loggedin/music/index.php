<?php require('../../include/php/auth.php'); ?>
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
    <script type="text/javascript" src="../../include/js/jQuery.js"></script>
    <script type="text/javascript" src="../../include/js/main.js"></script>
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
                <a class="menu-icon fast-submenu-switch menu-music-new-icon" href="javascript:gotosubpage('new', 'Neu | Bepr Musik');">Neu</a>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-all-icon" href="javascript:gotosubpage('all', 'Gesamt | Bepr Musik');">Gesamt</a>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-playlists-icon" href="javascript:gotosubpage('playlists', 'Playlisten | Bepr Musik');">Playlisten</a>
            </li>
            <li>
                <a class="menu-icon fast-submenu-switch menu-music-suggest-icon" href="javascript:gotosubpage('suggest', 'Vorschlagen | Bepr Musik');">Vorschlagen</a>
            </li>
            <input type="search" placeholder="Suchen (Momentan keine Alben m&ouml;glich)" id="MusicSearchInput" autocomplete="on" min="2" />
        </ul>
    </header>
    <main>
        <div id="loadingbar">
            <div id="loadingbar-status"></div>
        </div>
        <div id="wrapper">

            <div data-persistent="yes">
                <div data-site="music">
                    <link href="../../include/css/page/music.css" type="text/css" rel="stylesheet" />
                    <script type="text/javascript" src="../../include/js/page/music.js"></script>
                    <!--<script type="text/javascript" src="https://www.gstatic.com/cv/js/sender/v1/cast_sender.js"></script> -->
                    <!-- <script type="text/javascript" src="../../include/js/page/music-chromecast.js"></script> -->
                    <!-- Music Player -->
                    <div id="musicplayer" data-videoavailable="false">
                        <div id="shield"></div>
                        <div id="musicvideo-container">
                            <video id="musicplayer-video" width="100%" height="100%"></video>
                        </div>
                        <div id="musicplayer-left">
                            <div id="musicplayer-cover"><div id="musicplayer-cover-expandicon"></div></div>
                            <div id="musicplayer-current">
                                <div id="musicplayer-current-interpreter"></div>
                                <div id="musicplayer-current-album"></div>
                                <div id="musicplayer-current-title"></div>
                                <div id="musicplayer-current-time"><span data-class="now"><span data-class="minute"></span><span data-class="second"></span></span><span data-class="total"><span data-class="minute"></span><span data-class="second"></span></span></div>
                            </div>
                        </div>
                        <div id="musicplayer-controls">
                            <div id="musicplayer-middle">
                                <div id="rewind" class="musicplayer-control last-next" onclick="playPrevious();"><svg viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" fit="" style="pointer-events: none; display: block;"><g><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" fill="white"></path></g></svg></div>
                                <div id="musicplayer-play-pause" class="musicplayer-control" data-show="play"></div>
                                <div id="forward" class="musicplayer-control last-next" onclick="playNext();"><svg viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" fit="" style="pointer-events: none; display: block; transform: rotate(180deg);"><g><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z" fill="white"></path></g></svg></div>
                            </div>
                            <div id="musicplayer-right">
                                <div id="musicplayer-fullscreen" class="musicplayer-control" onclick="MusicVideoFullscreen();"></div>
                                <div id="musicplayer-mute" class="musicplayer-control" data-show="mute"></div>
                                <div id="musicplayer-videoCrop" class="musicplayer-control" data-mode="1"></div>
                            </div>
                            <div id="musicplayer-progress-bar">
                                <span id="musicplayer-seek-bar-shadow"></span>
                                <span id="musicplayer-seek-bar-buffered"></span>
                                <input type="range" id="musicplayer-seek-bar" value="0" min="0" max="100" step="0.1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div data-persistent="no" data-a="<?php echo $_GET['a']; ?>" class='PresentPage'>
                <!-- Script -->
                <script>start = 0;<?php if ($_GET['a'] == "all"){echo "getSongs.all();";}else if ($_GET['a'] == "new"){echo "getSongs.neww();";}else if($_GET['a'] == "playlists"){echo "getPlaylist.Playlists();";}else if ($_GET['a'] == "playlist" && isset($_GET['playlistID'])) {echo "getPlaylist.Playlist('".$_GET['playlistID']."');";}
                    if (isset($_GET['songID'])) {echo "$(document).ready(function(){play(\"".$_GET['songID']."\");$(\"#musicplayer-cover\").click();});";}?></script>
                    <!-- Seitenspezifisch -->
                    <?php if ($_GET['a'] == "all"){ ?>
                        <!-- Gesamte Musik -->
                        <div id="allSongs">
                            <div class="pleasewait">Bitte warten</div>
                        </div>
                        <div id="loadmore" onclick="getSongs.all();">Mehr laden</div>
                        <?php } else if ($_GET['a'] == "new"){ ?>
                            <!-- Neue Musik -->
                            <div id="newwSongs">
                                <div class="pleasewait">Bitte warten</div>
                            </div>
                            <div id="loadmore" onclick="getSongs.neww();">Mehr laden</div>
                            <?php } else if ($_GET['a'] == "playlists"){ ?>
                                <!-- Playlists -->
                                <div id="playlists">
                                    <div id="playlist-loading">Bitte warten</div>
                                    <div id="playlists-own"><span style="padding-left: 5px;">Deine Playlisten:</span><br /></div>
                                    <div id="playlists-public"><span style="padding-left: 5px;">&Ouml;ffentliche Playlisten:</span><br /></div>
                                    <div id="playlist-clear"></div>
                                </div>
                                <?php } else if ($_GET['a'] == "playlist" && isset($_GET['playlistID'])) { ?>
                                    <!-- Playlist <?php echo $_GET['playlistID']; ?> -->
                                    <div id="playlist">
                                        <div id="playlist-header">
                                            <div id="playlist-cover"></div>
                                            <div id="playlist-info">
                                                <div id="playlist-name"></div>
                                                <div id="playlist-creator"></div>
                                                <div id="playlist-tracks"></div>
                                                <div id="playlist-public" data-public="0"></div>
                                            </div>
                                            <div id="playlist-description" data-description="0"></div>
                                        </div>
                                        <div id="playlist-loading">Bitte warten</div>
                                        <div id="playlist-content">
                                            <table id="js-playlist-content" style="width: 100%">
                                                <!-- JS -->
                                            </table>
                                        </div>
                                        <div id="playlist-clear"></div>
                                    </div>
                                    <?php } else if ($_GET['a'] == "search") { ?>
                                        <script><?php if (isset($_GET['q'])){echo "getSongs.search('".$_GET['q']."');";} ?></script>
                                        <div id="search">
                                            <div id="search-loading">Bitte warten</div>
                                            <div id="search-interpreter"></div>
                                            <div id="search-album"></div>
                                            <div id="search-song"></div>
                                            <div style="clear: both; margin-bottom: 100px;"></div>
                                        </div>
                                        <?php } else if ($_GET['a'] == "listen") { ?>
                                            <!-- Nur zuhören -->
                                            <!-- Song wurde durch Script gestartet -->
                                            <?php } ?>
                                        </div>
                                    </div>
                                </main>
                            </body>
                            </html>
