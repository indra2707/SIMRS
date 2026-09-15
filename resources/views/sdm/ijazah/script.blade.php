<script type="text/javascript">
    // Variable Name
    var $tableIjazah = $('#table_ijazah');

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
        $('select[name="id_pegawai"]').val('').trigger('change');

        InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-ijazah")
        });
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


    $(function () {
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
            url: "{{ route('master-data.ijazah-sdm.view') }}",
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
            $('input[name="id_ijazah"]').val(row.id_ijazah);
            $('input[name="nomor_ijazah"]').val(row.nomor_ijazah);
            $('input[name="institusi"]').val(row.institusi);
            $('select[name="pendidikan"]').val(row.pendidikan).trigger('change');
            $('input[name="prodi"]').val(row.prodi);
            $('input[name="tahun_lulus"]').val(row.tahun_lulus);

            InitSelect2($("select[name='id_pegawai']"), {
            url: "{{ route('get-select-pegawai') }}",
            dropdownParent: $("#modal-ijazah"),
            initialValue: row.id_pegawai
        });


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
</script>