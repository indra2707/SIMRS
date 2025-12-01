<script type="text/javascript">
    // Variable Name
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-kalibrasi"),
        allowClear: true
    });

    var $table = $('#table_kalibrasi');

    // Open Modal
    $(document).on('click', '.add-btn', function () {
        $('.form-kalibrasi').removeClass('was-validated');
        $('#modal-kalibrasi').modal('show');
        $('.modal-title').text('Form Tambah Kalibrasi');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="tgl_kalibrasi"]').val('');
        $("select[name='kode_aset']").val('').trigger('change');
        $('input[name="status"]').prop('checked', false);
        $('input[name="aktif"]').prop('checked', true);
        $('input[name="no_sn"]').val('');
        $('input[name="nama_aset"]').val('');
        $('input[name="lokasi_name"]').val('');

        InitSelect2($("select[name='kode_aset']"), {
            url: "{{ route('get-select-aset') }}",
            dropdownParent: $("#modal-kalibrasi")
        });

    });

    // ketika user memilih aset
    $('#kode_aset').on('select2:select', function (e) {
        var selected = e.params.data; // ini berisi objek yang dikembalikan di processResults
        var asetId = selected.id;

        // Cara 1: gunakan data yang sudah ada dari results (jika kamu menyertakan nama)
        if (selected.nama !== undefined) {
            $('#nama_aset').val(selected.nama  || '');
            $('#no_sn').val(selected.no_sn  || '');
            $('#lokasi_name').val(selected.lokasi_name  || '');
            return;
        }

        // Cara 2: fallback -> ambil detail via AJAX
        var url = "{{ route('master-data.mutasi.detail', ':id') }}".replace(':id', asetId);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (res) {
                $('#nama_aset').val(res.nama || '');
                $('#no_sn').val(res.no_sn || '');
                $('#lokasi_name').val(res.lokasi_name || '');
            },
            error: function (err) {
                console.error(err);
                $('#nama_aset').val('');
                $('#no_sn').val('');
                $('#lokasi_name').val('');
            }
        });
    });

    // Save
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.kalibrasi.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.kalibrasi.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-kalibrasi');
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
                    data: $('.form-kalibrasi').serialize(),
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
                            $('#modal-kalibrasi').modal('hide');
                            $table.bootstrapTable('refresh');
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
                            $('#modal-kalibrasi').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function (xhr, status, error) {
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
    $(function () {
        initTable();
    });

    // ---------------------------------------------------------------------------------------------
    // init table
    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: false,
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
            url: "{{ route('master-data.kalibrasi.view') }}",
            columns: [
                [
                    {
                        width: '10%',
                        field: 'id',
                        sortable: true,
                        visible: false,
                    },
                    // {
                    //     width: '200%',
                    //     field: 'id_aset',
                    //     sortable: true,
                    // },
                    {
                        width: '350%',
                        field: 'no_aset',
                        sortable: true,
                    },
                    {
                        width: '350%',
                        field: 'nama_aset',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'merek',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'no_sn',
                        sortable: true,
                    },
                    {
                        width: '800%',
                        field: 'nama_lokasi',
                        sortable: true,
                        // visible: false,
                    },
                    {
                        width: '200%',
                        field: 'status',
                        sortable: true,
                        // visible: false,
                    },
                    {
                        width: '150%',
                        field: 'tgl_kalibrasi',
                        sortable: true,
                        // visible: false,
                    },
                    {
                        width: '200%',
                        align: 'center',
                        valign: 'middle',
                        formatter: function (value, row, index) {
                            let buttons = '';  // Tombol yang akan ditampilkan

                            // Kondisi untuk menampilkan tombol berdasarkan 'selisih_hari'
                            if (row.selisih_hari >= 120) {
                                buttons += `
                                    <button class="btn btn-pill btn-xs btn-success action-btn">${row.selisih_hari} Hari</button>
                                `;
                            } else if (row.selisih_hari >= 90) {
                                buttons += `
                                    <button class="btn btn-pill btn-xs btn-warning action-btn">${row.selisih_hari} Hari</button>
                                `;
                            } else {
                                buttons += `
                                    <button class="btn btn-pill btn-xs btn-danger action-btn">${row.selisih_hari} Hari</button>
                                `;
                            }

                            return buttons;
                        }
                    },
                    {
                        width: '100%',
                        field: 'aktif',
                        sortable: true,
                        events: window.updateStatusKalibrasi,
                        formatter: function (value, row, index) {
                            return [
                                '<div class="media-body text-center switch-sm icon-state">',
                                '<label class="switch">',
                                '<input type="checkbox" class="update-status" ' + (row.aktif ===
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

    function actionsFunction(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-kalibrasi').modal('show');
            $('.modal-title').text('Form Edit Kalibrasi');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="tgl_kalibrasi"]').val(row.tgl_kalibrasi);
            $('input[name="no_sn"]').val(row.no_sn);
            $('input[name="nama_aset"]').val(row.nama_aset);
            $('input[name="lokasi_name"]').val(row.nama_lokasi);

            if (row.status === 'Laik') {
                $("#Laik").prop("checked", true);
            } else {
                $("#Tidak Laik").prop("checked", true);
            }

            // Init Select2 aset
            let selectAset = $("select[name='kode_aset']");
            InitSelect2(selectAset, {
                url: "{{ route('get-select-aset') }}",
                dropdownParent: $("#modal-kalibrasi")
            });

            // Set nilai awal aset
            let asetText = `${row.no_aset} - ${row.nama_aset}`;
            let optAset = new Option(asetText, row.id_aset, true, true);
            selectAset.append(optAset).trigger("change");

        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('master-data.kalibrasi.delete', ':id') }}";
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
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }


    // Window operateChange Status kalibrasi
    window.updateStatusKalibrasi = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('master-data.kalibrasi.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    aktif: e.target.checked ? 1 : 0,
                    table: 'tbl_kalibrasi',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $table.bootstrapTable('refresh');
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