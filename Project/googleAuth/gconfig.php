<?php

//If the secure access parameter is not set, kill the process.
if ( !defined( 'secureAccessParameter' ) )die( 'Improper Access' );

// Google App Client Id
define('CLIENT_ID', '113699226954-d13ipgtirln8f5na422jrcq2eb9di472.apps.googleusercontent.com');

// Google App Client Secret
define('CLIENT_SECRET', 'i1en4rp-AJx2BkVZGmYwymO6');

// Google App Redirect Url
define('CLIENT_REDIRECT_URL', 'http://11wha.ashvillecomputing.co.uk/Project/googleAuth/Callback.php');

?>