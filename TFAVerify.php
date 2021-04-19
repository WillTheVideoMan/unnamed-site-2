<?php
require_once 'PHPGansta_GoogleAuth.php';
$secret = "V6XGDSZGXADUOJU5";

$inputCode = $_POST['authCode'];

$ga = new PHPGangsta_GoogleAuthenticator();
$checkResult = $ga->verifyCode($secret, $inputCode, 2);    // 2 = 2*30sec clock tolerance
if ($checkResult) {
    echo 'OK';
} else {
    echo 'FAILED';
}
?>