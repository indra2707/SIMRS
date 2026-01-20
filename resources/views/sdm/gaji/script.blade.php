<script type="text/javascript">
    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-gaji"),
        allowClear: true
    });

    // Tabel
    var $tableGaji = $('#table_gaji');

    // Open Modal Gaji
    $(document).on('click', '.add-btn', function () {
        $('.form-gaji').removeClass('was-validated');
        $('#modal-gaji').modal('show');
        $('.modal-title').text('Form Tambah Slip Gaji');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="bulan"]').val('');
        $('#upload_gaji').val('');
    });

    // Save 
    $(document).on('click', '.save-btn', function (e) {
        e.preventDefault();
        const form = $('.form-gaji')[0];
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        let formData = new FormData(form);

        $.ajax({
            url: "{{ route('sdm.gaji.create') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // ✅ TAMBAHKAN INI
        },

            beforeSend: function () {
                $('.save-btn')
                    .html('<span class="spinner-border spinner-border-sm"></span>')
                    .prop('disabled', true);
            },

            success: function (res) {
                if (res.success) {
                    Alert('success', res.message);
                    $('#modal-gaji').modal('hide');
                    $tableGaji.bootstrapTable('refresh');
                } else {
                    Alert('warning', res.message);
                }
            },

            error: function (xhr) {
                Alert('error', xhr.responseJSON?.message ?? 'Gagal import file');
            },

            complete: function () {
                $('.save-btn')
                    .html('Simpan')
                    .prop('disabled', false);
            }
        });
    });



    // Hapus All (Bulk Delete)
    $(document).on('click', '.hapus-all-btn', function () {
        let rows = $tableGaji.bootstrapTable('getSelections');
        if (!rows.length) {
            Swal.fire('Peringatan', 'Pilih data terlebih dahulu', 'warning');
            return;
        }

        let ids = rows.map(r => r.id);

        Swal.fire({
            title: 'Yakin hapus data?',
            text: `${ids.length} data akan dihapus`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!'
        }).then(result => {
            if (result.isConfirmed) {
                $.post("{{ route('sdm.gaji.deleteMultiple') }}", {
                    ids: ids,
                    _token: "{{ csrf_token() }}",
                }).done(function (res, status, xhr) {
                    if (xhr.status == 200 && res.success == true) {
                        Alert('success', res.message);
                        $tableGaji.bootstrapTable('refresh');
                    } else {
                        Alert('warning', res.message);
                    }
                }).fail(function () {
                    Alert('error', 'Gagal menghapus data');
                });
            }
        });
    });

    // Enable/Disable Button Hapus All
    $tableGaji.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        $('.hapus-all-btn').prop(
            'disabled',
            !$tableGaji.bootstrapTable('getSelections').length
        );
    });

    // Page Load Event
    $(function () {
        initTable();
    });

    // Table Gaji
    function initTable() {
        $tableGaji.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            pagination: true,
            pageSize: 50,
            pageList: [10, 20, 35, 50, 100, 'all'],
            showRefresh: true,
            showColumns: true,
            showPaginationSwitch: true,
            fullscreen: true,
            minimumCountColumns: 2,
            icons: iconsFunction(),
            loadingTemplate: loadingTemplate,
            url: "{{ route('sdm.gaji.view') }}",
            columns: [
                {
                    field: 'state',
                    checkbox: true,
                    align: 'center',
                    valign: 'middle'
                },
                {
                    field: 'nomor_pekerja',
                    title: 'Nomor Pekerja',
                    sortable: true
                },
                {
                    field: 'bulan',
                    title: 'Bulan',
                    sortable: true
                },
                {
                    field: 'action',
                    title: 'Aksi',
                    align: 'center',
                    valign: 'middle',
                    clickToSelect: false,
                    events: window.eventsGaji,
                    formatter: actionsFunctionGaji
                }
            ],

            responseHandler: function (res) {
                return res;
            }
        });
    }

    function actionsFunctionGaji(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end">',
            '<a class="dropdown-item btn-print" href="javascript:void(0)"><i class="fa fa-print text-secondary"></i> Print</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsGaji = {
        'click .btn-print': function (e, value, row, index) {
            var url = "{{ route('sdm.spd.print', ':id') }}";
            url = url.replace(':id', row.id);
            window.open(url, '_blank');
        }
    }
</script>
