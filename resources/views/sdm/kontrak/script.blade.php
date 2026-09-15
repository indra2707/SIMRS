<script type="text/javascript">
    // Variable Name
    var $tableKontrak = $('#table_kontrak');

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
        initTableKontrak();
    });

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

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-kontrak")
        });
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
            url: "{{ route('master-data.kontrak.viewsdm') }}",
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
                // {
                //     field: 'id_kontrak',
                //     sortable: true,
                //     visible: false
                // },
                {
                    field: 'nama_pekerja',
                    sortable: true,
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

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-kontrak"),
                initialValue: row.id_pegawai
            });

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

</script>