<script type="text/javascript">
    // Variable Name
    var $tablePks = $("#table_pks");

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-pks"),
        allowClear: true
    });

    // filter status
    $(".select3").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        allowClear: true,
        width: "100%"
    });
    $('#filter-status').val('1').trigger('change');

    // Filter Tabel
    $('#filter-status').on('change', function () {
        $tablePks.bootstrapTable('refresh', {
            silent: true
        });
    });

    //tanggal
    $('.js-daterangepicker').datepicker({
        dateFormat: 'dd/mm/yyyy',
        range: true,
        multipleDates: true,
        multipleDatesSeparator: ' - ',
        autoClose: true,
        toggleSelected: false,
        clearButton: true,

        onSelect: function (formattedDate, date, inst) {

            if (!date || date.length < 2) return;

            let start = date[0];
            let end = date[1];

            $('#tgl_awal').val(formatDate(start));
            $('#tgl_akhir').val(formatDate(end));

            $tablePks.bootstrapTable('refresh', {
                pageNumber: 1
            });
        },

        onHide: function (inst) {
            if (!$('.js-daterangepicker').val()) {

                $('#tgl_awal').val(null);
                $('#tgl_akhir').val(null);

                $tablePks.bootstrapTable('refresh', {
                    pageNumber: 1
                });
            }
        }
    });


    // helper format dd/mm/yyyy (untuk tampilan datepicker)
    function formatDisplay(date) {
        let d = String(date.getDate()).padStart(2, '0');
        let m = String(date.getMonth() + 1).padStart(2, '0');
        let y = date.getFullYear();
        return `${d}/${m}/${y}`;
    }

    // helper format Y-m-d (untuk database)
    function formatDate(date) {
        let d = String(date.getDate()).padStart(2, '0');
        let m = String(date.getMonth() + 1).padStart(2, '0');
        let y = date.getFullYear();
        return `${y}-${m}-${d}`;
    }


    // onclick upload
    $('#btn-attach').on('click', function () {
        $('#lampiran').trigger('click');
    });

    //upload dokumen
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
        renderPreviewPDF(file);
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
        $('#modal-pks').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf', function () {
        fileBuffer = new DataTransfer();
        $('#lampiran').val('');
        $('#preview-images').empty();
    });

    // close modal
    $('#modal-preview-pdf').on('hidden.bs.modal', function () {
        $('#modal-pks').modal('show');
    });

    // Add Button
    $(document).on("click", ".add-btn", function () {
        $(".form-pks").removeClass("was-validated");
        $("#modal-pks").modal("show");
        $(".modal-title").text("Form Tambah PKS");
        $('.save-btn').show();
        $(".save-btn").html('<span class="fa fa-check"></span> Simpan').removeAttr("disabled");
        $('#preview-images').empty();
        $('input[name="id"]').val("");
        $('input[name="lampiran"]').val("");
        $('input[name="nomor_kontrak"]').val("");
        $('input[name="judul"]').val("");
        $('select[name="id_jenis_kontrak"]').val('').trigger('change');
        $('input[name="pihak"]').val("");
        $('input[name="tanggal_mulai"]').val("");
        $('input[name="tanggal_selesai"]').val("");
        $('#lampiran').val('');
        fileBuffer = new DataTransfer();

        InitSelect2($("select[name='id_jenis_kontrak']"), {
            url: "{{ route('get-select-jenis-kontrak') }}",
            dropdownParent: $("#modal-pks")
        });
    });


    // Save
    $(document).on('click', '.save-btn', function (event) {

        var id = $('input[name="id"]').val();
        var url, type;

        if (id) {
            url = "{{ route('legal.pks.update', ':id') }}".replace(':id', id);
            type = "POST"; // FormData harus POST
        } else {
            url = "{{ route('legal.pks.create') }}";
            type = "POST";
        }

        var forms = document.getElementsByClassName('form-pks');

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
                            $('#modal-pks').modal('hide');
                            $tablePks.bootstrapTable('refresh');
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
    $(function () {
        initTable();
    });


    // init table
    function initTable() {
        $tablePks.bootstrapTable("destroy").bootstrapTable({
            height: 500,
            locale: "en-US",
            search: true,
            // showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            showExport: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, "all"],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ["excel", "pdf"],
            url: "{{ route('legal.pks.view') }}",

            queryParams: function (params) {
                console.log("Filter:", $('#tgl_awal').val(), $('#tgl_akhir').val());

                return {
                    ...params,
                    tgl_awal: $('#tgl_awal').val() || null,
                    tgl_akhir: $('#tgl_akhir').val() || null,
                    status: $('#filter-status').val() || null
                };
            },

            columns: [{
                field: "id",
                sortable: true,
                align: "center",
                formatter: function (value, row, index) {
                    return index + 1;
                },
            },
            {
                field: "nomor_kontrak",
                sortable: true,
            },
            {
                field: "nama_jenis_kontrak",
                sortable: true,
            },
            {
                field: "judul",
                sortable: true,
            },
            {
                field: "pihak",
                sortable: true,
            },
            {
                field: "tanggal_mulai",
                sortable: true,
            },
            {
                field: "tanggal_selesai",
                sortable: true,
            },
            {
                field: "sisa_hari",
                sortable: true,
                align: "center",
                formatter: function (value, row, index) {

                    if (value <= 0) {
                        return '<button class="btn btn-secondary btn-sm">Kontrak Berakhir</button>';
                    }
                    else if (value <= 30) {
                        return '<button class="btn btn-pill btn-danger btn-xs">' + value +' Hari</button>';
                    }
                    else if (value <= 90) {
                        return '<button class="btn btn-pill btn-warning btn-xs">' + value + ' Hari</button>';
                    }
                    else {
                        return '<button class="btn btn-pill btn-success btn-xs">' + value + ' Hari</button>';
                    }

                }
            },
            {
                width: '100%',
                field: 'status1',
                sortable: true,
                events: window.updateStatusPks,
                formatter: function (value, row, index) {
                    return [
                        '<div class="media-body text-center switch-sm icon-state">',
                        '<label class="switch">',
                        '<input type="checkbox" class="update-status" ' + (row.status ===
                            '1' ? 'checked' : '') + '>',
                        '<span class="switch-state"></span>',
                        '</label>',
                        '</div>'
                    ].join("");
                }
            },
            {
                field: "action",
                title: "Aksi",
                align: "center",
                formatter: actionsFunction,
                events: window.operateEvents,
            },
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
            responseHandler: function (data) {
                return data;
            },
        });
    }

    function actionsFunction(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            "</button>",
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
            '<a class="dropdown-item btn-print" href="javascript:void(0)"><i class="fa fa-print text-primary"></i> Print</a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            "</div>",
            "</div>",
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-print': function (e, value, row, index) {
            if (row.file) {
                var fileUrl = '{{ url("uploads/images/pks") }}/' + row.file;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-pks').modal('show');
            $('.modal-title').text('Form Edit PKS');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');

            $('input[name="id"]').val(row.id);
            $('input[name="lampiran"]').val(row.lampiran);
            $('input[name="nomor_kontrak"]').val(row.nomor_kontrak);
            $('input[name="judul"]').val(row.judul);
            $('input[name="pihak"]').val(row.pihak);
            $('input[name="tanggal_mulai"]').val(row.tanggal_mulai);
            $('input[name="tanggal_selesai"]').val(row.tanggal_selesai);
            $('#lampiran').val('');

            InitSelect2($("select[name='id_jenis_kontrak']"), {
                url: "{{ route('get-select-jenis-kontrak') }}",
                dropdownParent: $("#modal-pks"),
                initialValue: row.id_jenis_kontrak
            });
        },
        "click .btn-delete": function (e, value, row, index) {
            var url = "{{ route('legal.pks.delete', ':id') }}";
            url = url.replace(":id", row.id);
            Swal.fire({
                icon: "warning",
                title: "Peringatan",
                text: "Anda yakin ingin menghapus data ini?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert("success", res.message);
                            } else {
                                Alert("warnig", res.message);
                            }
                        },
                    }).done(function () {
                        $tablePks.bootstrapTable("refresh");
                    });
                }
            });
        },
    };

    // Window operateChange Status Pks
    window.updateStatusPks = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('legal.pks.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'tbl_pks',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tableKota.bootstrapTable('refresh');
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 400) {
                        Alert('error', xhr.responseJSON.message);
                    } else if (xhr.status == 500) {
                        Alert('info',
                            "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                        );
                    }
                }
            });
        }
    }

</script>