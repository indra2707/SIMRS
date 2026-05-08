<script type="text/javascript">
    // Tabel
    var $table = $('#table_tt');

    // filter status
    $(".select3").select2({
        placeholder: "--- Pilih Salah Satu ---",
        theme: "bootstrap-5",
        allowClear: true,
        width: "100%"
    });

    // Open Modal
    $(document).on('click', '.add-btn', function () {
        $('.form-tt').removeClass('was-validated');
        $('#modal-tt').modal('show');
        $('.modal-title').text('Form Tambah Tempat Tidur');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
    });

    // Save Tempat Tidur
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id_t_tt"]').val();
        var url, type;
        if (id) {
            url = "{{ route('rs-online.tempat-tidur.update', ':id') }}";
            url = url.replace(':id', id);
            type = "POST";
        } else {
            url = "{{ route('rs-online.tempat-tidur.create') }}";
            type = "POST";
        }
        var forms = document.getElementsByClassName('form-tt');
        Array.prototype.filter.call(forms, function (form) {

            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {

                var formData = new FormData(form);

                // method spoofing untuk update
                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",

                    beforeSend: function () {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-tt').modal('hide');
                            $table.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-warning',
                                title: 'Warning',
                                message: res.message
                            }, {
                                type: 'warning'
                            });

                            form.classList.remove('was-validated');
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


    // Table perizinan
    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
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
            exportTypes: ['excel', 'pdf'],
            url: "{{ route('rs-online.tempat-tidur.view') }}",
            method: 'GET',
            columns: [
                {
                    field: "id",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    field: 'id_tt',
                    sortable: true,
                    align: 'center',
                    visible: false
                },
                {
                    field: 'tt',
                    sortable: true,
                },
                {
                    field: 'ruang',
                    sortable: true,
                },
                {
                    field: 'kode_siranap',
                    align: 'center',
                    sortable: true,
                    visible: false
                },
                {
                    field: 'jumlah',
                    sortable: true
                },
                {
                    field: 'kosong',
                    align: 'center',
                    sortable: true,
                    visible: false
                },
                {
                    field: 'terpakai',
                    align: 'center',
                    sortable: true
                },
                {
                    field: 'id_t_tt',
                    sortable: true,
                    visible: false
                },
                {
                    field: "action",
                    align: "center",
                    formatter: actionsFunction,
                    events: window.operateEvents,
                },

            ],

            responseHandler: function (res) {

                console.log('RESPONSE : ', res);

                return res.rows;
            },

            onLoadSuccess: function (data) {
                console.log('DATA TABLE : ', data);
            },

            onLoadError: function (status, res) {
                console.log(status);
                console.log(res);
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
            $('#modal-tt').modal('show');
            $('.modal-title').text('Form Edit Tempat Tidur');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id_tt"]').val(row.id_tt);
            $('input[name="id_t_tt"]').val(row.id_t_tt);
            $('input[name="tt"]').val(row.tt);
            $('input[name="ruang"]').val(row.ruang);
            $('input[name="kode_siranap"]').val(row.kode_siranap);
            $('input[name="jumlah"]').val(row.jumlah);
            $('input[name="kosong"]').val(row.kosong);
            $('input[name="terpakai"]').val(row.terpakai);
        },
        'click .btn-delete': function (e, value, row, index) {
            let url = "{{ route('rs-online.tempat-tidur.delete', ':id') }}";
            url = url.replace(':id', row.id_t_tt);
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin menghapus data ini?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'JSON',

                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Sedang menghapus data',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function (res, status, xhr) {
                            Swal.close();
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                                $table.bootstrapTable('refresh');
                            } else {
                                Alert('warning', res.message);
                            }
                        },

                        error: function (xhr) {
                            Swal.close();
                            let message = 'Gagal menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Alert('error', message);
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        }
    }
</script>