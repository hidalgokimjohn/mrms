<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if($app->deleteFile($_POST['file_id'],$_POST['form_id'])){
    echo 'deleted';
}else{
    return false;
}
