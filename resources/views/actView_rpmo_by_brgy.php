<?php
$area = $app->actView_areaInfo($_GET['cycle'], $_GET['area']);
$progress = $app->areaProgress($_GET['cycle'], $_GET['area']);
$sp = new \app\SubProject();
?>
<div class="col-sm-9 col-xl-12">
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
                    <a href="home.php?p=mywork&m=view_more&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-arrow-left"></span> Back</a>
                    <a href="home.php?p=act&m=by_brgy&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-bars"></span> By Barangay</a>
                    <a href="#" class="p-1"><span class="fa fa-users"></span> Set up members</a>
                    <a href="home.php?p=generate_findings&cycle=<?php echo $_GET['cycle'] ?>&area=<?php echo $_GET['area'] ?>" class="p-1"><span class="fa fa-print"></span> Generate Findings</a>

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="tbl_by_brgy" style="width:100%;">
                                <thead>
                                <tr class="border-bottom-0">
                                    <th>CADT Area</th>
                                    <th>Cycle Name</th>
                                    <th>MDRRMC TCM</th>
                                    <th>MDRRMC Meeting</th>
                                    <th>SOC Investigation</th>
                                    <th>RACE I</th>
                                    <th>ICC Meeting</th>
                                    <th>BDRRMC Meeting</th>
                                    <th>MIAC
                                        Tech</th>
                                    <th>MIAC TechIP</th>
                                    <th>GRS</th>
                                    <th>Finance Training</th>
                                    <th>Inra Training</th>
                                    <th>Proc Training</th>
                                    <th>CV Profile</th>
                                    <th>Opening Bank</th>
                                    <th>Proc Act</th>
                                    <th>SPI</th>
                                    <th>ICC_BDRRMC_Meeting</th>
                                    <th>Plan_OM</th>
                                    <th>Reflection</th>
                                    <th>AR</th>
                                    <th>SUS_Plan</th>
                                    <th>FA</th>
                                    <th>Report_Liquidation</th>
                                    <th>Closing_Accounts</th>
                                    <th>Overall</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($ipcdd = $app->view_tbl_by_brgy_milestone($_GET['cycle'],$_GET['area']))) {
                                    foreach ($ipcdd as $item){
                                        echo '<tr>';
                                        echo '<td>'.$item['location'].'</td>';
                                        echo '<td>'.$item['cycle_name'].'</td>';
                                        echo '<td>'.$item['MDRRMC_TCM'].'</td>';
                                        echo '<td>'.$item['MDRRMC_Meeting'].'</td>';
                                        echo '<td>'.$item['SOC_Investigation'].'</td>';
                                        echo '<td>'.$item['RACE_I'].'</td>';
                                        echo '<td>'.$item['ICC_Meeting'].'</td>';
                                        echo '<td>'.$item['BDRRMC_Meeting'].'</td>';
                                        echo '<td>'.$item['MIAC_Tech'].'</td>';
                                        echo '<td>'.$item['MIAC_TechIP'].'</td>';
                                        echo '<td>'.$item['GRS'].'</td>';
                                        echo '<td>'.$item['Finance_Training'].'</td>';
                                        echo '<td>'.$item['Infra_Training'].'</td>';
                                        echo '<td>'.$item['Proc_Training'].'</td>';
                                        echo '<td>'.$item['CV_Profile'].'</td>';
                                        echo '<td>'.$item['Opening_Bank'].'</td>';
                                        echo '<td>'.$item['Proc_Act'].'</td>';
                                        echo '<td>'.$item['SPI'].'</td>';
                                        echo '<td>'.$item['ICC_BDRRMC_Meeting'].'</td>';
                                        echo '<td>'.$item['Plan_OM'].'</td>';
                                        echo '<td>'.$item['Reflection'].'</td>';
                                        echo '<td>'.$item['AR'].'</td>';
                                        echo '<td>'.$item['SUS_Plan'].'</td>';
                                        echo '<td>'.$item['FA'].'</td>';
                                        echo '<td>'.$item['Report_Liquidate'].'</td>';
                                        echo '<td>'.$item['Closing_Accounts'].'</td>';
                                        echo '<td>'.$item['overall'].'</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
