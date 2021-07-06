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
                    <h5>Members</h5>
                    <?php
                    $av = $app->getACTMembers_avatar($_GET['cycle'], $_GET['area']);
                    foreach ($av as $avatar) {
                        echo '<img src="' . $avatar['avatar_path'] . '" class="rounded-circle mr-1" alt="' . $avatar['username'] . '" title="@' . $avatar['username'] . '" width="48" height="48">';
                    }
                    ?>
                    <p class="mb-2 font-weight-bold mt-2">MOV Progress <span
                                class="float-right"><?php echo $progress; ?>%</span>
                    </p>
                    <div class="progress progress-sm">
                        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="100"
                             aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress; ?>%;">
                        </div>
                    </div>
                </div>
                <div class="m-3 mt-1">
                    <a href="home.php?p=act&m=by_brgy&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>"
                       class="p-1"><span class="fa fa-bars"></span> By Barangay</a>
                    <a href="#" class="p-1"><span class="fa fa-users"></span> Set up members</a>
                    <a href="home.php?p=mywork&m=generate_findings&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>"
                       class="p-1"><span class="fa fa-print"></span> Generate Findings</a>
                </div>
            </div>
            <div class="card">
                <div class="m-3">
                    <a href="home.php?p=mywork&m=view_area&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>"
                       class="pr-3"><span class="fa fa-arrow-left"></span> Back</a>
                    <span class="font-weight-bold"> <?php echo $app->getActivityName($_GET['activity_id']); ?></span>
                </div>
                <div class="table-responsive">
                    <table id="tbl_actFiles" class="table table-hover " style="width:100%">
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
        <!--<div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0"><strong>Sub Project
                            Implementation </strong><br><span><small>GWA as of <?php /*echo date('M d, Y', strtotime($sp->last_update())); */ ?>. Data is based from Geotagging WebApp (updated weekly). <br>To update the data visit <a
                                        href="https://geotagging.dswd.gov.ph/" target="_blank">https://geotagging.dswd.gov.ph/</a> <br/></small></span>
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-4">
                                    <h3><?php /*echo $sp->total_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h3>
                                    Total SPs
                                </div>
                                <div class="col-4">
                                    <h3><?php /*echo $sp->ongoing_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h3>
                                    On-going
                                </div>
                                <div class="col-4">
                                    <h3><?php /*echo $sp->completed_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h3>
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
                                <div class="col-4">
                                    <h4><?php /*echo $sp->totalHH_target_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h4>
                                    Target
                                </div>
                                <div class="col-4">
                                    <h4><?php /*echo $sp->totalHH_actual_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h4>
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
                                <div class="col-4">
                                    <h4 class="font-weight-bolder">
                                        PHP <?php /*echo $sp->final_grantMibf_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h4>
                                    Final Grant Amount MIBF
                                </div>
                                <div class="col-4">
                                    <h4 class="font-weight-bolder">
                                        PHP <?php /*echo $sp->final_amntDownloaded_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h4>
                                    Final Amount Downloaded (BTF)
                                </div>
                                <div class="col-4">
                                    <h4 class="font-weight-bolder">
                                        PHP <?php /*echo $sp->final_LccDelivery_subProject($area['area_name'], $area['batch'], $area['cycle_name']); */ ?></h4>
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
        </div>-->
    </div>
</div>
<div class="modal fade" id="modalViewFile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title file-name text-uppercase"></h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-3">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" class="g-3 needs-validation" novalidate id="submitFinding">
                                    <label for="choicesFinding" class="form-label">With Findings?</label>
                                    <select id="choicesFinding" class="form-control choices-findings"
                                            name="withFindings">
                                        <option value="">Select Options</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="ta">Give TA</option>
                                    </select>
                                    <label for="choicesTypeOfFindings" class="form-label">Type of Findings</label>
                                    <select id="choicesTypeOfFindings" class="form-control choices-type-of-findings"
                                            name="typeOfFindings">
                                        <option value="">Select Options</option>
                                        <?php
                                        foreach ($app->getTypeOfFindings() as $options) {
                                            echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['findings_type']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="text_findings">Findings/TA</label>
                                    <textarea name="textFindings" id="text_findings" class="form-control"
                                              required></textarea>
                                    <br>
                                    <label for="responsiblePerson" class="form-label">Responsible Person</label>
                                    <select id="responsiblePerson" class="form-control choices-staff"
                                            name="responsiblePerson">
                                        <option value="">Select Staff</option>
                                        <?php
                                        foreach ($app->getActUser() as $act) {
                                            echo '<option class="text-capitalize" value="' . $act['id_number'] . '">' . ucwords(strtolower($act['fname'] . ' ' . $act['mname'] . ' ' . $act['lname'])) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label class="form-label">Date of Compliance</label>
                                    <input type="text" name="dateOfCompliance" class="form-control flatpickr-minimum"
                                           id="dateOfCompliance" placeholder="Select date.." required>
                                    <br>
                                    <label for="dqaLevel" class="form-label">DQA Level</label>
                                    <select id="dqaLevel" class="form-control choices-dqa-level" name="dqaLevel">
                                        <option value="">Select Options</option>
                                        <option value="field office">Field Office</option>
                                        <option value="field act">Field (ACT)</option>
                                    </select>
                                    <button class="btn btn-primary" type="submit" id="btnSubmitFinding"><span
                                                class="fa fa-save"></span> Submit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="card">
                            <div class="card-body">
                                <div id="pdf" class="mb-3 bg-light">

                                </div>
                                <h3>Findings</h3>
                                <div class="loading-screen">
                                    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100 p-3">
                                        <div class="d-table-cell align-middle">
                                            <div class="text-center">
                                                <div class="spinner-border text-primary mr-2" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div id="displayFindings">

                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-12">
                             <div class="card">
                                 <div class="card-header">
                                     Findings
                                 </div>

                             </div>
                         </div>-->
                    </div>
                    <!--<div class="col-sm-2">
                        <div class="card">
                            <div class="card-header">
                                <h3>Related files</h3>
                            </div>
                            <div class="list-group list-group-flush" role="tablist" id="relatedFiles">

                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>