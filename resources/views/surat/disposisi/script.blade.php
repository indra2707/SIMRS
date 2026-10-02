<script type="text/javascript">
    // Tabel
    var $tableSuratSiap = $('#table_surat_siap');
    var $tableDisposisiMasuk = $('#table_disposisi_masuk');

    // Modal mana yang harus dibuka lagi setelah modal preview besar ditutup
    var modalAsalPreviewDisposisi = '#modal-detail-surat-disposisi';

    // Data detail disposisi masuk yang sedang dibuka (dipakai tombol "Teruskan ke Bawahan")
    var rowDetailDisposisiMasukSaatIni = null;

    // Label tingkat surat
    var labelTingkatSurat = {
        'R': {
            text: 'Rahasia',
            badge: 'bg-danger'
        },
        'P': {
            text: 'Penting',
            badge: 'bg-warning text-dark'
        },
        'S': {
            text: 'Segera',
            badge: 'bg-primary'
        },
        'B': {
            text: 'Biasa',
            badge: 'bg-secondary'
        },
    };

    // Label status disposisi
    var labelStatusDisposisi = {
        'Menunggu': 'bg-warning text-dark',
        'Dibaca': 'bg-info',
        'Selesai': 'bg-success',
    };

    // Page Load Event
    $(function() {
        initTableSuratSiap();
        initTableDisposisiMasuk();
    });

    // ===================== TABEL 1: SURAT SIAP DIDISPOSISIKAN =====================
    function initTableSuratSiap() {
        $tableSuratSiap.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id_surat',
            uniqueId: 'id_surat',
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
            url: "{{ route('surat.disposisi.view-surat-siap') }}",
            columns: [
                [{
                        field: "id_surat",
                        sortable: true,
                        align: "center",
                        width: '60px',
                        formatter: function(value, row, index) {
                            return index + 1;
                        },
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
                        field: 'nama_pembuat',
                        sortable: true,
                    },
                    {
                        field: 'status_surat',
                        title: 'Status Surat',
                        align: 'center',
                        formatter: function(value, row, index) {
                            if (!value) {
                                return '<span class="badge bg-secondary">-</span>';
                            }
                            switch (String(value).trim().toLowerCase()) {
                                case 'selesai':
                                    return '<span class="badge bg-success">Selesai</span>';
                                case 'approve':
                                    return '<span class="badge bg-warning text-dark">Approve</span>';
                                default:
                                    return '<span class="badge bg-secondary">' + value + '</span>';
                            }
                        }
                    },
                    {
                        title: 'Action',
                        field: 'action',
                        align: 'center',
                        width: '220px',
                        events: window.eventsSuratSiap,
                        formatter: actionsFunctionSuratSiap
                    }
                ]
            ],
            error: ajaxErrorHandlerDisposisi,
            responseHandler: function(res) {
                return res;
            }
        });
    }

    function actionsFunctionSuratSiap(value, row, index) {
        var tombolBuat = row.sudah_disposisi ?
            '<button type="button" class="btn btn-secondary btn-xs" disabled title="Sudah dibuat disposisi">' +
            '<i class="fa fa-check"></i> Sudah Didisposisi</button>' :
            '<button type="button" class="btn btn-success btn-xs btn-buat-disposisi" title="Buat Disposisi">' +
            '<i class="fa fa-share"></i> Buat Disposisi</button>';

        return [
            '<button type="button" class="btn btn-info btn-xs btn-lihat-surat-siap" title="Lihat Detail">',
            '<i class="fa fa-eye"></i>',
            '</button> ',
            tombolBuat,
        ].join("");
    }

    window.eventsSuratSiap = {
        'click .btn-lihat-surat-siap': function(e, value, row, index) {
            $('.detail-surat-tanggal').text(row.tanggal);
            $('.detail-surat-no-surat').text(row.no_surat);
            $('.detail-surat-perihal').text(row.perihal);
            $('.detail-surat-pembuat').text(row.nama_pembuat ?? '-');
            $('.detail-surat-isi-surat').text(row.isi_surat ?? '-');
            $('.detail-surat-lampiran').html(renderLampiranThumbsDisposisi(row.lampiran));
            $('#modal-detail-surat-disposisi').modal('show');
        },
        'click .btn-buat-disposisi': function(e, value, row, index) {
            bukaModalDisposisi({
                mode: 'root',
                id_surat: row.id_surat,
                id_parent: null,
                no_surat: row.no_surat,
                perihal: row.perihal,
            });
        }
    };

    // ===================== TABEL 2: DISPOSISI MASUK =====================
    function initTableDisposisiMasuk() {
        $tableDisposisiMasuk.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id_disposisi',
            uniqueId: 'id_disposisi',
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
                        field: "id_disposisi",
                        sortable: true,
                        align: "center",
                        width: '60px',
                        formatter: function(value, row, index) {
                            return index + 1;
                        },
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
                        field: 'nama_pengirim',
                        title: 'Dari',
                        sortable: true,
                        formatter: function(value) {
                            return value ?? '-';
                        }
                    },
                    {
                        field: 'tingkat_surat',
                        title: 'Tingkat',
                        align: 'center',
                        formatter: function(value) {
                            var t = labelTingkatSurat[value] || {
                                text: value,
                                badge: 'bg-secondary'
                            };
                            return '<span class="badge ' + t.badge + ' badge-tingkat">' + t.text +
                                '</span>';
                        }
                    },
                    {
                        field: 'checklist',
                        title: 'Checklist',
                        align: 'center',
                        formatter: function(value, row) {
                            return renderChecklistBadges(row);
                        }
                    },
                    {
                        field: 'status',
                        title: 'Status',
                        align: 'center',
                        formatter: function(value, row) {
                            var kelas = labelStatusDisposisi[value] || 'bg-secondary';
                            var html = '<span class="badge ' + kelas + '">' + value + '</span>';
                            if (row.sudah_diteruskan) {
                                html += '<br><small class="text-muted">Sudah diteruskan</small>';
                            }
                            return html;
                        }
                    },
                    {
                        title: 'Action',
                        field: 'action',
                        align: 'center',
                        width: '160px',
                        events: window.eventsDisposisiMasuk,
                        formatter: actionsFunctionDisposisiMasuk
                    }
                ]
            ],
            error: ajaxErrorHandlerDisposisi,
            responseHandler: function(res) {
                return res;
            }
        });
    }

    function actionsFunctionDisposisiMasuk(value, row, index) {
        return [
            '<button type="button" class="btn btn-info btn-xs btn-lihat-disposisi-masuk" title="Lihat Detail">',
            '<i class="fa fa-eye"></i>',
            '</button> ',
            '<button type="button" class="btn btn-success btn-xs btn-teruskan-disposisi-masuk" title="Teruskan ke Bawahan">',
            '<i class="fa fa-share"></i>',
            '</button>',
        ].join("");
    }

    window.eventsDisposisiMasuk = {
        'click .btn-lihat-disposisi-masuk': function(e, value, row, index) {
            bukaDetailDisposisiMasuk(row.id_disposisi);
        },
        'click .btn-teruskan-disposisi-masuk': function(e, value, row, index) {
            bukaModalDisposisi({
                mode: 'forward',
                id_surat: row.id_surat,
                id_parent: row.id_disposisi,
                no_surat: row.no_surat,
                perihal: row.perihal,
            });
        }
    };

    // ===================== DETAIL DISPOSISI MASUK =====================
    function bukaDetailDisposisiMasuk(idDisposisi) {
        $.ajax({
            url: "{{ route('surat.disposisi.show', ':id') }}".replace(':id', idDisposisi),
            type: 'GET',
            success: function(res) {
                if (!res.success) {
                    Alert('warning', res.message);
                    return;
                }

                var d = res.data;
                rowDetailDisposisiMasukSaatIni = d;

                $('.detail-masuk-id-disposisi').val(d.id);
                $('.detail-masuk-no-surat').text(d.surat ? d.surat.no_surat : '-');
                $('.detail-masuk-perihal').text(d.surat ? d.surat.perihal : '-');
                $('.detail-masuk-pengirim').text(d.pengirim ? d.pengirim.nama_pekerja : '-');

                var t = labelTingkatSurat[d.tingkat_surat] || {
                    text: d.tingkat_surat,
                    badge: 'bg-secondary'
                };
                $('.detail-masuk-tingkat').html('<span class="badge ' + t.badge + '">' + t.text +
                    '</span>');

                $('.detail-masuk-checklist').html(renderChecklistBadges(d));

                var kelasStatus = labelStatusDisposisi[d.status] || 'bg-secondary';
                $('.detail-masuk-status').html('<span class="badge ' + kelasStatus + '">' + d.status +
                    '</span>');

                $('.detail-masuk-catatan').text(d.catatan || '-');

                var lampiran = [];
                if (d.surat && d.surat.lampiran) {
                    try {
                        lampiran = JSON.parse(d.surat.lampiran);
                    } catch (e) {
                        lampiran = [];
                    }
                }
                $('.detail-masuk-lampiran').html(renderLampiranThumbsDisposisi(lampiran));

                if (d.status === 'Selesai') {
                    $('.btn-tandai-selesai-masuk').addClass('d-none');
                } else {
                    $('.btn-tandai-selesai-masuk').removeClass('d-none');
                }

                $('#modal-detail-disposisi-masuk').modal('show');

                // Refresh tabel supaya status "Menunggu" -> "Dibaca" ikut update
                $tableDisposisiMasuk.bootstrapTable('refresh');
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    Alert('warning', xhr.responseJSON.message);
                } else {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            }
        });
    }

    $(document).on('click', '.btn-tandai-selesai-masuk', function() {
        var id = $('.detail-masuk-id-disposisi').val();

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: 'Tandai disposisi ini sebagai Selesai?',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "{{ route('surat.disposisi.selesai', ':id') }}".replace(':id', id),
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        Alert('success', res.message);
                        $('#modal-detail-disposisi-masuk').modal('hide');
                        $tableDisposisiMasuk.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                },
                error: function() {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            });
        });
    });

    // Tombol "Teruskan ke Bawahan" dari dalam modal detail
    $(document).on('click', '.btn-teruskan-dari-detail', function() {
        if (!rowDetailDisposisiMasukSaatIni) return;

        var d = rowDetailDisposisiMasukSaatIni;
        $('#modal-detail-disposisi-masuk').modal('hide');

        bukaModalDisposisi({
            mode: 'forward',
            id_surat: d.id_surat,
            id_parent: d.id,
            no_surat: d.surat ? d.surat.no_surat : '-',
            perihal: d.surat ? d.surat.perihal : '-',
        });
    });

    // ===================== MODAL BUAT / TERUSKAN DISPOSISI =====================
    function bukaModalDisposisi(opsi) {
        $('.form-buat-disposisi')[0].reset();
        $('.baris-tujuan-disposisi').empty();
        $('.lembar-kosong-warning').hide();
        $('.tabel-tujuan-disposisi').show();

        $('input[name="id_surat"]').val(opsi.id_surat);
        $('input[name="id_parent"]').val(opsi.id_parent ?? '');

        $('.disposisi-form-no-surat').text(opsi.no_surat);
        $('.disposisi-form-perihal').text(opsi.perihal);
        $('.disposisi-form-nama-master').text('Memuat...');

        if (opsi.mode === 'forward') {
            $('.judul-modal-disposisi').text('Teruskan Disposisi ke Bawahan');
            $('.teks-btn-simpan-disposisi').text('Teruskan Disposisi');
        } else {
            $('.judul-modal-disposisi').text('Buat Disposisi');
            $('.teks-btn-simpan-disposisi').text('Kirim Disposisi');
        }

        $('#modal-buat-disposisi').modal('show');

        muatTemplateTujuanDisposisi();
    }

    function muatTemplateTujuanDisposisi() {
        $.ajax({
            url: "{{ route('surat.disposisi.template-tujuan') }}",
            type: 'GET',
            success: function(res) {
                if (!res.success) {
                    tampilkanLembarKosong(res.message);
                    return;
                }

                $('.disposisi-form-nama-master').text(res.nama_master ?? '-');
                $('.baris-tujuan-disposisi').html(renderBarisTujuanDisposisi(res.data));
            },
            error: function(xhr) {
                var pesan = xhr.responseJSON && xhr.responseJSON.message ?
                    xhr.responseJSON.message :
                    'Gagal memuat template tujuan disposisi.';
                tampilkanLembarKosong(pesan);
            }
        });
    }

    function tampilkanLembarKosong(pesan) {
        $('.disposisi-form-nama-master').text('-');
        $('.tabel-tujuan-disposisi').hide();
        $('.lembar-kosong-warning').text(pesan).show();
    }

    function renderBarisTujuanDisposisi(list) {
        if (!list || list.length === 0) {
            return '<tr><td colspan="7" class="text-center text-muted">Tidak ada data tujuan.</td></tr>';
        }

        return list.map(function(item, index) {
            var idPegawai = item.id_pegawai_tujuan;
            var idUnit = item.id_unit ?? '';

            return '<tr>' +
                '<td>' + (index + 1) + '</td>' +
                '<td class="nama-tujuan-col">' + (item.nama_pegawai_tujuan ?? '-') + '</td>' +
                '<td>' +
                '<input type="checkbox" class="form-check-input chk-pilih-tujuan" ' +
                'data-id-pegawai="' + idPegawai + '" data-id-unit="' + idUnit + '">' +
                '</td>' +
                '<td><input type="checkbox" class="form-check-input chk-aitf" data-jenis="is_action"></td>' +
                '<td><input type="checkbox" class="form-check-input chk-aitf" data-jenis="is_tanggapan"></td>' +
                '<td><input type="checkbox" class="form-check-input chk-aitf" data-jenis="is_info"></td>' +
                '<td><input type="checkbox" class="form-check-input chk-aitf" data-jenis="is_file"></td>' +
                '</tr>';
        }).join('');
    }

    // Centang salah satu A/T/I/F -> otomatis centang "Pilih" juga di baris yang sama
    $(document).on('change', '.chk-aitf', function() {
        var $tr = $(this).closest('tr');
        var adaYangDicentang = $tr.find('.chk-aitf:checked').length > 0;
        if (adaYangDicentang) {
            $tr.find('.chk-pilih-tujuan').prop('checked', true);
        }
    });

    // Uncheck "Pilih" -> otomatis uncheck semua A/T/I/F di baris yang sama
    $(document).on('change', '.chk-pilih-tujuan', function() {
        if (!$(this).is(':checked')) {
            $(this).closest('tr').find('.chk-aitf').prop('checked', false);
        }
    });

    $(document).on('click', '.btn-pilih-semua-tujuan', function() {
        $('.baris-tujuan-disposisi .chk-pilih-tujuan').prop('checked', true);
    });

    $(document).on('click', '.btn-batal-pilih-tujuan', function() {
        $('.baris-tujuan-disposisi .chk-pilih-tujuan, .baris-tujuan-disposisi .chk-aitf')
            .prop('checked', false);
    });

    // Simpan disposisi (baik root maupun forward)
    $(document).on('click', '.btn-simpan-disposisi', function() {
        var idSurat = $('input[name="id_surat"]').val();
        var idParent = $('input[name="id_parent"]').val();
        var tingkatSurat = $('input[name="tingkat_surat"]:checked').val();
        var catatan = $('#catatan_disposisi').val();

        var tujuan = [];
        $('.baris-tujuan-disposisi tr').each(function() {
            var $chkPilih = $(this).find('.chk-pilih-tujuan');
            if ($chkPilih.length === 0 || !$chkPilih.is(':checked')) return;

            tujuan.push({
                id_pegawai: $chkPilih.data('id-pegawai'),
                id_unit: $chkPilih.data('id-unit') || null,
                is_action: $(this).find('.chk-aitf[data-jenis="is_action"]').is(':checked'),
                is_tanggapan: $(this).find('.chk-aitf[data-jenis="is_tanggapan"]').is(':checked'),
                is_info: $(this).find('.chk-aitf[data-jenis="is_info"]').is(':checked'),
                is_file: $(this).find('.chk-aitf[data-jenis="is_file"]').is(':checked'),
                tingkat_surat: tingkatSurat,
                catatan: catatan,
            });
        });

        if (tujuan.length === 0) {
            Alert('warning', 'Pilih minimal 1 tujuan disposisi.');
            return;
        }

        $.ajax({
            url: "{{ route('surat.disposisi.store') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id_surat: idSurat,
                id_parent: idParent ? idParent : null,
                tujuan: tujuan,
            },
            beforeSend: function() {
                $('.btn-simpan-disposisi').attr('disabled', true);
            },
            complete: function() {
                $('.btn-simpan-disposisi').removeAttr('disabled');
            },
            success: function(res, status, xhr) {
                if (xhr.status == 200 && res.success) {
                    Alert('success', res.message);
                    $('#modal-buat-disposisi').modal('hide');
                    $tableSuratSiap.bootstrapTable('refresh');
                    $tableDisposisiMasuk.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },
            error: function(xhr) {
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0][0];
                    Alert('warning', firstError);
                } else if (xhr.status == 403 || xhr.status == 409 || xhr.status == 404) {
                    Alert('warning', xhr.responseJSON.message);
                } else {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            }
        });
    });

    // ===================== HELPER =====================
    function renderChecklistBadges(row) {
        var badges = [];
        if (row.is_action) badges.push('<span class="badge bg-success badge-checklist">Action</span>');
        if (row.is_tanggapan) badges.push('<span class="badge bg-primary badge-checklist">Tanggapan</span>');
        if (row.is_info) badges.push('<span class="badge bg-info badge-checklist">Info</span>');
        if (row.is_file) badges.push('<span class="badge bg-secondary badge-checklist">File</span>');

        return badges.length ? badges.join(' ') : '<span class="text-muted">-</span>';
    }

    function renderLampiranThumbsDisposisi(lampiranArr) {
        if (!lampiranArr || lampiranArr.length === 0) {
            return '<p class="text-muted mb-0">Tidak ada lampiran.</p>';
        }

        return lampiranArr.map(function(path) {
            // Sesuaikan base path ini dengan lokasi penyimpanan lampiran surat Anda
            var url = '/uploads/surat/memo/' + path.split('/').pop();

            return '<div class="lampiran-thumb-wrap">' +
                '<img src="' + url + '" class="btn-preview-disposisi" data-src="' + url + '">' +
                '</div>';
        }).join('');
    }

    // Lihat foto besar (dari modal Detail Surat / Detail Disposisi Masuk)
    $(document).on('click', '.btn-preview-disposisi', function() {
        modalAsalPreviewDisposisi = $(this).closest('.modal').length ?
            '#' + $(this).closest('.modal').attr('id') :
            '#modal-detail-surat-disposisi';

        $('#preview-large-disposisi').attr('src', $(this).data('src'));
        $('#modal-preview-image-disposisi').modal('show');
        $(modalAsalPreviewDisposisi).modal('hide');
    });

    // Tutup modal preview besar -> balik ke modal asal
    $('#modal-preview-image-disposisi').on('hidden.bs.modal', function() {
        $(modalAsalPreviewDisposisi).modal('show');
    });

    function ajaxErrorHandlerDisposisi(xhr, status, error) {
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
    }
</script>
