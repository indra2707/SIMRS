<script type="text/javascript">
    // Variable Name
    var $tableDokumenLainnya = $('#table_dokumen_lainnya');

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
        initTableDokumenLainnya();
    });

    // hasil Dokumen Lainnya
    $('#btn-attach-dokumen-lainnya').on('click', function () {
        $('#lampiran-dokumen-lainnya').trigger('click');
    });

    //upload dokumen Lainnya
    let fileBufferDokumenLainnya = new DataTransfer();
    $(document).on('change', '#lampiran-dokumen-lainnya', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferDokumenLainnya = new DataTransfer();
        fileBufferDokumenLainnya.items.add(file);
        input.files = fileBufferDokumenLainnya.files;

        $('#preview-images-dokumen-lainnya').empty();
        renderPreviewPDFDokumen(file, '#preview-images-dokumen-lainnya');
    });

    // // perview File Pdf Hasil Dokumen Lainnya
    function renderPreviewPDFDokumen(file) {
        const fileURLDokumen = URL.createObjectURL(file);
        $('#preview-images-dokumen-lainnya').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLDokumen}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-dokumen"
                            data-src="${fileURLDokumen}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf-mcu"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
    }

    // Lihat FFile Hasil Dokumen Lainnya
    $(document).on('click', '.btn-preview-pdf-dokumen', function () {
        $('#preview-pdf-dokumen').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-dokumen').modal('show');
        $('#modal-dokumen-lainnya').modal('hide');
    });

    // hapus dokumen Hasil Dokumen Lainnya
    $(document).on('click', '.btn-remove-pdf-dokumen', function () {
        fileBufferDokumenLainnya = new DataTransfer();
        $('#lampiran-dokumen-lainnya').val('');
        $('#preview-images-dokumen-lainnya').empty();
    });

    // close modal
    $('#modal-preview-pdf-dokumen').on('hidden.bs.modal', function () {
        $('#modal-dokumen-lainnya').modal('show');
    });

    // Open Modal Dokumen Lainnya
    $(document).on('click', '.add-btn-dokumen-lainnya', function () {
        $('.form-dokumen-lainnya').removeClass('was-validated');
        $('#modal-dokumen-lainnya').modal('show');
        $('.modal-title').text('Form Tambah Dokumen Lainnya');
        $('.save-btn-dokumen-lainnya').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_dokumen_lainnya"]').val('');
        $('select[name="jenis_dokumen_lainnya"]').val('').trigger('change');
        $('input[name="nomor_dokumen_lainnya"]').val('');
        $('textarea[name="catatan_dokumen_lainnya"]').val('');
        $('#lampiran-dokumen-lainnya').val('');
        $('#preview-images-dokumen-lainnya').empty();

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-dokumen-lainnya")
        });
    });

    // table Dokumen Lainnya
    function initTableDokumenLainnya() {
        $tableDokumenLainnya.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.dokumen-lainnya-sdm.view') }}",
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
                    field: 'jenis_dokumen_lainnya',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'nomor_dokumen_lainnya',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'catatan_dokumen_lainnya',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsDokumenLainnya,
                    formatter: actionsFunctionDokumenLainnya
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

    // Save Hasil MCU
    $(document).on('click', '.save-btn-dokumen-lainnya', function (event) {
        var id = $('input[name="id_dokumen_lainnya"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.dokumen-lainnya.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.dokumen-lainnya.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-dokumen-lainnya');
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
                        $('.save-btn-dokumen-lainnya').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-dokumen-lainnya').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-dokumen-lainnya').modal('hide');
                            $tableDokumenLainnya.bootstrapTable('refresh');
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

    function actionsFunctionDokumenLainnya(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-dokumen-lainnya" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-dokumen-lainnya" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-dokumen-lainnya" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsDokumenLainnya = {
        'click .btn-pdf-dokumen-lainnya': function (e, value, row, index) {
            if (row.lampiran_dokumen_lainnya) {
                var fileUrl = '{{ url("uploads/dokumen_lainnya") }}/' + row.lampiran_dokumen_lainnya;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-dokumen-lainnya': function (e, value, row, index) {
            $('#modal-dokumen-lainnya').modal('show');
            $('.modal-title').text('Form Edit Dokumen Lainnya');
            $('.save-btn-dokumen-lainnya').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_dokumen_lainnya"]').val(row.id_dokumen_lainnya);
            $('input[name="nomor_dokumen_lainnya"]').val(row.nomor_dokumen_lainnya);
            $('textarea[name="catatan_dokumen_lainnya"]').val(row.catatan_dokumen_lainnya);
            $('select[name="jenis_dokumen_lainnya"]').val(row.jenis_dokumen_lainnya).trigger('change');

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-dokumen-lainnya"),
                initialValue: row.id_pegawai
            });

            // reset preview
            $('#preview-images-dokumen-lainnya').empty();

            // tampilkan file lama
            if (row.lampiran_dokumen_lainnya) {
                let fileURLDokumenLainnya = "/uploads/dokumen_lainnya/" + row.lampiran_dokumen_lainnya;
                $('#preview-images-dokumen-lainnya').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">

                            <iframe src="${fileURLDokumenLainnya}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-dokumen-lainnya"
                                        data-src="${fileURLDokumenLainnya}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-dokumen-lainnya">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-dokumen-lainnya': function (e, value, row, index) {
            var url = "{{ route('master-data.dokumen-lainnya.delete', ':id') }}";
            url = url.replace(':id', row.id_dokumen_lainnya);
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
                        $tableDokumenLainnya.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

</script>