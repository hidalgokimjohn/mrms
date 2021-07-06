<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/DataQualityAssessment.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
$dqa = new \app\DataQualityAssessment();

//add to dqa list
if($dqa->add_to_dqa_list()){
    if($_POST['withFindings']=='yes'){
        $required_fields = array("withFindings", "typeOfFindings", "textFindings", "responsiblePerson","dateOfCompliance","dqaLevel");

        foreach ($required_fields as $field) {
            if (!strlen($_POST[$field])) {
                $error[]=$field." is required";
            }
        }
        if(!empty($error)){
            echo 'error_on_required_fields';
        }else{
            echo ($dqa->submitWithFinding())?'submitted':'submit_error';
        }
    }

    if($_POST['withFindings']=='ta'){
        $required_fields = array("textFindings","dqaLevel");

        foreach ($required_fields as $field) {
            if (!strlen($_POST[$field])) {
                $error[]=$field." is required";
            }
        }
        if(!empty($error)){
            echo 'error_on_required_fields';
        }else{
            echo ($dqa->submitGiveTa())?'submitted':'submit_error';
        }
    }
    if($_POST['withFindings']=='no'){
        $required_fields = array("dqaLevel");
        foreach ($required_fields as $field) {
            if (!strlen($_POST[$field])) {
                $error[]=$field." is required";
            }
        }
        if(!empty($error)){
            echo 'error_on_required_fields';
        }else{
            echo ($dqa->submitNoFinding())?'submitted':'submit_error';
        }
    }
}else{
    echo 'something went wrong adding to tbl_dqa_user_list';
}


