<?php
include ('app/Database.php');
include ('app/App.php');
$app = new \app\App();
/*echo '<pre>';
$r=$app->weeklyUpload('ipcdd_drom',2021);
var_dump($r);*/
session_start();
/*echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$app->getUserActiveAreas();
echo '<pre>';
echo "'".implode("','", $app->area_id)."'".'<br/>';
echo "'".implode("','", $app->cycle_id)."'";

echo '<br/>';

//var_dump($app->getUploadedFiles());
echo '<pre>';*/
/*echo '<pre>';
($app->act_tblDqa());*/
echo '<pre>';
$app->createChecklist('ipcdd_drom','mc_23',$_GET['cadt_id'],$_GET['cycle_id']);
echo '</pre>';
?>