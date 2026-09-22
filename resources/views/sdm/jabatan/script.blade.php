<script type="text/javascript">
    // Variable Name
    var $tableJabatan = $('#table_jabatan');

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
        initTableJabatan();
    });


    $('#btn-attach-jabatan').on('click', function () {
        $('#lampiran-jabatan').trigger('click');
    });

    //upload dokumen SK Jabatan
    let fileBufferJabatan = new DataTransfer();
    $(document).on('change', '#lampiran-jabatan', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferJabatan = new DataTransfer();
        fileBufferJabatan.items.add(file);
        input.files = fileBufferJabatan.files;

        $('#preview-images-jabatan').empty();
        renderPreviewPDFJabatan(file, '#preview-images-jabatan');
    });

    // // perview File Pdf kontrak
    function renderPreviewPDFJabatan(file) {
        const fileURLJabatan = URL.createObjectURL(file);
        $('#preview-images-jabatan').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLJabatan}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-jabatan"
                            data-src="${fileURLJabatan}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf-jabatan"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
    }

    // Lihat FIle PDF
    $(document).on('click', '.btn-preview-pdf-jabatan', function () {
        $('#preview-pdf-jabatan').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-jabatan').modal('show');
        $('#modal-jabatan').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf-jabatan', function () {
        fileBuffer = new DataTransfer();
        $('#lampiran-jabatan').val('');
        $('#preview-images-jabatan').empty();
    });

    // close modal
    $('#modal-preview-pdf-jabatan').on('hidden.bs.modal', function () {
        $('#modal-jabatan').modal('show');
    });

    // Open Modal Jabatan
    $(document).on('click', '.add-btn-jabatan', function () {
        $('.form-jabatan').removeClass('was-validated');
        $('#modal-jabatan').modal('show');
        $('.modal-title').text('Form Tambah Jabatan');
        $('.save-btn-jabatan').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_jabatan"]').val('');
        $('input[name="nomor_sk"]').val('');
        $('input[name="nama_jabatan"]').val('');
        $('input[name="tanggal_mulai_jabatan"]').val('');
        $('input[name="tanggal_berakhir_jabatan"]').val('');
        $('#lampiran_jabatan').val('');
        $('#preview-images-jabatan').empty();

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-jabatan")
        });

    });


    // table Jabatan
    function initTableJabatan() {
        $tableJabatan.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.skjabatan-sdm.view') }}",
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
                    field: 'id_jabatan',
                    sortable: true,
                    visible: false,
                },
                {
                    field: 'nama_pegawai',
                    sortable: true,
                },
                {
                    field: 'nomor_sk',
                    sortable: true,
                },
                {
                    field: 'nama_jabatan',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'tanggal_mulai_jabatan',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'tanggal_berakhir_jabatan',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsJabatan,
                    formatter: actionsFunctionJabatan
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

    // Save Jabatan
    $(document).on('click', '.save-btn-jabatan', function (event) {
        var id = $('input[name="id_jabatan"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.skjabatan.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.skjabatan.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-jabatan');
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
                        $('.save-btn-jabatan').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-jabatan').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-jabatan').modal('hide');
                            $tableJabatan.bootstrapTable('refresh');
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

    function actionsFunctionJabatan(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-jabatan" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-jabatan" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-jabatan" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsJabatan = {
        'click .btn-pdf-jabatan': function (e, value, row, index) {
            if (row.lampiran) {
                var fileUrl = '{{ url("uploads/jabatan") }}/' + row.lampiran;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-jabatan': function (e, value, row, index) {
            $('#modal-jabatan').modal('show');
            $('.modal-title').text('Form Edit Jabatan');
            $('.save-btn-jabatan').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_jabatan"]').val(row.id_jabatan);
            $('input[name="nomor_sk"]').val(row.nomor_sk);
            $('input[name="nama_jabatan"]').val(row.nama_jabatan);
            $('input[name="tanggal_mulai_jabatan"]').val(row.tanggal_mulai_jabatan);
            $('input[name="tanggal_berakhir_jabatan"]').val(row.tanggal_berakhir_jabatan);

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-jabatan"),
                initialValue: row.id_pegawai
            });

            // reset preview
            $('#preview-images-jabatan').empty();

            // tampilkan file lama
            if (row.lampiran_jabatan) {
                let fileURLJabatan = "/uploads/jabatan/" + row.lampiran_jabatan;
                $('#preview-images-jabatan').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">
                            
                            <iframe src="${fileURLJabatan}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-jabatan"
                                        data-src="${fileURLJabatan}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-jabatan">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-jabatan': function (e, value, row, index) {
            var url = "{{ route('master-data.skjabatan.delete', ':id') }}";
            url = url.replace(':id', row.id_jabatan);
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
                        $tableJabatan.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

</script>