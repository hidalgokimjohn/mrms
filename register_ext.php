<?php
include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');
include_once('app/User.php');
$app = new \app\App();
$auth = new \app\Auth();
$user = new \app\User();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
          content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="shortcut icon" href="resources/img/icons/icon-48x48.png"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css"/>
    <link rel="canonical" href="https://demo.adminkit.io/pages-blank.html"/>

    <title>MRMS | Create External Account</title>
    <link href="resources/css/app.css" rel="stylesheet">
    <script type="text/css">
        .choices[data-type*="select-one"] select.choices__input {
            display: block !important;
            opacity: 0;
            pointer-events: none;
            position: absolute;
            left: 0;
            bottom: 0;
        }
    </script>
    <!-- BEGIN SETTINGS -->
    <!-- END SETTINGS -->
</head>
<!--
  HOW TO USE:
  data-theme: default (default), dark, light
  data-layout: fluid (default), boxed
  data-sidebar: left (default), right
-->

<body data-theme="default" data-layout="fluid" data-sidebar="left">
<main class="d-flex w-100 h-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <div class="text-center mt-4">
                                    <h1 class="h2">Create External Account</h1>
                                    <p class="lead m-1">
                                        For External Users Only (MCEF, MDM)
                                    </p>
                                    <p class="m-0"> <small>Note: This module is intended for KC-AF Implementation only</small></p>
                                </div>
                                <form method="post" id="register_ext_users" action="<?php $_SERVER['PHP_SELF']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input class="form-control form-control-lg" type="text" name="first_name" placeholder="Enter your name" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input class="form-control form-control-lg" type="text" name="last_name" placeholder="Enter your last name" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Position</label>
                                        <select class="form-control text-capitalize" name="ext_position" required>
                                            <option value="">Select Position</option>
                                             <?php
                                            $positions = $app->getUserPosition('external_user');
                                            foreach ($positions as $position){
                                                echo '<option value="'.$position['id'].'">'.$position['user_position'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Assigned Area/Municipality/City</label>
                                        <select class="form-control" name="assigned_area" required>
                                            <option value="">Select Position</option>
                                            <?php
                                            $cities = $app->getAllCity();
                                            foreach ($cities as $city){
                                                echo '<option value="'.$city['psgc_mun'].'">'.$city['mun_name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" required/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Comfirm Password</label>
                                        <input class="form-control form-control-lg" type="password" name="confirm_password" placeholder="Re-type your password  " required/>
                                    </div>
                                    <div class="mt-3">
                                        <input type="submit" name="create_account" class="btn btn-lg btn-primary" value="Create">
                                       
                                        <?php
                                        if (isset($_POST['create_account'])) {
                                            if ($user->validateRegisterForm() == true) {
                                                $user->create_external_account();
                                                echo ' <div class="mt-3 alert alert-warning" role="alert">
                                            <div class="alert-icon">
                                                <i class="fa fa-check"></i>
                                            </div>
                                            <div class="alert-message">
                                                <strong>Account created successfully pending for activation</strong>
                                            </div>
                                        </div>';
                                            }else{
                                                ?>
                                                    <div class="mt-3 alert alert-danger alert-dismissible" role="alert">
                                                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                                                        <div class="alert-message">
                                                            <h4 class="alert-heading">Account creation failed!</h4>
                                                            <ul>
                                                            <?php
                                                                $errors = $user->validation_errors;
                                                                foreach ($errors as $error){
                                                                    echo '<li>'.$error.'</li>';
                                                                }
                                                            ?>
                                                            </ul>
                                                        </div>
                                                    </div>

                                           <?php  }
                                        }
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

    </div>
</main>
</body>

<script src="resources/js/jquery-3.5.1.js"></script>
<script src="resources/js/app.js"></script>
<!-- 3rd Party Plugin-->
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<!--Initialization-->
<script type="text/javascript" src="vendor/PDFObject-master/pdfobject.min.js"></script>
<script type="text/javascript" src="resources/js/dqa.js"></script>
<script type="text/javascript" src="resources/js/search.js"></script>
<script>

</script>
</html>