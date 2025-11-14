<script type="text/javascript">
    // Variable Name
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-tarif-tindakan"),
        allowClear: true
    });

    var $table = $('#table_tindakan_tarif');
    var $table1 = $('#table_harga_tarif');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-tarif-tindakan').removeClass('was-validated');
        $('#modal-tarif-tindakan').modal('show');
        $('.modal-title').text('Form Tambah Tarif Tindakan');
        clearFormInputFields('.form-tarif-tindakan');

        // var url = "{{ route('generate-kode-tarif-tindakan', ':id') }}";
        // url = url.replace(':id');
        // console.warn($('input[name="kode_tarif"]').val());

        // $.get(url,
        //     function(data, textStatus, jqXHR) {
        //         $('input[name="kode_tarif"]').val(data['data']).attr('readonly', true);
        //     },
        //     "JSON"
        // );

        InitSelect2($("select[name='coa_pendapatan_rj']"), {
            url: "{{ route('get-select-coa') }}",
            dropdownParent: $("#modal-tarif-tindakan"),
            initialValue: ''
        });

        InitSelect2($("select[name='coa_pendapatan_ri']"), {
            url: "{{ route('get-select-coa') }}",
            dropdownParent: $("#modal-tarif-tindakan"),
            initialValue: ''
        });

        InitSelect2($("select[name='coa_reduksi_rj']"), {
            url: "{{ route('get-select-coa') }}",
            dropdownParent: $("#modal-tarif-tindakan"),
            initialValue: ''
        });

        InitSelect2($("select[name='coa_reduksi_ri']"), {
            url: "{{ route('get-select-coa') }}",
            dropdownParent: $("#modal-tarif-tindakan"),
            initialValue: ''
        });

        InitSelect2($("select[name='coa_mcu_insite']"), {
            url: "{{ route('get-select-coa') }}",
            dropdownParent: $("#modal-tarif-tindakan"),
            initialValue: ''
        });

        InitSelect2($("select[name='coa_mcu_onsite']"), {
            url: "{{ route('get-select-coa') }}",
            dropdownParent: $("#modal-tarif-tindakan"),
            initialValue: ''
        });

    });

    // Open Modal Harga Tindakan
    $(document).on('click', '.add-btn-harga', function() {
        $('.form-harga-detail').removeClass('was-validated');
        $('#modal-harga-tindakan').modal('hide');
        $('#modal-harga-detail').modal('show');
        $('.modal-title').text('Form Tambah Harga Tindakan');
        $('input[name="id1"]').val('');
        $('input[name="kelas_1"]').val('');
        $('input[name="kelas_2"]').val('');
        $('input[name="kelas_3"]').val('');
        $('input[name="kelas_isolasi"]').val('');
        $('input[name="kelas_intensif"]').val('');
        $('input[name="kelas_vip"]').val('');
        $('input[name="kelas_vvip"]').val('');
        $('select[name="kode_sk "]').val('').trigger('change');

        InitSelect2($('select[name="kode_sk"]'), {
            url: "{{ route('master-data.penjamin.select_tarif') }}",
            dropdownParent: $("#modal-harga-detail"),
            initialValue: ''

        });
    });

    $('#modal-harga-detail').on('hidden.bs.modal', function() {
        $('#modal-harga-tindakan').modal('show');
    });


    // Save  Tarif
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('tarif.tindakan.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('tarif.tindakan.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-tarif-tindakan');
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
                    data: $('.form-tarif-tindakan').serialize(),
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
                            $('#modal-tarif-tindakan').modal('hide');
                            $table.bootstrapTable('refresh');
                        } else {
                            Alert('Warning', res.message);
                            $('#modal-tarif-tindakan').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            var errors = xhr.responseJSON.errors;
                            Alert('danger', res.message);
                        } else if (xhr.status == 500) {
                            Alert('info', "Silahkan hubungi IT Rumah Sakit!");
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });


    // Save  harga
    $(document).on('click', '.save-btn-harga', function() {
        var id1 = $('input[name="id1"]').val();
        if (id1) {
            var url = "{{ route('tarif.harga.update', ':id1') }}";
            url = url.replace(':id1', id1);
            var type = "PUT";
        } else {
            var url = "{{ route('tarif.harga.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-harga-detail');
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
                    data: $('.form-harga-detail').serialize(),
                    beforeSend: function() {
                        $('.save-btn-harga').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('.save-btn-harga').html(
                                '<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function(res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-harga-detail').modal('hide');
                            $table1.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                            $('#modal-harga-detail').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            var errors = xhr.responseJSON.errors;
                            Alert('danger', res.message);
                        } else if (xhr.status == 500) {
                            Alert('info', "Silahkan hubungi IT Rumah Sakit!");
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


    // init table
    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('tarif.tindakan.view') }}",
            columns: [
                [
                    //     {
                    //     title: 'No.',
                    //     align: 'center',
                    //     valign: 'middle',
                    //     sortable: true,
                    //     width: '5%',
                    //     formatter: function (value, row, index) {
                    //         return index + 1
                    //     }
                    // },
                    {
                        width: '100%',
                        field: 'kode_tarif',
                        sortable: true,
                    },
                    {
                        field: 'tindakan',
                        sortable: true,
                    },
                    {
                        field: 'kategori',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'cito',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        // title: 'STATUS',
                        field: 'status',
                        sortable: true,
                        events: window.operateChange,
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
                        // title: 'ACTIONS',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.operateEvents,
                        formatter: actionsFunction
                    }
                ]
            ],
            error: function(xhr, status, error) {
                if (xhr.status == 400) {
                    var errors = xhr.responseJSON.errors;
                    Alert('danger', res.message);
                } else if (xhr.status == 500) {
                    Alert('info', "Silahkan hubungi IT Rumah Sakit!");
                }
            },
            responseHandler: function(data) {
                return data;
            }
        });
    }


    // init table harga tindakan
    function initTableHarga($kode_tarif) {
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
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('tarif.harga.view') }}",
            queryParams: function(params) {
                return {
                    kode: $kode_tarif
                }
            },
            columns: [
                [{
                        field: 'sk',
                        sortable: true,
                    },
                    {
                        field: 'kelas_1',
                        sortable: true,
                    },
                    {
                        field: 'kelas_2',
                        sortable: true,
                    },
                    {
                        field: 'kelas_3',
                        sortable: true,
                    },
                    {
                        field: 'kelas_intensif',
                        sortable: true,
                    },
                    {
                        field: 'kelas_isolasi',
                        sortable: true,
                    },
                    {
                        field: 'kelas_vip',
                        sortable: true,
                    },
                    {
                        field: 'kelas_vvip',
                        sortable: true,
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

    function actionsFunction(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-info" href="javascript:void(0)"><i class="fa fa-list text-success"></i> Info</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    function actionsFunction1(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit1" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete1" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-info': function(e, value, row, index) {
            $('#modal-harga-tindakan').modal('show');
            $('.modal-title').text('Form Harga Tindakan');
            $('input[name="id"]').val(row.id);
            $('input[name="kode_tarif"]').val(row.kode_tarif);
            $('input[name="tindakan"]').val(row.tindakan);
            $('input[name="cito"]').val(row.cito);
            $('input[name="status"]').prop('checked', row.status === '1');
            $('select[name="kategori"]').val(row.kategori).trigger('change');
            initTableHarga(row.kode_tarif);
        },
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-tarif-tindakan').modal('show');
            $('.modal-title').text('Form Edit Tarif Tindakan');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode_tarif"]').val(row.kode_tarif);
            $('input[name="tindakan"]').val(row.tindakan);
            $('input[name="cito"]').val(row.cito);
            // $('input[name="status"]').prop('checked', row.status === '1');
            $('select[name="kategori"]').val(row.kategori).trigger('change');


            InitSelect2($("select[name='coa_pendapatan_rj']"), {
                url: "{{ route('get-select-coa') }}",
                dropdownParent: $("#modal-tarif-tindakan"),
                initialValue: row.coa_pendapatan_rj
            });

            InitSelect2($("select[name='coa_pendapatan_ri']"), {
                url: "{{ route('get-select-coa') }}",
                dropdownParent: $("#modal-tarif-tindakan"),
                initialValue: row.coa_pendapatan_ri
            });

            InitSelect2($("select[name='coa_reduksi_rj']"), {
                url: "{{ route('get-select-coa') }}",
                dropdownParent: $("#modal-tarif-tindakan"),
                initialValue: row.coa_reduksi_rj
            });

            InitSelect2($("select[name='coa_reduksi_ri']"), {
                url: "{{ route('get-select-coa') }}",
                dropdownParent: $("#modal-tarif-tindakan"),
                initialValue: row.coa_reduksi_ri
            });

            InitSelect2($("select[name='coa_mcu_insite']"), {
                url: "{{ route('get-select-coa') }}",
                dropdownParent: $("#modal-tarif-tindakan"),
                initialValue: row.coa_mcu_insite
            });

            InitSelect2($("select[name='coa_mcu_onsite']"), {
                url: "{{ route('get-select-coa') }}",
                dropdownParent: $("#modal-tarif-tindakan"),
                initialValue: row.coa_mcu_onsite
            });
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('tarif.tindakan.delete', ':id') }}";
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
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    window.operateEvents1 = {
        'click .btn-edit1': function(e, value, row, index) {
            $('.form-harga-detail').removeClass('was-validated');
            $('#modal-harga-tindakan').modal('hide');
            $('#modal-harga-detail').modal('show');
            $('.modal-title').text('Form Edit Harga Tindakan');

            $('input[name="id1"]').val(row.id1);

            InitCleaveJs($("input[name='kelas_1']"), {
                type: 'rupiah',
                initValue: row.kelas_1
            });

            InitCleaveJs($("input[name='kelas_2']"), {
                type: 'rupiah',
                initValue: row.kelas_2
            });

            InitCleaveJs($("input[name='kelas_3']"), {
                type: 'rupiah',
                initValue: row.kelas_3
            });

            InitCleaveJs($("input[name='kelas_isolasi']"), {
                type: 'rupiah',
                initValue: row.kelas_isolasi
            });

            InitCleaveJs($("input[name='kelas_intensif']"), {
                type: 'rupiah',
                initValue: row.kelas_intensif
            });

            InitCleaveJs($("input[name='kelas_vip']"), {
                type: 'rupiah',
                initValue: row.kelas_vip
            });

            InitCleaveJs($("input[name='kelas_vvip']"), {
                type: 'rupiah',
                initValue: row.kelas_vvip
            });

            InitSelect2($('select[name="kode_sk"]'), {
                url: "{{ route('master-data.penjamin.select_tarif') }}",
                dropdownParent: $("#modal-harga-detail"),
                initialValue: row.kode_sk
            });

        },
        'click .btn-delete1': function(e, value, row, index) {
            var url = "{{ route('tarif.harga.delete', ':id1') }}";
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

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('tarif.tarif.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $table.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 400) {
                        var errors = xhr.responseJSON.errors;
                        Alert('danger', res.message);
                    } else if (xhr.status == 500) {
                        Alert('info', "Silahkan hubungi IT Rumah Sakit!");
                    }
                }
            });
        }
    }
</script>
