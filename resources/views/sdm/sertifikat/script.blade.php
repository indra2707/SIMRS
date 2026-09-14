<script type="text/javascript">
    // Variable Name
    var $tableSertifikat = $('#table_sertifikat');

    // With Placeholder
    $(".select2").each(function () {
        $(this).select2({
            placeholder: "---- Pilih Salah Satu ----",
            theme: "bootstrap-5",
            dropdownParent: $(this).closest(".modal"),
            allowClear: true
        });
    });


    $(function () {
        initTableSertifikat();
    });

    // Tahun Sertifikat
    $('#tahun_sertifikat').datepicker({
        language: 'en',
        view: 'years',
        minView: 'years',
        dateFormat: 'yyyy',
        autoClose: true
    });

    // Sertifikat
    $('#btn-attach-sertifikat').on('click', function () {
        $('#lampiran-sertifikat').trigger('click');
    });

    //upload dokumen Sertifikat
    let fileBufferSertifikat = new DataTransfer();
    $(document).on('change', '#lampiran-sertifikat', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferSertifikat = new DataTransfer();
        fileBufferSertifikat.items.add(file);
        input.files = fileBufferSertifikat.files;

        $('#preview-images-sertifikat').empty();
        renderPreviewPDFSertifikat(file, '#preview-images-sertifikat');
    });

    // // perview File Pdf Sertifikat
    function renderPreviewPDFSertifikat(file) {
        const fileURLSertifikat = URL.createObjectURL(file);
        $('#preview-images-sertifikat').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLSertifikat}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-sertifikat"
                            data-src="${fileURLSertifikat}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf-sertifikat"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
    }

    // Lihat FFile Sertifikat
    $(document).on('click', '.btn-preview-pdf-sertifikat', function () {
        $('#preview-pdf-sertifikat').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-sertifikat').modal('show');
        $('#modal-sertifikat').modal('hide');
    });

    // hapus dokumen Sertifikat
    $(document).on('click', '.btn-remove-pdf-sertifikat', function () {
        fileBufferSertifikat = new DataTransfer();
        $('#lampiran-sertifikat').val('');
        $('#preview-images-sertifikat').empty();
    });

    // close modal
    $('#modal-preview-pdf-sertifikat').on('hidden.bs.modal', function () {
        $('#modal-sertifikat').modal('show');
    });

    // Open Modal Sertifikat
    $(document).on('click', '.add-btn-sertifikat', function () {
        $('.form-sertifikat').removeClass('was-validated');
        $('#modal-sertifikat').modal('show');
        $('.modal-title').text('Form Tambah Sertifikat');
        $('.save-btn-sertifikat').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_sertifikat"]').val('');
        $('input[name="nama_sertifikat"]').val('');
        $('input[name="penyelenggara_sertifikat"]').val('');
        $('input[name="tahun_sertifikat"]').val('');
        $('select[name="jenis_sertifikat"]').val('').trigger('change');
        $('#lampiran_sertifikat').val('');
        $('#preview-images-sertifikat').empty();

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-sertifikat")
        });
    });

    // table Sertifikat
    function initTableSertifikat() {
        $tableSertifikat.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            // showColumns: true,
            // showPaginationSwitch: true,
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
            url: "{{ route('master-data.sertifikat-sdm.view') }}",
            columns: [
                [{
                    field: "id",
                    sortable: true,
                    align: "center",
                    width: '60px',
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    // width: '50%',
                    field: 'nama_pekerja',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'jenis_sertifikat',
                    sortable: true,
                },
                {
                    field: 'nama_sertifikat',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'penyelenggara_sertifikat',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'tahun_sertifikat',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsSertifikat,
                    formatter: actionsFunctionSertifikat
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

    // Save Sertifikat
    $(document).on('click', '.save-btn-sertifikat', function (event) {
        var id = $('input[name="id_sertifikat"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.sertifikat.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.sertifikat.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-sertifikat');
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
                        $('.save-btn-sertifikat').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-sertifikat').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-sertifikat').modal('hide');
                            $tableSertifikat.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-warning',
                                title: 'Warning',
                                message: res.message
                            }, { type: 'warning' });
                            form.classList.remove('was-validated');
                        }
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });

    function actionsFunctionSertifikat(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-sertifikat" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-sertifikat" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-sertifikat" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsSertifikat = {
        'click .btn-pdf-sertifikat': function (e, value, row, index) {
            if (row.lampiran_sertifikat) {
                var fileUrl = '{{ url("uploads/sertifikat") }}/' + row.lampiran_sertifikat;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-sertifikat': function (e, value, row, index) {
            $('#modal-sertifikat').modal('show');
            $('.modal-title').text('Form Edit Sertifikat');
            $('.save-btn-sertifikat').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_sertifikat"]').val(row.id_sertifikat);
            $('input[name="nama_sertifikat"]').val(row.nama_sertifikat);
            $('input[name="penyelenggara_sertifikat"]').val(row.penyelenggara_sertifikat);
            $('input[name="tahun_sertifikat"]').val(row.tahun_sertifikat);
            $('select[name="jenis_sertifikat"]').val(row.jenis_sertifikat).trigger('change');

            InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-sertifikat"),
            initialValue: row.id_pegawai
        });

            // reset preview
            $('#preview-images-sertifikat').empty();

            // tampilkan file lama
            if (row.lampiran_sertifikat) {
                let fileURLSertifikat = "/uploads/sertifikat/" + row.lampiran_sertifikat;
                $('#preview-images-sertifikat').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">

                            <iframe src="${fileURLSertifikat}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-sertifikat"
                                        data-src="${fileURLSertifikat}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-sertifikat">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-sertifikat': function (e, value, row, index) {
            var url = "{{ route('master-data.sertifikat.delete', ':id') }}";
            url = url.replace(':id', row.id_sertifikat);
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
                    }).done(function () {
                        $tableSertifikat.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

</script>