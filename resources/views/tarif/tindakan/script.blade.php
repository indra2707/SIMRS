<script type="text/javascript">
    // Variable Name
    var $table = $('#table_tindakan_tarif');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-tarif-tindakan').removeClass('was-validated');
        $('#modal-tarif-tindakan').modal('show');
        $('.modal-title').text('Form Tambah Tarif Tindakan');
        var url = "{{ route('generate-kode-tarif-tindakan', ':id') }}";
        url = url.replace(':id', 1);
        $.get(url,
            function(data, textStatus, jqXHR) {
                $('input[name="kode"]').val(data['data']).attr('readonly', true);
            },
            "JSON"
        );




        // $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        // $('input[name="id"]').val('');
        // $('input[name="no_sk"]').val('');
        // $('input[name="tgl_mulai"]').val('');
        // $('input[name="tgl_akhir"]').val('');
        // $('textarea[name="deskripsi"]').val('');
    });


    // Save
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('tarif.tindakan.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('tarif.tindakan.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-tarif-tindakan');
        var validation = Array.prototype.filter.call(forms, function(form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                console.warn($('.form-tarif-tindakan').serialize());
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-tarif-tindakan').serialize(),
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
                            $table.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                        }
                        $('#modal-tarif-tindakan').modal('hide');
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info',
                                "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!"
                            );
                        }
                        form.classList.remove('was-validated');
                    }
                });
            }
            form.classList.add('was-validated');
        });
    });


    // Page Load Event
    $(function() {
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
            url: "{{ route('tarif.tindakan.view') }}",
            columns: [
                [{
                        title: 'No.',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        width: '5%',
                        formatter: function(value, row, index) {
                            return index + 1
                        }
                    },
                    {
                        field: 'kode_tarif',
                        sortable: true,
                    },
                    {
                        field: 'tindakan',
                        sortable: true,
                    },
                    {
                        field: 'tarif_rs',
                        sortable: true,
                    },
                    {
                        field: 'kelompok_tindakan',
                        sortable: true,
                    },
                    {
                        field: 'tipe',
                        sortable: true,
                    },
                    {
                        field: 'kategori_layanan',
                        sortable: true,
                    },
                    {
                        field: 'group_tindakan',
                        sortable: true,
                    },
                    {
                        field: 'cito',
                        sortable: true,
                    },
                    {
                        field: 'status_operasi',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        // title: 'STATUS',
                        field: 'status_tindakan',
                        sortable: true,
                        events: window.operateChange,
                        formatter: function(value, row, index) {
                            return [
                                '<div class="media-body text-center switch-sm icon-state">',
                                '<label class="switch">',
                                '<input type="checkbox" class="update-status" ' + (row.status ===
                                    '1' ? 'checked' : '') + '>',
                                '<span class="switch-state"></span>',
                                '</label>',
                                '</div>'
                            ].join("");
                        }
                    },
                    {
                        field: 'flat',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        // title: 'ACTIONS',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.operateEvents,
                        formatter: actionsFunction
                    }
                ]
            ]
        });
    }

    function actionsFunction(value, row, index) {
        return [
            '<ul class="nav-menus">',
                '<li class="language-nav">',
                    '<div class="translate_wrapper">',
                        '<div class="current_lang">',
                            '<div class="lang"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">EN </span>',
                            '</div>',
                        '</div>',
                        '<div class="more_lang">',
                             '<div class="lang selected" data-value="en"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">English<span> (US)</span></span></div>',
                            '<div class="lang" data-value="de"><i class="flag-icon flag-icon-de"></i><span class="lang-txt">Deutsch</span></div>',
                            '<div class="lang" data-value="es"><i class="flag-icon flag-icon-es"></i><span class="lang-txt">Español</span></div>',
                            '<div class="lang" data-value="fr"><i class="flag-icon flag-icon-fr"></i><span class="lang-txt">Français</span></div>',
                            '<div class="lang" data-value="pt"><i class="flag-icon flag-icon-pt"></i><span class="lang-txt">Português<span> (BR)</span></span></div>',
                            '<div class="lang" data-value="cn"><i class="flag-icon flag-icon-cn"></i><span class="lang-txt">简体中文</span></div>',
                            '<div class="lang" data-value="ae"><i class="flag-icon flag-icon-ae"></i><span class="lang-txt">لعربية <span> (ae)</span></span></div>',
                        '</div>',
                    '</div>',
                '</li>',
            '</ul>',






            // '<div class="dropdown icon-dropdown">',
            // '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            // '<i class="icon-more-alt"></i>',
            // '</button>',
            // '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            // // '<a class="dropdown-item btn-info" href="javascript:void(0)"><i class="fa fa-list text-info"></i> Info</a></a>',
            // '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            // '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            // '</div>',
            // '</div>',
        ].join("");
    }

    window.operateEvents = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-sk-tarif').modal('show');
            $('.modal-title').text('Form Edit SK Tarif');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="no_sk"]').val(row.no_sk);
            $('input[name="tgl_mulai"]').val(row.tgl_mulai);
            $('input[name="tgl_akhir"]').val(row.tgl_akhir);
            $('textarea[name="deskripsi"]').val(row.deskripsi);
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('tarif.sk-tarif.delete', ':id') }}";
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
                                $table.bootstrapTable('refresh');
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function() {
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }
</script>
