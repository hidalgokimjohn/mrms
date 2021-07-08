<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">MOV Checklist</h5>
                    <form class="row row-cols-md-auto align-items-center">
                        <div class="col-lg-5">
                            <select class="form-control choices-generate-checklist-cadt" name="cadt_id">
                                <option value="">Select Area</option>
                                <?php
                                $areas = $app->getCadt('active', 'ipcdd_drom');
                                foreach ($areas as $area) {
                                    echo '<option value="' . $area['id'] . '">' .$area['cadt_name']. '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <select class="form-control choices-generate-checklist-cycle" name="cycle_id">
                                <option value="">Select Batch/Cycle</option>
                                <?php
                                $cycles = $app->getCycle('active','ipcdd_drom');
                                foreach ($cycles as $cycle){
                                    echo '<option value="'.$cycle['id'].'">'.$cycle['batch'].' '.$cycle['cycle_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-4 p-3">
                            <button type="button" class="btn btn-primary btn-generate-checklist"><span class="fa fa-print"></span> Generate</button>
                            <a id="dlink" style="display:none;"></a>
                            <button type="button" class="dl_checklist btn btn-warning"
                                    onclick="tableToExcel('generated_checklist', 'name')"><span
                                        class="fa fa-download"></span> Export to excel
                            </button>
                        </div>
                    </form>
                    <div class="loading-screen p-5" hidden>
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
                    <div class="display-generated-checklist pt-3">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>