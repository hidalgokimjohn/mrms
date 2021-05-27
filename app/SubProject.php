<?php


namespace app;


class SubProject extends App
{
    public function sub_project_all(){
        $mysql = $this->connectDatabase();
        $q="SELECT
                tbl_kcdashboard.`Mode`,
                tbl_kcdashboard.Sub_Project_Name,
                tbl_kcdashboard.No_of_household_beneficiaries_actual,
                tbl_kcdashboard.No_of_household_beneficiaries_target,
                tbl_kcdashboard.No_actual_male,
                tbl_kcdashboard.No_actual_female,
                tbl_kcdashboard.No_actual_ip_families,
                tbl_kcdashboard.No_actual_ip_households,
                tbl_kcdashboard.No_actual_pantawid_families,
                tbl_kcdashboard.No_actual_pantawid_households,
                tbl_kcdashboard.No_actual_slp_families,
                tbl_kcdashboard.No_actual_slp_households,
                tbl_kcdashboard.Physical_Accomplishment_Percentage_Original_POW,
                tbl_kcdashboard.Physical_Status_Original_POW,
                tbl_kcdashboard.Date_Started_Original_POW,
                tbl_kcdashboard.Date_Completed_Original_POW,
                tbl_kcdashboard.created_date,
                tbl_kcdashboard.Geotagging_SP_ID,
                tbl_kcdashboard.created_by,
                tbl_kcdashboard.last_updated_date,
                tbl_kcdashboard.last_updated_by
                FROM
                tbl_kcdashboard";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            $json_data = array("data" => $data);
            echo json_encode($json_data);
        }else{
            return false;
        }

    }

    public function total_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                count(tbl_kcdashboard.Geotagging_SP_ID) as totalSp
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return $row['totalSp'];
        }else{
            return false;
        }
    }
    public function ongoing_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                count(tbl_kcdashboard.Geotagging_SP_ID) as totalSp
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.Physical_Status_Original_POW='On-going' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return $row['totalSp'];
        }else{
            return false;
        }
    }
    public function completed_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                count(tbl_kcdashboard.Geotagging_SP_ID) as totalSp
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.Physical_Status_Original_POW='Completed' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return $row['totalSp'];
        }else{
            return false;
        }
    }
    public function totalHH_target_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                sum(tbl_kcdashboard.No_of_household_beneficiaries_target) as total_targetHH
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return $row['total_targetHH'];
        }else{
            return false;
        }
    }
    public function totalHH_actual_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                sum(tbl_kcdashboard.No_of_household_beneficiaries_actual) as total_actualHH
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return $row['total_actualHH'];
        }else{
            return false;
        }
    }

    public function final_grantMibf_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                sum(tbl_kcdashboard.Final_Grant_Amount_MIBF) as final_grant_mibf
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return number_format($row['final_grant_mibf'],2);
        }else{
            return false;
        }
    }

    public function final_amntDownloaded_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                sum(tbl_kcdashboard.Final_Amount_Downloaded) as final_amnt_downloaded
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return number_format($row['final_amnt_downloaded'],2);
        }else{
            return false;
        }
    }

    public function final_LccDelivery_subProject($cadt,$batch,$cycle){
        $mysql = $this->connectDatabase();
        $q="SELECT
                sum(tbl_kcdashboard.Final_LCC_Amount_Actual_Delivery) as final_LCC_delivery
                FROM
                tbl_kcdashboard
                WHERE tbl_kcdashboard.CADT='$cadt' AND tbl_kcdashboard.IPCDD_BATCH='$batch' AND tbl_kcdashboard.Cycle='$cycle'";
        $result = $mysql->query($q) or die ($mysql->error);
        if($result->num_rows>0){
            $row = $result->fetch_assoc();
            return number_format($row['final_LCC_delivery'],2);
        }else{
            return false;
        }
    }
}