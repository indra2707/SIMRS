<script type="text/javascript">
    // Tabel
    var $tableSurat = $('#table_surat');

    var lampiranHapusList = [];

    let fileBufferSurat = new DataTransfer();

    // select2 approval
    $(".select2").select2({
        placeholder: "--- Pilih Salah Satu ---",
        theme: "bootstrap-5",
        allowClear: true,
        width: "100%",
        dropdownParent: $("#modal-surat")
    });

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
        const $item = $(this).closest('.col-md-2');

        const removeIndex = $('#preview-images-surat')
            .children('.col-md-2')
            .index($item);

        const input = document.querySelector('input[name="lampiran[]"]');

        let newBuffer = new DataTransfer();

        Array.from(fileBufferSurat.files).forEach((file, i) => {
            if (i !== removeIndex) {
                newBuffer.items.add(file);
            }
        });

        fileBufferSurat = newBuffer;
        input.files = fileBufferSurat.files;

        // refresh preview
        $('#preview-images-surat').empty();
        Array.from(fileBufferSurat.files).forEach((file, index) => {
            renderPreviewSurat(file, index);
        });
    });

    // Tutup modal preview besar -> balik ke modal asal (form / detail / lampiran-list)
    $('#modal-preview-image-surat').on('hidden.bs.modal', function () {
        $(modalAsalPreviewSurat).modal('show');
    });

    // Open Modal Tambah Surat
    $(document).on('click', '.add-btn', function () {
        $('.form-surat')[0].reset();
        $('.form-surat').removeClass('was-validated');
        $('#modal-surat').modal('show');
        $('.modal-title').text('Form Tambah Surat');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('select[name="approval_id"]').val('').trigger('change');
        $('.lampiran-current').html('');
        $('.lampiran-lama-label, .lampiran-lama-wrap').hide();
        $('#preview-images-surat').empty();
        $('#lampiran').val('');
        $('input[name="no_surat"]').val('');
        lampiranHapusList = [];
        fileBufferSurat = new DataTransfer();
    });

    // Generate no surat otomatis berdasarkan tanggal
    $(document).on('click', '.btn-generate-no', function () {
        var tanggal = $('input[name="tanggal"]').val();

        if (!tanggal) {
            $.notify({
                icon: 'fa fa-warning',
                title: 'Warning',
                message: 'Pilih tanggal terlebih dahulu.'
            }, {
                type: 'warning'
            });
            return;
        }

        $.ajax({
            url: "{{ route('surat.generate-no-surat') }}",
            type: "GET",
            data: {
                tanggal: tanggal,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function () {
                $('.btn-generate-no').attr('disabled', true);
            },
            complete: function () {
                $('.btn-generate-no').removeAttr('disabled');
            },
            success: function (res, status, xhr) {
                if (xhr.status == 200 && res.success) {
                    $('input[name="no_surat"]').val(res.no_surat);
                } else {
                    Alert('warning', res.message);
                }
            },
            error: function (xhr) {
                if (xhr.status == 422) {
                    Alert('warning', 'Tanggal tidak valid.');
                } else {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            }
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
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        var url, type;
        if (id) {
            url = "{{ route('surat.update', ':id') }}";
            url = url.replace(':id', id);
            type = "POST";
        } else {
            url = "{{ route('surat.create') }}";
            type = "POST";
        }
        var forms = document.getElementsByClassName('form-surat');
        Array.prototype.filter.call(forms, function (form) {

            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid, .form-select:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {

                var formData = new FormData(form);

                formData.delete('lampiran[]');

                if (fileBufferSurat.files.length > 0) {
                    Array.from(fileBufferSurat.files).forEach(function (file) {
                        formData.append('lampiran[]', file, file.name);
                    });
                }

                lampiranHapusList.forEach(function (path) {
                    formData.append('hapus_lampiran[]', path);
                });

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
                            $('#modal-surat').modal('hide');
                            lampiranHapusList = [];
                            fileBufferSurat = new DataTransfer();
                            $('#preview-images-surat').empty();
                            $('#lampiran').val('');
                            $tableSurat.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-warning',
                                title: 'Warning',
                                message: res.message
                            }, {
                                type: 'warning'
                            });

                            form.classList.remove('was-validated');
                        }
                    },

                    error: function (xhr) {
                        if (xhr.status == 422) {
                            var errors = xhr.responseJSON.errors;
                            var firstError = Object.values(errors)[0][0];
                            Alert('warning', firstError);
                        } else {
                            Alert('info', 'Silahkan hubungi IT!');
                        }
                    }
                });
            }

            form.classList.add('was-validated');
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
                    field: 'perihal',
                    sortable: true,
                },
                {
                    field: 'nama_approver',
                    sortable: true,
                    align: 'center',
                },
                {
                    field: 'lampiran_count',
                    title: 'Lampiran',
                    align: 'center',
                    events: window.eventsSurat,
                    formatter: function (value, row, index) {
                        if (!row.lampiran_count || row.lampiran_count == 0) {
                            return '-';
                        }
                        var label = row.lampiran_count > 1 ?
                            'Lihat (' + row.lampiran_count + ')' :
                            'Lihat';
                        return '<a href="javascript:void(0)" class="btn-lihat-lampiran">' +
                            '<span class="fa fa-image text-primary"></span> ' + label + '</a>';
                    }
                },
                {
                    field: 'created_at',
                    sortable: true,
                    align: 'center',
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
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-detail" href="javascript:void(0)"><i class="fa fa-eye text-info"></i> Detail</a>',
            '<a class="dropdown-item btn-pdf" href="javascript:void(0)"><i class="fa fa-file-pdf-o text-danger"></i> Export PDF</a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    
    function renderLampiranThumbs(lampiranArr, withRemoveButton, modalAsal) {
        if (!lampiranArr || lampiranArr.length === 0) {
            return withRemoveButton ? '' : '<p class="text-muted mb-0">Tidak ada lampiran.</p>';
        }

        return lampiranArr.map(function (path) {
            var removeBtn = withRemoveButton ?
                '<button type="button" class="lampiran-thumb-remove" data-path="' + path +
                '" title="Hapus lampiran ini">&times;</button>' :
                '';

            return '<div class="lampiran-thumb-wrap" data-path="' + path + '">' +
                '<img src="/storage/' + path + '" class="btn-preview-surat-tersimpan" ' +
                'data-src="/storage/' + path + '" data-modal-asal="' + modalAsal + '">' +
                removeBtn +
                '</div>';
        }).join('');
    }

    // Handle events button actions
    window.eventsSurat = {
        'click .btn-lihat-lampiran': function (e, value, row, index) {
            $('.lampiran-view-list').html(
                renderLampiranThumbs(row.lampiran, false, '#modal-lampiran-surat')
            );
            $('#modal-lampiran-surat').modal('show');
        },
        'click .btn-detail': function (e, value, row, index) {
            $('.detail-tanggal').text(row.tanggal);
            $('.detail-no-surat').text(row.no_surat);
            $('.detail-perihal').text(row.perihal);
            $('.detail-approval').text(row.nama_approver ?? '-');
            $('.detail-isi-surat').text(row.isi_surat);
            $('.detail-lampiran').html(renderLampiranThumbs(row.lampiran, false, '#modal-detail-surat'));
            $('#modal-detail-surat').modal('show');
        },
        'click .btn-pdf': function (e, value, row, index) {
            var url = "{{ route('surat.export-pdf', ':id') }}";
            url = url.replace(':id', row.id);
            window.open(url, '_blank');
        },
        'click .btn-edit': function (e, value, row, index) {
            $('.form-surat')[0].reset();
            $('#modal-surat').modal('show');
            $('.modal-title').text('Form Edit Surat');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="tanggal"]').val(row.tanggal_raw ?? row.tanggal);
            $('input[name="no_surat"]').val(row.no_surat);
            $('input[name="perihal"]').val(row.perihal);
            $('textarea[name="isi_surat"]').val(row.isi_surat);
            $('select[name="approval_id"]').val(row.approval_id).trigger('change');

            lampiranHapusList = [];
            fileBufferSurat = new DataTransfer();
            $('#preview-images-surat').empty();
            $('#lampiran').val('');

            $('.lampiran-lama-label, .lampiran-lama-wrap').toggle((row.lampiran || []).length > 0);
            $('.lampiran-current').html(renderLampiranThumbs(row.lampiran, true, '#modal-surat'));
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
</script>