<script type="text/javascript">
    // Variable Name
    var $table = $('#table_icd9');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-sk-tarif').removeClass('was-validated');
        $('#modal-sk-tarif').modal('show');
        $('.modal-title').text('Form Tambah SK Tarif');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="no_sk"]').val('');
        $('input[name="tgl_mulai"]').val('');
        $('input[name="tgl_akhir"]').val('');
        $('textarea[name="deskripsi"]').val('');
    });

    // Save
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('tarif.sk-tarif.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('tarif.sk-tarif.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-sk-tarif');
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
                    data: $('.form-sk-tarif').serialize(),
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
                            $table.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                        }
                        $('#modal-sk-tarif').modal('hide');
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
            buttonClass: 'btn btn-primary',
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            // ajax: {
            //     type: "GET",
            //     url: "{{ route('tarif.sk-tarif.view') }}",
            //     dataType: "json",
            //     beforeSend: function() {
            //         $table.bootstrapTable('loading');
            //     },
            //     complete: function() {
            //         $table.bootstrapTable('resetView');
            //     },
            //     success: function(res, status, xhr) {
            //         if (xhr.status == 200 && res.success == true) {
            //             $table.bootstrapTable('load', res.data);
            //         }
            //     }}
            // },
            url: "{{ route('tarif.sk-tarif.view') }}",
            columns: [
                [{
                        title: 'No.',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        width: '5%',
                        formatter: function(value, row, index) {
                            return index + 1
                        }
                    },
                    {
                        width: 10,
                        field: 'no_sk',
                        sortable: true,
                    },
                    {
                        field: 'tgl_mulai',
                        sortable: true,
                    },
                    {
                        field: 'tgl_akhir',
                        sortable: true,
                    },
                    {
                        field: 'deskripsi',
                        sortable: true,
                    },
                    {
                        width: '5%',
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
            ]
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
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-sk-tarif').modal('show');
            $('.modal-title').text('Form Edit SK Tarif');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="no_sk"]').val(row.no_sk);
            $('input[name="tgl_mulai"]').val(row.tgl_mulai);
            $('input[name="tgl_akhir"]').val(row.tgl_akhir);
            $('textarea[name="deskripsi"]').val(row.deskripsi);
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('tarif.sk-tarif.delete', ':id') }}";
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
                                $table.bootstrapTable('refresh');
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

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('tarif.sk-tarif.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'sk_tarifs',
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
