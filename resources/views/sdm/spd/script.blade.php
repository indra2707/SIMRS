<script type="text/javascript">

    // With Placeholder
    $(".select2").select2({
        placeholder: "---- Pilih Salah Satu ----",
        theme: "bootstrap-5",
        dropdownParent: $("#modal-spd"),
        allowClear: true
    });

    // Show More Options
    function showMoreOption(divId, element) {
        document.getElementById(divId).style.display =
            element.value == 'Lebih Satu Orang' ? 'block' : 'none';
    }

    //date range picker
    $('.js-daterangepicker').datepicker({
        // language: 'id',
        dateFormat: 'dd/mm/yyyy',
        range: true,
        multipleDates: true,
        multipleDatesSeparator: ' - ',
        autoClose: true,
    });

    //btn btn-group
    $(document).on('click', '.biaya-group button', function () {
        $(this).addClass('active').attr('aria-pressed', true);
        $(this).siblings().removeClass('active').attr('aria-pressed', false);

        $('#biaya_ditanggung').val($(this).data('value'));
    });

    // Tabel
    var $tableSpd = $('#table_spd');

    // Open Modal spd
    $(document).on('click', '.add-btn', function () {
        $('.form-spd').removeClass('was-validated');
        $('#modal-spd').modal('show');
        $('.modal-title').text('Form Tambah SPD');
        $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
        $('input[name="id"]').val('');
        $('input[name="nama"]').val('');
        $('input[name="tanggal"]').val('');
        $('input[name="form_end_date"]').val('');
        $('input[name="hakcuti"]').val('');
        $('input[name="cutilalu"]').val('');
        $('input[name="jatuh_tempo"]').val('');
        $('input[name="panjar_cuti"]').val('');
        $('textarea[name="keterangan"]').val('');
        $('input[name="btn-group"]').val('');
        $('select[name="pegawai"]').val('').trigger('change');
        $('select[name="pelaksanaan"]').val('').trigger('change');
        $('select[name="id_vendor"]').val('').trigger('change');
        $('select[name="asal"]').val('').trigger('change');
        $('select[name="tujuan"]').val('').trigger('change');


        InitSelect2($("select[name='id_vendor']"), {
            url: "{{ route('get-select-vendor') }}",
            dropdownParent: $("#modal-spd")
        });


        //  RESET BTN-GROUP
        // $('.biaya-group button').removeClass('active').attr('aria-pressed', false);
        // $('#biaya_ditanggung').val('');
    });

    // Save Asset
    $(document).on('click', '.save-btn', function () {
        var id = $('input[name="id"]').val();
        if (id) {
            var url = "{{ route('sdm.spd.update', ':id') }}";
            url = url.replace(':id', id);
            var type = "PUT";
        } else {
            var url = "{{ route('sdm.spd.create') }}";
            var type = "POST";
        }
        var forms = document.getElementsByClassName('form-spd');
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
                    data: $('.form-spd').serialize(),
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
                            $('#modal-spd').modal('hide');
                            $tableSpd.bootstrapTable('refresh');
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


    // Table SPD
    function initTable() {
        $tableSpd.bootstrapTable('destroy').bootstrapTable({
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
            url: "{{ route('sdm.spd.view') }}",
            columns: [
                [
                    {
                        field: 'nama',
                        sortable: true,
                    },
                    {
                        width: '100%',
                        field: 'action',
                        align: 'center',
                        valign: 'middle',
                        sortable: true,
                        clickToSelect: false,
                        events: window.eventsSpd,
                        formatter: actionsFunctionSpd
                    }
                ]
            ],
            responseHandler: function (data) {
                return data;
            }
        });
    }


    function actionsFunctionSpd(value, row, index) {
        return [
            '<div class="dropdown icon-dropdown">',
            '<button class="btn dropdown-toggle" id="setings-menu" type="button" data-bs-toggle="dropdown" aria-expanded="false">',
            '<i class="icon-more-alt"></i>',
            '</button>',
            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="setings-menu" style="">',
            '<a class="dropdown-item btn-edit" href="javascript:void(0)"><i class="fa fa-edit text-primary"></i> Edit</a>',
            '<a class="dropdown-item btn-delete" href="javascript:void(0)"><i class="fa fa-trash text-danger"></i> Hapus</a>',
            '</div>',
            '</div>',
        ].join("");
    }

    // Handle events button actions
    window.eventsSpd = {
        'click .btn-edit': function (e, value, row, index) {
            $('#modal-spd').modal('show');
            $('.modal-title').text('Form Edit SPD');
            $('.save-btn').html('<span class="fa fa-check"></span> Simpan').removeAttr('disabled');
            $('input[name="id"]').val(row.id);
            $('input[name="nama"]').val(row.nama);
            $('input[name="status"]').prop('checked', row.status === '1');
        },
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
                            _token: "{{ csrf_token() }}"
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
        }
    }

</script>