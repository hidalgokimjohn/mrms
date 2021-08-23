$(document).ready(function () {
    $('.dataTables_paginate').addClass('p-3');
    $('.dataTables_info').addClass('p-3');
    var m = url.searchParams.get("m");
    var p = url.searchParams.get("p");


    /*if (m == 'dqa_conducted') {
        new Choices(document.querySelector(".choices-muni"));
        new Choices(document.querySelector(".choicesCycle"));
        new Choices(document.querySelector(".choicesAc"));
        new Choices(document.querySelector(".editChoicesAc"));
    }*/

    //generate checklist module//
    if (p === 'generate_checklist' || m === 'generate_checklist') {
        var choiceCadt = new Choices(".choices-generate-checklist-cadt", {
            shouldSort: false
        });
        var choiceCycle = new Choices(".choices-generate-checklist-cycle", {
            shouldSort: false
        });
    }

    if (p === 'upload_others') {
        var choicesOtherFiles = new Choices(".choices-category-other-files", {
            shouldSort: false
        });
    }


    if (m == 'upload' || p == 'upload') {
        var choiceTypeOfCadt = new Choices(".choices-of-cadt", {
            shouldSort: false
        });
        var choiceTypeOfCycle = new Choices(".choices-of-cycle", {
            shouldSort: false
        }).disable();

        var choiceTypeOfActivity = new Choices(".choices-of-activity", {
            shouldSort: false
        }).disable();

        var choiceTypeOfCity = new Choices(".choices-of-city", {
            shouldSort: false
        }).disable();

        var choiceTypeOfBrgy = new Choices(".choices-of-brgy", {
            shouldSort: false
        }).disable();

        var choiceTypeOfForm = new Choices(".choices-of-form", {
            shouldSort: false
        }).disable();

        var choiceTypeOfrp = new Choices(".choices-of-rp", {
            shouldSort: false
        });

        $('.choices-of-cadt').on('change', function () {
            choiceTypeOfCycle.enable();
            choiceTypeOfForm.clearStore();
            choiceTypeOfCity.clearStore();
            choiceTypeOfBrgy.clearStore();
            var area = $('.choices-of-cadt').val();
            if (area == '') {
                choiceTypeOfCycle.clearChoices();
                choiceTypeOfCycle.disable();
            }
        });

        $('.choices-of-cycle').on('change', function () {
            var cycle_id = $('.choices-of-cycle').val();
            var area_id = $('.choices-of-cadt').val();
            choiceTypeOfActivity.clearStore();
            choiceTypeOfForm.clearStore();

            $.ajax({
                type: 'POST',
                url: 'resources/ajax/uploadCycleOnChange.php',
                data: {"cycle_id": cycle_id, "area_id": area_id},
                async: true,
                dataType: 'json',
                success: function (data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if (data) {
                        choiceTypeOfActivity.enable();
                        choiceTypeOfActivity.setChoices(data);
                    }
                }
            });
        });
        $('.choices-of-activity').on('change', function () {
            var cycle_id = $('.choices-of-cycle').val();
            var area_id = $('.choices-of-cadt').val();
            var activity_id = $('.choices-of-activity').val();
            choiceTypeOfForm.clearStore();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/uploadActivityOnChange_2.php',
                data: {"cycle_id": cycle_id, "area_id": area_id, "activity_id": activity_id},
                async: true,
                dataType: 'json',
                success: function (data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if (data) {
                        choiceTypeOfForm.enable();
                        choiceTypeOfForm.setChoices(data);
                        choiceTypeOfBrgy.clearStore();
                        choiceTypeOfCity.clearStore();
                        choiceTypeOfCity.disable();
                        choiceTypeOfBrgy.disable();
                    }
                }
            });
        });

        $('.choices-of-form').on('change', function () {
            var cycle_id = $('.choices-of-cycle').val();
            var area_id = $('.choices-of-cadt').val();
            var form_id = $('.choices-of-form').val();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/uploadActivityOnChange.php',
                data: {"cycle_id": cycle_id, "area_id": area_id, "form_id": form_id},
                async: true,
                dataType: 'json',
                success: function (data) {
                    x = data[0].form_type
                    choiceTypeOfCity.clearStore();
                    choiceTypeOfBrgy.clearStore();
                    if (x === 'municipal' || x === 'cadt') {
                        choiceTypeOfCity.enable();
                        choiceTypeOfCity.setChoices(data);
                        choiceTypeOfBrgy.disable();
                    }

                    if (x === 'barangay') {
                        choiceTypeOfBrgy.enable();
                        choiceTypeOfBrgy.setChoices(data);
                        choiceTypeOfCity.disable();
                    }
                }
            });

            $("#fileInfo").prop('hidden', false);
            setTimeout(function () {
                $('.fileInfo_body').prop('hidden', false)
            }, 2000);
            setTimeout(function () {
                $('.spinner-border').prop('hidden', true)
            }, 2000);

        });

        $('#tbl_uploadedFiles').on('click', 'tbody td .delete-file', function (e) {
            var file_id = $(this).attr('data-file-id');
            var form_id = $(this).attr('data-form-id');
            var r = confirm('Are you sure you want to delete this MOV');
            if (r) {
                $.ajax({
                    type: "post",
                    url: "resources/ajax/deleteFile.php",
                    data: {
                        "file_id": file_id,
                        "form_id": form_id
                    },
                    dataType: 'html',
                    success: function (data) {
                        if (data == 'deleted') {
                            window.notyf.open({
                                type: 'success',
                                message: '<strong>File deleted</strong>',
                                duration: '5000',
                                ripple: true,
                                dismissible: true,
                                position: {
                                    x: 'center',
                                    y: 'top'
                                }
                            });
                            tbl_uploadedFiles.ajax.reload();
                        } else {
                            window.notyf.open({
                                type: 'error',
                                message: '<strong>Sorry</strong>, reviewed documents can\'t be removed.',
                                duration: '5000',
                                ripple: true,
                                dismissible: true,
                                position: {
                                    x: 'center',
                                    y: 'top'
                                }
                            });
                        }
                    }
                });
            }
        })

        $('#tbl_uploadedFiles thead tr').clone(true).appendTo('#tbl_uploadedFiles thead');
        $('#tbl_uploadedFiles thead tr:eq(1) th').each(function (i) {
            if (i !== 0) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                $('input', this).on('keyup change', function (e) {
                    if (tbl_uploadedFiles.column(i).search() !== this.value) {
                        tbl_uploadedFiles.column(i).search(this.value).draw();
                    }
                });

            } else {
                var title = $(this).text();
                $(this).html('<a href="#uploadModal" data-toggle="modal">\n' +
                    '                <button type="button" class="btn btn-primary"><span class="fa fa-plus"></span></button>\n' +
                    '            </a>');
            }

        });
        var tbl_uploadedFiles = $('#tbl_uploadedFiles').DataTable({
            orderCellsTop: true,
            order: [
                [6, "desc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            dom: '<<t>ip>',
            //dom: '<"html5buttons">bitpr',
            ajax: {
                url: "resources/ajax/tbl_uploadedFiles.php",
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
            initComplete: function (settings, json) {
                $('.dataTables_paginate').addClass('p-3');
                $('.dataTables_info').addClass('p-3');
            },
            columnDefs: [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    if (data['is_reviewed'] == 'for review') {
                        return '<a href="#" class="btn btn-outline-danger btn-pill delete-file" data-file-id="' + data['file_id'] + '" data-form-id="' + data['ft_guid'] + '"><span class="fa fa-trash-alt"></span></a>'
                    } else {
                        return '<a href="#" class="btn btn-outline-danger disabled btn-pill delete-file" data-file-id="' + data['file_id'] + '" data-form-id="' + data['ft_guid'] + '"><span class="fa fa-trash-alt"></span></a>'
                    }
                },
            },
                {
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data['original_filename'] !== null) {

                            return '<a href="' + data['host'] + data['file_path'] + '" target="_blank" title="' + data['activity_name'] + ', ' + data['form_name'] + '"><strong>' + data['original_filename'] + '</strong></a>';
                        } else {
                            return '<strong>Not Yet Uploaded</strong>'
                        }
                    },
                },
                {
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data['original_filename'] !== '') {
                            return data['batch'] + " " + data['cycle_name'];
                        } else {
                            return '<strong>Not Yet Uploaded</strong>'
                        }

                    },
                },
                {
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data['original_filename'] !== '') {
                            return data['form_name'] + "<br/>" + '<small>Activity: ' + data['activity_name'] + '</small>';
                        } else {
                            return '<strong>Not Yet Uploaded</strong>'
                        }

                    },
                },
                {
                    "targets": 4,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data['original_filename'] !== '') {
                            return '<span title="' + data['cadt_name'] + ', ' + data['mun_name'] + '">' + data['area'] + '</span>';
                        } else {
                            return '<strong class="text-danger">Not Yet Uploaded</strong>'
                        }
                    },
                },
                {
                    "targets": 5,
                    "data": null,
                    "render": function (data, type, row) {
                        return data['rp'];
                    },
                },
                {
                    "targets": 6,
                    "data": null,
                    "render": function (data, type, row) {
                        return data['date_uploaded'];
                    },
                },
                {
                    "targets": 7,
                    "data": null,
                    "render": function (data, type, row) {
                        if (data['original_filename'] !== '') {
                            if (data['is_reviewed'] == 'for review') {
                                return '<div class="badge bg-warning"><span class="fa fa-exclamation-circle"></span> For Review</div>'
                            } else if (data['is_reviewed'] == 'reviewed') {
                                return '<div class="badge bg-primary"><span class="fa fa-check-circle"></span> Reviewed</div>'
                            } else {
                                return '-';
                            }
                        } else {
                            return '<strong>Not Yet Uploaded</strong>'
                        }
                    },
                }
            ],

        });

        $("form#formFileUpload").submit(function (event) {
            event.preventDefault();
            var btn = this;
            var form_id = $('.choices-of-city').val();
            if (form_id === null) {
                form_id = $('.choices-of-brgy').val();
            }
            $('.btn-upload-file').prop('disabled', true);
            $('.btn-upload-file-text').text(' Uploading...');
            var formData = new FormData($(this)[0]);
            var rp_id = $('.choices-of-rp').val();
            if (rp_id === '') {
                alert('Responsible Person is required');
                $(".upload_percent").text('');
                $('.btn-upload-file-text').text(' Upload');
                $('.btn-upload-file').prop('disabled', false);
            } else {
                $.ajax({
                    url: 'resources/ajax/uploadFile.php?form_id=' + form_id,
                    type: 'POST',
                    dataType: 'html',
                    data: formData,
                    async: true,
                    cache: false,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                console.log(percentComplete);
                                $(".upload_percent").text(+' ' + percentComplete + "%");
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (returndata) {

                        if (returndata === 'uploaded') {
                            window.notyf.open({
                                type: 'success',
                                message: '<strong>File uploaded </strong>successfully',
                                duration: '5000',
                                ripple: true,
                                dismissible: true,
                                position: {
                                    x: 'center',
                                    y: 'top'
                                }
                            });

                            $("#status").text('');
                            $("#fileToUpload").val('');
                            $('.btn-upload-file-text').text(' Upload');
                            $(".upload_percent").text('');
                            $('.btn-upload-file').prop('disabled', false);
                            tbl_uploadedFiles.ajax.reload();
                        } else if (returndata === 'target_reached') {
                            window.notyf.open({
                                type: 'error',
                                message: 'Target reached. To adjust targets please contact PEO.',
                                duration: '5000',
                                ripple: true,
                                dismissible: true,
                                position: {
                                    x: 'center',
                                    y: 'top'
                                }
                            });

                            $(".upload_percent").text('');
                            $('.btn-upload-file-text').text(' Upload');
                            $('.btn-upload-file').prop('disabled', false);
                        } else {
                            window.notyf.open({
                                type: 'error',
                                message: 'Something went wrong please try again.',
                                duration: '5000',
                                ripple: true,
                                dismissible: true,
                                position: {
                                    x: 'center',
                                    y: 'top'
                                }
                            });

                            $(".upload_percent").text('');
                            $('.btn-upload-file-text').text(' Upload');
                            $('.btn-upload-file').prop('disabled', false);
                        }
                    }
                });
            }
        });

        function clearFileInput() {
            $("#fileToUpload").val('');
        }


    }


    var tbl_nyu = '';

    $('#tbl_nyu thead tr').clone(true).appendTo('#tbl_nyu thead');
    $('#tbl_nyu thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
        $('input', this).on('keyup change', function (e) {
            if (tbl_nyu.column(i).search() !== this.value) {
                tbl_nyu.column(i).search(this.value).draw();
            }
        });
    });

    if (m == 'nyu') {
        tbl_nyu = $('#tbl_nyu').DataTable({
            orderCellsTop: true,
            bDestroy: true,
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            dom: '<<t>ip>',
            //dom: '<"html5buttons">bitpr',
            ajax: {
                url: "resources/ajax/tbl_nyu.php",
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
            initComplete: function (settings, json) {
                $('.dataTables_paginate').addClass('p-3');
                $('.dataTables_info').addClass('p-3');
            },
            columnDefs: [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    var mun_name = '';
                    if (data['mun_name'] !== null) {
                        mun_name = data['mun_name'];
                    }
                    return data['cadt_muni'] + '<br/><small>' + mun_name + '</small>';
                },
            },
                {
                    "targets": 1,
                    "data": null,
                    "render": function (data, type, row) {
                        return data['brgy_name'];
                    },
                },
                {
                    "targets": 2,
                    "data": null,
                    "render": function (data, type, row) {
                        return data['activity_name'];
                    },
                },
                {
                    "targets": 3,
                    "data": null,
                    "render": function (data, type, row) {
                        return data['form_name'] + '<br/><small class="text-danger font-weight-bold">Target: ' + data['target'] + ' Actual: ' + data['actual'] + '</small>';
                    },
                }
            ],

        });
    }

    if (p == 'user_coverage') {
        var id_number = url.searchParams.get("id");
        var choiceOfModality = new Choices(".choices-modality", {
            shouldSort: false
        });
        var choiceOfArea = new Choices(".choices-area", {
            shouldSort: true,
            removeItems: true,
            removeItemButton: true,
        });
        var choiceOfCycle = new Choices(".choices-cycle", {
            shouldSort: false
        });

        choiceOfCycle.disable();
        choiceOfArea.disable();

        $('.choices-modality').on('change', function () {
            var modality_id = $('.choices-modality').val();
            choiceOfCycle.clearStore();
            choiceOfArea.clearStore();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/selectModalityOnChange.php',
                data: {"modality_id": modality_id},
                async: true,
                dataType: 'json',
                success: function (data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if (data) {
                        choiceOfCycle.enable();
                        choiceOfCycle.setChoices(data);
                    } else {
                        choiceOfCycle.disable();
                        choiceOfArea.disable();
                    }
                }
            });
        });
        $('.choices-cycle').on('change', function () {
            var cycle_id = $('.choices-cycle').val();
            choiceOfArea.clearStore();
            $.ajax({
                type: 'POST',
                url: 'resources/ajax/selectCycleOnChange.php',
                data: {"cycle_id": cycle_id},
                async: true,
                dataType: 'json',
                success: function (data) {
                    //$('#selectActivity').html(data);
                    console.log(data);
                    if (data) {
                        choiceOfArea.enable();
                        choiceOfArea.clearChoices();
                        choiceOfArea.setChoices(data);
                    }
                }
            });
        });

        $("form#submitUserCoverage").submit(function (event) {
            event.preventDefault();
            var btn = this;
            $('#btnSubmitUserArea').html('<i class="fa fa-circle-notch fa-spin"></i> Submitting, please wait...');
            $('#btnSubmitUserArea').attr("disabled", "disabled");
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: 'resources/ajax/addUserCoverage.php?id_number=' + id_number,
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    if (returndata == 'coverage_added') {
                        notyf.success({
                            message: '<strong>Coverage </strong>successfully added',
                            duration: 10000,
                            ripple: true,
                            dismissible: true
                        });
                        $('#btnSubmitUserArea').prop('disabled', false);
                        $('#btnSubmitUserArea').text('Submit');
                        tbl_userCoverage.ajax.reload();
                    } else {
                        notyf.error({
                            message: 'Something went wrong. Please try again',
                            duration: 10000,
                            ripple: true,
                            dismissible: true
                        });
                    }
                }
            });
        });

    }


    if (p == 'user_profile') {
        new Choices(document.querySelector(".choices-dqa-level"));
        flatpickr(".flatpickr-minimum", {
            minDate: 'today'
        });
        $("#dateOfCompliance").removeAttr('readonly')
        const choicesFinding = new Choices(".choices-findings", {
            shouldSort: false
        });
        const choiceTypeOfFindings = new Choices(".choices-type-of-findings", {
            shouldSort: false
        });
        const choicesStaff = new Choices(".choices-staff");
        document.getElementById("choicesFinding").addEventListener("change", function (e) {
            if (this.value == 'no') {
                choiceTypeOfFindings.disable();
                choicesStaff.disable();
                document.getElementById("text_findings").disabled = true;
                document.getElementById("text_findings").value = '';
                document.getElementById("dateOfCompliance").value = '';
                document.getElementById("responsiblePerson").value = '';
                $("#dateOfCompliance").prop('disabled', true);
            }
            if (this.value == 'yes') {
                choiceTypeOfFindings.enable();
                choicesStaff.enable();
                document.getElementById("text_findings").disabled = false;
                $('.flatpickr-minimum').prop('disabled', false);
            }
            if (this.value == 'ta') {
                choiceTypeOfFindings.disable();
                choicesStaff.disable();
                $("#dateOfCompliance").prop('disabled', true);
                document.getElementById("text_findings").disabled = false;
            }
        });
    }


    $('#tbl_users_external thead tr').clone(true).appendTo('#tbl_users_external thead');
    $('#tbl_users_external thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_users_external.column(i).search() !== this.value) {
                    tbl_users_external.column(i).search(this.value).draw();
                }
            });
        }
    });
    var tbl_users_external = $('#tbl_users_external').DataTable({
        orderCellsTop: true,

        order: [
            [1, "asc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">bitpr',
        ajax: {
            url: "resources/ajax/tbl_users_external.php",
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
        initComplete: function (settings, json) {
            $('.dataTables_paginate').addClass('p-3');
            $('.dataTables_info').addClass('p-3');
        },
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-pill btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
                    '<div class="dropdown-menu"><a class="dropdown-item" href="home.php?p=user_coverage&id=' + data['id_number'] + '">Coverage</a>' +
                    '<a class="dropdown-item" href="#">Disable</a>' +
                    '<div class="dropdown-divider"></div><a class="dropdown-item" href="#">Email: ' + data['id_number'] + '</a></div></div>'
            },
        }, {
            "targets": 1,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<img src="resources/img/avatars/default.jpg" width="48" height="48" class="rounded-circle my-n1"></img> ' + data['fname'] + ' ' + data['lname'];
            },
        }, {
            "targets": 2,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['position_desc'];
            },
        }, {
            "targets": 3,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['office_name'];
            },
        }, {
            "targets": 4,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="badge bg-success"><span class="fa fa-check-circle"></span> ' + data['status_name'] + '</div>';
            },
        }
        ],
    });

    $('#tbl_users thead tr').clone(true).appendTo('#tbl_users thead');
    $('#tbl_users thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_users.column(i).search() !== this.value) {
                    tbl_users.column(i).search(this.value).draw();
                }
            });
        }
    });
    var tbl_users = $('#tbl_users').DataTable({
        orderCellsTop: true,

        order: [
            [1, "asc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">bitpr',
        ajax: {
            url: "resources/ajax/tbl_users.php",
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
        initComplete: function (settings, json) {
            $('.dataTables_paginate').addClass('p-3');
            $('.dataTables_info').addClass('p-3');
        },
        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-pill btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
                    '<div class="dropdown-menu"><a class="dropdown-item" href="home.php?p=user_coverage&id=' + data['id_number'] + '">Coverage</a>' +
                    '<a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>' +
                    '<div class="dropdown-divider"></div><a class="dropdown-item" href="#">ID-Number: ' + data['id_number'] + '</a></div></div>'
            },
        }, {
            "targets": 1,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<img src="resources/img/avatars/default.jpg" width="48" height="48" class="rounded-circle my-n1"></img> ' + data['fname'] + ' ' + data['lname'];
            },
        }, {
            "targets": 2,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['position_desc'];
            },
        }, {
            "targets": 3,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['office_name'];
            },
        }, {
            "targets": 4,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="badge bg-success"><span class="fa fa-check-circle"></span> ' + data['status_name'] + '</div>';
            },
        }
        ],
    });

    //tbl_userCoverage
    $('#tbl_userCoverage thead tr').clone(true).appendTo('#tbl_userCoverage thead');
    $('#tbl_userCoverage thead tr:eq(1) th').each(function (i) {
        if (i !== 0) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function (e) {
                if (tbl_userCoverage.column(i).search() !== this.value) {
                    tbl_userCoverage.column(i).search(this.value).draw();
                }
            });
        }
    });
    var tbl_userCoverage = $('#tbl_userCoverage').DataTable({
        orderCellsTop: true,
        order: [
            [4, "desc"]
        ],
        columnDefs: [{
            orderable: false,
            targets: 0
        }],
        dom: '<<t>ip>',
        //dom: '<"html5buttons">bitpr',
        ajax: {
            url: "resources/ajax/tbl_userCoverage.php?id_number=" + id_number,
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

        columnDefs: [{
            "targets": 0,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="btn-group">' +
                    '<button type="button" class="btn btn-pill btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
                    '<div class="dropdown-menu"><a class="dropdown-item" href="home.php?p=user_coverage&id=' + data['fk_username'] + '">Active</a>' +
                    '<a class="dropdown-item" href="#">Open</a>' +
                    '<a class="dropdown-item" href="#">Close</a>'
            },
        }, {
            "targets": 1,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['area_name'];
            },
        }, {
            "targets": 2,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="text-capitalize">' + data['batch'] + ' ' + data['cycle_name'] + '</div>';
            },
        }, {
            "targets": 3,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return '<div class="badge bg-success text-capitalize" title="Has access and can perform actions">' + data['status'] + '</div>';
            },
        }, {
            "targets": 4,
            "data": null,
            "render": function (data, type, row) {
                //<button class="btn btn-danger btn-sm">Delete</button>
                return data['created_at'];
            },
        }
        ],
    });


    $('#tbl_uploading_progress_ipcdd').DataTable({
        dom: 'B',
        order: [
            [1, "asc"]
        ],
        paging: false,
        initComplete: function () {
            var btns = $('.btn');
            var btns_group = $('.btn-group');
            btns.addClass('btn btn-sm pull-right');
            btns_group.addClass('float-right');
            btns.removeClass('buttons-html5 btn-secondary');

        }
        //fixedHeader: true,
    });
    $('#tbl_by_brgy').DataTable({
        dom: 'B',
        order: [
            [1, "asc"]
        ],
        paging: false,
        initComplete: function () {
            var btns = $('.btn');
            var btns_group = $('.btn-group');
            btns.addClass('btn btn-sm pull-right');
            btns_group.addClass('float-right');
            btns.removeClass('buttons-html5 btn-secondary');

        }
        //fixedHeader: true,
    });
    $('#tbl_uploading_progress_af').DataTable({
        dom: '',
        paging: false,
        order: [
            [2, "desc"]
        ],
    });
    $('#tbl_mywork').DataTable({
        dom: 'B',
        initComplete: function () {
            var btns = $('.btn');
            var btns_group = $('.btn-group');
            btns.addClass('btn btn-sm pull-right');
            btns_group.addClass('float-right');
            btns.removeClass('buttons-html5 btn-secondary');

        },
        paging: false,
        order: [
            [5, "desc"]
        ],
    });
    $('#tbl_weekly_upload_af').DataTable({
        dom: '',
        paging: false,
        order: [
            [0, "desc"]
        ],
    });
    $('#tbl_weekly_upload_ipcdd').DataTable({
        dom: '',
        paging: false,
        order: [
            [0, "desc"]
        ],
    });
    $('#tbl_milestone').DataTable({
        dom: '',
        paging: false,
        order: [
            [2, "desc"]
        ],
    });

    //start generate checklist module//

    $('.btn-generate-checklist').on('click', function () {
        var cadt_id = $('.choices-generate-checklist-cadt').val();
        var cycle_id = $('.choices-generate-checklist-cycle').val();
        //disable button//
        $('.btn-generate-checklist').html('<i class="fa fa-circle-notch fa-spin"></i> Generating');
        $('.btn-generate-checklist').attr("disabled", "disabled");

        //showloading screen//
        $('.loading-screen').prop('hidden', false);

        //reset displayed data//
        $('.display-generated-checklist').html('');

        //request data//
        $.ajax({
            type: 'POST',
            url: 'resources/ajax/generateIpcddChecklist.php',
            data: {
                "cycle_id": cycle_id,
                "cadt_id": cadt_id
            },
            async: true,
            dataType: 'html',
            success: function (data) {
                //hideloading screen//
                $('.loading-screen').prop('hidden', true);

                //display data//
                $('.display-generated-checklist').html(data);
                //rowspanizer
                $("#generated_checklist").rowspanizer({
                    columns: [0, 1]
                });
                $('.btn-generate-checklist').prop('disabled', false);
                $('.btn-generate-checklist').html('<i class="fa fa-print"></i> Generate');
            }
        });
    });

    //end generate checklist module//

    $('.dataTables_paginate').addClass('p-3');
    $('.dataTables_info').addClass('p-3');
    console.log(localStorage);
});
