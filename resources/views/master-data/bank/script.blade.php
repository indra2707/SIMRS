<script type="text/javascript">
    // Tabel
    var $tableBank = $('#table_bank');

    //selection
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-bank"),
        allowClear: true

    });

    // Open Modal Bank
    $(document).on('click', '.add-btn', function () {
        $('.form-bank').removeClass('was-validated');
        $('#modal-bank').modal('show');
        $('.modal-title').text('Form Tambah Bank');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="nama_bank"]').val('');
        $('input[name="singkatan"]').val('');
        $('select[name="jenis_bank"]').val('').trigger('change');
        $('input[name="status"]').prop('checked', true);
    });

    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.bank.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.bank.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-bank');
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
                    data: $('.form-bank').serialize(),
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
                            $('#modal-bank').modal('hide');
                            $tableBank.bootstrapTable('refresh');
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


    // Table Bank
    function initTable() {
        $tableBank.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            // showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            // showExport: true,
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
            url: "{{ route('master-data.bank.view') }}",
            columns: [
                [
                    {
                        sortable: true,
                        align: 'center',
                        width: 50,
                        formatter: function (value, row, index) {
                            return index + 1;
                        }
                    },
                    {
                        field: 'nama_bank',
                        sortable: true,
                    },
                    {
                        field: 'singkatan',
                        sortable: true,
                    },
                    {
                        field: 'jenis_bank',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'status',
                        sortable: true,
                        events: window.updateStatusBank,
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
                        events: window.eventsBank,
                        formatter: actionsFunctionBank
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionBank(value, row, index) {
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
    window.eventsBank = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-bank').modal('show');
            $('.modal-title').text('Form Edit Bank');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama_bank"]').val(row.nama_bank);
            $('input[name="singkatan"]').val(row.singkatan);
            $('select[name="jenis_bank"]').val(row.jenis_bank).trigger('change');
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('master-data.bank.delete', ':id') }}";
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
                        $tableBank.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status bank
    window.updateStatusBank = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('master-data.bank.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'tbl_lokasis',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tableBank.bootstrapTable('refresh');
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