<script type="text/javascript">

    // Tabel
    var $tablePerpustakaan = $('#table_perpustakaan');

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-perpustakaan"),
        allowClear: true
    });


    // lampiran
    $('#btn-attach').on('click', function () {
        $('#lampiran').trigger('click');
    });

    //upload dokumen SK Jabatan
    let fileBuffer = new DataTransfer();
    $(document).on('change', '#lampiran', function () {
        const input = this;
        const file = input.files[0];

        if (!file) return;

        if (file.type !== "application/pdf") {
            Swal.fire("Error", "File harus PDF", "error");
            input.value = "";
            return;
        }

        fileBuffer = new DataTransfer();
        fileBuffer.items.add(file);
        input.files = fileBuffer.files;

        $('#preview-images').empty();
        renderPreviewPDF(file, '#preview-images');
    });

    // // perview File Pdf
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
                            class="btn btn-danger btn-xs btn-remove-pdf-str"
                            style="opacity:0.7;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `);
    }

    // Lihat FIle
    $(document).on('click', '.btn-preview-pdf', function () {
        $('#preview-pdf').attr('src', $(this).data('src'));
        $('#modal-preview-pdf').modal('show');
        $('#modal-perpustakaan').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf', function () {
        fileBuffer = new DataTransfer();
        $('#lampiran').val('');
        $('#preview-images').empty();
    });

    // close modal
    $('#modal-preview-pdf').on('hidden.bs.modal', function () {
        $('#modal-perpustakaan').modal('show');
    });

    // Open Modal
    $(document).on('click', '.add-btn', function () {
        $('.form-perpustakaan').removeClass('was-validated');
        $('#modal-perpustakaan').modal('show');
        $('.modal-title').text('Form Tambah');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="judul"]').val('');
        $('select[name="kategori"]').val('').trigger('change');
        $('#lampiran').val('');
        $('#preview-images').empty();
    });

    // Save
    $(document).on('click', '.save-btn', function (event) {
        var id = $('input[name="id"]').val();
        var url, type;

        if (id) {
            url = "{{ route('logistik.perpustakaan.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('logistik.perpustakaan.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-perpustakaan');
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
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm"></span>'
                        ).attr('disabled', true);
                    },

                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },

                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success) {
                            Alert('success', res.message);
                            $('#modal-perpustakaan').modal('hide');
                            $tablePerpustakaan.bootstrapTable('refresh');
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
        initTablePerpustakaan();
    });

    // Initialize Table
    function initTablePerpustakaan() {
        $tablePerpustakaan.bootstrapTable('destroy').bootstrapTable({
            height: 620,
            locale: 'en-US',
            search: true,
            showHeader: false,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('logistik.perpustakaan.view') }}",
            columns: [
                [
                    {
                        field: 'judul',
                        sortable: true,
                        formatter: function (value, row, index) {
                            let url = `/uploads/perpustakaan/${encodeURIComponent(row.lampiran)}`;
                            return `
                                <div>
                                    <div style="
                                        font-size: 22px;
                                        font-weight: 600;
                                        text-transform: uppercase;
                                        line-height: 1.3;
                                    ">
                                        <a href="${url}"
                                           target="_blank"
                                           style="
                                               color: #212529;
                                               text-decoration: none;
                                               cursor: pointer;
                                           ">
                                            ${value ?? ''}
                                        </a>
                                    </div>

                                    <div style="
                                        font-size: 13px;
                                        color: #888;
                                        margin-top: 4px;
                                    ">
                                        Kategori : ${row.kategori ?? ''}
                                    </div>
                                </div>
                            `;
                        }
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

</script>