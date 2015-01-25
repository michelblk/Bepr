<?php require('../../include/php/auth.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Videos | Bepr</title>
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
        <link href="../../include/css/page/videos.css" type="text/css" rel="stylesheet" />
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
                                <a href="../videos/?a=search">Suchen</a>
                                <a href="../videos/?a=recent">Neu hinzugef&uuml;gt</a>
                                <a href="../videos/?a=movies">Filme</a>
                                <a href="../videos/?a=series">Serien</a>
                                <a href="../videos/?a=add" class="adminOnly">Hinzuf&uuml;gen</a>
                            </dd>
                        </div>
                        <dt>Pr&auml;sentationen</dt>
                        <div>
                            <dd>
                                <a href="../presentation/?a=create">Erstellen</a>
                                <a href="../presentation/?a=edit">Bearbeiten</a>
                                <a href="../presentation/?a=present">Pr&auml;sentieren</a>
                            </dd>
                        </div>
                        <dt>Dateien</dt>
                        <div>
                            <dd>
                                <a href="../nas/?a=home">Eigene</a>
                                <a href="../nas/?a=groups">Gruppen</a>
                            </dd>
                        </div>
                        <dt>Einstellungen</dt>
                        <div>
                            <dd>
                                <a href="../settings/?a=account">Account</a>
                                <a href="../settings/?a=privacy">Datenschutz</a>
                                <a href="../settings/?a=safety">Sicherheit</a>
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

        </main>
    </body>
</html>