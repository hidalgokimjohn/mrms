<?php
    $areaInfo = $app->actView_areaInfo($_GET['cycle_id'],$_GET['cadt_id']);
    if(!$areaInfo){
        include ("404.php");
    }else { ?>
    <h3 class="h3 mb-3 text-capitalize">CEAC / IPCDD DROM </h3>
    <div class="card">
        <div class="ml-3 mt-3">
            <h5 class="text-capitalize card-title"><strong><a href="home.php?p=ceac_mngt&m=list&modality=ipcdd_drom"><span class="fa fa-arrow-left"></span> Back </a><span class="ml-3"><strong><?php echo $areaInfo['area_name'].' - '.$areaInfo['cycle_name'].' '.$areaInfo['batch'] ; ?></strong></span></strong></h5>
        </div>
        <div class="table-responsive">
            <table id="tbl_viewCeacIpcddTargets" class="table table-hover" style="width:100%">
                <thead>
                <tr class="border-bottom-0">
                    <th></th>
                    <th>Activity</th>
                    <th>Form</th>
                    <th>Area</th>
                    <th>Target</th>
                    <th>Actual</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalEditCeacTarget" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Edit target</strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-2">
                    <label class="form-label mb-0">Activity:</label>
                    <input class="form-control activity-name mb-2"disabled>
                    <label class="form-label mb-0">Form:</label>
                    <input class="form-control form-name mb-2"disabled>
                    <label class="form-label mb-0">Area:</label>
                    <input class="form-control location mb-2" disabled>
                    <label class="form-label mb-0">Target:</label>
                    <input class="form-control form-target mb-2"  name="new_target" type="number">
                    <label class="form-label mb-0">Reason:</label>
                    <input class="form-control mb-2" name="reason" type="text" placeholder="Please provide reason for adjustment">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnEditTarget"><span class="fa fa-save"></span>
                        <span class="text_editTarget">Save changes</span></button>
                </div>
            </div>
        </div>
    </div>
    <?php }
?>


