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

    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        var url = "{{ route('sdm.gaji.create') }}";
        var type = "POST";
        var forms = document.getElementsByClassName('form-gaji');
        var validation = Array.prototype.filter.call(forms, function (form) {
            if (!form.checkValidity()) {
                form.querySelector(".form-control:invalid").focus();
                event.preventDefault();
                event.stopPropagation();
            } else {
                $.ajax({
                    type: type,
                    url: url,
                    dataType: "json",
                    data: $('.form-gaji').serialize(),
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
                            $('#modal-gaji').modal('hide');
                            $tableGaji.bootstrapTable('refresh');
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
    $(function () {
        initTable();
    });


    // Table Gaji
    function initTable() {
        $tableGaji.bootstrapTable('destroy').bootstrapTable({
            height: 500,
            locale: 'en-US',
            search: true,
            showColumns: true,
            showPaginationSwitch: true,
            showToggle: false,
            showExport: false,
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
            url: "{{ route('sdm.gaji.view') }}",
            columns: [
                [
                    {
                        field: 'state',
                        checkbox: true,
                        align: 'center',
                        valign: 'middle'
                    },
                    {
                        field: 'nomor_pekerja',
                        sortable: true,
                    },
                    {
                        field: 'bulan',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsGaji,
                        formatter: actionsFunctionGaji
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
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
        'click .btn-delete': function (e, value, row, index) {
            var url = "{{ route('sdm.spd.delete', ':id') }}";
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
                            _token: "{{ csrf_token() }}",
                            no_surat: row.no_surat // menambah data
                        },
                        success: function (res, status, xhr) {
                            if (xhr.status == 200 && res.success == true) {
                                Alert('success', res.message);
                            } else {
                                Alert('warning', res.message);
                            }
                        }
                    }).done(function () {
                        $tableSpd.bootstrapTable('refresh');
                    });

                }
            })
        },
        'click .btn-print': function (e, value, row, index) {
            var url = "{{ route('sdm.spd.print', ':id') }}";
            url = url.replace(':id', row.id);
            window.open(url, '_blank');
        }
    }
</script>