<script type="text/javascript">
    // Variable Name
    var $table = $('#table_icd9');

    // Open Modal
    $(document).on('click', '.add-btn', function () {
        $('#form-modal').modal('show');
        $('.modal-title').text('Form Tambah ICD-9');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="kode"]').val('');
        $('input[name="nama"]').val('');
        $('input[name="kelas"]').val('');
        $('input[name="status"]').prop('checked', true);
    });

    // Save
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('master-data.icd-9.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.icd-9.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-icd9');
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                form.classList.remove('was-validated');
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-icd9').serialize(),
                    beforeSend: function () {
                        $('.save-btn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').attr('disabled', 'disabled');
                    },
                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
                    },
                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#form-modal').modal('hide');
                            $table.bootstrapTable('refresh');
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning',
                                text: res.message,
                            });
                            $('#form-modal').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status == 400) {
                            var errors = xhr.responseJSON.errors;
                            Swal.fire({
                                icon: 'error',
                                title: error,
                                text: xhr.responseJSON.message,
                            });
                        } else if (xhr.status == 500) {
                            Swal.fire({
                                icon: 'error',
                                title: error,
                                text: "Silahkan hubungi administrator!",
                            });
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

    // ---------------------------------------------------------------------------------------------
    // init table
    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            height: 495,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: true,
            showExport: true,
            showPaginationSwitch: true,
            pagination: true,
            pageSize: 10,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            showExport: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            // exportOptions: {
            //     fileName: 'ICD-9 (<?= date('d-m-Y') ?>)',
            // },
            url: "{{ route('master-data.icd-9.view') }}",
            columns: [
                [{
                    title: 'No.',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    width: '5%',
                    formatter: function (value, row, index) {
                        return index + 1
                    }
                },
                {
                    title: 'Kode',
                    field: 'kode',
                    sortable: true,
                },
                {
                    title: 'Nama',
                    field: 'nama'
                },
                {
                    title: 'Kelas',
                    field: 'kelas'
                },
                {
                    title: 'Status',
                    field: 'status',
                    align: 'center',
                    events: window.operateChange,
                    formatter: function (value, row, index) {
                        return [
                            '<div class="media-body text-end">',
                            '<label class="switch">',
                            '<input type="checkbox" class="update-status" ' + (row.status === '1' ? 'checked' : '') + '>',
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
                    clickToSelect: false,
                    events: window.operateEvents,
                    formatter: actionsFunction
                }
                ]
            ],
            error: function (xhr, status, error) {
                if (xhr.status == 400) {
                    var errors = xhr.responseJSON.errors;
                    Swal.fire({
                        icon: 'error',
                        title: error,
                        text: xhr.responseJSON.message,
                    });
                } else if (xhr.status == 500) {
                    Swal.fire({
                        icon: 'error',
                        title: error,
                        text: "Silahkan hubungi administrator!",
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
            // '<a class="dropdown-item btn-info" href="javascript:void(0)"><i class="fa fa-list text-info"></i> Info</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function (e, value, row, index) {
            $('#form-modal').modal('show');
            $('.modal-title').text('Form Edit ICD-9');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
            $('input[name="kelas"]').val(row.kelas);
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('master-data.icd-9.delete', ':id') }}";
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Warning',
                                    text: res.message,
                                })
                            }
                        }
                    }).done(function () {
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('master-data.icd-9.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            showConfirmButton: true,
                            timer: 2000
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: res.message,
                        })
                    }
                    $table.bootstrapTable('refresh');
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 400) {
                        var errors = xhr.responseJSON.errors;
                        Swal.fire({
                            icon: 'error',
                            title: error,
                            text: xhr.responseJSON.message,
                        });
                    } else if (xhr.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: error,
                            text: "Silahkan hubungi administrator!",
                        });
                    }
                }
            });
        }
    }
</script>
