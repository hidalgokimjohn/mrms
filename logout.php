<?php
session_start();
include ('app/Database.php');
include ('app/App.php');
$app = new \app\App();
$app->log($_SESSION['username'], 'logout', 'has logged out', null, null);
$app->logout();
header('location: https://caraga-auth.dswd.gov.ph:8443/auth/realms/entdswd.local/protocol/openid-connect/logout?redirect_uri=http://crg-kcapps-svr.entdswd.local/mrms/index.php');
exit;