<script type="text/javascript">
    // Variable Name
    var $tableMcu = $('#table_mcu');

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
        initTableMcu();
    });

    // hasil MCU
    $('#btn-attach-mcu').on('click', function () {
        $('#lampiran-mcu').trigger('click');
    });

    //upload dokumen Hasil MCU
    let fileBufferMcu = new DataTransfer();
    $(document).on('change', '#lampiran-mcu', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferMcu = new DataTransfer();
        fileBufferMcu.items.add(file);
        input.files = fileBufferMcu.files;

        $('#preview-images-mcu').empty();
        renderPreviewPDFMcu(file, '#preview-images-mcu');
    });

    // // perview File Pdf Hasil MCU
    function renderPreviewPDFMcu(file) {
        const fileURLMcu = URL.createObjectURL(file);
        $('#preview-images-mcu').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLMcu}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-mcu"
                            data-src="${fileURLMcu}"
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

    // Lihat FFile Hasil MCU
    $(document).on('click', '.btn-preview-pdf-mcu', function () {
        $('#preview-pdf-mcu').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-mcu').modal('show');
        $('#modal-mcu').modal('hide');
    });

    // hapus dokumen Hasil MCU
    $(document).on('click', '.btn-remove-pdf-mcu', function () {
        fileBufferMcu = new DataTransfer();
        $('#lampiran-mcu').val('');
        $('#preview-images-mcu').empty();
    });

    // close modal
    $('#modal-preview-pdf-mcu').on('hidden.bs.modal', function () {
        $('#modal-mcu').modal('show');
    });

    // Open Modal Hasil MCU
    $(document).on('click', '.add-btn-mcu', function () {
        $('.form-mcu').removeClass('was-validated');
        $('#modal-mcu').modal('show');
        $('.modal-title').text('Form Tambah Hasil MCU');
        $('.save-btn-mcu').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_mcu"]').val('');
        $('input[name="tanggal_mcu"]').val('');
        $('textarea[name="catatan_mcu"]').val('');
        $('select[name="hasil_mcu"]').val('').trigger('change');
        $('#lampiran_mcu').val('');
        $('#preview-images-mcu').empty();

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-mcu")
        });
    });

    // table hasil MCU
    function initTableMcu() {
        $tableMcu.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.mcu-sdm.view') }}",
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
                    field: 'tanggal_mcu',
                    sortable: true,
                },
                {
                    field: 'hasil_mcu',
                    sortable: true,
                    formatter: function (value, row, index) {
                        const hasilMCU = {
                            'P1': 'P1 - TIDAK DITEMUKAN KELAINAN MEDIS',
                            'P2': 'P2 - DITEMUKAN KELAINAN MEDIS YANG TIDAK SERIUS',
                            'P3': 'P3 - DITEMUKAN KELAINAN MEDIS, RESIKO KESEHATAN RENDAH',
                            'P4': 'P4 - DITEMUKAN KELAINAN MEDIS BERMAKNA YANG DAPAT MENJADI SERIUS, RESIKO KESEHATAN SEDANG',
                            'P5': 'P5 - DITEMUKAN KELAINAN MEDIS YANG SERIUS, RESIKO KESEHATAN TINGGI',
                            'P6': 'P6 - DITEMUKAN KELAINAN MEDIS YANG MENYEBABKAN KETERBATASAN FISIK ATAU PSIKIS UNTUK MELAKUKAN PEKERJAAN SESUAI DENGAN JABATAN ATAU FUNGSINYA',
                            'P7': 'P7 - TIDAK DAPAT BEKERJA UNTUK MELAKUKAN PEKERJAAN SESUAI DENGAN JABATAN/POSISINYA DAN/ATAU POSISI APAPUN, SEDANG DALAM PERAWATAN DI RUMAH SAKIT, ATAU DENGAN STATUS IZIN SAKIT'
                        };

                        return hasilMCU[value] || '-';
                    }
                },
                {
                    // width: '50%',
                    field: 'catatan_mcu',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsMcu,
                    formatter: actionsFunctionMcu
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
    $(document).on('click', '.save-btn-mcu', function (event) {
        var id = $('input[name="id_mcu"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.mcu.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.mcu.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-mcu');
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
                        $('.save-btn-mcu').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-mcu').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-mcu').modal('hide');
                            $tableMcu.bootstrapTable('refresh');
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

    function actionsFunctionMcu(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-mcu" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-mcu" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-mcu" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsMcu = {
        'click .btn-pdf-mcu': function (e, value, row, index) {
            if (row.lampiran_mcu) {
                var fileUrl = '{{ url("uploads/mcu") }}/' + row.lampiran_mcu;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-mcu': function (e, value, row, index) {
            $('#modal-mcu').modal('show');
            $('.modal-title').text('Form Edit Hasil MCU');
            $('.save-btn-mcu').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_mcu"]').val(row.id_mcu);
            $('input[name="tanggal_mcu"]').val(row.tanggal_mcu);
            $('textarea[name="catatan_mcu"]').val(row.catatan_mcu);
            $('select[name="hasil_mcu"]').val(row.hasil_mcu).trigger('change');

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-mcu"),
                initialValue: row.id_pegawai

            });

            // reset preview
            $('#preview-images-mcu').empty();

            // tampilkan file lama
            if (row.lampiran_mcu) {
                let fileURLMcu = "/uploads/mcu/" + row.lampiran_mcu;
                $('#preview-images-mcu').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">

                            <iframe src="${fileURLMcu}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-mcu"
                                        data-src="${fileURLMcu}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-mcu">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-mcu': function (e, value, row, index) {
            var url = "{{ route('master-data.mcu.delete', ':id') }}";
            url = url.replace(':id', row.id_mcu);
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
                        $tableMcu.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

</script>