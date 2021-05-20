$(document).ready(function () {
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
        bDestroy: true,
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

});
