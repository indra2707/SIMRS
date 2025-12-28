<script type="text/javascript">
    // Disable form validation untuk navigasi step
    document.addEventListener('DOMContentLoaded', function () {
        // Cegah browser validasi form secara otomatis
        var form = document.querySelector('.form-pegawai');
        if (form) {
            form.setAttribute('novalidate', 'novalidate');
        }

        // Hapus semua required kecuali nama_pekerja
        $('.form-pegawai input, .form-pegawai select, .form-pegawai textarea')
            .not('[name="nama_pekerja"]')
            .removeAttr('required');
    });

    // Tabel
    var $tablePegawai = $('#table_pegawai');

    // select2 global
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-pegawai"),
        allowClear: true

    });

    // select2 ajax init function
    function InitSelect2(element, options) {

        if (element.hasClass("select2-hidden-accessible")) {
            element.select2('destroy');
            element.empty();
        }

        element.prop('disabled', false);

        element.select2({
            theme: "bootstrap-5",
            dropdownParent: options.dropdownParent,
            placeholder: "---- Pilih Salah Satu ----",
            allowClear: true,
            ajax: {
                url: options.url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let data = { search: params.term };
                    if (typeof options.data === 'function') {
                        data = Object.assign(data, options.data(params));
                    }
                    return data;
                },
                processResults: function (response) {
                    return { results: response.data };
                }
            }
        });

        // SET VALUE EDIT
        if (options.initialValue && options.initialText) {
            let option = new Option(
                options.initialText,
                options.initialValue,
                true,
                true
            );
            element.append(option).trigger('change');
        }
    }



    // aktifkan saat SK dipilih
    const jabatanSelect = $("select[name='id_jabatan']");
    jabatanSelect.prop('disabled', true);
    $("select[name='id_sk_struktur']").on('change', function () {
        const idSk = $(this).val();
        jabatanSelect.val(null).trigger('change');
        if (idSk) {
            jabatanSelect.prop('disabled', false);
        } else {
            jabatanSelect.prop('disabled', true);
        }
    });


    // disabled nomor pekerja input
    const $statusPegawai = $("select[name='status_kepegawaian']");
    const $nomorPekerja = $("input[name='nomor_pekerja']");
    function toggleNomorPekerja() {
        const status = $statusPegawai.val();
        const hideList = ['PWTT', 'PWT'];
        const disableList = [
            'Mitra Pegawai',
            'Mitra Dokter',
            'Outsourcing',
            'Internship'
        ];

        if (hideList.includes(status)) {
            $nomorPekerja.val('').prop('readonly', false).removeAttr('required');
            return;
        }

        if (disableList.includes(status)) {
            $nomorPekerja.val('').prop('readonly', true).removeAttr('required');
            loadNomorPekerja(status);
        } else {
            $nomorPekerja.prop('readonly', false).attr('required', true);
        }
    }
    $statusPegawai.on('change', toggleNomorPekerja);
    toggleNomorPekerja();


    // Nomor Pekerja otomatis
    let isLoadingNomorPekerja = false;

    function loadNomorPekerja(status) {

        if (isLoadingNomorPekerja) return;
        isLoadingNomorPekerja = true;

        $.ajax({
            url: "{{ route('pegawai.generate-nomor-pekerja') }}",
            type: "GET",
            dataType: "json",
            data: {
                status_pegawai: status
            },
            success: function (res) {

                // set hanya jika masih kosong
                if (!$nomorPekerja.val()) {
                    $nomorPekerja.val(res.nomor_pekerja);
                }
            },
            error: function () {
                alert('Gagal mengambil nomor pekerja');
            },
            complete: function () {
                isLoadingNomorPekerja = false;
            }
        });
    }



    var reader = new FileReader();

    // Main Wrapper Selector
    const avatarFileUpload = document.getElementById('AvatarFileUpload');
    // Preview Wrapper Selector
    const imageViewer = avatarFileUpload.querySelector('.selected-image-holder>img');
    // Image Selector button Selector
    const imageSelector = avatarFileUpload.querySelector('.avatar-selector-btn');
    // Image Input File Selector - PERBAIKAN: Ganti 'profil' jadi 'foto'
    const imageInput = avatarFileUpload.querySelector('input[name="foto"]');

    /** Trigger Browsing Image to Upload */
    imageSelector.addEventListener('click', e => {
        e.preventDefault()
        // Trigger Image input click
        imageInput.click()
    });

    imageInput.addEventListener('change', e => {
        // Open File eader
        reader.onload = function () {
            // Preview Image
            imageViewer.src = reader.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });


    // Open Modal Pegawai
    $(document).on('click', '.add-btn', function () {
        $('#modal-pegawai').modal('show');
        $('.modal-title').text('Form Tambah Pegawai');

        var form = document.querySelector('.form-pegawai');

        // Reset form native
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
        }

        // Reset khusus
        $('input[name="id"]').val(''); // Kosongkan ID
        $("select[name='id_jabatan']").val('').trigger('change');

        // Reset Select2 (jika ada)
        $('.select2').each(function () {
            $(this).val(null).trigger('change');
        });

        InitSelect2($("select[name='id_bank']"), {
            url: "{{ route('get-select-bank') }}",
            dropdownParent: $("#modal-pegawai")
        });

        InitSelect2($("select[name='id_sub_fungsi']"), {
            url: "{{ route('get-select-fungsi') }}",
            dropdownParent: $("#modal-pegawai")
        });

        InitSelect2($("select[name='id_sk_struktur']"), {
            url: "{{ route('get-select-sk-struktur') }}",
            dropdownParent: $("#modal-pegawai")
        });

        InitSelect2($("select[name='id_jabatan']"), {
            url: "{{ route('get-select-jabatan') }}",
            dropdownParent: $("#modal-pegawai"),
            data: function (params) {
                return {
                    id_sk_struktur: $("select[name='id_sk_struktur']").val()
                };
            }
        });

        // Reset checkbox/radio ke unchecked
        $('input[type="checkbox"]').prop('checked', false);
        $('input[type="radio"]').prop('checked', false);

        // Reset file input & preview
        $('input[type="file"]').val('');
        if (typeof imageViewer !== 'undefined') {
            imageViewer.src = "{{ asset('assets/images/avatar/user2.png') }}";
        }

        // Reset button state
        $('.save-btn')
            .html('<span class="fa fa-check"></span> Simpan')
            .removeAttr('disabled');
        resetWizardToFirstStep();
    });

    // Fungsi untuk konversi tanggal dari DD/MM/YYYY ke YYYY-MM-DD


    // Save/update
    // Nonaktifkan validasi HTML5 native
    // $('.form-pegawai').attr('novalidate', 'novalidate');

    $(document).on('click', '.save-btn', function (e) {
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
        // var form = forms[0];
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                const fileInput = $('#foto')[0],
                    file = fileInput.files[0],
                    maxSize = 1 * 1024 * 1024,
                    allowedTypes = ['image/jpeg', 'image/png',
                        'image/jpg'
                    ]; // Example allowed types
                // Validate file type
                if (file && !allowedTypes.includes(file.type)) {
                    Alert('warning',
                        'Jenis berkas tidak valid. Jenis yang diperbolehkan: JPEG, PNG, JPG.');
                    return;
                }
                // Validate file size
                if (file && file.size > maxSize) {
                    Alert('warning', 'Ukuran file melebihi batas maksimal 1 MB');
                    return;
                }
                let myformData = new FormData(form);
                console.log('=== FORM DATA ===');
                for (let [key, value] of myformData.entries()) {
                    console.log(key + ':', value);
                }

                // Pastikan nama_pekerja ada
                if (!myformData.get('nama_pekerja')) {
                    Alert('warning', 'Nama pekerja wajib diisi!');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: myformData,
                    enctype: 'multipart/form-data',
                    beforeSend: function () {
                        $('.save-btn').html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disabled', 'disabled');
                    },
                    complete: function () {
                        $('.save-btn').html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr('disabled');
                    },
                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert('success', res.message);
                            $('#modal-pegawai').modal('hide');
                            resetWizardToFirstStep();
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
                    formatter: function (value, row, index) {
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
                    field: 'id_sk_struktur',
                    title: 'No SK Struktur',
                    sortable: true,
                    width: 120,
                },
                {
                    field: 'id_jabatan',
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
                    field: 'id_sub_fungsi',
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
                    field: 'id_bank',
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

            onLoadSuccess: function (data) {
                console.log(' Data loaded:', data.length, 'records');
            },

            onLoadError: function (status, xhr) {
                console.error(' Load error:', status);
                console.error('Response:', xhr.responseText);
            },

            responseHandler: function (res) {
                console.log(' Response received:', res);
                return res; // Return langsung karena sudah array
            }
        });
    }

    //  Call function saat document ready
    $(document).ready(function () {
        // Paksa nonaktifkan validasi
        setTimeout(function () {
            $('.form-pegawai').attr('novalidate', 'novalidate');

            // Hapus semua required kecuali nama_pekerja
            $('.form-pegawai input, .form-pegawai select, .form-pegawai textarea')
                .not('[name="nama_pekerja"]')
                .each(function () {
                    $(this).removeAttr('required');
                    $(this).prop('required', false);
                });
        }, 500);

        // Intercept button next SEBELUM validasi plugin berjalan
        $(document).on('click', '.btn-next', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            // Hapus validasi visual
            $('.form-pegawai').removeClass('was-validated');
            $('.form-control').removeClass('is-invalid');

            // Trigger next step secara manual
            var $currentFieldset = $(this).closest('fieldset');
            var $nextFieldset = $currentFieldset.next('fieldset');

            if ($nextFieldset.length) {
                $currentFieldset.fadeOut(300, function () {
                    $nextFieldset.fadeIn(300);
                });

                // Update progress bar
                var stepIndex = $nextFieldset.index('fieldset');
                var totalSteps = $('fieldset').length;
                var progressPercentage = ((stepIndex + 1) / totalSteps) * 100;

                $('.f1-progress-line').css('width', progressPercentage + '%');

                // Update step indicator
                $('.f1-step').removeClass('active').removeClass('activated');
                $('.f1-step').eq(stepIndex).addClass('active');
                $('.f1-step').eq(stepIndex).prevAll().addClass('activated');
            }

            return false;
        });

        // $(document).on('click', '.btn-next', function (e) {
        //     e.preventDefault();

        //     let $currentFieldset = $(this).closest('fieldset');
        //     let inputs = $currentFieldset.find('input, select, textarea').filter(':visible');
        //     let isValid = true;

        //     // reset error
        //     $('.form-control').removeClass('is-invalid');

        //     inputs.each(function () {
        //         // KHUSUS select2
        //         if ($(this).hasClass('select2') && !$(this).val()) {
        //             $(this).addClass('is-invalid');
        //             isValid = false;
        //             return false;
        //         }

        //         // HTML5 validation
        //         if (!this.checkValidity()) {
        //             this.reportValidity();
        //             $(this).addClass('is-invalid');
        //             isValid = false;
        //             return false;
        //         }
        //     });

        //     // JANGAN LANJUT JIKA TIDAK VALID
        //     if (!isValid) {
        //         return false;
        //     }

        //     // JIKA VALID → LANJUT STEP
        //     let $nextFieldset = $currentFieldset.next('fieldset');

        //     if ($nextFieldset.length) {
        //         $currentFieldset.fadeOut(300, function () {
        //             $nextFieldset.fadeIn(300);
        //         });

        //         // progress bar
        //         let stepIndex = $('fieldset').index($nextFieldset);
        //         let totalSteps = $('fieldset').length;
        //         let progressPercentage = ((stepIndex + 1) / totalSteps) * 100;

        //         $('.f1-progress-line').css('width', progressPercentage + '%');

        //         // step indicator
        //         $('.f1-step').removeClass('active activated');
        //         $('.f1-step').eq(stepIndex).addClass('active');
        //         $('.f1-step').eq(stepIndex).prevAll().addClass('activated');
        //     }
        // });


        // Handle button previous
        $(document).on('click', '.btn-previous', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var $currentFieldset = $(this).closest('fieldset');
            var $prevFieldset = $currentFieldset.prev('fieldset');

            if ($prevFieldset.length) {
                $currentFieldset.hide();
                $prevFieldset.show();

                // Update progress bar
                var stepIndex = $prevFieldset.index('fieldset');
                var totalSteps = $('fieldset').length;
                var progressPercentage = ((stepIndex + 1) / totalSteps) * 100;

                $('.f1-progress-line').css('width', progressPercentage + '%');

                // Update step indicator
                $('.f1-step').removeClass('active');
                $('.f1-step').eq(stepIndex).addClass('active');
            }

            return false;
        });
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
        'click .btn-edit': function (e, value, row, index) {
            // console.log('Edit button clicked!', row);

            $('.form-pegawai').removeClass('was-validated');
            $('#modal-pegawai').modal('show');
            resetWizardToFirstStep();
            $('.modal-title').text('Form Edit Pegawai');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id"]').val(row.id);

            // DATA PERUSAHAAN
            $('input[name="anak_perusahaan"]').val(row.anak_perusahaan || '');
            $('input[name="penempatan"]').val(row.penempatan || '');
            $('input[name="lokasi_kerja"]').val(row.lokasi_kerja || '');

            $('select[name="id_sk_struktur"]').val(row.id_sk_struktur || '').trigger('change');

            InitSelect2($("select[name='id_sk_struktur']"), {
                url: "{{ route('get-select-sk-struktur') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_sk_struktur
            });

            InitSelect2($("select[name='id_jabatan']"), {
                url: "{{ route('get-select-jabatan') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_jabatan,
                data: function (params) {
                    return {
                        id_sk_struktur: $("select[name='id_sk_struktur']").val()
                    };
                }
            });

            // DATA PRIBADI
            $('select[name="status_kepegawaian"]').val(row.status_kepegawaian || '').trigger('change');
            $('input[name="nomor_pekerja"]').val(row.nomor_pekerja || '');
            $('input[name="nama_pekerja"]').val(row.nama_pekerja || '');
            $('input[name="nik"]').val(row.nik || '');
            $('input[name="tanggal_lahir"]').val(row.tanggal_lahir || '');

            // Select2 fields
            $('select[name="jenis_kelamin"]').val(row.jenis_kelamin || '').trigger('change');
            $('select[name="agama"]').val(row.agama || '').trigger('change');
            $('select[name="status_pernikahan"]').val(row.status_pernikahan || '').trigger('change');
            $('select[name="golongan_darah"]').val(row.golongan_darah || '').trigger('change');
            $('select[name="disabilitas"]').val(row.disabilitas || '').trigger('change');

            // DATA KEPEGAWAIAN
            $('select[name="golongan_upah"]').val(row.golongan_upah || '').trigger('change');
            $('input[name="tmt_status_kepegawaian"]').val(row.tmt_status_kepegawaian || '');
            $('input[name="tmt_pwtt"]').val(row.tmt_pwtt || '');
            $('input[name="tmt_pwt"]').val(row.tmt_pwt || '');
            $('input[name="masa_kerja"]').val(row.masa_kerja || '');
            $('input[name="tanggal_akhir_kontrak"]').val(row.tanggal_akhir_kontrak || '');

            // FUNGSI & GRADE
            $('select[name="fungsi"]').val(row.fungsi || '').trigger('change');
            $('input[name="tmt_jabatan"]').val(row.tmt_jabatan || '');
            $('input[name="tmt_golongan_upah"]').val(row.tmt_golongan_upah || '');
            $('input[name="penyetaraan_jabatan_ap"]').val(row.penyetaraan_jabatan_ap || '');
            $('input[name="penyetaraan_golongan_upah_ap"]').val(row.penyetaraan_golongan_upah_ap || '');

            InitSelect2($("select[name='id_sub_fungsi']"), {
                url: "{{ route('get-select-fungsi') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_sub_fungsi,
                initialText: row.id_sub_fungsi
            });

            // BANKING INFO
            $('input[name="nomor_rekening"]').val(row.nomor_rekening || '');
            $('input[name="nama_rekening"]').val(row.nama_rekening || '');

            InitSelect2($("select[name='id_bank']"), {
                url: "{{ route('get-select-bank') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_bank
            });

            // INSURANCE & TAX
            $('input[name="nomor_bpjstk"]').val(row.nomor_bpjstk || '');
            $('input[name="nomor_bpjskesehatan"]').val(row.nomor_bpjskesehatan || '');
            $('input[name="nomor_npwp"]').val(row.nomor_npwp || '');

            // CONTACT INFO
            $('input[name="nomor_hp"]').val(row.nomor_hp || '');
            $('input[name="email"]').val(row.email || '');
            $('input[name="email_dinas"]').val(row.email_dinas || '');
            $('input[name="nomor_kontak_darurat"]').val(row.nomor_kontak_darurat || '');
            $('input[name="nama_kontak_darurat"]').val(row.nama_kontak_darurat || '');
            $('select[name="hubungan_kontak_darurat"]').val(row.hubungan_kontak_darurat || '').trigger('change');

            // ADDRESS INFO
            $('textarea[name="alamat_ktp"]').val(row.alamat_ktp || '');
            $('textarea[name="alamat_npwp"]').val(row.alamat_npwp || '');
            $('textarea[name="alamat_domisili"]').val(row.alamat_domisili || '');

            // PROFESSIONAL LICENSES
            $('input[name="nomor_str"]').val(row.nomor_str || '');
            $('select[name="str_seumur_hidup"]').val(row.str_seumur_hidup || '').trigger('change');
            $('input[name="masa_berlaku_str"]').val(row.masa_berlaku_str || '');
            $('input[name="nomor_sip"]').val(row.nomor_sip || '');
            $('input[name="masa_berlaku_sip"]').val(row.masa_berlaku_sip || '');
            $('input[name="asuransi_profesi"]').val(row.asuransi_profesi || '');
            $('input[name="nomor_polis"]').val(row.nomor_polis || '');
            $('input[name="masa_berlaku_asuransi"]').val(row.masa_berlaku_asuransi || '');

            // EDUCATION
            $('input[name="pend_diploma"]').val(row.pend_diploma || '');
            $('input[name="pend_s1"]').val(row.pend_s1 || '');
            $('input[name="pend_s2"]').val(row.pend_s2 || '');
            $('input[name="pend_s3"]').val(row.pend_s3 || '');
            $('input[name="kampus_terakhir"]').val(row.kampus_terakhir || '');
            $('select[name="jenjang_pendidikan_terakhir"]').val(row.jenjang_pendidikan_terakhir || '').trigger('change');
            $('textarea[name="keterangan"]').val(row.keterangan || '');

            // SYSTEM INFO
            imageInput.value = '';
            if (row.foto) {
                imageViewer.src = "{{ asset('/uploads/images/foto-pegawai') }}" + '/' + row.foto;
            } else {
                imageViewer.src = "{{ asset('assets/images/avatar/user2.png') }}";
            }

            console.log('Form populated with data for ID:', row.id);
        },

        'click .btn-delete': function (e, value, row, index) {
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
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Menghapus...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                            setTimeout(() => {
                                Swal.close();
                            }, 1500);

                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data'
                            });
                            console.error('Delete error:', xhr.responseText);
                        }
                    }).done(function () {
                        $tablePegawai.bootstrapTable('refresh');
                    });
                }
            });
        }
    };

    // Window operateChange Status Pegawai
    window.updateStatusPegawai = {
        'click .update-status': function (e, value, row, index) {
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
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $tablePegawai.bootstrapTable('refresh');
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

    // Fungsi untuk reset wizard ke step 1
    function resetWizardToFirstStep() {
        // Sembunyikan semua fieldset
        $('fieldset').hide();

        // Tampilkan fieldset pertama
        $('fieldset').first().show();

        // Reset progress bar ke 0%
        var totalSteps = $('fieldset').length;
        var progressPercentage = (1 / totalSteps) * 100;
        $('.f1-progress-line').css('width', progressPercentage + '%');

        // Reset step indicator
        $('.f1-step').removeClass('active').removeClass('activated');
        $('.f1-step').first().addClass('active');
    }

    // Page Load Event
    $(function () {
        initTable();
    });
</script>