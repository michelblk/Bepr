<?php
if (isset($_GET['ref'])){
	$ref = "v3/?ref=".$_GET['ref'];
}else{
	$ref = "v3/";
}
header("LOCATION: $ref");
exit;
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
session_start();
if(isset($_COOKIE["remain"]) && $_COOKIE["remain"] != "") {
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
		<meta name="theme-color" content="#03a9f4" />
		<meta http-equiv="language" content="EN" />
		<link rel="shortcut icon" href="favicon.ico" />
		<link href="include/css/roboto.css" rel="stylesheet" type="text/css" />
		<link href="include/css/page/login.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="include/js/page/login.js"></script>
		<!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
	</head>
	<body>
		<div id="form">
			<div style="position: absolute; margin-top: -20px; color: grey;">This version of bepr will be discontinued soon.</div>
			<nav id="nav">
				<span class="nav activ" data-action="sign-in" style="width: 50px;">Sign in</span>
				<span class="nav" data-action="sign-up" style="width: 90px;">Sign up</span>
				<span class="nav" data-action="reset-password" style="width: 150px;">Reset password</span>
			</nav>
			<main id="content">
				<div id="sign-in" class="login-section activ">
					<?php
					if (isset($_GET['login']) && $_GET['login'] == "failed"){
						echo "<div id=\"error\">Login failed. Check email and password.</div>";
					}else
					if (isset($_GET['login']) && $_GET['login'] == "expired"){
						echo "<div id=\"error\">Your login has expired. Please log-in again.</div>";
					}
					?>
					<div style="font-size: 2em; padding-left: 10px;">Sign in</div>
					<div style="padding-left: 10px;">with Email and Password</div>
					<form style="margin-top: 10px;" method="POST" action="login.php?a=sign-in<?php if (isset($_GET['ref'])){echo "&ref=".$_GET['ref'];}?>">
						<input type="email" name="email" placeholder="Email" class="input" required autofocus />
						<input type="password" name="password" placeholder="Password" class="input" required pattern=".{3,}" title="Three or more characters" />
						<div style="margin-left: 10px; margin-top: 10px; float: left;"><input type="checkbox" name="remain" value="remain" style="margin: 0px;"/> Remain signed in</div>
						<input type="submit" value="SIGN IN" class="submit" style="float: right; margin-right: 10px;"/>
					</form>
				</div>
				<div id="sign-up"  class="login-section">
					<div id="error">Sign up disabled!</div>
					<div style="font-size: 2em; padding-left: 10px;">Sign up</div>
					<div style="padding-left: 10px;">create a new account</div>
					<form style="margin-top: 10px;" method="POST" action="login.php?a=sign-up">
						<input type="email" name="email" placeholder="Email" class="input" required />
						<input type="password" name="password" placeholder="Password" class="input" required />
						<input type="checkbox" name="agb" value="accept" style="margin-left: 10px;" required />I agree to the <a href="public/terms-of-use.php" target="_blank" style="color: #FFFFFF;">Terms of Use</a> and <a href="public/privacy-policy.php" target="_blank" style="color: #FFFFFF;">Privacy Policy</a>
						<input type="submit" value="SIGN UP" class="submit"/>
						<!--<div class="g-recaptcha" data-sitekey="6LcIOgUTAAAAAIkrN2fb2I_2fzEcudPeVBQwDo6S"></div>-->
					</form>
				</div>
				<div id="reset-password"  class="login-section">
					<div style="font-size: 2em; padding-left: 10px;">Reset Password</div>
					<div style="padding-left: 10px;">we will send you an email</div>
					<form style="margin-top: 10px;" method="POST" action="login.php?a=reset-password">
						<input type="email" name="email" placeholder="Email" class="input" required />
						<input type="submit" value="Send" class="submit"/>
					</form>
				</div>
			</main>
			<div style="clear: both; margin-top: -5px; font-size: 0.8em; margin-left: 10px;">Check out <a href="v3/" style="text-decoration: line-through; color: #FFFFFF;">bepr v3 (experimental)</a></div>
		</div>
	</body>
	</html>
	<?}else{
		header('LOCATION: loggedin/');
	}
	?>
