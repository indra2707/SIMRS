<script type="text/javascript">
    // Variable Name
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        minimumInputLength: 1,
        theme: "bootstrap-5",
        dropdownParent: $("#modal-kalibrasi"),
        allowClear: true
    });

    var $table = $('#table_mutasi');

    // Open Modal
    $(document).on('click', '.add-btn', function () {
        $('.form-mutasi').removeClass('was-validated');
        $('#modal-mutasi').modal('show');
        $('.modal-title').text('Form Tambah Mutasi');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="tgl_mutasi"]').val('');
        $('textarea[name="keterangan"]').val('');
        $('#lokasi_lama').val('');
        $('#id_lokasi_lama').val('');
        $("select[name='kode_lokasi']").val('').trigger('change');
        $("select[name='kode_kondisi_aset']").val('').trigger('change');
        $("select[name='kode_aset']").val('').trigger('change');


        // InitSelect2($("select[name='kode_aset']"), {
        //     url: "{{ route('get-select-aset') }}",
        //     dropdownParent: $("#modal-mutasi")
        // });

        InitSelect2($("select[name='kode_kondisi_aset']"), {
            url: "{{ route('get-select-kondisi-aset') }}",
            dropdownParent: $("#modal-mutasi")
        });

        InitSelect2($("select[name='kode_lokasi']"), {
            url: "{{ route('get-select-lokasi') }}",
            dropdownParent: $("#modal-mutasi")
        });

    });


    $(document).ready(function () {
        $('#kode_aset').select2({
            minimumInputLength: 1,
            theme: "bootstrap-5",
            dropdownParent: $("#modal-kalibrasi"),
            allowClear: true,
            dropdownParent: $("#modal-mutasi"), // hapus atau sesuaikan jika tidak di modal
            ajax: {
                url: "{{ route('get-select-aset') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { search: params.term };
                },
                processResults: function (data) {
                    // data.data adalah koleksi objek {id, text, lokasi_name, id_lokasi, ...}
                    return { results: data.data };
                },
                cache: true
            },
            minimumInputLength: 0
        });

        // ketika user memilih aset
        $('#kode_aset').on('select2:select', function (e) {
            var selected = e.params.data; // ini berisi objek yang dikembalikan di processResults
            var asetId = selected.id;

            // Cara 1: gunakan data yang sudah ada dari results (jika kamu menyertakan lokasi_name)
            if (selected.lokasi_name !== undefined) {
                $('#lokasi_lama').val(selected.lokasi_name || selected.id_lokasi || '');
                $('#id_lokasi_lama').val(selected.id_lokasi || '');
                return;
            }

            // Cara 2: fallback -> ambil detail via AJAX
            var url = "{{ route('master-data.mutasi.detail', ':id') }}".replace(':id', asetId);
            $.ajax({
                url: url,
                type: 'GET',
                success: function (res) {
                    $('#lokasi_lama').val(res.id_lokasi || '');
                },
                error: function (err) {
                    console.error(err);
                    $('#lokasi_lama').val('');
                }
            });
        });
    });


    // Save
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        var kode_aset = $('#kode_aset').val();
        var kode_kondisi_aset = $('#kode_kondisi_aset').val();
        var kode_lokasi = $('#kode_lokasi').val();

        console.log(kode_aset, kode_kondisi_aset, kode_lokasi);

        if (id) {
            var url = "{{ route('master-data.mutasi.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.mutasi.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-mutasi');
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
                    data: $('.form-mutasi').serializeArray().concat({ name: "kode_aset", value: kode_aset }, { name: "kode_kondisi_aset", value: kode_kondisi_aset }, { name: "kode_lokasi", value: kode_lokasi }),
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
                            $('#modal-mutasi').modal('hide');
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
                            $('#modal-mutasi').modal('hide');
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
            url: "{{ route('master-data.mutasi.view') }}",
            columns: [
                [
                    {
                        width: '10%',
                        field: 'id',
                        sortable: true,
                        visible: false,
                    },
                    {
                        width: '100%',
                        field: 'tgl_mutasi',
                        sortable: true,
                    },
                    {
                        width: '350%',
                        field: 'id_aset',
                        sortable: true,
                        visible: false,
                    },
                    {
                        width: '350%',
                        field: 'nama_aset',
                        sortable: true,
                    },
                    {
                        width: '350%',
                        field: 'no_sn',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'id_lokasi',
                        sortable: true,
                        visible: false,
                    },
                    {
                        width: '250%',
                        field: 'nama_lokasi_asal',
                        sortable: true,
                    },
                    {
                        width: '250%',
                        field: 'id_lokasi_new',
                        sortable: true,
                        visible: false,
                    },
                    {
                        width: '250%',
                        field: 'nama_lokasi_tujuan',
                        sortable: true,
                    },
                    {
                        width: '300%',
                        field: 'id_kondisi',
                        sortable: true,
                        visible: false,
                    },
                    {
                        width: '800%',
                        field: 'nama_kondisi',
                        sortable: true,
                        // visible: false,
                    },
                    {
                        width: '200%',
                        field: 'keterangan',
                        sortable: true,
                        // visible: false,
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
            $('#modal-mutasi').modal('show');
            $('.modal-title').text('Form Edit Mutasi');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="tgl_mutasi"]').val(row.tgl_mutasi);
            $('textarea[name="keterangan"]').val(row.keterangan);
            $('#lokasi_lama').val(row.nama_lokasi_asal);
            $('#id_lokasi_lama').val(row.id_lokasi);  

            InitSelect2($("select[name='kode_aset']"), {
                url: "{{ route('get-select-aset') }}",
                dropdownParent: $("#modal-mutasi"),
                initialValue: row.id_aset
            });

            InitSelect2($("select[name='kode_kondisi_aset']"), {
                url: "{{ route('get-select-kondisi-aset') }}",
                dropdownParent: $("#modal-mutasi"),
                initialValue: row.id_kondisi
            });

            InitSelect2($("select[name='kode_lokasi']"), {
                url: "{{ route('get-select-lokasi') }}",
                dropdownParent: $("#modal-mutasi"),
                initialValue: row.id_lokasi_new
            });

        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('master-data.mutasi.delete', ':id') }}";
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

</script>