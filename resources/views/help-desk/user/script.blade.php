<script type="text/javascript">
    // Variable Name
    var $table = $("#table_helpdesk");

    // Open Modal
    $(document).on("click", ".add-btn", function () {
        $(".form-helpdesk").removeClass("was-validated");
        $("#helpdesk-modal").modal("show");
        $(".modal-title").text("Form Tambah Help Desk");
        $(".save-btn")
            .html('<span class="fa fa-check"></span> Simpan')
            .removeAttr("disabled");

        $('input[name="keterangan"]').val("");
    });

    // Save
    $(document).on("click", ".save-btn", function () {
        var forms = document.getElementsByClassName("form-helpdesk");
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                var url = "{{ route('user.helpdesk-store') }}";
                var type = "POST";
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $(".form-helpdesk").serialize(),
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
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert("error", xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert(
                                "info",
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

    // ---------------------------------------------------------------------------------------------
    // init table
    function initTable() {
        $table.bootstrapTable("destroy").bootstrapTable({
            height: 500,
            locale: "en-US",
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: true,
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
            columns: [
                {
                    field: "id",
                    title: "ID",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row, index) {
                        return index + 1;
                    },
                },
                {
                    field: "keterangan",
                    title: "Keterangan",
                    sortable: true,
                },
                {
                    field: "tanggal",
                    title: "Tanggal",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row) {
                        if (!value) return "-";
                        const date = new Date(value);
                        return date.toLocaleDateString("id-ID", {
                            day: "numeric",
                            month: "long",
                            year: "numeric",
                        });
                    },
                },
                {
                    field: "status",
                    title: "Status",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row) {
                        let badgeClass = "";
                        switch (row.status) {
                            case "accept":
                                badgeClass =
                                    "badge rounded-pill bg-primary fs-6";
                                break;
                            case "on-progress":
                                badgeClass =
                                    "badge rounded-pill bg-warning fs-6";
                                break;
                            case "done":
                                badgeClass =
                                    "badge rounded-pill bg-success fs-6";
                                break;
                            default:
                                badgeClass = "badge rounded-pill bg-light";
                        }
                        return `<span class="${badgeClass}">${row.status}</span>`;
                    },
                    events: window.operateChange,
                },
                {
                    field: "created_at",
                    title: "Created At",
                    sortable: true,
                    align: "center",
                    formatter: function (value, row) {
                        if (!row.created_at) return "-";
                        const date = new Date(row.created_at);
                        return date.toLocaleString("id-ID", {
                            hour: "2-digit",
                            minute: "2-digit",
                        });
                    },
                    events: window.operateChange,
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
                    $.notify(
                        {
                            icon: "fa fa-check",
                            title: error,
                            message: xhr.responseJSON.message,
                        },
                        {
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
                        }
                    );
                } else if (xhr.status == 500) {
                    $.notify(
                        {
                            icon: "icon-info-alt",
                            title: "Error",
                            message: "Silahkan hubungi IT Rumah Sakit!",
                        },
                        {
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
                        }
                    );
                }
            },
            responseHandler: function (data) {
                return data;
            },
        });
    }

    function actionsFunction(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            "</button>",
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu">',
            `<a class="dropdown-item btn-chat" href="javascript:void(0)" data-helpdesk-id="${row.id}"><i class="fa fa-comment text-primary"></i> Chat</a>`,

            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            "</div>",
            "</div>",
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
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

        // loadChat(helpdeskId); // COMMENT dulu sementara
        $("#chatModal").modal("show");
    });
    // Window operateChange Status
    // window.operateChange = {
    //     'click .update-status': function(e, value, row, index) {
    //         var url = "{{ route('master-data.icd-9.update-status', ':id') }}";
    //         url = url.replace(':id', row.id);
    //         $.ajax({
    //             url: url,
    //             type: "POST",
    //             data: {
    //                 status: e.target.checked ? 1 : 0,
    //                 _token: "{{ csrf_token() }}"
    //             },
    //             success: function(res, status, xhr) {
    //                 if (xhr.status == 200 && res.success == true) {
    //                     Alert('success', res.message);
    //                 } else {
    //                     Alert('warnig', res.message);
    //                 }
    //                 $table.bootstrapTable('refresh');
    //             },
    //             error: function(xhr, status, error) {
    //                 if (xhr.status == 400) {
    //                     var errors = xhr.responseJSON.errors;
    //                     Alert('danger', res.message);
    //                 } else if (xhr.status == 500) {
    //                     Alert('warnig', "Silahkan hubungi IT Rumah Sakit!");
    //                 }
    //             }
    //         });
    //     }
    // }
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.1/echo.iife.js"></script>

<script>
    window.Echo = new Echo({
        broadcaster: "pusher",
        key: "local", // sama dengan config PUSHER_APP_KEY
        wsHost: "simrs.local", // domain laragon custom
        wsPort: 6001, // port websocket
        forceTLS: false,
        encrypted: false,
        disableStats: true,
    });

    // Subscribe channel untuk user
    window.Echo.channel("helpdesk-user").listen(
        "HelpdeskStatusUpdated",
        (e) => {
            console.log("Helpdesk diupdate oleh admin:", e);

            $.notify(
                {
                    message: `
                <div class="d-flex align-items-start">
                    <i class="fa fa-info-circle text-white me-2 fs-5"></i>
                    <div>
                        <strong>Helpdesk Diperbarui!</strong><br>
                        ID: <b>${e.id}</b><br>
                        Status: <b>${e.status}</b><br>
                        Keterangan: ${e.keterangan || "-"}
                    </div>
                </div>
            `,
                },
                {
                    type: "primary", // ubah sesuai kebutuhan: info, warning, danger
                    allow_dismiss: true,
                    delay: 4000,
                    showProgressbar: true,
                    timer: 300,
                    z_index: 1127,
                }
            );

            // Opsional: refresh tabel user
            $table.bootstrapTable("refresh");
        }
    );
</script>
