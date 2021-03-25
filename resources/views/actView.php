<?php
$area = $app->actView_areaInfo($_GET['cycle'], $_GET['area']);
?>
<div class="col-md-3 col-xl-10">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0 text-capitalize"><strong><?php echo $area['area_name']; ?></strong></h5>
                    <div class="badge bg-primary my-2"><?php echo $area['year']; ?></div>
                    <div class="badge bg-success my-2 text-capitalize"><?php echo $area['batch'] . ' ' . $area['cycle_name']; ?></div>
                    <div class="badge bg-success my-2 text-capitalize"><?php echo $area['status']; ?></div>
                </div>
            </div>
            <div class="row">
                <?php
                $cat = $app->actCategoryProg($_GET['cycle'],$_GET['area']);
                if($cat){
                    foreach ($cat as $category){ ?>
                    <div class="col-md-12 col-lg-12">
                    <div class="card">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th colspan="2"><?php echo 'Stage '.$category['stage_no']; ?><small><p title="<?php echo $category['category_name']; ?>"><?php echo ucwords($category['category_name']); ?></p></small></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $activities = $app->actActivityProg($_GET['cycle'],$_GET['area'],$category['fk_category']);
                            if($activities){
                                foreach ($activities as $activity){
                                    echo '<tr>';
                                    echo '<td>'.$activity['activity_name'].'</td>';
                                    echo '<td><strong class="float-right">'.$activity['progress'].'%</strong></td>';
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
    </div>
</div>
