<?php
session_start();

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

define( 'secureAccessParameter', true );
require_once 'config.php';
require_once 'googleLoginHelper.php';

$scopes = array('https://www.googleapis.com/auth/userinfo.profile' ,'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/plus.me');

$gHelper = new GoogleLoginHelper();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Sign In With Google</title>
<link rel="icon" href="favicon.ico" type="image/x-icon" />
</head>

<body>
	<a href = "<?php echo $gHelper->GetAuthURL(CLIENT_ID, CLIENT_REDIRECT_URL, $scopes);?>"><img width = "300px;" src="btn_google_signin_dark_normal_web@2x.png" ></a>
</body>
</html>