<script type="text/javascript">
    // Variable Name
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-tarif-tindakan"),
        allowClear: true

    });

    var $table = $('#table_tindakan_tarif');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-tarif-tindakan').removeClass('was-validated');
        $('#modal-tarif-tindakan').modal('show');
        $('.modal-title').text('Form Tambah Tarif Tindakan');
        $('input[name="id"]').val('');
        $('input[name="tindakan"]').val('');
        $('input[name="cito"]').val('30');
        $('select[name="coa_ri"]').val('').trigger('change');
        $('select[name="coa_rj"]').val('').trigger('change');
        $('select[name="reduksi_ri"]').val('').trigger('change');
        $('select[name="reduksi_rj"]').val('').trigger('change');
        $('select[name="onsite"]').val('').trigger('change');
        $('select[name="insite"]').val('').trigger('change');
        $('select[name="kategori"]').val('').trigger('change');

        // var url = "{{ route('generate-kode-tarif-tindakan', ':id') }}";
        // url = url.replace(':id', 1);
        // $.get(url,
        //     function(data, textStatus, jqXHR) {
        //         $('input[name="kode"]').val(data['data']).attr('readonly', true);
        //     },
        //     "JSON"
        // );
    });

    // Open Modal Harga Tindakan
    $(document).on('click', '.add-btn-harga', function() {
        $('#modal-harga-tindakan').modal('hide');
        $('.form-harga-detail').removeClass('was-validated');
        $('#modal-harga-detail').modal('show');
        $('.modal-title').text('Form Tambah Harga Tindakan');
        $('input[name="id"]').val('');
        $('select[name="tarif"]').val('').trigger('change');
    });

    // Save
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
                            $('#modal-tarif-tindakan').modal('hide');
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
                            $('#modal-tarif-tindakan').modal('hide');
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
            '<a class="dropdown-item btn-info" href="javascript:void(0)"><i class="fa fa-list text-info"></i> Info</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
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
        },
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-tarif-tindakan').modal('show');
            $('.modal-title').text('Form Edit Tarif Tindakan');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode_tarif"]').val(row.kode_tarif);
            $('input[name="tindakan"]').val(row.tindakan);
            $('input[name="cito"]').val(row.cito);
            $('input[name="status"]').prop('checked', row.status === '1');
            $('select[name="kategori"]').val(row.kategori).trigger('change');
            $('select[name="coa_pendapatan_rj"]').val(row.coa_pendapatan_rj).trigger('change');
            $('select[name="coa_pendapatan_ri"]').val(row.coa_pendapatan_ri).trigger('change');
            $('select[name="coa_reduksi_rj"]').val(row.coa_reduksi_rj).trigger('change');
            $('select[name="coa_reduksi_ri"]').val(row.coa_reduksi_ri).trigger('change');
            $('select[name="coa_mcu_onsite"]').val(row.coa_mcu_onsite).trigger('change');
            $('select[name="coa_mcu_insite"]').val(row.coa_mcu_insite).trigger('change');
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
