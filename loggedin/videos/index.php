<?php
require('../../include/php/auth.php');
require('../../include/php/database.php');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr Videos</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr, Be, productive" />
        <meta name="description" content="Sei Produktiv." />
        <meta name="viewport" content="width=990, initial-scale=0" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
        <link href="../../include/css/page/videos.css" type="text/css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/menu.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/page/videos.js"></script>
    </head>
    <body>
        <header>
            <div id="header-wrapper">
                <div id="header-title">Videos</div>
                <div id="chapter-title"><?php echo ucfirst($_GET['a']); ?></div>
                <?php if (isset($_GET['title'])){ ?>
                <div class="page-title"><?php echo ucfirst($_GET['interpreter']); ?></div>
                <?php } ?>
                <div id="account">
                    <div id="account-logout"><a href="../logout.php">Logout</a></div>
                    <div id="account-image"></div>
                    <div id="account-name"><?php echo $_SESSION['firstname']; ?></div>
                </div>
            </div>
        </header>
        <nav>
            <button id="navigation-button"></button>
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
                            <a href="../music/?a=search">Search</a>
                            <a href="../music/?a=recent">Recent</a>
                            <a href="../music/?a=playlist">Playlist</a>
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
        <?php if (isset($_GET['a']) && $_GET['a'] == "search"){ ?>
            <script> <?php if (isset($_GET['q'])){echo "search('".$_GET['q']."');";} ?></script>
            <div id="search-input">
                <input type="text" name="search" placeholder="e.g. Warehouse 13" id="search-box" autofocus />
                <input type="image" src="../../include/image/SVG/search.svg" id="search-button"/>
            </div>
            <div id="search-results">
                <!-- Javascript -->
            </div> 
            <video id="video" width="100%" controls></video>  
            <?php }else
            if (isset($_GET['a']) && $_GET['a'] == "recent"){ ?>
            <script> recent(); <?php if (isset($_GET['videoPath']) && isset($_GET['title'])){echo "playVideo('".base64_decode($_GET['videoPath'])."', '".$_GET['title']."');";}?> </script>
            <div id="search-results">
                <!-- Javascript -->
            </div>
            <div onclick="recent()" style="text-align: center; clear: both; padding-bottom: 20px; padding-top: 10px; font-site: 1.2em;" id="loadmore">Load more</div>
            <video id="video" width="100%" controls>
            </video>    
            <?php }else
            if (isset($_GET['a']) && $_GET['a'] == "upload"){ ?>
                
            <?php } ?>
        </main>

        <div id="mask"></div>
    </body>
</html>
