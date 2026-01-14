<script type="text/javascript">
    // Tabel
    var $tablePegawai = $('#table_pegawai');

    // select2 global
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-pegawai"),
        allowClear: true

    });

    // Page Load Event
    $(function() {
        initTable();
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
                data: function(params) {
                    let data = {
                        search: params.term
                    };
                    if (typeof options.data === 'function') {
                        Object.assign(data, options.data(params));
                    }
                    return data;
                },
                processResults: function(response) {
                    return {
                        results: response.data
                    };
                }
            }
        });

        // Set Value Edit
        if (options.initialValue && options.initialText) {
            const option = new Option(
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
    $("select[name='id_sk_struktur']").on('change', function() {
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
    let isLoadingNomorPekerja = false;


    // Nomor Otomatis
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
            success: function(res) {

                // set hanya jika masih kosong
                if (!$nomorPekerja.val()) {
                    $nomorPekerja.val(res.nomor_pekerja);
                }
            },
            error: function() {
                alert('Gagal mengambil nomor pekerja');
            },
            complete: function() {
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
        reader.onload = function() {
            // Preview Image
            imageViewer.src = reader.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    });


    // Open Modal Pegawai
    $(document).on('click', '.add-btn', function() {
        clearAllValidationErrors();

        var form = document.querySelector('.form-pegawai');
        if (form) {
            form.reset();
        }

        resetWizardToFirstStep();

        // Setup modal
        $('#modal-pegawai').modal('show');
        $('.modal-title').text('Form Tambah Pegawai');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');

        $('input[name="id"]').val('');

        // Reset Select2
        $('.select2').each(function() {
            $(this).val(null).trigger('change');
        });

        $("select[name='disabilitas']").val('Tidak').trigger('change');

        // Init Select2
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
            data: function(params) {
                return {
                    id_sk_struktur: $("select[name='id_sk_struktur']").val()
                };
            }
        });

        // Reset inputs
        $('input[type="checkbox"]').prop('checked', false);
        $('input[type="radio"]').prop('checked', false);
        $('input[type="file"]').val('');

        if (typeof imageViewer !== 'undefined') {
            imageViewer.src = "{{ asset('assets/images/avatar/user2.png') }}";
        }

        // Clear validasi KEDUA KALI (setelah init)
        setTimeout(function() {
            clearAllValidationErrors();
        }, 250);
    });


    // Save/update
    $(document).on('click', '.save-btn', function(e) {
        e.preventDefault();

        let $form = $('.form-pegawai');
        let form = $form[0];

        if (!form.checkValidity()) {
            // form.classList.add('was-validated');
            Alert('warning', 'Mohon lengkapi semua field yang wajib diisi');
            return;
        }

        // Validasi foto (opsional)
        const fileInput = $('#foto')[0];
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 1024 * 1024;
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type)) {
                Alert('warning', 'Format foto hanya JPG, JPEG, atau PNG');
                return;
            }
            if (file.size > maxSize) {
                Alert('warning', 'Ukuran foto maksimal 1 MB');
                return;
            }
        }

        let formData = new FormData(form);
        let id = $('input[name="id"]').val();

        // Tentukan URL
        let url = id ?
            "{{ route('pegawai-update', ':id') }}".replace(':id', id) :
            "{{ route('pegawai-store') }}";

        // Tambahkan _method = PUT kalau edit
        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST', // SELALU POST
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: () => {
                $('.save-btn').html(
                    '<span class="spinner-border spinner-border-sm"></span> Menyimpan...').prop(
                    'disabled', true);
            },
            success: function(res) {
                if (res.success) {
                    Alert('success', res.message || 'Data berhasil diperbarui!');
                    $('#modal-pegawai').modal('hide');
                    $tablePegawai.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message || 'Gagal menyimpan data');
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan';
                if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                Alert('error', msg);
            },
            complete: () => {
                $('.save-btn').html(id ? '<span class="fa fa-check"></span> Update' :
                        '<span class="fa fa-check"></span> Simpan')
                    .prop('disabled', false);
            }
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
                    field: 'nama_rumah_sakit',
                    title: 'Rumah Sakit',
                    sortable: true,
                    width: 150,
                    visible: false
                },
                {
                    field: 'no_sk_struktur',
                    title: 'No SK Struktur',
                    sortable: true,
                    width: 120,
                },
                {
                    field: 'nama_jabatan',
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
                    width: 120,
                    visible: false
                },

                // ========== PERSONAL INFO ==========
                {
                    field: 'jenis_kelamin',
                    title: 'L/P',
                    sortable: true,
                    align: 'center',
                    width: 60,
                    formatter: function(value, row, index) {
                        return value == 'Laki-laki' ? 'L' : 'P';
                    }

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
                    visible: false
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
                    width: 100,
                    visible: false
                },
                {
                    field: 'tanggal_akhir_kontrak_formatted',
                    title: 'Akhir Kontrak',
                    sortable: true,
                    width: 120,
                    visible: false

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
                    field: 'nama_sub_fungsi',
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
                    visible: false
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
                    width: 150,
                    visible: false
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
                console.log(' Data loaded:', data.length, 'records');
            },

            onLoadError: function(status, xhr) {
                console.error(' Load error:', status);
                console.error('Response:', xhr.responseText);
            },

            responseHandler: function(res) {
                console.log(' Response received:', res);
                return res; // Return langsung karena sudah array
            }
        });
    }

    $(document).ready(function() {
        // Handle tombol NEXT dengan validasi hanya untuk field required
        $(document).on('click', '.btn-next', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            let $currentFieldset = $(this).closest('fieldset');
            let isValid = true;
            let errorMessages = [];

            // Bersihkan error sebelumnya
            $currentFieldset.find('.is-invalid').removeClass('is-invalid');
            $currentFieldset.find('.invalid-feedback').remove();
            $currentFieldset.find('.select2-container').removeClass(
                'border border-danger is-invalid-select2');

            $currentFieldset.find('input:visible[required], textarea:visible[required]').each(
                function() {
                    let $input = $(this);
                    let value = $input.val();

                    // Jika ini datepicker, cek apakah ada nilai (bisa berformat tanggal)
                    if ($input.hasClass('js-datepicker')) {
                        if (!value || value === '' || value === '____-__-__' || value === '//') {
                            isValid = false;
                            $input.addClass('is-invalid');
                            let fieldName = $input.closest('.mb-3, .form-group').find('label')
                                .clone()
                                .children().remove().end().text().trim();
                            errorMessages.push(fieldName || $input.attr('name'));
                            if (!$input.next('.invalid-feedback').length) {
                                $input.after(
                                    '<div class="invalid-feedback d-block">Field ini wajib diisi</div>'
                                );
                            }
                        }
                    } else {
                        // Input biasa
                        if (!value || $.trim(value) === '') {
                            isValid = false;
                            $input.addClass('is-invalid');
                            let fieldName = $input.closest('.mb-3, .form-group').find('label')
                                .clone()
                                .children().remove().end().text().trim();
                            errorMessages.push(fieldName || $input.attr('name'));
                            if (!$input.next('.invalid-feedback').length) {
                                $input.after(
                                    '<div class="invalid-feedback d-block">Field ini wajib diisi</div>'
                                );
                            }
                        }
                    }
                });

            // validadsi selcetbiasa
            $currentFieldset.find('select:visible[required]').not('.select2-hidden-accessible').each(
                function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        let fieldName = $(this).closest('.mb-3, .form-group').find('label').clone()
                            .children().remove().end().text().trim();
                        errorMessages.push(fieldName || $(this).attr('name'));
                        $(this).after(
                            '<div class="invalid-feedback d-block">Field ini wajib dipilih</div>'
                        );
                    }
                });

            // validasi select2
            $currentFieldset.find('select.select2[required]').each(function() {
                let $select = $(this);
                let value = $select.val();
                let isEmpty = !value || value === '' || value === null || (Array.isArray(
                    value) && value.length === 0);

                if (isEmpty) {
                    isValid = false;
                    $select.next('.select2-container').addClass(
                        'is-invalid-select2 border border-danger');

                    let fieldName = $select.closest('.mb-3, .form-group').find('label').clone()
                        .children().remove().end().text().trim();
                    errorMessages.push(fieldName || $select.attr('name'));

                    // Tambahkan pesan error jika belum ada
                    if (!$select.next('.select2-container').next('.invalid-feedback').length) {
                        $select.next('.select2-container').after(
                            '<div class="invalid-feedback d-block">Field ini wajib dipilih</div>'
                        );
                    }
                }
            });

            if (!isValid) {
                // Scroll halus ke field error pertama + highlight (tanpa buka Select2 otomatis)
                setTimeout(function() {
                    let $firstInvalid = $currentFieldset.find(
                        '.is-invalid:first, .is-invalid-select2:first').first();

                    if ($firstInvalid.length) {
                        let $target = $firstInvalid.hasClass('select2-hidden-accessible') ||
                            $firstInvalid.hasClass('is-invalid-select2') ?
                            $firstInvalid.next('.select2-container') :
                            $firstInvalid;

                        // Pastikan elemen visible dan memiliki offset
                        if ($target.length && $target.is(':visible') && $target.offset()) {
                            let modalBody = $('.modal-body');

                            // Cek apakah modal body ada dan visible
                            if (modalBody.length && modalBody.offset()) {
                                let targetOffset = $target.offset().top;
                                let modalOffset = modalBody.offset().top;
                                let scrollTop = modalBody.scrollTop();

                                modalBody.animate({
                                    scrollTop: targetOffset - modalOffset + scrollTop -
                                        150
                                }, 600);

                                // Highlight field error
                                $target.effect('highlight', {
                                    color: '#f8d7da'
                                }, 2000);
                            }
                        } else {
                            // Fallback: scroll ke atas jika offset tidak tersedia
                            $('.modal-body').animate({
                                scrollTop: 0
                            }, 400);
                            console.warn('Target element not visible or has no offset');
                        }
                    }
                }, 100);

                Alert('warning', 'Mohon lengkapi semua field yang wajib diisi');

                return; // ← PALING PENTING: Hentikan eksekusi sepenuhnya
            }

            // ====== JIKA VALID → LANJUT KE STEP BERIKUTNYA ======
            let $nextFieldset = $currentFieldset.next('fieldset');
            if ($nextFieldset.length) {
                $currentFieldset.fadeOut(300, function() {
                    $nextFieldset.fadeIn(300);
                    $('.modal-body').animate({
                        scrollTop: 0
                    }, 300);
                });

                // Update progress bar
                let stepIndex = $nextFieldset.index('fieldset');
                let totalSteps = $('fieldset').length;
                let progressPercentage = ((stepIndex + 1) / totalSteps) * 100;
                $('.f1-progress-line').css('width', progressPercentage + '%');

                // Update step indicator
                $('.f1-step').removeClass('active activated');
                $('.f1-step').eq(stepIndex + 1).addClass(
                    'active'); // +1 karena index fieldset mulai dari 0
                $('.f1-step').eq(stepIndex + 1).prevAll().addClass('activated');
            }
        });

        // Handle tombol PREVIOUS (tanpa validasi)
        $(document).on('click', '.btn-previous', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            let $currentFieldset = $(this).closest('fieldset');
            let $prevFieldset = $currentFieldset.prev('fieldset');

            if ($prevFieldset.length) {
                $currentFieldset.fadeOut(300, function() {
                    $prevFieldset.fadeIn(300);
                    $('.modal-body').animate({
                        scrollTop: 0
                    }, 300);
                });

                let stepIndex = $prevFieldset.index('fieldset');
                let totalSteps = $('fieldset').length;
                let progressPercentage = ((stepIndex + 1) / totalSteps) * 100;
                $('.f1-progress-line').css('width', progressPercentage + '%');

                $('.f1-step').removeClass('active').removeClass('activated');
                $('.f1-step').eq(stepIndex).addClass('active');
                $('.f1-step').eq(stepIndex).prevAll().addClass('activated');
            }

            return false;
        });

        // Clear error saat user input/select
        $('.form-pegawai').on('input change', 'input, select, textarea', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();

            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).next('.select2-container').removeClass(
                    'is-invalid-select2 border border-danger');
                $(this).next('.select2-container').next('.invalid-feedback').remove();
            }
        });

        // Clear error saat Select2 dibuka atau dipilih
        $(document).on('select2:open select2:select', '.select2', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.select2-container').removeClass('is-invalid-select2 border border-danger');
            $(this).next('.select2-container').next('.invalid-feedback').remove();
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
        'click .btn-edit': function(e, value, row, index) {

            clearAllValidationErrors();

            $('.form-pegawai').removeClass('was-validated');
            resetWizardToFirstStep();

            $('#modal-pegawai').modal('show');
            $('.modal-title').text('Form Edit Pegawai');
            $('.save-btn').html('<span class="fa fa-check"></span> Update').removeAttr('disabled');
            $('input[name="id"]').val(row.id);



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
            jabatanSelect.prop('disabled', false);
            console.log(row.no_sk_struktur, row.nama_jabatan);

            InitSelect2($("select[name='id_sk_struktur']"), {
                url: "{{ route('get-select-sk-struktur') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_sk_struktur,
                initialText: row.no_sk_struktur
            });

            InitSelect2($("select[name='id_jabatan']"), {
                url: "{{ route('get-select-jabatan') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_jabatan,
                initialText: row.nama_jabatan,
                data: function() {
                    return {
                        id_sk_struktur: $("select[name='id_sk_struktur']").val() || row
                            .id_sk_struktur
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
                initialText: row.nama_fungsi
            });

            // BANKING INFO
            $('input[name="nomor_rekening"]').val(row.nomor_rekening || '');
            $('input[name="nama_rekening"]').val(row.nama_rekening || '');

            InitSelect2($("select[name='id_bank']"), {
                url: "{{ route('get-select-bank') }}",
                dropdownParent: $("#modal-pegawai"),
                initialValue: row.id_bank,
                initialText: row.nama_bank
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
            $('select[name="hubungan_kontak_darurat"]').val(row.hubungan_kontak_darurat || '').trigger(
                'change');

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
            $('select[name="jenjang_pendidikan_terakhir"]').val(row.jenjang_pendidikan_terakhir || '').trigger(
                'change');
            $('textarea[name="keterangan"]').val(row.keterangan || '');

            // SYSTEM INFO
            imageInput.value = '';
            if (row.foto) {
                imageViewer.src = "{{ asset('/uploads/images/foto-pegawai') }}" + '/' + row.foto;
            } else {
                imageViewer.src = "{{ asset('assets/images/avatar/user2.png') }}";
            }

            console.log('Form populated with data for ID:', row.id);
            setTimeout(function() {
                clearAllValidationErrors();
            }, 300);
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

    function resetWizardToFirstStep() {
        $('fieldset').hide();
        $('fieldset').first().show();

        var totalSteps = $('fieldset').length;
        var progressPercentage = (1 / totalSteps) * 100;
        $('.f1-progress-line').css('width', progressPercentage + '%');

        $('.f1-step').removeClass('active').removeClass('activated');
        $('.f1-step').first().addClass('active');
    }

    // ============================================================
    // FUNGSI CLEAR VALIDASI - ULTIMATE VERSION
    // ============================================================
    function clearAllValidationErrors() {
        console.log('🧹 Membersihkan SEMUA validasi...');

        // 1. Hapus class Bootstrap validation
        $('.form-pegawai').removeClass('was-validated');

        // 2. Hapus class is-invalid dari semua input
        $('.form-pegawai input, .form-pegawai select, .form-pegawai textarea').removeClass('is-invalid');

        // 3. Hapus semua pesan error
        $('.form-pegawai .invalid-feedback').remove();

        // 4. Reset Select2 errors
        $('.form-pegawai .select2-container').removeClass('border border-danger is-invalid-select2');

        // 5. Reset custom validity (browser native)
        $('.form-pegawai input, .form-pegawai select, .form-pegawai textarea').each(function() {
            this.setCustomValidity('');
        });

        // 6. Reset inline styles
        $('.form-pegawai input, .form-pegawai select, .form-pegawai textarea').css({
            'border-color': '',
            'border': ''
        });

        console.log('✅ Validasi dibersihkan!');
    }


    // Saat modal MULAI dibuka
    $('#modal-pegawai').on('show.bs.modal', function() {
        console.log('▶️ Modal akan dibuka...');
        clearAllValidationErrors();
    });

    // Saat modal SUDAH terbuka
    $('#modal-pegawai').on('shown.bs.modal', function() {
        console.log('✅ Modal sudah terbuka');
        setTimeout(function() {
            clearAllValidationErrors();
        }, 100);
    });

    // Saat modal MULAI ditutup
    $('#modal-pegawai').on('hide.bs.modal', function() {
        console.log('▶️ Modal akan ditutup...');
        clearAllValidationErrors();
    });

    // Saat modal SUDAH ditutup
    $('#modal-pegawai').on('hidden.bs.modal', function() {
        console.log('✅ Modal sudah ditutup');

        var form = document.querySelector('.form-pegawai');
        if (form) {
            form.reset();
        }

        clearAllValidationErrors();
        resetWizardToFirstStep();
        $('.modal-body').scrollTop(0);

        // Final cleanup dengan delay
        setTimeout(function() {
            clearAllValidationErrors();
        }, 150);
    });

    // Inisialisasi ulang js-datepicker
    $(document).ready(function() {
        $('.js-datepicker').datepicker({
            language: 'en',
            dateFormat: 'yyyy-mm-dd',
            autoClose: true,
            onSelect: function(formattedDate, date, inst) {
                $(inst.el).val(formattedDate).trigger('change').trigger('input');
            }
        });
    });
    // Clear error saat user pilih tanggal di datepicker
    $('.form-pegawai').on('change input', 'input.js-datepicker[required]', function() {
        let $el = $(this);
        if ($el.val() && $el.val() !== '' && $el.val() !== '//') {
            $el.removeClass('is-invalid');
            $el.next('.invalid-feedback').remove();
        }
    });


    // ============================================================
    // IMPORT EXCEL FUNCTIONALITY
    // ============================================================

    // Open Modal Import
    $(document).on('click', '.import-btn', function() {
        $('#modal-import').modal('show');
        $('#form-import')[0].reset();
        $('#import-progress').addClass('d-none');
        $('.progress-bar').css('width', '0%').text('0%');
    });

    // Handle Import Submit
    $(document).on('submit', '#form-import', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let fileInput = $('#file_excel')[0];

        // Validasi file
        if (!fileInput.files.length) {
            Alert('warning', 'Silakan pilih file Excel terlebih dahulu');
            return;
        }

        let file = fileInput.files[0];
        let fileName = file.name;
        let fileSize = file.size;
        let fileExt = fileName.split('.').pop().toLowerCase();

        // Validasi ekstensi
        if (!['xlsx', 'xls', 'csv'].includes(fileExt)) {
            Alert('warning', 'Format file harus .xlsx atau .xls');
            return;
        }

        // Validasi ukuran (10MB)
        if (fileSize > 10 * 1024 * 1024) {
            Alert('warning', 'Ukuran file maksimal 10MB');
            return;
        }

        // Show progress bar
        $('#import-progress').removeClass('d-none');

        $.ajax({
            url: "{{ route('pegawai-import') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                // Upload progress
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        let percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('.progress-bar').css('width', percentComplete + '%')
                            .text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            beforeSend: function() {
                $('.btn-import-submit').prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm"></span> Mengimport...');
            },
            success: function(res) {
                if (res.success) {
                    Alert('success', res.message || 'Data berhasil diimport!');
                    $('#modal-import').modal('hide');
                    $tablePegawai.bootstrapTable('refresh');

                    // Reset form
                    $('#form-import')[0].reset();
                    $('#import-progress').addClass('d-none');
                    $('.progress-bar').css('width', '0%').text('0%');
                } else {
                    Alert('warning', res.message || 'Import gagal');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat import';

                if (xhr.status === 422) {
                    // Validation errors
                    let errors = xhr.responseJSON.errors;
                    let errorList = '<ul class="mb-0">';
                    $.each(errors, function(key, value) {
                        errorList += '<li>' + value[0] + '</li>';
                    });
                    errorList += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: errorList
                    });
                } else if (xhr.status === 500) {
                    // Server error
                    let error = xhr.responseJSON?.errors?.exception || 'Server error';

                    Swal.fire({
                        icon: 'error',
                        title: 'Import Gagal',
                        html: '<p>' + errorMsg + '</p><small class="text-muted">' + error +
                            '</small>'
                    });
                } else {
                    Alert('error', errorMsg);
                }

                console.error('Import error:', xhr.responseText);
            },
            complete: function() {
                $('.btn-import-submit').prop('disabled', false)
                    .html('<span class="fa fa-upload"></span> Import');
            }
        });
    });

    // Reset form saat modal ditutup
    $('#modal-import').on('hidden.bs.modal', function() {
        $('#form-import')[0].reset();
        $('#import-progress').addClass('d-none');
        $('.progress-bar').css('width', '0%').text('0%');
    });

    // Preview file name
    $(document).on('change', '#file_excel', function() {
        let fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $(this).next('.form-text').text('File dipilih: ' + fileName);
        }
    });
</script>
