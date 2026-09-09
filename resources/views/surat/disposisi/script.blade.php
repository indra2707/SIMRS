<script type="text/javascript">
    // Tabel
    var $tableDisposisi = $('#table_disposisi');

    // Modal mana yang harus dibuka lagi setelah modal preview besar ditutup
    var modalAsalPreviewDisposisi = '#modal-detail-disposisi';

    var labelTingkat = {
        'R': 'Rahasia',
        'P': 'Penting',
        'S': 'Segera',
        'B': 'Biasa',
    };

    // Page Load Event
    $(function () {
        initTableDisposisi();
    });

    function initTableDisposisi() {
        $tableDisposisi.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id_detail',
            uniqueId: 'id_detail',
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
            url: "{{ route('surat.disposisi.view') }}",
            columns: [
                [{
                    field: "id_detail",
                    sortable: true,
                    align: "center",
                    width: '50px',
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    field: 'no_agenda',
                    sortable: true,
                    align: 'center',
                    formatter: function (value) {
                        return value || '-';
                    }
                },
                {
                    field: 'no_surat',
                    sortable: true,
                },
                {
                    field: 'tanggal',
                    sortable: true,
                    align: 'center',
                },
                {
                    field: 'perihal',
                    sortable: true,
                },
                {
                    field: 'nama_pengirim',
                    title: 'Dari',
                    sortable: true,
                    formatter: function (value) {
                        return value || '-';
                    }
                },
                {
                    field: 'tingkat_surat',
                    title: 'Tingkat',
                    align: 'center',
                    formatter: function (value) {
                        if (!value) return '-';
                        return '<span class="badge badge-tingkat-' + value + '">' +
                            (labelTingkat[value] || value) + '</span>';
                    }
                },
                {
                    field: 'tindakan_action',
                    title: 'Tindakan Diminta',
                    align: 'center',
                    formatter: function (value, row) {
                        var badges = [];
                        if (row.tindakan_action) badges.push('<span class="badge bg-primary badge-tindakan">Action</span>');
                        if (row.tindakan_tanggapan) badges.push('<span class="badge bg-info badge-tindakan">Tanggapan</span>');
                        if (row.tindakan_info) badges.push('<span class="badge bg-secondary badge-tindakan">Info</span>');
                        if (row.tindakan_file) badges.push('<span class="badge bg-dark badge-tindakan">File</span>');
                        return badges.length ? badges.join(' ') : '<span class="text-muted">-</span>';
                    }
                },
                {
                    field: 'status',
                    title: 'Status',
                    align: 'center',
                    formatter: function (value) {
                        var badgeClass = 'bg-secondary';
                        if (value === 'Menunggu') badgeClass = 'bg-warning text-dark';
                        if (value === 'Dibaca') badgeClass = 'bg-info text-dark';
                        if (value === 'Selesai') badgeClass = 'bg-success';
                        return '<span class="badge ' + badgeClass + '">' + value + '</span>';
                    }
                },
                {
                    title: 'Action',
                    field: 'action',
                    align: 'center',
                    width: '180px',
                    events: window.eventsDisposisi,
                    formatter: actionsFunctionDisposisi
                }
                ]
            ],
            error: function (xhr, status, error) {
                if (xhr.status == 400 || xhr.status == 401) {
                    $.notify({
                        icon: "fa fa-warning",
                        title: "Peringatan",
                        message: xhr.responseJSON.message,
                    }, {
                        type: "warning",
                        allow_dismiss: true,
                        delay: 3000,
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

    function actionsFunctionDisposisi(value, row, index) {
        var actions = [
            '<button type="button" class="btn btn-info btn-xs btn-lihat-disposisi" title="Lihat Detail">',
            '<i class="fa fa-eye"></i> Lihat',
            '</button> ',
        ];

        if (row.status !== 'Selesai') {
            actions.push(
                '<button type="button" class="btn btn-success btn-xs btn-paraf-disposisi" title="Paraf / Selesai">',
                '<i class="fa fa-check"></i> Paraf',
                '</button>'
            );
        }

        return actions.join("");
    }

    // Render thumbnail lampiran (klik untuk perbesar)
    function renderLampiranThumbsDisposisi(lampiranArr) {
        if (!lampiranArr || lampiranArr.length === 0) {
            return '<p class="text-muted mb-0">Tidak ada lampiran.</p>';
        }

        return lampiranArr.map(function (path) {
            // Sesuaikan base path ini dengan lokasi penyimpanan lampiran surat Anda
            var url = '/uploads/surat/memo/' + path.split('/').pop();

            return '<div class="lampiran-thumb-wrap">' +
                '<img src="' + url + '" class="btn-preview-disposisi" data-src="' + url + '">' +
                '</div>';
        }).join('');
    }

    function renderTindakanBadges(row) {
        var badges = [];
        if (row.tindakan_action) badges.push('<span class="badge bg-primary badge-tindakan">Action</span>');
        if (row.tindakan_tanggapan) badges.push('<span class="badge bg-info badge-tindakan">Tanggapan</span>');
        if (row.tindakan_info) badges.push('<span class="badge bg-secondary badge-tindakan">Info</span>');
        if (row.tindakan_file) badges.push('<span class="badge bg-dark badge-tindakan">File</span>');
        return badges.length ? badges.join(' ') : '<span class="text-muted">-</span>';
    }

    // Handle events button actions
    window.eventsDisposisi = {
        'click .btn-lihat-disposisi': function (e, value, row, index) {
            $('.detail-disp-no-agenda').text(row.no_agenda || '-');
            $('.detail-disp-tanggal').text(row.tanggal);
            $('.detail-disp-no-surat').text(row.no_surat);
            $('.detail-disp-perihal').text(row.perihal);
            $('.detail-disp-pengirim').text(row.nama_pengirim ?? '-');
            $('.detail-disp-jabatan').text(row.nama_jabatan ?? '-');
            $('.detail-disp-tingkat').html(
                row.tingkat_surat ?
                '<span class="badge badge-tingkat-' + row.tingkat_surat + '">' +
                (labelTingkat[row.tingkat_surat] || row.tingkat_surat) + '</span>' :
                '-'
            );
            $('.detail-disp-tindakan').html(renderTindakanBadges(row));
            $('.detail-disp-catatan').text(row.catatan_disposisi || 'Tidak ada catatan.');
            $('.detail-disp-isi-surat').text(row.isi_surat ?? '-');
            $('.detail-disp-lampiran').html(renderLampiranThumbsDisposisi(row.lampiran));
            $('#modal-detail-disposisi').modal('show');

            // Tandai dibaca otomatis (kalau masih 'Menunggu')
            if (row.status === 'Menunggu') {
                var url = "{{ route('surat.disposisi.dibaca', ':id') }}";
                url = url.replace(':id', row.id_detail);
                $.post(url, { _token: "{{ csrf_token() }}" }, function () {
                    $tableDisposisi.bootstrapTable('refresh');
                });
            }
        },
        'click .btn-paraf-disposisi': function (e, value, row, index) {
            $('.form-paraf-disposisi')[0].reset();
            $('input[name="id_detail"]').val(row.id_detail);
            $('#modal-paraf-disposisi').modal('show');
        }
    };

    // Lihat foto besar (dari modal Detail)
    $(document).on('click', '.btn-preview-disposisi', function () {
        modalAsalPreviewDisposisi = '#modal-detail-disposisi';
        $('#preview-large-disposisi').attr('src', $(this).data('src'));
        $('#modal-preview-image-disposisi').modal('show');
        $('#modal-detail-disposisi').modal('hide');
    });

    // Tutup modal preview besar -> balik ke modal asal
    $('#modal-preview-image-disposisi').on('hidden.bs.modal', function () {
        $(modalAsalPreviewDisposisi).modal('show');
    });

    // Submit Paraf / Tandai Selesai
    $(document).on('click', '.btn-submit-paraf-disposisi', function () {
        var id = $('input[name="id_detail"]').val();
        var catatan = $('#catatan_tindak_lanjut').val();

        var url = "{{ route('surat.disposisi.paraf', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                catatan_tindak_lanjut: catatan,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function () {
                $('.btn-submit-paraf-disposisi').attr('disabled', true);
            },
            complete: function () {
                $('.btn-submit-paraf-disposisi').removeAttr('disabled');
            },
            success: function (res, status, xhr) {
                if (xhr.status == 200 && res.success) {
                    Alert('success', res.message);
                    $('#modal-paraf-disposisi').modal('hide');
                    $tableDisposisi.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },
            error: function (xhr) {
                if (xhr.status == 404) {
                    Alert('warning', xhr.responseJSON.message);
                    $tableDisposisi.bootstrapTable('refresh');
                } else {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            }
        });
    });
</script>