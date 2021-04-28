<?php

$dqaInfo=$app->getDqaInfo($_GET['dqaid']);

?>

<div class="col-md-9 col-xl-10">
    <div class="card mb-2">
        <h5 class="p-3 text-capitalize card-title"><strong><a href="home.php?p=act&m=findings" class=""><span class="fa fa-arrow-left"></span> Back </a><span class="ml-3"><?php echo '#'.$dqaInfo['id'].' '.$dqaInfo['title']; ?></span></strong></h5>
        <div class="table-responsive">
            <style type="text/css">
                td a {
                    display: block;

                }
            </style>
            <table id="tbl_actDqaItems" class="table table-hover" style="width:100%">
                <thead>
                <tr class="border-bottom-0">
                    <th style="width: 10%;"></th>
                    <th style="width: 25%;">Filename</th>
                    <th style="width: 25%;">Form</th>
                    <th style="width: 15%;">Area</th>
                    <th style="width: 10%;">Uploader</th>
                    <th style="width: 15%;">Reviewer</th>
                    <th style="width: 10%;">Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
    </div>
</div>
    <div class="modal fade" id="modalViewFindings" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <div class="card-body mt-0">
                                    <h4>Upload Compliance</h4>
                                    <form method="post" name="fileUpload" id="formComplianceFileUpload" enctype="multipart/form-data">
                                        <div class="m-2">
                                            <label class="form-label mb-2">Select file</label>
                                            <input type="file" class="form-control"
                                                   required accept="application/pdf"
                                                   name="fileToUpload"
                                                   id="fileToUpload"> <label>
                                        </div>
                                        <div class="m-2">
                                            <button class="btn btn-primary btn-upload-file">
                                                <span class="fa fa-paper-plane"></span><span class="btn-upload-file-text"> Upload compliance </span>
                                                <span class="upload_percent"></span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body mt-0">
                                    <div id="displayFileHistory">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="card">
                                <div class="card-body">
                                    <div id="pdf" class="mb-3 bg-light">

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
                    </div>
                </div>
            </div>
        </div>
    </div>