
    <div class="col-md-9 col-xl-10">
        <div class="card mb-2">
            <div class="table-responsive">
                <table id="tbl_uploadedFiles" class="table table-striped table-hover" style="width:100%">
                    <thead>
                    <tr class="border-bottom-0">
                        <th style="width: 5%;"></th>
                        <th style="width: 35%;">Filename</th>
                        <th style="width: 20%;">Form</th>
                        <th style="width: 20%;">Area</th>
                        <th style="width: 15%;">Responsible Person</th>
                        <th style="width: 10%;">Uploaded</th>
                        <th style="width: 5%;">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-s" role="document">
            <div class="modal-content p-3">
                <form method="post" name="fileUpload" id="formFileUpload" enctype="multipart/form-data">
                    <div class="m-2">
                        <label class="form-label m-0">Area</label>
                        <select class="form-control choices-of-cadt" name="area_id">
                            <option value="">Select Area</option>
                            <?php
                            $uc = $app->userCoverage($_SESSION['id_number']);
                            foreach ($uc as $item){
                                echo '<option value="'.$item['area_id'].'">'.$item['area_name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="m-2">
                        <label class="form-label m-0">Cycle</label>
                        <select class="form-control choices-of-cycle" name="cycle_id">
                            <option value="">Select Cycle</option>
                            <optgroup label="IPCDD DROM">
                                <?php
                                $uc = $app->searchGetCycles('ipcdd_drom');
                                foreach ($uc as $item){
                                    echo '<option value="'.$item['id'].'">'.ucwords($item['cycle_name']).'</option>';
                                }
                                ?>
                            </optgroup>
                            <optgroup label="NCDDP DROM">
                                <?php
                                $uc = $app->searchGetCycles('ncddp_drom');
                                foreach ($uc as $item){
                                    echo '<option value="'.$item['id'].'">'.ucwords($item['cycle_name']).'</option>';
                                }
                                ?>
                            </optgroup>
                            <optgroup label="KC-AF CBRC">
                                <?php
                                $uc = $app->searchGetCycles('af_cbcr');
                                foreach ($uc as $item){
                                    echo '<option value="'.$item['id'].'">'.ucwords($item['cycle_name']).'</option>';
                                }
                                ?>
                            </optgroup>
                            <optgroup label="KKB">
                                <?php
                                $uc = $app->searchGetCycles('kkb_drom');
                                foreach ($uc as $item){
                                    echo '<option value="'.$item['id'].'">'.ucwords($item['cycle_name']).'</option>';
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="m-2">
                        <label class="form-label m-0">Activity</label>
                        <select class="form-control choices-of-activity" name="activity_id">
                            <option value="">Select Activity</option>
                        </select>
                    </div>
                    <div class="m-2">
                        <label class="form-label m-0">Form</label>
                        <select class="form-control choices-of-form" name="form_id">
                            <option value="">Select Form</option>
                        </select>
                    </div>
                    <div class="m-2">
                        <label class="form-label m-0">Responsible Person<br/> <small><i>(Choose the assigned AC/CEF for this document)</i></small></label>

                        <select class="form-control choices-of-rp" name="rp_id">
                            <option value="">Select Responsible Person</option>
                            <?php
                            foreach ($app->getActUser() as $act) {
                                echo '<option class="text-capitalize" value="' . $act['id_number'] . '">' . ucwords(strtolower($act['fname'].' '.$act['mname'].' '.$act['lname'])) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div id="fileInfo" hidden>
                        <div class="ml-2 mt-4 mr-2">
                            <div class="col-md-12">
                                <div class="card bg-light py-2 py-md-3 border">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="spinner-border text-primary mr-2" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="fileInfo_body" hidden>
                                            <strong>CEAC Information</strong>
                                            <p class="text-center"><small>No information available at the moment.</small></p>
                                            <!--<p class="m-0">Target: Feb 12, 2021</p>
                                            <p class="m-0">Actual: Feb 15, 2021</p>
                                            <p class="m-0">Status: <span class="font-weight-bold text-danger">Late (3Days)</span></p>
                                            <br/>-->
                                            <p class="m-0"><strong>MOV Submission</strong></p>
                                            <p class="text-center"><small>No information available at the moment.</small></p>
                                            <!--<small>
                                                <p class="m-0">+10days from the Actual Conducted Date</p>
                                                <p class="m-0">MOV should be uploaded until <strong>Feb 25, 2021</strong> otherwise late</p>
                                            </small>
                                            <p class="m-0">Status: <span class="text-success font-weight-bold">Advance</span></p>-->
                                            <p class="m-0"><strong>DQA Information</strong></p>
                                            <p class="text-center"><small>No information available at the moment.</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-2">
                            <input type="file" class="form-control"
                                   required accept="application/pdf"
                                   name="fileToUpload"
                                   id="fileToUpload"> <label>
                        </div>
                        <div class="m-2">
                            <button class="btn btn-primary btn-upload-file"><span class="btn-upload-file-text">Upload </span><span
                                        class="upload_percent"></span></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>