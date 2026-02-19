<script type="text/javascript">
    // Tabel
    var $tablePerizinan = $('#table_perizinan');

    // filter status
    $(".select3").select2({
        placeholder: "--- Pilih Salah Satu ---",
        theme: "bootstrap-5",
        allowClear: true,
        width: "100%"
    });

    // Filter Tabel
    $('#filter-status').on('change', function () {
        $tablePerizinan.bootstrapTable('refresh', {
            silent: true
        });
    });
    $('#filter-status').val('1').trigger('change');


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

            // Jika tombol clear diklik
            if (!formattedDate) {

                $('#tgl_awal').val(null);
                $('#tgl_akhir').val(null);

                $tablePerizinan.bootstrapTable('refresh', {
                    pageNumber: 1
                });

                // Hilangkan autofocus setelah clear
                setTimeout(function () {
                    $('.js-daterangepicker').blur();
                }, 100);

                return;
            }

            if (!date || date.length < 2) return;

            let start = date[0];
            let end = date[1];

            $('#tgl_awal').val(formatDate(start));
            $('#tgl_akhir').val(formatDate(end));

            $tablePerizinan.bootstrapTable('refresh', {
                pageNumber: 1
            });
        },

        onHide: function (inst) {
            setTimeout(function () {
                $('.js-daterangepicker').blur();
            }, 100);
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
        $('#upload').trigger('click');
    });

    //upload dokumen
    let fileBuffer = new DataTransfer();
    $(document).on('change', '#upload', function () {
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
        $('#modal-perizinan').modal('hide');
    });

    // hapus dokumen
    $(document).on('click', '.btn-remove-pdf', function () {
        // fileBuffer = new DataTransfer();
        $('#upload').val('');
        $('#preview-images').empty();
    });

    // close modal
    $('#modal-preview-pdf').on('hidden.bs.modal', function () {
        $('#modal-perizinan').modal('show');
    });

    // Open Modal perizinan
    $(document).on('click', '.add-btn', function () {
        $('.form-perizinan').removeClass('was-validated');
        $('#modal-perizinan').modal('show');
        $('.modal-title').text('Form Tambah Perizinan');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('#preview-images').empty();
        $('input[name="id"]').val('');
        $('input[name="nomor_perizinan"]').val('');
        $('input[name="jenis_perizinan"]').val('');
        $('input[name="status"]').val('');
        $('.form-perizinan input[name="tgl_awal"]').val('');
        $('.form-perizinan input[name="tgl_akhir"]').val('');
        $('#upload').val('');
    });

    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        var url, type;
        if (id) {
            url = "{{ route('legal.perizinan.update', ':id') }}";
            url = url.replace(':id', id);
            type = "POST";
        } else {
            url = "{{ route('legal.perizinan.create') }}";
            type = "POST";
        }
        var forms = document.getElementsByClassName('form-perizinan');
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
                            $('#modal-perizinan').modal('hide');
                            $tablePerizinan.bootstrapTable('refresh');
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


    // Table perizinan
    function initTable() {
        $tablePerizinan.bootstrapTable('destroy').bootstrapTable({
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
            // showToggle: true,
            showExport: true,
            pagination: true,
            maintainSelected: true,
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
            url: "{{ route('legal.perizinan.view') }}",
            queryParams: function (params) {
                console.log("Filter:", $('#tgl_awal').val(), $('#tgl_akhir').val());

                return {
                    ...params,
                    tgl_awal: $('#tgl_awal').val() || null,
                    tgl_akhir: $('#tgl_akhir').val() || null,
                    status: $('#filter-status').val() || null
                };
            },
            columns: [
                [{
                    field: "id",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    field: 'nomor_perizinan',
                    sortable: true,
                },
                {
                    field: 'jenis_perizinan',
                    sortable: true,
                },
                {
                    field: 'tgl_awal',
                    sortable: true,
                },
                {
                    field: 'tgl_akhir',
                    sortable: true,
                },
                {
                    field: "sisa_hari",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row, index) {

                        if (value <= 0) {
                            return '<button class="btn btn-pill btn-xs" style="background-color: gray !important; border-color: gray !important; color: white;">Berakhir</button>';
                        } else if (value <= 90) {
                            return '<button class="btn btn-pill btn-danger btn-xs">' + value +
                                ' Hari</button>';
                        } else if (value <= 180) {
                            return '<button class="btn btn-pill btn-warning btn-xs">' + value +
                                ' Hari</button>';
                        } else {
                            return '<button class="btn btn-pill btn-success btn-xs">' + value +
                                ' Hari</button>';
                        }

                    }
                },
                {
                    width: '100%',
                    field: 'status1',
                    sortable: true,
                    events: window.updateStatusPerizinan,
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
                    title: 'Action',
                    field: 'action',
                    align: 'center',
                    events: window.eventsPerizinan,
                    formatter: actionsFunctionPerizinan
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

    function actionsFunctionPerizinan(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-print" href="javascript:void(0)"><i class="fa fa-print text-warning"></i> Print</a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsPerizinan = {
        'click .btn-print': function (e, value, row, index) {
            if (row.upload) {
                var fileUrl = '{{ url('uploads/legal/perizinan') }}/' + row.upload;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },

        'click .btn-edit': function (e, value, row, index) {
            $('#modal-perizinan').modal('show');
            $('.modal-title').text('Form Edit Perizinan');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');

            $('input[name="id"]').val(row.id);
            $('input[name="nomor_perizinan"]').val(row.nomor_perizinan);
            $('input[name="jenis_perizinan"]').val(row.jenis_perizinan);

            $('.form-perizinan input[name="tgl_awal"]').val(row.tgl_awal);
            $('.form-perizinan input[name="tgl_akhir"]').val(row.tgl_akhir);


            // $('input[name="upload"]').val(row.upload);
            $('#preview-images').empty();
            if (row.upload) {
                let fileURL = "/uploads/legal/perizinan/" + row.upload;
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
            console.log(row);

        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('legal.perizinan.delete', ':id') }}";
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
                        $tablePerizinan.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status perizinan
    window.updateStatusPerizinan = {
        'click .update-status': function (e, value, row, index) {
            var url = "{{ route('legal.perizinan.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'polis',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tablePerizinan.bootstrapTable('refresh');
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


    window.addEventListener("load", function () {
        fetch("{{ route('legal.perizinan.notify') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
            .then(res => res.json())
            .then(data => {

                console.log("DATA:", data);

                if (!Array.isArray(data) || data.length === 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Menu Perizinan',
                        text: 'Tidak ada Perizinan yang akan berakhir dalam 90 hari.',
                        showCloseButton: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                    return;
                }

                let html = `
        <div style="max-height:400px; overflow-y:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px">
            <thead>
                <tr style="background:#f8f9fa">
                    <th style="padding:6px; border:1px solid #ddd">Nomor Perizinan</th>
                    <th style="padding:6px; border:1px solid #ddd">Jenis Perizinan</th>
                    <th style="padding:6px; border:1px solid #ddd">Berakhir</th>
                    <th style="padding:6px; border:1px solid #ddd">Sisa Hari</th>
                </tr>
            </thead>
            <tbody>
        `;
                data.forEach(item => {

                    let warna = "#0d6efd";
                    if (item.sisa_hari <= 7) warna = "#dc3545";
                    else if (item.sisa_hari <= 30) warna = "#ffc107";

                    html += `
                <tr>
                    <td style="padding:6px; border:1px solid #ddd" align="left">${item.nomor_perizinan}</td>
                    <td style="padding:6px; border:1px solid #ddd" align="left">${item.jenis_perizinan}</td>
                    <td style="padding:6px; border:1px solid #ddd" align="left">${item.tgl_akhir}</td>
                    <td style="padding:6px; border:1px solid #ddd; font-weight:bold; color:${warna}">
                        ${item.sisa_hari} hari
                    </td>
                </tr>
            `;
                });

                html += "</tbody></table></div>";

                Swal.fire({
                    icon: 'warning',
                    title: '⚠ Perizinan Akan Berakhir',
                    html: html,
                    width: 850,
                    confirmButtonText: "Mengerti",
                    showCloseButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });

            })
            .catch(err => console.log("ERROR:", err));
    });
</script>