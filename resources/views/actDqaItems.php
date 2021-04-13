<?php

$dqaInfo=$app->getDqaInfo($_GET['dqaid']);

?>

<div class="col-md-9 col-xl-10">
    <div class="card mb-2">
        <h5 class="p-3 text-capitalize card-title"><strong><a href="home.php?p=act&m=findings" class=""><span class="fa fa-arrow-left"></span> Back </a><span class="ml-3"><?php echo '#'.$dqaInfo['id'].' '.$dqaInfo['title']; ?></span></strong></h5>
        <div class="table-responsive">
            <style type="text/css">
                td a {
                    display: block;

                }
            </style>
            <table id="tbl_actDqaItems" class="table table-hover" style="width:100%">
                <thead>
                <tr class="border-bottom-0">
                    <th style="width: 10%;"></th>
                    <th style="width: 25%;">Filename</th>
                    <th style="width: 25%;">Form</th>
                    <th style="width: 15%;">Area</th>
                    <th style="width: 10%;">Uploader</th>
                    <th style="width: 15%;">Reviewer</th>
                    <th style="width: 10%;">Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
    </div>
</div>