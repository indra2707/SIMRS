<script type="text/javascript">
    // Tabel
    var $tablePegawai = $('#table_pegawai');

    // Open Modal Pegawai
    $(document).on('click', '.add-btn', function() {

        // Reset validasi
        $('.form-pegawai').removeClass('was-validated');

        // Tampilkan modal
        $('#modal-pegawai').modal('show');

        // Judul modal
        $('.modal-title').text('Form Tambah Pegawai');

        // Tombol simpan
        $('.save-btn')
            .html('<span class="fa fa-check"></span> Simpan')
            .removeAttr('disabled');

        // Reset semua field sesuai kolom tabel pekerja
        let fields = [
            'anak_perusahaan',
            'rumah_sakit',
            'nomor_sk_struktur',
            'jabatan',
            'penempatan',
            'lokasi_kerja',
            'nomor_pekerja',
            'nama_pekerja',
            'jenis_kelamin',
            'agama',
            'nik',
            'status_pernikahan',
            'golongan_darah',
            'disabilitas',
            'tanggal_lahir',
            'golongan_upah',
            'status_kepegawaian',
            'tmt_status_kepegawaian',
            'tmt_pwtt',
            'tmt_pwt',
            'masa_kerja',
            'fungsi',
            'sub_fungsi',
            'tmt_jabatan',
            'tmt_golongan_upah',
            'penyetaraan_jabatan_ap',
            'penyetaraan_golongan_upah_ap',
            'nama_bank',
            'nomor_rekening',
            'nama_rekening',
            'nomor_bpjstk',
            'nomor_bpjskesehatan',
            'nomor_npwp',
            'nomor_hp',
            'nomor_kontak_darurat',
            'nama_kontak_darurat',
            'hubungan_kontak_darurat',
            'email',
            'email_dinas',
            'alamat_ktp',
            'alamat_npwp',
            'alamat_domisili',
            'nomor_str',
            'str_seumur_hidup',
            'masa_berlaku_str',
            'nomor_sip',
            'masa_berlaku_sip',
            'nomor_sikp',
            'masa_berlaku_sikp',
            'file_sk_pengangkatan',
            'file_sk_terakhir',
            'file_foto',
            'file_ktp',
            'file_kk',
            'file_rekening',
            'file_cv'
        ];

        // Loop reset input text
        fields.forEach(function(name) {
            $('input[name="' + name + '"]').val('');
            $('textarea[name="' + name + '"]').val('');
        });

        // Reset semua select
        $('select').prop('selectedIndex', 0);

        // Reset checkbox STR seumur hidup khusus
        $('input[name="str_seumur_hidup"]').prop('checked', false);

        // Reset file input (jika ada)
        $('input[type="file"]').val('');

    });


    // Save Asset
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('pegawai-update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('pegawai-store') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-pegawai');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-pegawai').serialize(),
                    beforeSend: function() {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function() {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function(res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-pegawai').modal('hide');
                            $tablePegawai.bootstrapTable('refresh');
                        } else {
                            $.notify({
                                icon: 'fa fa-check',
                                title: 'Warning',
                                message: res.message
                            }, {
                                type: 'warning',
                                allow_dismiss: true,
                                delay: 2000,
                                showProgressbar: true,
                                timer: 300,
                                z_index: 1127,
                                animate: {
                                    enter: 'animated fadeInDown',
                                    exit: 'animated fadeOutUp'
                                },
                            });
                            form.classList.remove('was-validated');
                        }
                    },
                });
            }
            form.classList.add('was-validated');
        });
    });

    // Page Load Event
    $(function() {
        initTable();
    });


    // Table Pegawai
    function initTable() {
        var $table = $('#table_pegawai');

        $table.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: true,
            showExport: true,
            pagination: true,
            pageSize: 80,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: true,
            fixedColumns: true,
            fixedNumber: 2,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],

            url: "{{ route('pegawai-view') }}",

            uniqueId: "id",

            columns: [
                // ========== BASIC INFO ==========
                {
                    field: 'id',
                    title: 'No',
                    sortable: true,
                    align: 'center',
                    width: 50,
                    formatter: function(value, row, index) {
                        return index + 1;
                    }
                },
                {
                    field: 'nomor_pekerja',
                    title: 'No. Pekerja',
                    sortable: true,
                    width: 120
                },
                {
                    field: 'nama_pekerja',
                    title: 'Nama Lengkap',
                    sortable: true,
                    width: 200
                },

                // ========== COMPANY INFO ==========
                {
                    field: 'anak_perusahaan',
                    title: 'Anak Perusahaan',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'rumah_sakit',
                    title: 'Rumah Sakit',
                    sortable: true,
                    width: 150
                },
                {
                    field: 'nomor_sk_struktur',
                    title: 'No SK Struktur',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'jabatan',
                    title: 'Jabatan',
                    sortable: true,
                    width: 150
                },
                {
                    field: 'penempatan',
                    title: 'Penempatan',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'lokasi_kerja',
                    title: 'Lokasi Kerja',
                    sortable: true,
                    width: 120
                },

                // ========== PERSONAL INFO ==========
                {
                    field: 'jenis_kelamin',
                    title: 'L/P',
                    sortable: true,
                    align: 'center',
                    width: 60
                },
                {
                    field: 'agama',
                    title: 'Agama',
                    sortable: true,
                    width: 100,
                    visible: true
                },
                {
                    field: 'nik',
                    title: 'NIK',
                    sortable: true,
                    width: 150
                },
                {
                    field: 'status_pernikahan',
                    title: 'Status Nikah',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'golongan_darah',
                    title: 'Gol. Darah',
                    sortable: true,
                    align: 'center',
                    width: 80,
                    visible: true
                },
                {
                    field: 'disabilitas',
                    title: 'Disabilitas',
                    sortable: true,
                    width: 100,
                    visible: true
                },
                {
                    field: 'tanggal_lahir_formatted',
                    title: 'Tanggal Lahir',
                    sortable: true,
                    width: 120,
                    visible: true
                },

                // ========== EMPLOYMENT STATUS ==========
                {
                    field: 'golongan_upah',
                    title: 'Gol. Upah',
                    sortable: true,
                    width: 100
                },
                {
                    field: 'status_kepegawaian',
                    title: 'Status Kepegawaian',
                    sortable: true,
                    width: 150
                },
                {
                    field: 'tmt_status_kepegawaian_formatted',
                    title: 'TMT Status',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'tmt_pwtt_formatted',
                    title: 'TMT PWTT',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'tmt_pwt_formatted',
                    title: 'TMT PWT',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'masa_kerja',
                    title: 'Masa Kerja',
                    sortable: true,
                    width: 100
                },
                {
                    field: 'tanggal_akhir_kontrak_formatted',
                    title: 'Akhir Kontrak',
                    sortable: true,
                    width: 120
                },

                // ========== FUNCTION & GRADE ==========
                {
                    field: 'fungsi',
                    title: 'Fungsi',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'sub_fungsi',
                    title: 'Sub Fungsi',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'tmt_jabatan_formatted',
                    title: 'TMT Jabatan',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'tmt_golongan_upah_formatted',
                    title: 'TMT Gol. Upah',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'penyetaraan_jabatan_ap',
                    title: 'Penyetaraan Jabatan AP',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'penyetaraan_golongan_upah_ap',
                    title: 'Penyetaraan Gol. Upah AP',
                    sortable: true,
                    width: 150,
                    visible: true
                },

                // ========== BANKING INFO ==========
                {
                    field: 'nama_bank',
                    title: 'Bank',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'nomor_rekening',
                    title: 'No. Rekening',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'nama_rekening',
                    title: 'Nama Rekening',
                    sortable: true,
                    width: 150,
                    visible: true
                },

                // ========== INSURANCE & TAX ==========
                {
                    field: 'nomor_bpjstk',
                    title: 'No. BPJS TK',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'nomor_bpjskesehatan',
                    title: 'No. BPJS Kesehatan',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'nomor_npwp',
                    title: 'No. NPWP',
                    sortable: true,
                    width: 150,
                    visible: true
                },

                // ========== CONTACT INFO ==========
                {
                    field: 'nomor_hp',
                    title: 'No. HP',
                    sortable: true,
                    width: 130
                },
                {
                    field: 'email',
                    title: 'Email Pribadi',
                    sortable: true,
                    width: 180,
                    visible: true
                },
                {
                    field: 'email_dinas',
                    title: 'Email Dinas',
                    sortable: true,
                    width: 180,
                    visible: true
                },
                {
                    field: 'nomor_kontak_darurat',
                    title: 'Kontak Darurat',
                    sortable: true,
                    width: 130,
                    visible: true
                },
                {
                    field: 'nama_kontak_darurat',
                    title: 'Nama Kontak Darurat',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'hubungan_kontak_darurat',
                    title: 'Hubungan Kontak',
                    sortable: true,
                    width: 120,
                    visible: true
                },

                // ========== ADDRESS INFO ==========
                {
                    field: 'alamat_ktp',
                    title: 'Alamat KTP',
                    sortable: true,
                    width: 200,
                    visible: true
                },
                {
                    field: 'alamat_npwp',
                    title: 'Alamat NPWP',
                    sortable: true,
                    width: 200,
                    visible: true
                },
                {
                    field: 'alamat_domisili',
                    title: 'Alamat Domisili',
                    sortable: true,
                    width: 200,
                    visible: true
                },

                // ========== PROFESSIONAL LICENSES ==========
                {
                    field: 'nomor_str',
                    title: 'No. STR',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'str_seumur_hidup',
                    title: 'STR Seumur Hidup',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'masa_berlaku_str_formatted',
                    title: 'Masa Berlaku STR',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'nomor_sip',
                    title: 'No. SIP',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'masa_berlaku_sip_formatted',
                    title: 'Masa Berlaku SIP',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'asuransi_profesi',
                    title: 'Asuransi Profesi',
                    sortable: true,
                    width: 130,
                    visible: true
                },
                {
                    field: 'nomor_polis',
                    title: 'No. Polis',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'masa_berlaku_asuransi_formatted',
                    title: 'Masa Berlaku Asuransi',
                    sortable: true,
                    width: 150,
                    visible: true
                },

                // ========== EDUCATION ==========
                {
                    field: 'jenjang_pendidikan_terakhir',
                    title: 'Pendidikan Terakhir',
                    sortable: true,
                    width: 150
                },
                {
                    field: 'pend_diploma',
                    title: 'Diploma',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'pend_s1',
                    title: 'S1',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'pend_s2',
                    title: 'S2',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'pend_s3',
                    title: 'S3',
                    sortable: true,
                    width: 150,
                    visible: true
                },
                {
                    field: 'kampus_terakhir',
                    title: 'Kampus Terakhir',
                    sortable: true,
                    width: 180,
                    visible: true
                },
                {
                    field: 'keterangan',
                    title: 'Keterangan',
                    sortable: true,
                    width: 200,
                    visible: true
                },

                // ========== SYSTEM INFO ==========
                {
                    field: 'input_by',
                    title: 'Input By',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'input_date_formatted',
                    title: 'Input Date',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'update_by',
                    title: 'Update By',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'update_date_formatted',
                    title: 'Update Date',
                    sortable: true,
                    width: 120,
                    visible: true
                },
                {
                    field: 'username',
                    title: 'Username',
                    sortable: true,
                    width: 120,
                    visible: true
                },

                // ========== ACTIONS ==========
                {
                    width: '100%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: true,
                    events: window.eventsSdm,
                    formatter: actionsFunctionPegawai
                }
            ],

            onLoadSuccess: function(data) {
                console.log('✅ Data loaded:', data.length, 'records');
            },

            onLoadError: function(status, xhr) {
                console.error('❌ Load error:', status);
                console.error('Response:', xhr.responseText);
            },

            responseHandler: function(res) {
                console.log('📦 Response received:', res);
                return res; // Return langsung karena sudah array
            }
        });
    }

    // ✅ Call function saat document ready
    $(document).ready(function() {
        initTable();
    });


    function actionsFunctionPegawai(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="true">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsPegawai = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-pegawai').modal('show');
            $('.modal-title').text('Form Edit Pegawai');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('pegawai-delete', ':id') }}";
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
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function() {
                        $tablePegawai.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status Pegawai
    window.updateStatusPegawai = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('pegawai.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    table: 'tbl_pegawais',
                    _token: "{{ csrf_token() }}"
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tablePegawai.bootstrapTable('refresh');
                },
                error: function(xhr, status, error) {
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
