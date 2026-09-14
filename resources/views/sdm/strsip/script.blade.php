<script type="text/javascript">
    // Variable Name
    var $tableStr = $('#table_str');

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
        initTableStr();
    });

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

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-str")
        });
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
            url: "{{ route('master-data.str-sip-sdm.view') }}",
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
                    field: 'nama_pekerja',
                    sortable: true,
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

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-str"),
                initialValue: row.id_pegawai
            });

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

</script>