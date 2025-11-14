<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-aset"),
        allowClear: true

    });

    // Tabel
    var $tableAset = $('#table_aset');
    var $tableInfoMutasi = $('#table-info-mutasi');

    // Open Modal Aset
    $(document).on('click', '.add-btn', function () {
        $('.form-aset').removeClass('was-validated');
        $('#modal-aset').modal('show');
        $('.modal-title').text('Form Tambah Asset & Inventaris');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="no_aset"]').val('');
        $('input[name="nama"]').val('');
        $('input[name="harga"]').val('');
        $('input[name="no_sn"]').val('');
        $('input[name="merek"]').val('');
        $('input[name="tahun"]').val('');
        $('input[name="tipe"]').val('');
        $('select[name="kategori"]').val('').trigger('change');
        $('select[name="jenis"]').val('').trigger('change');
        $("select[name='kode_lokasi']").val('').trigger('change');
        $("select[name='kode_kondisi_aset']").val('').trigger('change');
        $("select[name='kode_kelompok_aset']").val('').trigger('change');
        $("select[name='id_vendor']").val('').trigger('change');

        $('input[name="status"]').prop('checked', true);

        InitSelect2($("select[name='kode_lokasi']"), {
            url: "{{ route('get-select-lokasi') }}",
            dropdownParent: $("#modal-aset")
        });

        InitSelect2($("select[name='id_vendor']"), {
            url: "{{ route('get-select-vendor') }}",
            dropdownParent: $("#modal-aset")
        });

        InitSelect2($("select[name='kode_kondisi_aset']"), {
            url: "{{ route('get-select-kondisi-aset') }}",
            dropdownParent: $("#modal-aset")
        });

        InitSelect2($("select[name='kode_kelompok_aset']"), {
            url: "{{ route('get-select-kelompok-aset') }}",
            dropdownParent: $("#modal-aset")
        });
    });

    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.aset.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.aset.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-aset');
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
                    data: $('.form-aset').serialize(),
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
                            $('#modal-aset').modal('hide');
                            $tableAset.bootstrapTable('refresh');
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
    });


    // Table Aset
    function initTable() {
        $tableAset.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: true,
            showExport: true,
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
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.aset.view') }}",
            columns: [
                [{
                    width: '100%',
                    field: 'no_aset',
                    sortable: true,
                },
                {
                    width: '100%',
                    field: 'jenis',
                    sortable: true,
                    visible: false,
                },
                {
                    field: 'nama',
                    sortable: true,
                },
                {
                    width: '200%',
                    field: 'merek',
                    sortable: true,
                },
                {
                    width: '200%',
                    field: 'tipe',
                    sortable: true,
                    visible: false,
                },
                {
                    width: '150%',
                    field: 'no_sn',
                    sortable: true,
                },
                {
                    width: '100%',
                    field: 'tahun',
                    sortable: true,
                },
                {
                    width: '100%',
                    field: 'kategori',
                    sortable: true,
                    visible: false,
                },
                {
                    width: '100%',
                    field: 'nama_kelompok',
                    sortable: true,
                },
                {
                    width: '150%',
                    field: 'harga',
                    sortable: true,
                    align: 'right',
                },
                // {
                //     width: '200%',
                //     field: 'id_lokasi',
                //     sortable: true,
                //     visible: false,
                // },
                {
                    width: '200%',
                    field: 'nama_lokasi',
                    sortable: true,
                    visible: false,
                },
                // {
                //     width: '200%',
                //     field: 'id_kondisi',
                //     sortable: true,
                //     visible: false,
                // },
                {
                    width: '200%',
                    field: 'nama_kondisi',
                    sortable: true,
                    visible: false,
                },
                {
                    width: '200%',
                    field: 'nama_vendor',
                    sortable: true,
                    align: 'center',
                    visible: false,
                },
                {
                    width: '20%',
                    field: 'selisih_bulan',
                    sortable: true,
                    align: 'center',
                    // visible: false,
                },
                {
                    width: '20%',
                    field: 'kelompok_bulan',
                    sortable: true,
                    align: 'center',
                    visible: false,
                },
                {
                    width: '20%',
                    field: 'sisa_umur',
                    sortable: true,
                    align: 'center',
                    visible: false,
                },
                {
                    width: '20%',
                    field: 'penyusutan',
                    sortable: true,
                    align: 'center',
                    visible: false,
                },
                {
                    width: '20%',
                    field: 'akumulasi_penyusutan',
                    sortable: true,
                    align: 'center',
                    visible: false,
                },
                {
                    width: '20%',
                    field: 'nilai_buku',
                    sortable: true,
                    align: 'center',
                    visible: false,
                },
                {
                    width: '5%',
                    field: 'status',
                    sortable: true,
                    events: window.updateStatusAset,
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
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.eventsAset,
                    formatter: actionsFunctionAset
                }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }

    function initTableMutasi(id_aset) {
        $tableInfoMutasi.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: false,
            showPaginationSwitch: false,
            showToggle: false,
            showExport: false,
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
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('master-data.aset.view_mutasi') }}",
            queryParams: function (params) {
                return { id: id_aset };
            },
            columns: [
                [
                    {
                        width: '100%',
                        field: 'tgl_mutasi',
                        sortable: true,
                    },
                    {
                        width: '350%',
                        field: 'nama_aset',
                        sortable: true,
                    },
                    {
                        width: '350%',
                        field: 'no_sn',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'nama_lokasi_asal',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'nama_lokasi_tujuan',
                        sortable: true,
                    },
                    {
                        width: '800%',
                        field: 'nama_kondisi',
                        sortable: true,
                        // visible: false,
                    },
                    {
                        width: '200%',
                        field: 'keterangan',
                        sortable: true,
                        // visible: false,
                    }
                ]

            ],
            error: function (xhr, status, error) {
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
            },
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionAset(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-infos" href="javascript:void(0)"><i class="fa fa-list text-info"></i> Info Mutasi</a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsAset = {
        'click .btn-infos': function (e, value, row, index) {
            $('#modal-info-mutasi').modal('show');
            initTableMutasi(row.id);
            $('.modal-title').text('Info Data Mutasi');

        },
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-aset').modal('show');
            $('.modal-title').text('Form Edit Asset & Inventaris');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="no_aset"]').val(row.no_aset);
            $('input[name="nama"]').val(row.nama);
            $('input[name="merek"]').val(row.merek);
            $('input[name="no_sn"]').val(row.no_sn);
            $('input[name="tahun"]').val(row.tahun);
            $('input[name="harga"]').val(row.harga);
            $('select[name="kategori"]').val(row.kategori).trigger('change');
            $('input[name="status"]').prop('checked', row.status === '1');
            $('select[name="jenis"]').val(row.jenis).trigger('change');
            $('input[name="tipe"]').val(row.tipe);
            $('select[name="kode_kelompok_aset"]').val(row.id_kelompok).trigger('change');

            InitSelect2($("select[name='kode_lokasi']"), {
                url: "{{ route('get-select-lokasi') }}",
                dropdownParent: $("#modal-aset"),
                initialValue: row.id_lokasi
            });

            InitSelect2($("select[name='id_vendor']"), {
                url: "{{ route('get-select-vendor') }}",
                dropdownParent: $("#modal-aset"),
                initialValue: row.id_vendor
            });

            InitSelect2($("select[name='kode_kondisi_aset']"), {
                url: "{{ route('get-select-kondisi-aset') }}",
                dropdownParent: $("#modal-aset"),
                initialValue: row.id_kondisi
            });

            InitSelect2($("select[name='kode_kelompok_aset']"), {
                url: "{{ route('get-select-kelompok-aset') }}",
                dropdownParent: $("#modal-aset"),
                initialValue: row.id_kelompok
            });
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('master-data.aset.delete', ':id') }}";
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
                        $tableAset.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status aset
    window.updateStatusAset = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('master-data.aset.update-status', ':id') }}";
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
                    $tableAset.bootstrapTable('refresh');
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