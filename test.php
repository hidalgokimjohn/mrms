<?php
include ('app/Database.php');
include ('app/App.php');
$app = new \app\App();
/*echo '<pre>';
$r=$app->weeklyUpload('ipcdd_drom',2021);
var_dump($r);*/
echo $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$app->getUserActiveAreas();
echo '<pre>';
echo "'".implode("','", $app->area_id)."'".'<br/>';
echo "'".implode("','", $app->cycle_id)."'";

echo '<br/>';

var_dump($app->getUploadedFiles());

?>