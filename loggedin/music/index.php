<?php
require('../../include/php/auth.php');
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache");
if (!isset($_GET['a'])){
    header('LOCATION: ?a=search');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo ucfirst($_GET['a'])." | "; ?>Bepr Music</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <meta name="viewport" content="width=990, initial-scale=0" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
        <link href="../../include/css/page/music.css" type="text/css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/menu.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/page/music.js"></script>
    </head>
    <body>
        <header>
            <button id="navigation-button"></button>
            <div id="header-wrapper">
                <div id="header-title">Music</div>
                <div id="chapter-title"><?php echo ucfirst($_GET['a']); ?></div>
                <div class="page-title" id="interpreter"></div>   
                <div class="page-title" id="title"></div>
                <div id="account">
                    <div id="account-logout"><a href="../logout.php">Logout</a></div>
                    <div id="account-image"></div>
                    <div id="account-name"><?php echo $_SESSION['firstname']; ?></div>
                </div>
            </div>
        </header>
        <nav>
            <div id="side-nav">
                <div id="logo">
                    <img src="../../include/image/logo.svg"/>
                </div>
                <dl>
                    <dt>Home</dt>
                    <div>
                        <dd>
                            <a href="../index.php">Home</a>
                        </dd>
                    </div>
                    <dt>Chat</dt>
                    <div>
                        <dd>
                            <a href="../chat/?a=new" style="font-weight: 700;">New</a>
                        </dd>
                    </div>
                    <dt>Presentations</dt>
                    <div>
                        <dd>
                            <a href="../presentation/?a=create">Create</a>
                            <a href="../presentation/?a=edit">Edit</a>
                            <a href="../presentation/?a=present">Present</a>
                        </dd>
                    </div>
                    <dt>Music</dt>
                    <div>
                        <dd>
                            <a href="../music/?a=search" onclick="goTo('search');return false;">Search</a>
                            <a href="../music/?a=recent" onclick="goTo('recent');return false;">Recent</a>
                            <a href="../music/?a=playlist" onclick="goTo('playlist');return false;">Playlist</a>
                            <a href="../music/?a=upload">Upload</a>
                        </dd>
                    </div>
                    <dt>Videos</dt>
                    <div>
                        <dd>
                            <a href="../videos/?a=search">Search</a>
                            <a href="../videos/?a=recent">Recent</a>
                            <a href="../videos/?a=movies">Movies</a>
                            <a href="../videos/?a=series">Series</a>
                        </dd>
                    </div>
                </dl>
            </div>
        </nav>

        <main>
            <div id="wrapper">
                <div style="text-align: center; font-size: 1.2em; font-weight: 300; cursor: pointer;" onclick="location.href='../../v3/loggedin/music/?a=new'">Diese Bepr Version wird bald nicht mehr verfügbar sein.<br/>Bitte wechseln Sie zur Version drei, um alle neuen Titel (<span id="newsongssinceversionthree">NN</span>) anhören zu können.</div>
                <?php if (isset($_GET['a']) && $_GET['a'] == "search"){ ?>
                <div data-a="search" class="music-a"> WARNING: STILL IN PROCESS
                <script> <?php if (isset($_GET['q'])){echo "search('".$_GET['q']."');";} ?></script>
                    <?php if (isset($_GET['songID'])){
                        $query = mysql_fetch_array(mysql_query("SELECT * FROM music WHERE `id` LIKE '".base64_decode($_GET['songID'])."'"));
                        $songInterpeter = $query['interpreter'];
                        $songTitle = $query['title'];
                        echo "<script>playSong(\"".$songInterpeter."\", \"".$songTitle."\", \"".$_GET['songID']."\");</script>";}
                    ?>
                    <div id="search-input">
                        <input type="text" name="search" placeholder="e.g. Yellow Flicker Beat" id="search-box" autofocus />
                        <input type="image" src="../../include/image/SVG/search.svg" id="search-button"/>
                    </div>
                    <div id="search-results">
                        <!-- Javascript -->
                    </div>
                    <div style="visibility: none; clear: both;" id="clear"><!-- To be sure, that the wrapper is also on the very bottom --></div> 
                </div>
                <?php }else
                if (isset($_GET['a']) && $_GET['a'] == "recent"){ ?>
                <div data-a="recent" class="music-a">
                     <script> recent(); <?php if (isset($_GET['songID'])){
                        $query = mysql_fetch_array(mysql_query("SELECT * FROM music WHERE `id` LIKE '".base64_decode($_GET['songID'])."'"));
                        $songInterpeter = $query['interpreter'];
                        $songTitle = $query['title'];
                        echo "playSong(\"".$songInterpeter."\", \"".$songTitle."\", \"".$_GET['songID']."\");";}
                    ?> </script> 
                    <div id="search-results">
                        <!-- Javascript -->
                    </div>
                    <div onclick="recent()" style="text-align: center; clear: both; padding-bottom: 20px; padding-top: 10px; font-site: 1.2em;" id="loadmore">Load more</div>
                </div>  
                <?php }else
                if (isset($_GET['a']) && $_GET['a'] == "upload"){ ?>
                <div data-a="upload" class="music-a">
                <script type="text/javascript" src="../../include/js/page/music-upload.js"></script>
                    <div id="upload-warning">
                        <span class="bold">You don't have the permission to upload music directly from this page.</span>
                        You have to upload the correct named file into "G:/Musik/Musikvideos/" on your network drive.
                        When you've done that, you can create a database-entry here.<br />
                        The file format must be <span class="bold">mp4</span>! Path is case-sensitive!
                    </div>
                    <form action="javascript:uploadtodatabase()" method="post" id="new-database-entry-form" enctype="multipart/form-data">
                        <div class="icon" id="title-icon"></div><input type="text" name="title" placeholder="Song title" id="title" autocomplete="off" required="required" /><br />
                        <div class="icon" id="album-icon"></div><input type="text" name="album" placeholder="Album" autocomplete="on" /><br />
                        <div class="icon" id="interpreter-icon"></div><input type="text" name="interpreter" placeholder="Interpreter" id="interpreter" autocomplete="on" required="required" /><br />
                        <div class="icon" id="genere-icon"></div><input type="text" name="genere" placeholder="Genere" id="genere" autocomplete="on" /><br />
                        <div class="icon" id="cover-icon"></div><div><input type="hidden" name="MAX_FILE_SIZE" value="2000" /><input type="file" name="cover" id="cover" style="margin-bottom: 0px;" accept="image/jpeg,image/png,image/gif" /></div><br /> 
                        <div class="icon" id="path-icon"></div><input type="text" name="path" placeholder="mp4 path" id="path" disabled /><br />
                        <div class="icon" id="subtitle-icon"></div><input type="text" name="subtitle" placeholder="Subtitles are not supported, yet!" id="sub-path" disabled /><br />   
                        <input type="image" src="../../include/image/SVG/ic_send_24px.svg" alt="Submit" id="submit-button" />
                        <?php if ($_SESSION['userid'] == 1){
                            echo "<input type=\"file\" name=\"video\" id=\"video\" style=\"margin-bottom: 0px;\" accept=\"video/mp4,audio/mp3\" />";
                        } ?>
                    </form>
                    <div id="upload-error-status" class="status"><img src="../../include/image/SVG/ic_announcement_24px_white.svg" height="50px" class="status-icon" />Something went wrong! Check the filename and internet connection!</div>
                    <div id="upload-success-status" class="status"><img src="../../include/image/SVG/ic_cloud_done_24px_white.svg" height="50px" class="status-icon" />The track has been added to the music database successfully.</div>
                 </div>
                <?php }else
                if (isset($_GET['a']) && $_GET['a'] == "playlist"){ ?>
                <div data-a="playlist" class="music-a">
                    <script><?php if (isset($_GET['playlistID'])){echo "playlist('".$_GET['playlistID']."', '".mysql_fetch_array(mysql_query("SELECT name FROM `music-playlist` WHERE `id` LIKE '".base64_decode($_GET['playlistID'])."' LIMIT 1"))['name']."');";}else{echo "playlists();";}?></script>
                    <div style="font-weight: 600; font-size: 1.4em;">Still in process</div>
                    <div id="new-playlist"></div>
                    <div id="playlists">
                        <!-- Javascript -->
                    </div>
                    <div id="playlist">
                        <!-- Javascript -->
                    </div>
                </div>    
                <?php } 
                if (!isset($_GET['audio']) && $_GET['audio'] != "only"){ ?>
                <video id="musicvideo" width="100%" height="100%" controls>
                    <track kind="subtitle" id="subtitle" srclang="en" label="English subtitles" />
                </video>
               <?php }else{ // Audio only?>
               <audio id="musicvideo" width="100%" height="100%" controls></audio>
               <?php } ?>
            </div>
        </main>

        <div id="loading-bar"><div id="loading-bar-status"></div></div>
        <div id="info" class="status"><img height="50px" id="info-image" class="status-icon" /><span id="info-message"></span></div>
        <div id="error" class="status"><img src="../../include/image/SVG/ic_announcement_24px_white.svg" height="50px" class="status-icon" />Something went wrong! Check your internet connection! [<span id="error-code"></span>]</div>
        <div id="mask"></div>
    </body>
</html>
