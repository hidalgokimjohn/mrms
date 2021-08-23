<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Upload Files</h5>
                    <form class="row row-cols-md-auto align-items-center">
                        <div class="col-lg-6">
                            <select class="form-control choices-category-other-files" name="cycle_id">
                                <option value="">Select Form</option>
                                <?php
                                    $options = $app->getFormsOtherFiles();
                                    foreach ($options  as $option){
                                        echo '<option value="'.$option['value'].'">'.$option['label'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <input type="file" class="form-control"
                                   required accept="application/pdf"
                                   name="other_files"
                                   id="other_files">
                        </div>
                        <div class="col-lg-12">
                           <textarea name="file_description" class="form-control mt-3" placeholder="Add some details/remarks (Optional)"></textarea>
                        </div>

                        <div class="col-lg-4 pt-3">
                            <button type="button" class="btn btn-primary btn-generate-checklist">
                                <span class="fa fa-upload"></span> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Files</h5>
                    <div class="table-responsive">
                        <table id="tbl_other_files" class="table table-striped table-hover" style="width:100%">
                            <thead>
                            <tr class="border-bottom-0">
                                <th></th>
                                <th>Filename</th>
                                <th>Form</th>
                                <th>Description</th>
                                <th>Uploader</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>