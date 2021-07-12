<?php
$area = $app->actView_areaInfo($_GET['cycle'], $_GET['area']);
$progress = $app->areaProgress($_GET['cycle'], $_GET['area']);
$sp = new \app\SubProject();
?>
<div class="col-sm-12 col-lg-12 col-xl-10">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0 text-capitalize"><strong><?php echo $area['area_name']; ?></strong></h5>
                    <div class="badge bg-primary my-2"><?php echo $area['year']; ?></div>
                    <div class="badge bg-success my-2 text-capitalize"><?php echo $area['batch'] . ' ' . $area['cycle_name']; ?></div>
                    <div class="badge bg-success my-2 text-capitalize"><?php echo $area['status']; ?></div>
                    <h5 >Members</h5>
                    <?php
                    $av = $app->getACTMembers_avatar($_GET['cycle'], $_GET['area']);
                    foreach ($av as $avatar) {
                        echo '<img src="' . $avatar['avatar_path'] . '" class="rounded-circle mr-1" alt="' . $avatar['username'] . '" title="@' . $avatar['username'] . '" width="48" height="48">';
                    }
                    ?>
                    <p class="mb-2 font-weight-bold mt-2">MOV Progress <span class="float-right"><?php echo $progress; ?>%</span>
                    </p>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress; ?>%;">
                        </div>
                    </div>
                </div>
                <div class="m-3 mt-1">
                    <a href="home.php?p=act&m=view_more&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-arrow-left"></span> Back</a>
                    <a href="home.php?p=act&m=by_brgy&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-bars"></span> By Barangay</a>
                    <a href="#" class="p-1"><span class="fa fa-users"></span> Set up members</a>
                    <a href="home.php?p=generate_findings&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-print"></span> Generate Findings</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <button class="btn btn-success mb-3" id="generate_findings"><span class="fa fa-print"></span> Print</button>
                    <button class="btn btn-success mb-3" onclick="Export()"><span class="fa fa-file-pdf"></span> PDF</button>
                    <style type="text/css">
                        table, thead, tbody,tr,td {
                            border: 1px solid black;
                            font-family: Calibri;
                            font-size: 12px;
                        }
                    </style>
                    <table id="generateFindings"class="table-bordered generatedFindings bg-white table-sm font-monospace text-dark" width="100%px;" style="border-collapse: collapse; width: 21cm; height: auto;">
                        <thead>
                        <tr>
                            <td colspan="4" class="text-center font-weight-bold"><?php echo $area['area_name'].' '.$area['batch'].' '.$area['cycle_name']; ?></td>
                        </tr>
                        <tr CLASS="font-weight-bold">
                            <td>Cadt/Mun/Barangay</td>
                            <td>Form/Output</td>
                            <td width="40%">Findings</td>
                            <td>Status</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $dqa->generate_findings($_GET['area'],$_GET['cycle']);

                        ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalViewActivity" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title activity_title"></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="table-responsive">
                <table id="tbl_actFiles" class="table table-hover" style="width:100%">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 10%;">Status</th>
                        <th style="width: 30%;">Filename</th>
                        <th style="width: 30%;">Form</th>
                        <th style="width: 20%;">Mun/Barangay</th>
                        <th style="width: 10%;">Uploaded</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
