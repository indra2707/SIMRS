<script type="text/javascript">
    // Variable Name
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-jadwal"),
        allowClear: true

    });


    $('#estimasi').on('keyup', function(e) {
        var mulai = $('#mulai').val();
        var akhir = $('#akhir').val();
        var estimasi = e.target.value;
        var menit = convertTimeToHours(mulai, akhir) / parseInt(estimasi);
        $('#kouta').val(Math.ceil(menit));
    })

    $('#mulai').on('keyup', function(e) {
        var mulai = e.target.value;
        var akhir = $('#akhir').val();
        var estimasi = $('#estimasi').val();
        var menit = convertTimeToHours(mulai, akhir) / parseInt(estimasi);
        $('#kouta').val(Math.ceil(menit));
    })

    $('#akhir').on('keyup', function(e) {
        var mulai = $('#mulai').val();
        var akhir = e.target.value;
        var estimasi = $('#estimasi').val();
        var menit = convertTimeToHours(mulai, akhir) / parseInt(estimasi);
        $('#kouta').val(Math.ceil(menit));
    })

    var $table = $('#table_coa');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-jadwal').removeClass('was-validated');
        $('#modal-jadwal').modal('show');
        $('.modal-title').text('Form Tambah Jadwal Dokter');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        clearFormInputFields('.form-jadwal');

        InitSelect2($("select[name='kode_dokter']"), {
            url: "{{ route('get-select-petugas') }}",
            dropdownParent: $("#modal-jadwal"),
            initialValue: ''
        });

        InitSelect2($("select[name='kode_poli']"), {
            url: "{{ route('get-select-poli') }}",
            dropdownParent: $("#modal-jadwal"),
            initialValue: ''
        });

    });

    // Save
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.jadwal-dokter.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.jadwal-dokter.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-jadwal');
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
                    data: $('.form-jadwal').serialize(),
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
                            $('#modal-jadwal').modal('hide');
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
                            $('#modal-jadwal').modal('hide');
                        }
                        form.classList.remove('was-validated');
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
            url: "{{ route('master-data.jadwal-dokter.view') }}",
            columns: [
                [{
                        width: '350%',
                        field: 'kode_poli',
                        sortable: true,
                        visible: false,
                    },
                    {
                        width: '350%',
                        field: 'nama_poli',
                        sortable: true,
                    },
                    {
                        width: '800%',
                        field: 'kode_dokter',
                        sortable: true,
                        visible: false,

                    },
                    {
                        // width: '450%',
                        field: 'nama_dokter',
                        sortable: true,

                    },
                    {
                        // width: '450%',
                        field: 'nama_spesialis',
                        sortable: true,

                    },
                    {
                        width: '200%',
                        field: 'hari',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'jam',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'kouta',
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
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-jadwal').modal('show');
            $('.modal-title').text('Form Edit coa');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('select[name="hari"]').val(row.hari).trigger('change');
            $('input[name="mulai"]').val(row.mulai);
            $('input[name="akhir"]').val(row.akhir);
            $('input[name="estimasi"]').val(row.estimasi);
            $('input[name="kouta"]').val(row.kouta);
            $('input[name="status"]').prop('checked', row.status === '1');

            InitSelect2($("select[name='kode_dokter']"), {
                url: "{{ route('get-select-petugas') }}",
                dropdownParent: $("#modal-jadwal"),
                initialValue: row.kode_dokter
            });

            InitSelect2($("select[name='kode_poli']"), {
                url: "{{ route('get-select-poli') }}",
                dropdownParent: $("#modal-jadwal"),
                initialValue: row.kode_poli
            });
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('master-data.jadwal-dokter.delete', ':id') }}";
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
                        }
                    }).done(function() {
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('master-data.jadwal-dokter.update-status', ':id') }}";
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
                    $table.bootstrapTable('refresh');
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
