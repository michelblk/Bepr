<?php 
require('../../include/php/auth.php');
require('../../include/php/database.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr Presentations</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <meta name="viewport" content="width=990, initial-scale=0" />
        <link rel="shortcut icon" href="../../favicon.ico" />
        <link href="../../include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="../../include/css/main.css" type="text/css" rel="stylesheet" />
        <link href="../../include/css/page/chat.css" type="text/css" rel="stylesheet" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script language="javascript" type="text/javascript" src="../../include/js/menu.js"></script>
    </head>
    <body>
        <header>
            <div id="header-wrapper">
                <div id="header-title">Chat</div>
                <div id="chapter-title"><?php echo ucfirst($_GET['a']); ?></div>
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
            <div id="wrapper">
                This page is still in process. Please try again later.
                <hr />   
            </div>
        </main>
        <div id="mask"></div>
    </body>
</html>
