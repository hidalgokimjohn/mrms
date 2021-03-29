<?php
$area = $app->actView_areaInfo($_GET['cycle'], $_GET['area']);
$progress = $app->areaProgress($_GET['cycle'], $_GET['area']);
if(!$area==false){
    //has_access show page
    ?>
    <!--<h1 class="h3 mb-3">My Work / <?php /*echo $area['area_name']; */?></h1>-->
    <div class="col-md-3 col-xl-12">
        <div class="row">

            <div class="col-12 col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-0 text-capitalize"><strong><?php echo $area['area_name']; ?></strong></h5>
                        <div class="badge bg-primary my-2"><?php echo $area['year']; ?></div>
                        <div class="badge bg-success my-2 text-capitalize"><?php echo $area['batch'] . ' ' . $area['cycle_name']; ?></div>
                        <div class="badge bg-success my-2 text-capitalize"><?php echo $area['status']; ?></div>
                        <p class="mb-2 font-weight-bold">Progress <span class="float-right"><?php echo $progress; ?>%</span>
                        </p>
                        <div class="progress progress-sm">
                            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="100"
                                 aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress; ?>%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <style type="text/css">
                        td a {
                            display: block;
                            color: #495057;
                        }
                    </style>
                    <?php
                    $cat = $app->actCategoryProg($_GET['cycle'], $_GET['area']);
                    if ($cat) {
                        foreach ($cat as $category) { ?>
                            <div class="col-md-12 col-lg-12">
                                <div class="card">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th colspan="2"><?php echo 'Stage ' . $category['stage_no']; ?><small><p
                                                            title="<?php echo $category['category_name']; ?>"><?php echo ucwords($category['category_name']); ?></p>
                                                </small></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $activities = $app->actActivityProg($_GET['cycle'], $_GET['area'], $category['fk_category']);
                                        if ($activities) {
                                            foreach ($activities as $activity) {
                                                echo '<tr>';
                                                echo '<td><a href="#modalViewActivity" data-toggle="modal" data-cycle-id="' . $activity['fk_cycle'] . '" data-area-id="' . $activity['area_id'] . '" data-activity-id="' . $activity['id'] . '" data-activity-name="' . $activity['activity_name'] . '">' . $activity['activity_name'] . '</a></td>';
                                                echo '<td><a href="#"><strong class="float-right">' . $activity['progress'] . '%</strong></a></td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        <?php }
                    }
                    ?>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Members</h5>
                        <?php
                        $av = $app->getACTMembers_avatar($_GET['cycle'], $_GET['area']);
                        foreach ($av as $avatar) {
                            echo '<img src="' . $avatar['avatar_path'] . '" class="rounded-circle mr-1" alt="' . $avatar['username'] . '" title="@' . $avatar['username'] . '" width="48" height="48">';
                        }
                        ?>
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
        <div class="modal-dialog modal-lg" role="document">
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

<?php } else {
    //else show 404 page
    include ('404.php');
    ?>

<?php }

?>

