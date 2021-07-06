<?php

include_once "../../app/Database.php";
include_once "../../app/App.php";
include_once "../../app/Auth.php";
$auth = new \app\Auth();
$app = new \app\App();

    $displayFindings = $app->displayFindings($_POST['parent_file_id'],$_POST['ft_guid']);
    if (!empty($displayFindings)) {
        echo '<h5 class="mb-3">1st level DQA Findings</h5>';
        foreach ($displayFindings as $displayFinding) {
            $today = date("Y-m-d");
            $date1 = date("Y-m-d", strtotime($displayFinding['created_at']));
            $date2 = $displayFinding['deadline_for_compliance'];
            $diff = abs(strtotime($date2) - strtotime($date1));            
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $is_checked = '';
            if($displayFinding['is_checked']==1){
                $is_checked='checked';
            }

            //if TA
            if($displayFinding['technical_advice']=='technical advice'){
                echo '<div class="card mb-3 bg-light border animated fadeIn">
            <div class="card-body">
                <div class="float-right mr-n2">
                    <label class="form-check">
                        <span class="badge bg-warning"><i class="fa fa-lightbulb"></i> Technical Advice</span>
                    </label>
                </div>
                <p>
                <div class="float-end me-n2">
													<label class="form-check">
														<input type="checkbox" class="form-check-input" '.$is_checked.' required>
														<span class="form-check-label d-none">Checkbox</span>
													</label>
												</div>
                Posted: ' . date("M d, Y H:m:s a", strtotime($displayFinding['created_at'])) . '
                <p><strong>' . htmlentities($displayFinding['findings']) . '</strong></p>
                <div class="mt-n1">
                    <img src="'.$displayFinding['user_avatar'].'" width="32" height="32" class="rounded-circle" alt="Avatar">
                    <span>' . $app->getUsersName($displayFinding['added_by']) . '</span>
                    <br>
                </div>
            </div>
            </div>';
            }else{
                echo '<div class="card mb-3 bg-light border animated fadeIn">
                <div class="card-body">
                    <div class="float-right mr-n2">
                        <label class="form-check">
                            ' . $app->dueStatus($displayFinding['deadline_for_compliance']) . '
                        </label>
                    </div>
                 
                    <p>RP: <a href="#" class="font-weight-bold text-primary">'.$app->responsiblePerson($displayFinding['responsible_person']). '</a> <br>Deadline ' . date("M d, Y", strtotime($displayFinding['deadline_for_compliance'])) . ' (' . $days . ' days)
                    <br>Posted: ' . date("M d, Y H:m:s a", strtotime($displayFinding['created_at'])) .'
                    <p><strong>' . htmlentities($displayFinding['findings']) . '</strong></p>
                    <div class=" mt-n1">
                        <img src="'.$displayFinding['user_avatar'].'" width="32" height="32" class="rounded-circle" alt="Avatar">
                        <span>' . $app->getUsersName($displayFinding['added_by']) . '</span>
                        <br>
                    </div>
                   
                </div>
                </div>';
            }
            
        }
    } else {
        //if reviewed and no findings
        if($app->noFindings($_POST['file_id'])){
            echo '<div class="alert alert-success alert-dismissible" role="alert">
            <div class="alert-message">
                <h4 class="alert-heading font-weight-bold"><i class="fa fa-check-circle"></i> Well done!</h4>
                <p>No findings found, but other reviewers can change the status of this document.</p>
                <hr>
                <p class="mb-0">-MRMS</p>
            </div>
        </div>';
        }else{
            echo '<a class="list-group-item font-weight-bold text-center">No reviews yet</a>';
        }
    }
    ?>
<!--<div class="float-end me-n2">
    <label class="form-check">
        <input type="checkbox" name="complied_findings[]" value="'.$displayFinding['findings_guid'].'" '.$is_checked.' class="form-check-input">
        <span class="form-check-label d-none">Checkbox</span>
    </label>
</div>-->
