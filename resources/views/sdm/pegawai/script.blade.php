<script type="text/javascript">
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-pegawai"),
        allowClear: true

    });
    // Tabel
    var $tablePegawai = $('#table_pegawai');

    // Open Modal Pegawai
    $(document).on('click', '.add-btn', function() {

        // Reset validasi
        $('.form-pegawai').removeClass('was-validated');

        $('#modal-pegawai').modal('show');
        $('.modal-title').text('Form Tambah Pegawai');
        $('.save-btn')
            .html('<span class="fa fa-check"></span> Simpan')
            .removeAttr('disabled');
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
            // 'file_sk_pengangkatan',
            // 'file_sk_terakhir',
            // 'file_foto',
            // 'file_ktp',
            // 'file_kk',
            // 'file_rekening',
            // 'file_cv'
        ];

        // Loop reset input text
        fields.forEach(function(name) {
            $('input[name="' + name + '"]').val('');
            $('textarea[name="' + name + '"]').val('');
        });
        $('select').prop('selectedIndex', 0);
        $('input[name="str_seumur_hidup"]').prop('checked', false);
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
                console.log('Form data:', $('.form-pegawai').serialize());
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
                    visible: false
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
                    visible: false
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
                    visible: false
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
                    visible: false
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
                    width: 100,
                    visible: false
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
                    visible: false
                },
                {
                    field: 'tmt_pwtt_formatted',
                    title: 'TMT PWTT',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'tmt_pwt_formatted',
                    title: 'TMT PWT',
                    sortable: true,
                    width: 120,
                    visible: false
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
                    visible: false
                },
                {
                    field: 'sub_fungsi',
                    title: 'Sub Fungsi',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'tmt_jabatan_formatted',
                    title: 'TMT Jabatan',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'tmt_golongan_upah_formatted',
                    title: 'TMT Gol. Upah',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'penyetaraan_jabatan_ap',
                    title: 'Penyetaraan Jabatan AP',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'penyetaraan_golongan_upah_ap',
                    title: 'Penyetaraan Gol. Upah AP',
                    sortable: true,
                    width: 150,
                    visible: false
                },

                // ========== BANKING INFO ==========
                {
                    field: 'nama_bank',
                    title: 'Bank',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'nomor_rekening',
                    title: 'No. Rekening',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'nama_rekening',
                    title: 'Nama Rekening',
                    sortable: true,
                    width: 150,
                    visible: false
                },

                // ========== INSURANCE & TAX ==========
                {
                    field: 'nomor_bpjstk',
                    title: 'No. BPJS TK',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'nomor_bpjskesehatan',
                    title: 'No. BPJS Kesehatan',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'nomor_npwp',
                    title: 'No. NPWP',
                    sortable: true,
                    width: 150,
                    visible: false
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
                    visible: false
                },
                {
                    field: 'nomor_kontak_darurat',
                    title: 'Kontak Darurat',
                    sortable: true,
                    width: 130,
                    visible: false
                },
                {
                    field: 'nama_kontak_darurat',
                    title: 'Nama Kontak Darurat',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'hubungan_kontak_darurat',
                    title: 'Hubungan Kontak',
                    sortable: true,
                    width: 120,
                    visible: false
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
                    visible: false
                },
                {
                    field: 'alamat_domisili',
                    title: 'Alamat Domisili',
                    sortable: true,
                    width: 200,
                    visible: false
                },

                // ========== PROFESSIONAL LICENSES ==========
                {
                    field: 'nomor_str',
                    title: 'No. STR',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'str_seumur_hidup',
                    title: 'STR Seumur Hidup',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'masa_berlaku_str_formatted',
                    title: 'Masa Berlaku STR',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'nomor_sip',
                    title: 'No. SIP',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'masa_berlaku_sip_formatted',
                    title: 'Masa Berlaku SIP',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'asuransi_profesi',
                    title: 'Asuransi Profesi',
                    sortable: true,
                    width: 130,
                    visible: false
                },
                {
                    field: 'nomor_polis',
                    title: 'No. Polis',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'masa_berlaku_asuransi_formatted',
                    title: 'Masa Berlaku Asuransi',
                    sortable: true,
                    width: 150,
                    visible: false
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
                    visible: false
                },
                {
                    field: 'pend_s1',
                    title: 'S1',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'pend_s2',
                    title: 'S2',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'pend_s3',
                    title: 'S3',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'kampus_terakhir',
                    title: 'Kampus Terakhir',
                    sortable: true,
                    width: 180,
                    visible: false
                },
                {
                    field: 'keterangan',
                    title: 'Keterangan',
                    sortable: true,
                    width: 200,
                    visible: false
                },

                // ========== SYSTEM INFO ==========
                {
                    field: 'input_by',
                    title: 'Input By',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'input_date_formatted',
                    title: 'Input Date',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'update_by',
                    title: 'Update By',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'update_date_formatted',
                    title: 'Update Date',
                    sortable: true,
                    width: 120,
                    visible: false
                },
                {
                    field: 'username',
                    title: 'Username',
                    sortable: true,
                    width: 120,
                    visible: false
                },

                // ========== ACTIONS ==========
                {
                    width: '100%',
                    field: 'action',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    clickToSelect: true,
                    events: window.eventsPegawai,
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
            console.log('✅ Edit button clicked!', row);

            // Reset validasi
            $('.form-pegawai').removeClass('was-validated');

            // Tampilkan modal
            $('#modal-pegawai').modal('show');

            // Judul modal
            $('.modal-title').text('Form Edit Pegawai');

            // Tombol simpan
            $('.save-btn')
                .html('<span class="fa fa-check"></span> Update')
                .removeAttr('disabled');

            // Set ID untuk update
            $('input[name="id"]').val(row.id);

            // ============================================
            // DATA PERUSAHAAN
            // ============================================
            $('input[name="anak_perusahaan"]').val(row.anak_perusahaan || '');
            $('input[name="rumah_sakit"]').val(row.rumah_sakit || '');
            $('input[name="nomor_sk_struktur"]').val(row.nomor_sk_struktur || '');
            $('input[name="jabatan"]').val(row.jabatan || '');
            $('input[name="penempatan"]').val(row.penempatan || '');
            $('input[name="lokasi_kerja"]').val(row.lokasi_kerja || '');

            // ============================================
            // DATA PRIBADI
            // ============================================
            $('input[name="nomor_pekerja"]').val(row.nomor_pekerja || '');
            $('input[name="nama_pekerja"]').val(row.nama_pekerja || '');
            $('select[name="jenis_kelamin"]').val(row.jenis_kelamin || '');
            $('select[name="agama"]').val(row.agama || '');
            $('input[name="nik"]').val(row.nik || '');
            $('select[name="status_pernikahan"]').val(row.status_pernikahan || '');
            $('select[name="golongan_darah"]').val(row.golongan_darah || '');
            $('select[name="disabilitas"]').val(row.disabilitas || '');
            $('input[name="tanggal_lahir"]').val(row.tanggal_lahir || '');

            // ============================================
            // DATA KEPEGAWAIAN
            // ============================================
            $('input[name="golongan_upah"]').val(row.golongan_upah || '');
            $('input[name="status_kepegawaian"]').val(row.status_kepegawaian || '');
            $('input[name="tmt_status_kepegawaian"]').val(row.tmt_status_kepegawaian || '');
            $('input[name="tmt_pwtt"]').val(row.tmt_pwtt || '');
            $('input[name="tmt_pwt"]').val(row.tmt_pwt || '');
            $('input[name="masa_kerja"]').val(row.masa_kerja || '');
            $('input[name="tanggal_akhir_kontrak"]').val(row.tanggal_akhir_kontrak || '');

            // ============================================
            // FUNGSI & GRADE
            // ============================================
            $('input[name="fungsi"]').val(row.fungsi || '');
            $('input[name="sub_fungsi"]').val(row.sub_fungsi || '');
            $('input[name="tmt_jabatan"]').val(row.tmt_jabatan || '');
            $('input[name="tmt_golongan_upah"]').val(row.tmt_golongan_upah || '');
            $('input[name="penyetaraan_jabatan_ap"]').val(row.penyetaraan_jabatan_ap || '');
            $('input[name="penyetaraan_golongan_upah_ap"]').val(row.penyetaraan_golongan_upah_ap || '');

            // ============================================
            // BANKING INFO
            // ============================================
            $('input[name="nama_bank"]').val(row.nama_bank || '');
            $('input[name="nomor_rekening"]').val(row.nomor_rekening || '');
            $('input[name="nama_rekening"]').val(row.nama_rekening || '');

            // ============================================
            // INSURANCE & TAX
            // ============================================
            $('input[name="nomor_bpjstk"]').val(row.nomor_bpjstk || '');
            $('input[name="nomor_bpjskesehatan"]').val(row.nomor_bpjskesehatan || '');
            $('input[name="nomor_npwp"]').val(row.nomor_npwp || '');

            // ============================================
            // CONTACT INFO
            // ============================================
            $('input[name="nomor_hp"]').val(row.nomor_hp || '');
            $('input[name="email"]').val(row.email || '');
            $('input[name="email_dinas"]').val(row.email_dinas || '');
            $('input[name="nomor_kontak_darurat"]').val(row.nomor_kontak_darurat || '');
            $('input[name="nama_kontak_darurat"]').val(row.nama_kontak_darurat || '');
            $('input[name="hubungan_kontak_darurat"]').val(row.hubungan_kontak_darurat || '');

            // ============================================
            // ADDRESS INFO
            // ============================================
            $('textarea[name="alamat_ktp"]').val(row.alamat_ktp || '');
            $('textarea[name="alamat_npwp"]').val(row.alamat_npwp || '');
            $('textarea[name="alamat_domisili"]').val(row.alamat_domisili || '');

            // ============================================
            // PROFESSIONAL LICENSES
            // ============================================
            $('input[name="nomor_str"]').val(row.nomor_str || '');
            $('input[name="str_seumur_hidup"]').val(row.str_seumur_hidup || '');
            $('input[name="masa_berlaku_str"]').val(row.masa_berlaku_str || '');
            $('input[name="nomor_sip"]').val(row.nomor_sip || '');
            $('input[name="masa_berlaku_sip"]').val(row.masa_berlaku_sip || '');
            $('input[name="asuransi_profesi"]').val(row.asuransi_profesi || '');
            $('input[name="nomor_polis"]').val(row.nomor_polis || '');
            $('input[name="masa_berlaku_asuransi"]').val(row.masa_berlaku_asuransi || '');

            // ============================================
            // EDUCATION
            // ============================================
            $('input[name="pend_diploma"]').val(row.pend_diploma || '');
            $('input[name="pend_s1"]').val(row.pend_s1 || '');
            $('input[name="pend_s2"]').val(row.pend_s2 || '');
            $('input[name="pend_s3"]').val(row.pend_s3 || '');
            $('input[name="kampus_terakhir"]').val(row.kampus_terakhir || '');
            $('input[name="jenjang_pendidikan_terakhir"]').val(row.jenjang_pendidikan_terakhir || '');
            $('textarea[name="keterangan"]').val(row.keterangan || '');

            // ============================================
            // SYSTEM INFO
            // ============================================
            $('input[name="input_by"]').val(row.input_by || '');
            $('input[name="input_date"]').val(row.input_date || '');
            $('input[name="update_by"]').val(row.update_by || '');
            $('input[name="update_date"]').val(row.update_date || '');
            $('input[name="temp_username"]').val(row.temp_username || '');
            $('input[name="username"]').val(row.username || '');

            console.log('Form populated with data for ID:', row.id);
        },

        'click .btn-delete': function(e, value, row, index) {
            console.log('✅ Delete button clicked!', row);

            var url = "{{ route('pegawai-delete', ':id') }}";
            url = url.replace(':id', row.id);

            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Anda yakin ingin menghapus data pegawai "' + (row.nama_pekerja || 'ini') + '"?',
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
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Menghapus...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success: function(res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                            setTimeout(() => {
                                Swal.close();
                            }, 1500);

                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data'
                            });
                            console.error('Delete error:', xhr.responseText);
                        }
                    }).done(function() {
                        $tablePegawai.bootstrapTable('refresh');
                    });
                }
            });
        }
    };

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
