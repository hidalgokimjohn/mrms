<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/DataQualityAssessment.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
$dqa = new \app\DataQualityAssessment();
if($_POST['finding_id']){
	echo ($dqa->removeFinding($_POST['finding_id'],$_POST['file_id']))?'removed':'';
}