<script type="text/javascript">
    // Tabel
    var $tableAprovalMemo = $('#table_aproval_memo');

    // Modal mana yang harus dibuka lagi setelah modal preview besar ditutup
    var modalAsalPreviewMemo = '#modal-detail-memo';

    // Label jabatan (samakan dengan value di form Hirarki Aproval Anda)
    var labelJabatanMemo = {
        '0': 'Director',
        '1': 'Vice Director',
        '2': 'Head',
    };

    // Page Load Event
    $(function() {
        initTableAprovalMemo();
    });

    // Table Approval Memorandum
    function initTableAprovalMemo() {

        
        $tableAprovalMemo.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            idField: 'id_aproval_surat',
            uniqueId: 'id_aproval_surat',
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
            url: "{{ route('surat.aproval-memorandum.view') }}",
            columns: [
                [{
                        field: "id_aproval_surat",
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
                        field: 'parent_jabatan',
                        title: 'Level Approval Saya',
                        align: 'center',
                        formatter: function(value, row, index) {
                            var label = labelJabatanMemo[String(value)] || '-';
                            return '<span class="badge bg-primary badge-jabatan">' + label + '</span>';
                        }
                    },
                    {
                        field: 'status_surat',
                        title: 'Status Surat',
                        align: 'center',
                        formatter: function(value, row, index) {

                            console.log('STATUS SURAT:', value);

                            if (value === null || value === undefined || String(value).trim() === '') {
                                return '<span class="badge bg-secondary">-</span>';
                            }

                            switch (String(value).trim().toLowerCase()) {

                                case 'approve':
                                    return '<span class="badge bg-warning text-dark">Approve</span>';

                                case 'selesai':
                                    return '<span class="badge bg-success">Selesai</span>';

                                case 'revisi':
                                    return '<span class="badge bg-danger">Revisi</span>';

                                case 'tolak':
                                    return '<span class="badge bg-danger">Tolak</span>';

                                default:
                                    return '<span class="badge bg-secondary">' +
                                        String(value) +
                                        '</span>';
                            }
                        }
                    },
                    {
                        title: 'Action',
                        field: 'action',
                        align: 'center',
                        width: '160px',
                        events: window.eventsAprovalMemo,
                        formatter: actionsFunctionAprovalMemo
                    }
                ]
            ],
            error: function(xhr, status, error) {
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
            responseHandler: function(res) {
                return res;
            }
        });
    }

    function actionsFunctionAprovalMemo(value, row, index) {
        return [
            '<button type="button" class="btn btn-info btn-xs btn-lihat-memo" title="Lihat Detail">',
            '<i class="fa fa-eye"></i>',
            '</button> ',
            '<button type="button" class="btn btn-primary btn-xs btn-keputusan-memo" title="Approve / Tolak">',
            '<i class="fa fa-gavel"></i> Proses',
            '</button>',
        ].join("");
    }

    // Render thumbnail lampiran (klik untuk perbesar)
    function renderLampiranThumbsMemo(lampiranArr) {
        if (!lampiranArr || lampiranArr.length === 0) {
            return '<p class="text-muted mb-0">Tidak ada lampiran.</p>';
        }

        return lampiranArr.map(function(path) {
            // Sesuaikan base path ini dengan lokasi penyimpanan lampiran surat Anda
            var url = '/uploads/surat/memo/' + path.split('/').pop();

            return '<div class="lampiran-thumb-wrap">' +
                '<img src="' + url + '" class="btn-preview-memo" data-src="' + url + '">' +
                '</div>';
        }).join('');
    }

    // Handle events button actions
    window.eventsAprovalMemo = {
        'click .btn-lihat-memo': function(e, value, row, index) {
            $('.detail-memo-tanggal').text(row.tanggal);
            $('.detail-memo-no-surat').text(row.no_surat);
            $('.detail-memo-perihal').text(row.perihal);
            $('.detail-memo-pembuat').text(row.nama_pembuat ?? '-');
            $('.detail-memo-isi-surat').text(row.isi_surat ?? '-');
            $('.detail-memo-lampiran').html(renderLampiranThumbsMemo(row.lampiran));
            $('#modal-detail-memo').modal('show');
        },
        'click .btn-keputusan-memo': function(e, value, row, index) {
            $('.form-keputusan-memo')[0].reset();
            $('.keterangan-wajib-warning').addClass('d-none');
            $('input[name="id_aproval_surat"]').val(row.id_aproval_surat);
            $('.keputusan-memo-no-surat').text(row.no_surat);
            $('.keputusan-memo-perihal').text(row.perihal);
            $('#modal-keputusan-memo').modal('show');
        }
    };

    // Lihat foto besar (dari modal Detail)
    $(document).on('click', '.btn-preview-memo', function() {
        modalAsalPreviewMemo = '#modal-detail-memo';
        $('#preview-large-memo').attr('src', $(this).data('src'));
        $('#modal-preview-image-memo').modal('show');
        $('#modal-detail-memo').modal('hide');
    });

    // Tutup modal preview besar -> balik ke modal asal
    $('#modal-preview-image-memo').on('hidden.bs.modal', function() {
        $(modalAsalPreviewMemo).modal('show');
    });

    // ===================== APPROVE =====================
    $(document).on('click', '.btn-approve-memo', function() {
        var id = $('input[name="id_aproval_surat"]').val();
        var keterangan = $('#keterangan_keputusan').val();

        var url = "{{ route('surat.aproval-memorandum.approve', ':id') }}";
        url = url.replace(':id', id);

        prosesKeputusanMemo(url, keterangan, 'Approve');
    });

    // ===================== TOLAK =====================
    $(document).on('click', '.btn-tolak-memo', function() {
        var id = $('input[name="id_aproval_surat"]').val();
        var keterangan = $('#keterangan_keputusan').val().trim();

        if (!keterangan) {
            $('.keterangan-wajib-warning').removeClass('d-none');
            $('#keterangan_keputusan').focus();
            return;
        }
        $('.keterangan-wajib-warning').addClass('d-none');

        var url = "{{ route('surat.aproval-memorandum.reject', ':id') }}";
        url = url.replace(':id', id);

        prosesKeputusanMemo(url, keterangan, 'Tolak');
    });

    function prosesKeputusanMemo(url, keterangan, jenis) {
        var teksKonfirmasi = jenis === 'Approve' ?
            'Surat ini akan disetujui.' :
            'Surat ini akan ditolak dan dikembalikan untuk revisi.';

        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi',
            text: teksKonfirmasi,
            showCancelButton: true,
            confirmButtonColor: jenis === 'Approve' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: jenis === 'Approve' ? 'Ya, Setujui!' : 'Ya, Tolak!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    keterangan: keterangan,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('.btn-approve-memo, .btn-tolak-memo').attr('disabled', true);
                },
                complete: function() {
                    $('.btn-approve-memo, .btn-tolak-memo').removeAttr('disabled');
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success) {
                        Alert('success', res.message);
                        $('#modal-keputusan-memo').modal('hide');
                        $tableAprovalMemo.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status == 422) {
                        var errors = xhr.responseJSON.errors;
                        var firstError = Object.values(errors)[0][0];
                        Alert('warning', firstError);
                    } else if (xhr.status == 404) {
                        Alert('warning', xhr.responseJSON.message);
                        $tableAprovalMemo.bootstrapTable('refresh');
                    } else {
                        Alert('info', 'Silahkan hubungi IT!');
                    }
                }
            });
        });
    }
</script>
