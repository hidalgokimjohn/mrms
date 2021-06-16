<?php


namespace app;


class DataQualityAssessment extends App
{
    public function generate_findings($area,$cycle){
        $mysql = $this->connectDatabase();
        //$user = $mysql->real_escape_string($user);
        $cycle = $mysql->real_escape_string($cycle);
        $area = $mysql->real_escape_string($area);
        $get_activity="SELECT 
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
        while($activity_rows=$activity_result->fetch_assoc()){
            echo '<tr>';
            echo '<td colspan="4" class="font-italic font-weight-bold">'.$activity_rows['activity_name'].'</td>';
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
            while($findings_rows = $findings_result->fetch_assoc()){
                if($findings_rows['is_checked']==0){
                    $status='Not complied';
                }elseif($findings_rows['is_checked']==1){
                    $status='Complied';
                }else{
                    $status='null';
                }
                echo '<td>'.$findings_rows['location'].'</td>';
                echo '<td>'.$findings_rows['form_name'].'</td>';
                echo '<td>'.$findings_rows['findings'].'</td>';
                echo '<td>'.$status.'</td>';
                echo '</tr>';
            }

        }

    }

    public function locrowspan($user,$area,$cycle,$activity_id,$loc){
        $mysql = $this->connectDatabase();
        $q="SELECT
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
}