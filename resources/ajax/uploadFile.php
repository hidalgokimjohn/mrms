<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if (isset($_GET['form_id'])) {
    //check if target reach
    $target_reach=$app->is_target_reach($_GET['form_id']);
    if ( $target_reach == 'no') {
        echo $app->uploadFile();
    }
    if($target_reach =='yes'){
        //EXCEMPT IF ERS/CVS/WORKERS PROFILE/SPCR
        if($app->allowThisForms($_GET['form_id'])){
            echo $app->uploadFile();
        }else{
            echo 'target_reached';
        }
    }
} else {
    echo 'false';
}
