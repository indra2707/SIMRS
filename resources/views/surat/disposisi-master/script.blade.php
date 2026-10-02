<script type="text/javascript">
    var $tableDisposisiMaster = $('#table_disposisi_master');

    // Cache daftar pegawai supaya tidak fetch berulang-ulang tiap buka modal
    var daftarPegawaiCache = [];

    // Counter untuk key unik tiap baris "Tambah Baris Baru" (client-side saja)
    var counterBarisBaru = 0;

    $(function() {
        initTableDisposisiMaster();
        muatDaftarPegawai();
    });

    // ===================== MUAT DAFTAR PEGAWAI (untuk semua dropdown) =====================
    function muatDaftarPegawai(callback) {
        $.ajax({
            url: "{{ route('surat.disposisi.pegawai-list') }}",
            type: 'GET',
            success: function(res) {
                daftarPegawaiCache = res || [];
                if (typeof callback === 'function') callback();
            },
            error: function() {
                Alert('info', 'Gagal memuat daftar pegawai.');
            }
        });
    }

    function renderOptionsPegawai(list, selectedId) {
        var opts = '<option value="">-- Pilih Pegawai --</option>';
        list.forEach(function(p) {
            var selected = (selectedId && String(selectedId) === String(p.id)) ? 'selected' : '';
            opts += '<option value="' + p.id + '" ' + selected + '>' + p.nama_pekerja + '</option>';
        });
        return opts;
    }

    // ===================== TABEL MASTER LEMBAR =====================
    function initTableDisposisiMaster() {
        $tableDisposisiMaster.bootstrapTable('destroy').bootstrapTable({
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
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            url: "{{ route('disposisi-master.views') }}",
            columns: [
                [{
                        field: "id",
                        sortable: true,
                        align: "center",
                        width: '60px',
                        formatter: function(value, row, index) {
                            return index + 1;
                        },
                    },
                    {
                        field: 'nama_master',
                        sortable: true,
                    },
                    {
                        field: 'nama_pemilik',
                        title: 'Pemilik',
                        sortable: true,
                        formatter: function(value) {
                            return value ?? '<span class="text-muted">Belum diisi</span>';
                        }
                    },
                    {
                        field: 'jumlah_tujuan',
                        title: 'Jumlah Tujuan',
                        align: 'center',
                        formatter: function(value) {
                            return '<span class="badge bg-secondary">' + value + '</span>';
                        }
                    },
                    {
                        field: 'status',
                        align: 'center',
                        formatter: function(value, row) {
                            var kelas = value === 'Aktif' ? 'bg-success' : 'bg-secondary';
                            return '<span class="badge ' + kelas +
                                ' btn-toggle-status-master" style="cursor:pointer" title="Klik untuk ubah status">' +
                                value + '</span>';
                        }
                    },
                    {
                        title: 'Action',
                        field: 'action',
                        align: 'center',
                        width: '220px',
                        events: window.eventsDisposisiMaster,
                        formatter: actionsFunctionDisposisiMaster
                    }
                ]
            ],
            error: ajaxErrorHandlerMaster,
            responseHandler: function(res) {
                return res;
            }
        });
    }

    function actionsFunctionDisposisiMaster(value, row, index) {
        return [
            '<button type="button" class="btn btn-primary btn-xs btn-kelola-tujuan" title="Kelola Tujuan">',
            '<i class="fa fa-list"></i> Tujuan</button> ',
            '<button type="button" class="btn btn-warning btn-xs btn-edit-master" title="Edit">',
            '<i class="fa fa-edit"></i></button> ',
            '<button type="button" class="btn btn-danger btn-xs btn-hapus-master" title="Hapus">',
            '<i class="fa fa-trash"></i></button>',
        ].join("");
    }

    window.eventsDisposisiMaster = {
        'click .btn-toggle-status-master': function(e, value, row, index) {
            toggleStatusMaster(row);
        },
        'click .btn-edit-master': function(e, value, row, index) {
            bukaModalMaster(row);
        },
        'click .btn-hapus-master': function(e, value, row, index) {
            hapusMaster(row);
        },
        'click .btn-kelola-tujuan': function(e, value, row, index) {
            bukaModalKelolaTujuan(row);
        }
    };

    function toggleStatusMaster(row) {
        var statusBaru = row.status === 'Aktif' ? 'Tidak Aktif' : 'Aktif';

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: 'Ubah status "' + row.nama_master + '" menjadi ' + statusBaru + '?',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "{{ route('disposisi-master.status', ':id') }}".replace(':id', row.id),
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: statusBaru,
                },
                success: function(res) {
                    if (res.success) {
                        Alert('success', res.message);
                        $tableDisposisiMaster.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                },
                error: function() {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            });
        });
    }

    function hapusMaster(row) {
        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Hapus',
            text: 'Hapus lembar "' + row.nama_master +
                '"? Semua baris tujuan di dalamnya ikut terhapus.',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "{{ route('disposisi-master.destroy', ':id') }}".replace(':id', row.id),
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    if (res.success) {
                        Alert('success', res.message);
                        $tableDisposisiMaster.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                },
                error: function() {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            });
        });
    }

    // ===================== MODAL TAMBAH / EDIT LEMBAR =====================
    $(document).on('click', '.btn-tambah-master', function() {
        bukaModalMaster(null);
    });

    function bukaModalMaster(row) {
        $('.form-master')[0].reset();
        $('input[name="id"]').val('');
        $('.cari-pegawai-pemilik').val('');

        if (row) {
            $('.judul-modal-master').text('Edit Lembar Disposisi');
            $('input[name="id"]').val(row.id);
            $('#nama_master').val(row.nama_master);

            var isiSelect = function() {
                $('#id_pegawai_pemilik').html(renderOptionsPegawai(daftarPegawaiCache, row
                    .id_pegawai_pemilik));
            };

            if (daftarPegawaiCache.length === 0) {
                muatDaftarPegawai(isiSelect);
            } else {
                isiSelect();
            }
        } else {
            $('.judul-modal-master').text('Tambah Lembar Disposisi');

            var isiSelectKosong = function() {
                $('#id_pegawai_pemilik').html(renderOptionsPegawai(daftarPegawaiCache, null));
            };

            if (daftarPegawaiCache.length === 0) {
                muatDaftarPegawai(isiSelectKosong);
            } else {
                isiSelectKosong();
            }
        }

        $('#modal-form-master').modal('show');
    }

    // Filter dropdown pemilik berdasarkan pencarian nama
    $(document).on('input', '.cari-pegawai-pemilik', function() {
        var kw = $(this).val().toLowerCase();
        var terpilihSaatIni = $('#id_pegawai_pemilik').val();

        var filtered = daftarPegawaiCache.filter(function(p) {
            return p.nama_pekerja.toLowerCase().indexOf(kw) !== -1;
        });

        $('#id_pegawai_pemilik').html(renderOptionsPegawai(filtered, terpilihSaatIni));
    });

    $(document).on('click', '.btn-simpan-master', function() {
        var id = $('input[name="id"]').val();
        var namaMaster = $('#nama_master').val().trim();
        var idPegawaiPemilik = $('#id_pegawai_pemilik').val();

        if (!namaMaster) {
            Alert('warning', 'Nama lembar wajib diisi.');
            return;
        }

        var isEdit = !!id;
        var url = isEdit ?
            "{{ route('disposisi-master.update', ':id') }}".replace(':id', id) :
            "{{ route('disposisi-master.store') }}";

        $.ajax({
            url: url,
            type: isEdit ? 'PUT' : 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                nama_master: namaMaster,
                id_pegawai_pemilik: idPegawaiPemilik || null,
            },
            beforeSend: function() {
                $('.btn-simpan-master').attr('disabled', true);
            },
            complete: function() {
                $('.btn-simpan-master').removeAttr('disabled');
            },
            success: function(res) {
                if (res.success) {
                    Alert('success', res.message);
                    $('#modal-form-master').modal('hide');
                    $tableDisposisiMaster.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },
            error: function(xhr) {
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0][0];
                    Alert('warning', firstError);
                } else {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            }
        });
    });

    // ===================== MODAL KELOLA TUJUAN =====================
    function bukaModalKelolaTujuan(row) {
        $('.kelola-tujuan-id-master').val(row.id);
        $('.kelola-tujuan-nama-master').text(row.nama_master);
        $('.baris-baru-tujuan-wrapper').empty();
        counterBarisBaru = 0;

        var lanjut = function() {
            muatBarisTujuanMaster(row.id);
            $('#modal-kelola-tujuan').modal('show');
        };

        if (daftarPegawaiCache.length === 0) {
            muatDaftarPegawai(lanjut);
        } else {
            lanjut();
        }
    }

    function muatBarisTujuanMaster(idMaster) {
        $.ajax({
            url: "{{ route('disposisi-master-detail.views') }}",
            type: 'GET',
            data: {
                id_disposisi_master: idMaster
            },
            success: function(res) {
                $('.isi-baris-tujuan-master').html(renderBarisTujuanMasterExisting(res));
            },
            error: function() {
                Alert('info', 'Gagal memuat daftar tujuan.');
            }
        });
    }

    function renderBarisTujuanMasterExisting(list) {
        if (!list || list.length === 0) {
            return '<tr><td colspan="3" class="text-center text-muted">Belum ada tujuan.</td></tr>';
        }

        return list.map(function(item) {
            return '<tr data-id-detail="' + item.id_detail + '">' +
                '<td>' + item.urutan + '</td>' +
                '<td>' + (item.nama_pekerja ?? '-') + '</td>' +
                '<td class="text-center">' +
                '<button type="button" class="btn btn-danger btn-xs btn-hapus-baris-master" ' +
                'data-id="' + item.id_detail + '"><i class="fa fa-trash"></i></button>' +
                '</td>' +
                '</tr>';
        }).join('');
    }

    $(document).on('click', '.btn-hapus-baris-master', function() {
        var idDetail = $(this).data('id');
        var idMaster = $('.kelola-tujuan-id-master').val();

        Swal.fire({
            icon: 'warning',
            title: 'Hapus baris ini?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "{{ route('disposisi-master-detail.destroy', ':id') }}".replace(':id',
                    idDetail),
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        Alert('success', res.message);
                        muatBarisTujuanMaster(idMaster);
                        $tableDisposisiMaster.bootstrapTable('refresh');
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

    // Tambah baris input baru (belum tersimpan, masih di sisi client)
    $(document).on('click', '.btn-tambah-baris-baru', function() {
        counterBarisBaru++;
        var jumlahBarisSaatIni = $('.isi-baris-tujuan-master tr[data-id-detail]').length;
        var jumlahBarisBaruSaatIni = $('.baris-baru-tujuan-wrapper .baris-baru-tujuan').length;
        var urutanDefault = jumlahBarisSaatIni + jumlahBarisBaruSaatIni + 1;

        var html = '<div class="row g-1 baris-baru-tujuan align-items-center mb-1" data-key="' +
            counterBarisBaru + '">' +
            '<div class="col-2">' +
            '<input type="number" class="form-control form-control-sm input-urutan-baru" ' +
            'value="' + urutanDefault + '" min="1" placeholder="No">' +
            '</div>' +
            '<div class="col-9">' +
            '<select class="form-select form-select-sm select-pegawai-baru">' +
            renderOptionsPegawai(daftarPegawaiCache, null) +
            '</select>' +
            '</div>' +
            '<div class="col-1 text-center">' +
            '<button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris-baru">' +
            '<i class="fa fa-times"></i></button>' +
            '</div>' +
            '</div>';

        $('.baris-baru-tujuan-wrapper').append(html);
    });

    $(document).on('click', '.btn-hapus-baris-baru', function() {
        $(this).closest('.baris-baru-tujuan').remove();
    });

    $(document).on('click', '.btn-simpan-baris-baru', function() {
        var idMaster = $('.kelola-tujuan-id-master').val();
        var items = [];

        $('.baris-baru-tujuan-wrapper .baris-baru-tujuan').each(function() {
            var urutan = $(this).find('.input-urutan-baru').val();
            var idPegawai = $(this).find('.select-pegawai-baru').val();

            if (!idPegawai) return;

            items.push({
                urutan: parseInt(urutan) || 1,
                id_pegawai_tujuan: idPegawai,
            });
        });

        if (items.length === 0) {
            Alert('warning', 'Tambah minimal 1 baris dan pilih pegawainya dulu.');
            return;
        }

        $.ajax({
            url: "{{ route('disposisi-master-detail.store-bulk') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id_disposisi_master: idMaster,
                items: items,
            },
            beforeSend: function() {
                $('.btn-simpan-baris-baru').attr('disabled', true);
            },
            complete: function() {
                $('.btn-simpan-baris-baru').removeAttr('disabled');
            },
            success: function(res) {
                if (res.success) {
                    Alert('success', res.message);
                    $('.baris-baru-tujuan-wrapper').empty();
                    muatBarisTujuanMaster(idMaster);
                    $tableDisposisiMaster.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },
            error: function(xhr) {
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0][0];
                    Alert('warning', firstError);
                } else {
                    Alert('info', 'Silahkan hubungi IT!');
                }
            }
        });
    });

    function ajaxErrorHandlerMaster(xhr, status, error) {
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
