<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-permintaan"),
        allowClear: true
    });

    // Tabel
    var $tablePermintaan = $('#table_permintaan');


    // Open Modal permintaan
    $(document).on('click', '.add-btn', function () {
        $('.form-permintaan').removeClass('was-validated');
        $('#modal-permintaan').modal('show');
        $('.modal-title').text('Form Tambah Permintaan');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');

        $('input[name="id"]').val('');
        $('input[name="no_permintaan"]').val('');
        $('input[name="nama_permintaan"]').val('');
        $('textarea[name="catatan"]').val('');
        $('select[name="id_unit[]"]').val('').trigger('change');
        $('select[name="status"]').val('Pengajuan Panjar').trigger('change');
        $('input[type="checkbox"]').prop('checked', false);

        InitSelect2($("select[name='id_unit[]']"), {
            url: "{{ route('get-select-unit') }}",
            dropdownParent: $("#modal-permintaan"),
            placeholder: "---- Pilih Salah Satu ----",
            allowClear: true
        });
    });


    window.actionFormatter = function (value, row, index) {
        return [
            '<a class="edit-employee-btn me-2" href="javascript:void(0)" data-id="' + row.id +
            '" data-field_id="' + row.field_id + '">',
            '<i class="fa fa-edit text-primary"></i>',
            '</a>  ',
            '<a class="remove-employee-btn" href="javascript:void(0)" data-id="' + row.id + '">',
            '<i class="fa fa-trash text-danger"></i>',
            '</a>'
        ].join('')
    };


    // Save spd
    $(document).on('click', '.save-btn', function () {

        if (!$('#ditanggung').val()) {
            let activeValue = $('.biaya-group button.active').data('value');
            $('#ditanggung').val(activeValue);
        }

        var id = $('input[name="id"]').val();

        if (id) {
            var url = "{{ route('sdm.spd.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('sdm.spd.create') }}";
            var type = "POST";
        }

        var forms = document.getElementsByClassName('form-spd');
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {

                var pengikutData = getPengikutData();

                // Serialize form data
                var formData = $('.form-spd').serializeArray();

                // Tambahkan data pengikut ke formData
                formData.push({
                    name: 'pengikut_data',
                    value: JSON.stringify(pengikutData)
                });

                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $.param(formData),

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
                        if (xhr.status == 200 && res.success === true) {
                            Alert('success', res.message);
                            $('#modal-spd').modal('hide');
                            $tableSpd.bootstrapTable('refresh');
                        }
                    },

                    error: function (xhr) {
                        let message = 'Terjadi kesalahan';

                        if (xhr.status === 422) {
                            message = xhr.responseJSON?.message || 'Nomor surat sudah ada';
                        } else if (xhr.status === 500) {
                            message = 'Kesalahan server';
                        }

                        $.notify({
                            // icon: 'fa fa-warning',
                            title: 'Peringatan',
                            message: message
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
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function () {
        initTable();
    });


    // Table SPD
    function initTable() {
        $tableSpd.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
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
            url: "{{ route('sdm.spd.view') }}",
            columns: [
                [{
                    title: 'No',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    width: '5%',
                    formatter: function (value, row, index) {
                        return index + 1
                    }
                },
                {
                    field: 'no_surat',
                    sortable: true,
                },
                {
                    field: 'nama_pegawai',
                    sortable: true,
                },
                {
                    field: 'pelaksanaan',
                    sortable: true,
                },
                {
                    field: 'nama_kota1',
                    sortable: true,
                },
                {
                    field: 'nama_kota2',
                    sortable: true,
                },
                {
                    field: 'pengikut1',
                    sortable: true,
                },
                {
                    width: '50%',
                    align: 'center',
                    valign: 'middle',
                    formatter: function (value, row, index) {
                        let buttons = ''; // Tombol yang akan ditampilkan

                        // Kondisi untuk menampilkan tombol berdasarkan 'selisih_hari'
                        if (row.status == 'Draft') {
                            buttons += `
                                    <button class="btn btn-pill btn-xs btn-success action-btn">${row.status}</button>
                                `;
                        } else {
                            buttons += `
                                    <button class="btn btn-pill btn-xs btn-danger action-btn">${row.status}</button>
                                `;
                        }

                        return buttons;
                    }
                },
                {
                    width: '100%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.eventsSpd,
                    formatter: actionsFunctionSpd
                }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionSpd(value, row, index) {
        const currentUsername = "{{ Auth::user()->username }}";

        if (currentUsername === 'superadmin') {
            if (row.status === 'Draft') {
                return [
                    '<div class="dropdown icon-dropdown">',
                    '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">',
                    '<i class="icon-more-alt"></i>',
                    '</button>',
                    '<div class="dropdown-menu dropdown-menu-end">',
                    '<a class="dropdown-item btn-tutup" href="javascript:void(0)"><i class="fa fa-lock text-warning"></i> Close</a>',
                    '<a class="dropdown-item btn-print" href="javascript:void(0)"><i class="fa fa-print text-secondary"></i> Print</a>',
                    '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
                    '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
                    '</div>',
                    '</div>',
                ].join("");
            }
            return [
                '<div class="dropdown icon-dropdown">',
                '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">',
                '<i class="icon-more-alt"></i>',
                '</button>',
                '<div class="dropdown-menu dropdown-menu-end">',
                '<a class="dropdown-item btn-draft" href="javascript:void(0)"><i class="fa fa-lock text-warning"></i> Draft</a>',
                '<a class="dropdown-item btn-print" href="javascript:void(0)"><i class="fa fa-print text-secondary"></i> Print</a>',
                '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
                '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
                '</div>',
                '</div>',
            ].join("");
        } else {
            // User biasa
            if (row.status === 'Close') {
                return [
                    '<div class="dropdown icon-dropdown">',
                    '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">',
                    '<i class="icon-more-alt"></i>',
                    '</button>',
                    '<div class="dropdown-menu dropdown-menu-end">',
                    '<a class="dropdown-item btn-print" href="javascript:void(0)">',
                    '<i class="fa fa-print text-secondary"></i> Print',
                    '</a>',
                    '</div>',
                    '</div>',
                ].join("");
            }

            return [
                '<div class="dropdown icon-dropdown">',
                '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">',
                '<i class="icon-more-alt"></i>',
                '</button>',
                '<div class="dropdown-menu dropdown-menu-end">',
                '<a class="dropdown-item btn-tutup" href="javascript:void(0)"><i class="fa fa-lock text-warning"></i> Close</a>',
                '<a class="dropdown-item btn-print" href="javascript:void(0)"><i class="fa fa-print text-secondary"></i> Print</a>',
                '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
                '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
                '</div>',
                '</div>',
            ].join("");
        }
    }


    // Handle events button actions
    window.eventsSpd = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-spd').modal('show');
            $('.modal-title').text('Form Edit SPD');
            let pengikutValue = (row.pengikut1 === 'Tidak Ada') ? '0' : '1';

            // Reset tabel pengikut
            $table_employee.bootstrapTable('removeAll');

            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="no_surat"]').val(row.no_surat);
            $('select[name="pelaksanaan"]').val(row.pelaksanaan).trigger('change');
            $('select[name="kendaraan"]').val(row.kendaraan).trigger('change');
            $('input[name="tgl_masuk"]').val(row.tgl_masuk);
            $('input[name="ditanggung"]').val(row.ditanggung);
            $('input[name="hak_cuti"]').val(row.hak_cuti);
            $('input[name="cuti_lalu"]').val(row.cuti_lalu);
            $('input[name="jatuh_tempo"]').val(row.jatuh_tempo);
            $('input[name="panjar_cuti"]').val(row.panjar_cuti);
            $('textarea[name="keterangan"]').val(row.keterangan);
            $('input[name="id_pimpinan"]').val(row.id_pimpinan);
            $('select[name="pengikut1"]').val(pengikutValue).trigger('change');
            $('input[name="format_no_surat"]').val('').hide();

            // format tampilan
            let startDisplay = formatDateDMY(row.tgl_awal);
            let endDisplay = formatDateDMY(row.tgl_akhir);

            // isi input text
            $('input[name="tgl"]').val(startDisplay + ' - ' + endDisplay);

            // isi hidden input
            $('#tgl_awal').val(row.tgl_awal);
            $('#tgl_akhir').val(row.tgl_akhir);

            // set ke datepicker (WAJIB)
            $('.js-daterangepicker')
                .datepicker()
                .data('datepicker')
                .selectDate([
                    new Date(row.tgl_awal),
                    new Date(row.tgl_akhir)
                ]);

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-spd"),
                initialValue: row.id_pegawai
            });

            InitSelect2($("select[name='id_pimpinan']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-spd"),
                initialValue: row.id_pimpinan
            });

            InitSelect2($("select[name='id_kota1']"), {
                url: "{{ route('get-select-kota') }}",
                dropdownParent: $("#modal-spd"),
                initialValue: row.id_kota1
            });

            InitSelect2($("select[name='id_kota2']"), {
                url: "{{ route('get-select-kota') }}",
                dropdownParent: $("#modal-spd"),
                initialValue: row.id_kota2
            });

            // ==========================================
            // TAMBAHKAN: Load data pengikut dari server
            // ==========================================
            if (pengikutValue === '1') {
                $.ajax({
                    url: "{{ route('sdm.spd.get-pengikut', ':id') }}".replace(':id', row.id),
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        if (response.success && response.data.length > 0) {
                            // Loop data pengikut dan tambahkan ke tabel
                            response.data.forEach(function (pengikut) {
                                $table_employee.bootstrapTable('append', {
                                    id: pengikut.id,
                                    field_id: pengikut.id_pegawai,
                                    field_nip: pengikut.nip || '',
                                    field_employee: pengikut.nama_pegawai
                                });
                            });

                            // Tampilkan tabel
                            $('#hidden_div').removeClass('d-none').show();
                            $table_employee.bootstrapTable('resetView');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error loading pengikut:', xhr);
                    }
                });
            }
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('sdm.spd.delete', ':id') }}";
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
                            _token: "{{ csrf_token() }}",
                            no_surat: row.no_surat // menambah data
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $tableSpd.bootstrapTable('refresh');
                    });

                }
            })
        },
        'click .btn-tutup': function (e, value, row, index) {
            e.preventDefault();

            let url = "{{ route('sdm.spd.update-status', ':id') }}";
            url = url.replace(':id', row.id);

            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin Close data ini?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Close!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        status: 'Close',
                        table: 'tbl_spds',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        if (res.success) {
                            Alert('success', res.message);
                        } else {
                            Alert('warning', res.message);
                        }
                    },
                    error: function () {
                        Alert('error', 'Terjadi kesalahan server');
                    },
                    complete: function () {
                        $tableSpd.bootstrapTable('refresh');
                    }
                });
            });
        },
        'click .btn-draft': function (e, value, row, index) {
            e.preventDefault();

            let url = "{{ route('sdm.spd.update-status', ':id') }}";
            url = url.replace(':id', row.id);

            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin Draft data ini?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Draft!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        status: 'Draft',
                        table: 'tbl_spds',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        if (res.success) {
                            Alert('success', res.message);
                        } else {
                            Alert('warning', res.message);
                        }
                    },
                    error: function () {
                        Alert('error', 'Terjadi kesalahan server');
                    },
                    complete: function () {
                        $tableSpd.bootstrapTable('refresh');
                    }
                });
            });
        },
        'click .btn-print': function (e, value, row, index) {
            var url = "{{ route('sdm.spd.print', ':id') }}";
            url = url.replace(':id', row.id);
            window.open(url, '_blank');
        }
    }
</script>