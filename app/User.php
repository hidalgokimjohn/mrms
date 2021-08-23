<?php

namespace app;

class User
{

    public $first_name;
    public $last_name;
    public $pic_url;
    public $username;
    public $position;
    public $position_abbrv;
    public $user_group;
    public $status;
    public $email;
    public $access_level;
    public $is_checked;
    public $is_checked_ipcdd;
    public $validation_errors;

    public function connectDatabase()
    {
        $database = Database::getInstance();
        return $database->getConnection();
    }

    public function is_pending($user)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT users.status FROM users WHERE users.username= ? ") or die($mysql->error);
        $q->bind_param('s', $user);
        $q->execute();
        $result = $q->get_result();
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0 && $row['status'] == 'pending') {
            return true;
        } else {
            return false;
        }
    }

    public function permission($user)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_user_permission.permission,
				user_permission.fk_username
				FROM
				user_permission
				INNER JOIN lib_user_permission ON user_permission.fk_user_permission = lib_user_permission.id
				WHERE user_permission.fk_username = ? ");
        $q->bind_param('s', $user);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row['permission'];
            }
            return $_SESSION['user_auth'] = array("permission" => $data);
            //return $_SESSION['user_auth'] = array("permission" => $data, "user_details" => array('username' => $row['fk_username']));
        } else {
            return false;
        }
    }

    public function is_usernameExist($username)
    {
        $mysql = $this->connectDatabase();
        $username = $mysql->escape_string($username);
        $q = "SELECT users.username FROM users WHERE users.username='$username'";
        $result = $mysql->query($q);
        if ($result->num_rows == 1) {
            return true;
        }
    }

    public function is_emailExist($email)
    {
        $mysql = $this->connectDatabase();
        $email = $mysql->escape_string($email);
        $q = "SELECT users.username FROM users WHERE users.email_address='$email'";
        $result = $mysql->query($q);
        if ($result->num_rows == 1) {
            return true;
        }
    }

    public function register()
    {
        $mysql = $this->connectDatabase();
        $user = $mysql->escape_string($_POST['username']);
        $pass = $mysql->escape_string($_POST['password']);
        $name = $mysql->escape_string($_POST['name']);
        $email = $mysql->escape_string($_POST['email']);
        $last_name = $mysql->escape_string($_POST['last_name']);
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $q = "INSERT INTO `users` (`username`, `password`, `email_address`, `status`, `created_at`) VALUES ('$user', '$hash', '$email', 'pending', now())";
        $execute = $mysql->query($q) or die ($mysql->error);
        $r = "INSERT INTO `personal_info` (`fk_username`, `first_name`, `last_name`,`pic_url`) VALUES ('$user', '$name', '$last_name','default.jpg')";
        $execute = $mysql->query($r);
    }

    public function info($username)
    {

        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				lib_user_positions.user_position,
				lib_user_positions.user_position_abbrv,
				personal_info.fk_username,
				personal_info.first_name,
				personal_info.last_name,
       			personal_info.pic_url
				FROM
				users
				INNER JOIN personal_info ON personal_info.fk_username = users.username
				INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
				WHERE users.username= ? ");
        $q->bind_param('s', $username);
        $q->execute();
        $result = $q->get_result();
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            $this->username = $row['fk_username'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->pic_url = $row['pic_url'];
            $this->position = $row['user_position'];
            $this->position_abbrv = $row['user_position_abbrv'];
            $_SESSION['pic_url'] = $row['pic_url'];
            return true;
        } else {
            return false;
        }
    }

    public function has_accessTo($access)
    {
        if (in_array($access, $_SESSION['user_auth']['permission'])) {
            $index = array_search($access, $_SESSION['user_auth']['permission']);
            $this->access_level = $access = $_SESSION['user_auth']['permission'][$index];
            return true;
        } else {
            return false;
        }
    }

    public function table_users()
    {
        $mysql = $this->connectDatabase();
        $params = $_REQUEST;
        $columns = array(0 => 'personal_info.fk_username', 1 => 'fullname', 2 => 'personal_info.pic_url', 3 => 'users.email_address', 4 => 'lib_user_positions.user_position', 5 => 'users.status');
        $where_con = $sqlTot = $sqlRec = "";
        if (!empty($params['search']['value'])) {
            $where_con .= " AND ( personal_info.fk_username LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.first_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.last_name LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR personal_info.pic_url LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR users.email_address LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR lib_user_positions.user_position LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR users.status LIKE '%" . $params['search']['value'] . "%'";
            $where_con .= " OR users.created_at LIKE '%" . $params['search']['value'] . "%')";

        }
        $q = "SELECT
					personal_info.fk_username,
					CONCAT(personal_info.first_name,' ',personal_info.last_name) AS fullname,
					personal_info.pic_url,
					users.email_address,
					lib_user_positions.user_position,
					users.`status`,
					DATE_FORMAT(users.created_at, '%m-%d-%Y'),
       				personal_info.first_name,
       				personal_info.last_name
					FROM
					users
					INNER JOIN personal_info ON personal_info.fk_username = users.username
					LEFT JOIN lib_user_positions ON users.fk_position = lib_user_positions.id 
					WHERE 1=1 ";
        $sqlTot .= $q;
        $sqlRec .= $q;

        if (isset($where_con) && $where_con != '') {
            $sqlTot .= $where_con;
            $sqlRec .= $where_con;

        }

        $sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'] . " ";
        $query_tot = $mysql->query($sqlTot) or die($mysql->error);
        $total_records = $query_tot->num_rows;
        $query_records = $mysql->query($sqlRec) or die($mysql->error);
        if ($query_records->num_rows > 0) {
            while ($row = $query_records->fetch_row()) {
                $data[] = $row;
            }
        }
        $json_data = array("draw" => intval($params['draw']), "recordsTotal" => intval($total_records), "recordsFiltered" => intval($total_records), "data" => (isset($data) ? $data : ''));
        echo json_encode($json_data);

    }

    public function disable($username)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("UPDATE `users` SET `status`='disabled' WHERE (`username`= ? ) LIMIT 1");
        $q->bind_param('s', $username);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function enable($username)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("UPDATE `users` SET `status`='active' WHERE (`username`= ? ) LIMIT 1");
        $q->bind_param('s', $username);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function user_positions()
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
      			lib_user_positions.id,
				lib_user_positions.user_position
				FROM
				lib_user_positions");
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function update_userPosition($pos_id, $username)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("UPDATE `users` SET `fk_position`= ? WHERE (users.username=?) LIMIT 1");
        $q->bind_param('is', $pos_id, $username);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_password()
    {
        $mysql = $this->connectDatabase();
        $pass = $mysql->escape_string($_POST['curr_password']);
        $check_curPass = "SELECT
                users.username,
                users.`password`
                FROM
                users
                WHERE users.username='$_SESSION[username]'";
        $result = $mysql->query($check_curPass) or die($mysql->error);
        if ($result) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                $new_pass = $mysql->escape_string($_POST['new_password']);
                $hash_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $user = $_SESSION['username'];
                $update_pass = "UPDATE `users` SET `password`='$hash_new_pass' WHERE (`username`='$user') LIMIT 1";
                $result = $mysql->query($update_pass);
                if ($result) {
                    return 'updated';
                } else {
                    return false;
                }
            } else {
                return 'incorrect password';
            }
        }
    }

    public function user_coverage($username, $city_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
				user_city_coverage.is_checked
				FROM
				user_city_coverage
				WHERE user_city_coverage.fk_username = ? AND user_city_coverage.fk_psgc_mun = ?");
        $q->bind_param('si', $username, $city_id);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->is_checked = $row['is_checked'];
            return true;
        } else {
            return false;
        }
    }

    public function user_coverage_ipcdd($username, $cadt_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("SELECT
            user_cadt_coverage.fk_username,
            user_cadt_coverage.fk_cadt,
            user_cadt_coverage.is_checked
            FROM
            user_cadt_coverage
            WHERE user_cadt_coverage.fk_username = ? AND	user_cadt_coverage.fk_cadt = ? ");
        $q->bind_param('si', $username, $cadt_id);
        $q->execute();
        $result = $q->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->is_checked_ipcdd = 'checked';
            return true;
        } else {
            return false;
        }
    }

    public function add_cityCoverage($username, $city_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("INSERT INTO `user_city_coverage` (`fk_username`, `fk_psgc_mun`, `is_checked`) VALUES (?, ?, 'yes')");
        $q->bind_param('si', $username, $city_id);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_ipcddCoverage($username, $cadt_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("INSERT INTO `user_cadt_coverage` (`fk_username`, `fk_cadt`, `is_checked`) VALUES (?, ?, 'yes')");
        $q->bind_param('si', $username, $cadt_id);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function remove_ipcddCoverage($username, $cadt_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("DELETE FROM `user_cadt_coverage` WHERE (`fk_username`=? and fk_cadt=?)");
        $q->bind_param('si', $username, $cadt_id);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function remove_cityCoverage($username, $city_id)
    {
        $mysql = $this->connectDatabase();
        $q = $mysql->prepare("DELETE FROM `user_city_coverage` WHERE (`fk_username`=? AND `fk_psgc_mun`=?)");
        $q->bind_param('si', $username, $city_id);
        $q->execute();
        if ($q->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_ipccd_coverage($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            lib_cadt.cadt_name,
            lib_cadt.id
            FROM
            lib_cadt";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($this->user_coverage_ipcdd($username, $row['id'])) {
                    $row['is_checked'] = $this->is_checked_ipcdd;
                } else {
                    $row['is_checked'] = '';
                }
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function get_staff($position)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
				CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullname,
				personal_info.fk_username,
				lib_user_positions.user_position
				FROM
				users
				INNER JOIN personal_info ON personal_info.fk_username = users.username
				INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
				WHERE lib_user_positions.user_position_abbrv IN ($position)";
        var_dump($q);
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function get_userArea($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        user_city_coverage.fk_psgc_mun,
        user_city_coverage.fk_username
        FROM
        user_city_coverage
        where user_city_coverage.fk_username='$username' LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['fk_psgc_mun'];
        } else {
            return false;
        }
    }

    public function userTeams($psgc_mun)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullName,
            user_city_coverage.fk_username,
            lib_user_positions.user_position_abbrv,
            personal_info.first_name,
            personal_info.last_name,
            personal_info.pic_url
            FROM
            user_city_coverage
            INNER JOIN users ON user_city_coverage.fk_username = users.username
            INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
            INNER JOIN personal_info ON personal_info.fk_username = users.username
            WHERE user_city_coverage.fk_psgc_mun='$psgc_mun' AND lib_user_positions.user_position_abbrv IN ('AC','CEF')";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function get_userArea_ipcdd($username)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
        user_cadt_coverage.fk_cadt,
        user_cadt_coverage.fk_username
        FROM
        user_cadt_coverage
        where user_cadt_coverage.fk_username='$username' LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['fk_cadt'];
        } else {
            return false;
        }
    }

    public function userTeams_ipcdd($cadt)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT
            CONCAT(personal_info.first_name,' ',personal_info.last_name) as fullName,
            user_cadt_coverage.fk_username,
            lib_user_positions.user_position_abbrv,
            personal_info.first_name,
            personal_info.last_name,
            personal_info.pic_url
            FROM
            user_cadt_coverage
            INNER JOIN users ON user_cadt_coverage.fk_username = users.username
            INNER JOIN lib_user_positions ON users.fk_position = lib_user_positions.id
            INNER JOIN personal_info ON personal_info.fk_username = users.username
            WHERE user_cadt_coverage.fk_cadt='$cadt' AND lib_user_positions.user_position_abbrv IN ('AC','CEF')";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function sso_isExist($user_sso)
    {
        $mysql = $this->connectDatabase();
        $user_sso = $user_sso->toArray();
        $oauth = $user_sso['sub'];
        $username = $user_sso['preferred_username'];
        $fname = $user_sso['given_name'];
        $lname = $user_sso['family_name'];
        $name = $user_sso['name'];

        $q = "SELECT
            users.oauth_client
            FROM
            users where oauth_client='$oauth'";

        $result = $mysql->query($q);
        $row = $result->fetch_assoc();

        if ($row['oauth_client']) {
            return 1;
        } else {
            return 0;
        }
    }

    public function register_sso($user_sso)
    {
        $mysql = $this->connectDatabase();
        $user_sso = $user_sso->toArray();
        $oauth = $user_sso['sub'];
        $username = $user_sso['preferred_username'];
        $fname = $user_sso['given_name'];
        $lname = $user_sso['family_name'];
        $name = $user_sso['name'];

        $user = $username;
        $pass = "default123$";
        $name = $name;
        $email = '';
        $last_name = $lname;
        $scenario = 'oauth_create';

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $q = "INSERT INTO `users` (`username`, `password`, `email_address`, `status`, `created_at`,`scenario`,`oauth_client`,`oauth_client_user_id`) VALUES ('$user', '$hash', '$email', 'pending', now(),'$scenario','$oauth','$oauth')";
        $execute = $mysql->query($q) or die ($mysql->error);
        $r = "INSERT INTO `personal_info` (`fk_username`, `first_name`, `last_name`,`pic_url`) VALUES ('$user', '$fname', '$last_name','default.jpg')";
        $execute = $mysql->query($r) or die($mysql->error);
    }

    public function getUserPositions($id)
    {
        $mysql = $this->connectDatabase();
        $q = "SELECT * FROM lib_user_positions WHERE lib_user_positions.id='$id'";
        $result = $mysql->query($q) or die($mysql->error);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function create_external_account(){
        $mysql = $this->connectDatabase();
        $password = $_POST['password'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $email = $mysql->real_escape_string($_POST['email']);
        $username = strstr($email, '@', true);
        $first_name = $mysql->real_escape_string($_POST['first_name']);
        $last_name = $mysql->real_escape_string($_POST['last_name']);
        $position = $mysql->real_escape_string($_POST['ext_position']);
        $position=$this->getUserPositions($position);
        $position_abbrv = $position['user_position_abbrv'];
        $position_desc = $position['user_position'];
        $assigned_area = $mysql->real_escape_string($_POST['assigned_area']);
        $q="INSERT INTO `tbl_users`(`id_number`, `username`, `password`, `created_at`, `user_type`) VALUES ('$email','$username','$hash', NOW(), 'external_user')";
        $result = $mysql->query($q) or die($mysql->error);
        if($mysql->affected_rows>0){
            //insert person info
            $q1="INSERT INTO `tbl_person_info` ( `fk_id_number`, `first_name`, `last_name`,`status`, `position_name`, `position_desc`, `office_name`, `office_desc`, `created_at`,`avatar_path`,`fk_username`)
                VALUES
                    (
                        '$email',
                        '$first_name',
                        '$last_name',
                        'Active',
                        '$position_abbrv',
                        '$position_desc',
                        'ACT',
                        'Municipal Coordinating Team',
                        NOW(),
                        'https://caraga-portal.dswd.gov.ph/media/picture/default.jpg',
                        '$username'
                    )";
            $result1 = $mysql->query($q1) or die($mysql->error);
            if($mysql->affected_rows>0){
                  return true;
            }
          
        }else{
            return false;
        }

    }

    public function confirm_password($pass1,$pass2){
        if($pass1==$pass2){
            return true;
        }else{
            return false;
        }
    }

    public function validateRegisterForm()
    {
        $errors = [];
        //start check empty fields
        $required = array('first_name', 'last_name', 'ext_position', 'assigned_area', 'email', 'password','confirm_password');
        $error = false;
        foreach($required as $field) {
            if (empty($_POST[$field])) {
                $error = true;
                echo $field.' is empty';
            }
        }
        //end check empty fields

        if ($error) {
            $errors[] = 'All fields are required';
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        if ($this->emailExist($_POST['email'])) {
            $errors[] = "Email is already taken. Try another email";
        }
        if (strlen($_POST['password']) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }
        if (!$this->confirm_password($_POST['password'],$_POST['confirm_password'])) {
            $errors[] = "Password did not matched";
        }
        $fields = ['First name' => $_POST['first_name'], 'Last name' => $_POST['last_name']];
        foreach ($fields as $item => $value) {
            if (preg_match('/[^a-z\s-]/i', $value)) {
                $errors[] = $item . " <strong>" . $value . "</strong>" . " contains invalid character";
            }
        }
        if (empty($errors)) {
            return true;
        } else {
            $this->validation_errors = $errors;
            return false;
        }
    }

    public function emailExist($email){
        $mysql = $this->connectDatabase();
        $email = $mysql->real_escape_string($email);
        $q="SELECT
                tbl_users.id_number
            FROM
                tbl_users
                WHERE tbl_users.id_number = '$email' LIMIT 1";
        $result = $mysql->query($q) or die($mysql->error);
        if($result->num_rows>0){
            return true;
        }else{
            return false;
        }
    }

}