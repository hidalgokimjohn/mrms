<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
//$app->getUserActiveAreas();
$app->getUploadedFilesByActivity($_GET['activity_id'],$_GET['cycle_id'],$_GET['area_id']);
