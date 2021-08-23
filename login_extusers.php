<?php
ob_start();

include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');

$app = new \app\App();
$authen = new \app\Auth();

if ($authen->loggedIn()) {
    if($_SESSION['user_lvl']=='ACT'){
        header('location: https://crg-kcapps-svr.entdswd.local/mrms/home.php?p=act&m=main');
        exit();
    }
    if($_SESSION['user_lvl']=='RPMO'){
        header('location: https://crg-kcapps-svr.entdswd.local/mrms/home.php?p=dashboards&modality=ipcdd_drom');
        exit();
        }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../resources/img/icons/icon-48x48.png"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <link rel="canonical" href="https://demo.adminkit.io/pages-sign-in.html"/>

    <title>Sign In | MRMS</title>

    <link href="resources/css/app.css" rel="stylesheet">

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
                                <form method="post">
                                    <div class="text-center mt-4">
                                        <h1 class="h2">MOV Repository & Management System</h1>
                                        <p class="lead">
                                            CARAGA | Kalahi-CIDSS<!-- <small>Beta 4.0</small>-->
                                        </p>
                                    </div>
                                    <div class="mb-3 text-center">
                                        MRMS is a regionally-managed database which serves as the repository (or storehouse) of the program documents or Means of Verification (MOVs) of the various program activities.
                                    </div>
                                    <div class="mb-3">
                                        <div class="alert alert-warning" role="alert">
                                            <div class="alert-message text-center">
                                                This Login Is <strong>For External Users Only</strong>
                                            </div>
                                        </div>
                                        <label class="form-label">Email</label>
                                        <input class="form-control form-control-lg" type="text" name="email"
                                               placeholder="Enter your email"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password"
                                               placeholder="Enter your password"/>
                                        <small>
                                            <a href="register_ext.php">Create External Account</a>
                                        </small>
                                        <small class="float-right">
                                            <a href="/mrms/">Back to Normal Login</a>
                                        </small>
                                    </div>
                                    <div>
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" value="remember-me"
                                                   name="remember-me" checked>
                                            <span class="form-check-label">
													Remember me next time
                                            </span>
                                        </label>
                                    </div>
                                    <div class="text-center mt-3">
                                         <input type="submit" name="submit" class="btn btn-lg btn-primary" value="Sign in">
                                    </div>
                                    <?php
                                    if (isset($_POST['submit'])) {
                                        /* if ($app->is_pending_ext($_POST['email'])) {
                                             echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
                                             <div class="alert-icon">
                                                 <i data-feather="alert-circle"></i>
                                             </div>
                                             <div class="alert-message">
                                                 <strong>Ops!</strong> This account is pending for activation.
                                             </div>
                                             </div>';
                                         } else {*/
                                        if ($z=$app->login($_POST['email'], $_POST['password'])) {
                                            $log = $app->log($_SESSION['email'], 'login', 'has logged in', null, null);
                                            if($_SESSION['user_lvl']=='ACT'){
                                                header('location: home.php?p=act&m=main');
                                                exit;
                                            }
                                            if($_SESSION['user_lvl']=='RPMO'){
                                                header('location: home.php?p=dashboards&modality=ipcdd_drom');
                                                exit;
                                            }
                                            exit;
                                        } else {
                                            echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
											<div class="alert-icon">
												<i data-feather="alert-circle"></i>
											</div>
											<div class="alert-message">Incorrect email or password. Please try again.</div>
										    </div>';
                                        }
                                    }
                                    
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script src="resources/js/app.js"></script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/604990d6385de407571edc94/1f0g89o21';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->
</body>
</html>