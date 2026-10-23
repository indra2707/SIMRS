<script type="text/javascript">

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
                            // $tableStr.bootstrapTable('refresh');
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


    //cari data
    $(document).ready(function () {
        $('#search').on('keyup', function () {
            let search = $(this).val().trim();
            // Jika input kosong
            if (search === '') {
                $('#searchResult').html('');
                return;
            }

            $.ajax({
                url: "{{ route('logistik.perpustakaan.search') }}",
                type: "GET",
                data: {
                    query: search
                },

                success: function (response) {
                    let html = '';
                    if (response.success && response.data.length > 0) {
                        $.each(response.data, function (index, item) {
  
                            html += `
                                <div style="margin-bottom: 12px;">

                                    <p style="font-size: 30px; margin-bottom: 2px; line-height: 1.2;">
                                        <b>
                                            <a href="/uploads/perpustakaan/${encodeURIComponent(item.lampiran)}"
                                               target="_blank"
                                               class="text-primary text-decoration-none">
                                                ${item.judul.toUpperCase()}
                                            </a>
                                        </b>
                                    </p>

                                    <div class="d-flex align-items-center flex-wrap gap-4 text-muted"
                                         style="line-height: 1.2;">
                                        <span style="font-size: 14px;">
                                            <i class="fa fa-folder me-1"></i>
                                            <strong>Kategori:</strong> ${item.kategori ?? '-'}
                                        </span>
                                    </div>

                             </div>
                            `;
                        });

                    } else {
                        // Swal.fire({
                        //     icon: 'warning',
                        //     title: 'Data Tidak Ditemukan',
                        //     text: 'Data yang Anda cari tidak ditemukan.',
                        //     confirmButtonText: 'OK'
                        // });
                    }

                    $('#searchResult').html(html);
                },

                error: function (xhr) {
                    console.log(xhr.responseText);
                    $('#searchResult').html(`
                    <div class="alert alert-danger">
                        Terjadi kesalahan saat mencari data.
                    </div>
                `);
                }
            });

        });
    });
    
</script>