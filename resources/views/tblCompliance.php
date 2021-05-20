<div class="col-auto d-none d-sm-block">
    <h3 class="h3 mb-3">Compliance</h3>
</div>
<div class="card mb-3">
    <div class="table-responsive">
        <table id="tbl_dqaCompliance" class="table table-striped table-hover" style="width:100%">
            <thead>
            <tr class="border-bottom-0">
                <th style="">Date</th>
                <th style="">Filename</th>
                <th style="">Form</th>
                <th style="">Cycle</th>
                <th>Area</th>
                <th title="Responsible Person">RP</th>
                <th title="">DQA #</th>
                <th class="">w/findings?</th>
                <th class="">isComplied?</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalReviewCompliance" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xxl" role="document">
        <div class="modal-content">
            <form method="post" id="formReviewCompliance">
                <div class="modal-header">
                    <h5 class="modal-title"><strong><span class="compliance-filename"></span></strong></h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-body">
                                    <div id="displayFindings">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div id="pdf" class="mb-3 bg-light">

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
                       <!-- <div class="col-sm-2">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Related files</h3>
                                </div>
                                <div class="list-group list-group-flush" role="tablist" id="relatedFiles">

                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>