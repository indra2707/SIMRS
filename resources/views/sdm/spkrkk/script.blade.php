<script type="text/javascript">
    // Variable Name
    var $tableSpk = $('#table_spk');

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
        initTableSpk();
    });


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

        $('select[name="id_pegawai"]').val('').trigger('change');
        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-spk")
        });
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
            url: "{{ route('master-data.spk-rkk-sdm.view') }}",
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

            InitSelect2($("select[name='id_pegawai']"), {
                url: "{{ route('get-select-pegawai') }}",
                dropdownParent: $("#modal-spk"),
                initialValue: row.id_pegawai
            });

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

</script>