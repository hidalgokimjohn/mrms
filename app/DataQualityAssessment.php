<?php


namespace app;


class DataQualityAssessment extends App
{
    public function generate_findings($area, $cycle)
    {
        $mysql = $this->connectDatabase();
        //$user = $mysql->real_escape_string($user);
        $cycle = $mysql->real_escape_string($cycle);
        $area = $mysql->real_escape_string($area);
        $get_activity = "SELECT 
                lib_activity.activity_name,
                lib_activity.id,
                COALESCE ( lib_barangay.psgc_brgy, lib_municipality.psgc_mun, lib_cadt.id, 'n/a' ) AS location,
                COALESCE ( lib_barangay.brgy_name, lib_municipality.mun_name, lib_cadt.cadt_name, 'n/a' ) AS location_name

                FROM
                tbl_dqa_findings
                INNER JOIN form_target ON tbl_dqa_findings.fk_ft_guid = form_target.ft_guid
                LEFT JOIN lib_barangay ON form_target.fk_psgc_brgy = lib_barangay.psgc_brgy
                LEFT JOIN lib_municipality ON form_target.fk_psgc_mun = lib_municipality.psgc_mun
                LEFT JOIN lib_cadt ON form_target.fk_cadt = lib_cadt.id
                INNER JOIN lib_form ON form_target.fk_form = lib_form.form_code
                INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id 
            WHERE
               (form_target.fk_cadt='$area' OR form_target.fk_psgc_mun='$area')  AND tbl_dqa_findings.is_deleted=0 GROUP BY lib_activity.id ORDER BY lib_activity.id";
        $activity_result = $mysql->query($get_activity) or die($mysql->error);
        while ($activity_rows = $activity_result->fetch_assoc()) {
            echo '<tr>';
            echo '<td colspan="4" class="font-italic font-weight-bold">' . $activity_rows['activity_name'] . '</td>';
            echo '</tr>';
            //
            $get_findings = "SELECT
                lib_activity.activity_name,
                COALESCE ( lib_barangay.brgy_name, lib_municipality.mun_name, lib_cadt.cadt_name, 'n/a' ) AS location,
                lib_form.form_name,
                tbl_dqa_findings.findings,
                tbl_dqa_findings.added_by,
                tbl_dqa_findings.is_checked,
                tbl_dqa_findings.findings_guid,
                lib_form.form_type,
                lib_form.form_code,
                form_target.ft_guid,
                form_target.fk_cadt,
                form_target.fk_cycle
            FROM
                tbl_dqa_findings
                INNER JOIN form_target ON tbl_dqa_findings.fk_ft_guid = form_target.ft_guid
                LEFT JOIN lib_barangay ON form_target.fk_psgc_brgy = lib_barangay.psgc_brgy
                LEFT JOIN lib_municipality ON form_target.fk_psgc_mun = lib_municipality.psgc_mun
                LEFT JOIN lib_cadt ON form_target.fk_cadt = lib_cadt.id
                INNER JOIN lib_form ON form_target.fk_form = lib_form.form_code
                INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id 
            WHERE
                 form_target.fk_cycle='$cycle' 
              AND (form_target.fk_cadt='$area' OR form_target.fk_psgc_mun='$area') 
              AND tbl_dqa_findings.is_deleted=0  
              AND lib_activity.id='$activity_rows[id]' order by lib_municipality.mun_name,lib_form.id";
            $findings_result = $mysql->query($get_findings) or die($mysql->error);
            while ($findings_rows = $findings_result->fetch_assoc()) {
                if ($findings_rows['is_checked'] == 0) {
                    $status = 'Not complied';
                } elseif ($findings_rows['is_checked'] == 1) {
                    $status = 'Complied';
                } else {
                    $status = 'null';
                }
                echo '<td>' . $findings_rows['location'] . '</td>';
                echo '<td>' . $findings_rows['form_name'] . '</td>';
                echo '<td>' . $findings_rows['findings'] . '</td>';
                echo '<td>' . $status . '</td>';
                echo '</tr>';
            }

        }

    }

    public function locrowspan($user, $area, $cycle, $activity_id, $loc)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                COUNT(COALESCE ( lib_barangay.brgy_name, lib_municipality.mun_name, lib_cadt.cadt_name, 'n/a' )) as rowspan
            FROM
                tbl_dqa_findings
                INNER JOIN form_target ON tbl_dqa_findings.fk_ft_guid = form_target.ft_guid
                LEFT JOIN lib_barangay ON form_target.fk_psgc_brgy = lib_barangay.psgc_brgy
                LEFT JOIN lib_municipality ON form_target.fk_psgc_mun = lib_municipality.psgc_mun
                LEFT JOIN lib_cadt ON form_target.fk_cadt = lib_cadt.id
                INNER JOIN lib_form ON form_target.fk_form = lib_form.form_code
                INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id 
            WHERE
                tbl_dqa_findings.added_by = '$user' AND form_target.fk_cycle='$cycle' 
              AND (form_target.fk_cadt='$area' OR form_target.fk_psgc_mun='$area') 
              AND tbl_dqa_findings.is_deleted=0  
              AND lib_activity.id='$activity_id' AND (lib_cadt.id='$loc' OR lib_barangay.psgc_brgy='$loc' OR lib_municipality.psgc_mun='$loc') ";
        $result = $mysql->query($q) or die($mysql->error);

        $row = $result->fetch_assoc();
        return $row['rowspan'];
    }

    public function submitWithFinding()
    {
        $mysql = $this->connectDatabase();
        $finding_guid = $this->v4();
        $fk_ft_guid = $_GET['ft_guid'];
        $fk_dqa_guid = $_GET['dqa_id'];
        $fileId = $_GET['file_id'];
        $listId = $_GET['list_id'];
        $textFindings = $mysql->real_escape_string($_POST['textFindings']);
        $responsiblePerson = $_POST['responsiblePerson'];
        $typeOfFindings = $_POST['typeOfFindings'];
        $dateOfCompliance = $_POST['dateOfCompliance'];
        $addedBy = $_SESSION['id_number'];
        $dqaLevel = $_POST['dqaLevel'];
        $q = "";
        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`, `created_at`, `is_checked`, `added_by`, `dqa_level`, `deadline_for_compliance`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$typeOfFindings', '$textFindings', '$responsiblePerson', '0', NOW(), '0', '$addedBy', '$dqaLevel', '$dateOfCompliance')";
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                //add to logs
                $desc = "added a finding";
                $action = "with findings";
                $this->add_to_dqa_logs($fileId, $_SESSION['id_number'], $desc, $action, '');
                //$listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed',`in_hard_copy`='yes',`date_reviewed`=NOW() WHERE (`id`='$listId') LIMIT 1";
                //$resultFileUpdate = $mysql->query($listUpdate) or die($mysql->error);
                return true;
            } else {
                return false;
            }
        } else {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `fk_findings`, `findings`, `responsible_person`, `is_deleted`, `created_at`, `is_checked`, `added_by`, `dqa_level`, `deadline_for_compliance`,`fk_file_guid`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$typeOfFindings', '$textFindings', '$responsiblePerson', '0', NOW(), '0', '$addedBy', '$dqaLevel', '$dateOfCompliance','$fileId')";
            //Update file status
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                //add to logs
                $desc = "added a finding";
                $action = "with findings";
                $this->add_to_dqa_logs($fileId, $_SESSION['id_number'], $desc, $action, '');
                /*$listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`id`='$listId') LIMIT 1";
                $resultFileUpdate = $mysql->query($listUpdate) or die($mysql->error);*/
                $fileUpdate = "UPDATE `form_uploaded` SET `with_findings`='with findings', `is_reviewed`='reviewed', `date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $mysql->query($fileUpdate) or die($mysql->error);
                return true;
            } else {
                return false;
            }
        }
    }

    public function add_to_dqa_logs($file_id, $user, $desc, $action, $finding_id)
    {
        $mysql = $this->connectDatabase();
        $file_id = $mysql->real_escape_string($file_id);
        $desc = $mysql->real_escape_string($desc);
        $action = $mysql->real_escape_string($action);
        $finding_id = $mysql->real_escape_string($finding_id);
        $q = "INSERT INTO tbl_dqa_logs (`fk_file_id`, `id_number`, `create_date`, `description`, `action`,`finding_id`) VALUES ('$file_id', '$user', NOW(), '$desc', '$action','$finding_id')";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function submitGiveTa()
    {
        $mysql = $this->connectDatabase();
        $mysql = $this->connectDatabase();
        $finding_guid = $this->v4();
        $fk_ft_guid = $_GET['ft_guid'];
        $fk_dqa_guid = $_GET['dqa_id'];
        $fileId = $_GET['file_id'];
        $textFindings = $_POST['textFindings'];
        $listId = $_GET['list_id'];
        $addedBy = $_SESSION['id_number'];
        $dqaLevel = $_POST['dqaLevel'];
        $q = "";
        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`,`findings`, `is_deleted`, `created_at`, `added_by`, `dqa_level`,`technical_advice`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$textFindings', '0',NOW(),'$addedBy','$dqaLevel','technical advice')";
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                //add to logs
                $desc = "provided technical advice";
                $action = "technical advice";
                $this->add_to_dqa_logs($fileId, $_SESSION['id_number'], $desc, $action, '');
                /* $listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`id`='$listId') LIMIT 1";
                 $mysql->query($listUpdate);*/
                return true;
            } else {
                return false;
            }
        } else {
            $q = "INSERT INTO `tbl_dqa_findings` (`findings_guid`, `fk_ft_guid`, `fk_dqa_guid`, `findings`, `is_deleted`, `created_at`, `added_by`,`fk_file_guid`,`dqa_level`,`technical_advice`) VALUES ('$finding_guid', '$fk_ft_guid', '$fk_dqa_guid', '$textFindings', '0', NOW(), '$addedBy','$fileId','$dqaLevel','technical advice')";
            //Update file status
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                $fileUpdate = "UPDATE `form_uploaded` SET `is_reviewed`='reviewed', `date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $resultFileUpdate = $mysql->query($fileUpdate) or die($mysql->error);
                if ($mysql->affected_rows > 0) {
                    //add to logs
                    $desc = "provided technical advice";
                    $action = "techinical advice";
                    $this->add_to_dqa_logs($fileId, $_SESSION['id_number'], $desc, $action, '');
                    return true;
                }

                /*$listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`id`='$listId') LIMIT 1";
                $mysql->query($listUpdate);*/

            } else {
                return false;
            }
        }
        return true;
    }

    public function submitNoFinding()
    {
        $mysql = $this->connectDatabase();
        $listId = $_GET['list_id'];

        if ($_GET['file_name'] == 'Not Yet Uploaded') {
            //NYU stands for Not Yet Uploaded
            echo 'notYetUploaded_';
        } else {
            //check if file has previous findings (not complied)
            if (!$this->checkPreviousFindings()) {
                //if no previous findings set no finding.
                $fileId = $_GET['file_id'];
                $q = "UPDATE `form_uploaded` SET `with_findings`='no findings', `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`file_id`='$fileId') LIMIT 1";
                $result = $mysql->query($q) or die($mysql->error);
                if ($mysql->affected_rows > 0) {
                    //add to logs
                    $desc = "marked this MOV with no findings";
                    $action = "No findings";
                    $this->add_to_dqa_logs($fileId, $_SESSION['id_number'], $desc, $action, '');
                    /*$listUpdate = "UPDATE `tbl_dqa_list` SET `is_reviewed`='reviewed',`date_reviewed`=NOW() WHERE (`id`='$listId') LIMIT 1";
                    $mysql->query($listUpdate);*/
                    return true;
                }
                return true;
            } else {
                echo 'hasPreviousFindings_';
            }
        }
    }

    public function checkPreviousFindings()
    {
        $mysql = $this->connectDatabase();
        $file_id = $_GET['file_id'];
        $q = "SELECT
            tbl_dqa_findings.fk_file_guid
            FROM
            tbl_dqa_findings
            WHERE fk_file_guid='$file_id' AND is_checked=0 AND is_deleted=0";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeFinding($id, $fileId)
    {
        $mysql = $this->connectDatabase();
        $fileId = $mysql->real_escape_string($fileId);
        $q = "UPDATE `tbl_dqa_findings` SET `is_deleted`='1' WHERE (`findings_guid`='$id') LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            $desc = "deleted " . $this->getFindings($id);
            $action = "remove finding";
            $this->add_to_dqa_logs($fileId, $_SESSION['id_number'], $desc, $action, $id);
            return true;
        } else {
            return false;
        }
    }

    public function getFindings($id)
    {
        $mysql = $this->connectDatabase();
        $id = $mysql->real_escape_string($id);
        $q = "SELECT findings FROM tbl_dqa_findings WHERE `findings_guid` = '$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['findings'];
        } else {
            return false;
        }
    }

    public function add_to_dqa_list()
    {
        $mysql = $this->connectDatabase();
        $file_id = $mysql->real_escape_string($_GET['file_id']);
        $user = $_SESSION['id_number'];
        //check if exist
        $q = "SELECT
            tbl_dqa_user_list.dqa_list_id
            FROM tbl_dqa_user_list WHERE tbl_dqa_user_list.fk_file_id='$file_id' LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            return 'file_exist';
        } else {
            //add to list if there's no record
            $q = "INSERT INTO `tbl_dqa_user_list`(`fk_file_id`, `id_number`, `created_date`) VALUES ('$file_id','$user',NOW())";
            $result = $mysql->query($q) or die($mysql->error);
            if ($mysql->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function tbl_dqa_list()
    {
        $mysql = $this->connectDatabase();
        $user = $_SESSION['id_number'];
        $modality_group = $mysql->real_escape_string($_GET['modality']);
        $q = "SELECT
                form_uploaded.original_filename,
                form_uploaded.file_path,
                form_uploaded.with_findings,
                form_uploaded.is_findings_complied,
                form_uploaded.is_reviewed,
                tbl_dqa_user_list.id_number,
                form_uploaded.rp_id,
                lib_activity.activity_name,
                lib_form.form_name,
                COALESCE ( lib_barangay.brgy_name, lib_municipality.mun_name, lib_cadt.cadt_name, 'n/a' ) AS location,
                form_uploaded.uploaded_by,
                form_uploaded.file_id,
                lib_cycle.cycle_name,
                cycles.batch,
                lib_modality.modality_group ,
                tbl_dqa_user_list.created_date,
                form_uploaded.host,
                form_target.ft_guid
            FROM
                form_uploaded
                INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
                LEFT JOIN lib_barangay ON form_target.fk_psgc_brgy = lib_barangay.psgc_brgy
                LEFT JOIN lib_municipality ON form_target.fk_psgc_mun = lib_municipality.psgc_mun
                LEFT JOIN lib_cadt ON form_target.fk_cadt = lib_cadt.id
                INNER JOIN cycles ON form_target.fk_cycle = cycles.id
                INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
                INNER JOIN lib_modality ON cycles.fk_modality = lib_modality.id
                INNER JOIN tbl_dqa_user_list ON form_uploaded.file_id = tbl_dqa_user_list.fk_file_id
                INNER JOIN lib_form ON form_target.fk_form = lib_form.form_code
                INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
            WHERE tbl_dqa_user_list.id_number='$user' ";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $json_data = array("data" => $data);
            echo json_encode($json_data);
        } else {
            $json_data = array("data" => '');
            echo json_encode($json_data);
        }

    }

    public function totalReviewed($id_number)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
            COUNT( form_uploaded.is_reviewed ) totalReviewed 
        FROM
            tbl_dqa_user_list
            INNER JOIN form_uploaded ON tbl_dqa_user_list.fk_file_id = form_uploaded.file_id
            WHERE tbl_dqa_user_list.id_number='$id_number'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalReviewed'];
        } else {
            return false;
        }

    }

    public function totalReviewedThisWeek($id_number, $status)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
            COUNT( form_uploaded.is_reviewed ) AS totalReviewedThisWeek 
        FROM
            tbl_dqa_user_list
            INNER JOIN form_uploaded ON tbl_dqa_user_list.fk_file_id = form_uploaded.file_id
            INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN cycles ON form_target.fk_cycle = cycles.id 
        WHERE
            tbl_dqa_user_list.id_number = '$id_number' AND cycles.`status`='$status'
            AND WEEKOFYEAR( tbl_dqa_user_list.created_date ) = WEEKOFYEAR(
            NOW()) 
            AND YEAR ( tbl_dqa_user_list.created_date ) = YEAR (
            NOW())";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalReviewedThisWeek'];
        } else {
            return 0;
        }
    }

    public function totalReviewedThisDay($id_number, $status)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
            COUNT( form_uploaded.is_reviewed ) AS totalReviewedThisDay 
        FROM
            tbl_dqa_user_list
            INNER JOIN form_uploaded ON tbl_dqa_user_list.fk_file_id = form_uploaded.file_id
            INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN cycles ON form_target.fk_cycle = cycles.id 
        WHERE
            tbl_dqa_user_list.id_number = '$id_number' AND cycles.`status`='$status'
            AND DAYOFYEAR( tbl_dqa_user_list.created_date ) = DAYOFYEAR(NOW()) 
            AND YEAR ( tbl_dqa_user_list.created_date ) = YEAR (
            NOW())";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalReviewedThisDay'];
        } else {
            return 0;
        }
    }

    public function totalFindings($id_number, $status)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
            count(tbl_dqa_findings.added_by) as cntFinding
            FROM
            tbl_dqa_findings
            INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
            INNER JOIN cycles ON cycles.id = form_target.fk_cycle
            INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
            WHERE cycles.`status`='$status' 
              AND tbl_dqa_findings.is_deleted=0 
              AND tbl_dqa_findings.is_checked=0 
              AND tbl_dqa_findings.added_by='$id_number'
              AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cntFinding'];
        }
    }

    public function totalFindingsThisWeek($id_number, $status)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
        count(tbl_dqa_findings.added_by) as cntFindingThisWeek
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$id_number'
        AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cntFindingThisWeek'];
        } else {
            return 0;
        }
    }

    public function totalFindingsThisDay($id_number, $status)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
        count(tbl_dqa_findings.added_by) as cntFindingThisDay
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$id_number'
        AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cntFindingThisDay'];
        } else {
            return 0;
        }
    }

    public function totalTA($id_number, $status)
    {
        $mysql = $this->connectDatabase();

        $q = "SELECT
        count(tbl_dqa_findings.added_by) as totalTA
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$id_number'
        AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalTA'];
        }
    }

    public function totalTAThisWeek($id_number, $status)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        count(tbl_dqa_findings.added_by) as totalTAThisWeek
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$id_number'
        AND WEEKOFYEAR(tbl_dqa_findings.created_at)=WEEKOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalTAThisWeek'];
        } else {
            return 0;
        }
    }

    public function totalTAThisDay($id_number, $status)
    {
        $mysql = $this->connectDatabase();
        $id_number = $_SESSION['id_number'];
        $q = "SELECT
        count(tbl_dqa_findings.added_by) as totalTAThisDay
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        INNER JOIN cycles ON cycles.id = form_target.fk_cycle
        INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
        WHERE cycles.`status`='$status' AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.added_by='$id_number'
        AND DAYOFYEAR(tbl_dqa_findings.created_at)=DAYOFYEAR(NOW()) 
        AND YEAR(tbl_dqa_findings.created_at) = YEAR(now())
        AND tbl_dqa_findings.technical_advice='technical advice'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['totalTAThisDay'];
        } else {
            return 0;
        }
    }

    public function countCompliedByUsername($cadt_id, $cadt_id2, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        COUNT(tbl_dqa_findings.findings_guid) as countComplied
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        WHERE (form_target.fk_cadt='$cadt_id' OR form_target.fk_psgc_mun='$cadt_id2') AND form_target.fk_cycle='$cycle_id' AND tbl_dqa_findings.added_by='$_SESSION[id_number]'
        AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.is_checked=1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['countComplied'];
        }
    }

    public function dqaDetailedSummarry_ipcdd($cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        //$status = $mysql->real_escape_string($status);
        $cadt_id = $mysql->real_escape_string($cadt_id);
        $cycle_id = $mysql->real_escape_string($cycle_id);
        $q = "SELECT
                    lib_cadt.cadt_name,
                    lib_cycle.cycle_name,
                    Sum(form_target.target) AS target,
                    Sum(form_target.actual) AS actual,
                    Sum(form_target.reviewed) AS reviewed,
                    CONCAT(
                        FORMAT(
                            Sum(form_target.actual) / Sum(form_target.target) * 100,
                            2
                        ),
                        '%'
                    ) AS uploadStatus,
                    cycles.id AS cycle_id,
                    cycles.batch,
                    form_target.fk_cadt as cadt_id
                FROM
                    form_target
                INNER JOIN cycles ON cycles.id = form_target.fk_cycle
                INNER JOIN lib_modality ON lib_modality.id = cycles.fk_modality
                INNER JOIN lib_cadt ON lib_cadt.id = form_target.fk_cadt
                INNER JOIN lib_cycle ON lib_cycle.id = cycles.fk_cycle
                WHERE
                    form_target.fk_cycle='$cycle_id'
                AND 
                    form_target.fk_cadt = '$cadt_id'
                
                AND form_target.target > 0";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['reviewed'] = $this->countReviewed($row['cadt_id'], $row['cycle_id']);
                $row['reviewedOverActual'] = number_format($row['reviewed'] / $row['actual'] * 100, 2);
                $row['findings'] = $this->countFinding($row['cadt_id'], $row['cycle_id']);
                $row['complied'] = $this->countComplied($row['cadt_id'], $row['cycle_id']);
                if ($row['findings']) {
                    $row['complied_%'] = number_format($row['complied'] / $row['findings'], 2) . '%';
                } else {
                    $row['complied_%'] = '0%';
                }

                $data[] = $row;
            }
            return $data;
        }
    }

    public function countReviewed($cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                COUNT(form_uploaded.is_reviewed) getReviewedMOVs
            FROM
                tbl_dqa_user_list
                INNER JOIN form_uploaded ON tbl_dqa_user_list.fk_file_id = form_uploaded.file_id
                INNER JOIN form_target ON form_target.ft_guid = form_uploaded.fk_ft_guid
                WHERE form_uploaded.is_reviewed='reviewed' AND form_target.fk_cadt='$cadt_id' AND form_target.fk_cycle='$cycle_id'";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['getReviewedMOVs'];
        }
    }

    public function countFinding($cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        COUNT(tbl_dqa_findings.findings_guid) as countFinding
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        WHERE form_target.fk_cadt='$cadt_id' 
          AND form_target.fk_cycle='$cycle_id' 
          AND tbl_dqa_findings.is_deleted=0 
          AND tbl_dqa_findings.is_checked=0 
          AND tbl_dqa_findings.technical_advice is NULL";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['countFinding'];
        } else {
            return false;
        }
    }

    public function countComplied($cadt_id, $cycle_id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        COUNT(tbl_dqa_findings.findings_guid) as countComplied
        FROM
        tbl_dqa_findings
        INNER JOIN form_target ON form_target.ft_guid = tbl_dqa_findings.fk_ft_guid
        WHERE form_target.fk_cadt='$cadt_id' AND form_target.fk_cycle='$cycle_id' AND tbl_dqa_findings.added_by='$_SESSION[id_number]'
        AND tbl_dqa_findings.is_deleted=0 AND tbl_dqa_findings.is_checked=1";
        $result = $mysql->query($q);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['countComplied'];
        }
    }

    public function migrate_compliance()
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
                file_id as compliance,
                form_uploaded.fk_ft_guid,
                form_uploaded.uploaded_by,
                form_uploaded.date_uploaded
            FROM
                form_uploaded
                INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
                INNER JOIN cycles ON form_target.fk_cycle = cycles.id 
            WHERE
                 cycles.fk_modality = 4 
                AND form_uploaded.is_compliance = 'compliance' 
                AND form_uploaded.is_deleted =0";
        $result = $mysql->query($q) or die($mysql->error);
        while ($row = $result->fetch_assoc()) {
            $ft_guid = $row['fk_ft_guid'];
            $compliance = $row['compliance'];
            $id_number = $row['uploaded_by'];
            $date_uploaded = $row['date_uploaded'];

            $q1 = "SELECT
                file_id as with_findings
            FROM
                form_uploaded
                WHERE is_compliance is NULL AND with_findings='with findings' AND fk_ft_guid='$ft_guid'";
            $result1 = $mysql->query($q1) or die($mysql->error);

            while ($row1 = $result1->fetch_assoc()) {
                $q3 = "INSERT INTO `tbl_dqa_compliance`(`file_with_findings`, `file_compliance`,`created_date`, `id_number`, `fk_ft_guid`) 
                    VALUES ('$row1[with_findings]', '$compliance', '$date_uploaded','$id_number', '$ft_guid')";
                $result2 = $mysql->query($q3) or die($mysql->error);
                if ($mysql->affected_rows > 0) {
                    echo 'Record inserted<br>';
                } else {
                    echo 'error not successfull<br>';
                }
            }
        }

    }

    public function migrateReviewed_movs(){
        $mysql=$this->connectDatabase();
        $q="SELECT
            tbl_dqa_list.fk_file_guid,
            form_uploaded.is_reviewed,
            form_uploaded.with_findings,
            tbl_dqa_list.added_by,
            tbl_dqa_list.is_delete,
            tbl_dqa_list.created_at 
        FROM
            tbl_dqa_list
            INNER JOIN form_uploaded ON tbl_dqa_list.fk_file_guid = form_uploaded.file_id
            INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
            INNER JOIN cycles ON form_target.fk_cycle = cycles.id 
        WHERE
            form_uploaded.is_deleted = 0 
            AND cycles.fk_modality = 4 
            AND form_uploaded.is_reviewed = 'reviewed'";
        $result = $mysql->query($q) or die($mysql->error);
        while($row = $result->fetch_assoc()){
            $file_id = $row['fk_file_guid'];
            $id_number = $row['added_by'];
            $created_date = $row['created_at'];
            $q2 = "INSERT INTO `tbl_dqa_user_list`(`fk_file_id`, `id_number`, `created_date`) VALUES ('$file_id', '$id_number', '$created_date')";
            $result1 = $mysql->query($q2) or die($mysql->error);
            if($mysql->affected_rows>0){
                echo 'Migrated Successfully<br>';
            }
        }

    }

    public function tbl_dqa_compliance()
    {
        $mysql = $this->connectDatabase();
        $id_number = $_SESSION['id_number'];
        $q = "SELECT
	form_uploaded.original_filename,
	form_uploaded.file_path,
	form_uploaded.is_reviewed,
	form_uploaded.is_compliance,
	form_uploaded.is_complied,
	form_uploaded.date_uploaded,
	form_uploaded.host,
	form_uploaded.file_id,
	form_uploaded.fk_ft_guid,
	form_uploaded.with_findings,
	form_uploaded.rp_id,
	lib_cycle.cycle_name,
	cycles.batch,
	lib_activity.activity_name,
	lib_form.form_name,
    tbl_dqa_compliance.id_number AS uploaded_by,
	tbl_dqa_user_list.id_number AS reviewer,
	tbl_dqa_compliance.file_with_findings,
    tbl_dqa_compliance.file_compliance,
	COALESCE ( lib_barangay.brgy_name, lib_municipality.mun_name, lib_cadt.cadt_name, 'n/a' ) AS location 
FROM
	tbl_dqa_compliance
	INNER JOIN tbl_dqa_user_list ON tbl_dqa_compliance.file_with_findings = tbl_dqa_user_list.fk_file_id
	INNER JOIN form_uploaded ON tbl_dqa_compliance.file_compliance = form_uploaded.file_id
	INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
	INNER JOIN lib_form ON form_target.fk_form = lib_form.form_code
	INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
	INNER JOIN cycles ON form_target.fk_cycle = cycles.id
	INNER JOIN lib_cycle ON cycles.fk_cycle = lib_cycle.id
	LEFT JOIN lib_barangay ON form_target.fk_psgc_brgy = lib_barangay.psgc_brgy
	LEFT JOIN lib_municipality ON form_target.fk_psgc_mun = lib_municipality.psgc_mun
	LEFT JOIN lib_cadt ON form_target.fk_cadt = lib_cadt.id 
WHERE
	tbl_dqa_user_list.id_number = '$id_number'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $json_data = array("data" => $data);
            echo json_encode($json_data);
        } else {
            $json_data = array("data" => '');
            echo json_encode($json_data);
        }

    }

    public function tbl_dqa_act_findings()
    {
        $mysql = $this->connectDatabase();
        $this->getUserActiveAreas();
        $cadt_id= "'".implode("','", $this->area_id)."'";
        $cycle_id= "'".implode("','", $this->cycle_id)."'";
        $q = "SELECT
                tbl_dqa_user_list.created_date,
                lib_activity.activity_name,
                lib_form.form_name,
                form_uploaded.original_filename,
                tbl_dqa_user_list.id_number,
                form_uploaded.is_findings_complied,
                COALESCE ( lib_barangay.brgy_name, lib_municipality.mun_name, lib_cadt.cadt_name, 'n/a' ) AS location,
                form_uploaded.file_id,
                form_uploaded.file_path,
                form_uploaded.uploaded_by,
                form_uploaded.is_reviewed,
                form_uploaded.is_compliance,
                form_uploaded.is_complied,
                tbl_dqa_findings.responsible_person,
                form_uploaded.`host`,
                tbl_dqa_findings.findings, 
                form_uploaded.fk_ft_guid,
                tbl_dqa_findings.added_by as reviewed_by
            FROM
                form_uploaded
                INNER JOIN form_target ON form_uploaded.fk_ft_guid = form_target.ft_guid
                INNER JOIN cycles ON form_target.fk_cycle = cycles.id
                INNER JOIN lib_form ON form_target.fk_form = lib_form.form_code
                INNER JOIN lib_activity ON lib_form.fk_activity = lib_activity.id
                LEFT JOIN lib_barangay ON form_target.fk_psgc_brgy = lib_barangay.psgc_brgy
                LEFT JOIN lib_municipality ON form_target.fk_psgc_mun = lib_municipality.psgc_mun
                LEFT JOIN lib_cadt ON form_target.fk_cadt = lib_cadt.id
                INNER JOIN tbl_dqa_user_list ON form_uploaded.file_id = tbl_dqa_user_list.fk_file_id
                INNER JOIN tbl_dqa_findings ON tbl_dqa_user_list.fk_file_id = tbl_dqa_findings.fk_file_guid 
            WHERE
                form_uploaded.is_deleted = 0 
                AND form_uploaded.is_reviewed = 'reviewed' 
                AND form_uploaded.with_findings = 'with findings' 
                AND cycles.fk_modality = 4 
                AND tbl_dqa_findings.is_deleted = 0 
                AND form_target.fk_cycle IN ($cycle_id)
                AND form_target.fk_cadt IN ($cadt_id)
            GROUP BY
                tbl_dqa_findings.fk_file_guid
            ORDER BY
                tbl_dqa_user_list.created_date desc";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['reviewed_by'] = $this->getUsersName($row['reviewed_by']);
                $data[] = $row;
            }
            $json_data = array("data" => $data);
            echo json_encode($json_data);
        } else {
            $json_data = array("data" => '');
            echo json_encode($json_data);
        }
    }

    public function findings_complied()
    {
        $mysql = $this->connectDatabase();
        $old_file_id = $mysql->real_escape_string($_POST['with_findings_file_id']);
        $compliance_file_id = $mysql->real_escape_string($_POST['compliance_file_id']);
        $ft_guid = $mysql->real_escape_string($_POST['ft_guid']);
        $rp_id = $mysql->real_escape_string($_POST['rp_id']);
        $date_uploaded = $_POST['date_uploaded'];
        $id_number = $_SESSION['id_number'];
        //update old file
        $q = "UPDATE `form_uploaded` SET `is_findings_complied` = 'complied' WHERE `file_id` = '$old_file_id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            //update
            $q1 = "UPDATE `form_uploaded` SET 
                                         `with_findings` = 'no findings', 
                                         `is_reviewed` = 'reviewed', 
                                         `reviewed_by` = '$id_number', 
                                         `date_reviewed` = NOW(), 
                                         `is_complied` = 'complied', 
                                         `rp_id` = '$rp_id' 
                WHERE `file_id` = '$compliance_file_id'";
            $result1=$mysql->query($q1)or die($mysql->error);
            if($mysql->affected_rows>0){
                //update findings to complied
                $q3="UPDATE `tbl_dqa_findings` SET `is_checked` = 1, `date_complied` = '$date_uploaded' 
                    WHERE `fk_file_guid` = '$old_file_id'  ";
                $result2=$mysql->query($q3) or die($mysql->error);
                if($mysql->affected_rows>0){
                    echo 'complied';
                }else{
                    echo 'already_updated';
                }
            }else{
                echo 'already_updated';
            }
        }else{
            echo 'already_updated';
        }
    }

    public function findings_notcomplied()
    {
        $mysql = $this->connectDatabase();
        $old_file_id = $mysql->real_escape_string($_POST['with_findings_file_id']);
        $compliance_file_id = $mysql->real_escape_string($_POST['compliance_file_id']);
        $ft_guid = $mysql->real_escape_string($_POST['ft_guid']);
        $rp_id = $mysql->real_escape_string($_POST['rp_id']);
        $date_uploaded = $_POST['date_uploaded'];
        $id_number = $_SESSION['id_number'];
        //update old file
        $q = "UPDATE `form_uploaded` SET `is_findings_complied` = 'not complied' WHERE `file_id` = '$old_file_id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($mysql->affected_rows > 0) {
            //update
            $q1 = "UPDATE `form_uploaded` SET 
                                         `with_findings` = 'with findings', 
                                         `is_reviewed` = 'reviewed', 
                                         `reviewed_by` = '$id_number', 
                                         `date_reviewed` = NOW(), 
                                         `is_complied` = 'not complied', 
                                         `rp_id` = '$rp_id' 
                WHERE `file_id` = '$compliance_file_id'";
            $result1=$mysql->query($q1)or die($mysql->error);
            if($mysql->affected_rows>0){
                //update findings to not complied
                $q3="UPDATE `tbl_dqa_findings` SET `is_checked` = 0, `date_complied` = NULL
                    WHERE `fk_file_guid` = '$old_file_id'";
                $result2=$mysql->query($q3) or die($mysql->error);
                if($mysql->affected_rows>0){
                    //add findings to the compliance file
                    if($this->addComplianceFindings()){
                        echo 'not complied';
                    }
                }else{
                    echo 'already_updated';
                }
            }else{
                echo 'not_complied_already';
            }
        }else{
            echo 'not_complied_already';
        }
    }

    public function addComplianceFindings()
    {
        $mysql = $this->connectDatabase();
        $findings = $mysql->real_escape_string($_POST['findings']);
        $compliance_file_id = $mysql->real_escape_string($_POST['compliance_file_id']);
        $id_number=$_SESSION['id_number'];
        $q="INSERT INTO `tbl_dqa_compliance_findings`(`fk_compliance_file_id`, `findings`, `created_date`, `added_by`, `is_deleted`) 
        VALUES ('$compliance_file_id', '$findings', NOW(), '$id_number', '0')";
        $result = $mysql->query($q) or die($mysql->error);
        if($mysql->affected_rows>0){
            return true;
        }else{
            return false;
        }
    }
}