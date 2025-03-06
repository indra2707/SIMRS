<script type="text/javascript">
    var $table = $('#table_penjamin');

    // Open Modal
    $(document).on('click', '.add-btn', function() {
        $('.form-penjamin').removeClass('was-validated');
        $('#modal-penjamin').modal('show');
        $('.modal-title').text('Form Tambah Penjamin');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="kode"]').val('');
        $('input[name="nama"]').val('');
        $('input[name="email"]').val('');
        $('input[name="telpon"]').val('');
        $('input[name="margin"]').val('');
        $('textarea[name="alamat"]').val('');
        $('select[name="tarif"]').val('').trigger('change');
        $('select[name="coa"]').val('').trigger('change');
        $('input[name="status"]').prop('checked', true);

        $('input[name="rj_tindakan"]').val('');
        $('input[name="rj_konsultasi"]').val('');
        $('input[name="rj_alat"]').val('');
        $('input[name="rj_ok"]').val('');
        $('input[name="rj_cathlab"]').val('');
        $('input[name="rj_radiologi"]').val('');
        $('input[name="rj_lab"]').val('');
        $('input[name="rj_akomodasi"]').val('');
        $('input[name="rj_paket"]').val('');
        $('input[name="rj_obat"]').val('');

        $('input[name="ri_tindakan"]').val('');
        $('input[name="ri_konsultasi"]').val('');
        $('input[name="ri_alat"]').val('');
        $('input[name="ri_ok"]').val('');
        $('input[name="ri_cathlab"]').val('');
        $('input[name="ri_radiologi"]').val('');
        $('input[name="ri_lab"]').val('');
        $('input[name="ri_akomodasi"]').val('');
        $('input[name="ri_paket"]').val('');
        $('input[name="ri_obat"]').val('');
    });

    // Save
    $(document).on('click', '.save-btn', function() {
        var id = $('input[name="id"]').val();
        var kode = $('input[name="kode"]').val();
        if (id && kode) {
            var url = "{{ route('master-data.penjamin.update', ':id', ':kode') }}";
            url = url.replace(':id', id, ':kode', kode);
            var type = "PUT";
        } else {
            var url = "{{ route('master-data.penjamin.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-penjamin');
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
                    data: $('.form-penjamin').serialize(),
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
                            $('#modal-penjamin').modal('hide');
                            $table.bootstrapTable('refresh');
                        } else {
                            Alert('warning', res.message);
                            $('#modal-penjamin').modal('hide');
                        }
                        form.classList.remove('was-validated');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status == 400) {
                            Alert('error', xhr.responseJSON.message);
                        } else if (xhr.status == 500) {
                            Alert('info', "<strong>Configuration Error!</strong> Silahkan hubungi IT Rumah Sakit!");
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
            url: "{{ route('master-data.penjamin.view') }}",
            columns: [
                [
                    // {
                    //     width: '100%',
                    //     field: 'id',
                    //     sortable: true,
                    // },
                    {
                        title: 'Kode',
                        width: '150%',
                        field: 'kode',
                        sortable: true,
                    },
                    {
                        title: 'Kode COA',
                        width: '150%',
                        field: 'kode_coa',
                        sortable: true,
                    },
                    {
                        field: 'nama',
                        sortable: true,
                    },
                    {
                        width: '5%',
                        field: 'status',
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
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            // '<a class="dropdown-item btn-info" href="javascript:void(0)"><i class="fa fa-list text-info"></i> Info</a></a>',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a></a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a></a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.operateEvents = {
        'click .btn-edit': function(e, value, row, index) {
            $('#modal-penjamin').modal('show');
            $('.modal-title').text('Form Edit Penjamin');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="kode"]').val(row.kode);
            $('input[name="nama"]').val(row.nama);
            $('input[name="email"]').val(row.email);
            $('input[name="tarif"]').val(row.tarif);
            $('input[name="telpon"]').val(row.telpon);
            $('textarea[name="alamat"]').val(row.alamat);
            $('select[name="margin"]').val(row.margin).trigger('change');
            $('select[name="coa"]').val(row.coa).trigger('change');
            $('input[name="status"]').prop('checked', row.status === '1');

            // Get Detail Diskon
            var url = "{{ route('master-data.penjamin.detail-diskon', ':id') }}";
            url = url.replace(':id', row.kode);
            $.ajax({
                type: 'GET',
                url: url,
                dataType: "json",
                success: function(res) {
                    var row_rajal = res.row_disc_rajal;
                    var row_ranap = res.row_disc_ranap;
                    // Rajal
                    $('input[name="rj_tindakan"]').val(row_rajal ? row_rajal.tindakan : '');
                    $('input[name="rj_konsultasi"]').val(row_rajal ? row_rajal.konsultasi : '');
                    $('input[name="rj_alat"]').val(row_rajal ? row_rajal.sewa_alat : '');
                    $('input[name="rj_ok"]').val(row_rajal ? row_rajal.ok : '');
                    $('input[name="rj_cathlab"]').val(row_rajal ? row_rajal.cathlab : '');
                    $('input[name="rj_radiologi"]').val(row_rajal ? row_rajal.radiologi : '');
                    $('input[name="rj_lab"]').val(row_rajal ? row_rajal.laboratorium : '');
                    $('input[name="rj_akomodasi"]').val(row_rajal ? row_rajal.akomodasi : '');
                    $('input[name="rj_paket"]').val(row_rajal ? row_rajal.paket : '');
                    $('input[name="rj_obat"]').val(row_rajal ? row_rajal.obat : '');
                    // Ranap
                    $('input[name="ri_tindakan"]').val(row_ranap ? row_ranap.tindakan : '');
                    $('input[name="ri_konsultasi"]').val(row_ranap ? row_ranap.konsultasi : '');
                    $('input[name="ri_alat"]').val(row_ranap ? row_ranap.sewa_alat : '');
                    $('input[name="ri_ok"]').val(row_ranap ? row_ranap.ok : '');
                    $('input[name="ri_cathlab"]').val(row_ranap ? row_ranap.cathlab : '');
                    $('input[name="ri_radiologi"]').val(row_ranap ? row_ranap.radiologi : '');
                    $('input[name="ri_lab"]').val(row_ranap ? row_ranap.laboratorium : '');
                    $('input[name="ri_akomodasi"]').val(row_ranap ? row_ranap.akomodasi : '');
                    $('input[name="ri_paket"]').val(row_ranap ? row_ranap.paket : '');
                    $('input[name="ri_obat"]').val(row_ranap ? row_ranap.obat : '');
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
        },
        'click .btn-delete': function(e, value, row, index) {
            var url = "{{ route('master-data.penjamin.delete', ':id') }}";
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
                            kode: row.kode,
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
                        $table.bootstrapTable('refresh');
                    });

                }
            })
        }
    }

    // Window operateChange Status
    window.operateChange = {
        'click .update-status': function(e, value, row, index) {
            var url = "{{ route('master-data.penjamin.update-status', ':id') }}";
            url = url.replace(':id', row.id);
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    status: e.target.checked ? 1 : 0,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                    } else {
                        Alert('warning', res.message);
                    }
                    $table.bootstrapTable('refresh');
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
