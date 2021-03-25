<div class="col-md-3 col-xl-10">
    <div class="row">
        <?php
        $c = $app->getUserCoverage();
        if($c){
            foreach ($c as $i){
                $progress=  $app->areaProgress($i['cycle_id'],$i['area_id']);
                ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-actions float-right">
                                <div class="dropdown show">
                                    <a href="#" data-toggle="dropdown" data-display="static">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
                            </div>
                            <h5 class="card-title mb-0 text-capitalize"><strong><?php echo $i['area_name']; ?></strong></h5>
                            <div class="badge bg-primary my-2"><?php echo $i['year']; ?></div>
                            <div class="badge bg-success my-2 text-capitalize"><?php echo $i['batch'].' '.$i['cycle_name']; ?></div>
                            <div class="badge bg-success my-2 text-capitalize"><?php echo $i['status']; ?></div>
                        </div>
                        <div class="card-body px-4 pt-2">
                            <!--<p>Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque
                                sed ipsum.</p>-->
                            <?php
                            $av = $app->getACTMembers_avatar($i['cycle_id'],$i['area_id']);
                            foreach ($av as $avatar){
                                echo '<img src="'.$avatar['avatar_path'].'" class="rounded-circle mr-1" alt="'.$avatar['username'].'" title="@'.$avatar['username'].'" width="48" height="48">';
                            }
                            ?>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-4 pb-4">
                                <p class="mb-2 font-weight-bold">Progress <span class="float-right"><?php echo $progress; ?>%</span></p>
                                <div class="progress progress-sm">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress; ?>%;">
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="card-footer text-center bg-primary">
                            <a href="<?php echo 'home.php?p=act&m=view_more&cycle='.$i['cycle_id'].'&area='.$i['area_id']; ?>" class="text-light">View more</a>
                        </div>
                    </div>

                </div>
            <?php }
        }
        ?>

    </div>
</div>