<script type="text/javascript">

    // textarea
    var editor;
    $(document).ready(function () {

        ClassicEditor
            .create($('#editable')[0], {
                toolbar: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    'subscript',
                    'superscript',
                    '|',
                    'removeFormat',
                    '|',
                    'fontSize',
                    'fontFamily',
                    'fontColor',
                    'fontBackgroundColor',
                    '|',
                    'alignment',
                    '|',
                    'bulletedList',
                    'numberedList',
                    'todoList',
                    '|',
                    'insertTable',
                    'specialCharacters',
                    '|',
                    'undo',
                    'redo'
                ],

                alignment: {
                    options: [
                        'left',
                        'center',
                        'right',
                        'justify'
                    ]
                }
            })
            .then(function (newEditor) {
                editor = newEditor;
                // Simpan ke global
                window.editor = newEditor;
                console.log('CKEditor berhasil dibuat');
            })
            .catch(function (error) {
                console.error('CKEditor error:', error);
            });
    });


    // Tabel
    var $tableSurat = $('#table_surat');
    var lampiranHapusList = [];
    let fileBufferSurat = new DataTransfer();


    $('#btn-attach-surat').on('click', function () {
        $('#lampiran').trigger('click');
    });

    $(document).on('change', 'input[name="lampiran[]"]', function () {
        const input = this;
        const newFiles = Array.from(input.files);

        // validasi maksimal 5 file baru
        if ((fileBufferSurat.files.length + newFiles.length) > 5) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Maksimal 5 file baru sekaligus.',
            });
            input.value = '';
            return;
        }

        newFiles.forEach((file) => {
            fileBufferSurat.items.add(file);
            renderPreviewSurat(file, fileBufferSurat.files.length - 1);
        });

        input.files = fileBufferSurat.files;
    });

    function renderPreviewSurat(file, index) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#preview-images-surat').append(`
            <div class="col-md-2 mb-2" data-index="${index}">
                <div class="position-relative">
                    <img src="${e.target.result}"
                         class="img-thumbnail preview-img-surat"
                         style="height:100px;object-fit:cover;cursor:pointer">

                    <div class="position-absolute bottom-0 end-0 m-1 d-flex gap-1">
                        <button type="button"
                                class="btn btn-light btn-xs btn-preview-surat"
                                data-src="${e.target.result}">
                            <i class="fa fa-eye"></i>
                        </button>

                        <button type="button"
                                class="btn btn-light btn-xs btn-remove-surat"
                                data-index="${index}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
        };

        reader.readAsDataURL(file);
    }

    var modalAsalPreviewSurat = '#modal-surat';

    $(document).on('click', '.btn-preview-surat', function () {
        modalAsalPreviewSurat = '#modal-surat';
        $('#preview-large-surat').attr('src', $(this).data('src'));
        $('#modal-preview-image-surat').modal('show');
        $('#modal-surat').modal('hide');
    });

    $(document).on('click', '.btn-preview-surat-tersimpan', function () {
        modalAsalPreviewSurat = $(this).data('modal-asal') || '#modal-surat';
        $('#preview-large-surat').attr('src', $(this).data('src'));
        $('#modal-preview-image-surat').modal('show');
        $(modalAsalPreviewSurat).modal('hide');
    });

    $(document).on('click', '.btn-remove-surat', function () {

        const $button = $(this);
        const $item = $button.closest('.col-md-4');

        const file = $button.attr('data-file');
        const index = $button.attr('data-index');

        // Lampiran existing
        if (file !== undefined && file !== '') {

            // Simpan file yang dihapus
            $('#deleted-lampiran-container').append(`
            <input type="hidden"
                   name="deleted_lampiran[]"
                   value="${file}">
        `);

            // Hapus preview
            $item.remove();

            return;
        }

        // Lampiran baru
        const input = document.querySelector(
            'input[name="lampiran[]"]'
        );

        if (!input || !fileBufferSurat) {
            return;
        }

        const removeIndex = parseInt(index);

        let newBuffer = new DataTransfer();

        Array.from(fileBufferSurat.files).forEach((file, i) => {
            if (i !== removeIndex) {
                newBuffer.items.add(file);
            }
        });

        fileBufferSurat = newBuffer;
        input.files = fileBufferSurat.files;

        $('#preview-images-surat')
            .find('.preview-file-baru')
            .remove();

        Array.from(fileBufferSurat.files).forEach(function (file, index) {
            renderPreviewSurat(file, index);
        });
    });

    // Tutup modal preview besar -> balik ke modal asal (form / detail / lampiran-list)
    $('#modal-preview-image-surat').on('hidden.bs.modal', function () {
        $(modalAsalPreviewSurat).modal('show');
    });

    // Generate Nomor Surat
    function getNomorSurat() {
        $.ajax({
            url: "{{ route('surat.getNomorSurat') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $('input[name="no_surat"]').val(response.no_surat);
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }


    // Open Modal Tambah Surat
    $(document).on('click', '.add-btn', function () {
        $('.form-surat')[0].reset();
        $('.form-surat').removeClass('was-validated');
        $('#modal-surat').modal('show');
        $('.modal-title').text('Form Tambah Memorandum');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('select[name="approval_id"]').val('').trigger('change');
        $('textarea[name="isi_surat"]').val('');
        getNomorSurat();

        $('.lampiran-current').html('');
        $('#preview-images-surat').empty();
        $('#lampiran').val('');
        lampiranHapusList = [];
        fileBufferSurat = new DataTransfer();
        editor.setData('');

        InitSelect2($("select[name='approval_id']"), {
            url: "{{ route('get-select-approval') }}",
            dropdownParent: $("#modal-surat")
        });

    });


    $(document).on('click', '.lampiran-thumb-remove', function () {
        var path = $(this).data('path');
        var $wrap = $(this).closest('.lampiran-thumb-wrap');

        if ($wrap.hasClass('marked-remove')) {
            // batal hapus
            lampiranHapusList = lampiranHapusList.filter(p => p !== path);
            $wrap.removeClass('marked-remove');
            $(this).attr('title', 'Hapus lampiran ini');
        } else {
            lampiranHapusList.push(path);
            $wrap.addClass('marked-remove');
            $(this).attr('title', 'Batal hapus');
        }
    });

    // Save Surat (create / update)
    $(document).on('click', '.save-btn', function (e) {
        e.preventDefault();
        var id = $('input[name="id"]').val();
        var form = document.querySelector('.form-surat');

        if (!form) {
            Alert('warning', 'Form surat tidak ditemukan.');
            return;
        }

        // VALIDASI CKEDITOR
        var isiSurat = editor.getData().trim();
        if (!isiSurat || isiSurat === '<p>&nbsp;</p>') {
            Alert('warning', 'Isi surat wajib diisi.');
            editor.editing.view.focus();
            return;
        }

        // Masukkan isi CKEditor ke textarea
        $('#editable').val(isiSurat);
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            var invalidInput = form.querySelector(
                '.form-control:invalid, .form-select:invalid'
            );
            if (invalidInput) {
                invalidInput.focus();
            }
            return;
        }

        var url;
        var type = 'POST';
        if (id) {
            url = "{{ route('surat.update', ':id') }}";
            url = url.replace(':id', id);
        } else {
            url = "{{ route('surat.create') }}";
        }

        // FORMDATA
        var formData = new FormData(form);
        // Hapus lampiran bawaan form
        formData.delete('lampiran[]');

        // Tambahkan file dari buffer
        if (
            typeof fileBufferSurat !== 'undefined' &&
            fileBufferSurat.files.length > 0
        ) {
            Array.from(fileBufferSurat.files).forEach(function (file) {
                formData.append(
                    'lampiran[]',
                    file,
                    file.name
                );
            });
        }

        // Lampiran yang dihapus
        if (
            typeof lampiranHapusList !== 'undefined' &&
            lampiranHapusList.length > 0
        ) {

            lampiranHapusList.forEach(function (path) {
                formData.append('hapus_lampiran[]', path);
            });
        }

        // Mode UPDATE
        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            type: type,
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',

            beforeSend: function () {
                $('.save-btn').html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...').prop('disabled', true);
            },

            success: function (res) {
                if (res.success) {
                    Alert('success', res.message);
                    $('#modal-surat').modal('hide');

                    // Reset
                    lampiranHapusList = [];
                    fileBufferSurat = new DataTransfer();
                    $('#preview-images-surat').empty();
                    $('#lampiran').val('');
                    form.reset();
                    form.classList.remove('was-validated');
                    $('input[name="id"]').val('');
                    // Reset CKEditor
                    editor.setData('');
                    // Refresh tabel
                    $tableSurat.bootstrapTable('refresh');
                } else {

                    Alert(
                        'warning',
                        res.message || 'Data gagal disimpan.'
                    );
                }
            },

            error: function (xhr) {
                console.log('STATUS:', xhr.status);
                console.log('RESPONSE:', xhr.responseText);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        var firstError =
                            Object.values(errors)[0][0];
                        Alert('warning', firstError);
                    }

                } else if (xhr.status === 419) {
                    Alert(
                        'warning',
                        'Session telah berakhir. Silakan login kembali.'
                    );

                } else if (xhr.status === 500) {
                    var message =
                        xhr.responseJSON?.message ||
                        'Terjadi kesalahan pada server.';
                    Alert('error', message);
                } else {
                    Alert(
                        'info',
                        'Silahkan hubungi IT!'
                    );
                }
            },

            complete: function () {
                $('.save-btn').html('<span class="fa fa-check"></span> Simpan').prop('disabled', false);

            }
        });

    });


    // Page Load Event
    $(function () {
        initTable();
    });

    // Table Surat
    function initTable() {
        $tableSurat.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id',
            uniqueId: 'id',
            sidePagination: 'client',
            maintainSelected: true,
            pagination: true,
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showExport: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['excel', 'pdf'],
            url: "{{ route('surat.view') }}",
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
                    field: 'tanggal',
                    sortable: true,
                    align: 'center',
                },
                {
                    field: 'no_surat',
                    sortable: true,
                },
                {
                    field: 'nama_aproval',
                    sortable: true,
                },
                {
                    field: 'perihal',
                    sortable: true,
                },
                {
                    field: 'status',
                    sortable: true,
                    align: "center",
                    width: '50px',
                    events: window.updateStatus,
                    formatter: function (value, row, index) {
                        if (value === 'Draft') {
                            return '<button class="btn btn-danger btn-pill btn-xs btn-draft" ' +
                                'style="width: 80px;">Draft</button>';
                        }

                        else if (value === 'Approve') {
                            return '<button class="btn btn-primary btn-pill btn-xs btn-approve" ' +
                                'style="width: 80px;">Approve</button>';
                        }

                        else {
                            return '<button class="btn btn-secondary btn-pill btn-xs" ' +
                                'style="width: 80px;">' +
                                (value || '-') +
                                '</button>';
                        }
                    }
                },
                {
                    field: 'created_at',
                    sortable: true,
                    visible: false,
                    align: "right",
                },
                {
                    title: 'Action',
                    field: 'action',
                    align: 'center',
                    width: '100px',
                    events: window.eventsSurat,
                    formatter: actionsFunctionSurat
                }
                ]
            ],
            error: function (xhr, status, error) {
                if (xhr.status == 400) {
                    $.notify({
                        icon: "fa fa-check",
                        title: error,
                        message: xhr.responseJSON.message,
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 2000,
                        showProgressbar: true,
                        timer: 300,
                        z_index: 1127,
                        animate: {
                            enter: "animated fadeInDown",
                            exit: "animated fadeOutUp",
                        },
                    });
                } else if (xhr.status == 500) {
                    $.notify({
                        icon: "icon-info-alt",
                        title: "Error",
                        message: "Silahkan hubungi IT Rumah Sakit!",
                    }, {
                        type: "danger",
                        allow_dismiss: true,
                        delay: 2000,
                        showProgressbar: true,
                        timer: 300,
                        z_index: 1127,
                        animate: {
                            enter: "animated fadeInDown",
                            exit: "animated fadeOutUp",
                        },
                    });
                }
            },
            responseHandler: function (res) {
                return res;
            }
        });
    }


    function actionsFunctionSurat(value, row, index) {
        var actions = [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu-' + row.id + '" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu-' + row.id + '">',
        ];

        if (row.status === 'Approve') {
            actions.push(
                '<a class="dropdown-item btn-detail" href="javascript:void(0)">' +
                '<i class="fa fa-eye text-info"></i> Status Surat' +
                '</a>'
            );
        }

        if (row.status === 'Draft') {
            actions.push(
                '<a class="dropdown-item btn-edit" href="javascript:void(0)">' +
                '<i class="fa fa-edit text-primary"></i> Edit' +
                '</a>',

                '<a class="dropdown-item btn-delete" href="javascript:void(0)">' +
                '<i class="fa fa-trash text-danger"></i> Hapus' +
                '</a>',
            );
        }

        actions.push(
            '<a class="dropdown-item btn-pdf" href="javascript:void(0)">' +
            '<i class="fa fa-file-pdf-o text-danger"></i> Export PDF' +
            '</a>',

            '</div>',
            '</div>'
        );

        return actions.join('');
    }

    // Handle events button actions
    window.eventsSurat = {
        'click .btn-lihat-lampiran': function (e, value, row, index) {

        },
        'click .btn-detail': function (e, value, row, index) {
            $('#modal-detail-surat').modal('show');
            $('.modal-title-surat').text('Status Surat');
            $('#modal-detail-surat').data('id-surat', row.id);

            // Loading
            $('#approvalWizard').html(`
            <div class="text-center w-100 py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <div class="mt-2">Memuat status approval...</div>
            </div>
            `);

            // Ambil data approval
            $.ajax({
                url: "{{ route('surat.viewapproval') }}",
                type: "GET",
                data: {
                    id_surat: row.id
                },

                success: function (response) {
                    let html = '';
                    if (!response || response.length === 0) {

                        $('#approvalWizard').html(`
                        <div class="text-center w-100 py-4 text-muted">
                        <i class="bi bi-info-circle fs-1"></i>
                        <div class="mt-2">
                        Belum terdapat data approval
                        </div>
                        </div>
                    `);

                        return;
                    }

                    response.forEach(function (item) {
                        let parentJabatan;
                        switch (String(item.parent_jabatan)) {
                            case '0': parentJabatan = 'Director'; break;
                            case '1': parentJabatan = 'Vice Director'; break;
                            case '2': parentJabatan = 'Head'; break;
                            default: parentJabatan = '-';
                        }

                        let approved =
                            item.tanggal_aproval !== null &&
                            item.tanggal_aproval !== undefined &&
                            String(item.tanggal_aproval).trim() !== '';

                        let stepClass = approved ? 'approved' : 'pending';
                        let icon = approved ? 'bi-check-lg' : 'bi-person';
                        let status = approved ? 'Terverifikasi' : 'Menunggu Approval';
                        let tanggal = '';
                        if (approved) {
                            let date = new Date(
                                String(item.tanggal_aproval).replace(' ', 'T')
                            );
                            let tanggalFormatted = '-';
                            if (!isNaN(date.getTime())) {
                                tanggalFormatted = date.toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit'
                                });
                            }

                            tanggal = `
                    <div class="approval-date">
                        ${tanggalFormatted}
                    </div>
                    `;
                        }

                        html += `
                <div class="approval-step ${stepClass}">
                    <div class="approval-icon"> <i class="bi ${icon}"></i></div>
                    <div class="approval-title"> ${parentJabatan} </div>
                    <div class="approval-name"> ${item.nama_pekerja ?? '-'} </div>
                    <div class="approval-status"> ${status} </div> 
                    ${tanggal} 
                </div>  `;
                    });
                    $('#approvalWizard').html(html);
                },

                error: function (xhr) {
                    console.log('ERROR AJAX:', xhr);
                    console.log('Response:', xhr.responseText);

                    $('#approvalWizard').html(`
                    <div class="text-center w-100 py-4 text-danger">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <div class="mt-2">
                    Gagal mengambil data approval
                    </div>
                    </div>
                    `);
                }
            });
        },
        'click .btn-pdf': function (e, value, row, index) {
            var url = "{{ route('surat.export-pdf', ':id') }}";
            url = url.replace(':id', row.id);
            window.open(url, '_blank');
        },
        'click .btn-edit': function (e, value, row, index) {
            $('.form-surat')[0].reset();
            $('#modal-surat').modal('show');
            $('.modal-title').text('Form Edit Memorandum');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="tanggal"]').val(row.tanggal);
            $('input[name="no_surat"]').val(row.no_surat);
            $('input[name="perihal"]').val(row.perihal);
            $('input[name="jumlah_lampiran"]').val(row.jumlah_lampiran);
            editor.setData(row.isi_surat);
            $('textarea[name="isi_surat"]').val(row.isi_surat);
            $('select[name="approval_id"]').val(row.approval_id).trigger('change');

            InitSelect2($("select[name='approval_id']"), {
                url: "{{ route('get-select-approval') }}",
                dropdownParent: $("#modal-surat"),
                initialValue: row.approval_id
            });

            $('#preview-images-surat').empty();
            fileBufferSurat = new DataTransfer();


            if (row.lampiran) {
                let lampiran = row.lampiran;
                if (typeof lampiran === 'string') {
                    try {
                        lampiran = JSON.parse(lampiran);
                    } catch (e) {
                        lampiran = [];
                    }
                }

                if (Array.isArray(lampiran)) {
                    lampiran.forEach(function (file, index) {
                        let fileURL = "/" + file;
                        let extension = file.split('.').pop().toLowerCase();
                        let preview = '';

                        // Gambar
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                            preview = `
                         <img src="${fileURL}"
                         style="width:100%; height:200px; object-fit:cover;">
                        `;

                            // PDF
                        } else if (extension === 'pdf') {

                            preview = `
                            <iframe src="${fileURL}"
                            style="width:100%; height:200px; border:none;">
                            </iframe>
                            `;

                            // File Word / Excel / lainnya
                        } else {
                            preview = `
                        <div class="d-flex flex-column align-items-center justify-content-center"
                        style="height:200px; background:#f8f9fa;">
                        <i class="fa fa-file fa-4x text-secondary mb-2"></i>
                        <span class="text-muted text-uppercase">
                            ${extension}
                        </span>
                    </div>
                `;
                        }

                        $('#preview-images-surat').append(`
                        <div class="col-md-4 mb-2 preview-lampiran-lama">
                        <div class="position-relative border rounded overflow-hidden">
                        ${preview}

                        <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-1">

                        <button type="button"
                        class="btn btn-primary btn-xs btn-preview-surat"
                        data-src="${fileURL}">
                        <i class="fa fa-eye"></i>
                        </button>

                        <button type="button"
                        class="btn btn-danger btn-xs btn-remove-surat"
                        data-index="${index}"
                        data-file="${file}">
                        <i class="fa fa-trash"></i>
                        </button>

                        </div>
                        </div>
                        </div>
                    `);

                    });
                }
            }

        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('surat.delete', ':id') }}";
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
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $tableSurat.bootstrapTable('refresh');
                    });
                }
            })
        }
    }

    // Window operateChange Status
    window.updateStatus = {
        'click .btn-draft': function (e, value, row, index) {
            Swal.fire({
                title: 'Konfirmasi Approve',
                text: 'Apakah Anda yakin ingin melakukan approve surat ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Approve',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('surat.update-status', ':id') }}";
                    url = url.replace(':id', row.id);
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            status: 'Approve',
                            table: 'surat',
                            approval_id: row.approval_id,
                            _token: "{{ csrf_token() }}"
                        },

                        beforeSend: function () {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },

                        success: function (res, status, xhr) {
                            Swal.close();
                            if (xhr.status === 200 && res.success === true) {
                                Alert('success', res.message);
                                $tableSurat.bootstrapTable('refresh');
                            } else {
                                Alert('warning', res.message);
                            }
                        },

                        error: function (xhr, status, error) {
                            Swal.close();
                            if (xhr.status === 400) {
                                Alert(
                                    'error',
                                    xhr.responseJSON?.message || 'Gagal melakukan Approve.'
                                );
                            } else if (xhr.status === 500) {
                                Alert('info', '<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!');

                            } else {
                                Alert('error', 'Terjadi kesalahan saat melakukan Approve.');
                            }
                        }
                    });
                }
            });
        },
        'click .btn-approve': function (e, value, row, index) {
            Swal.fire({
                icon: 'info',
                title: 'Data Sudah di Approve',
                text: 'Data surat ini sudah di Approve dan tidak dapat diubah kembali.',
                confirmButtonText: 'OK'
            });
        }
    };
</script>