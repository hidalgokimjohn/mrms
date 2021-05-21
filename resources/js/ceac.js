$(document).ready(function () {

    var cycle_id = url.searchParams.get("cycle_id");
    var cadt_id = url.searchParams.get("cadt_id");

    $('.dataTables_paginate').addClass('p-3');
    $('.dataTables_info').addClass('p-3');

    $('#tbl_viewCeacIpcdd thead tr').clone(true).appendTo('#tbl_viewCeacIpcdd thead');
    $('#tbl_viewCeacIpcdd thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_viewCeacIpcdd.column(i).search() !== this.value) {
                    tbl_viewCeacIpcdd.column(i).search(this.value).draw();
                }
            });
        }
    });

    var tbl_viewCeacIpcdd = $('#tbl_viewCeacIpcdd').DataTable({
        orderCellsTop: true,
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">bitpr',
        ajax: {
            url: "resources/ajax/tbl_ceac_ipcdd.php",
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        language: {
            "emptyTable": "<b>No records <found class=''></found></b>"
        },
        initComplete: function(settings, json) {
            $('.dataTables_paginate').addClass('p-3');
            $('.dataTables_info').addClass('p-3');
        },
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                return '<a href="home.php?p=ceac_mngt&m=edit_target&modality=ipcdd_drom&cadt_id='+data['fk_cadt']+'&cycle_id='+data['fk_cycles']+'">Targets</a>'
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return data['cadt_name'];
                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return data['cycle_name']+' '+data['batch'];
                },
            },
            {
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    return data['modality_group'];
                },
            },
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    var x='';
                   if(data['status']=='current'){
                       x ='<div class="badge bg-success"><span class="fa fa-check-circle"></span> Current</div>'
                   }
                    return x;
                },
            }
        ],

    });

    $('#tbl_viewCeacIpcddTargets thead tr').clone(true).appendTo('#tbl_viewCeacIpcddTargets thead');
    $('#tbl_viewCeacIpcddTargets thead tr:eq(1) th').each(function (i) {

        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_viewCeacIpcddTargets.column(i).search() !== this.value) {
                    tbl_viewCeacIpcddTargets.column(i).search(this.value).draw();
                }
            });
        }
    });

    var tbl_viewCeacIpcddTargets = $('#tbl_viewCeacIpcddTargets').DataTable({
        orderCellsTop: true,
        order: [
            [1, "asc"]
        ],
        dom: 'B<<t>ip>',
        //dom: '<"html5buttons">btpr',
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        ajax: {
            url: "resources/ajax/tbl_ceac_ipcdd_targets.php?cadt_id="+cadt_id+"&cycle_id="+cycle_id,
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            error: function () {
                $("post_list_processing").css("display", "none");
            }
        },
        language: {
            "emptyTable": "<b>No records <found class=''></found></b>"
        },
        initComplete: function(settings, json) {
            var dtButtons = $('.dt-buttons');
            dtButtons.addClass('float-right mt-3 mr-3');
            dtButtons.removeClass('btn-group');

            var btnExcel = $('.buttons-excel');
            var btnCopy = $('.buttons-copy');
            var btnCsv = $('.buttons-csv');
            var btnPdf = $('.buttons-pdf');
            var btnPrint = $('.buttons-print');

            btnExcel.addClass('btn btn-outline-primary');
            btnExcel.removeClass('buttons-html5 btn-secondary');

            btnCopy.addClass('btn btn-outline-primary');
            btnCopy.removeClass('buttons-html5 btn-secondary');

            btnCsv.addClass('btn btn-outline-primary');
            btnCsv.removeClass('buttons-html5 btn-secondary');

            btnPdf.addClass('btn btn-outline-primary');
            btnPdf.removeClass('buttons-html5 btn-secondary');

            btnPrint.addClass('btn btn-outline-primary');
            btnPrint.removeClass('buttons-html5 btn-secondary');
        },
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                return '<a href="#modalEditCeacTarget" data-toggle="modal" data-form-id="'+data['ft_guid']+'" ' +
                    'data-activity-name="'+data['activity_name']+'" data-location="'+data['location']+'" data-form-name="'+data['form_name']+'" data-prevtarget="'+data['target']+'">Edit</a>'
            },
        },
            {
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    return data['activity_name']+'<br/><small class="text-capitalize text-muted">'+data['category_name']+'</small>';
                },
            },
            {
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    return data['form_name'];
                },
            },{
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    return data['location'];
                },
            },
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return data['target'];
                },
            },
            {
                "targets": 5,
                "data": null,
                "render": function (data, type, row) {
                    return data['actual'];
                },
            }
        ],

    });

    const modalEditCeacTarget = document.getElementById('modalEditCeacTarget');
    if (modalEditCeacTarget) {
        modalEditCeacTarget.addEventListener('show.bs.modal', function (e) {
            $('.activity-name').val($(e.relatedTarget).data('activity-name'));
            $('.form-name').val($(e.relatedTarget).data('form-name'));
            $('.location').val($(e.relatedTarget).data('location'));
            $('.form-target').val($(e.relatedTarget).data('prevtarget'));
        });
    }

});
