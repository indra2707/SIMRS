<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-poli"),
        allowClear: true

    });

    // Tabel
    var $tablePoli = $('#table_poli');
    var $table1 = $('#table_tindakan');
    var $table2 = $('#table_obat');

    // Open Modal Poli
    $(document).on('click', '.add-btn', function() {
        $('.form-poli').removeClass('was-validated');
        $('#modal-poli').modal('show');
        $('.modal-title').text('Form Tambah Poliklinik');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="kode"]').val('');
        $('input[name="nama"]').val('');
        $('select[name="kategori"]').val('').trigger('change');
        $('input[name="status"]').prop('checked', true);
    });

    $('#info-home-tab').on('click', function() {
        initTableTindakan($('input[name="kode"]').val());
    });
    $('#profile-info-tab').on('click', function() {
        initTableObat($('input[name="kode"]').val());
    });

    // Open Modal Tindakan
    $(document).on('click', '.add-tindakan', function() {
        $('#modal-infos').modal('hide');
        $('#modal-input-tindakan').modal('show');
        $('.form-tindakan').removeClass('was-validated');
        $('.modal-title').text('Form Tambah Tindakan');
        $('.save-btn-tindakan').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="status1"]').prop('checked', true);

        InitSelect2($("select[name='tindakan']"), {
            url: "{{ route('get-select-tindakan') }}",
            dropdownParent: $("#modal-input-tindakan"),
            initialValue: ''
        });
    });

    $('#modal-input-tindakan').on('hidden.bs.modal', function() {
        $('#modal-infos').modal('show');
    });


    // Open Modal Obat
    $(document).on('click', '.add-obat', function() {
        $('#modal-kelompok').modal('hide');
        $('#modal-input-obat').modal('show');
        $('.form-obat').removeClass('was-validated');
        $('.modal-title').text('Form Tambah Obat & BMHP');
        $('.save-btn-obat').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="kode_obat"]').val('');
        $('input[name="status2"]').prop('checked', true);
    });

    $('#modal-input-obat').on('hidden.bs.modal', function() {
        $('#modal-kelompok').modal('show');
    });


    // Save Poli
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.poli.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.poli.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-poli');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-poli').serialize(),
                    beforeSend: function() {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function(res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-poli').modal('hide');
                            $tablePoli.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                            $('#modal-poli').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info',
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });


    // Save Tindakan poli
    $(document).on('click', '.save-btn-tindakan', function() {
        var id1 = $('input[name="id1"]').val();
        if (id1) {
            var url = "{{ route('master-data.tindakan-poli.update', ':id') }}";
            url = url.replace(':id1', id1);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.tindakan-poli.create') }}";
            // url = url.replace(':kode', kode);
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-tindakan');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-tindakan').serialize(),
                    beforeSend: function() {
                        $('.save-btn-tindakan').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('.save-btn-tindakan').html(
                                '<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function(res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-input-tindakan').modal('hide');
                            $table1.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                            $('#modal-input-tindakan').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info',
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });


    // Save Obat & BMHP
    $(document).on('click', '.save-btn-obat', function() {
        var id2 = $('input[name="id2"]').val();
        if (id2) {
            var url = "{{ route('master-data.obat-poli.update', ':id2') }}";
            url = url.replace(':id2', id2);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.obat-poli.create') }}";
            // url = url.replace(':kode', kode);
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-obat');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-obat').serialize(),
                    beforeSend: function() {
                        $('.save-btn-obat').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('.save-btn-obat').html(
                                '<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function(res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-input-obat').modal('hide');
                            $table2.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                            $('#modal-input-obat').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info',
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function() {
        initTable();
    });

    // ---------------------------------------------------------------------------------------------
    // init table
    // Table Poliklinik
    function initTable() {
        $tablePoli.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            // exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.poli.view') }}",
            columns: [
                [{
                        width: '100%',
                        field: 'kode',
                        sortable: true,
                    },
                    {
                        field: 'nama',
                        sortable: true,
                    },
                    {
                        width: '150%',
                        field: 'kategori',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        field: 'status',
                        sortable: true,
                        events: window.updateStatusPoli,
                        formatter: function(value, row, index) {
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
                        width: '5%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsPoliklinik,
                        formatter: actionsFunctionPoliklinik
                    }
                ]
            ],
            responseHandler: function(data) {
                return data;
            }
        });
    }


    // ---------------------------------------------------------------------------------------------
    // init table Tindakan
    function initTableTindakan($kode) {
        $table1.bootstrapTable('destroy').bootstrapTable({
            height: 350,
            locale: 'en-US',
            search: true,
            // showColumns: true,
            // showPaginationSwitch: true,
            // showToggle: true,
            // showExport: true,
            pagination: true,
            pageSize: 10,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            // exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.tindakan-poli.view') }}",
            queryParams: function(params) {
                return {
                    kode: $kode
                }
            },
            columns: [
                [{
                        field: 'kode_tindakan',
                        sortable: true,
                        visible: false,
                    },
                    {
                        field: 'tindakan',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        field: 'status',
                        sortable: true,
                        events: window.operateChange1,
                        formatter: function(value, row, index) {
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
                        width: '5%',
                        title: 'ACTIONS',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.operateEvents1,
                        formatter: actionsFunction1
                    }
                ]
            ],
            responseHandler: function(data) {
                return data;
            }
        });
    }


    // ---------------------------------------------------------------------------------------------
    // init table Tindakan
    function initTableObat($kode) {
        $table2.bootstrapTable('destroy').bootstrapTable({
            height: 350,
            locale: 'en-US',
            search: true,
            pagination: true,
            pageSize: 10,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            url: "{{ route('master-data.obat-poli.view') }}",
            queryParams: function(params) {
                return {
                    kode: $kode
                }
            },
            columns: [
                [{
                        field: 'kode_obat',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        field: 'status',
                        sortable: true,
                        events: window.operateChange2,
                        formatter: function(value, row, index) {
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
                        width: '5%',
                        title: 'ACTIONS',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.operateEvents2,
                        formatter: actionsFunction2
                    }
                ]
            ],
            responseHandler: function(data) {
                return data;
            }
        });
    }

    function actionsFunctionPoliklinik(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-infos" href="javascript:void(0)"><i class="fa fa-list text-info"></i> Info</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    function actionsFunction1(value, row, index) {
        return [
            // '<button class="btn btn-danger btn-sm btn-delete1">',
            // '<span class="fa fa-trash"></span>',
            // '</button>',

            '<a class="btn-delete1" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i></a></a>',

        ].join("");
    }

    function actionsFunction2(value, row, index) {
        return [
            // '<div class="dropdown icon-dropdown">',
            '<button class="btn  btn-delete2" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="fa fa-trash text-danger"></i>',
            '</button>',
            // '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            // // '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            // '<a class="dropdown-item btn-delete1" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            // '</div>',
            // '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsPoliklinik = {
        'click .btn-infos': function(e, value, row, index) {
            $('#modal-infos').modal('show');
            initTableTindakan(row.kode);
            $('.modal-title').text('Form Mapping Data');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('select[name="kategori"]').val(row.kategori).trigger('change');
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-poli').modal('show');
            $('.modal-title').text('Form Edit poli');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('select[name="kategori"]').val(row.kategori).trigger('change');
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('master-data.poli.delete', ':id') }}";
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
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function() {
                        $tablePoli.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // modal-input-tindakan
    window.operateEvents1 = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-poli').modal('hide');
            $('#modal-kelompok').modal('hide');
            $('#modal-input-tindakan').modal('show');
            $('.modal-title').text('Form Edit poli');
            $('.save-btn-tindakan').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-delete1': function(e, value, row, index) {
            var url = "{{ route('master-data.tindakan-poli.delete', ':id1') }}";
            url = url.replace(':id1', row.id1);
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
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function() {
                        $table1.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // modal-input-obat
    window.operateEvents2 = {
        'click .btn-edit': function(e, value, row, index) {
            // $('#modal-poli').modal('hide');
            $('#modal-kelompok').modal('hide');
            $('#modal-input-obat').modal('show');
            $('.modal-title').text('Form Edit poli');
            $('.save-btn-obat').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="kode_obat"]').val(row.kode_obat);
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-delete2': function(e, value, row, index) {
            var url = "{{ route('master-data.obat-poli.delete', ':id2') }}";
            url = url.replace(':id2', row.id2);
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
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function() {
                        $table2.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status poli
    window.updateStatusPoli = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('tarif.sk-tarif.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'polis',
                    _token: "{{ csrf_token() }}"
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tablePoli.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
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

    // Window operateChange Status tindakan
    window.operateChange1 = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('master-data.tindakan-poli.update-status', ':id1') }}";
            url = url.replace(':id1', row.id1);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        $.notify({
                            icon: 'fa fa-check',
                            title: 'Success',
                            message: res.message
                        }, {
                            type: 'success',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    } else {
                        $.notify({
                            icon: 'fa fa-check',
                            title: 'Warning',
                            message: res.message
                        }, {
                            type: 'warning',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    }
                    $table1.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 400) {
                        var errors = xhr.responseJSON.errors;
                        $.notify({
                            icon: 'fa fa-check',
                            title: error,
                            message: xhr.responseJSON.message
                        }, {
                            type: 'danger',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    } else if (xhr.status == 500) {
                        $.notify({
                            icon: 'icon-info-alt',
                            title: 'error',
                            message: "Silahkan hubungi IT Rumah Sakit!"
                        }, {
                            type: 'danger',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    }
                }
            });
        }
    }


    // Window operateChange Status tindakan
    window.operateChange2 = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('master-data.obat-poli.update-status', ':id') }}";
            url = url.replace(':id', row.id2);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        $.notify({
                            icon: 'fa fa-check',
                            title: 'Success',
                            message: res.message
                        }, {
                            type: 'success',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    } else {
                        $.notify({
                            icon: 'fa fa-check',
                            title: 'Warning',
                            message: res.message
                        }, {
                            type: 'warning',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    }
                    $table1.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 400) {
                        var errors = xhr.responseJSON.errors;
                        $.notify({
                            icon: 'fa fa-check',
                            title: error,
                            message: xhr.responseJSON.message
                        }, {
                            type: 'danger',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    } else if (xhr.status == 500) {
                        $.notify({
                            icon: 'icon-info-alt',
                            title: 'error',
                            message: "Silahkan hubungi IT Rumah Sakit!"
                        }, {
                            type: 'danger',
                            allow_dismiss: true,
                            delay: 2000,
                            showProgressbar: true,
                            timer: 300,
                            z_index: 1127,
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        });
                    }
                }
            });
        }
    }
</script>
