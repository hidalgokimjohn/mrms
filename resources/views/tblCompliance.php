<div class="col-auto d-none d-sm-block">
    <h3 class="h3 mb-3">Compliance</h3>
</div>
<div class="card mb-3">
    <div class="table-responsive">
        <table id="tbl_dqaCompliance" class="table table-striped table-hover" style="width:100%">
            <thead>
            <tr class="border-bottom-0">
                <th style="">Date</th>
                <th style="">Title</th>
                <th style="">Cycle</th>
                <th>Area</th>
                <th type="Responsible Person">RP</th>
                <th title="">DQA #</th>
                <th class="">w/findings?</th>
                <th class="">isComplied?</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalCreateDqa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form method="post" id="formCreateDqa">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Create DQA</strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <label for="choicesMun" class="form-label">Municipality/CADT</label>
                    <select id="choicesMun" class="form-control choices-muni" name="municipality" required>
                        <option value="">Select Municipality/CADT</option>
                        <optgroup label="MUNICIPALITY">
                            <?php
                            foreach ($app->getCities('active', $_GET['modality']) as $options) {
                                echo '<option value="' . $options['psgc_mun'] . '">' . $options['mun_name'] . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="CADTs">
                            <?php
                            foreach ($app->getCadt('active', $_GET['modality']) as $options) {
                                echo '<option value="' . $options['id'] . '">' . strtoupper($options['cadt_name']) . '</option>';
                            }
                            ?>
                        </optgroup>

                    </select>
                    <label for="choicesCycle" class="form-label">Cycle</label>
                    <select id="choicesCycle" class="form-control choicesCycle" name="cycle" required>
                        <option value="">Select Cycle</option>
                        <optgroup label="KC-AF CBCR">
                            <?php
                            foreach ($app->getCycle(2021, 'ncddp') as $options) {
                                echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['batch']) . ' ' . strtoupper($options['cycle_name']) . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="IPCDD DROM">
                            <?php
                            foreach ($app->getCycle('active', $_GET['modality']) as $options) {
                                echo '<option value="' . $options['id'] . '" class="text-capitalize">' . strtoupper($options['batch']) . ' ' . strtoupper($options['cycle_name']) . '</option>';
                            }
                            ?>
                        </optgroup>
                    </select>
                    <label for="choicesAC" class="form-label">Area Coordinator</label>
                    <select id="choicesAC" class="form-control choicesAc" name="staff" required>
                        <option value="">Select Area Coordinator</option>
                        <?php
                        foreach ($app->getACUser() as $act) {
                            echo '<option class="text-capitalize" value="' . $act['id_number'] . '">' . ucwords(strtolower($act['fname'] . ' ' . $act['mname'] . ' ' . $act['lname'])) . '</option>';
                        }
                        ?>
                    </select>
                    <label for="choicesTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" placeholder="Enter your title" name="dqaTitle" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn_saveDqa"><span class="fa fa-save"></span>
                        <span class="text_saveDqa">Save</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editDqaTitle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form method="post" id="formEditDqaTitle" class="g-3 needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Edit Title</strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <label for="editChoicesAC" class="form-label">Area Coordinator</label>
                    <select id="editChoicesAC" class="form-control editChoicesAc" name="staff">
                        <option value="">Select Area Coordinator</option>
                        <?php
                        foreach ($app->getActUser() as $act) {
                            echo '<option class="text-capitalize" value="' . $act['id_number'] . '">' . ucwords(strtolower($act['fname'].' '.$act['mname'].' '.$act['lname'])) . '</option>';
                        }
                        ?>
                    </select>
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control dqaTitle" placeholder="Enter your title" name="dqaTitle"
                           required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnEditDqaTitle"><span class="fa fa-save"></span>
                        <span class="text_editDqaTitle">Save changes</span></button>
                </div>
            </form>
        </div>
    </div>
</div>