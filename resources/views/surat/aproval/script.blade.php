<script type="text/javascript">
    // Tabel
    var $tableAproval = $('#table_aproval');
    var $tableAprovalDetail = $('#table_aproval_detail');

    // filter status
    $(".select2").select2({
        placeholder: "--- Pilih Salah Satu ---",
        theme: "bootstrap-5",
        allowClear: true,
        width: "100%",
        dropdownParent: $("#modal-input-hirarki")
    });

    // close modal
    $('#modal-preview-pdf').on('hidden.bs.modal', function () {
        $('#modal-perizinan').modal('show');
    });

    // Open Modal aproval
    $(document).on('click', '.add-btn', function () {
        $('.form-aproval').removeClass('was-validated');
        $('#modal-aproval').modal('show');
        $('.modal-title').text('Form Tambah Aproval');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="nama_aproval"]').val('');
    });

    // Open Modal Hirarki
    $(document).on('click', '.add-hirarki', function () {
        $('.form-hirarki').removeClass('was-validated');
        $('#modal-input-hirarki').modal('show');
        $('#modal-hirarki').modal('hide');
        $('.modal-title').text('Form Tambah Hirarki');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_detail"]').val('');
        $('select[name="parent_jabatan"]').val('').trigger('change');
        $('select[name="id_pegawai"]').val('').trigger('change');

        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-input-hirarki")
        });

    });

    $('#modal-input-hirarki').on('hidden.bs.modal', function () {
        $('#modal-hirarki').modal('show');
    });


    // Save aproval
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        var url, type;
        if (id) {
            url = "{{ route('surat.aproval.update', ':id') }}";
            url = url.replace(':id', id);
            type = "POST";
        } else {
            url = "{{ route('surat.aproval.create') }}";
            type = "POST";
        }
        var forms = document.getElementsByClassName('form-aproval');
        Array.prototype.filter.call(forms, function (form) {

            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {

                var formData = new FormData(form);

                // method spoofing untuk update
                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    beforeSend: function () {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-aproval').modal('hide');
                            $tableAproval.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-warning',
                                title: 'Warning',
                                message: res.message
                            }, {
                                type: 'warning'
                            });

                            form.classList.remove('was-validated');
                        }
                    }
                });
            }

            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function () {
        initTable();
    });


    // Table aproval
    function initTable() {
        $tableAproval.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id',
            uniqueId: 'id',
            sidePagination: 'client',
            maintainSelected: true,
            pagination: true,
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            showExport: true,
            pagination: true,
            maintainSelected: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['excel', 'pdf'],
            url: "{{ route('surat.aproval.view') }}",
            columns: [
                [{
                    field: "id",
                    sortable: true,
                    align: "center",
                    width: '70px',
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    field: 'nama_aproval',
                    sortable: true,
                },
                {
                    width: '100%',
                    field: 'status',
                    sortable: true,
                    events: window.updateStatus,
                    formatter: function (value, row, index) {
                        return [
                            '<div class="media-body text-center switch-sm icon-state">',
                            '<label class="switch">',
                            '<input type="checkbox" class="update-status" ' + (row.status ===
                                '1' ? 'checked' : '') + '>',
                            '<span class="switch-state"></span>',
                            '</label>',
                            '</div>'
                        ].join("");
                    }
                },
                {
                    title: 'Action',
                    field: 'action',
                    align: 'center',
                    width: '100px',
                    events: window.eventsAproval,
                    formatter: actionsFunctionAproval
                }
                ]
            ],
            error: function (xhr, status, error) {
                if (xhr.status == 400) {
                    $.notify({
                        icon: "fa fa-check",
                        title: error,
                        message: xhr.responseJSON.message,
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 2000,
                        showProgressbar: true,
                        timer: 300,
                        z_index: 1127,
                        animate: {
                            enter: "animated fadeInDown",
                            exit: "animated fadeOutUp",
                        },
                    });
                } else if (xhr.status == 500) {
                    $.notify({
                        icon: "icon-info-alt",
                        title: "Error",
                        message: "Silahkan hubungi IT Rumah Sakit!",
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 2000,
                        showProgressbar: true,
                        timer: 300,
                        z_index: 1127,
                        animate: {
                            enter: "animated fadeInDown",
                            exit: "animated fadeOutUp",
                        },
                    });
                }
            },
            responseHandler: function (res) {
                return res;
            }
        });
    }

    function actionsFunctionAproval(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-hirarki" href="javascript:void(0)"><i class="fa fa-list text-secondary"></i> Hirarki</a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsAproval = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-aproval').modal('show');
            $('.modal-title').text('Form Edit Aproval');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama_aproval"]').val(row.nama_aproval);
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('surat.aproval.delete', ':id') }}";
            url = url.replace(':id', row.id);
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin menghapus data ini?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $tableAproval.bootstrapTable('refresh');
                    });

                }
            })
        },
        'click .btn-hirarki': function (e, value, row, index) {
            $('#modal-hirarki').modal('show');
            $('.modal-title').text('Data Hirarki');
            $('.add-jabatan').data('id', row.id);
            $('input[name="id_aproval"]').val(row.id);
            $('input[name="nama_aproval"]').val(row.nama_aproval);
            initTabledetail(row.id);
        }
    }

    // Window operateChange Status
    window.updateStatus = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('surat.aproval.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'polis',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tableAproval.bootstrapTable('refresh');
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 400) {
                        Alert('error', xhr.responseJSON.message);
                    } else if (xhr.status == 500) {
                        Alert('info',
                            "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                        );
                    }
                }
            });
        }
    }

    // Table aproval Detail
    function initTabledetail(idAproval) {
        $tableAprovalDetail.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id',
            uniqueId: 'id',
            sidePagination: 'client',
            maintainSelected: true,
            pagination: true,
            search: true,
            // showColumns: true,
            // showPaginationSwitch: true,
            // showToggle: true,
            // showExport: true,
            pagination: true,
            maintainSelected: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            // exportTypes: ['excel', 'pdf'],
            url: "{{ route('surat.aprovaldetail.view') }}",
            // Kirim row.id
            queryParams: function (params) {
                params.id_aproval = idAproval;
                return params;
            },
            columns: [
                [{
                    field: "id",
                    sortable: true,
                    align: "center",
                    width: '70px',
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    field: 'parent_jabatan',
                    title: 'Parent Jabatan',
                    sortable: true,
                    formatter: function (value, row, index) {
                        switch (String(value)) {
                            case '0':
                                return 'Director';
                            case '1':
                                return 'Vice Director';
                            case '2':
                                return 'Head';
                            default:
                                return '-';
                        }
                    }
                },
                {
                    field: 'nama_pekerja',
                    sortable: true,
                },
                {
                    title: 'Action',
                    field: 'action',
                    align: 'center',
                    width: '100px',
                    events: window.eventsAprovalDetail,
                    formatter: actionsFunctionAprovalDetail
                }
                ]
            ],
            error: function (xhr, status, error) {
                if (xhr.status == 400) {
                    $.notify({
                        icon: "fa fa-check",
                        title: error,
                        message: xhr.responseJSON.message,
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 2000,
                        showProgressbar: true,
                        timer: 300,
                        z_index: 1127,
                        animate: {
                            enter: "animated fadeInDown",
                            exit: "animated fadeOutUp",
                        },
                    });
                } else if (xhr.status == 500) {
                    $.notify({
                        icon: "icon-info-alt",
                        title: "Error",
                        message: "Silahkan hubungi IT Rumah Sakit!",
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 2000,
                        showProgressbar: true,
                        timer: 300,
                        z_index: 1127,
                        animate: {
                            enter: "animated fadeInDown",
                            exit: "animated fadeOutUp",
                        },
                    });
                }
            },
            responseHandler: function (res) {
                return res;
            }
        });
    }

    // Save aproval Detail
    $(document).on('click', '.save-btn-detail', function () {
        var id = $('input[name="id_detail"]').val();
        var url, type;
        if (id) {
            url = "{{ route('surat.aprovaldetail.update', ':id') }}";
            url = url.replace(':id', id);
            type = "POST";
        } else {
            url = "{{ route('surat.aprovaldetail.create') }}";
            type = "POST";
        }
        var forms = document.getElementsByClassName('form-aproval-detail');
        Array.prototype.filter.call(forms, function (form) {

            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {

                var formData = new FormData(form);

                // method spoofing untuk update
                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    beforeSend: function () {
                        $('.save-btn-detail').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-detail').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-input-hirarki').modal('hide');
                            $tableAprovalDetail.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-warning',
                                title: 'Warning',
                                message: res.message
                            }, {
                                type: 'warning'
                            });

                            form.classList.remove('was-validated');
                        }
                    }
                });
            }

            form.classList.add('was-validated');
        });
    });


    function actionsFunctionAprovalDetail(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit-hirarki" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-hirarki" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions detail
    window.eventsAprovalDetail = {
        'click .btn-edit-hirarki': function (e, value, row, index) {
            $('#modal-hirarki').modal('hide');
            $('#modal-input-hirarki').modal('show');
            $('.modal-title').text('Form Edit Hirarki');
            $('.save-btn-detail').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id_detail"]').val(row.id_detail);
            $('select[name="parent_jabatan"]').val(row.parent_jabatan).trigger('change');

              InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-input-hirarki"),
                initialValue: row.id_pegawai
            });
        },
        'click .btn-delete-hirarki': function (e, value, row, index) {
            var url = "{{ route('surat.aprovaldetail.delete', ':id') }}";
            url = url.replace(':id', row.id_detail);
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin menghapus data ini?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $tableAprovalDetail.bootstrapTable('refresh');
                    });

                }
            })
        }
    }


</script>