<?php
$area = $app->actView_areaInfo($_GET['cycle'], $_GET['area']);
$progress = $app->areaProgress($_GET['cycle'], $_GET['area']);
$sp = new \app\SubProject();
?>
<div class="col-sm-12 col-lg-12 col-xl-12">
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
                    <a href="home.php?p=mywork&m=view_area&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-arrow-left"></span> Back</a>
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
                    <table id="generateFindings"class="table-bordered generatedFindings bg-white table-sm font-monospace text-dark" width="100%px;" style="border-collapse: collapse; width: 21cm; height: 29.7cm;">
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
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0"><strong>Sub Project Implementation </strong><br><span><small>GWA as of <?php echo date('M d, Y', strtotime($sp->last_update())); ?>. Data is based from Geotagging WebApp (updated weekly). <br>To update the data visit <a href="https://geotagging.dswd.gov.ph/" target="_blank">https://geotagging.dswd.gov.ph/</a> <br/></small></span></h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-4">
                                    <h3><?php echo $sp->total_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h3>
                                    Total SPs
                                </div>
                                <div class="col-4">
                                    <h3><?php echo $sp->ongoing_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h3>
                                    On-going
                                </div>
                                <div class="col-4">
                                    <h3><?php echo $sp->completed_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h3>
                                    Completed
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <h5 class="h3 card-title"><strong>Household Beneficiaries</strong></h5>
                    <div class="row pt-1">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-3">
                                    <h4><?php echo $sp->totalHH_target_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h4>
                                    Target
                                </div>
                                <div class="col-3">
                                    <h4><?php echo $sp->totalHH_actual_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h4>
                                    Actual
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-body border-top">
                    <h5 class="h3 card-title"><strong>Financial Information</strong></h5>
                    <div class="row pt-1">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-6">
                                    <h4 class="font-weight-bolder">PHP <?php echo $sp->final_grantMibf_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h4>
                                    Final Grant Amount MIBF
                                </div>
                                <div class="col-6">
                                    <h4 class="font-weight-bolder">PHP <?php echo $sp->final_amntDownloaded_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h4>
                                    Final Amount Downloaded (BTF)
                                </div>
                                <div class="col-6 pt-2">
                                    <h4 class="font-weight-bolder">PHP <?php echo $sp->final_LccDelivery_subProject($area['area_name'],$area['batch'],$area['cycle_name']); ?></h4>
                                    Final LCC Amount Delivery
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-primary">
                    <a href="home.php?p=act&amp;m=view_more&amp;cycle=14&amp;area=10" class="text-light">View more</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5>Activity log</h5>
                    <ul class="timeline mt-2 mb-0">
                        <li class="timeline-item">
                            <strong>Signed out</strong>
                            <span class="float-right text-muted text-sm">30m ago</span>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Created invoice #1204</strong>
                            <span class="float-right text-muted text-sm">2h ago</span>
                            <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Discarded invoice #1147</strong>
                            <span class="float-right text-muted text-sm">3h ago</span>
                            <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Signed in</strong>
                            <span class="float-right text-muted text-sm">3h ago</span>
                            <p>Curabitur ligula sapien, tincidunt non, euismod vitae...</p>
                        </li>
                        <li class="timeline-item">
                            <strong>Signed up</strong>
                            <span class="float-right text-muted text-sm">2d ago</span>
                            <p>Sed aliquam ultrices mauris. Integer ante arcu...</p>
                        </li>
                    </ul>
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
