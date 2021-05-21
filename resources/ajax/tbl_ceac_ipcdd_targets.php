<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
$app->tbl_CeacIpcddTargets($_GET['cadt_id'],$_GET['cycle_id'],'ipcdd_drom');

