<script type="text/javascript">
    // Variable Name
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-helpdesk"),
        allowClear: true

    });

    $(".select3").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        allowClear: true,
        width: 'style'
    });
    
    var $table = $('#table_helpdesk');
    $('.js-daterangepicker').datepicker({
        dateFormat: 'dd/mm/yyyy',
        range: true,
        multipleDates: true,
        multipleDatesSeparator: ' - ',
        autoClose: true,
        toggleSelected: false,

        onSelect: function (formattedDate, date, inst) {
            // jika belum pilih 2 tanggal, hentikan
            if (!date || date.length < 2) {
                return;
            }

            // date berupa array [startDate, endDate]
            let start = date[0];
            let end = date[1];

            // format ke Y-m-d untuk database
            $('#tgl_awal').val(formatDate(start));
            $('#tgl_akhir').val(formatDate(end));

            $table.bootstrapTable("refresh");
        }
    });

    let now = new Date();
    let firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    let lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

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

    $('.js-daterangepicker').val(
        formatDisplay(firstDay) + ' - ' + formatDisplay(lastDay)
    );

    $('#tgl_awal').val(formatDate(firstDay));
    $('#tgl_akhir').val(formatDate(lastDay));

    // onclick upload
    $('#btn-attach2').on('click', function () {
        $('#lampiran_selesai').trigger('click');
    });

    //upload foto multiple
    let fileBuffer = new DataTransfer();
    $(document).on('change', 'input[name="lampiran_selesai[]"]', function () {
        const input = this;
        const newFiles = Array.from(input.files);

        // validasi total maksimal 5
        if ((fileBuffer.files.length + newFiles.length) > 5) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Maksimal 5 gambar',
            });
            input.value = '';
            return;
        }

        // tambahkan file baru
        newFiles.forEach((file) => {
            fileBuffer.items.add(file);
            renderPreview(file, fileBuffer.files.length - 1);
        });

        // sinkron ke input
        input.files = fileBuffer.files;

        // reset input supaya bisa upload file yg sama lagi
        // input.value = '';
    });


    // perview gambar
    function renderPreview(file, index) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#preview-images2').append(`
            <div class="col-md-2 mb-2" data-index="${index}">
                <div class="position-relative">
                    <img src="${e.target.result}"
                         class="img-thumbnail preview-img"
                         style="height:100px;object-fit:cover;cursor:pointer">

                    <div class="position-absolute bottom-0 end-0 m-1 d-flex gap-1">
                        <button type="button"
                                class="btn btn-light btn-xs btn-preview"
                                data-src="${e.target.result}">
                            <i class="fa fa-eye"></i>
                        </button>

                        <button type="button"
                                class="btn btn-light btn-xs btn-remove"
                                data-index="${index}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
        };
        reader.readAsDataURL(file);
    }

    // LIHAT FOTO
    $(document).on('click', '.btn-preview', function () {
        $('#preview-large').attr('src', $(this).data('src'));
        $('#modal-preview-image').modal('show');
        $('#modal-helpdesk').modal('hide');
    });

    // HAPUS FOTO
    $(document).on('click', '.btn-remove', function () {
        const $item = $(this).closest('.col-md-2');
        // ambil index yang BENAR hanya dari preview
        const removeIndex = $('#preview-images2')
            .children('.col-md-2')
            .index($item);

        const input = document.querySelector('input[name="lampiran_selesai[]"]');
        let newBuffer = new DataTransfer();
        Array.from(fileBuffer.files).forEach((file, i) => {
            if (i !== removeIndex) {
                newBuffer.items.add(file);
            }
        });

        fileBuffer = newBuffer;
        input.files = fileBuffer.files;

        // refresh preview
        $('#preview-images2').empty();
        Array.from(fileBuffer.files).forEach((file, index) => {
            renderPreview(file, index);
        });
    });

    // close modal
    $('#modal-preview-image').on('hidden.bs.modal', function () {
        $('#modal-helpdesk').modal('show');
    });

    // Save gambar2
    let saveBtnProcessing = false;
    $(document).on("click", ".save-btn", function (event) {
        event.preventDefault();

        if (saveBtnProcessing) return;
        saveBtnProcessing = true;

        const $btn = $(this);
        const form = document.querySelector('.form-helpdesk');
        const id = $('input[name="id"]').val();
        const catatan = $('textarea[name="catatan"]').val();

        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            saveBtnProcessing = false;
            return;
        }

        $btn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm"></span> Memproses...');

        const url = "{{ route('admin.helpdesk-update', ':id') }}".replace(':id', id);

        const formData = new FormData(form);

        // HAPUS file bawaan (WAJIB)
        formData.delete('lampiran_selesai[]');

        // APPEND manual (WAJIB)
        const inputSelesai = document.getElementById('lampiran_selesai');
        if (inputSelesai && inputSelesai.files.length > 0) {
            Array.from(inputSelesai.files).forEach(file => {
                formData.append('lampiran_selesai[]', file);
            });
        }

        /* DEBUG */
        console.log('=== FormData FINAL ===');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(`${key}: FILE -> ${value.name}`);
            } else {
                console.log(`${key}: ${value}`);
            }
        }

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",

            success(res) {
                if (res.success) {
                    Alert("success", res.message);
                    $("#modal-helpdesk").modal("hide");
                    $table.bootstrapTable("refresh");
                } else {
                    Alert("warning", res.message);
                }
            },

            error(xhr) {
                Alert("error", xhr.responseJSON?.message || "Terjadi kesalahan sistem");
            },

            complete() {
                $btn.prop('disabled', false)
                    .html('<span class="fa fa-check"></span> Simpan');
                saveBtnProcessing = false;
                form.classList.remove("was-validated");
            }
        });
    });


    // Page Load Event
    $(function () {
        initTable();
    });

    // ---------------------------------------------------------------------------------------------
    // init table
    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: true,
            showExport: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ['json', 'csv', 'txt', 'excel'],
            url: "{{ route('admin.helpdesk-views') }}",
            uniqueId: "id",
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
                // {
                //     field: 'id',
                //     title: 'ID',
                //     sortable: true,
                //     align: 'center',
                //     formatter: function(value, row, index) {
                //         return index + 1;
                //     }
                // },
                {
                    field: 'tiket',
                    sortable: true,
                },
                {
                    field: 'judul_laporan',
                    sortable: true,
                    formatter: value =>
                        value && value.length > 50 ?
                            value.slice(0, 50) + '...' : value
                },
                {
                    field: 'kategori',
                    sortable: true,
                    align: 'center',

                },
                {
                    field: 'prioritas',
                    sortable: true,
                    align: 'center',

                },
                {
                    field: 'nama_lengkap',
                    sortable: true,
                    align: 'center',
                },
                {
                    field: 'created_at',
                    sortable: true,
                    align: 'center',
                },
                {
                    field: 'tgl_terima',
                    sortable: true,
                    align: 'center',
                    visible: false
                },
                {
                    field: 'tgl_selesai',
                    sortable: true,
                    align: 'center',
                    visible: false
                },
                {
                    field: 'updated_by',
                    sortable: true,
                    align: 'center',
                    visible: false
                },
                {
                    field: 'status',
                    sortable: true,
                    align: 'center',
                    formatter: function (value, row) {
                        let badgeClass = '';
                        let text = value || 'unknown';
                        let clickableClass = '';
                        let cursorStyle = 'cursor:pointer;';
                        let clickable = true;

                        switch (value) {
                            case 'accept':
                                badgeClass = 'badge rounded-pill bg-primary fs-8';
                                clickableClass = 'update-status';
                                break;
                            case 'on-progress':
                                badgeClass = 'badge rounded-pill bg-warning fs-8';
                                clickableClass = 'update-status';
                                break;
                            case 'done':
                                badgeClass = 'badge rounded-pill bg-success fs-8';
                                clickable = false; // tidak bisa diklik
                                cursorStyle = 'cursor:default;';
                                break;
                            default:
                                badgeClass = 'badge rounded-pill bg-light';
                                clickable = false;
                                cursorStyle = 'cursor:default;';
                        }

                        text = text.charAt(0).toUpperCase() + text.slice(1); // kapital huruf pertama

                        if (clickable) {
                            return `<span class="${badgeClass} ${clickableClass}" 
                        style="${cursorStyle} display:inline-block; width:75px; text-align:center;" 
                        data-id="${row.id}">${text}</span>`;
                        } else {
                            return `<span class="${badgeClass}" 
                        style="${cursorStyle} display:inline-block; width:75px; text-align:center;">${text}</span>`;
                        }
                    },
                    events: window.operateChange // <-- ini wajib supaya klik bisa dideteksi
                },
                {
                    field: 'catatan',
                    sortable: true,
                    align: 'center',
                    visible: false
                },
                {
                    field: 'action',
                    align: 'center',
                    formatter: actionsFunction,
                    events: window.operateEvents
                }
            ],

            error: function (xhr, status, error) {
                if (xhr.status == 400) {
                    var errors = xhr.responseJSON.errors;
                    $.notify({
                        icon: 'fa fa-check',
                        title: error,
                        message: xhr.responseJSON.message
                    }, {
                        type: 'danger',
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
                } else if (xhr.status == 500) {
                    $.notify({
                        icon: 'icon-info-alt',
                        title: 'error',
                        message: "Silahkan hubungi IT Rumah Sakit!"
                    }, {
                        type: 'danger',
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
                }
            },
            responseHandler: function (data) {
                return data;
            }
        });
    }

    function actionsFunction(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
            `<a class="dropdown-item btn-chat" href="javascript:void(0)" data-helpdesk-id="${row.id}"><i class="fa fa-comment text-primary"></i> Chat</a>`,
            `<a class="dropdown-item btn-infos" href="javascript:void(0)" data-helpdesk-id="${row.id}"><i class="fa fa-list text-primary"></i> Info</a>`,
            // '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }


    let isViewMode = false;
    // Handle events button actions
    window.operateEvents = {
        'click .btn-infos': function (e, value, row, index) {
            console.log('=== DEBUG INFO ===');
            console.log('Row Data:', row);
            console.log('Gambar Value:', row.gambar);
            isViewMode = true;
            fileBuffer = new DataTransfer();
            $('#modal-helpdesk').modal('show');
            $('.modal-title').text('Detail Helpdesk');
            $('#preview-images2').empty();
            $('#btn-attach').hide(); // Sembunyikan tombol attach
            $('#lampiran').hide(); // Sembunyikan input lampiran


            // Isi data form
            $('input[name="id"]').val(row.id);
            $('input[name="judul_laporan"]').val(row.judul_laporan).prop('readonly', true);
            $('input[name="tiket"]').val(row.tiket).prop('readonly', true);
            $('input[name="nama_lengkap"]').val(row.nama_lengkap).prop('readonly', true);
            $('input[name="username"]').val(row.username).prop('readonly', true);
            $('input[name="status"]').val(row.status).prop('readonly', true);

            var $badge = $('#status-badge');
            var statusText = row.status;
            $badge.removeClass('bg-primary bg-success bg-warning');
            if (statusText.toLowerCase() === 'accept') {
                $badge.addClass('bg-primary');
            } else if (statusText.toLowerCase() === 'on-progress') {
                $badge.addClass('bg-warning');
            } else {
                $badge.addClass('bg-success');
            }
            $badge.text(statusText.charAt(0).toUpperCase() + statusText.slice(1));

            $('select[name="kategori"]').val(row.kategori).trigger('change').prop('disabled', true);
            $('select[name="prioritas"]').val(row.prioritas).trigger('change').prop('disabled', true);
            $('textarea[name="keterangan"]').val(row.keterangan).prop('readonly', true);
            $('textarea[name="catatan"]').val(row.catatan);


            // tampilkan lampiran dari DB
            if (Array.isArray(row.gambar2)) {
                row.gambar2.forEach((img) => {
                    const imageUrl = `/uploads/images/help-desk/${img}`;

                    $('#preview-images2').append(`
            <div class="col-md-2 mb-2">
                <div class="position-relative">
                    <img src="${imageUrl}"
                         class="img-thumbnail"
                         style="height:100px;object-fit:cover;cursor:pointer">
                    <div class="position-absolute bottom-0 end-0 m-1">
                        <button type="button"
                                class="btn btn-light btn-xs btn-preview"
                                data-src="${imageUrl}">
                            <i class="fa fa-eye"></i>
                        </button>
                         <button type="button"
                                class="btn btn-light btn-xs btn-remove"
                                data-index="${index}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `);
                });
            }


            // GUNAKAN #preview-images (sesuai view)
            $('#preview-images').empty();

            console.log('Container exists:', $('#preview-images').length);

            if (row.gambar) {
                console.log('Gambar ada, mencoba parse...');
                try {
                    let images;

                    // Cek apakah sudah array atau masih string
                    if (typeof row.gambar === 'string') {
                        images = JSON.parse(row.gambar);
                        console.log('Parsed images:', images);
                    } else {
                        images = row.gambar;
                        console.log('Images sudah array:', images);
                    }

                    if (Array.isArray(images) && images.length > 0) {
                        console.log('Jumlah gambar:', images.length);

                        images.forEach((filename, index) => {
                            let imgUrl = '/uploads/images/help-desk/' + filename;
                            console.log(`Image ${index + 1}:`, imgUrl);

                            $('#preview-images').append(`
                            <div class="col-md-2 mb-2">
                                <div class="position-relative">
                                    <img src="${imgUrl}"
                                         class="img-thumbnail preview-img"
                                         style="height:100px;object-fit:cover;cursor:pointer"
                                         onerror="console.error('Failed to load:', '${imgUrl}'); this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' width=\\'100\\' height=\\'100\\'%3E%3Crect fill=\\'%23ddd\\' width=\\'100\\' height=\\'100\\'/%3E%3Ctext x=\\'50%25\\' y=\\'50%25\\' text-anchor=\\'middle\\' fill=\\'%23999\\'%3EError%3C/text%3E%3C/svg%3E';">
                                    <div class="position-absolute bottom-0 end-0 m-1">
                                        <button type="button"
                                                class="btn btn-light btn-xs btn-preview-view"
                                                data-src="${imgUrl}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `);
                        });
                        console.log('Gambar berhasil ditambahkan ke DOM');
                    } else {
                        console.log('Images bukan array atau kosong');
                        $('#preview-images').html('<p class="text-muted">Tidak ada gambar</p>');
                    }
                } catch (e) {
                    console.error('Error parsing gambar:', e);
                    console.error('Raw gambar value:', row.gambar);
                    $('#preview-images').html('<p class="text-danger">Error memuat gambar: ' + e.message +
                        '</p>');
                }
            } else {
                console.log('Row.gambar null atau undefined');
                $('#preview-images').html('<p class="text-muted">Tidak ada gambar</p>');
            }

            console.log('=== END DEBUG ===');
        },
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('admin.helpdesk-destroy', ':id') }}";
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
                                Alert('warnig', res.message);
                            }
                        }
                    }).done(function () {
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }
    $(document).on('click', '.btn-preview-view', function () {
        $('#preview-large').attr('src', $(this).data('src'));
        $('#modal-preview-image').modal('show');
        $('#modal-helpdesk').modal('hide');
    });

    // Kembali ke modal helpdesk saat modal preview ditutup
    $('#modal-preview-image').on('hidden.bs.modal', function () {
        if (isViewMode) {
            // Jangan trigger event hidden dari modal-helpdesk
            $('#modal-helpdesk').modal('show');
        }
    });

    // Reset form saat modal ditutup
    // $('#modal-helpdesk').on('hidden.bs.modal', function () {
    //     // Cek apakah modal preview sedang dibuka
    //     if (!$('#modal-preview-image').hasClass('show')) {
    //         console.log('Modal helpdesk ditutup, reset form');

    //         // Reset flag
    //         isViewMode = false;

    //         // Enable kembali semua input
    //         $('#modal-helpdesk input, #modal-helpdesk textarea, #modal-helpdesk select').attr('disabled',
    //             false);
    //         $('#btn-attach').show();
    //         $('.save-btn').show();

    //         // Clear preview images
    //         $('#preview-images').empty();

    //         // Reset form
    //         $('.form-helpdesk')[0].reset();
    //         $('.form-helpdesk').removeClass('was-validated');
    //     } else {
    //         console.log('Modal preview masih terbuka, jangan reset');
    //     }
    // });

    // Window operateChange Status
    let btnProcessing = false;
    window.operateChange = {
        'click .update-status': function (e, value, row, index) {
            e.preventDefault();
            var $badge = $(e.target); // cegah event bubbling
            if (btnProcessing) return;
            btnProcessing = true;
            $badge.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm"></span>');

            var url = "{{ route('admin.helpdesk-update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}"
                },
                success: function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warnig', res.message);
                    }
                    $table.bootstrapTable('refresh');
                },
                error: function (xhr, status, error) {
                    if (xhr.status == 400) {
                        var errors = xhr.responseJSON.errors;
                        $.notify({
                            icon: 'fa fa-check',
                            title: error,
                            message: xhr.responseJSON.message
                        }, {
                            type: 'danger',
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
                    } else if (xhr.status == 500) {
                        $.notify({
                            icon: 'icon-info-alt',
                            title: 'error',
                            message: "Silahkan hubungi IT Rumah Sakit!"
                        }, {
                            type: 'danger',
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
                    }
                },
                complete: function () {
                    // Tahan spinner selama 800ms supaya terlihat lebih jelas
                    setTimeout(function () {
                        btnProcessing = false;
                        // Badge akan otomatis kembali normal setelah refresh table
                    }, 800);
                }
            });
        }
    }
    window.addEventListener('refresh-admin-table', function () {
        $('#table_helpdesk').bootstrapTable('refresh');
    });

    $(document).on('click', '.btn-chat', function () {
        var helpdeskId = $(this).data('helpdesk-id');
        if (!helpdeskId) return;

        // loadChat(helpdeskId); // COMMENT dulu sementara
        $('#chatModal').modal('show');
    });

    function updateLastSeen() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;

        $('#lastSeen').text(`Last Seen ${hours}:${minutes} ${ampm}`);
    }
</script>
// Echo listener
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.1/echo.iife.js"></script>

<script>
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'local',
        wsHost: window.location.hostname,
        wsPort: 6001, // sesuai config
        forceTLS: false,
        encrypted: false,
        disableStats: true
    });

    window.Echo.channel('helpdesk-admin')
        .listen('HelpdeskCreated', (e) => {
            console.log('Helpdesk baru:', e);

            // refresh bootstrapTable
            $('#table_helpdesk').bootstrapTable('refresh');

            // Tampilkan notifikasi popup
            $.notify({
                message: `
                <div class="d-flex align-items-start">
                    <i class="fa fa-bell text-primary me-2 fs-5"></i>
                    <div>
                        <strong>Helpdesk Baru!</strong><br>
                        Ticket: <b>${e.tiket} - ${e.nama_lengkap}/${e.department}</b><br>
                        Laporan: <b>${e.judul_laporan} - ${e.prioritas}</b>
                    </div>
                </div>
            `
            }, {
                type: 'primary', // info, success, warning, danger
                allow_dismiss: true,
                delay: 4000,
                showProgressbar: true,
                timer: 300,
                z_index: 1127,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });

        });
</script>