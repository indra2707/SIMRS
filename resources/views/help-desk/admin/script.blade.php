<script type="text/javascript">
    // Variable Name
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-helpdesk"),
        allowClear: true

    });
    var $table = $('#table_helpdesk');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-helpdesk').removeClass('was-validated');
        $('#modal-helpdesk').modal('show');
        $('.modal-title').text('Form Tambah helpdesk');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="keterangan"]').val('');

    });

    // Save
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('admin.helpdesk-update', ':id') }}";
            url = url.replace(':id', id);
        }

        var forms = document.getElementsByClassName('form-helpdesk');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: $('.form-helpdesk').serialize(),
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
                        $('#modal-helpdesk').modal('hide');
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
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('admin.helpdesk-views') }}",
            uniqueId: "id",
            columns: [{
                    field: 'id',
                    title: 'ID',
                    sortable: true,
                    align: 'center',
                    formatter: function(value, row, index) {
                        return index + 1;
                    }
                },
                {
                    field: 'keterangan',
                    title: 'Description',
                    sortable: true
                },
                {
                    field: 'nama_lengkap',
                    title: 'Nama Pelapor',
                    sortable: true,
                    align: 'center',

                },
                {
                    field: 'department',
                    title: 'Department',
                    sortable: true,
                    align: 'center',

                },

                {
                    field: 'tanggal',
                    title: 'Date',
                    sortable: true,
                    align: 'center',
                    formatter: function(value, row) {
                        if (!value) return '-';
                        const date = new Date(value);
                        return date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long', // menampilkan bulan penuh
                            year: 'numeric'
                        });
                    }
                },

                {
                    field: 'created_at',
                    title: 'Time',
                    sortable: true,
                    align: 'center',
                    formatter: function(value, row) {
                        if (!row.created_at) return '-';
                        const date = new Date(row.created_at);
                        return date.toLocaleString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    },
                    events: window.operateChange
                },
                {
                    field: 'status',
                    title: 'Status',
                    sortable: true,
                    align: 'center',
                    formatter: function(value, row) {
                        let badgeClass = '';
                        switch (value) {
                            case 'accept':
                                badgeClass = 'badge rounded-pill bg-primary fs-6';
                                break;
                            case 'on-progress':
                                badgeClass = 'badge rounded-pill bg-warning fs-6';
                                break;
                            case 'done':
                                badgeClass = 'badge rounded-pill bg-success fs-6';
                                break;
                            default:
                                badgeClass = 'badge rounded-pill bg-light';
                        }
                        return `<span class="${badgeClass} update-status" style="cursor:pointer;" data-id="${row.id}">${value}</span>`;
                    },
                    events: window.operateChange // <-- ini wajib supaya klik bisa dideteksi
                },

                {
                    field: 'action',
                    title: 'Aksi',
                    align: 'center',
                    formatter: actionsFunction,
                    events: window.operateEvents
                }
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
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
            `<a class="dropdown-item btn-chat" href="javascript:void(0)" data-helpdesk-id="${row.id}"><i class="fa fa-comment text-primary"></i> Chat</a>`,
            // '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }


    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-helpdesk').modal('show');
            $('.modal-title').text('Form Edit helpdesk');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
        },



        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('admin.helpdesk-destroy', ':id') }}";
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
                                Alert('warnig', res.message);
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
            var id = $(this).data('id');

            var url = "{{ route('admin.helpdesk-update-status', ':id') }}";
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
                        Alert('warnig', res.message);
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
    window.addEventListener('refresh-admin-table', function() {
        $('#table_helpdesk').bootstrapTable('refresh');
    });

    $(document).on('click', '.btn-chat', function() {
        var helpdeskId = $(this).data('helpdesk-id');
        if (!helpdeskId) return;

        // loadChat(helpdeskId); // COMMENT dulu sementara
        $('#chatModal').modal('show');
    });
</script>
// Echo listener
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.1/echo.iife.js"></script>

<script>
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'local', // sesuaikan dengan PUSHER_APP_KEY
        wsHost: 'simrs.local', // domain laragon custom
        wsPort: 6001, // sesuai config
        forceTLS: false,
        encrypted: false,
        disableStats: true
    });

    window.Echo.channel('helpdesk-admin')
        .listen('HelpdeskCreated', (e) => {
            console.log('Helpdesk baru:', e);

            // refresh bootstrapTable
            $('#table_helpdesk').bootstrapTable('refresh');

            // Tampilkan notifikasi popup
            $.notify({
                message: `
                <div class="d-flex align-items-start">
                    <i class="fa fa-bell text-primary me-2 fs-5"></i>
                    <div>
                        <strong>Helpdesk Baru!</strong><br>
                        Dari: <b>${e.nama_lengkap}</b><br>
                        Department: <b>${e.department}</b>
                    </div>
                </div>
            `
            }, {
                type: 'primary', // info, success, warning, danger
                allow_dismiss: true,
                delay: 4000,
                showProgressbar: true,
                timer: 300,
                z_index: 1127,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });

        });
</script>
