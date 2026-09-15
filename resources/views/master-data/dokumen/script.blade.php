<script type="text/javascript">

    var $tableIjazah = $('#table_ijazah');
    var $tableKontrak = $('#table_kontrak');
    var $tableJabatan = $('#table_jabatan');
    var $tableStr = $('#table_str');
    var $tableSpk = $('#table_spk');
    var $tableSertifikat = $('#table_sertifikat');
    var $tableMcu = $('#table_mcu');
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

    // onclick upload
    $('#btn-attach').on('click', function () {
        $('#lampiran').trigger('click');
    });

    //upload dokumen
    let fileBufferLampiran = new DataTransfer();
    $(document).on('change', '#lampiran', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferLampiran = new DataTransfer();
        fileBufferLampiran.items.add(file);
        input.files = fileBufferLampiran.files;

        $('#preview-images').empty();
        renderPreviewPDF(file, '#preview-images');
    });

    // perview File Pdf
    function renderPreviewPDF(file) {
        const fileURL = URL.createObjectURL(file);
        $('#preview-images').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURL}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf"
                            data-src="${fileURL}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>

                </div>

            </div>
        </div>
    `);
    }

    // Lihat FIle PDF
    $(document).on('click', '.btn-preview-pdf', function () {
        $('#preview-pdf').attr('src', $(this).data('src'));
        $('#modal-preview-pdf').modal('show');
        $('#modal-ijazah').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf', function () {
        fileBuffer = new DataTransfer();
        $('#lampiran').val('');
        $('#preview-images').empty();
    });

    // close modal
    $('#modal-preview-pdf').on('hidden.bs.modal', function () {
        $('#modal-ijazah').modal('show');
    });

    // Open Modal Ijazah
    $(document).on('click', '.add-btn-ijazah', function () {
        $('.form-ijazah').removeClass('was-validated');
        $('#modal-ijazah').modal('show');
        $('.modal-title').text('Form Tambah Ijazah');
        $('.save-btn-ijazah').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_ijazah"]').val('');
        $('input[name="nomor_ijazah"]').val('');
        $('input[name="institusi"]').val('');
        $('select[name="pendidikan"]').val('').trigger('change');
        $('input[name="prodi"]').val('');
        $('input[name="tahun_lulus"]').val('');
        $('#lampiran').val('');
        $('#preview-images').empty();
    });

    // Save ijazah
    $(document).on('click', '.save-btn-ijazah', function (event) {
        var id = $('input[name="id_ijazah"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.ijazah.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.ijazah.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-ijazah');
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
                        $('.save-btn-ijazah').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-ijazah').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-ijazah').modal('hide');
                            $tableIjazah.bootstrapTable('refresh');
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

    // Page Load Event
    $(document).ready(function () {

        // Tab Ijazah
        $('#ijazah-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableIjazah();
        });

        // Tab Kontrak
        $('#kontrak-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableKontrak();
        });

        // Tab Jabatan
        $('#jabatan-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableJabatan();
        });

        // Tab STR
        $('#str-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableStr();
        });

        // Tab SPK
        $('#spk-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableSpk();
        });

        // Tab Sertifikat
        $('#sertifikat-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableSertifikat();
        });

        // Tab Hasil MCU
        $('#mcu-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableMcu();
        });

         // Tab Dokumen Lainnya
        $('#dokumen-lainnya-tab').off('shown.bs.tab').on('shown.bs.tab', function (e) {
            initTableDokumenLainnya();
        });


        // Karena Ijazah adalah tab aktif saat pertama kali halaman dibuka
        initTableIjazah();

    });

    // Table Ijazah
    function initTableIjazah() {
        $tableIjazah.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.ijazah.view') }}",
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
                    field: 'nomor_ijazah',
                    sortable: true,
                },
                {
                    width: '70%',
                    field: 'pendidikan',
                    sortable: true,
                },
                {
                    field: 'institusi',
                    sortable: true,
                },
                {
                    // width: '100%',
                    field: 'prodi',
                    sortable: true,
                },
                {
                    width: '50%',
                    field: 'tahun_lulus',
                    sortable: true,
                },
                {
                    width: '5%',
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
            '<a class="dropdown-item btn-pdf-ijazah" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-ijazah" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-ijazah" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-pdf-ijazah': function (e, value, row, index) {
            if (row.lampiran) {
                var fileUrl = '{{ url("uploads/ijazah") }}/' + row.lampiran;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-ijazah': function (e, value, row, index) {
            $('#modal-ijazah').modal('show');
            $('.modal-title').text('Form Edit Ijazah');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_ijazah"]').val(row.id);
            $('input[name="nomor_ijazah"]').val(row.nomor_ijazah);
            $('input[name="institusi"]').val(row.institusi);
            $('select[name="pendidikan"]').val(row.pendidikan).trigger('change');
            $('input[name="prodi"]').val(row.prodi);
            $('input[name="tahun_lulus"]').val(row.tahun_lulus);


            // reset preview
            $('#preview-images').empty();

            // tampilkan file lama
            if (row.lampiran) {
                let fileURL = "/uploads/ijazah/" + row.lampiran;
                $('#preview-images').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">
                            
                            <iframe src="${fileURL}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf"
                                        data-src="${fileURL}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-ijazah': function (e, value, row, index) {
            var url = "{{ route('master-data.ijazah.delete', ':id') }}";
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
                        $tableIjazah.bootstrapTable('refresh');
                    });

                }
            })
        }
    }


    // kontrak
    $('#btn-attach-kontrak').on('click', function () {
        $('#lampiran-kontrak').trigger('click');
    });

    //upload dokumen kontrak
    let fileBufferKontrak = new DataTransfer();
    $(document).on('change', '#lampiran-kontrak', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferKontrak = new DataTransfer();
        fileBufferKontrak.items.add(file);
        input.files = fileBufferKontrak.files;

        $('#preview-images-kontrak').empty();
        renderPreviewPDFKontrak(file, '#preview-images-kontrak');
    });

    // // perview File Pdf kontrak
    function renderPreviewPDFKontrak(file) {
        const fileURLKontrak = URL.createObjectURL(file);
        $('#preview-images-kontrak').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLKontrak}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-kontrak"
                            data-src="${fileURLKontrak}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf-kontrak"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>

                </div>

            </div>
        </div>
    `);
    }

    // Lihat FIle PDF
    $(document).on('click', '.btn-preview-pdf-kontrak', function () {
        $('#preview-pdf-kontrak').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-kontrak').modal('show');
        $('#modal-kontrak').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf-kontrak', function () {
        fileBuffer = new DataTransfer();
        $('#lampiran-kontrak').val('');
        $('#preview-images-kontrak').empty();
    });

    // close modal
    $('#modal-preview-pdf-kontrak').on('hidden.bs.modal', function () {
        $('#modal-kontrak').modal('show');
    });

    // Open Modal Kontrak
    $(document).on('click', '.add-btn-kontrak', function () {
        $('.form-kontrak').removeClass('was-validated');
        $('#modal-kontrak').modal('show');
        $('.modal-title').text('Form Tambah Kontrak');
        $('.save-btn-kontrak').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_kontrak"]').val('');
        $('input[name="nomor_kontrak"]').val('');
        $('select[name="status"]').val('').trigger('change');
        $('input[name="tanggal_mulai"]').val('');
        $('input[name="tanggal_berakhir"]').val('');
        $('input[name="masa_berlaku"][type="checkbox"]').prop('checked', false).val('1');
        $('input[name="masa_berlaku"][type="hidden"]').val('0');
        $('#lampiran_kontrak').val('');
        $('#preview-images-kontrak').empty();
        $("#tanggal_berakhir").prop("readonly", false).attr("required", true).closest(".row").show();
        toggleMasaBerlaku();
    });

    // Masa Berlaku
    $("#masa_berlaku").on("change", toggleMasaBerlaku);
    function toggleMasaBerlaku() {
        const checked = $("#masa_berlaku").is(":checked");
        $("#tanggal_berakhir").prop("readonly", checked).prop("required", !checked).closest(".row").toggle(!checked);
        if (checked) {
            $("#tanggal_berakhir").val("");
        }
    }


    // table kontrak
    function initTableKontrak() {
        $tableKontrak.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.kontrak.view') }}",
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
                    field: 'id_kontrak',
                    sortable: true,
                    visible: false
                },
                {
                    field: 'nomor_kontrak',
                    sortable: true,
                },
                {
                    field: 'status',
                    sortable: true,
                },
                {
                    field: 'masa_berlaku',
                    sortable: true,
                    formatter: function (value, row, index) {
                        if (value == 0) {
                            return '-';
                        } else if (value == 1) {
                            return 'Seumur Hidup';
                        }
                        return '-';
                    }
                },
                {
                    width: '50%',
                    field: 'tanggal_mulai',
                    sortable: true,
                },
                {
                    width: '50%',
                    field: 'tanggal_berakhir',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsKontrak,
                    formatter: actionsFunctionKontrak
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

    // Save Kontrak
    $(document).on('click', '.save-btn-kontrak', function (event) {
        var id = $('input[name="id_kontrak"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.kontrak.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.kontrak.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-kontrak');
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
                        $('.save-btn-kontrak').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-kontrak').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-kontrak').modal('hide');
                            $tableKontrak.bootstrapTable('refresh');
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


    function actionsFunctionKontrak(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-kontrak" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-kontrak" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-kontrak" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsKontrak = {
        'click .btn-pdf-kontrak': function (e, value, row, index) {
            if (row.lampiran) {
                var fileUrl = '{{ url("uploads/kontrak") }}/' + row.lampiran;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-kontrak': function (e, value, row, index) {
            $('#modal-kontrak').modal('show');
            $('.modal-title').text('Form Edit Kontrak');
            $('.save-btn-kontrak').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_kontrak"]').val(row.id_kontrak);
            $('input[name="nomor_kontrak"]').val(row.nomor_kontrak);
            $('select[name="status"]').val(row.status).trigger('change');
            $('input[name="tanggal_mulai"]').val(row.tanggal_mulai);
            $('input[name="tanggal_berakhir"]').val(row.tanggal_berakhir);
            $('input[name="masa_berlaku"][type="checkbox"]').prop('checked', row.masa_berlaku == 1).trigger('change');

            // reset preview
            $('#preview-images-kontrak').empty();

            // tampilkan file lama
            if (row.lampiran) {
                let fileURLKontrak = "/uploads/kontrak/" + row.lampiran;
                $('#preview-images-kontrak').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">
                            
                            <iframe src="${fileURLKontrak}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-kontrak"
                                        data-src="${fileURLKontrak}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-kontrak">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-kontrak': function (e, value, row, index) {
            var url = "{{ route('master-data.kontrak.delete', ':id') }}";
            url = url.replace(':id', row.id_kontrak);
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
                        data: {
                            _token: "{{ csrf_token() }}"
                        },

                        success: function (res, status, xhr) {

                            if (xhr.status === 200 && res.success === true) {

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
                                    }
                                });

                                $tableKontrak.bootstrapTable('refresh');

                            } else {

                                $.notify({
                                    icon: 'fa fa-exclamation-triangle',
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
                                    }
                                });
                            }
                        },

                        error: function (xhr) {

                            $.notify({
                                icon: 'fa fa-times',
                                title: 'Error',
                                message: 'Terjadi kesalahan saat menghapus data.'
                            }, {
                                type: 'danger',
                                allow_dismiss: true,
                                delay: 3000,
                                showProgressbar: true,
                                timer: 300,
                                z_index: 1127
                            });

                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        }
    }




    // SK Jabatan
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
            url: "{{ route('master-data.jabatan.view') }}",
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
            url = "{{ route('master-data.jabatan.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.jabatan.create') }}";
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
            var url = "{{ route('master-data.jabatan.delete', ':id') }}";
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





    // STR dan SIP
    $('#btn-attach-str').on('click', function () {
        $('#lampiran-str').trigger('click');
    });

    //upload dokumen SK Jabatan
    let fileBufferStr = new DataTransfer();
    $(document).on('change', '#lampiran-str', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferStr = new DataTransfer();
        fileBufferStr.items.add(file);
        input.files = fileBufferStr.files;

        $('#preview-images-str').empty();
        renderPreviewPDFStr(file, '#preview-images-str');
    });

    // // perview File Pdf kontrak
    function renderPreviewPDFStr(file) {
        const fileURLStr = URL.createObjectURL(file);
        $('#preview-images-str').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLStr}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-str"
                            data-src="${fileURLStr}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf-str"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
    }

    // Lihat FIle STR
    $(document).on('click', '.btn-preview-pdf-str', function () {
        $('#preview-pdf-str').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-str').modal('show');
        $('#modal-str').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf-str', function () {
        fileBufferStr = new DataTransfer();
        $('#lampiran-str').val('');
        $('#preview-images-str').empty();
    });

    // close modal
    $('#modal-preview-pdf-str').on('hidden.bs.modal', function () {
        $('#modal-str').modal('show');
    });

    // Open Modal STR dan SIP
    $(document).on('click', '.add-btn-str', function () {
        $('.form-str').removeClass('was-validated');
        $('#modal-str').modal('show');
        $('.modal-title').text('Form Tambah STR dan SIP');
        $('.save-btn-str').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_str"]').val('');
        $('input[name="nomor_str"]').val('');
        $('select[name="jenis_str"]').val('').trigger('change');
        $('input[name="tanggal_mulai_str"]').val('');
        $('input[name="tanggal_berakhir_str"]').val('');
        $('input[name="masa_berlaku_str"][type="checkbox"]').prop('checked', false).val('1');
        $('input[name="masa_berlaku_str"][type="hidden"]').val('0');
        $('#lampiran_str').val('');
        $('#preview-images-str').empty();
        toggleMasaBerlakuStr();
    });

    // Masa Berlaku STR dan SIP
    $("#masa_berlaku_str").on("change", toggleMasaBerlakuStr);
    function toggleMasaBerlakuStr() {
        const checked = $("#masa_berlaku_str").is(":checked");
        $("#tanggal_berakhir_str").prop("readonly", checked).prop("required", !checked).closest(".row").toggle(!checked);
        if (checked) {
            $("#tanggal_berakhir_str").val("");
        }
    }

    // table STR dan SIP
    function initTableStr() {
        $tableStr.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.str-sip.view') }}",
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
                    field: 'nomor_str',
                    sortable: true,
                },
                {
                    field: 'jenis_str',
                    sortable: true,
                },
                {
                    field: 'masa_berlaku_str',
                    sortable: true,
                    formatter: function (value, row, index) {
                        if (value == 0) {
                            return '-';
                        } else if (value == 1) {
                            return 'Seumur Hidup';
                        }
                        return '-';
                    }
                },
                {
                    // width: '50%',
                    field: 'tanggal_mulai_str',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'tanggal_berakhir_str',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsStr,
                    formatter: actionsFunctionStr
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

    // Save STR dan SIP
    $(document).on('click', '.save-btn-str', function (event) {
        var id = $('input[name="id_str"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.str-sip.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.str-sip.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-str');
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
                        $('.save-btn-str').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-str').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-str').modal('hide');
                            $tableStr.bootstrapTable('refresh');
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

    function actionsFunctionStr(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-str" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-str" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-str" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsStr = {
        'click .btn-pdf-str': function (e, value, row, index) {
            if (row.lampiran_str) {
                var fileUrl = '{{ url("uploads/str") }}/' + row.lampiran_str;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-str': function (e, value, row, index) {
            $('#modal-str').modal('show');
            $('.modal-title').text('Form Edit STR');
            $('.save-btn-str').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_str"]').val(row.id_str);
            $('input[name="nomor_str"]').val(row.nomor_str);
            $('select[name="jenis_str"]').val(row.jenis_str).trigger('change');
            $('input[name="tanggal_mulai_str"]').val(row.tanggal_mulai_str);
            $('input[name="tanggal_berakhir_str"]').val(row.tanggal_berakhir_str);
            $('input[name="masa_berlaku_str"][type="checkbox"]').prop('checked', row.masa_berlaku_str == 1).trigger('change');

            // reset preview
            $('#preview-images-str').empty();

            // tampilkan file lama
            if (row.lampiran_str) {
                let fileURLStr = "/uploads/str/" + row.lampiran_str;
                $('#preview-images-str').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">

                            <iframe src="${fileURLStr}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-str"
                                        data-src="${fileURLStr}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-str">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-str': function (e, value, row, index) {
            var url = "{{ route('master-data.str-sip.delete', ':id') }}";
            url = url.replace(':id', row.id_str);
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
                        $tableStr.bootstrapTable('refresh');
                    });

                }
            })
        }
    }



    // SPK dan RKK
    $('#btn-attach-spk').on('click', function () {
        $('#lampiran-spk').trigger('click');
    });

    //upload dokumen SPK dan RKK
    let fileBufferSpk = new DataTransfer();
    $(document).on('change', '#lampiran-spk', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBufferSpk = new DataTransfer();
        fileBufferSpk.items.add(file);
        input.files = fileBufferSpk.files;

        $('#preview-images-spk').empty();
        renderPreviewPDFSpk(file, '#preview-images-spk');
    });

    // // perview File Pdf SPK dan RKK
    function renderPreviewPDFSpk(file) {
        const fileURLSpk = URL.createObjectURL(file);
        $('#preview-images-spk').append(`
        <div class="col-md-4 mb-2">
            <div class="position-relative border rounded overflow-hidden">

                <!-- Preview PDF -->
                <iframe src="${fileURLSpk}"
                        style="width:100%; height:200px; border:none;">
                </iframe>

                <!-- Action Button -->
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                    
                    <button type="button"
                            class="btn btn-primary btn-xs btn-preview-pdf-spk"
                            data-src="${fileURLSpk}"
                            style="opacity:0.7;">
                        <i class="fa fa-eye"></i>
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-xs btn-remove-pdf-spk"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
    }

    // Lihat FIle SPK dan RKK
    $(document).on('click', '.btn-preview-pdf-spk', function () {
        $('#preview-pdf-spk').attr('src', $(this).data('src'));
        $('#modal-preview-pdf-spk').modal('show');
        $('#modal-spk').modal('hide');
    });

    // hapus dokumen SPK dan RKK
    $(document).on('click', '.btn-remove-pdf-spk', function () {
        fileBufferSpk = new DataTransfer();
        $('#lampiran-spk').val('');
        $('#preview-images-spk').empty();
    });

    // close modal
    $('#modal-preview-pdf-spk').on('hidden.bs.modal', function () {
        $('#modal-spk').modal('show');
    });

    // Open Modal SPK dan RKK
    $(document).on('click', '.add-btn-spk', function () {
        $('.form-spk').removeClass('was-validated');
        $('#modal-spk').modal('show');
        $('.modal-title').text('Form Tambah SPK dan RKK');
        $('.save-btn-spk').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id_spk"]').val('');
        $('input[name="nomor_spk"]').val('');
        $('input[name="tanggal_mulai_spk"]').val('');
        $('input[name="tanggal_berakhir_spk"]').val('');
        $('#lampiran_spk').val('');
        $('#preview-images-spk').empty();
    });

    // table SPK dan RKK
    function initTableSpk() {
        $tableSpk.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('master-data.spk-rkk.view') }}",
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
                    field: 'nomor_spk',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'tanggal_mulai_spk',
                    sortable: true,
                },
                {
                    // width: '50%',
                    field: 'tanggal_berakhir_spk',
                    sortable: true,
                },
                {
                    width: '5%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: false,
                    events: window.operateEventsSpk,
                    formatter: actionsFunctionSpk
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

    // Save SPK dan RKK
    $(document).on('click', '.save-btn-spk', function (event) {
        var id = $('input[name="id_spk"]').val();
        var url, type;

        if (id) {
            url = "{{ route('master-data.spk-rkk.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('master-data.spk-rkk.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-spk');
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
                        $('.save-btn-spk').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn-spk').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-spk').modal('hide');
                            $tableSpk.bootstrapTable('refresh');
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

    function actionsFunctionSpk(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-pdf-spk" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> PDF</a>',
            '<a class="dropdown-item btn-edit-spk" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete-spk" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEventsSpk = {
        'click .btn-pdf-spk': function (e, value, row, index) {
            if (row.lampiran_spk) {
                var fileUrl = '{{ url("uploads/spk") }}/' + row.lampiran_spk;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit-spk': function (e, value, row, index) {
            $('#modal-spk').modal('show');
            $('.modal-title').text('Form Edit SPK');
            $('.save-btn-spk').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id_spk"]').val(row.id_spk);
            $('input[name="nomor_spk"]').val(row.nomor_spk);
            $('input[name="tanggal_mulai_spk"]').val(row.tanggal_mulai_spk);
            $('input[name="tanggal_berakhir_spk"]').val(row.tanggal_berakhir_spk);

            // reset preview
            $('#preview-images-spk').empty();

            // tampilkan file lama
            if (row.lampiran_spk) {
                let fileURLSpk = "/uploads/spk/" + row.lampiran_spk;
                $('#preview-images-spk').append(`
                    <div class="col-md-4 mb-2">
                        <div class="position-relative border rounded overflow-hidden">

                            <iframe src="${fileURLSpk}"
                                    style="width:100%; height:200px; border:none;">
                            </iframe>

                            <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">
                                <button type="button"
                                        class="btn btn-primary btn-xs btn-preview-pdf-spk"
                                        data-src="${fileURLSpk}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-danger btn-xs btn-remove-pdf-spk">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            }
        },
        'click .btn-delete-spk': function (e, value, row, index) {
            var url = "{{ route('master-data.spk-rkk.delete', ':id') }}";
            url = url.replace(':id', row.id_spk);
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
                        $tableSpk.bootstrapTable('refresh');
                    });

                }
            })
        }
    }



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
            url: "{{ route('master-data.sertifikat.view') }}",
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
            url: "{{ route('master-data.mcu.view') }}",
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
            url: "{{ route('master-data.dokumen-lainnya.view') }}",
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