<script type="text/javascript">
    // Variable Name
    var $tableEmerald = $("#table_emerald");

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-emerald"),
        allowClear: true
    });

    // Add Button
    $(document).on("click", ".add-btn", function () {
        $(".form-emerald").removeClass("was-validated");
        $("#modal-emerald").modal("show");
        $(".modal-title").text("Form Tambah Emerald");
        $('.save-btn').show();
        $(".save-btn").html('<span class="fa fa-check"></span> Simpan').removeAttr("disabled");
        $('input[name="id"]').val("");
        $('input[name="userid"]').val("");
        $('input[name="name"]').val("");
        $('input[name="card_number"]').val("");
        $('select[name="role"]').val('0').trigger('change');
    });

    // sinkronisasi
    $(document).ready(function () {
        $('.sinkronisasi-btn').on('click', function (e) {
            e.preventDefault();
            let button = $(this);
            button.prop('disabled', true);
            button.html('<span class="fa fa-spinner fa-spin"></span> Sinkronisasi...');
            $.ajax({
                url: "{{ route('pintu.emerald.sync') }}",
                type: "GET",
                dataType: "json",
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.status) {
                        Alert('success', res.message +
                            ' (Total User: ' + res.total_user + ')');
                        // kalau pakai bootstrap table
                        if (typeof $tableEmerald !== 'undefined') {
                            $tableEmerald.bootstrapTable('refresh');
                        }
                    } else {
                        $.notify({
                            icon: 'fa fa-warning',
                            title: 'Warning',
                            message: res.message
                        }, { type: 'warning' });
                    }
                },

                error: function (xhr) {
                    let message = 'Terjadi kesalahan saat sinkronisasi';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    $.notify({
                        icon: 'fa fa-warning',
                        title: 'Error',
                        message: message
                    }, { type: 'danger' });
                },
                complete: function () {
                    button.prop('disabled', false);
                    button.html('<span class="fa fa-spinner"></span> Sinkronisasi');
                }
            });
        });
    });


    // Save emerald
    $(document).on('click', '.save-btn', function (event) {
        event.preventDefault();
        let form = document.querySelector('.form-emerald');
        let id = $('input[name="id"]').val();
        let url, type;

        if (id) {
            url = "{{ route('pintu.emerald.update', ':id') }}".replace(':id', id);
            type = "PUT";
        } else {
            url = "{{ route('pintu.emerald.create') }}";
            type = "POST";
        }

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            form.querySelector(".form-control:invalid").focus();
            return;
        }
        $.ajax({
            type: type,
            url: url,
            data: $('.form-emerald').serialize(),
            dataType: "json",

            beforeSend: function () {
                $('.save-btn')
                    .html('<span class="spinner-border spinner-border-sm"></span>')
                    .prop('disabled', true);
            },
            success: function (res) {
                if (res.status === true) {
                    Alert('success', res.message);
                    $('#modal-emerald').modal('hide');
                    form.reset();
                    form.classList.remove('was-validated');
                    $tableEmerald.bootstrapTable('refresh');

                } else {
                    $.notify({
                        icon: 'fa fa-warning',
                        title: 'Warning',
                        message: res.message
                    }, {
                        type: 'warning',
                        delay: 2000
                    });

                }
            },
            error: function (xhr) {
                let message = "Terjadi kesalahan server";
                if (xhr.status === 422) {
                    message = "Validasi gagal. Periksa input.";
                }
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                $.notify({
                    icon: 'fa fa-times',
                    title: 'Error',
                    message: message
                }, {
                    type: 'danger'
                });
            },

            complete: function () {
                $('.save-btn').html('<span class="fa fa-check"></span> Simpan').prop('disabled', false);
            }
        });
    });


    //Open Pintu
    $('.open-btn').click(function (e) {
        e.preventDefault();

        let button = $(this);

        button.prop('disabled', true);
        button.html('<span class="fa fa-spinner fa-spin"></span> Membuka...');

        $.ajax({
            url: "{{ route('pintu.emerald.open-door') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        })
            .done(function (response) {

                Alert('success', response.message);

            })
            .fail(function (xhr) {
                let errorMessage = "Gagal membuka pintu!";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $.notify({
                    icon: 'fa fa-times',
                    title: 'Error',
                    message: errorMessage
                }, {
                    type: 'danger'
                });

            })
            .always(function () {
                button.prop('disabled', false);
                button.html('<span class="fa fa-key"></span> Buka Pintu');

            });
    });


    // Page Load Event
    $(function () {
        initTable();
    });


    // init table
    function initTable() {
        $tableEmerald.bootstrapTable("destroy").bootstrapTable({
            height: 500,
            locale: "en-US",
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            showExport: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, "all"],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ["excel", "pdf"],
            url: "{{ route('pintu.emerald.view') }}",
            columns: [{
                sortable: true,
                align: "center",
                formatter: function (value, row, index) {
                    return index + 1;
                },
            },
            {
                field: "uid",
                sortable: true,
            },
            {
                field: "userid",
                sortable: true,
            },
            {
                field: "name",
                sortable: true,
            },
            {
                field: "card_number",
                sortable: true,
            },
            {
                field: "role",
                sortable: true,
                align: "center",
                formatter: function (value) {
                    if (value == 14) {
                        return '<span class="badge bg-danger">Admin</span>';
                    } else if (value == 0) {
                        return '<span class="badge bg-primary">User</span>';
                    } else {
                        return '<span class="badge bg-secondary">Unknown</span>';
                    }
                }
            },
            {
                field: "action",
                title: "Aksi",
                align: "center",
                formatter: actionsFunction,
                events: window.operateEvents,
            },
            ],
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
            "</button>",
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            "</div>",
            "</div>",
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-emerald').modal('show');
            $('.modal-title').text('Form Edit Emerald');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="userid"]').val(row.userid);
            $('input[name="name"]').val(row.name);
            $('input[name="card_number"]').val(row.card_number);
            $('select[name="role"]').val(row.role).trigger('change');
        },
        "click .btn-delete": function (e, value, row, index) {
            let url = "{{ route('pintu.emerald.delete', ':id') }}";
            url = url.replace(":id", row.id);
            Swal.fire({
                icon: "warning",
                title: "Peringatan",
                text: "Anda yakin ingin menghapus data ini?",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",

                        beforeSend: function () {
                            Swal.showLoading();
                        },
                        success: function (res) {
                            if (res.status === true) {
                                Alert("success", res.message);
                                $tableEmerald.bootstrapTable("refresh");
                            } else {
                                Alert("warning", res.message);
                            }
                        },
                        error: function (xhr) {
                            let message = "Terjadi kesalahan server";
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Alert("error", message);
                        },
                        complete: function () {
                            Swal.close();
                        }
                    });
                }
            });
        }
    };

</script>