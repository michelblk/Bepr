<?php 
session_start();
if(isset($_COOKIE["remain"]) && $_COOKIE["remain"] != "" && (!isset($_GET['login']) || $_GET['login'] == "noLogin")) {
	if (isset($_GET['ref'])){
		$ref = "login.php?remain&ref=".$_GET['ref'];
	}else{
		$ref = "login.php?remain";
	}
    header("LOCATION: $ref");
    exit;
}
if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == FALSE){?>   
<!DOCTYPE html>
<html>
    <head>
        <title>Bepr</title>
        <meta charset="utf-8" />
        <meta name="author" content="Michel Blank" />
        <meta name="keywords" content="Bepr" />
        <meta name="description" content="Be productive!" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#0370B8" />
        <link rel="shortcut icon" href="favicon.ico" />
        <link href="include/css/roboto.css" rel="stylesheet" type="text/css" />
        <link href="include/css/page/login.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <div id="form">
            <main id="content">
                <div id="sign-in" class="login-section">
                    <?php 
                    if (isset($_GET['login']) && $_GET['login'] == "failed"){
                        echo "<div id=\"error\">Login fehlgeschlagen. Bitte versuchen Sie es erneut.</div>";
                    }else
                    if (isset($_GET['login']) && $_GET['login'] == "expired"){
                        echo "<div id=\"error\">Dein Login ist abgelaufen. Bitte melde dich erneut an.</div>";
                    }else
                    if (isset($_GET['login']) && $_GET['login'] == "disabled"){
                        echo "<div id=\"error\">Dein Account wurde deaktiviert! Kontaktiere den Administrator!</div>";    
                    }else
                    if (isset($_GET['login']) && $_GET['login'] == "noLogin"){
                        echo "<div id=\"error\">Du bist nicht angemeldet oder dein Login ist abgelaufen.</div>";
                    }
                    ?>
                    <div style="font-size: 2em; padding-left: 10px;">Bepr Anmeldung</div>
                    <form style="margin-top: 10px;" method="POST" action="login.php?a=sign-in<?php if (isset($_GET['ref'])){echo "&ref=".$_GET['ref'];}?>" id="login-form">
                        <fieldset id="fieldset">
                            <input type="email" name="email" placeholder="E-mail" class="input" id="inputmail" required autofocus />
                            <input type="password" name="password" placeholder="Passwort" class="input" id="inputpassword" required pattern=".{5,}" title="F&uuml;nf oder mehr Buchstaben!" />
                        </fieldset>
                        <div style="margin-left: 10px; margin-top: 10px; float: left; height: 36px; line-height: 36px;">
                            <label id="checkbox">
                                <input type="checkbox" name="remain" value="remain" id="remain-checkbox"/>
                                <span></span>
                            </label>
                            <label id="checkbox-label" for="remain-checkbox">Angemeldet bleiben</label>
                        </div> 
                        <input type="submit" value="Anmelden" id="submit" style="float: right; margin-right: 10px;"/>
                    </form>
                </div>
            </main>
        </div>
    </body>
</html>
<?}else{
    header('LOCATION: loggedin/');
}
?>