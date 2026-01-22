<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-gaji"),
        allowClear: true
    });

    //tanggal
    // Inisialisasi datepicker
    $('.js-daterangepicker').datepicker({
        dateFormat: 'dd/mm/yyyy',
        range: true,
        multipleDates: true,
        multipleDatesSeparator: ' - ',
        autoClose: true,
        toggleSelected: false,

        onSelect: function (formattedDate, date) {
            // pastikan sudah pilih 2 tanggal
            if (!date || date.length < 2) return;

            let start = date[0];
            let end = date[1];

            $('#tgl_awal').val(formatDate(start));
            $('#tgl_akhir').val(formatDate(end));
            $tableGaji.bootstrapTable('refresh');
        }
    });

    // Tanggal sekarang
    let now = new Date();

    // Tanggal awal satu bulan kebelakang
    let firstDay = new Date(now.getFullYear(), now.getMonth() - 1, 1);

    // Tanggal terakhir hari ini
    let lastDay = new Date(now);

    // Format tampilkan dd/mm/yyyy
    function formatDisplay(date) {
        let d = String(date.getDate()).padStart(2, '0');
        let m = String(date.getMonth() + 1).padStart(2, '0');
        let y = date.getFullYear();
        return `${d}/${m}/${y}`;
    }

    // Format untuk input hidden yyyy-mm-dd
    function formatDate(date) {
        let d = String(date.getDate()).padStart(2, '0');
        let m = String(date.getMonth() + 1).padStart(2, '0');
        let y = date.getFullYear();
        return `${y}-${m}-${d}`;
    }

    // Set default value datepicker dan input hidden
    $('.js-daterangepicker').val(
        formatDisplay(firstDay) + ' - ' + formatDisplay(lastDay)
    );

    $('#tgl_awal').val(formatDate(firstDay));
    $('#tgl_akhir').val(formatDate(lastDay));



    // Tabel
    var $tableGaji = $('#table_gaji');

    // Open Modal Gaji
    $(document).on('click', '.add-btn', function () {
        $('.form-gaji').removeClass('was-validated');
        $('#modal-gaji').modal('show');
        $('.modal-title').text('Form Tambah Slip Gaji');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="bulan"]').val('');
        $('#upload_gaji').val('');
    });

    // Save 
    $(document).on('click', '.save-btn', function (e) {
        e.preventDefault();
        const form = $('.form-gaji')[0];
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        let formData = new FormData(form);

        $.ajax({
            url: "{{ route('sdm.gaji.create') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // ✅ TAMBAHKAN INI
            },

            beforeSend: function () {
                $('.save-btn')
                    .html('<span class="spinner-border spinner-border-sm"></span>')
                    .prop('disabled', true);
            },

            success: function (res) {
                if (res.success) {
                    Alert('success', res.message);
                    $('#modal-gaji').modal('hide');
                    $tableGaji.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },

            error: function (xhr) {
                Alert('error', xhr.responseJSON?.message ?? 'Gagal import file');
            },

            complete: function () {
                $('.save-btn')
                    .html('Simpan')
                    .prop('disabled', false);
            }
        });
    });



    // Hapus All (Bulk Delete)
    $(document).on('click', '.hapus-all-btn', function () {
        let rows = $tableGaji.bootstrapTable('getSelections');
        if (!rows.length) {
            Swal.fire('Peringatan', 'Pilih data terlebih dahulu', 'warning');
            return;
        }

        let ids = rows.map(r => r.id);

        Swal.fire({
            title: 'Yakin hapus data?',
            text: `${ids.length} data akan dihapus`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then(result => {
            if (result.isConfirmed) {
                $.post("{{ route('sdm.gaji.deleteMultiple') }}", {
                    ids: ids,
                    _token: "{{ csrf_token() }}",
                }).done(function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                        $tableGaji.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                }).fail(function () {
                    Alert('error', 'Gagal menghapus data');
                });
            }
        });
    });

    // Enable/Disable Button Hapus All
    function toggleDeleteButton() {
        $('.hapus-all-btn').prop(
            'disabled',
            !$tableGaji.bootstrapTable('getSelections').length
        );
    }

    $('.hapus-all-btn').prop('disabled', true);

    $tableGaji.on(
        'check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table load-success.bs.table',
        toggleDeleteButton
    );

    // Page Load Event
    $(function () {
        initTable();
    });

    // Table Gaji
    function initTable() {
        $tableGaji.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            showColumns: true,
            showPaginationSwitch: true,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            url: "{{ route('sdm.gaji.view') }}",
            queryParams: function (params) {
                return {
                    limit: params.limit,
                    offset: params.offset,
                    search: params.search,

                    tgl_awal: $('#tgl_awal').val(),
                    tgl_akhir: $('#tgl_akhir').val()
                };
            },
            columns: [
                {
                    field: 'state',
                    checkbox: true,
                    align: 'center',
                    valign: 'middle'
                },
                {
                    field: 'nomor_pekerja',
                    title: 'Nomor Pekerja',
                    sortable: true
                },
                {
                    field: 'bulan',
                    title: 'Bulan',
                    sortable: true
                },
                {
                    field: 'action',
                    title: 'Aksi',
                    align: 'center',
                    valign: 'middle',
                    clickToSelect: false,
                    events: window.eventsGaji,
                    formatter: actionsFunctionGaji
                }
            ],

            responseHandler: function (res) {
                return res;
            }
        });
    }

    function actionsFunctionGaji(value, row, index) {
        return `
        <button class="btn btn-xs btn-outline-success btn-print" title="Print"> 
            <i class="fa fa-print"></i> Print
        </button>
    `;
    }

    // Handle events button actions
    window.eventsGaji = {
        'click .btn-print': function (e, value, row, index) {
            if (row.file) {
                var fileUrl = '{{ url("/") }}/' + row.file;
                window.open(fileUrl, '_blank');
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },

        'click .btn-download': function (e, value, row, index) {
            if (row.file) {
                var fileUrl = '{{ url("/") }}/' + row.file;
                var link = document.createElement('a');
                link.href = fileUrl;
                link.download = 'Slip_Gaji_' + row.nomor_pekerja + '_' + row.bulan.replace(/\//g, '-') + '.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                Alert('error', 'File tidak ditemukan');
            }
        },

        'click .btn-delete': function (e, value, row, index) {
            Swal.fire({
                title: 'Yakin hapus data?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('sdm.gaji.delete') }}",
                        type: "POST",
                        data: {
                            id: row.id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            if (res.success) {
                                Alert('success', res.message);
                                $tableGaji.bootstrapTable('refresh');
                            } else {
                                Alert('error', res.message);
                            }
                        },
                        error: function () {
                            Alert('error', 'Gagal menghapus data');
                        }
                    });
                }
            });
        }
    }
</script>