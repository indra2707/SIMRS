<script type="text/javascript">
    // Tabel
    var $table_sk_struktur = $('#table_sk_struktur');
    var $table_jabatan = $('#table_jabatan');

    //select
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-jabatan"),
        allowClear: true

    });

    // Open Modal SK Struktur
    $(document).on('click', '.add-btn', function () {
        $('.form-sk-struktur').removeClass('was-validated');
        $('#modal-sk-struktur').modal('show');
        $('.modal-title').text('Form Tambah SK Struktur');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="no_sk"]').val('');
        $('input[name="tanggal_mulai"]').val('');
        $('input[name="tanggal_selesai"]').val('');
        $('textarea[name="keterangan"]').val('');
        $('input[name="status"]').prop('checked', true);
    });

    // Save Sk Struktur
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.sk-struktur.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.sk-struktur.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-sk-struktur');
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-sk-struktur').serialize(),
                    beforeSend: function () {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-sk-struktur').modal('hide');
                            $table_sk_struktur.bootstrapTable('refresh');
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
                            form.classList.remove('was-validated');
                        }
                    },
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function () {
        initTable();
        initTable1();
    });


    // Table SK Struktur
    function initTable() {
        $table_sk_struktur.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            // showColumns: true,
            // showPaginationSwitch: true,
            // showToggle: true,
            // showExport: true,
            // pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.sk-struktur.view') }}",
            columns: [
                [
                    {
                        width: '50px',
                        field: 'no',
                        align: 'center',
                        formatter: function (value, row, index) {
                            return index + 1;
                        }
                    },
                    {
                        field: 'no_sk',
                        sortable: true,
                    },
                    {
                        field: 'tanggal_mulai',
                        sortable: true,
                    },
                    {
                        field: 'tanggal_selesai',
                        sortable: true,
                    },
                    {
                        field: 'keterangan',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'status',
                        sortable: true,
                        events: window.updateStatusSKStruktur,
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
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsSKStruktur,
                        formatter: actionsFunctionSKStruktur
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }

    //Tabel Jabatan
    function initTable1() {
        $table_jabatan.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            // showColumns: true,
            // showPaginationSwitch: true,
            // showToggle: true,
            // showExport: true,
            // pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.jabatan.view') }}",
            queryParams: function (params) {
                return {
                    id_sk_struktur: selectedSkId
                };
            },
            columns: [
                [
                    {
                        width: '50px',
                        field: 'no',
                        align: 'center',
                        formatter: function (value, row1, index) {
                            return index + 1;
                        }
                    },
                    {
                        field: 'no_skstruktur',
                        sortable: true,
                    },
                    {
                        field: 'unit',
                        sortable: true,
                    },
                    {
                        field: 'nama_jabatan',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'status',
                        sortable: true,
                        events: window.updateStatusJabatan,
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
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsJabatan,
                        formatter: actionsFunctionJabatan
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }

    // Format Action Button SK Struktur
    function actionsFunctionSKStruktur(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-jabatan" href="javascript:void(0)"><i class="fa fa-list text-secondary"></i> Jabatan</a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    //Format Action Button Jabatan 
    function actionsFunctionJabatan(value, row1, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions sk struktur
    window.eventsSKStruktur = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-sk-struktur').modal('show');
            $('.modal-title').text('Form Edit SK Struktur');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="no_sk"]').val(row.no_sk);
            $('input[name="tanggal_mulai"]').val(row.tanggal_mulai);
            $('input[name="tanggal_selesai"]').val(row.tanggal_selesai);
            $('textarea[name="keterangan"]').val(row.keterangan);
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('master-data.sk-struktur.delete', ':id') }}";
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
                        $table_sk_struktur.bootstrapTable('refresh');
                    });

                }
            })
        },
        'click .btn-jabatan': function (e, value, row, index) {
            $('#modal-view-jabatan').modal('show');
            $('.modal-title').text('Data Jabatan');
            $('.add-jabatan').data('id', row.id);

            selectedSkId = row.id;
            $('.add-jabatan').data('id', selectedSkId);
            $('#table_jabatan').bootstrapTable('refresh');
        }
    }

    // Window operateChange Status SK Struktur
    window.updateStatusSKStruktur = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('master-data.sk-struktur.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'tbl_sk_struktur',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $table_sk_struktur.bootstrapTable('refresh');
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

    // Handle events button actions jabatan
    window.eventsJabatan = {
        'click .btn-delete': function (e, value, row1, index) {
            var url = "{{ route('master-data.jabatan.delete', ':id') }}";
            url = url.replace(':id', row1.id);
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
                        $table_jabatan.bootstrapTable('refresh');
                    });

                }
            })
        },
         'click .btn-edit': function (e, value, row1, index) {
            $('#modal-jabatan').modal('show');
            $('#modal-view-jabatan').modal('hide');
            $('.modal-title').text('Form Edit Jabatan');
            $('.save-jabatan').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_jabatan"]').val(row1.id);
            $('input[name="id_sk_struktur"]').val(row1.id_sk_struktur);
            $('input[name="nama_jabatan"]').val(row1.nama_jabatan);
            $('select[name="unit"]').val(row1.unit).trigger('change');
            $('textarea[name="keterangan"]').val(row1.keterangan);
            $('input[name="status"]').prop('checked', row1.status === '1');
        }
    }

    // Open Modal Jabatan
    $(document).on('click', '.add-jabatan', function () {
        let id_sk = $(this).data('id');
        $('.form-jabatan').removeClass('was-validated');
        $('#modal-jabatan').modal('show');
        $('#modal-view-jabatan').modal('hide');
        $('.modal-title').text('Form Tambah Jabatan');
        $('.save-jabatan').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_jabatan"]').val('');
        $('input[name="id_sk_struktur"]').val(id_sk);
        $('input[name="nama_jabatan"]').val('');
        $('select[name="unit"]').val('').trigger('change');
        $('input[name="status"]').prop('checked', true);
    });

    $('#modal-jabatan').on('hidden.bs.modal', function () {
        $('#modal-view-jabatan').modal('show');
    });


    //save jabatan
    $(document).on('click', '.save-jabatan', function () {
        var id = $('input[name="id_jabatan"]').val();
        if (id) {
            var url = "{{ route('master-data.jabatan.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.jabatan.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-jabatan');
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-jabatan').serialize(),
                    beforeSend: function () {
                        $('.save-jabatan').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function () {
                        $('.save-jabatan').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-jabatan').modal('hide');
                            $('#modal-view-jabatan').modal('show');
                            $table_jabatan.bootstrapTable('refresh');
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
                            form.classList.remove('was-validated');
                        }
                    },
                });
            }
            form.classList.add('was-validated');
        });
    });

    // update status javbatan
    window.updateStatusJabatan = {
        'click .update-status': function (e, value, row1, index) {
            var url = "{{ route('master-data.jabatan.update-status', ':id') }}";
            url = url.replace(':id', row1.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'tbl_jabatan',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $table_jabatan.bootstrapTable('refresh');
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

</script>