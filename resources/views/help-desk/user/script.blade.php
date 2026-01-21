<script type="text/javascript">
    // Variable Name
    var $table = $("#table_helpdesk");

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#helpdesk-modal"),
        allowClear: true
    });

    //tanggal
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
    $('#btn-attach').on('click', function () {
        $('#lampiran').trigger('click');
    });

    //upload foto multiple
    let fileBuffer = new DataTransfer();
    $(document).on('change', 'input[name="lampiran[]"]', function () {
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
            $('#preview-images').append(`
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
        $('#helpdesk-modal').modal('hide');
    });

    // HAPUS FOTO
    $(document).on('click', '.btn-remove', function () {

        const $item = $(this).closest('.col-md-2');

        // ambil index yang BENAR hanya dari preview
        const removeIndex = $('#preview-images')
            .children('.col-md-2')
            .index($item);

        const input = document.querySelector('input[name="lampiran[]"]');

        let newBuffer = new DataTransfer();

        Array.from(fileBuffer.files).forEach((file, i) => {
            if (i !== removeIndex) {
                newBuffer.items.add(file);
            }
        });

        fileBuffer = newBuffer;
        input.files = fileBuffer.files;

        // refresh preview
        $('#preview-images').empty();
        Array.from(fileBuffer.files).forEach((file, index) => {
            renderPreview(file, index);
        });
    });

    // close modal
    $('#modal-preview-image').on('hidden.bs.modal', function () {
        $('#helpdesk-modal').modal('show');
    });


    $(document).on("click", ".add-btn", function () {
        $(".form-helpdesk").removeClass("was-validated");
        $("#helpdesk-modal").modal("show");
        $(".modal-title").text("Form Tambah Help Desk");
        $('.save-btn').show();
        $('.btn-attach').show();
        $('.btn-attach2').hide();
        $('label:contains("Lampiran Selesai")').hide();
        $('label:contains("Catatan")').hide();
        $(".save-btn").html('<span class="fa fa-check"></span> Simpan').removeAttr("disabled");
        $('#preview-images').empty();
        $('#preview-images2').empty().hide();
        $('input[name="id"]').val("");
        $('textarea[name="keterangan"]').val("").prop('readonly', false);
        $('input[name="judul_laporan"]').val("").prop('readonly', false);
        $('#lampiran').val('');
        $('#lampiran_selesai').val('');
        $('select[name="kategori"]').val('').trigger('change').prop('disabled', false);
        $('select[name="prioritas"]').val('').trigger('change').prop('disabled', false);
        $('textarea[name="catatan"]').hide();
        fileBuffer = new DataTransfer();
    });


    // Save
    let saveBtnProcessing = false;
    $(document).on("click", ".save-btn", function (event) {
        event.preventDefault();
        if (saveBtnProcessing) return; // kalau sedang proses, langsung stop
        saveBtnProcessing = true;
        var $btn = $(this);
        if ($btn.prop('disabled')) return;
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Memproses...');
        var forms = document.getElementsByClassName("form-helpdesk");
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.stopPropagation();
                saveBtnProcessing = false;
            } else {
                var url = "{{ route('user.helpdesk-store') }}";
                var type = "POST";

                // FormData dari form
                let myformData = new FormData(form);
                myformData.delete('lampiran[]');


                // Append file dari fileBuffer
                if (fileBuffer.files.length > 0) {
                    console.log('📤 Uploading', fileBuffer.files.length, 'files');
                    Array.from(fileBuffer.files).forEach((file, index) => {
                        myformData.append('lampiran[]', file, file.name);
                        console.log(
                            `  ${index + 1}. ${file.name} (${(file.size / 1024).toFixed(2)} KB)`
                        );
                    });
                } else {
                    console.log('No files selected');
                }

                // Debug FormData
                console.log('=== FormData Contents ===');
                for (let pair of myformData.entries()) {
                    if (pair[1] instanceof File) {
                        console.log(`${pair[0]} => FILE: ${pair[1].name}`);
                    } else {
                        console.log(`${pair[0]} => ${pair[1]}`);
                    }
                }

                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: myformData,
                    beforeSend: function () {
                        $(".save-btn")
                            .html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            )
                            .attr("disabled", "disabled");
                    },
                    complete: function () {
                        $(".save-btn")
                            .html('<span class="fa fa-check"></span> Simpan')
                            .removeAttr("disabled");
                        saveBtnProcessing = false;
                    },
                    success: function (res, status, xhr) {
                        if (xhr.status == 200 && res.success == true) {
                            Alert("success", res.message);
                            $table.bootstrapTable("refresh");
                        } else {
                            Alert("warning", res.message);
                        }
                        $("#helpdesk-modal").modal("hide");
                        form.classList.remove("was-validated");

                        // Reset fileBuffer agar bisa upload lagi
                        fileBuffer = new DataTransfer();
                        $('#lampiran').val('');
                        $('#preview-images').empty();
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert("error", xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert("info",
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove("was-validated");
                    },
                });
            }
            form.classList.add("was-validated");
        });
    });


    // Page Load Event
    $(function () {
        initTable();
    });


    // init table
    function initTable() {
        $table.bootstrapTable("destroy").bootstrapTable({
            height: 500,
            locale: "en-US",
            search: true,
            // showColumns: true,
            showPaginationSwitch: true,
            // showToggle: true,
            showExport: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, "all"],
            showRefresh: true,
            stickyHeader: false,
            fixedColumns: false,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            exportTypes: ["json", "csv", "txt", "excel"],
            url: "{{ route('user.helpdesk-views') }}",

            queryParams: function (params) {
                return {
                    limit: params.limit,
                    offset: params.offset,
                    search: params.search,

                    tgl_awal: $('#tgl_awal').val(),
                    tgl_akhir: $('#tgl_akhir').val()
                };
            },

            columns: [{
                field: "id",
                sortable: true,
                align: "center",
                formatter: function (value, row, index) {
                    return index + 1;
                },
            },
            {
                field: "tiket",
                sortable: true,
            },
            {
                field: "judul_laporan",
                sortable: true,
            },
            {
                field: "kategori",
                sortable: true,
            },
            {
                field: "prioritas",
                sortable: true,
            },
            {
                field: "status",
                sortable: true,
                align: "center",
                formatter: function (value, row) {
                    let badgeClass = "";
                    switch (row.status) {
                        case "accept":
                            badgeClass =
                                "badge rounded-pill bg-primary fs-9";
                            break;
                        case "on-progress":
                            badgeClass =
                                "badge rounded-pill bg-warning fs-9";
                            break;
                        case "done":
                            badgeClass =
                                "badge rounded-pill bg-success fs-9";
                            break;
                        default:
                            badgeClass = "badge rounded-pill bg-light";
                    }
                    return `<span class="${badgeClass}" style="cursor:pointer; display:inline-block; width:75px; text-align:center;">${row.status}</span>`;
                },
                events: window.operateChange,
            },
            {
                field: "created_at",
                sortable: true,
                align: "center",
            },
            {
                field: "updated_by",
                sortable: true,
                align: "center",
            },
            {
                field: 'tgl_terima',
                sortable: true,
                align: 'center',
            },
            {
                field: 'tgl_selesai',
                sortable: true,
                align: 'center',
            },
            {
                field: "action",
                title: "Aksi",
                align: "center",
                formatter: actionsFunction,
                events: window.operateEvents,
            },
            ],
            error: function (xhr, status, error) {
                if (xhr.status == 400) {
                    $.notify({
                        icon: "fa fa-check",
                        title: error,
                        message: xhr.responseJSON.message,
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
            responseHandler: function (data) {
                return data;
            },
        });
    }

    function actionsFunction(value, row, index) {
        if (row.status === 'accept') {
            return [
                '<div class="dropdown icon-dropdown">',
                '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
                '<i class="icon-more-alt"></i>',
                "</button>",
                '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
                '<a class="dropdown-item btn-infoo" href="javascript:void(0)"><i class="fa fa-info text-secondary"></i> Informasi</a>',
                `<a class="dropdown-item btn-chat" href="javascript:void(0)" data-helpdesk-id="${row.id}"><i class="fa fa-comment text-primary"></i> Chat</a>`,
                '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
                "</div>",
                "</div>",
            ].join("");
        } else {
            return [
                '<div class="dropdown icon-dropdown">',
                '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
                '<i class="icon-more-alt"></i>',
                "</button>",
                '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
                '<a class="dropdown-item btn-infoo" href="javascript:void(0)"><i class="fa fa-info text-secondary"></i> Informasi</a>',
                `<a class="dropdown-item btn-chat" href="javascript:void(0)" data-helpdesk-id="${row.id}"><i class="fa fa-comment text-primary"></i> Chat</a>`,
                "</div>",
                "</div>",
            ].join("");
        }
    }

    // Handle events button actions
    window.operateEvents = {
        "click .btn-infoo": function (e, value, row, index) {
            $('#helpdesk-modal').modal('show');
            $('.modal-title').text('Nomor Tiket : ' + row.tiket);
            $('.save-btn').hide();
            $('.btn-attach').hide();
            $('.btn-attach2').hide();
            $('label:contains("Lampiran Selesai")').show();
            $('label:contains("Catatan")').show();

            $('input[name="id"]').val(row.id);
            $('textarea[name="keterangan"]').val(row.keterangan).prop('readonly', true);
            $('input[name="judul_laporan"]').val(row.judul_laporan).prop('readonly', true);
            $('select[name="kategori"]').val(row.kategori).trigger('change').prop('disabled', true);
            $('select[name="prioritas"]').val(row.prioritas).trigger('change').prop('disabled', true);
            $('textarea[name="catatan"]').val(row.catatan).prop('readonly', true).show();

            // reset preview
            $('#preview-images').empty();
            $('#preview-images2').empty().show();
            fileBuffer = new DataTransfer();

            //Gambar 2
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
                    </div>
                </div>
            </div>
        `);
                });
            }

            // tampilkan lampiran dari DB
            if (Array.isArray(row.lampiran)) {
                row.lampiran.forEach((img, index) => {
                    const imageUrl = `/uploads/images/help-desk/${img}`; // SESUAIKAN PATH

                    $('#preview-images').append(`
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
                        </div>
                    </div>
                </div>
            `);
                });
            }
        },
        "click .btn-delete": function (e, value, row, index) {
            var url = "{{ route('user.helpdesk-delete', ':id') }}";
            url = url.replace(":id", row.id);
            Swal.fire({
                icon: "warning",
                title: "Peringatan",
                text: "Anda yakin ingin menghapus data ini?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert("success", res.message);
                            } else {
                                Alert("warnig", res.message);
                            }
                        },
                    }).done(function () {
                        $table.bootstrapTable("refresh");
                    });
                }
            });
        },
    };
    $(document).on("click", ".btn-chat", function () {
        var helpdeskId = $(this).data("helpdesk-id");
        if (!helpdeskId) return;
        // $("#chatOpponentUsername").text(row.user_name);

        $("#chatOpponentName").text("Loading...");
        $("#chatTypingStatus").text("");

        $.ajax({
            url: "/chat/opponent/" + helpdeskId,
            type: "GET",
            success: function (res) {
                // $("#chatOpponentFullName").text(res.nama_lengkap);
                $("#chatOpponentUsername").text(res.username);
            },
            error: function () {
                $("#chatOpponentName").text("Unknown");
            }
        });

        $("#chatModal").modal("show");
    });

    // Mengambil data jam sekarang
    function updateLastSeen() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12; // jam 0 jadi 12
        minutes = minutes < 10 ? '0' + minutes : minutes;

        $('#lastSeen').text(`Last Seen ${hours}:${minutes} ${ampm}`);
    }
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.1/echo.iife.js"></script>

<script>
    window.Echo = new Echo({
        broadcaster: "pusher",
        key: "local",
        wsHost: window.location.hostname,
        wsPort: 6001,
        forceTLS: false,
        encrypted: false,
        disableStats: true,
    });

    // Subscribe channel untuk user
    window.Echo.channel("helpdesk-user").listen(
        "HelpdeskStatusUpdated",
        (e) => {
            console.log("Helpdesk diupdate oleh admin:", e);

            $.notify({
                message: `
                <div class="d-flex align-items-start">
                    <i class="fa fa-info-circle text-white me-2 fs-5"></i>
                    <div>
                        <strong>Helpdesk Diperbarui!</strong><br>
                        Status: <b>${e.status}</b><br>
                        Tiket: <b>${e.tiket}</b><br>
                        Laporan: ${e.judul_laporan || "-"}
                    </div>
                </div>
            `,
            }, {
                type: "primary", // ubah sesuai kebutuhan: info, warning, danger
                allow_dismiss: true,
                delay: 4000,
                showProgressbar: true,
                timer: 300,
                z_index: 1127,
            });

            // Opsional: refresh tabel user
            $table.bootstrapTable("refresh");
        }
    );
</script>